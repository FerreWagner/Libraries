<?php
    //判断当前目录是否存在在线上
	set_time_limit(0);
	function read()
	{
		$dir = './';
		$dh  = opendir($dir);
	    while(($file = readdir($dh)) !== false){
	        // 先要过滤掉当前目录'.'和上一级目录'..'
	        if($file == '.' || $file == '..') continue;
	        $r = read301('wo de url'.$file);
	        if(!$r){
	        	echo '<li>'.iconv('gbk','utf-8',$file).'</li>';
	        }
	    }
	}
	
	function read301($url)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
		// 不需要页面内容
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
		// 不直接输出
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 8_0 like Mac OS X) AppleWebKit/600.1.3 (KHTML, like Gecko) Version/8.0 Mobile/12A4345d Safari/600.1.4'));
		// 返回最后的Location
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_exec($ch);
	    $info = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);
	    curl_close($ch);
	    return $info == $url ? true : false;
	    
	}

	read();
?>