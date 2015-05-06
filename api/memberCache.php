<?php
	
/*
 * 新浪SAE MemberCache缓存
 */
class MemberCache{
	
	/*
	 * 利用SAE MemberCache存储数据
	*/
	public function setMemberCache($key,$value){
		$mmc=memcache_init();
		if($mmc==false)
			echo false;
		else{
			memcache_set($mmc,$key,$value);
			return true;
		}
	}
	
	/*
	 * 获取SAE MemberCache存储数据
	*/
	public function getMemberCache($key){
		$mmc=memcache_init();
		if($mmc==false)
			return null;
		else{
			return memcache_get($mmc,$key);;
		}
	}
}
?>
