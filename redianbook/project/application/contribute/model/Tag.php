<?php
namespace app\contribute\model;

use think\Model;
use \think\validate;

class Tag extends Model
{

	public function tagTake($book_id){

	}

	/**
	 *	对tag进行添加
	 *	@param tags (array) 一个tag的数组
	 *	@param limit (int) 限制添加标签的个数
	 *	@return 成功返回：数组结果集 (array)
	 *			失败返回：错误字符串 (string)
	 *			无需添加标签时：返回空数组 (array)
	 */

	public function tagPut($tags = [],$limit = 5){

		if(empty($tags)){	//空标签就返回空数组
			return [];
		}

		if(count($tags) > $limit){
			return '超出添加标签数';
		}

		$list = [];
		$exist = [];
		foreach($tags as $tag){

			if( $find = self::get(['name' => $tag]) ){	//判断是否已经存在标签 存在则获取值并跳出
				$exist[] = $find;
				continue;
			}
			
			$list[] 		= [
				'name' => $tag,
				'create_time' => time(),
			];
		}

		if(!isset($list) && !isset($exist)){	//如果不存在标签或是未添加标签返回空
			return [];
		}
		//验证是否有未通过的
		$tags = $this->validate(true)->saveAll($list);
		if( is_array($tags) ){
			return isset($exist) ? array_merge($exist,$tags) : $tags;
		}else{
			return $this->getError();
		}

	}


	/**
	 *	模型多项关联 TagRelation 表
	 */
	public function tagRelations()
    {
        return $this->hasMany('TagRelation','tag_id');
    }
  

}