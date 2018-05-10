<?php
namespace app\contribute\controller\admin;

use app\contribute\controller\Common;
use app\contribute\controller\AdminCheck;

use app\contribute\model\BookMark;
use app\contribute\model\Advert;

use think\Request;

class Setting extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();
	}

	//首页书籍推介
	public function home(){


		return $this->fetch();
	}

	//广告位推介
	public function advert()
	{

		return $this->fetch();
	}

	//修改设置表
	public function saveBook(Request $Request)
	{

	}

	//修改设置表
	public function saveAdvert(Request $Request)
	{

	}


}