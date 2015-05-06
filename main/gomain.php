<?php
/**
 * 通用助手类 
 * @author zt
 */
include_once 'contants/Contants.php';
include_once 'contants/ContantsXml.php';
include_once 'util/handle.php';
include_once 'api/face.php';
include_once 'menu.php';
include_once 'api/baiduAPP.php';
include_once 'counter/gomain_counterHandle.php';
include_once 'db/gomainDBOperate.php';
class GeneralHandle{
	
	var $TOKEN;
	
	function GeneralHandle($TOKEN){
		$this->TOKEN = $TOKEN;
	}
	
	/*
	 * 获取菜单
	 */
	public function getMenu(){
		Menu::getMenuList();
	}
	
	/*
	 * 微信认证
	 */
	public function checkSignature(){
		$signature = $_GET["signature"];  
        $timestamp = $_GET["timestamp"];  
        $nonce = $_GET["nonce"];  
        $token = $this->TOKEN;  
        $tmpArr = array($token, $timestamp, $nonce);  
        sort($tmpArr);  
        $tmpStr = implode( $tmpArr );  
        $tmpStr = sha1( $tmpStr );  
        if( $tmpStr == $signature ) {  
            return true;  
        } else {  
            return false;  
        } 
	}
	
	public function valid() {  
        $echoStr = $_GET["echostr"];  
        if($this->checkSignature()){  
            echo $echoStr;  
            exit;  
        }  
    } 
    
    public function responseMsg() {
    	//计数器加1
    	$wxGomainCounterHandler = new WxGomainCounterHandler();
    	$counter = $wxGomainCounterHandler->incrCounter();
 		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
 		if(!empty($postStr)){
 			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
 			GomainDBOperate::addUser($postObj->FromUserName,"");
 			//用户访问+1
 			GomainDBOperate::updateVisitCount($postObj->FromUserName);
 			$type = trim($postObj->MsgType);
 			switch ($type){
 				case "event":
 					$result = $this->eventHandle($postObj);
 					break;
 				case "text":
 					$result = $this->textHandle($postObj);
 					break;
 				case "image":
 					$result = $this->imageHandle($postObj);
 					break;
 				case "voice":
 					break;
 			}
 		}else{
 			echo "";
 			exit;
 		}
     
	}
	
	/*
	 * 文本事件
	 */
	private function textHandle($postObj){
        $keyword = trim($postObj->Content);
        //$this->keyWordValidForNumber($postObj,$keyword);
        $this->keyWordValid($postObj,$keyword);
        $time = time();
        $pattern = '/^[0-9]/';//以数字开头
        //if(preg_match($pattern,$keyword)){
        	//$this->chooseHandleForNum($postObj,$keyword);
       // }else{
        	$this->chooseHandle($postObj,$keyword);
       // }
        
	}
	
	/*
	 * 语音消息
	*/
	private function voiceHandle($postObj){
		
	}
	
	/*
	 * 图片消息
	 */
	private function imageHandle($postObj){
		$picUrl = trim($postObj->PicUrl);
		$faceApp = new FaceApp();
		if(!empty($picUrl)){
			$data = $faceApp->detectionExecute(Contants::$PUBLIC_FACE_DETECTION_DETECT_URL,$picUrl);
			if($data!=null){
				$faceCount = 0;
				$faceCounts = 0;
				$str="共检测到";
				foreach ($data['face'] as $face) {
					$faceCount++;
				}
				
				$str.= $faceCount."张人脸\n";
				$faceIds = array();
				if($faceCount!=0){
					foreach ($data['face'] as $face) {
						if($faceCount == 2){
							array_push($faceIds,$face['face_id']);
						}
						$faceCounts++;
						$faceAttribute = $face['attribute'];
						$faceAttribute_age = $faceAttribute['age'];
						$faceAttribute_gender = $faceAttribute['gender'];
						$sex = $faceAttribute_gender['value'];
						$faceAttribute_race = $faceAttribute['race'];//人种 Asian/White/Black
						$male = $faceAttribute_race['value'];
						if($sex == "Female"){
							$sex = "女";
						}else{
							$sex = "男";
						}
						
						$malePeople = "";
						if($male == "Asian"){
							$malePeople = "黄色人种";
						}else if($male == "White"){
							$malePeople = "白色人种";
						}else if($male == "Black"){
							$malePeople = "黑色人种";
						}
						$str.="人脸".$faceCounts."： $malePeople "."年龄：约".$faceAttribute_age['value']."岁，性别：".$sex."\n";
					}
					
					if($faceCount == 2){//对比相似度
						$data = $faceApp->recognitionCompare($faceIds[0],$faceIds[1]);
						$component_similarity = $data['component_similarity'];
						$eye = $component_similarity['eye'];//眼睛
						$mouth = $component_similarity['mouth'];//嘴
						$nose = $component_similarity['nose'];//鼻子
						$eyebrow = $component_similarity['eyebrow'];//眉毛
						$similarity = $data['similarity'];//两个face的相似性
						$str .= "近亲指数:".round($similarity)."\n";
						$str .= "相似的部位:\n";
						$str .= "眼睛:".round($eye)."\n";
						$str .= "嘴:".round($mouth)."\n";
						$str .= "鼻子:".round($nose)."\n";
						echo $this->responseText($postObj,$str);
					}
				}else{
					$str=Contants::$PUBLIC_FACE_NOTFOUND;
				}
				echo $this->responseText($postObj,$str);
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_FACE_ERRORMSG);
			}
		}
	}
	
	/*
	 * 根据数字选择功能
	 */
	private function chooseHandleForNum($postObj,$keyword){
		$key_begin = mb_substr($keyword,0,1,"utf-8");
		$key = mb_substr($keyword,1,strlen($keyword),"utf-8");
		$key = trim($key);
		if($key_begin == "1"){
			$weatherId = Handle::getWeatherByKey($key);
			if(!empty($weatherId)){
				echo $this->responseWeather($postObj,$weatherId,0,$key);
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_NOTFOUND);
			}
		}
		 
		else if($key_begin == "2"){//笑话
			if(!empty($key)){
				echo $this->responseText($postObj,Contants::$PUBLIC_JOKE_ERROR);
			}
			echo $this->jokeHandle($postObj);
		}
		 
		else if($key_begin == "3"){//点歌
			echo $this->responseMusic($postObj,$key);
		}
		
		else if($key_begin == "5"){//快递
			echo $this->responseExpress($postObj,$key);
		}
		
		else if($key_begin == "6"){
			if(!empty($key)){
				echo $this->responseText($postObj,Contants::$PUBLIC_JOKE_ERROR);
			}
			echo $this->responseText($postObj,Handle::getHistoryToday());
		}
	}
	
	/*
	 * 根据关键字选择功能
	 */
	private function chooseHandle($postObj,$keyword){
		if(stristr($keyword,Contants::$PUBLIC_JOKE) || stristr($keyword,Contants::$PUBLIC_JOKE_CHINESE)){//笑话
			$joke = stripos($keyword, Contants::$PUBLIC_JOKE);//查找字符串首次出现的位置（不区分大小写）
			$joke2 = stripos($keyword,Contants::$PUBLIC_JOKE_CHINESE);
			if(($joke !== false && $joke == 0) || ($joke2 !== false && $joke2 == 0)) {//笑话
				echo $this->jokeHandle($postObj);
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_JOKE_ERROR);
			}
		}
		
		else if(stristr($keyword, Contants::$PUBLIC_MUSIC)){//点歌
			$music = stripos($keyword, Contants::$PUBLIC_MUSIC);
			if($music !== false && $music == 0){//点歌
				$key = mb_substr($keyword,2,strlen($keyword),"utf-8");
				$key = trim($key);
				echo $this->responseMusic($postObj,$key);
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NUM_ERRORMSG3);
			}
		}
		
		else if(stristr($keyword, Contants::$PUBLIC_WEATHER)){//天气
			$weather = stripos($keyword,Contants::$PUBLIC_WEATHER);
			if($weather !== false){
				$len = Handle::utf8_strlen($keyword);//获取长度
				$key = mb_substr($keyword,Handle::utf8_strlen($keyword)-2,Handle::utf8_strlen($keyword),"utf-8");
				if($key == Contants::$PUBLIC_WEATHER){
					$key = mb_substr($keyword,0,Handle::utf8_strlen($keyword)-2,"utf-8");
					$key = trim($key);
					$weatherId = Handle::getWeatherByKey($key);
					if(!empty($weatherId)){
						echo $this->responseWeather($postObj,$weatherId,0,$key);
					}else{
						echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_NOTFOUND);
					}
				}else{
					echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_ERRORMSG);
				}
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_ERRORMSG);
			}
		}
		
		else if(stristr($keyword, Contants::$PUBLIC_TRANSLATE_EX)){//翻译
			$translate = stripos($keyword, Contants::$PUBLIC_TRANSLATE_EX);
			if($translate !== false && $translate == 0){//翻译
				$key = mb_substr($keyword,2,strlen($keyword),"utf-8");
				$key = trim($key);
				echo $this->responseTranslate($postObj,$key);
			}else{
				echo $this->responseText($postObj,Contants::$PUBLIC_TRANSLATE_MESSAGE);
			}
		}
		
		else if($keyword == "时间" || $keyword == "time"){
			$content = date("Y-m-d H:i:s",time());
			$result = $this->responseText($postObj,$content);
			echo $result;
		}else{
			echo $this->responseText($postObj,$this->getPublicMessage());
			//echo Handle::getAutoMessage($keyword);
			//echo $this->responseText($postObj,"http://wxmain.sinaapp.com/translate/translate_index.php");
		}
	}
	
	
	/*
	 * 笑话
	 */
	private function jokeHandle($postObj){
		/*$url = Contants::$PUBLIC_JOKE_URL;
		$output = file_get_contents($url);
		$str = json_decode($output,true);
		if(is_array($str)){
			$result = $this->transmitNews($postObj,$str);
		}else{
			$result = $this->responseText($postObj,$str);
		}*/
		$content = Handle::getJokeHandle();
		echo $this->responseText($postObj,$content);
	}
	
	/*
	 * 校验关键字
	 */
	private function keyWordValid($postObj,$keyword){
		switch ($keyword){
			case Contants::$PUBLIC_MUSIC://点歌
				echo $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NUM_ERRORMSG2);
				break;
			case Contants::$PUBLIC_WEATHER://天气
				echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_ERRORMSG);
				break;
			case Contants::$PUBLIC_FACE:
			case Contants::$PUBLIC_FACE_EX:
			case Contants::$PUBLIC_FACE_CX:
				echo $this->responseText($postObj,Contants::$PUBLIC_FACE_MESSAGE);
				break;
			case Contants::$PUBLIC_TRANSLATE:
			case Contants::$PUBLIC_TRANSLATE_EX:
			case Contants::$PUBLIC_TRANSLATE_EX2:
			case Contants::$PUBLIC_TRANSLATE_EX3:
			case Contants::$PUBLIC_TRANSLATE_EX4:
				echo $this->responseText($postObj,Contants::$PUBLIC_TRANSLATE_MESSAGE);
				break;
			default:
				break;
		}
	}
	
	/*
	 * 校验数字
	 */
	private function keyWordValidForNumber($postObj,$keyword){
		switch ($keyword){
			case "1"://天气
				echo $this->responseText($postObj,Contants::$PUBLIC_WEATHER_NUM_ERRORMSG);
				break;
			case "3"://点歌
				echo $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NUM_ERRORMSG3);
				break;
			case "5":
				echo $this->responseText($postObj,Contants::$PUBLIC_EXPRESS_ERRORMSG);
				break;
			default:
				break;
		}
	}
	
	/*
	 * 输出新闻
	 */
	private function transmitNews($postObj, $arr_item){
		
		if(!is_array($arr_item))
			return;
	
		$item_str = "";
		
		foreach ($arr_item as $item)
			$item_str .= sprintf(ContantsXml::$PUBLIC_ITEM_NEWS, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);	
	
		$resultStr = sprintf(ContantsXml::$PUBLIC_NEWS, $postObj->FromUserName, $postObj->ToUserName, time(), count($arr_item),$item_str);
		return $resultStr;
	}
	
	
	/*
	 * 消息事件
	 */
	private function eventHandle($postObj){
		$content = "";
		switch ($postObj->Event) {
			case "subscribe"://订阅/关注
				//存入数据库
				GomainDBOperate::addUser($postObj->FromUserName,"");
				echo $this->responseText($postObj,$this->getPublicMessage());
				break;
			case "unsubscribe":
				//关闭用户账号
				GomainDBOperate::setUserOff($postObj->FromUserName);
				break;
			case "CLICK":
				switch ($postObj->EventKey){
					case Contants::$PUBLIC_MENU_WEATHER://天气
						echo $this->responseWeatherHeader($postObj);
						break;
					case Contants::$PUBLIC_JOKE://笑话
						echo $this->jokeHandle($postObj);
						break;
					case Contants::$PUBLIC_MUSIC_EX://点歌
						//echo $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NUM_ERRORMSG3);
						echo $this->responseMusicHeader($postObj);
						break;
					case Contants::$PUBLIC_FACE://人脸识别
						//echo $this->responseText($postObj,Contants::$PUBLIC_FACE_MESSAGE);
						echo $this->responseFace($postObj);
						break;
					case Contants::$PUBLIC_MENU_HISTORY://历史上的今天
						echo $this->responseText($postObj,Handle::getHistoryToday());
						break;
					case Contants::$PUBLIC_MENU_ABOUT://关于我们
						echo $this->responseText($postObj,$this->getPublicMessage());
						break;
					case Contants::$PUBLIC_MENU_ABOUT_WEATHER://关于天气
						echo $this->responseText($postObj,"天气的查询还可以使用如下方式:\n".Contants::$PUBLIC_WEATHER_ERRORMSG);
						break;
					case Contants::$PUBLIC_MENU_TRANSLATE://翻译
						//echo $this->responseText($postObj,Contants::$PUBLIC_TRANSLATE_MESSAGE);
						echo $this->responseTranslateHeader($postObj);
						break;
					case Contants::$PUBLIC_MENU_MOVIE://电影
						echo $this->responseMovie($postObj);
						break;
					case Contants::$PUBLIC_MENU_ASTRO://星座
						echo $this->responseAstro($postObj);
						break;
					case Contants::$PUBLIC_MENU_EXPRESS://快递
						echo $this->responseExpressHeader($postObj);
						break;
				}
				break;
			default:         	 
				break;
		}
		
       // $result = $this->responseText($postObj,$content);
	   // echo $result;
	}
	
	/*
	 * 输出电影
	 */
	private function responseMovie($postObj){
		$arr = Handle::getMovie();
		$movieArray = array();
		//$movieArray[] = array("Title"=>"\n正在上映电影\n\n", "Description"=>"", "PicUrl"=>"", "Url" =>"");
		
		$i = 0;
		foreach ($arr as $key=>$value){
			if($i<10){
				$title = $key;
				$movieArray[] = array("Title"=>$title, "Description"=>"", "PicUrl"=>$value, "Url" =>"");
			}
			$i++;
		}
			
		return $this->transmitNews($postObj,$movieArray);
		
	}
	
	
	/*
	 * 输出快递
	 */
	private function responseExpress($postObj,$key){
		$key = trim($key);
		$isBlank = stripos($key, " ");
		
		if($isBlank === false){//没有空格
			echo $this->responseText($postObj,Contants::$PUBLIC_EXPRESS_ERRORMSG);
		}
		
		$str = explode(" ",$key);
		$expressName = trim($str[0]);
		$expressCode = trim($str[1]);
		
		if(empty($expressCode)){
			echo $this->responseText($postObj,Contants::$PUBLIC_EXPRESS_ERRORMSG);
		}
		
		//根据快递公司名称查询快递公司代码
		$expressCompanyCode = Handle::getExpressCode($expressName);
		if(empty($expressCompanyCode)){
			echo $this->responseText($postObj,Contants::$PUBLIC_EXPRESS_NOTFOUND);
		}

		$url = sprintf(Contants::$PUBLIC_EXPRESS_URL,$expressCompanyCode,$expressCode);	
		echo $this->responseText($postObj,"请点击以下链接查看结果:\n".$url);
	}
	
	/*
	 * 输出音乐
	 */
	private function responseMusic($postObj,$key){
		//判断$key开头是不是包含@
		$key_begin = mb_substr($key,0,1,"utf-8");
		$key_end = mb_substr($key,1,strlen($key),"utf-8");
		$key_begin = trim($key_begin);
		$isBlank = stripos($key, " ");
		$url = "";
		
		$title = "";
		$author = "";
		if($key_begin == "@"){
			if(stripos($key_end, "@")){
				$str = explode($key_begin,$key_end);
				$title = trim($str[0]);
				$author = trim($str[1]);
			}else{
				$title = trim($key_end);//没有歌手
			}
		}else{
			//对作者和歌曲进行编码,解码函数为:urldecode()
			if($isBlank == 0){//没有空格,没有歌手
				$title = trim($key);//没有歌手
			}else{
				$str = explode(" ",$key);
				$title = trim($str[0]);
				$author = trim($str[1]);
			}
		}
		
		$result = "";
		if(!empty($title)){
			$title_encode = urlencode($title);
			if(!empty($author)){
				$author_encode = urlencode($author);
				$url = sprintf(Contants::$PUBLIC_MUSIC_URL_AUTHOR,$title_encode,$author_encode);
			}else{
				$url = sprintf(Contants::$PUBLIC_MUSIC_URL,$title_encode);
			}
			
			// 处理名称、作者中间的空格,空格会被编码成+号,"\\+", "%20"
			$url = str_replace("\\+","%20",$url);
			$simstr=file_get_contents($url);
			
			$menus=simplexml_load_string($simstr);
			$musicCount = $menus->count;
			
			if($musicCount > 0 ){
				$musicurl = "";
				//普通品质取url中的encode,decode
				//高品质取durl中的encode,decode
					
				//默认取高品质
				foreach($menus->durl as $itemobj)
				{
					$musicurl = $this->analyMusic($itemobj);
					break;
				}
					
				if(empty($musicurl)){//取普通品质
					foreach($menus->url as $itemobj)
					{
						$musicurl = $this->analyMusic($itemobj);
						break;
					}
				}
					
				if(!empty($musicurl)){
					$result= sprintf(ContantsXml::$PUBLIC_MUSIC, $postObj->FromUserName, $postObj->ToUserName,time(), $title." ".$author,$musicurl,$musicurl);
				}else{
					$result = $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NOTFOUND);
				}
			}else{
				$result = $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NOTFOUND);
			}
			
		}else{
			$result = $this->responseText($postObj,Contants::$PUBLIC_MUSIC_NUM_ERRORMSG2);
		}
		
		return $result;
	}
	
	/*
	 * 输出翻译
	 */
	private function responseTranslate($postObj,$keyword){
		//判断$key开头是不是包含@
		$key_begin = mb_substr($keyword,0,1,"utf-8");
		$key_end = mb_substr($keyword,1,strlen($keyword),"utf-8");
		$key_begin = trim($key_begin);
		
		$contant = "";
		$to = "";
		if($key_begin == "@"){
			if(stripos($key_end, "@")){
				$str = explode($key_begin,$key_end);
				$contant = trim($str[0]);
				$to = trim($str[1]);
			}else{
				$contant = trim($key_end);//没有歌手
			}
		}
		
		if(empty($contant)){
			return $this->responseText($postObj,Contants::$PUBLIC_JOKE_ERROR);
		}
		
		if(!empty($to)){
			//查找对应的语言代码
			$toCode = Handle::getCodyByLanguage($to);
			$str = BaiDuAPP::translate($contant,auto,$toCode);
			if(!empty($str)){
				return $this->responseText($postObj,$str);
			}
		}
		
		//echo $this->responseText($postObj,$contant.":".$to);
		$str_ = BaiDuAPP::translate($contant);
		if(!empty($str_)){
			return $this->responseText($postObj,$str_);
		}

		
	}
	
	/*
	 * 解析music
	 */
	private function analyMusic($itemobj){
		$encode = $itemobj->encode;
		//处理decode ,发现微信在处理音乐的时候有个问题，所以这里删除一个参数
		$decode = $itemobj->decode;
		$removedecode = end(explode('&', $decode));//分割 取最后一个
		if($removedecode<>"")
		{
			$removedecode="&".$removedecode;
		}
		$decode = str_replace($removedecode,"", $decode);
		$musicurl= str_replace(end(explode('/', $encode)),$decode,$encode);
		//&& strpos($encode,"baidu.com") && strpos($decode,".mp3")
		/*if(isset($encode) && isset($decode)){
		 $result = substr($encode,0,strripos($encode,'/') + 1).$decode;
		if(!strpos($result,"?") && !strpos($result,"xcode")){
		$musicurl = urldecode($result);
		break;
		}
		
		}*/
		return $musicurl;
	}
	

	/*
	 * 在线点歌
	*/
	private function responseMusicHeader($postObj){
		$musicArray[] = array("Title"=>"音乐可以改善你的性情、带走你的寂寞", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/music.jpg","url"=>"");
		$musicArray[] = array("Title"=>Contants::$PUBLIC_MUSIC_NUM_ERRORMSG4, "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/music2.jpg","url"=>"");
		return $this->transmitNews($postObj,$musicArray);
	}

	/*
	 * 在线翻译
	*/
	private function responseTranslateHeader($postObj){
		$musicArray[] = array("Title"=>Contants::$PUBLIC_TRANSLATE_MESSAGE2, "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/translate2.jpg","Url"=>"http://wxmain.sinaapp.com/translate/translate_index.php");
		$musicArray[] = array("Title"=>Contants::$PUBLIC_TRANSLATE_MESSAGE3, "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/translate3.jpg","Url"=>"http://wxmain.sinaapp.com/translate/translate_index.php");
		return $this->transmitNews($postObj,$musicArray);
	}
	
	
	/*
	 * 输出天气图片
	 */
	private function responseWeatherHeader($postObj){
		$weatherArray[] = array("Title"=>"播报实时天气", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/weather.jpg","Url" =>Contants::$PUBLIC_WEATHER_LOCAL_URL);
		return $this->transmitNews($postObj,$weatherArray);
	}
	
	/*
	 * 输出快递
	 */
	private function responseExpressHeader($postObj){
		$weatherArray[] = array("Title"=>"实时查询快递", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/express.jpg","Url" =>Contants::$PUBLIC_EXPRESS_Menu_URL);
		return $this->transmitNews($postObj,$weatherArray);
	}
	
	/*
	 * 输出天气
	 */
	private function responseWeather($postObj,$weatherId,$flag=0,$key){
		//$output = "";
		//$info = "";
		/*if($flag == 0){
			$url = sprintf(Contants::$PUBLIC_WEATHER_URL,$weatherId);
			$info = $this->getWeatherJSONData($url);
			$output = $this->getWeatherForDaily($postObj,$url,$info,$weatherId);
		}else{
			$url = sprintf(Contants::$PUBLIC_WEATHER_URL_SIX,$weatherId);
			$info = $this->getWeatherJSONData($url);
			$output = $this->getWeatherForSix($postObj,$url,$info);
		}*/
		
		//$out = $this->getWeather($postObj,$output,$info);
		$output = $this->getWeatherInfo($postObj,$weatherId,$key);
		
		if(count($output)> 0 ){
			return $this->transmitNews($postObj,$output);
		}else{
			return $this->responseText($postObj,Contants::$PUBLIC_WEATHER_ERROR);
		}
		
		/*if($info != null){
			return $this->transmitNews($postObj,$output);
		}else{
			return $this->responseText($postObj,Contants::$PUBLIC_WEATHER_ERROR);
		}*/
	}
	
	/*
	 * 通过解析中国天气网源码拼接成最终天气
	 */
	private function getWeatherInfo($postObj,$weatherId,$key){
		$weatherArray = array();
		$result = Handle::getWeather($weatherId);
		if(!empty($result)){
			$weatherArray[] = array("Title"=>"\n".$key."天气预报\n", "Description"=>"", "PicUrl"=>"", "Url" =>"");
			$weatherArray[] = array("Title"=>str_replace("%", "﹪", $result), "Description"=>"", "PicUrl"=>"", "Url" =>"");
		}
		return $weatherArray;
	}
	
	/*
	 * 获取实时天气
	 */
	private function getWeatherForDaily($postObj,$url,$info,$weatherId){
		if(!empty($info)){
			$weatherArray = array();
			$weatherArray[] = array("Title"=>$info['city']."天气预报", "Description"=>"", "PicUrl"=>"", "Url" =>"");
			if ((int)$cityCode < 101340000){
				$result = "实况 温度：".$info['temp']."℃ 湿度：".$info['SD']." 风速：".$info['WD'].$info['WSE']."级"." 空气质量：".Handle::getWRQK($weatherId);
				$weatherArray[] = array("Title"=>str_replace("%", "﹪", $result), "Description"=>"", "PicUrl"=>"", "Url" =>"");
			}
							
			return $weatherArray;
		}
		return null;
	}
	
	/*
	 * 获取六日天气
	 */
	private function getWeatherForSix($postObj,$url,$info){
		if(!empty($info)){
			$weatherArray = array();
			if (!empty($info['index_d'])){
				$weatherArray[] = array("Title" =>$info['index_d'], "Description" =>"", "PicUrl" =>"", "Url" =>"");
			}			
			return $weatherArray;
		}
		return null;
	}
	
	/*
	 * 获取数据
	 */
	private function getWeatherJSONData($url){
		$info = "";
		$output = Handle::httpRequest($url);
		if(!stristr($output,"Error:")){
			$weather = json_decode($output, true);
			$info = $weather['weatherinfo'];
		}
		return $info;
	}
	
	/*
	 * 组装成最终天气
	 */
	private function getWeather($postObj,$weatherArray,$info){
		$weekArray = array("日","一","二","三","四","五","六");
		$maxlength = Contants::$PUBLIC_WEATHER_DAYS;
		
		for ($i = 1; $i <= $maxlength; $i++) {
			$offset = strtotime("+".($i-1)." day");
			$subTitle = date("m月d日",$offset)." 周".$weekArray[date('w',$offset)]." ".$info['temp'.$i]." ".$info['weather'.$i]." ".$info['wind'.$i];
			$weatherArray[] = array("Title" =>$subTitle, "Description" =>"", "PicUrl" =>"http://discuz.comli.com/weixin/weather/"."d".sprintf("%02u",$info['img'.(($i *2)-1)]).".jpg", "Url" =>"");
		}
		return $weatherArray;
	}
	
	/*
	 * 人脸识别
	 */
	private function responseFace($postObj){
		$faceArray[] = array("Title"=>"自动识别出照片、视频流中的人脸身份", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/face.png","url"=>"");
		$faceArray[] = array("Title"=>Contants::$PUBLIC_FACE_MESSAGE2, "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/other/face2.jpg","url"=>"");
		return $this->transmitNews($postObj,$faceArray);
	}
	
	/*
	 * 输出星座
	*/
	private function responseAstro($postObj){
		$weixinId = $postObj->FromUserName;
		//查看用户是否设置了星座
		$arr=GomainDBOperate::findHandleByWeixinId($weixinId);
		if(!empty($arr)){
			$name = $arr['ASTRONAME'];
			$code = $arr['ASTROCODE'];
			$code = ucfirst($code);
			$random = rand(1, 5);//生成随机数
			$astroArray[] = array("Title"=>trim($name)."的蜜语", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/astro/astro_".$random."/".$code."_".$random.".jpg", "Url" =>Contants::$PUBLIC_MENU_ASTRO_DETAIL_URL."?vcode=".trim($code)."&svptod=".$weixinId);
		}else{
			$random = rand(1, 12);//生成随机数
			$astroArray[] = array("Title"=>"聆听星座的蜜语", "Description"=>"", "PicUrl"=>"http://wxmain.sinaapp.com/img/astro/bg/bg_".$random.".jpg", "Url" =>Contants::$PUBLIC_MENU_ASTRO_URL."?svptod=".$weixinId);
			//获取幸运星座
			$lucklyCode = Handle::getLucklyAstro();
			if(!empty($lucklyCode)){
				$cname = Handle::getCodeByConstellationName($lucklyCode);
				$names = explode("/",$cname);
				$astroArray[] = array("Title" =>"幸运星座：".$names[1], "Description" =>"幸运星座", "PicUrl" =>"http://wxmain.sinaapp.com/img/astro/astro_1/".$lucklyCode."_1.jpg", "Url" =>Contants::$PUBLIC_MENU_ASTRO_DETAIL_URL."?vcode=".trim($lucklyCode)."&svptod=".$weixinId);
			}
		}
		return $this->transmitNews($postObj,$astroArray);
		//return $this->responseText($postObj,Contants::$PUBLIC_MENU_ASTRO_URL."?svptod=".$weixinId);
	}
	
	/*
	 * 输出信息
	*/
	public function responseText($postObj, $content, $flag=0){
		$result = sprintf(ContantsXml::$PUBLIC_TEXT, $postObj->FromUserName, $postObj->ToUserName, time(),$content, $flag);
		return $result;
	}
	
	/*
	 * 获取订阅的信息
	*/
	private function getPublicMessage(){
		return "感谢关注【日常小助手】"."微信号：wx_gomain".
				//"\n"."我们为您提供一些常用的日常功能。".
				"\n"."您的满意是我们一生的追求...";
	}
	
}

?>
