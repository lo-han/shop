<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\News as ModelNews;

use \think\Request;
use \think\Db;


class News extends Common
{


	public function list()
	{


		return $this->fetch();
	}

	public function show()
	{

		return $this->fetch();
	}
}