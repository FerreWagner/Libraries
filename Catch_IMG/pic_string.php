<!DOCTYPE>
<html>
<head>
<title>瀑布流</title>
<meta charset="utf8" />
</head>
<style type="text/css">
*{
	font-family:微软雅黑;
}
</style>
<body>
<form action="" method="post">
请输入站点页面,用于抓取图片：<input type="text" name="pic" /><br />
<p>友情提示</p>
风景图片：http://www.27270.com/word/fengjingsheying/ <br />
风景图片：http://www.tooopen.com/img/87.aspx <br />
素材公社：http://www.tooopen.com/    <br />
图片之家：http://www.tupianzj.com/   <br />
百度图片：http://image.baidu.com/    <br />
天堂图片：http://www.ivsky.com/  <br />
<input type="submit" name="send" value="提交" />
</form>

<?php   
//取得指定位址的內容，并储存至 $text  
if(@$_POST['send']){
    @$text=file_get_contents($_POST['pic']);
}
  
//取得所有img标签，并储存至二维数组 $match 中   
@preg_match_all('/<img[^>]*>/i', $text, $match);   
  
//取出img
$_img = array();
foreach ($match as $_k => $_v){
    $_img = $_v;
}

//清洗不拥有链接的图片
$_http_img = array();
foreach ($_img as $_k => $_v){
    if(strpos($_v, "http")){
        @$_http_img[] = $_v;
    }
}

//获得纯粹的URL
$_real_pic = array();
for ($_i = 0;$_i < count($_http_img);$_i ++){
    $_first = strpos($_http_img[$_i], "src=") + 4;
    if(strpos($_http_img[$_i], 'jpg')){
        $_ext = 'jpg';
    }elseif (strpos($_http_img[$_i], 'gif')){
        $_ext = 'gif';
    }elseif (strpos($_http_img[$_i], 'png')){
        $_ext = 'png';
    }elseif (strpos($_http_img[$_i], 'jpeg')){
        $_ext = 'jpeg';
    }
    $_last       = strpos($_http_img[$_i], $_ext) + 4 - $_first;
    $_done       = $_last - $_first;
    $_real_pic[] = substr($_http_img[$_i], $_first,$_last);
}
// var_dump($_real_pic);
// print_r($_http_img);
?>
</body>
</html>
