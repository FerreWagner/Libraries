<?php
	require_once 'pic_string.php';
?>

<!DOCTYPE>
<html>
<head>
<title>瀑布流布局</title>
<meta charset="utf8" />
<link type="text/css" rel="stylesheet" href="css/style.css">
<script src="http://apps.bdimg.com/libs/jquery/1.11.1/jquery.js"></script>
<script src="js/script.js"></script>
</head>
<body>

<?php for($_i = 0;$_i < count($_real_pic); $_i ++){?>
<div id="main">
	<div class="box">
		<div class="pic">
			<img src=<?php echo $_real_pic[$_i];?> />
		</div>
	</div>
</div>
<?php }?>
</body>
</html>
