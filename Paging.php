<style type="text/css">
*{
	text-align:center;
	font-family:微软雅黑;
	font-size:13px;
}
body{
	margin:40px 0 20px 0;
}
</style>

<?php
    //TIPS:位于代码69、70行的数据需要替换成您需要查询的数据库字段;另外，实例化此程序请先准备好$_field数组数据,为空则默认搜索所有数据;
    class Paging{
        
        private $_page;                 //当前页码
        private $_pagesize = 4;         //每页显示的数据量
        private $_show_page = 5;        //显示的数字页码
        private $_localhost;            //数据库连接域名
        private $_user;                 //数据库用户
        private $_password;             //数据库密码
        private $_db_name;              //数据库名称
        private $_table;                //数据表表名
        private $_field = array();      //需要搜索的数据
        
        
        public function __construct($pagesize,$page,$localhost,$user,$password,$dbname,$table,$field){
            //对可变参数数据进行初始化
            $this->_pagesize  = @$pagesize;
            $this->_page      = @$_GET['page'];      //初始化当前页码
            $this->_localhost = $localhost;
            $this->_user      = $user;
            $this->_password  = $password;
            $this->_db_name   = $dbname;
            $this->_table     = $table;
            $this->_field     = @$field;
            $this->PlayPage();
        }
        
        private function PlayPage(){
            //根据页码取出数据;
            $_mysqli = new mysqli($this->_localhost,$this->_user,$this->_password,$this->_db_name);
            if(!$_mysqli){
                exit(mysqli_connect_error());
            }
            
            $_mysqli->set_charset("set names utf8");
	    //设置页面page情况
            if ($this->_page < 1){
                $this->_page = 1;
            }elseif (is_int($this->_page)){
                $this->_page = 1;
            }
            
            //将需要搜索的数据进行处理，分隔成字符串
            if(isset($this->_field)){
                $this->_field = implode(',', $this->_field);
            }else{  
                //如果未设置搜索数据,那么默认为搜索所有数据
                $this->_field = '*';
            }
            $_sql    = "select ".$this->_field." from ".$this->_table." limit ".($this->_page - 1)*$this->_pagesize . ",$this->_pagesize ";
            $_result = $_mysqli->query($_sql);
            
            echo "<table border=1 cellspacing=0 width=10% align=center>";
            echo "<tr><th>NAME</th><th>DATA</th></tr>";
            while (!!$_rows = $_result->fetch_array()){
                echo "<tr>";
                echo "<td>{$_rows['title']}</td>";
                echo "<td>{$_rows['desc']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            //获取总数据量
            $_total_sql    = 'select count(*) from '.$this->_table;
            $_total_result = $_mysqli->query($_total_sql);
            while (!!$_total_rows = $_total_result->fetch_array()){
            	//提取分页的总数据量
                $_data = $_total_rows[0];
            }
            
            //总页数：总数目/每页的显示数量
            $_total_pages = ceil($_data/$this->_pagesize); 
            
            $_result->free();
            $_mysqli->close();
            
            //显示数据 + 分页条;
            //$_page_banner为显示的页码数据
            
            //计算偏移量
            $_page_offset = ceil($this->_show_page-1)/2;
            
            if($this->_page > 1){
                @$_page_banner .= "<a href='".$_SERVER['PHP_SELF']."?page=1'>首页</a>";
                $_page_banner  .= "<a href='".$_SERVER['PHP_SELF']."?page=".($this->_page-1)."'><上一页</a>";
            }elseif($this->_page < 1){
                @$_page_banner .= "<span class='disable'>首页</a></span>";
                $_page_banner  .= "<span class='disable'>上一页</a></span>";
            }elseif($this->_page > $_total_pages){
                @$_page_banner .= "<span class='disable'>首页</a></span>";
                $_page_banner  .= "<span class='disable'>上一页</a></span>";
            }
            
            //初始化数据,数字页码
	    //这里是初始化情况,也是$_total_pages<=$_show_page的情况
            $_start = 1;
            $_end   = $_total_pages;
	    //这里总页数>显示页面数量的情况
            if($_total_pages > $this->_show_page){
                if($this->_page > $_page_offset + 1){
                    $_page_banner .= "..";
                }
		//对脱离最开始偏移量的页数做处理
                if($this->_page > $_page_offset){
                    $_start = $this->_page - $_page_offset;
                    $_end   = $_total_pages > $this->_page+$_page_offset ? $this->_page+$_page_offset : $_total_pages;
                    //如果当前页（如5）+偏移量（如2）=7小于总页数，那么就显示完整的页码+..，否则直接显示最后一页
                }else{
		    //对未脱离偏移量的起始页码做处理
                    //当前页码小于或等于偏移量，我们设置的偏移量为2，这里可看做：页码为1或者2的时候，开始页面都为1，
                    $_start = 1;
                    $_end   = $_total_pages > $this->_show_page ? $this->_show_page : $_total_pages;
                    //如果总页码>设置的显示页码（即大于5），那么就显示最开始的5页，否则，显示所有页数（总页数）
                }
		//对临近尾页的页码做处理
                if($this->_page + $_page_offset > $_total_pages){
                    $_start = $_start - ($this->_page + $_page_offset - $_end);
		    //这里的start从$_page>$_page_offset来得到，这个算法重要的是$_page当前页码,可由规律得到
                }
                
            }
            //循环显示出页码数
            for($_i = $_start;$_i <= $_end;$_i ++){
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
            //banner末尾下一页和尾页的显示
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
