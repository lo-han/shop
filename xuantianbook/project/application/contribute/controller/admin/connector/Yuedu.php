<?php
namespace app\contribute\controller\admin\connector;

use app\contribute\controller\Common;
use app\contribute\controller\AdminCheck;

use app\contribute\model\Connector;
use app\contribute\model\Book;
use app\contribute\model\BookSection;
use app\contribute\model\TagRelation;

use think\Request;
use think\Db;

use yuedu\PushBook;
use yuedu\SearchBook;
use yuedu\DelectBook;

class Yuedu extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}

	const className = "yuedu";

	const secretKey = "4PrkumTUSFzh2Vds";
	const consumerKey = "04281064";

	const bookPushAdd = 'http://testapi.yuedu.163.com/book/add.json';
	const chapterPushAdd = 'http://testapi.yuedu.163.com/bookSection/add.json';
	const bookPushUpdate = 'http://testapi.yuedu.163.com/book/update.json';
	const chapterPushUpdate = 'http://testapi.yuedu.163.com/bookSection/update.json';

	const bookSearchInfo = 'http://testapi.yuedu.163.com/book/list.json';
	const bookSearchBookInfo = 'http://testapi.yuedu.163.com/book/info.json';
	const bookSearchSectionList = 'http://testapi.yuedu.163.com/book/sections.json';
	const bookSearchSectionInfo = 'http://testapi.yuedu.163.com/book/content.json';

	const bookDelectSection = 'http://testapi.yuedu.163.com/bookSection/delete.json';

	public $category = [
		29	=> [16,"女频短篇"],
		28	=> [12,"男频短篇"],
		9	=> [15,"女频灵异"],
		27	=> [15,"男频灵异"],
		26	=> [15,"女频同人"],
		25	=> [10,"男频同人"],
		24	=> [24,"古言"],
		23	=> [22,"校园"],
		22	=> [18,"宫斗"],
		21	=> [17,"种田"],
		20	=> [13,"仙侠"],
		19	=> [9,"穿越"],
		18	=> [8,"现言"],
		16	=> [11,"历史"],
		14	=> [5,"悬疑"],
		13	=> [4,"官场"],
		12	=> [3,"军事"],
		11	=> [2,"科幻"],
		4	=> [1,"玄幻"],
		2	=> [7,"都市"],
	];

	public $return = [
		'code' 	=> 200,
	];

	public function book(Connector $connector,Book $book)
	{
		$count = $book->bookCount(['check'=>1]);
		
		return $this->fetch("",[
			'count'	=> $count,
		]);
	}

	public function list(Connector $connector,Book $book,Request $request)
	{
		$check 	= $connector->getConnectorBook(Yuedu::className);
		$book 	= $book->bookGet([],$request->get('size'));
		
		echo $this->fetch("",[
			'check' => $check,
			'list' 	=> $book,
		]);
	}

	public function add(Connector $connector,Book $book,Request $request)
	{
		$ids = explode("," , $request->post('ids'));

		$connectorBook = explode( 
			",",
			objectFormList($connector->getConnectorBook(Yuedu::className),'book_id')
		);

		$ids = array_filter( array_diff($ids,$connectorBook) );

		if( empty($ids) )
		{
			return true;
		}

		return $connector->createRelation($ids,Yuedu::className,"push");

	}

	public function delete(Connector $connector,Request $request)
	{
		$id = $request->post('id');

		return $connector->where(['book_id'=>$id,'source_name'=>Yuedu::className])->delete();
	}

	//添加推送
	public function push(Connector $connector,Book $book,BookSection $chapter,Request $request,PushBook $pushBook)
	{
		$ids = $request->post("id");

		$pushBook->key( Yuedu::secretKey,Yuedu::consumerKey );

		$pushBook->config([
			'book'	=> Yuedu::bookPushAdd,
			'chapter'	=> Yuedu::chapterPushAdd,
		]);

		$books = $book->all($ids);

		foreach($books as $book)
		{
			$tags = (new TagRelation)->getRelationTag($book->id);
			if($tags)
			{
				$book->setAttr('tags',implode(",",array_column($tags, "name")));
			}
			$book->setAttr('category_id', $this->category[$book->category_id][0] );

			$isPush = $pushBook->book($book);
			if($isPush['code'] !== 200 )
			{
				//$this->return['code'] 	= $isPush['code'];
				//$this->return['msg'][] 	= $book->title . " error :" . $isPush['error_msg'];
			}

			//章节处理
			$chapters = $book->bookSections;
			foreach($chapters as $chapter )
			{
				$chapter->content = strip_tags($chapter->content,"<p>");
				$isPush = $pushBook->chapter($chapter);
				if($isPush['code'] !== 200 )
				{
					//$this->return['code'] 	= $isPush['code'];
					//$this->return['msg'][] 	= $chapter->title . " error :" . $isPush['error_msg'];
				}
			}


		}
		
		return $this->return;
	}

	//更新推送
	public function update(Connector $connector,Book $book,BookSection $chapter,Request $request,PushBook $pushBook)
	{
		$ids = $request->post("id");

		$pushBook->key( Yuedu::secretKey,Yuedu::consumerKey );
		$pushBook->config([
			'updateBook'	=> Yuedu::bookPushUpdate,
			'updateChapter'	=> Yuedu::chapterPushUpdate,
		]);

		$books = $book->all($ids);

		foreach($books as $book)
		{
			$tags = (new TagRelation)->getRelationTag($book->id);
			if($tags)
			{
				$book->setAttr('tags',implode(",",array_column($tags, "name")));
			}
			$book->setAttr('category_id', $this->category[$book->category_id][0] );

			$isPush = $pushBook->updateBook($book);
			if($isPush['code'] !== 200 )
			{
				$this->return['code'] 	= $isPush['code'];
				$this->return['msg'][] 	= $book->title . " error :" . $isPush['error_msg'];
			}

			//章节处理
			$chapters = $book->bookSections;
			foreach($chapters as $chapter )
			{
				$chapter->content = strip_tags($chapter->content,"<p>");
				$isPush = $pushBook->updateChapter($chapter);
				if($isPush['code'] !== 200 )
				{
					$this->return['code'] 	= $isPush['code'];
					$this->return['msg'][] 	= $chapter->title . " error :" . $isPush['error_msg'];
				}
			}


		}
		
		return $this->return;
	}


	//添加推送
	public function pushAdd(Connector $connector,Book $book,BookSection $chapter,Request $request,PushBook $pushBook)
	{
		$ids 	= explode( 
			",",
			objectFormList($connector->getConnectorBook(Yuedu::className),'book_id')
		);

		$pushBook->key( Yuedu::secretKey,Yuedu::consumerKey );
		$pushBook->config([
			'chapter'	=> Yuedu::chapterPushAdd,
		]);

		$books = $book->all($ids);

		foreach($books as $book)
		{
			//章节处理
			$chapters = $book->bookSections;
			foreach ($chapters as $key => $row) {
			    $sort[$row['sort']]  = $row;
			}
			krsort($sort);
			foreach($sort as $chapter )
			{
				$chapter->content = strip_tags($chapter->content,"<p>");
				$isPush = $pushBook->chapter($chapter);
				
				if($isPush['code'] !== 200 )
				{
					unset($sort);
					break;
				}
			}


		}

		return $this->return;
	}

	//查询记录
	public function info(SearchBook $search,Request $request)
	{
		
		$search->key( Yuedu::secretKey,Yuedu::consumerKey );

		$bookList = $search->info( Yuedu::bookSearchInfo );

		foreach($bookList['books'] as $key=>$value)
		{
			$chapterList = $search->sectionList( Yuedu::bookSearchSectionList , $value['bookId'] );
			$bookList['books'][$key]['sectionName'] = array_pop($chapterList['sections'])['name'];
		}
		
		return $this->fetch("info",[
			'books' => $bookList['books'],
		]);
		
	}

	public function chapterDelect(SearchBook $search,DelectBook $delect,Request $request)
	{
		$search->key( Yuedu::secretKey,Yuedu::consumerKey );
		$delect->key( Yuedu::secretKey,Yuedu::consumerKey );
		$bookid = $request->get('bookid');

		$chapterList = $search->sectionList( Yuedu::bookSearchSectionList , $bookid );

		foreach($chapterList['sections'] as $value )
		{	
			$section = $delect->section( Yuedu::bookDelectSection , $bookid , $value['id'] );
		}
		
		$this->redirect( defaults($_SERVER['HTTP_REFERER'],url('AdminConnectorYueduInfo')) );

	}


}