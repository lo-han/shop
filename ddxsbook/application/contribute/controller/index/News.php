<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\News as NewsModel;

use \think\Request;
use \think\Db;

class News extends Common
{

	public function show()
	{

		return $this->fetch();
	}

}