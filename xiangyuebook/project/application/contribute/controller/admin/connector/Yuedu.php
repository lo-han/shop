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

class Yuedu extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}

	const className = "yuedu";

	const secretKey = "WY9eCCjtMpfPTVyO";
	const consumerKey = "02430617";

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
		$book 	= $book->bookCheck([],$request->get('size'));
		
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


	public function push(Connector $connector,Book $book,BookSection $chapter,Request $request,PushBook $pushBook)
	{
		$ids = $request->post("id");

		$pushBook->key( Yuedu::secretKey,Yuedu::consumerKey );

		$pushBook->config([
			'book'	=> 'http://testapi.yuedu.163.com/book/add.json',
			'chapter'	=> 'http://testapi.yuedu.163.com/bookSection/add.json',
		]);

		$books = $book->all($ids);

		foreach($books as $book)
		{
			$tags = (new TagRelation)->getRelationTag($book->id);
			if($tags)
			{
				$book->setAttr('tags',implode(",",array_column($tags, "name")));
			}
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



}