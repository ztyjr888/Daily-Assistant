<?php

/*
 * 向管理员发邮件
 */
include_once 'gomain_counterHandle.php';
include_once '../db/gomainDBOperate.php';
class WxGomainMailHandler{
	
	var $USERNAME = "wxgomain@163.com";
	var $USERNAME_TO = "ztyjr88@163.com";
	var $PASSWORD = "hanqiyu88";
	
	var $mail;
	
	/*
	 * 构造函数
	*/
	function __construct(){
		$this->mail = $this->initMail();
	}
	
	/*
	 * 析构函数
	*/
	function __destruct() {
	
	}
	
	/*
	 * 初始化邮箱
	 */
	private function initMail(){
		try{
			$mail_ = new SaeMail();
			return $mail_;
		}catch(Exception $ex){
			die($ex->getMessage());
		}
		
		return null;
	}
	
	/*
	 * 快速发送邮件
	 */
	public function quickSendEmail($subject,$content){
		$ret = $this->mail->quickSend(
				$this->USERNAME_TO,
				$subject,
				$content, 
				$this->USERNAME,
				$this->PASSWORD
		);
		echo $ret;
		
		//发送失败时输出错误码和错误信息
		if ($ret === false)
			var_dump($this->mail->errno(), $this->mail->errmsg());
	}
	
	/*
	 * 重用email
	 */
	public function clearEmail(){
		$this->mail->clean();
	}
	
	/*
	 * 发送HTML格式得email
	 */
	public function sendEmail(){
		$wxGomainCounterHandler = new WxGomainCounterHandler();
		$counter = $wxGomainCounterHandler->getCounter();
		$content = "<span style=\"color: #E47916;font-weight: bold;padding-left: 20px;\">"."Gomain Counter:".$counter."</span>";
		$content .= $this->getUserCount();
		$subject = "Gomain Counter";
		$content_type = "HTML";
		$str = $this->htmlTemplate($content);
		echo $str;
		$options = $this->setOptions($subject,$str,$content_type);
		$this->mail->setOpt($options);
		$this->mail->send();
	}
	
	/*
	 * 邮件设置
	 */
	private function setOptions($subject,$content,$content_type=null){
		if(empty($content_type))
			$content_type = "TEXT";
		return array(
			"from"=>$this->USERNAME,
			"to"=>$this->USERNAME_TO,
			"cc"=>"",//抄送
			"smtp_host"=>"smtp.163.com",
			"smtp_port"=>"25",//default 25
			"smtp_username"=>$this->USERNAME,
			"smtp_password"=>$this->PASSWORD,
			"subject"=>$subject,
			"content"=>$content,
			"content_type"=>$content_type, //"TEXT"|"HTML",default TEXT
			"charset"=>"utf8",//default utf8
			"tls"=>"false",//default false
			"compress"=>"",// string 设置此参数后，SaeMail会将所有附件压缩成一个zip文件，此参数用来指定压缩后的文件名.
			"callback_url"=>""//SMTP发送失败时的回调地址，回调方式为post，postdata格式：timestamp=时间戳&from=from地址&to=to地址（如有多个to，则以,分割）
		);
	}
	
	/*
	 * 邮件模板
	 */
	public function htmlTemplate($content){
		$str  = "<center><div class = \"warp\" style=\"width: 600px;height: 400px;border:15px groove #178651;border-radius: 15px;margin-top: 20px;overflow-y: auto;overflow-x: hidden;\">";
		$str .= "<div style=\"text-align: left;margin-top: 10px;margin-left: 10px;\">";
		$str .= "<span style=\"color: rgb(18, 18, 236);font-weight: bold;font-size: 20px;\">Dear :</span>";
		//$str .= "<span style=\"margin-left: 20px;\">";
		//$str .= $this->USERNAME_TO;
		//$str .= "</span>";
		$str .= "</div>";
		
		$str .= "<div style=\"word-break: break-all;word-wrap: break-word;margin-top: 20px;margin-left: 50px;margin-right: 50px;color: #207EE4;font-size: 20px;text-align: left;\">".$content."</div>";
		$str .= "</div></center>";
		return $str;
	}
	
	/*
	 * 清空当日访问量
	 */
	public function clearUserVisitCount(){
		GomainDBOperate::clearUserVisitCount();
	}
	
	/*
	 * 获取用户访问量
	 */
	public function getUserCount(){
		$arr = GomainDBOperate::getUserCountGroupBy();
		$str = "<div style=\"border:2px solid #A298A8;margin-top: 10px;margin-bottom: 20px;padding-left: 20px;padding-right: 20px;\">";
		if(!empty($arr)){
			for($i = 0;$i<count($arr);$i++){
				$arr_ = $arr[$i];
				$weixinId   = $arr_['WEIXINID'];
				$weixinName   = $arr_['WEIXINNM'];
				$visitCount = $arr_['VISITCOUNT'];
				$totalCount = $arr_['TOTALCOUNT'];
				$createTime = $arr_['CREATETIME'];
				$deleteTime = $arr_['DELETETIME'];
				$ison = $arr_['ISON'];
				$ex = "";
				if($ison == 0){
					$ex = "可用";
				}else if($ison == 1){
					$ex = "不可用";
				}
				$s = "<div style=\"margin-top: 20px;margin-bottom: 20px;border-bottom: 1px dashed #E6A317;\">";
				$s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">用户ID:</span><span style=\"color: red;font-weight: 200;\">".$weixinId."</span><br/>";
			    $s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">用户名:</span><span style=\"color: red;font-weight: 200;\">".$weixinName."</span><br/>";
				$s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">今日访问量:</span><span style=\"color: red;font-weight: 200;\">".$visitCount."</span><br/>";
				$s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">总访问量:<span style=\"color: red;font-weight: 200;\">".$totalCount."</span><br/>";
			    $s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">账号是否可用:</span><span style=\"color: red;font-weight: 200;\">".$ex."</span><br/>";
			    $s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">账号创建时间:</span><span style=\"color: red;font-weight: 200;\">".$createTime."</span><br/>";
			    if($ison == 1){
			   	 	$s.= "<span style=\"color: rgb(72, 145, 24);font-weight: bold;margin-right: 10px;\">账号删除时间:</span><span style=\"color: red;font-weight: 200;\">".$deleteTime."</span><br/>";
			    }
			    $s.="</div>";
			    $str .=$s;
			}
		}
		
		$str .="</div>";
		return $str;
	}
	
	public function resetCounter(){
		$wxGomainCounterHandler = new WxGomainCounterHandler();
		$wxGomainCounterHandler->resetCounter();
	}
}

?>
