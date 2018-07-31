<?php
namespace app\contribute\model;

use think\Model;

use app\contribute\model\Tag;

class TagRelation extends Model
{


	/**
	 *	对tag进行添加
	 *	@param tags (array) 一个tag的数组
	 *	@return 成功返回：数组结果集 (array)
	 *			失败返回：错误字符串 (string)
	 */

	public function createRelation($tagsId = [],$bookId){

		//对原有的关联进行删除
		//在进行重新构造
		$this->destroy(['book_id' => $bookId]);	

		if(empty($tagsId)){ //标签为空时就不用创建关联
			return false;
		}

		$list = [];
		foreach($tagsId as $tagId){

			$list[] 		= [
				'tag_id' => $tagId,
				'book_id' => $bookId,
			];

		}

		return $this->saveAll($list);

	}

	/**
	 *	获取相关书籍的tag标签对象
	 *	@param book_id (int) 书籍ID 
	 *	@return false (bool) 不存在关联
	 *			tag (class) 关联的tag对象
	 */

	public function getRelationTag($book_id){
		$tag_id = self::where('book_id =' . $book_id)->column("tag_id");

		if(empty($tag_id)){
			return false;
		}

		return tag::all($tag_id);
	}


	/**
	 *	模型单项关联 tag 表
	 */
	public function tag()
    {
        return $this->hasOne('Tag','id','tag_id');
    }

    /**
	 *	模型单项关联 book 表
	 */
	public function book()
    {
        return $this->hasOne('Book','id','book_id');
    }

}