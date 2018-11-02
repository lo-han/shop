<?php
namespace app\contribute\controller;

use app\contribute\model\Place;
use app\contribute\model\PlaceRelation;

use \think\Response;

trait ApiCheck
{

	protected $place;

	//渠道用户的apikey验证
	public function check(Place $place){

		$apikey = request()->get('apikey');

		if( $place = $place->get(['apikey'=>$apikey]) ){
			$this->place = $place;

			$this->place->setAttr(
				'posBook',
				PlaceRelation::where(['place_id'=>$this->place->id])->column('book_id')
			);
		}else{

			die(
				$this->returnXml([
					'code'	=> 415,
					'msg'	=> 'apikey error'
				],415)->send()
			);

		}

	}

	//书本的API权限验证
	public function bookAuth($bookid){

		if($bookid){

			if(in_array($bookid,$this->place->posBook))
			{
				return true;
			}

		}

		die(
			$this->returnXml([
				'code'	=> 415,
				'msg'	=> 'Not your book'
			],415)->send()
		);
		
	}

	//书本的API权限验证
	public function bookAuthJson($bookid){

		if($bookid){

			if(in_array($bookid,$this->place->posBook))
			{
				return true;
			}

		}

		die(
			$this->jsonError([
				'code'	=> 415,
				'msg'	=> 'Not your book'
			],415)->send()
		);
		
	}


}