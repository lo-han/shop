<?php
namespace app\contribute\model;

use think\Model;
use app\contribute\model\Book;
use app\contribute\model\Place;
use app\contribute\model\Admin;

class Mark extends Model
{


	public function setting($data)
	{
		
		foreach($data as $key=>$val){

			$arr = ['source_type'=>$key];
			$save[] = array_merge($arr,$val);

		}

		return $this->saveAll($save);
	}

	public function homeMark()
	{
		$mark = $this->whereExp('source_type','regexp "home_*" ')->select();

		$data = [];
		foreach($mark as $key=>$val)
		{
			$data[$val->source_type] = $val;
		}

		return $data;
	}

	public function getAll()
	{
		$mark = $this->select();

		$data = [];
		foreach($mark as $key=>$val)
		{
			$data[$val->source_type] = $val;
		}

		return $data;
	}

	//获取某项配置
	public static function getMark($source_type,$column)
	{
		$mark = self::where('source_type',$source_type)->column($column);
		return $mark[0];
	}

	//推介位的书籍获得
	public function bookGet($limit)
	{	
		return Book::whereIn('id',$this->source_id)->limit($limit)->select();
	}

	//制作人获取
	public function makerGet($limit)
	{	
		return Admin::whereIn('id',$this->source_id)->where('role',2)->where('status',1)->limit($limit)->select();
	}

	//渠道商获取
	public function placeGet($limit)
	{	
		return Place::whereIn('id',$this->source_id)->limit($limit)->select();
	}



}