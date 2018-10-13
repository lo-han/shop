<?php
//配置文件
return [
	'userLogin' => //用户登陆的错误
	[
		101 => '用户名错误',
		102 => '密码错误',
	],

	'book' => //书本状态
	[

		'status' => 
		[
			1 => '连载',
			2 => '完结',
		],

		'copyright'	=> 
		[
			1 => '独家',
			2 => '非独家',
		],

	],

	'bookSection' 	=> //章节状态
	[
		'attr'	=> 
		[
			1	=> '免费',
			2	=> '付费',
		],
	],

	'check' => //审核状态
	[
		0 => '未审核',
		1 => '已审核',
	],

	'section'	=> [
		'default_title'	=> '第%d章',	//章节的默认标题 采用sprintf 函数来输出
	],

	'role'		=> [	//后台用户权限
		2	=> '制作人',
		1	=> '管理员',
	],


	'hostImg'	=> "http://img.redianbook.com", //图片域名
	'hostWWW'	=> "http://www.redianbook.com" //主站域名

];