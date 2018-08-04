<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\User;
use \app\contribute\model\Category;
use \app\contribute\model\BookSection;
use \app\contribute\model\Book as BookModel;
use \app\contribute\model\PlaceRelation;

use \think\Request;
use \think\Db;

class Book extends Common
{

	//书本列表页
	public function list(Request $Request,BookModel $book,User $user){
		$category = Category::getAll();
		$works = Db::table("admin")->where("role",2)->select();

		$where['check'] = 1;
		$whereOr = [];

		if($Request->get('title',false) != false){		//对用户名以及书本名进行搜索
			$user = $user->whereLike('pen_name',"%" . $Request->get('title') . "%")->select();
			$user_id = implode(',',array_column($user,'id'));
			
			if($user_id != false){
				$whereOr['user_id'] = ['in',$user_id];
			}
			$whereOr['title'] = ['like',"%" . $Request->get('title') . "%"];
		}

		if($Request->get('category',false) != false){		//对用户名以及书本名进行搜索
			$where['category_id'] = $Request->get('category');
		}

		if($Request->get('status',false) != false){		//对用户名以及书本名进行搜索
			$where['status'] = $Request->get('status');
		}

		if($Request->get('works',false) != false){		//对用户名以及书本名进行搜索
			$where['admin_id'] = $Request->get('works');
		}
		
		$book = $book->bookListSearch($where,$whereOr,24);


		$this->assign('book',$book);		
		$this->assign('works',$works);
		$this->assign('search',$Request->Request());
		$this->assign('status',config('book.status'));
		$this->assign('category',$category);
		return $this->fetch();
	}

	//书本展示页面
	public function show(Request $Request,PlaceRelation $placeRelation,BookSection $bookSection){
		$id = $Request->route('id');
		$book = BookModel::get($id);

		if(!isset($book) || $book->check == 0){ //未发布404
			return $this->returnCode();
		}

		$section = $bookSection->sectionFind(['check'=>1,'book_id'=>$book->id]); 	//获取最新章节
		if( isset($section) ){
			$book->setAttr('update_time',$section->create_time);
		}	//存在章节的用章节创建时间作为 最近更新		

		$user = User::get($book->user_id);	//对应书籍的用户

		$moreBook = BookModel::all(function ($query) use ($book){
			$query->where('user_id',$book->user_id)->where('check',1)->where('id','<>',$book->id)->order('id','DESC')->limit(3);
		});		//获取 用户的其他书籍

		$place = $placeRelation->getRelationPlace($book->id);	//获取渠道商

		$this->assign("book",$book);
		$this->assign("user",$user);
		$this->assign("moreBook",$moreBook);
		$this->assign("place",$place);
		return $this->fetch();
	}


}