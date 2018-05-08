<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\User;
use \app\contribute\model\BookSection;
use \app\contribute\model\Book;
use \app\contribute\model\News;
use \app\contribute\model\PlaceRelation;
use \app\contribute\model\Admin;

use \think\Request;
use \think\Db;

class Index extends Common
{

	//首页
	public function index(){

		//调用最新的资讯
		$news = News::all(function($query){
		    return $query->where('check', 1)->limit(5)->order('id', 'DESC');
		});

		//调用最新的作品
		$book = Book::all(function($query){
		    return $query->where('check', 1)->limit(12)->order('sort', 'DESC')->order('id', 'DESC');
		});

		//调用作者
		$user = User::all(function($query){
		    return $query->whereIn('id',[1,2,3,4,5,6,7])->limit(7);
		});

		//调用制作者
		$admin = Admin::all(function($query){
		    return $query->whereIn('id',[1,2,3,4,5,6,7])->where('role',2)->limit(7);
		});

		$this->assign("news",$news);
		$this->assign("book",$book);
		$this->assign("user",$user);
		$this->assign("admin",$admin);
		return $this->fetch();
	}

	//制作人展示页面
	public function maker(){
		//调用制作者
		$admin = Admin::getAll();

		foreach($admin as $value){
			$book = book::all(function($query) use ($value){
		    	return $query->where('admin_id',$value->id)->where('check',1)->order('id','DESC');
			});
			$value->setAttr('book',$book); //设置制作者 制作的书籍
		}

		$this->assign("admin",$admin);
		return $this->fetch();
	}

	//作者展示页面
	public function author(Request $Request,User $user){

		//调用作者
		$user = $user->homeUserShow($Request->route('sort','newest'),6);

		foreach($user as $value){

			$book = book::all(function($query) use ($value){
		    	return $query->where('user_id',$value->id)->where('check',1)->order('id','DESC')->limit(6);
			});

			$value->setAttr('book',$book); //设置制作者 制作的书籍

		}

		$this->assign("user",$user);
		return $this->fetch();
	}

	//关于我们
	public function we(){

		return $this->fetch();
	}


}