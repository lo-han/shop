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
		$limit 	= explode(",",$limit);
		$sort 	= explode(",",$this->source_id);

		if(count($limit) == 2){
			$sort = array_slice($sort,$limit[0],$limit[1]);
		}else{
			$sort = array_slice($sort,0,$limit[0]);
		}

		$data = Book::whereIn('id', implode(",",$sort) )->select();

		return $this->markSort($data,$sort);
	}

	//制作人获取
	public function makerGet($limit)
	{	
		$limit 	= explode(",",$limit);
		$sort 	= explode(",",$this->source_id);

		if(count($limit) == 2){
			$sort = array_slice($sort,$limit[0],$limit[1]);
		}else{
			$sort = array_slice($sort,0,$limit[0]);
		}

		$data = Admin::whereIn('id', implode(",",$sort) )->where('role',2)->where('status',1)->select();

		return $this->markSort($data,$sort);
	}

	//渠道商获取
	public function placeGet($limit)
	{	

		$limit 	= explode(",",$limit);
		$sort 	= explode(",",$this->source_id);

		if(count($limit) == 2){
			$sort = array_slice($sort,$limit[0],$limit[1]);
		}else{
			$sort = array_slice($sort,0,$limit[0]);
		}

		$data = Place::whereIn('id', implode(",",$sort) )->select();

		return $this->markSort($data,$sort);
	}

	//wherein 排序
	protected function markSort($data,$sort)
	{
		$ret = [];
		foreach($data as $val)
		{
			foreach($sort as $k=>$s)
			{
				
				if( $val->id == $s )
				{	
					$ret[$k] = $val;
				}

			}
			
		}
		ksort($ret);
		return $ret;
	}



}