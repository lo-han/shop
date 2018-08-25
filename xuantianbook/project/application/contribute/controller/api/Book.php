<?php
namespace app\contribute\controller\api;

use app\contribute\controller\Common;
use app\contribute\controller\ApiCheck;

use app\contribute\model\Book as ModelBook;
use app\contribute\model\BookSection;
use app\contribute\model\Place;

use think\Response;
use think\Request;


class Book extends Common
{

	use ApiCheck;

	public function _initialize(){

		$this->check((new Place));

	}

	public function get(Request $Request,ModelBook $book){

		$this->bookAuth($Request->get('bookid'));

		$book = $book::get(function ($query) use ($Request){
			$query->alias('b')
			->field('b.id,b.title,b.cover,b.description,b.copyright,b.status,b.char_number,c.title as ctitle,u.pen_name as author')
			->join('category c','b.category_id = c.id','LEFT')
			->join('user u','b.user_id = u.id','LEFT')
			->where(['b.id'=>$Request->get('bookid')]);	
		});

		//不存在书籍返回无资源
		if(!$book){
			return $this->returnXml(['code'=>415,'msg'=>'Not your book'],415);
		}

		//获取此书所有的章节ID
		$sectionid = BookSection::where(['book_id'=>$book->id,'check'=>1])->order("sort ASC")->column('id');

		$book->setAttr('cover',imagePath(['table'=>'book','category'=>'cover'],$book->cover));
		$book->setAttr('copyright',config('book.copyright')[$book->copyright]);
		$book->setAttr('status',config('book.status')[$book->status]);
		$book->setAttr('sectionid',$sectionid);
		
		return $this->returnXml(
			['code'=>200,'msg'=>json_decode(json_encode($book),true)],
			200
		);

	}

	public function info(Request $Request,ModelBook $book){

		$book = $book::all(function ($query){
			$query->field('id,title')->whereIn('id',$this->place->posBook);
		});
		
		
		return $this->returnXml(
			['code'=>200,'msg'=>json_decode(json_encode($book),true)],
			200
		);

	}


}