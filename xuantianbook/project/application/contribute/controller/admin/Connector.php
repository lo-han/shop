<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Connector as ConnectorModel;

class Connector extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();
	}

	public function index(ConnectorModel $connector){
		

		return $this->fetch('',[
			'connector' => $connector,
		]);
	}

}