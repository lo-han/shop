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

use kanshu\PushBook;

class Kanshu extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}

	const className = "kanshu";

	const secretKey = "cea8100a05c0fd7d6ab11bfa99e42664";
	const consumerKey = "61037292";

	const bookPushAdd = 'http://open.kanshu.com/hezuo/book/add';

	public $category = [
		9	=> [69,"古代言情"],
		8	=> [50,"现代言情"],
		7	=> [78,"同人其他"],
		6	=> [103,"科幻末日"],
		5	=> [36,"体育电竞"],
		4	=> [31,"历史军事"],
		3	=> [22,"玄幻仙侠"],
		2	=> [38,"乡村生活"],
		1	=> [15,"现代都市"],
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
		$check 	= $connector->getConnectorBook(Kanshu::className);
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
			objectFormList($connector->getConnectorBook(Kanshu::className),'book_id')
		);

		$ids = array_filter( array_diff($ids,$connectorBook) );

		if( empty($ids) )
		{
			return true;
		}

		return $connector->createRelation($ids,Kanshu::className,"push");

	}

	public function delete(Connector $connector,Request $request)
	{
		$id = $request->post('id');

		return $connector->where(['book_id'=>$id,'source_name'=>Kanshu::className])->delete();
	}

	//添加推送
	public function push(Connector $connector,Book $book,BookSection $chapter,Request $request,PushBook $pushBook)
	{
		$ids = $request->post("id");

		$pushBook->key( Kanshu::secretKey,Kanshu::consumerKey );

		$pushBook->config([
			'book'	=> Kanshu::bookPushAdd,
		]);

		$books = $book->all($ids);

		foreach($books as $book)
		{
			$book->setAttr('category_id', $this->category[$book->category_id][0] );

			$bookInfo = [
				'hz_book_id'	=> $book->id,
				'title'			=> $book->title,
				'book_intro'	=> $book->description,
				'author_name'	=> $book->user->pen_name,
				'site'			=> 1,
				'category_id'	=> $book->category_id,
				'cover_url'		=> imagePath(['table'=>'book','category'=>'cover'],$book->cover),
				'writing_process'	=> $book->status == 1 ? 0 : 1,
				'createtime'	=> date('Y-m-d H:i:s',$book->create_time),
			];

			if($book->status == 2)
			{
				$bookInfo['over_time']	= date('Y-m-d H:i:s',$book->update_time);
			}	//完结书籍需要over_time
			
			
			//章节处理
			$chapters = $book->bookSections;

			foreach($chapters as $chapter )
			{
				$chapter->content = str_replace("</p>", "</p>\n", $chapter->content);
				$chapter->content = str_replace(" ", "", $chapter->content);
				$chapter->content = strip_tags($chapter->content);
				
				$chapterInfo[] = [
					'hz_book_id'	=> $chapter->book_id,
					'hz_chapter_id'	=> $chapter->id,
					'title'			=> $chapter->title,
					'content'		=> $chapter->content,
					'createtime'	=> date("Y-m-d H:i:s",$chapter->create_time),
					'order'			=> $chapter->sort,
					'vip'			=> 0,
					'sale_price'	=> 0,
				];
			}
			$return = $pushBook->book($bookInfo,$chapterInfo);
			var_dump(json_decode($return->response));
		}
		
		return $this->return;
	}


}