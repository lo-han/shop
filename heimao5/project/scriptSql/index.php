<?php

namespace access;

use access\database;
use access\sql;
use access\config;
use access\json;
use access\dir;

header("Content-type: text/html; charset=utf-8"); 
include __DIR__ . DIRECTORY_SEPARATOR . 'access/autoload.php';
spl_autoload_register(array('Autoload', 'classLoader')); 		//自动加载
set_time_limit(0);				//php 执行无时间限制
ignore_user_abort(true);		//客户端执行
error_reporting(E_ALL);			//显示报错代码
ini_set("display_errors", 1);  //显示报错代码



class bookExport{

	public $db;

	protected $category = [
		1 => [
			'title' 	=> '奇幻玄幻',
			'spell' 	=> 'qihuanxuanhuan',
		],
		2 => [
			'title' 	=> '武侠仙侠',
			'spell' 	=> 'wuxiaxianxia',
		],
		3 => [
			'title' 	=> '都市娱乐',
			'spell' 	=> 'dushiyule',
		],
		4 => [
			'title' 	=> '历史军事',
			'spell' 	=> 'lishijunshi',
		],
		5 => [
			'title' 	=> '悬疑灵异',
			'spell' 	=> 'xuanyilingyi',
		],
		6 => [
			'title' 	=> '竞技同人',
			'spell' 	=> 'jingjitonren',
		],
		7 => [
			'title' 	=> '科幻游戏',
			'spell' 	=> 'kehuanyouxi',
		],
		8 => [
			'title' 	=> '都市言情',
			'spell' 	=> 'dushiyanqing',
		],
		9 => [
			'title' 	=> '古代言情',
			'spell' 	=> 'gudaiyanqing',
		],
		10 => [
			'title' 	=> '幻想时空',
			'spell' 	=> 'huanxiangshikong',
		],
		11 => [
			'title' 	=> '耽美同人',
			'spell' 	=> 'danmeitongren',
		],
		12 => [
			'title' 	=> '评论文集',
			'spell' 	=> 'pinglunwenji',
		],
		13 => [
			'title' 	=> '短篇美文',
			'spell' 	=> 'duanpianmeiwen',
		],
	];

	public function __construct(){
		$config = config::$mysql;
		$config['db'] = 'book';
		$this->db = new sql($config); //数据库对象
	}

	public function book()
	{
		$jsonData = new json;
		$book = $this->db->select('select obookid,obookname,keywords,sortid,intro,author from jieqi_obook_obook');

		foreach($book as $data)
		{
			$data['copyright'] 	= 2;
			$data['status'] = 2;
			$file = __DIR__ . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . $data['obookid'] . '.json';
		
			if($jsonData->put($file,$data) === false)
			{
				return 'error book => ' . $data['obookid'];
			}

		}
		return true;
	}

	public function chapter()
	{
		$jsonData = new json;
		$dir = new dir;
		$bookPath = __DIR__ . DIRECTORY_SEPARATOR . 'book';
		$chapterPath = __DIR__ . DIRECTORY_SEPARATOR . 'chapter';
		$dir->tree($book,$bookPath);

		foreach($book as $file )
		{
			$file = $bookPath . $file;
			$book = $jsonData->get($file);

			$chapter = $this->db->select("SELECT a.ochapterid,a.chaptername,a.chapterorder,b.ocontent FROM jieqi_obook_ochapter AS a
			LEFT JOIN jieqi_obook_ocontent AS b ON a.ochapterid = b.ochapterid
			WHERE a.obookid = {$book['obookid']} ORDER BY a.chapterorder ASC");

			$char_number = 0;

			foreach($chapter as $key=>$data)
			{
				$chapterfile = $chapterPath . DIRECTORY_SEPARATOR . $book['obookid'] . DIRECTORY_SEPARATOR . $data['ochapterid'] . '.json';

				$data['char'] = mb_strlen($data['ocontent']);
				$data['attr'] = 1;
				$data['book_id'] = $book['obookid'];

				if($key > 18){
					$data['attr'] = 2;					
				}

				if($jsonData->put($chapterfile,$data) === false)
				{
					return 'error chapter => ' . $data['ochapterid'];
				}
				$char_number += $data['char'];
			}

			$book['char_number'] = $char_number;
			$jsonData->put($file,$book);
			
		}
		return true;
	}


}


class bookLead{

	public $db;

	protected $category = [
		1 => [
			'title' 	=> '奇幻玄幻',
			'spell' 	=> 'qihuanxuanhuan',
		],
		2 => [
			'title' 	=> '武侠仙侠',
			'spell' 	=> 'wuxiaxianxia',
		],
		3 => [
			'title' 	=> '都市娱乐',
			'spell' 	=> 'dushiyule',
		],
		4 => [
			'title' 	=> '历史军事',
			'spell' 	=> 'lishijunshi',
		],
		5 => [
			'title' 	=> '悬疑灵异',
			'spell' 	=> 'xuanyilingyi',
		],
		6 => [
			'title' 	=> '竞技同人',
			'spell' 	=> 'jingjitonren',
		],
		7 => [
			'title' 	=> '科幻游戏',
			'spell' 	=> 'kehuanyouxi',
		],
		8 => [
			'title' 	=> '都市言情',
			'spell' 	=> 'dushiyanqing',
		],
		9 => [
			'title' 	=> '古代言情',
			'spell' 	=> 'gudaiyanqing',
		],
		10 => [
			'title' 	=> '幻想时空',
			'spell' 	=> 'huanxiangshikong',
		],
		11 => [
			'title' 	=> '耽美同人',
			'spell' 	=> 'danmeitongren',
		],
		12 => [
			'title' 	=> '评论文集',
			'spell' 	=> 'pinglunwenji',
		],
		13 => [
			'title' 	=> '短篇美文',
			'spell' 	=> 'duanpianmeiwen',
		],
	];

	public function __construct(){
		$config = config::$mysqlTest;
		$this->db = new sql($config); //数据库对象
	}


	public function book()
	{
		$jsonData = new json;
		$dir = new dir;
		$bookPath = __DIR__ . DIRECTORY_SEPARATOR . 'book';
		$dir->tree($books,$bookPath);

		foreach($books as $file)
		{
			$file = $bookPath . $file;
			$book = $jsonData->get($file);
			
			$user_id = $this->userAdd($book['author']);
			$category_id = $this->categoryAdd($book['sortid']);

			$bookId = $this->db->table('book')->save([
				'user_id' 		=> $user_id,
				'category_id' 	=> $category_id,
				'title' 		=> $book['obookname'],
				'char_number' 	=> $book['char_number'],
				'description' 	=> $book['intro'],
				'copyright' 	=> $book['copyright'],
				'status' 		=> $book['status'],
				'check' 		=> 1,
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			])->insert();

			if(!empty($book['keywords']))
			{
				$this->tagAdd($book['keywords'],$bookId);
			}

			$this->chapter($bookId,$user_id,$book['obookid']);
		}

		return true;
	}

	protected function chapter($bookId,$userId,$obookid)
	{
		$jsonData = new json;
		$dir = new dir;
		$chapterPath = __DIR__ . DIRECTORY_SEPARATOR . 'chapter' . DIRECTORY_SEPARATOR . $obookid;
		$dir->tree($chapters,$chapterPath);

		foreach($chapters as $file)
		{
			$file = $chapterPath . $file;
			$chapter = $jsonData->get($file);
			
			$chapterId = $this->db->table('book_section')->save([
				'book_id' 		=> $bookId,
				'user_id' 		=> $userId,
				'title' 		=> $chapter['chaptername'],
				'content' 		=> $chapter['ocontent'],
				'char' 			=> $chapter['char'],
				'attr' 			=> $chapter['attr'],
				'check' 		=> 1,
				'sort' 			=> $chapter['chapterorder'],
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			])->insert();
			
		}

	}

	protected function tagAdd($tag,$bookId)
	{
		$tags = array_filter( explode(" ",$tag) );

		foreach($tags as $data)
		{
			$category = $this->db->select("SELECT * FROM tag WHERE name = '{$data}'");

			if( empty($category) )
			{
				$tagId[] = $this->db->table('tag')->save([
					'name' 			=> $data,
					'create_time' 	=> time(),
				])->insert();
			}else{
				$tagId[] = $category[0]['id'];
			}

		}

		foreach( $tagId as $id )
		{
			$tagId[] = $this->db->table('tag_relation')->save([
				'tag_id' 	=> $id,
				'book_id' 	=> $bookId,
			])->insert();
		}
	}

	protected function categoryAdd($id)
	{
		$categoryName = $this->category[$id];
		$category = $this->db->select("SELECT * FROM category WHERE title = '{$categoryName['title']}'");
		if( empty($category) )
		{
			return $this->db->table('category')->save([
				'title' 		=> $categoryName['title'],
				'spell' 		=> $categoryName['spell'],
				'sort' 			=> 20,
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			])->insert(); 
		}
		return $category[0]['id'];
	}

	protected function userAdd($penName)
	{
		$user = $this->db->select("SELECT * FROM user WHERE pen_name = '{$penName}'");
		$count = $this->db->count("SELECT count(*) FROM user");
		
		if(empty($user))
		{
			return $this->db->table('user')->save([
				'name' 			=> 'HSHUSER' . str_pad($count+1,3,"0",STR_PAD_LEFT),
				'pen_name' 		=> $penName,
				'password' 		=> '14e1b600b1fd579f47433b88e8d85291',
				'status' 		=> 1,
				'update_time' 	=> time(),
				'create_time' 	=> time(),
			])->insert();
		}

		return $user[0]['id'];
	}

}


$obj = __NAMESPACE__ . '\\' . $argv[1];
$fun = $argv[2];

if( !class_exists($obj) ){
	die("不存在对象");
}

if( !method_exists($obj,$fun) ){
	die("不存在执行方式");
}

$book = (new $obj())->$fun();
var_dump($book);



?>