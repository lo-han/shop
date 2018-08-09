<?php
namespace app\contribute\model;

use think\Model;

class Category extends Model
{


	/**
	 *	对分类进行修改或是添加
	 *	@param request 请求集
	 *	@return (array) 是否创建成功
	 */

	public function upOrCreate($request){

		if($request->has('id')){
			$table = $this->get($request->post('id'));
		}else{
			$table = $this->data([
				'create_time' 	=> time(),
			]);
		}
		
		$data = [
			'title' 		=> $request->post('title'),
			'spell' 		=> $request->post('spell'),
			'sort' 			=> $request->post('sort',20),
			'update_time' 	=> time(),
		];
		
		$data = array_merge($table->getData(),$data);
		$table->data($data)->save();

		return $table->toArray();

	}

	public static function getAll(){
		return Category::order('sort DESC')->order('id DESC')->cache(60)->select();
	}

}