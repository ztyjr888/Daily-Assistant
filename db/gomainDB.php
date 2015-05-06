<?php
/**
 * 新浪SAE MySQL数据库文件
 * @author zt
 *
 */
class GomainDB {
	
	/*
	 * 获取表名
	 */
	function tbname($tb) {
		return $tb;
	}
	
	/*
	 * 初始化数据库
	*/
	function &in() {
		static $object;
		if(empty($object)) {
			$object = new SaeMysql();
		}
		return $object;
	}
	
	/*
	 * 插入
	*/
	function insert($tb , $arr,  $getinsertid = false, $replace = false) {
		$o = & self::in();
		$tb= self::tbname($tb);
		$data = self::getdata($arr);
		$cmd = $replace ? 'REPLACE INTO':'INSERT INTO';
		$silence = $silence ? 'SILENT':'';
		$query = "{$cmd} `{$tb}` SET {$data}";
		$return = $o->runSql($query);
		$id = "";
		if($getinsertid){
			$id = $o->lastId();
		}
		
		self::closeDB($o);
		return $id;
	}
	
	/*
	 * 修改
	*/
	function update($tb , $arr,  $terms = NULL , $getarows = false , $low_priority = false) {
		$o = & self::in();
		$tb= self::tbname($tb);
		$data = self::getdata($arr);
		$cmd = "UPDATE ".($low_priority ? 'LOW_PRIORITY' : '');
		$where = empty($terms) ? '1' : $terms;
		$query = "{$cmd} `{$tb}` SET {$data} WHERE {$where}";
		$return = $o->runSql($query);
		$rows;
		if($getarows){
			$rows = $o->affectedRows();
		}
		self::closeDB($o);
		return $rows;
	}
	
	/*
	 * 通过SQL查询
	 */
	function queryBySql($sql,$getarows = false){
		$o = & self::in();
		$tb= self::tbname($tb);
		//$return = $o->runSql($sql);
		$arr = $o->getData($sql);
		self::closeDB($o);
		if($getarows){
			return $arr;
		}
	}
	
	/*
	 * SQL
	*/
	function executeBySql($sql){
		$o = & self::in();
		$tb= self::tbname($tb);
		$return = $o->runSql($sql);
		self::closeDB($o);
	}
	
	/*
	 * 删除
	*/
	function delete($tb , $terms = NULL,$getarows = false, $limit = 0) {
		$o = & self::in();
		$tb = self::tbname($tb);
		$where = empty($terms) ? '1' : $terms;
		$query = "DELETE FROM `{$tb}` WHERE {$where} ".($limit ? "LIMIT {$limit}" : '');
		$return = $o->runSql($query);
		$rows;
		if($getarows){
			$rows = $o->affectedRows();
		}
		
		self::closeDB($o);
		return $rows;
	}
	
	/*
	 * 获取count
	*/
	function count ($tb , $fields = '*' , $terms = ''){
		$o = & self::in();
		$tb= self::tbname($tb);
		$where = empty($terms) ? '1' : $terms;
		$query = "select count({$fields}) from `{$tb}` where  {$where}";
		$count = $o->getVar($query);
		self::closeDB($o);
		return $count;
	}
	
	
   /*
    * 取得 多维数组
    */
	function fetchdata ($tb , $fields = '*' , $terms = ''){
		$o = & self::in();
		$tb= self::tbname($tb);
		$data = array();
		$query = "select {$fields} from `{$tb}` {$terms}";
		$arr = $o->getData($query);
		self::closeDB($o);
		return $arr;
	}
	
	/*
	 * 取得 单维数组
	*/
	function fetchrow ($tb , $fields = '*' , $terms = ''){
		$o = & self::in();
		$tb= self::tbname($tb);
		$data = array();
		$query = "select {$fields} from `{$tb}` {$terms}";
		$arr = $o->getLine($query);
		self::closeDB($o);
		return $arr;
	}
	
	/*
	 * 取得 单项值
	 */
	function fetchitem ($tb , $field , $terms = ''){
		$o = & self::in();
		$tb= self::tbname($tb);
		$data = array();
		$query = "select {$field} from `{$tb}` {$terms}";
		$item = $o->getVar($query);
		self::closeDB($o);
		return $item;
	}
	
	function affected_rows() {
		$o = & self::in();
		$arr = $o->affectedRows();
		self::closeDB($o);
		return $arr;
	}
	
	/*
	 * 查询 SQL
	 */
	function query($query) {
		$o = & self::in();
		$arr = $o->runSql($query);
		self::closeDB($o);
		return $arr;
	}
	
	/*
	 * 获取数据
	*/
	function getdata ($arr , $separator = ',') {
		$str = $s = '';
		foreach ($arr as $k => $v) {
			$str .= $s."`{$k}`='{$v}'";
			$s = $separator;
		}
		return $str;
	}
	
	/*
	 * 返回插入id
	*/
	function insert_id() {
		$o = & self::in();
		$id = $o->lastId();
		self::closeDB($o);
		return $id;
	}
	
	/*
	 * 关闭数据库
	*/
	function closeDB($o){
		if(!empty($o)){
			$o->closeDb();
		}
	}
}
?>
