<?php 
//     $_num = 627;1439,1509
//     $_num = rand(580,750);
    $_num = rand(1439,1509);
    $_url = "http://www.amisheng.com/news/show.php?itemid=$_num&modid=1";
    
    header("Location:$_url");
?>
