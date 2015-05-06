
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
<body>
<?php 
//include 'constellation/constellation.php';
//print_r(Constellation::getConstellation("天秤座"));
?>
<?php
/**
 * 日常功能助手
 * @author zt
 * @date 2014-08-05 22:46:21
 */
define("TOKEN", "gomain");

#定义web目录
#define('WEB_PATH','');
require_once 'main/gomain.php';

$generalHandle = new GeneralHandle(TOKEN);
$generalHandle->getMenu();
$generalHandle->responseMsg();

?>
</body>
</html>
