<?php

/**
 * 通用方法类助手
 * @author zt
 *
 */
include_once 'array/weather_city.php';
include_once 'contants/Contants.php';
include_once 'array/express.php';
include_once 'api/memberCache.php';
include_once 'array/language.php';
include_once 'array/constellation_code.php';
include_once 'db/gomainDBOperate.php';
class Handle{
	
	function Handle(){
	}
	
	/*
	 * 抓取URL,并解析
	 */
	function httpRequest($url,$data = null)
	{
		$ch = curl_init();//初始化一个 cURL 对象
		curl_setopt($ch, CURLOPT_URL, $url);//设置需要抓取的URL
		//curl_setopt($ch, CURLOPT_HEADER, 1);// 设置header
		//curl_setopt($curl, CURLOPT_GET, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上
		$output = curl_exec($ch);//运行cURL，请求网页
		curl_close($ch);// 关闭URL请求
		if ($output === FALSE){
			return "cURL Error: ". curl_error($ch);
		}
		return $output;
	}
	
	/*
	 * 计算中英文字符串长度
	 */
	function utf8_strlen($string = null) {
		// 将字符串分解为单元
		preg_match_all("/./us", $string, $match);
		// 返回单元个数
		return count($match[0]);
	}
	
	/*
	 * 根据key获取值
	 */
	function getWeatherByKey($key){
		global $weather_city;
		return $weather_city[$key];
	}
	
	/*
	 * 根据名称查询语言代码
	 */
	function getCodyByLanguage($value_){
		global $language;
		$code = "";
		foreach ($language as $key=>$value){
			$flag = stripos($key,$value_);
			if($flag !== false){
				$code = $value;
			}
		}
		return $code;
	}
	
	/*
	 * 根据快递公司名称查询快递代码
	 */
	function getExpressCode($value_){
		global $express_company_code;
		$expressCompanyCode = "";
		foreach ($express_company_code as $key=>$value){
			$flag = stripos($value,$value_);
			if($flag !== false){
				$expressCompanyCode = $key;
			}
		}
		return $expressCompanyCode;
	}
	
	/*
	 * 根据key获取value 
	 */
	function getWeatherByValue($value){
		global $weather_city;
		if(is_array($weather_city)){
			return array_search($value,$weather_city);
		}
		return null;
	}
	
	/*
	 * 获取小黄鸡聊天代码
	*/
	public function getAutoMessage($data){
		$curlPost=array("txt"=>$data);
        $ch = curl_init();//初始化curl
        curl_setopt($ch,CURLOPT_URL,'http://www.xiaohuangji.com/ajax.php');//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
		//$str = self::httpRequest(Contants::$PUBLIC_XIAOHUANGJI_URL,$curlPost);
		//$error = stripos($str, "Error");
		//$content = "";
		//if($error===false){
			//$content = $str;
		//}
		//return $content;
	}
	
	/*
	 * 抓取中国天气网源码
	 */
	function getChinaWeatherSource($weatherId){
		//header("Content-Type:text/html;charset=utf-8");
		$pattern = '/<div[^>]*id="today"[^>]*>(.*?<li[^>]*data-dn="todayN"[^>]*>(.*?)<\/li>.*?)<\/div>/si';
		$url = sprintf(Contants::$PUBLIC_WEATHER_URL_NEW,$weatherId);
		$str = file_get_contents($url);
		//preg_match_all('/(<div class=\"m m1\" id=\"today\">.*?<\/div>)/',$str,$match);
		preg_match($pattern,$str,$match);
		return $match[0];
	}
	
	/*
	 * 解析源代码
	 */
	function getWeather($weatherId){
		//5分钟放一次缓存
		$yet5MinDate = strtotime("-5 minute");//获取前5分钟
		$nowDate = strtotime("now");
		$cacheKey = MemberCache::getMemberCache("weaCacheTime".$weatherId);
		if(!empty($cacheKey)){
			$time_ = $nowDate - $cacheKey;
			echo "time:".$time_;
			if($time_ > 300){//缓存过期
				MemberCache::setMemberCache("weaCacheTime".$weatherId,"");
				MemberCache::setMemberCache("weaInfo_".$weatherId,"");
			}else{
				echo "cache:".$time_;
				return MemberCache::getMemberCache("weaInfo_".$weatherId);
			}
		}
		
		$todate = date("Ymd",time());
		//获取今天缓存
		$todate_cache = MemberCache::getMemberCache($todate);
		
		//只抓取今天天气情况
		$match = self::getChinaWeatherSource($weatherId);
		$pattern = '/<div[^>]*id="today"[^>]*>(.*?)<\/div>/si';
		preg_match($pattern,$match,$match_today);
		
		//只抓取今天天气情况
		$pattern_ul = '/<ul.*?>(.*?)<\/ul>/si';
		preg_match($pattern_ul,$match,$match_ul);
		$match_today_str = $match_ul[1];
		
		$pattern_air_todayT = '/<li.*?>(.*?)<\/li>/si';
		preg_match_all($pattern_air_todayT,$match_today_str,$match_air_todayT);
		
		$match_today_wrqk = $match_air_todayT[0][0];//空气质量
		$match_today_daily = $match_air_todayT[0][1];//今日白天天气
		$match_today_night = $match_air_todayT[0][2];//今日夜间天气
		
		//空气质量
		$pattern_air_todayT_zl = '/<p class="air">(.*?)<span>(.*?)<\/span>(.*?)<\/p>/si';
		preg_match($pattern_air_todayT_zl,$match_today_wrqk,$match_air_todayT_zl);
		//获取空气质量数据 + 污染情况
		$airDataName = $match_air_todayT_zl[1];
		$airDataInfo = "";
		if(!empty($airDataName)){
			$airData = $match_air_todayT_zl[2];
			$airData_int = sprintf("%d", trim($airData));
			$level = self::setAirLevel($airData_int);
			$airDataInfo = trim($airDataName)."：".$airData.$level;
		}
		
		$todayT = self::getTodayInfo($match_today_daily);
		$todayN = self::getTodayInfo($match_today_night);
		$result = "";
		if(!empty($todayT)){
			$result.=$todayT."\n";
		}
		
		if(!empty($todayN)){
			$result.=$todayN."\n";
		}
		
		//放入缓存
		if(!empty($result)){
			$now = strtotime("now");
			MemberCache::setMemberCache("weaCacheTime".$weatherId,$now);
			MemberCache::setMemberCache("weaInfo_".$weatherId,$result);
		}
		
		return $result;
	}
	
	/*
	 * 获取今天白天或夜晚天气
	 */
	private function  getTodayInfo($match_today){
		$result = "";
		if(!empty($match_today)){
			//标题
			$pattern_air_todayT_title = '/<h1>(.*?)<\/h1>/i';
			preg_match($pattern_air_todayT_title,$match_today,$match_air_todayT_title);
			$todayT_arirTitle = $match_air_todayT_title[1];
			
			//天气情况
			$pattern_air_todayT_airInfo = '/<p class="wea">(.*?)<\/p>/i';
			preg_match($pattern_air_todayT_airInfo,$match_today,$match_air_todayT_airInfo);
			$todayT_airInfo = $match_air_todayT_airInfo[1];
			
			//温度
			$pattern_air_todayT_airTemp= '/<p class="tem">(.*?)<span>(.*?)<\/span>(.*?)<\/p>/i';
			preg_match($pattern_air_todayT_airTemp,$match_today,$match_air_todayT_airTemp);
			$todayT_airTemp = $match_air_todayT_airTemp[2];
			
			//风速
			$pattern_air_todayT_airWin= '/<span.+?title="(.+?)".*?>(.+?)<\/span>/i';
			preg_match($pattern_air_todayT_airWin,$match_today,$match_air_todayT_airWin);
			$todayT_airWinName = $match_air_todayT_airWin[1];
			$todayT_airWin = $match_air_todayT_airWin[2];
			
			//日升/日落
			$pattern_air_todayT_airSun= '/<p class="sunUp">(.*?)<\/p>/i';
			preg_match($pattern_air_todayT_airSun,$match_today,$pattern_air_todayT_airSun);
			$todayT_airSun = $pattern_air_todayT_airSun[1];
			
			if(!empty($airDataInfo)){
				$result = $todayT_arirTitle.":".trim($todayT_airInfo)." 温度：".trim($todayT_airTemp)."℃ "." 风速：".trim($todayT_airWinName)." ".trim($todayT_airWin);
			}else{
				$result = $todayT_arirTitle.":".trim($todayT_airInfo)." 温度：".trim($todayT_airTemp)."℃ "." 风速：".trim($todayT_airWinName)." ".trim($todayT_airWin)." ".$airDataInfo;
			}
		}
		return $result;
	}
	
	
	/*
	 * 设置空气污染级别
	 */
	private function setAirLevel($airData_int){
		$airData = "";
		if($airData_int <=50){
			$airData = "优秀";
		}
		
		if($airData_int >50 && $airData_int <=100){
			$airData = "良好";
		}
		
		if($airData_int >100 && $airData_int <=150){
			$airData = "轻度污染";
		}
		
		if($airData_int >150 && $airData_int <=200){
			$airData = "中度污染";
		}
		
		if($airData_int >200 && $airData_int <=300){
			$airData = "重度污染";
		}
		
		if($airData_int >300){
			$airData = "严重污染";
		}
		
		return $airData;
	}
	
	/*
	 * 抓取历史上的今天
	 */
	public function getHistoryToday(){
		//获取membercahce中的数据
		$yesDate = date("Ymd",strtotime("-1 day"));//获取前一天
		//清除前一天缓存
		MemberCache::setMemberCache($yesDate,"");
		
		$todate = date("Ymd",time());
		//获取今天缓存
		$todate_cache = MemberCache::getMemberCache($todate);
		if(!empty($todate_cache)){
			return $todate_cache;
		}
		
		$url = Contants::$PUBLIC_TODAY_URL;
		$str = file_get_contents($url);
		$pattern = '/(<div class=\"listren\">)(.*?)(<\/div>)/si';
		preg_match($pattern,$str,$match);
		$listren = $match[0];
		
		$str = "";
		
		if(empty($listren)){
			return $str;
		}
		
		$pattern_li_a = '/<a href=\"(.*?)\".*?>(.*?)<\/a>/i';
		preg_match_all($pattern_li_a,$listren,$match_a);
		
		if(count($match_a) <= 0){
			return $str;
		}
		
		$time = date("m月d日",time());
		$str .= "≡历史上的".$time."≡\n";
		foreach ($match_a[2] as $listren_li_a){
			$str .=$listren_li_a."\n";
		}
		
		//放入缓存
		MemberCache::setMemberCache($todate,$str);
		return $str;
	}
	
	/*
	 * 抓取美团电影源码(http://sh.meituan.com/shop/1469758?mtt=1.movie%2Fcinemalist.0.0.hz11k2kh)
	 */
	private function analyMeiTuanSource(){
		$url = Contants::$PUBLIC_MOVIE_URL;
		$str = self::httpRequest($url);
		
		$arr = array();
		if(empty($str)){
			return $arr;
		}
		
		$pattern_ul = '/(<ul class=\"reco-slides__slides\">)(.*?)(<\/ul>)/si';
		preg_match($pattern_ul,$str,$match_ul);
		
		$pattern_li = '/<li.*?>(.*?)<\/li>/si';
		preg_match_all($pattern_li,$match_ul[0],$match_li);
		
		if(empty($match_li)){
			return $arr;
		}
		
		$titles = array();
		$src = array();
		$pattern_a = '/(<a.*?title=\"(.*?)\".*?>(.*?)<img width=\"(.*?)\" height=\"(.*?)\" src=\"(.*?)\"[^>]*>(.*?)<\/a>)/si';
		$pattern_a_data = '/<a.*?title=\"(.*?)\".*?>(.*?)<img.*?data-src=\"(.*?)\">(.*?)<\/a>/si';
		
		$str_li = $match_li[0];
		
		preg_match_all($pattern_a,$str_li[0],$match_a);
		
		if(!empty($match_a)){
			//print_r($match_a);
			$pattern_img = '/<img width=\"(.*?)\" height=\"(.*?)\" src=\"(.*?)\">(.*?)/si';
			for($y = 0;$y<count($match_a[6]);$y++){
				preg_match_all($pattern_img,$match_a[6][$y],$match_img);
				if(!in_array(trim($match_a[2][$y]),$titles)){
					array_push($src,trim($match_a[6][$y]));
					array_push($titles,trim($match_a[2][$y]));
					$arr[trim($match_a[2][$y])]=trim($match_a[6][$y]);
				}
			}
		}

		for($i = 1;$i<count($str_li);$i++){
			$pattern_a_data = '/<a.*?title=\"(.*?)\".*?>(.*?)<img.*?data-src=\"(.*?)\">(.*?)<\/a>/si';
			preg_match_all('/<img.+data-src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+>/i',$str_li[$i],$matches);
			preg_match_all($pattern_a_data,$str_li[$i],$title);
			
			$x = 0;
			$array1 = array();
			$array2 = array();
			foreach($matches[1] as $matche){
				array_push($array1,trim($matche));
				
			}
			
			foreach ($title[1] as $t){
				array_push($array2,trim($t));
			}
			
			for($y = 0;$y<count($array2);$y++){
				if(!in_array(trim($array2[$y]),$titles)){
					array_push($src,trim($array1[$y]));
					array_push($titles,trim($array2[$y]));
					$arr[trim($array2[$y])]=trim($array1[$y]);
				}
			}
		}
		
		return $arr;
		
	}
	
	/*
	 * 获取电影
	 */
	public function getMovie(){
		
		//获取membercahce中的数据
		$yesDate = date("Ymd",strtotime("-1 day"));//获取前一天
		//清除前一天缓存
		MemberCache::setMemberCache($yesDate."movie","");
		
		$todate = date("Ymd",time());
		//获取今天缓存
		$todate_cache_movie = MemberCache::getMemberCache($todate."movie");
		if(!empty($todate_cache_movie)){
			return $todate_cache_movie;
		}
		
		$arr = self::analyMeiTuanSource();
		if(empty($arr)){
			return "";
		}
		
		//放入缓存
		MemberCache::setMemberCache($todate."movie",$arr);
		return $arr;
	}
	
	/*
	 * 根据星座代码查找星座日期
	*/
	public function getDateByConstellationCode($constellationCode){
		global $constellation_date;
		$constellationDate = "";
		foreach ($constellation_date as $key=>$value){
			$flag = stripos($key,$constellationCode);
			if($flag !== false){
				$constellationDate = $value;
			}
		}
		return $constellationDate;
	}
	
	/*
	 * 获取幸运星座
	 */
	public function getLucklyAstro(){
		global $constellation_date;
		
		$constellationCode = "";
		foreach ($constellation_date as $key=>$value){
			$dates = explode(",",$value);
			$date_begin = intval($dates[0]);
			$date_end = intval($dates[1]);
			//获取今天日期
			$date = date("md",time());
			$d = intval($date);
			if($d>=$date_begin && $d<=$date_end){
				$constellationCode = $key;
			}
		}
		
		return $constellationCode;
	}
	

	/*
	 * 根据星座代码查询星座名称
	*/
	function getCodeByConstellationName($constellationCode){
		global $constellation_code;
		$constellationName = "";
		foreach ($constellation_code as $key=>$value){
			$flag = stripos($value,$constellationCode);
			if($flag !== false){
				$constellationName = $key;
			}
		}
		return $constellationName;
	}
	
	/*
	 * 获取笑话
	 */
	function getJokeHandle(){
		$random = rand(1, 107);//生成随机数
		$jokes = MemberCache::getMemberCache("GOMAIN_JOKE");
		if(!empty($jokes)){
			return $jokes[$random];
		}
		
		$arr = GomainDBOperate::findJokeRand();
		MemberCache::setMemberCache("GOMAIN_JOKE",$jokeArray);
		
		$jokeArray = array();
		for($i = 0;$i<count($arr);$i++){
			$x = $arr[$i];
			$jokeArray[$x['ID']]=$x['CONTENT'];
		}
		
		return $jokeArray[$random];
	}
}

?>
