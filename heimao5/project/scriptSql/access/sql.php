<?php
namespace access;

final class sql{
	private $pdo;
	private $PDOStatement;
	private $config;	//用作备份的config配置

	//结构组织
	private $field;
	private $table;

	private $save = [];
	
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
	
	public function table($table)
	{
		$this->table = $table;
		return $this;
	}

	public function field($field)
	{
		$this->field = $field;
		return $this;
	}
	

	public function save(array $save)
	{
		$this->save = $save;
		return $this;
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

	public function insert()
	{
		if(!$this->ping()){
			$this->Again();
		} //检测是否还存在连接

		$arrField = [];
		$arrValue = [];
		$arrStance = [];

		array_walk($this->save,function ($value,$key) use(&$arrField,&$arrValue,&$arrStance) {
			$arrField[] = '`' . $key . '`';
			$arrValue[] = $value;
			$arrStance[] = '?';
		});

		$stringField = implode("," , $arrField);
		$stringValue = implode("," , $arrValue);
		$stringStance = implode("," , $arrStance);
		
		$table = $this->table;

		$sql = "INSERT INTO {$table} ({$stringField}) VALUES ({$stringStance})";
		$this->PDOStatement = $this->pdo->prepare($sql);

		$data = $this->PDOStatement->execute( $arrValue );

		if($data == false){
			
			die('sql 错误 error sql ='. $sql .' 错误文件： '. __FILE__ . ' line '. __LINE__ . ' ---> info :' .  $this->PDOStatement->errorInfo());
		}

		$res = $this->pdo->lastInsertId(); 

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