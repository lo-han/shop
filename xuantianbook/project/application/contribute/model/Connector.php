<?php
namespace app\contribute\model;

use think\Model;

class Connector extends Model
{

	/**
	 *	对place进行添加
	 *	@param bookId (array) 一个place的ID数组
	 *	@return 成功返回：数组结果集 (array)
	 *			失败返回：错误字符串 (string)
	 */

	public function createRelation($bookId = [],$source_name,$source_type){

		if(empty($bookId)){ //标签为空时就不用创建关联
			return false;
		}

		$list = [];
		foreach($bookId as $id){
			$list[] 		= [
				'book_id' => $id,
				'source_name' => $source_name,
				'source_type' => $source_type,
			];
		}

		return $this->saveAll($list);

	}


	//获取关联的source_name 的书籍
	public function getConnectorBook($source_name)
	{
		return $this->where(['source_name'=>$source_name])->select();
	}

}