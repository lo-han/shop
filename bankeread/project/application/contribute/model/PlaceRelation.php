<?php
namespace app\contribute\model;

use think\Model;

use app\contribute\model\Place;

class PlaceRelation extends Model
{

	/**
	 *	对place进行添加
	 *	@param placesId (array) 一个place的ID数组
	 *	@return 成功返回：数组结果集 (array)
	 *			失败返回：错误字符串 (string)
	 */

	public function createRelation($placesId = [],$bookId){

		//对原有的关联进行删除
		//在进行重新构造
		$this->destroy(['book_id' => $bookId]);

		if(empty($placesId)){ //标签为空时就不用创建关联
			return false;
		}

		$list = [];
		foreach($placesId as $placeId){

			$list[] 		= [
				'place_id' => $placeId,
				'book_id' => $bookId,
			];

		}

		return $this->saveAll($list);

	}


	/**
	 *	对渠道商的赋权书籍进行管理
	 *	@param booksId (array) 一个book的ID数组
	 *	@return 成功返回：数组结果集 (array)
	 *			失败返回：错误字符串 (string)
	 */

	public function distributionOfBooks($booksId = [],$placeId){

		//对原有的关联进行删除
		//在进行重新构造
		$this->destroy(['place_id' => $placeId]);

		if(empty($booksId)){ //标签为空时就不用创建关联
			return false;
		}

		$list = [];
		foreach($booksId as $bookId){

			$list[] 		= [
				'place_id' => $placeId,
				'book_id' => $bookId,
			];

		}

		
		return $this->saveAll($list);

	}

	/**
	 *	获取相关书籍的place分销商对象
	 *	@param book_id (int) 书籍ID 
	 *	@return false (bool) 不存在关联
	 *			place (class) 关联的place对象
	 */

	public function getRelationPlace($book_id){
		$place_id = self::where('book_id =' . $book_id)->column("place_id");

		if(empty($place_id)){
			return false;
		}

		return Place::all($place_id);
	}

	/**
	 *	模型单项关联 tag 表
	 */
	public function place()
    {
        return $this->hasOne('Place','id','place_id');
    }

    /**
	 *	模型单项关联 book 表
	 */
	public function book()
    {
        return $this->hasOne('Book','id','book_id');
    }

}