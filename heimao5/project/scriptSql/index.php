<?php

namespace access;

use access\database;
use access\sql;
use access\config;
use access\json;

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

	public function book(json $jsonData)
	{
		$book = $this->db->select('select * from jieqi_obook_obook');

		var_dump($book);exit;

		foreach($book as $data)
		{

			$file = __DIR__ . DIRECTORY_SEPARATOR . 'book' . DIRECTORY_SEPARATOR . '1.json';
		
			if($jsonData->put($file) === false)
			{
				return 'error book => ' . 1;
			}

		}


		


	}


}
$book = new bookExport;
$book->book(new json);


?>