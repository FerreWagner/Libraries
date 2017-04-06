<?php 
class Page{
    /*
     * 返回值为一个数组，返回limit数据和html页码数据
     * 使用实例：
     * include_once 'perfect_page.php';
     * $_fate = new Page(这里是数据库的查询总量, 3, 'page',5);
     * $_da = $_fate->paging();
     * echo $_da['html'].'<br />';
     * 在数据的查询处添加上{$_da['limit']};
     * 按照此流程，即可方便使用此分页函数
     */
    private $_count;        //数据总量
    private $_page_size;    //每页显示多少条
    private $_page;         //分页的GET参数
    private $_button;       //要显示的页码数
    
    public function __construct($count,$page_size,$page,$button = 5){
        $this->_count = $count;
        $this->_page_size = $page_size;
        $this->_page = $page;
        $this->_button = $button;
    }
    
    public function paging(){
        //对URL里的page的合理性进行处理
        if(!isset($_GET[$this->_page]) || !is_numeric($_GET[$this->_page]) || $_GET[$this->_page] < 1){
            $_GET[$this->_page] = 1;
        }
        $_pagecount = ceil($this->_count/$this->_page_size);
        //对URL里超出最大页码量进行处理
        if($_GET[$this->_page] > $_pagecount){
            $_GET[$this->_page] = $_pagecount;
        }
        //对limit进行处理
        $_start = ($_GET[$this->_page] - 1) * $this->_page_size;
        $_limit = "limit {$_start},{$this->_page_size}";
        //灵活处理url
        $_current_url  = $_SERVER['REQUEST_URI'];
        $_arr_current  = parse_url($_current_url);  //将url拆分到数组里
        $_current_path = $_arr_current['path']; 
        $_url = '';
        //判断是否存在参数部分
        if(isset($_arr_current['query'])){          
            parse_str($_arr_current['query'],$_arr_query);
            //去掉page参数,为了将page参数放在末尾
            unset($_arr_query[$this->_page]);      
            if(empty($_arr_query)){
                $_url = "{$_current_path}?{$this->_page}=";
            }else{  //还存在其他参数,如：id=xxx
                $_other = http_build_query($_arr_query);
                $_url = "{$_current_path}?{$_other}&{$this->_page}=";
            }
        }else{
            $_url = "{$_current_path}?{$this->_page}=";
        }
        //处理页码数量的HTML
        $_html = array();
        if($this->_button >= $_pagecount){
            for($_i = 1;$_i <= $_pagecount; $_i ++){
                if($_GET[$this->_page] == $_i){
                    @$_html[$_i]= "<span>{$_i}<span>";
                }else{
                    @$_html[$_i]= "<a href='$_url{$_i}'>{$_i}</a>";
                }
            }
        }else{
            $_page_left      = floor(($this->_button - 1)/2);
            //起始页码号
            $_start_page     = $_GET[$this->_page] - $_page_left;   
            //结束页码数
            $_end_page       = $_start_page + $this->_button - 1;   
            //当跳到首页，强制设置页码为1，避免出现为0的页码
            if($_start_page  < 1){   
                $_start_page = 1;
            }
            //当下面的计算程序到结尾出现：尾部会多出几个多余的页数时会执行
            if($_end_page    > $_pagecount){   
                $_start_page = $_pagecount - ($this->_button - 1);
            }
            
            for ($_i = 0;$_i < $this->_button;$_i ++){
                if($_GET[$this->_page] == $_start_page){
                    @$_html[$_start_page]= "<span>{$_start_page}<span>";
                }else{
                    @$_html[$_start_page]= "<a href='{$_url}{$_start_page}'>{$_start_page}</a>";
                }
                $_start_page ++;
            }
            //判断存在几个按钮(即数组里有几个元素),少于2个按钮就不做省略号效果
            if(count($_html) >= 3){
                //将数组内部指向第一个单元
                reset($_html);              
                //取到第一个单元的下标
                $_key_first = key($_html);  
                //将数组内部指向最后一个单元
                end($_html);                
                $_key_end   = key($_html);
                //构造不为第一页时的省略号
                if($_key_first != 1){
                    //去掉数组的第一个元素,然后下标从0开始
                    array_shift($_html);    
                    array_unshift($_html, "<a href='{$_url}1'>1...</a>");
                }
                //构造不为最后一页时的省略号
                if($_key_end != $_pagecount){
                    //将最后一个元素弹出(出栈)
                    array_pop($_html);      
                    array_push($_html, "<a href='{$_url}{$_pagecount}'>...{$_pagecount}</a>"); //入栈
                }
            }
        }
        //在第一页前做上一页,array_unshift表示在最开始添加数据
        if($_GET[$this->_page] != 1){
            $_prev = $_GET[$this->_page] - 1;
            array_unshift($_html, "<a href='{$_url}{$_prev}'><< 上一页</a>");
        }
        //最后一页,array_push表示在数组最后添加数据
        if($_GET[$this->_page] != $_pagecount){
            $_next = $_GET[$this->_page] + 1;
            array_push($_html, "<a href='{$_url}{$_next}'>下一页 >></a>");
        }
        $_html = implode(' ', $_html);
        $_data = array(
            'limit' => $_limit,
            'html'  => $_html,
        );
        return $_data;
    }
}

?>
