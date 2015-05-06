<?php

/*
 * 星座
*/
include_once 'array/constellation_code.php';
include_once 'util/handle.php';
include_once 'api/memberCache.php';
Class Constellation{
	
	/*
	 * 获取http://www.xingzuowu.com/网源码
	 */
	public function getConstellation($url,$constellation_code,$pattern){
		$str = Handle::httpRequest($url);
		$str = iconv("gb2312","utf8",$str);
		preg_match($pattern,$str,$match);
		return $match;
	}
	
	public function getAstro($constellation_code){
		//获取membercahce中的数据
		$yesDate = date("Ymd",strtotime("-1 day"));//获取前一天
		//清除前一天缓存
		MemberCache::setMemberCache($yesDate."_astro_".$constellation_code,"");
		
		$todate = date("Ymd",time());
		//获取今天缓存
		$todate_cache = MemberCache::getMemberCache($todate."_astro_".$constellation_code);
		if(!empty($todate_cache)){
			return $todate_cache;
		}
		
		//$constellation_code = self::getCodeByConstellationName($constellationName);
		if(empty($constellation_code)){
			return "";
		}
		$str1 = self::getAstroContent($constellation_code);
		$str2 = self::getAstroYunShiContent($constellation_code);
		
		//放入缓存
		MemberCache::setMemberCache($todate."_astro_".$constellation_code,$str1.$str2);
		return $str1.$str2;
	}
	
	/*
	 * 获取星座
	 */
	public function getAstroContent($constellation_code){
		$url = "http://www.xingzuowu.com/astro/%s/index.html";
		$url_ = sprintf($url,$constellation_code);
		//$pattern = '/(<dl class=\"amain fl\">)(.*?)(<\/dl>)/si';
		$pattern = '/<dl class=\"amain fl\">(.*?)<dt>(.*?)<\/dt>(.*?)<dd>(.*?)<ul>(.*?)<\/ul>(.*?)<\/dd>(.*?)<\/dl>/si';
		$str = self::getConstellation($url_,$constellation_code,$pattern);
		
		$lis = $str[5];
		
		$pattern_li = '/<li>(.*?)<\/li>/si';
		
		preg_match_all($pattern_li,$lis,$match_li);
		
		$lis_arry = $match_li[1];
		
		$ul_li = "";
		if(!empty($lis_arry)){
			for($i = 0;$i<count($lis_arry);$i++){
				$li_s = "<li>";
				$li_d = trim($lis_arry[$i]);
				$li_d = str_replace(' ','',$li_d);
				$li_d = str_replace('　','',$li_d);
				$li_e = "</li>";
				$li = $li_s.$li_d.$li_e;
				$ul_li .= $li;
			}
		}
		
		$dd = "<dd><ul>".$ul_li."</ul></dd>";
		
		
		$ul = "";
		
		$fl = "<div class=\"fl tbl\"></div>";
		$tbc = "<div class=\"fl tbc\"></div>";
		$string  =  "<div class=\"aitb mT10\">".$fl;
		$string .= "<dl class=\"amain fl\">";
		$string .= "<dt>";
		$string .= $str[2];
		$string .= "</dt>";
		$string .= $dd;
		$string .= "</dl>";
		$string .= $tbc."</div>";
		$string = preg_replace("<img src=\"(.*?)\" class=\"fl\">","img src=\"img/astro/astro_".ucfirst($constellation_code).".gif\" class=\"fl\"",$string);
		$string = preg_replace("/(<div class=\"burl\">)(.*?)(<\/div>)/is","",$string);
		return $string;
	}
	
	/*
	 * 获取星座运势
	 */
	public function getAstroYunShiContent($constellation_code){
		$url = "http://www.xingzuowu.com/fortune/%s/index.html";
		$constellation_code = lcfirst($constellation_code);
		$url_ = sprintf($url,$constellation_code);
		$div = "<div class=\"cb fl\">";
		$div_ = "</div>";
		$pattern = '/(<dl class=\"bmt\">)(.*?)(<\/dl>)/si';
		$str_array = self::getConstellation($url_,$constellation_code,$pattern);
		$str = $str_array[0];
		
		$pattern2 = '/(<div class=\"mcz\">)(.*?)(<\/div>)/si';
		$str2_array = self::getConstellation($url_,$constellation_code,$pattern2);
		$str2 = $str2_array[0];
		
		$pattern3 = '/(<div class=\"mcb\">)(.*?)(<\/div>)/si';
		$str3_array = self::getConstellation($url_,$constellation_code,$pattern3);
		$str3 = $str3_array[0];
		
		$str = $div.$str.$str2.$str3.$div_;
		
		$str = preg_replace("<img src=\"(.*?)\" alt=\"(.*?)\">","img src=\"img/astro/yunshi_".lcfirst($constellation_code).".gif\" class=\"fl\"",$str);
		$str = preg_replace("/<script.*?>(.*?)<\/script>/si","",$str);
		$str = preg_replace("/(<div class=\"chart\">)(.*?)(<\/div>)/is","",$str);
		return $str;
	}
	
	/*
	 * 根据星座名字查找星座代码
	 */
	public function getCodeByConstellationName($constellationName){
		global $constellation_code;
		$constellationCode = "";
		foreach ($constellation_code as $key=>$value){
			$flag = stripos($key,$constellationName);
			if($flag !== false){
				$constellationCode = $value;
			}
		}
		return $constellationCode;
	}
	
}
?>
