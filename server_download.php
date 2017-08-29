<?php

class download_inserver
{
  var $url;//远程文件地址 
  var $file_name = "hdwiki.zip";//下载来的文件名称 
  var $save_path = "./www.phpfensi.com";//下载到本地的文件路径 
  var $localfile;//下载到本地文件的路径和名称 
  var $warning;//警告信息 
  var $redown=0;//是否重新下载 

  /*初始化*/
  function seturl($url)
  {
    if(!empty($url))$this->url = $url;
  }
  function setfilename($file_name)
  {
    if(!empty($file_name))$this->file_name = $file_name;
  }
  function setsavepath($save_path)
  {
    if(!empty($save_path))$this->save_path = $save_path;
  }
  function setredown($redown)
  {
    if(!empty($redown))$this->redown = $redown;
  }
  function download_inserver($url, $redown = 0, $save_path = 0, $file_name = 0)
  {
    $this->seturl($url);
    $this->setfilename($file_name);
    $this->setsavepath($save_path);
    $this->setredown($redown);
    if(!file_exists($this->save_path))
    {
      $dir = explode("/",$this->save_path);
      foreach($dir as $p)
        mkdir($p);
    }
  }

  /* 检查url合法性函数 */
  function checkurl(){
    return preg_match("/^(http|ftp)(://)([a-za-z0-9-_]+[./]+[w-_/]+.*)+$/i", $this->url);
  }
  //下载文件到本地 
  function downloadfile()
  {
    //检测变量 
    $this->localfile = $this->save_path."/".$this->file_name;
    if($this->url == "" || $this->localfile == ""){
      $this->warning = "error: 变量设置错误.";
      return $this->warning;
    }
    if (!$this->checkurl()){
      $this->warning = "error: url ". $this->url ." 不合法.";
      return $this->warning;
    }
    if (file_exists($this->localfile)){
      if($this->redown)
      {
        unlink($this->localfile);
      }
      else
      {
        $this->warning = "warning: 升级文件 ". $this->localfile ." 已经存在！ 重新下载";
        return $this->warning;
        //exit("error: 本地文件 ". $this->localfile ." 已经存在,请删除或改名后重新运行本程序."); 
      }
    }
    //打开远程文件 
    $fp = fopen($this->url, "rb");
    if (!$fp){
      $this->warning = "error: 打开远程文件 ". $this->url ." 失败.";
      return $this->warning;
    }
    //打开本地文件 
    $sp = fopen($this->localfile, "wb");
    if (!$sp){
      $this->warning = "error: 打开本地文件 ". $this->localfile ." 失败.";
      return $this->warning;
    }
    //下载远程文件 
    //echo "正在下载远程文件，请等待"; 
    while (!feof($fp)){
      $tmpfile .= fread($fp, 1024);
      //echo strlen($tmpfile); 
    }
    //保存文件到本地 
    fwrite($sp, $tmpfile);
    fclose($fp);
    fclose($sp);

    if($this->redown)
      $this->warning = "success: 重新下载文件 ". $this->file_name ." 成功";
    else
      $this->warning = "success: 下载文件 ". $this->file_name ." 成功";

    return $this->warning;
  }
} 
