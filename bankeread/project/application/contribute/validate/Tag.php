<?php
namespace app\contribute\validate;

use think\Validate;

class Tag extends Validate
{

	//标签验证方式
	protected $rule = [
		'name'	=> 'max:10',
	];

	protected $message  =   [
		'name.max'	=> '标签长度不能超过10字符',
    ];

}