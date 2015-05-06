<?php

/*
 * 连接SAE MySql
 */
class SaeMySql{
	
	var $mysql;
	
	/*
	 * 构造函数
	*/
	function init(){
		//$this->mysql = $this->initMySqlAuto();
	}
	
	/*
	 * 析构函数
	*/
	function __destruct() {
	
	}
	
	/*
	 * 初始化数据库
	 */
	public static function initMySqlAuto(){
		try{
			$mysql = new SaeMysql();
			$sql = "SELECT * FROM `WXGOMAIN_USER` LIMIT 10";
			$data = $mysql->getData( $sql );
			$name = strip_tags( $_REQUEST['WEIXINNM'] );
			$sql = "INSERT  INTO `WXGOMAIN_USER` ( `WEIXINID`, `WEIXINNM`, `CREATETIME`) VALUES ('111111' , 'xxxxxx' , NOW() ) ";
			$mysql->runSql( $sql );
			if( $mysql->errno() != 0 )
			{
			    die( "Error:" . $mysql->errmsg() );
			}
			
			$mysql->closeDb();
		}catch(Exception $ex){
			die($ex->getMessage());
		}
		
		return null;
	}
	
	private function initMySql(){
		try{
			$link= mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
			if($link)
			{
				mysql_select_db(SAE_MYSQL_DB,$link);
			}
			
			return $link;
		}catch(Exception $ex){
			die($ex->getMessage());
		}
		
		return null;
	}
	
	/*
	 * 执行SQL
	 */
	public function executeSql($sql){
		$this->mysql->runSql($sql);
	}
	
	/*
	 * 获取数据
	 */
	public function getData($sql){
		$this->mysql->getData($sql);
	}
	
	/*
	 * 获取属性
	 */
	public function getProp($propName){
		return strip_tags( $_REQUEST[$propName] );
	}
	
	/*
	 * 关闭数据库
	*/
	public function closeMysql(){
		$this->mysql->closeDb();
	}
}
?>
