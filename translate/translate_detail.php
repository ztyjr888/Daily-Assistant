<?php 
include_once '../api/baiduAPP.php';
$fromCode = $_POST['fromCode'];
$toCode = $_POST['toCode'];
$contant = $_POST['content'];
$arr = array();
$arr['msg'] = "error";
if(!empty($fromCode) && !empty($toCode) && !empty($contant)){
	$str = BaiDuAPP::translate($contant,$fromCode,$toCode);
	if(!empty($str)){
		$arr['msg'] = "success";
		$arr['content'] = $str;
	}
}

echo json_encode($arr);
?>
