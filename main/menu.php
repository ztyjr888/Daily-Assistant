<?php

/**
 * 微信菜单,先获取access_token,再用access_token把数据传给服务器
 * access_token有效期为7200秒(2小时)
 * @author zt
 * @date 2014-08-15
 *
 */
require_once 'api/memberCache.php';
require_once 'util/handle.php';
class Menu{
	
	/*
	 * 获取access_token
	 */
	private function getAccessToken(){
		//查看缓存中是否有token
		$nowDate = strtotime("now");
		$cacheKey = MemberCache::getMemberCache("tokenCacheTime");
		$access_token= "";
		if(!empty($cacheKey)){
			$time_ = $nowDate - $cacheKey;
			if($time_ >= 7200){//缓存过期
				MemberCache::setMemberCache("tokenCacheTime","");
				MemberCache::setMemberCache("access_token","");
			}else{
				$access_token = MemberCache::getMemberCache("access_token");
				//echo "cache:".$access_token;
				return $access_token;
			}
		}
		
		$appid = "wxe428aa82e76d3508";
		$appsecre = "f0e6c04514e003f140838a8b24d149a2";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s";
		$url = sprintf($url,$appid,$appsecre);
		
		$accessTokenInfo = Handle::httpRequest($url);
		$jsoninfo = json_decode($accessTokenInfo, true);
		
		MemberCache::setMemberCache("tokenCacheTime",$nowDate);
		MemberCache::setMemberCache("access_token",$jsoninfo["access_token"]);
		
		return $jsoninfo["access_token"];
	}
	
	/*
	 * 获取菜单
	 */
	public function getMenuList(){
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s";
		$url = sprintf($url,self::getAccessToken());
		//echo $url;
		$menu = self::getPublicMenu();
		//echo $menu;
		$result = Handle::httpRequest($url, $menu);
		//var_dump($result);
		return $result;
	}
	
	/*
	 * 创建菜单
	 */
	private function getPublicMenu(){
		$menuInfo = '{
			      "button":[
			      {
			           "name":"日常功能",
			           "sub_button":[
			            {
			               "type":"click",
			               "name":"%s",
			               "key":"%s"
			            },
						{
			                "type":"click",
			                "name":"%s",
			                "key":"%s"
			            },
			            {
			               "type":"click",
			               "name":"%s",
			               "key":"%s"
			            },
						{
			               "type":"click",
			               "name":"%s",
			               "key":"%s"
			            },
						{
			               "type":"click",
			               "name":"%s",
			               "key":"%s"
			            }		            
			            ]
			          },
					  {
						"name":"趣味功能",
						"sub_button":[
							{
				               "type":"click",
				               "name":"%s",
				               "key":"%s"
				            },
				            {
				               "type":"click",
				               "name":"%s",
				               "key":"%s"
				            },
							{
				               "type":"click",
				               "name":"%s",
				               "key":"%s"
				            },
							{
				               "type":"click",
				               "name":"%s",
				               "key":"%s"
				            }
						]
					  },
			         {
			           "name":"帮助",
			           "sub_button":[
			            {
				 		   "type":"click",
			               "name":"%s",
			               "key":"%s"
						}
						]
			       }]
		   }';
		
		$menuInfo = sprintf($menuInfo,
				Contants::$PUBLIC_MENU_WEATHER_NAME,Contants::$PUBLIC_MENU_WEATHER,
				Contants::$PUBLIC_MENU_EXPRESS_NAME,Contants::$PUBLIC_MENU_EXPRESS,
				Contants::$PUBLIC_MENU_MOVIE_NAME,Contants::$PUBLIC_MENU_MOVIE,
 				Contants::$PUBLIC_MENU_JOKE_NAME,Contants::$PUBLIC_JOKE,
				Contants::$PUBLIC_MENU_HISTORY_NAME,Contants::$PUBLIC_MENU_HISTORY,
				Contants::$PUBLIC_MENU_FACE_NAME,Contants::$PUBLIC_FACE,
				Contants::$PUBLIC_MENU_MUSIC_NAME,Contants::$PUBLIC_MUSIC_EX,
				Contants::$PUBLIC_MENU_TRANSLATE_NAME,Contants::$PUBLIC_MENU_TRANSLATE,
				Contants::$PUBLIC_MENU_ASTRO_NAME,Contants::$PUBLIC_MENU_ASTRO,
				Contants::$PUBLIC_MENU_ABOUT_NAME,Contants::$PUBLIC_MENU_ABOUT
				);
		return $menuInfo;
	}
}
?>
