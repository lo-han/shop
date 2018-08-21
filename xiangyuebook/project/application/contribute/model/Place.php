<?php
namespace app\contribute\model;

use think\Model;

class Place extends Model
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
			'name' 		=> $request->post('name'),
			'image' 		=> $request->post('image'),
			'url' 			=> $request->post('url'),
			'update_time' 	=> time(),
		];
		
		$data = array_merge($table->getData(),$data);
		$table->data($data)->save();

		return $table->toArray();

	}

	/**
	 *	模型多项关联 PlaceRelation 表
	 */
	public function placeRelation()
    {
        return $this->hasMany('PlaceRelation','place_id');
    }

	//获取所有分销商
	public static function getAll(){
		return self::order('id DESC')->select();
	}

}