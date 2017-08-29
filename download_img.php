<?php
/**
 * 下载远程图片到本地
 *
 * @param string $url 远程文件地址
 * @param string $filename 保存后的文件名（为空时则为随机生成的文件名，否则为原文件名）
 * @param array $fileType 允许的文件类型
 * @param string $dirName 文件保存的路径（路径其余部分根据时间系统自动生成）
 * @param int $type 远程获取文件的方式
 * @return json 返回文件名、文件的保存路径
 * @author blog.snsgou.com
 */
function download_image($url, $fileName = '', $dirName, $fileType = array('jpg', 'gif', 'png'), $type = 1)
{
    if ($url == '')
    {
        return false;
    }

    // 获取文件原文件名
    $defaultFileName = basename($url);

    // 获取文件类型
    $suffix = substr(strrchr($url, '.'), 1);
    if (!in_array($suffix, $fileType))
    {
        return false;
    }

    // 设置保存后的文件名
    $fileName = $fileName == '' ? time() . rand(0, 9) . '.' . $suffix : $defaultFileName;

    // 获取远程文件资源
    if ($type)
    {
        $ch = curl_init();
        $timeout = 30;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file = curl_exec($ch);
        curl_close($ch);
    }
    else
    {
        ob_start();
        readfile($url);
        $file = ob_get_contents();
        ob_end_clean();
    }

    // 设置文件保存路径
    //$dirName = $dirName . '/' . date('Y', time()) . '/' . date('m', time()) . '/' . date('d', time());
    $dirName = $dirName . '/' . date('Ym', time());
    if (!file_exists($dirName))
    {
        mkdir($dirName, 0777, true);
    }

    // 保存文件
    $res = fopen($dirName . '/' . $fileName, 'a');
    fwrite($res, $file);
    fclose($res);

    return array(
        'fileName' => $fileName,
        'saveDir' => $dirName
    );
}
download_image("http://files.51aspx.com/aspxfiles/indexbanner/main_big_banner_wrpw2_636373448230461714.png", '', './');     
