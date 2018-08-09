<?php
namespace access;

final class database extends \PDO{
	protected $type = 'mysql'; //默认数据库类型配置mysql
	protected $db;
	protected $host;
	protected $user;
	protected $pass;
	
	private static $sin;
	
	public function __construct(array $config){
		
		if(isset($config['type'])) $this->type = $config['type'];
		
		$this->host = isset($config['host']) ? $config['host'] : die("你的源地址host参数为填写，(your host to fill out)");
		$this->db 	= isset($config['db']) ? $config['db'] : die("你的库名称db参数为填写，(your db to fill out)");
		$this->user = isset($config['user']) ? $config['user'] : die("你的用户名user参数为填写，(your user to fill out)");
		$this->pass = isset($config['pass']) ? $config['pass'] : die("你的密码pass参数为填写，(your pass to fill out)");

		if($this->type == 'sqlsrv'){
			$dsn = $this->type . ':Server='.$this->host . ';Database='.$this->db.';';
		}else{
			$dsn = $this->type . ':host='.$this->host . ';dbname='.$this->db.';charset=utf8';	
		}

		$driver_options = [ 
					
				];
				
		parent::__construct($dsn,$this->user,$this->pass,$driver_options);
	}
	
	public static function single(array $config){
		
		$type = $config['type']; //数据库类型

		//判断对不同类型的数据库进行单例化，目前只支持2种类型数据库，mysql以及sqlsrv
		if(empty(self::$sin[$type])){
			self::$sin[$type] = new database($config);
		}
		return self::$sin[$type];
	}
	
}
?>

