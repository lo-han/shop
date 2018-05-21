<?php
namespace app\contribute\controller\admin;

use app\contribute\controller\Common;
use app\contribute\controller\AdminCheck;

use app\contribute\model\Mark;
use app\contribute\model\Advert;

use think\Request;

class Setting extends Common
{

	use AdminCheck;

	public function _initialize()
	{
		$this->check();
	}

	//首页书籍推介
	public function home(Mark $mark)
	{
 		$post = $mark->getAll();

 		$this->assign('data',$post);
		return $this->fetch();
	}

	//广告位推介
	public function advert(Advert $advert)
	{
		$post = $advert->getAll();

 		$this->assign('data',$post);
		return $this->fetch();
	}

	//修改设置表
	public function saveBook(Request $Request,Mark $mark)
	{
		$post = $Request->post();

		$mark->setting($post['post']);

		$this->redirect(url('AdminSettingHome'));	//添加成功返回列表页面
	}

	//修改设置表
	public function saveAdvert(Request $Request,Advert $advert)
	{
		$post = $Request->post();

		$advert->setting($post['post']);

		$this->redirect(url('AdminSettingAdvert'));	//添加成功返回列表页面
	}


}