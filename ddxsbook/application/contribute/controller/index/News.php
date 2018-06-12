<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\News as NewsModel;

use \think\Request;
use \think\Db;

class News extends Common
{

	public function show(Request $Request,NewsModel $news)
	{
		$id = $Request->route('id');
		$news = $news->get($id);

		$this->assign('news',$news);
		return $this->fetch();
	}

}