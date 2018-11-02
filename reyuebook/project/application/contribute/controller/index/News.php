<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\News as ModelNews;

use \think\Request;
use \think\Db;


class News extends Common
{


	public function list(ModelNews $news)
	{
		$news = $news->newsGet(['check'=>1],8);

		$this->assign('news',$news);
		return $this->fetch();
	}

	public function show(Request $Request,ModelNews $news)
	{
		$id = $Request->route('id');
		$news = $news->where('check',1)->find($id);

		$this->assign('news',$news);
		return $this->fetch();
	}
}