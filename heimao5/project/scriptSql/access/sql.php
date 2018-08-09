<?php
namespace access;

final class sql{
	private $pdo;
	private $PDOStatement;
	private $config;	//用作备份的config配置
	
	public function __construct(array $config){
		$this->config = $config; //拷贝一份配置信息

		$this->pdo = database::single($config);
	}
	
	public function where(){
		//暂无内容
	}
	
	public function limit(){
		//暂无内容
	}
	
	public function table(){
		//暂无内容
	}
	
	public function select($sql = ''){

		if(!$this->ping()){
			$this->Again();
		} //检测是否还存在连接

		$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
		
		$this->PDOStatement = $this->pdo->query($sql); 
		if($this->PDOStatement == false){
			die('sql 错误 error sql ='. $sql .' 错误文件： '. __FILE__ . ' line '. __LINE__);
		}
		$this->PDOStatement->setFetchMode(\PDO::FETCH_ASSOC);
		$res = $this->PDOStatement->fetchAll();
		
		if($this->PDOStatement){
			$this->PDOStatement->closeCursor();
			unset($this->PDOStatement);
		}
		return $res;
		
	}
	
	public function count($sql = ''){

		if(!$this->ping()){
			$this->Again();
		} //检测是否还存在连接

		$this->PDOStatement = $this->pdo->query($sql);
		if($this->PDOStatement == false){
			die('sql 错误 error sql ='. $sql .' 错误文件： '. __FILE__ . ' line '. __LINE__);
		}
		$this->PDOStatement->setFetchMode(\PDO::FETCH_COLUMN,0);
		$res = $this->PDOStatement->fetch();
		
		if($this->PDOStatement){
			$this->PDOStatement->closeCursor();
			unset($this->PDOStatement);
		}
		
		return $res;
		
	}

	//检测是否存在连接
	public function ping(){
		try{
	        $this->pdo->getAttribute(\PDO::ATTR_SERVER_INFO);
	    } catch (\PDOException $e) {
	        if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
	            return false;
	        }
	    }
	    return true;
	}

	//重新连接数据库
	private function Again(){
		$this->pdo = null;
		$this->pdo = database::single($this->config);
	}
}

?>