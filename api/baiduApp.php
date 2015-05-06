<?php

/**
 * 调用百度API(http://lbsyun.baidu.com/  ztzhangtao120)
 * @author zt
 *
 */
define("WAPK", "qHAIYhzssWtBH5x409LVfXLf");
class BaiDuAPP{
	
	
	/*
	 * 获取用户位置
	 */
	public function  getUserLocation($addx,$addy){
		$arr = array();
		$result = self::httpRequest("http://api.map.baidu.com/geocoder/v2/?ak=".WAPK."&callback=renderReverse&location={$addx},{$addy}&output=xml&pois=0");
		$data = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
		$result = self::json2array($data);
		$arr = $result["result"]["addressComponent"];
		$address = $result['result']['formatted_address'];
		array_push($arr,$address);
		return $arr;
	}
	
	/*
	 * 解析json为数组
	 */
	private function json2array($json) {
		if ($json) {
			foreach ((array)$json as $k=>$v) {
				$data[$k] = !is_string($v) ? $this->json2array($v) : $v;
			}
			return $data;
		}
	}
	
	/*
	 * 百度翻译
	 * 如:{"from":"jp","to":"zh","trans_result":[{"src":"\u308f\u305f\u3057\u306f\u3042\u306a\u305f\u3092\u3042\u3044\u3057\u3066\u3044\u307e\u3059","dst":"\u6211\u7231\u4f60"}]}
	 */
	public function translate($data,$from=auto,$to=auto){
		$data = urlencode($data);
		$url="http://openapi.baidu.com/public/2.0/bmt/translate?client_id=".WAPK."&q={$data}&from={$from}&to={$to}";
		$fanyi=self::httpRequest($url);
		$shuju=json_decode($fanyi);
		$result=$shuju->trans_result;
		return $result[0]->dst;
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
}
?>
