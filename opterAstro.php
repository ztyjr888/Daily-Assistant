<?php

$weixinId = $_POST["svptod"];
$name = $_POST["name"];
$code = $_POST["code"];
$arr = array();
$arr['msg'] = 'error';
if(!empty($weixinId) && !empty($code)){
	if(strcmp(trim($weixinId),"null") == 0){
		$arr['msg'] = 'errorUser';
	}else{
		include_once 'constellation/constellation.php';
		include_once 'db/gomainDBOperate.php';
		//$constellation_code = Constellation::getCodeByConstellationName($name);
		$handleArr = @GomainDBOperate::findHandleByWeixinId($weixinId);
		if(empty($handleArr)){
			@GomainDBOperate::addHandle($weixinId,$code,$name);
			$arr['msg'] = 'success';
		}else{//已设置过
			$userArr = @GomainDBOperate::queryUserBySql($weixinId);
			if(!empty($userArr)){
				$userArr_ = $userArr[0];
				$role = $userArr_['ROLE'];
				if($role == 0){//管理员,可修改
					$astro_code = $handleArr['ASTROCODE'];
					if(strcmp(trim($astro_code),trim($code)) == 0){//重复设置
						$arr['msg'] = 'setNo';
					}else{
						@GomainDBOperate::updateHandle($weixinId,$code,$name);
						$arr['msg'] = 'updateSuccess';
					}
				}else{
					$arr['msg'] = 'exists';
				}
			}else{
				$arr['msg'] = 'exists';
			}
		}
	}
}

echo json_encode($arr);
?>
