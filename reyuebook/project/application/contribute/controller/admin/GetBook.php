<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use getbook\GetBook as api;
use getbook\Curl;

use app\contribute\model\User;
use app\contribute\model\Book;
use app\contribute\model\BookSection;
use app\contribute\model\Category;

use \think\Request;
use \think\Db;

class GetBook extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}

	protected $bookTableName = 'hongshuhui_book';

	protected $chapterTableName = 'hongshuhui_chapter';


	protected $bookTableCreate = "CREATE TABLE `hongshuhui_book` (
								  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '书本主键',
								  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '作者ID',
								  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '制作人ID',
								  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
								  `title` varchar(50) NOT NULL COMMENT '书本名称',
								  `cover` varchar(255) NOT NULL COMMENT '书本封面',
								  `description` varchar(500) NOT NULL COMMENT '书本简介',
								  `char_number` int(11) DEFAULT '0' COMMENT '所有章节的文字总和',
								  `copyright` tinyint(4) DEFAULT NULL COMMENT '书本版权',
								  `status` tinyint(4) NOT NULL COMMENT '书本是否完结',
								  `check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态是否在审核',
								  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '章节排序',
								  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
								  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
								  PRIMARY KEY (`id`)
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书本表格'";


	protected $chapterTableCreate = "CREATE TABLE `hongshuhui_chapter` (
								  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '章节主键',
								  `chapterid` int(10) NOT NULL DEFAULT '0' COMMENT '书本ID',
								  `book_id` int(10) unsigned DEFAULT NULL COMMENT '书本ID',
								  `user_id` int(10) unsigned DEFAULT NULL COMMENT '作者ID',
								  `title` varchar(255) NOT NULL COMMENT '章节标题',
								  `content` text NOT NULL COMMENT '章节正文',
								  `char` int(11) DEFAULT '0' COMMENT '章节文字数',
								  `attr` tinyint(4) DEFAULT NULL COMMENT '章节属性',
								  `check` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态是否在审核',
								  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '章节排序',
								  `update_time` int(11) NOT NULL COMMENT '修改时间戳',
								  `create_time` int(11) NOT NULL COMMENT '创建时间戳',
								  PRIMARY KEY (`id`),
								  UNIQUE KEY `table_hongshuhui-chapter_index_book-id` (`book_id`,`chapterid`) USING BTREE
								) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='书本章节表格'";

	protected $category = [
		1001 => [
			'title' 	=> '热血青春',
			'spell' 	=> 'rexueqingchun',
		],
		1002 => [
			'title' 	=> '现代都市',
			'spell' 	=> 'xiandaidushi',
		],
		1003 => [
			'title' 	=> '悬疑灵异',
			'spell' 	=> 'xuanyilingyi',
		],
		1004 => [
			'title' 	=> '军事历史',
			'spell' 	=> 'junshilishi',
		],
		1005 => [
			'title' 	=> '玄幻奇幻',
			'spell' 	=> 'xuanhuanqihuan',
		],
		1006 => [
			'title' 	=> '武侠仙侠',
			'spell' 	=> 'wuxiaxianxia',
		],
		1007 => [
			'title' 	=> '网游竞技',
			'spell' 	=> 'wangyoujingji',
		],
		1008 => [
			'title' 	=> '热血言情',
			'spell' 	=> 'rexueyanqing',
		],
		1009 => [
			'title' 	=> '古代言情',
			'spell' 	=> 'gudaiyanqing',
		],
		1010 => [
			'title' 	=> '现代爱情',
			'spell' 	=> 'xiandaiaiqing',
		],
		1011 => [
			'title' 	=> '幻想世界',
			'spell' 	=> 'huanxiangshijie',
		],
	];


	public function bookPut(api $api){
		set_time_limit(0);
		ini_set('memory_limit', '-1');
		/**
	      *  必须传输的配置
	      *  info book chapter 代表接口地址
	      *  apikey 是商户的唯一秘钥
	      */
	    $api->config([
	       'info'		=>'http://www.tcss88.com/?s=JsonApi&a=index&api=get.spbooks',
	       'book'		=>'http://www.tcss88.com/?s=JsonApi&a=index&api=get.book.info',
	       'chapterInfo'=>'http://www.tcss88.com/?s=JsonApi&a=index&api=get.book.chapter',
	       'chapter'	=>'http://www.tcss88.com/?s=JsonApi&a=index&api=get.book.content',
	       'apikey'		=>'hongshuhui2',
	       'spid'		=>120,
	    ]);

	    //http://www.tcss88.com/?s=JsonApi&a=index&api=get.book.chapter&apikey=jinyuewenhua&spid=109&bookid=113
	    //http://www.tcss88.com/?s=JsonApi&a=index&api=get.book.content&apikey=jinyuewenhua&spid=109&bookid=113&chapterid=3857
	    //$bookid = $api->info()['data'][0]['bookid'];
	    //$chapter = $api->chapterInfo($bookid)['data'][0];
	    //var_dump($api->chapter($bookid,$chapter['chapters'][0]['chapterid']));

	    $bookData = $api->info()['data'];
	    
	    $this->isChapter();
	    $this->isBook();
	    
	    foreach($bookData as $key=>$book){
	    	
	    	$bookInfo 		= $api->book($book['bookid']);

	    	$this->bookBase($bookInfo['data']);
	    	$chapterInfo 	= $this->chapterInfo($api,$bookInfo['data']['bookid']);
	    	$this->chapter($api,$chapterInfo[0],$bookInfo['data']['bookid'],$bookInfo['data']['author']);
	    }
	    
	    return $this->jsonSuccess(['code'=>200,'msg'=>'success']);

	}

	public function bookImport(Book $book,BookSection $bookSection)
	{
		set_time_limit(0);
		ini_set('memory_limit', '-1');

		$hongshuhui_book = Db::table($this->bookTableName)->select();
		$hongshuhui_new_chapter = $hongshuhui_book;
		$allBook = array_column($hongshuhui_book,'id');

		$isHongshuhui = array_column($book->whereIn('lead_id',$allBook)->select(),'lead_id');

		array_walk($hongshuhui_book,function ($item,$key) use($isHongshuhui,&$hongshuhui_book){
			if(in_array($item['id'],$isHongshuhui) === true)
			{
				unset($hongshuhui_book[$key]);
			}	
		});

		array_walk($hongshuhui_book,function (&$item,$key){
			$item['lead_id'] = $item['id'];
			unset($item['id']);
		});

		$book->saveAll($hongshuhui_book);

		foreach($hongshuhui_new_chapter as $book_id)
		{
			$realBookId = $book->where('lead_id',$book_id['id'])->find()['id'];

			$hongshuhui_chapter = Db::table($this->chapterTableName)->where('book_id',$book_id['id'])->select();
			$allChapter = array_column($hongshuhui_chapter,'id');

			$isChapter = array_column($bookSection->whereIn('lead_id',$allChapter)->select(),'lead_id');

			array_walk($hongshuhui_chapter,function ($item,$key) use($isChapter,&$hongshuhui_chapter){
				if(in_array($item['id'],$isChapter) === true)
				{
					unset($hongshuhui_chapter[$key]);
				}	
				unset($item);
			});

			array_walk($hongshuhui_chapter,function (&$item,$key) use($realBookId){
				$item['lead_id'] = $item['id'];
				$item['book_id'] = $realBookId;

				$content = [];
				foreach(explode("\n",$item['content']) as $val)
				{
					$content[] = '<p>'.$val.'</p>';
				}
				$item['content'] = implode('',$content);

				unset($item['id']);
				unset($item['chapterid']);
			});

			$bookSection->saveAll($hongshuhui_chapter);
			unset($hongshuhui_chapter);
			unset($isChapter);
			unset($allChapter);

		}

		return $this->jsonSuccess(['code'=>200,'msg'=>'success']);
		
	}

	protected function chapterInfo(api $api,$id){
		$charpter = [];

		foreach($api->chapterInfo($id)['data'] as $volu )
		{
			array_push($charpter, $volu['chapters']);
		}
		return $charpter;
	}

	protected function chapter(api $api,$chapterInfo,$bookid,$penName){
		$data = [];

		foreach($chapterInfo as $key=>$vo)
		{
			
			$isBook = Db::table($this->chapterTableName)->where('chapterid',$vo['chapterid'])->where('book_id',$bookid)->find();

			if(!isset($isBook))
			{
				$info = $api->chapter($bookid,$vo['chapterid']);

				$chapter = array_merge($info['data'],$vo);
				array_push($data, $chapter);
				$this->chapterBase($chapter,$bookid,$penName,$key+1);
			}
			
		}
	}

	protected function info(api $api){
		return $api->info();
	}

	protected function book(api $api,$id){
		return $api->book();
	}

	protected function bookBase($book)
	{	
		$isBook = Db::table($this->bookTableName)->where('id',$book['bookid'])->find();

		if(!isset($isBook))
		{
			Db::table($this->bookTableName)->insert([
				'id' 			=> $book['bookid'],
				'user_id' 		=> $this->randUser($book['author']),
				'category_id' 	=> $this->createCategory($book['category']),
				'title' 		=> $book['title'],
				'cover' 		=> (new Curl)->getResource($book['cover_url']),
				'description' 	=> $book['description'],
				'char_number' 	=> $book['word_count'],
				'copyright' 	=> 2,
				'status' 		=> $book['bookstatus'] == 1 ? 2 : 1,
				'check' 		=> 1,
				'sort' 			=> 0,
				'update_time' 	=> $book['lastupdatetime'],
				'create_time' 	=> $book['createtime'],
			]);
		}

	}

	protected function chapterBase($chapter,$bookid,$penName,$sort)
	{
		Db::table($this->chapterTableName)->insert([
			'chapterid' 	=> $chapter['chapterid'],
			'book_id' 		=> $chapter['bookid'],
			'user_id' 		=> Db::table('user')->where('pen_name',$penName)->find()['id'],
			'title' 		=> $chapter['chaptername'],
			'content' 		=> $chapter['content'],
			'char' 			=> $chapter['chaptersize'],
			'attr' 			=> $chapter['free'] == 1 ? 2 : 1,
			'check' 		=> 1,
			'sort' 			=> $sort,
			'update_time' 	=> $chapter['lastupdatetime'],
			'create_time' 	=> $chapter['createtime'],
		]);

	}

	protected function isChapter()
	{
		$is_table = in_array(
			'hongshuhui_chapter',
			array_column(
				Db::query('show tables'),
				'Tables_in_tcss88'
			)
		);
		if($is_table == false)
		{
			Db::query(
				$this->chapterTableCreate
			);
		}
	}

	protected function isBook()
	{
		$is_table = in_array(
			'hongshuhui_book',
			array_column(
				Db::query('show tables'),
				'Tables_in_tcss88'
			)
		);
		if($is_table == false)
		{
			Db::query(
				$this->bookTableCreate
			);
		}
	}

	protected function createCategory($id)
	{
		$categoryName = $this->category[$id];
		$category = Category::where('title',$categoryName['title'])->find();
		if( !isset($category) )
		{
			return Db::table('category')->insertGetId([
				'title' 		=> $categoryName['title'],
				'spell' 		=> $categoryName['spell'],
				'sort' 			=> 20,
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			]);
		}

		return $category->id;
	}

	protected function randUser($penName)
	{

		$user = User::where('pen_name',$penName)->find();

		if(!isset($user))
		{
			return Db::table('user')->insertGetId([
				'name' 			=> 'HSHUSER' . str_pad(User::count()+1,3,"0",STR_PAD_LEFT),
				'pen_name' 		=> $penName,
				'password' 		=> '14e1b600b1fd579f47433b88e8d85291',
				'status' 		=> 1,
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			]);
		}

		return $user->id;
		

	}


}