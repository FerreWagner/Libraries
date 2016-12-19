<?php
    class PdoMysql{
        
        public static $_config       = array();       //设置连接参数；配置信息；
        public static $_link         = null;          //保存连接标识符
        public static $_pconnect     = false;         //是否开启长链接
        public static $_dbversion    = null;          //保存数据库版本
        public static $_connected    = false;         //判断是否连接成狗
        public static $_PDOStatement = null;          //保存PDOStatement对象
        public static $_querystr     = null;          //保存最后执行的操作
        public static $_error        = null;          //保存错误信息
        public static $_lastInsertId = null;          //保存上一步插入操作产生AUTO_INCREMENT
        public static $_numRows      = 0;             //上一步操作产生受影响的记录的条数
        
        public function __construct($_dbconfig = ''){
            if(!class_exists("PDO")){
                self::throw_excpeion('不支持PDO,请先开启');
            }
            if(!is_array($_dbconfig)){
                $_dbconfig = array(
                    'hostname' => DB_NAME,
                    'username' => DB_USER,
                    'password' => DB_PWD,
                    'database' => DB_NAME,
                    'hostport' => DB_PORT,
                    'dbms'     =>DB_TYPE,
                    'dsn'      =>DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME
                );
                
            }
            if(empty($_dbconfig['hostname'])) self::throw_excpeion('没有定义数据库配置,请先定义');
            self::$_config = $_dbconfig;
            if(empty(self::$_config['params'])) self::$_config['params'] = array();       //在实例化的时候，设置数据库连接属性的东西
            //得没得到数据库连接对象，只保证不管用多少次只有一个对象的模式，即为单例模式；
            if(!isset(self::$_link)){
                $_configs = self::$_config;
                if(self::$_pconnect){
                    //开启长连接，添加到配置数组中
                    $_configs['params'][constant("PDO::ATTR_PERSISTENT")] = true;
                }
                try {
                    self::$_link = new PDO($_configs['dsn'],$_configs['username'],$_configs['password'],$_configs['params']);
                }catch (PDOException $_e){
                    self::throw_excpeion($_e->getMessage());
                }
                if(!self::$_link){
                    self::throw_excpeion('PDO连接错误');
                    return false;
                }
                self::$_link->exec('SET NAMES '.DB_CHARSET);
                self::$_dbversion = self::$_link->getAttribute(constant("PDO::ATTR_SERVER_VERSION"));
                self::$_connected = true;
                unset($_configs);
            }
        }
        
        //得到所有记录
        public static function getAll($_sql = null){
            if($_sql != null){
                self::query($_sql);
            }
            $_result = self::$_PDOStatement->fetchAll(constant("PDO::FETCH_ASSOC"));
            return $_result;
        }
        
        //得到结果集中的一条记录
        public static function getRow($_sql = null){
            if($_sql != null){
                self::query($_sql);
            }
            $_result = self::$_PDOStatement->fetch(constant("PDO::FETCH_ASSOC"));
            return $_result;
        }
        
        //根据主键查询相应记录
        public static function findById($tabName,$_priId,$_fields = '*'){
            $_sql = 'select %s from %s where id=%d';
            return self::getRow(sprintf($_sql,self::parseFields($_fields),$tabName,$_priId));
        }
        
        //普通查询：条件、分组、筛选、排序
        //执行普通查询
        public static function find($_tables,$_where = null,$_fields = '*',$_group = null,$_having = null,$_order = null,$_limit = null){
            $_sql = 'select '.self::parseFields($_fields).' from '.$_tables.' '.self::parseWhere($_where).self::parseGroup($_group).self::parseHaving($_having).self::parseOrder($_order).self::parseLimit($_limit);
            $_dataall = self::getAll($_sql);
            return count($_dataall) == 1 ? $_dataall[0] : $_dataall;
        }
//        array('username'=>'imooc','password'=>'imooc','email'=>'xx@xx.com','token'=>'xxx','token_exptime'=>'xxx','regtime'=>'xxx')
        //insert xxx
        //添加记录的操作
        public static function add($_data,$_table){
            $_keys = array_keys($_data);
            array_walk($_keys, array('PdoMysql','addSpecialChar'));
            $_fieldsStr= join(',', $_keys);
            $_values = "'".join("','", array_values($_data))."'";
            $_sql = "insert {$_table}({$_fieldsStr}) values({$_values})";
            
            return self::execute($_sql);
        }
        
        //更新记录
        public static function update($_data,$_table,$_where = null,$_order = null,$_limit = null){
            foreach ($_data as $_key=>$_value){
                @$_sets .= $_key."='".$_value."',";
            }
            $_sets = rtrim($_sets,',');
            $_sql = "update {$_table} set {$_sets} ".self::parseWhere($_where).self::parseOrder($_order).self::parseLimit($_limit);
//             echo $_sql;
            return self::execute($_sql);
        }
        
        //删除记录的操作
        public static function delete($_table,$_where = null,$_order = null,$_limit = 0){
            $_sql = "delete from {$_table} ".self::parseWhere($_where).self::parseOrder($_order).self::parseLimit($_limit);
            return self::execute($_sql);
        }
        //得到最后执行的SQL语句
        public static function getlastsql(){
            $_link = self::$_link;
            if(!$_link) return false;
            return self::$_querystr;
        }
        //得到上一步插入操作产生auto_increment的值；
        public static function getlastinsertid(){
            $_link = self::$_link;
            if(!$_link) return false;
            return self::$_lastInsertId;
        }
        //得到数据库的版本
        public static function getdbversion(){
            $_link = self::$_link;
            if(!$_link) return false;
            return self::$_dbversion;
        }
        //得到数据库中的数据表
        public static function showtables(){
            $_tables = array();
            if(self::query("show tables")){
                $_result = self::getAll();
                foreach ($_result as $_key=>$_value){
                    $_tables[$_key] = current($_value);
                }
            }
            return $_tables;
        }
        //解析where条件
        public static function parseWhere($_where){
            $_whereStr = '';
            if(is_string($_where) && !empty($_where)){
                $_whereStr = $_where;
            }
            return empty($_whereStr) ? '' : ' where '.$_whereStr;
        }
        //解析分组
        public static function parseGroup($_group){
            $_groupStr = '';
            if(is_array($_group)){
                $_groupStr .= ' group by'.implode(',', $_group);
            }elseif (is_string($_group) && !empty($_group)){
                $_groupStr .= ' group by '.$_group;
            }
            return empty($_groupStr) ? '' : $_groupStr;
        }
        //对分组结果通过having子句进行二次筛选
        public static function parseHaving($_having){
            $_havingStr = '';
            if(is_string($_having) && !empty($_having)){
                $_havingStr .= ' having '.$_having;
            }
            return $_havingStr;
        }
        //解析order by排序
        public static function parseOrder($_order){
            $_orderStr = '';
            if(is_array($_order)){
                $_orderStr .= ' order by '.join(',',$_order);
            }elseif (is_string($_order) && !empty($_order)){
                $_orderStr .= ' order by '.$_order;
            }
            return $_orderStr;
        }
        
        //解析字段
        public static function parseFields($_fields){
    		if(is_array($_fields)){
    			array_walk($_fields,array('PdoMySQL','addSpecialChar'));
    			$_fieldsStr=implode(',',$_fields);
    		}elseif(is_string($_fields)&&!empty($_fields)){
    			if(strpos($_fields,'`')===false){
    				$_fields=explode(',',$_fields);
    				array_walk($_fields,array('PdoMySQL','addSpecialChar'));
    				$_fieldsStr=implode(',',$_fields);
    			}else{
    				$_fieldsStr=$_fields;
    			}
    		}else{
    			$_fieldsStr='*';
    		}
    		return $_fieldsStr;
    	}
        //解析限制条数limit；例如limit 3或者limit 0,3
        public static function parseLimit($_limit){
            $_limitStr = '';
            if(is_array($_limit)){
                if(count($_limit) > 1){
                    $_limitStr .= ' limit '.$_limit[0].','.$_limit[1];
                }else{
                    $_limitStr .= ' limit '.$_limit[0];
                }
            }elseif (is_string($_limit) && !empty($_limit)){
                $_limitStr .= ' limit '.$_limit;
            }
            return $_limitStr;
        }
        
//         通过反引号引用字段，防止你使用的字段与mysql预保留的关键字冲突产生错误
        public static function addSpecialChar(&$_value){
            if($_value === '*' || strpos($_value, '.') !== false || strpos($_value, '`') !== false){
                //不用做处理
            }elseif (strpos($_value, '`') === false){
                $_value = '`'.trim($_value).'`';
            }
            return $_value;
        }
        
        //执行增删改操作，返回受影响的记录的条数
        public static function execute($_sql = null){
            $_link = self::$_link;
            if(!$_link) return false;
            self::$_querystr = $_sql;
            if(!empty(self::$_PDOStatement)) self::free();
            $_result = $_link->exec(self::$_querystr);
            self::haveErrorThrowExcption();
            if($_result){
                self::$_lastInsertId = $_link->lastInsertId();
                self::$_numRows = $_result;
                return self::$_numRows;
            }else{
                return false;
            }
        }
        
        //释放结果集
        public static function free(){
            self::$_PDOStatement = null;
        }
        
        public static function query($_sql = ''){
            $_link = self::$_link;
            if(!$_link) return false;
            //判断之前是否有结果集，如果有的话，释放结果集
            if(!empty(self::$_PDOStatement)) self::free();
            self::$_querystr = $_sql;
            self::$_PDOStatement = $_link->prepare(self::$_querystr);
            $_res = self::$_PDOStatement->execute();
            self::haveErrorThrowExcption();
            return $_res;
        }
        
        public static function haveErrorThrowExcption(){
            $_obj = empty(self::$_PDOStatement) ? self::$_link : self::$_PDOStatement;
            $_arrError = $_arrError = $_obj->errorInfo();
//             print_r($_arrError);
            if($_arrError[0] != '00000'){
                self::$_error = 'SQLSTATE：'.$_arrError[0].'<br />SQL Error：'.$_arrError[2].'<br />Error SQL：'.self::$_querystr;
                self::throw_excpeion(self::$_error);
                return false;
            }
            if(self::$_querystr == ''){
                self::throw_excpeion('没有执行SQL语句');
                return false;
            }
        }
        
        /*
         * 自定义错误处理
         */
        public static function throw_excpeion($_error){
            echo '<div style="width=80%;background-color:#ddd;color:#982391;font-size:20px;">';
            echo $_error;
            echo '</div>';
        }
        //销毁连接对象.关闭数据库
        public static function close(){
            self::$_link = null;
        }
    }
    
    
    require 'config.php';
    $_pdoo = new PdoMysql();
