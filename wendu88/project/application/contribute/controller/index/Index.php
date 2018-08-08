<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\User;
use \app\contribute\model\BookSection;
use \app\contribute\model\Book;
use \app\contribute\model\News;
use \app\contribute\model\PlaceRelation;
use \app\contribute\model\Admin;
use \app\contribute\model\Advert;
use \app\contribute\model\Mark;

use \think\Request;
use \think\Db;

class Index extends Common
{

	//首页
	public function index(Book $book){

		$advert = (new Advert)->homeMark();
		$mark = (new Mark)->homeMark();
		$newBook = $book->bookGet(['check'=>1],10);

		$this->assign('newBook',$newBook);
		$this->assign('advert',$advert);
		$this->assign('mark',$mark);
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