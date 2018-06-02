<?php
namespace app\contribute\model;

use think\Model;

class Advert extends Model
{

	public function setting($data)
	{
		
		foreach($data as $key=>$val){

			$arr = ['site'=>$key];
			$save[] = array_merge($arr,$val);

		}

		return $this->saveAll($save);
	}

	public function homeMark()
	{
		$site = $this->whereExp('site','regexp "home_*" ')->select();

		$data = [];
		foreach($site as $key=>$val)
		{
			$data[$val->site] = $val;
		}

		return $data;
	}


	public function getAll()
	{
		$site = $this->select();

		$data = [];
		foreach($site as $key=>$val)
		{
			$data[$val->site] = $val;
		}

		return $data;
	}

	//获取某项配置
	public static function getMark($source_type)
	{
		$mark = self::where('site',$source_type)->find();
		return $mark;
	}



}