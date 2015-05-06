<?php

/*
 * 统计日常小助手每天的访问流量,发送邮件到邮箱
 */
class WxGomainCounterHandler{
	
	var $counter;
	
	var $counter_name;
	
	/*
	 * 构造函数
	 */
	function __construct(){
		$this->counter_name = "GomainCounter";
		$this->counter = $this->initCounter();
	}
	
	/*
	 * 析构函数
	*/
	function __destruct() {
		
	}
	
	/*
	 * 初始化计数器
	 */
	private function initCounter(){
		try{
			$counter_ = new SaeCounter();
			return $counter_;
		}catch(Exception $ex){
			die($ex->getMessage());
		}
		
		return null;
	}
	
	/*
	 * 获取计数器
	 */
	public function getCounter(){
		if(!empty($this->counter)){
			return $this->counter->get($this->counter_name);
		}
		return null;
	}
	
	/*
	 * 计数器+1
	 */
	public function incrCounter(){
		if(!empty($this->counter)){
			$this->counter->incr($this->counter_name);
		}
	}
	
	/*
	 * 计数器-1
	*/
	public function decrCounter(){
		if(!empty($this->counter)){
			$this->counter->decr($this->counter_name);
		}
	}
	
	/*
	 * 重置计数器的值为0
	 */
	public function resetCounter(){
		$this->counter->set($this->counter_name,0);
	}
	
}

?>
