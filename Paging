声明(Statement)：此程序借鉴于慕课网讲师 BobWang(PHP开发工程师)编写,请勿抄袭，谢谢；URL：http://www.imooc.com/learn/419

Author:将BobWang的程序进行简单封装；

<?php

    class Paging{
        
        private $_page;                 //当前页码
        private $_pagesize = 4;         //每页显示的数据量
        private $_show_page = 5;        //显示的数字页码
        private $_localhost;            //数据库连接域名
        private $_user;                 //数据库用户
        private $_password;             //数据库密码
        private $_db_name;              //数据库名称
        private $_table;                //数据表表名
        
        
        public function __construct($pagesize,$page,$localhost,$user,$password,$dbname,$table){
            //对可变参数数据进行初始化
            $this->_pagesize  = $pagesize;
            $this->_page      = @$_GET['page'];      //初始化当前页码
            $this->_localhost = $localhost;
            $this->_user      = $user;
            $this->_password  = $password;
            $this->_db_name   = $dbname;
            $this->_table     = $table;
            $this->PlayPage();
        }
        
        private function PlayPage(){
            if ($this->_page < 1){
                $this->_page = 1;
            }elseif (is_int($this->_page)){
                $this->_page = 1;
            }
            
            //根据页码取出数据;
            $_mysqli = new mysqli($this->_localhost,$this->_user,$this->_password,$this->_db_name);
            if(!$_mysqli){
                exit(mysqli_connect_error());
            }
            
            $_mysqli->set_charset("set names utf8");
            $_sql = "select * from demo limit ".($this->_page - 1)*$this->_pagesize . ",$this->_pagesize ";
            $_result = $_mysqli->query($_sql);
            
            echo "<table border=1 cellspacing=0 width=10% align=center>";
            echo "<tr><th>NAME</th><th>DATA</th></tr>";
            while (!!$_rows = $_result->fetch_array()){
                echo "<tr>";
                echo "<td>{$_rows['id']}</td>";
                echo "<td>{$_rows['n_info']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            //获取总数据量
            $_total_sql = 'select count(*) from '.$this->_table;
            $_total_result = $_mysqli->query($_total_sql);
            while (!!$_total_rows = $_total_result->fetch_array()){
//                 提取分页的总数据量
                $_data = $_total_rows[0];
            }
            
            //总页数：总数目/每页的显示数量
            $_total_pages = ceil($_data/$this->_pagesize); 
            
            $_result->free();
            $_mysqli->close();
            
            //显示数据 + 分页条;$_SERVER['PHP_SELE']：访问本页   
            //$_page_banner为显示的页码数据
            
            //计算偏移量
            $_page_offset = ceil($this->_show_page-1)/2;
            
            if($this->_page > 1){
                @$_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=1'>首页</a>";
                $_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=".($this->_page-1)."'><上一页</a>";
            }else{
                @$_page_banner .= "<span class='disable'>首页</a></span>";
                $_page_banner .= "<span class='disable'>上一页</a></span>";
            }
            
            //初始化数据,数字页码
            $_start = 1;
            $_end = $_total_pages;
            if($_total_pages > $this->_show_page){
                if($this->_page > $_page_offset+1){              //当前页码>偏移量+1，就 加上省略号，体验友好
                    $_page_banner .= "..";
                }
                if($this->_page > $_page_offset){                 //此处意为：若当前页>偏移量（即已经脱离了最开始的那几页，例正在第三页>偏移量2，那么对start和end做处理），以便于后面的显示
                    $_start = $this->_page - $_page_offset;   //起始页为当前页-偏移量（这里为2）
                    $_end = $_total_pages > $this->_page+$_page_offset ? $this->_page+$_page_offset : $_total_pages;
                    //当总页数>当前页+偏移量，那么end赋给当前页+偏移量，否则end赋给总页数
                    //目的是为了，如果当前页（如5）+偏移量（如2）=7小于总页数，那么就显示完整的页码+..，否则直接显示最后一页
                }else{
                    //当前页码小于或等于偏移量，我们设置的偏移量为2，这里可看做：页码为1或者2的时候，开始页面都为1，
                    $_start = 1;
                    $_end = $_total_pages > $this->_show_page ? $this->_show_page : $_total_pages;
                    //如果总页码>设置的显示页码（即大于5），那么就显示最开始的5页，否则，显示所有页数（总页数）
                }
                if($this->_page + $_page_offset > $_total_pages){                     //当当前页+偏移量>总页码（即到了后面的页码数据量越来越接近最后，根本用不上偏移量）
                    $_start = $_start - ($this->_page + $_page_offset - $_end);   //这里的start从$_page>$_page_offset来得到，由此算法得到后几页的页码规律
                }
                
            }
            
            for($_i = $_start;$_i <= $_end;$_i ++){     //循环显示出页码数
                if($this->_page == $_i){
                    $_page_banner .= "<span class='current'>{$_i}</span>";
                }else{
                    $_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=".$_i."'>{$_i}</a>";
                }
             }
            
            //尾部的省略
            if($_total_pages > $this->_show_page && $_total_pages > $this->_page + $_page_offset){
                //当总页数大于展示页码（即还有其他的页码没有显示出来），且总页数大于当前页+偏移量（即除了当前页，还有未显示的页码）
                $_page_banner .= '..';
            }else{
                $_page_banner .= "<span class='disable'>尾页</a></span>";
                $_page_banner .= "<span class='disable'>下一页</a></span>";
            }
            
            if($this->_page < $_total_pages){
                $_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=".($this->_page+1)."'>下一页></a>";
                $_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=".($_total_pages)."'>尾页</a>";
            }
            $_page_banner .= "共{$_total_pages}页";
            $_page_banner .= "<form action='".$_SERVER['PHP_SELF']."' method='get'>";
            $_page_banner .= "第<input type='text' size='2' name='page' />页，";
            $_page_banner .= "<input type='submit' value='确定' />";
            $_page_banner .= "</form>";
            echo $_page_banner;
        }
    }
   
?>
