<?php
namespace app\contribute\model;

use think\Model;
use app\contribute\model\Book;

class BookMark extends Model
{


	public function setting($data)
	{
		
		foreach($data as $key=>$val){

			$arr = ['mark'=>$key];
			$save[] = array_merge($arr,$val);

		}

		return $this->saveAll($save);
	}

	public function homeMark()
	{
		$mark = $this->whereExp('mark','regexp "home_*" ')->select();

		$data = [];
		foreach($mark as $key=>$val)
		{
			$data[$val->mark] = $val;
		}

		return $data;
	}

	public function getAll()
	{
		$mark = $this->select();

		$data = [];
		foreach($mark as $key=>$val)
		{
			$data[$val->mark] = $val;
		}

		return $data;
	}

	//推介位的书籍获得
	public function bookGet($limit)
	{	
		return Book::whereIn('id',$this->book_id)->limit($limit)->select();
	}

}