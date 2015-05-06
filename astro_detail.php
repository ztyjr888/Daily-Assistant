<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    	<link type="text/css" href="css/astro/layout.css" rel="stylesheet">
    	<link type="text/css" href="css/astro/astro.css?v=5" rel="stylesheet">
    	<link type="text/css" href="css/astro/fortune.css" rel="stylesheet">
    	<script type="text/javascript" src="js/jquery-1.7.min.js"></script> 
    	<script type="text/javascript" src="js/base.js"></script>
    	<script type="text/javascript" src="js/astro.js"></script>
    </head>
<body>
<center>
	<div class = "header">
	    <a href="javascript:;" class="smart-return-btn" onclick="javascript:astro.goBack();">
	    	<span class="ui-btn-inner ui-btn-corner-all">
	    		<span class="ui-btn-text">返回</span>
	    	</span>
	    </a>
	    <br/>
    </div>
	<?php 
		$code = $_GET['vcode'];
		$weixinId = $_GET['svptod'];
		if(!empty($code)){
			include 'constellation/constellation.php';
			echo Constellation::getAstro(trim($code));
		}
	?>
</center>
</body>
</html>
