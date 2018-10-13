<?php
namespace app\contribute\model;

use think\Model;

class Book extends Model
{

	/**
	 *	调用用户的书籍
	 *	@param userId 用户的ID 
	 *	@param num 数据条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */
	public function userBook($userId,$num = 6){
		return self::where(['user_id' => $userId])->order('id DESC')->paginate($num);
	}

	/**
	 *	对书籍进行修改或是添加
	 *	@param request 请求集
	 *	@param user_id 用户ID
	 *	@return bool 是否创建成功
	 */

	public function upOrCreate($request,$user_id){

		if($request->has('id')){
			$book = $this->get($request->post('id'));
		}else{
			$book = $this->data([
				'create_time' 	=> time(),
			]);
		}
		
		$data = [
			'user_id' 		=> $user_id,
			'category_id' 	=> $request->post('category_id'),
			'title' 		=> $request->post('title'),
			'cover' 		=> $request->post('cover'),
			'description' 	=> $request->post('description'),
			'copyright' 	=> $request->post('copyright'),
			'status'		=> $request->post('status'),
			'check'			=> $request->post('check',0),
			'update_time' 	=> time(),
		];

		if($request->has('admin_id')){
			$data['admin_id'] = $request->post('admin_id',0);
		}
		
		$data = array_merge($book->getData(),$data);
		$book->data($data)->save();

		return $book->toArray();

	}

	/**
	 *	通过搜索获取书籍
	 *	@param where (array) 搜索条件
	 *	@return  int 统计条数
	 */

	public function bookCount($where = []){
		return $this->where($where)->count();
	}

	/**
	 *	通过搜索获取书籍
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function bookGet($where = [],$limit){
		return $this->where($where)->order('id DESC')->paginate($limit,false, ['query' => request()->param()]);
	}

	/**
	 *	前台用户对书籍的搜索
	 *	@param where (array) 搜索条件
	 *	@param whereOr (arrya) 或者的搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function bookListSearch($where = [],$whereOr = [],$limit){

		return $this->where($where)->where(function ($query) use($whereOr){
			$query->whereOr($whereOr);
		})->order('id DESC')->paginate($limit,false, ['query' => request()->param()]);

	}

	/**
	 *	通过搜索获取书籍
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  app\contribute\model\Book 书籍模型
	 */

	public static function checkBook(){
		return self::where(['check'=>1]);
	}


	/**
	 *	模型单项关联 TagRelation 表
	 */
	public function place()
    {
        return $this->hasOne('Place','id','place_id');
    }

    /**
	 *	模型单项关联 TagRelation 表
	 */
	public function category()
    {
        return $this->hasOne('Category','id','category_id');
    }

    /**
	 *	模型单项关联 User 表
	 */
	public function user()
    {
        return $this->hasOne('User','id','user_id');
    }

    /**
	 *	模型单项关联 Admin 表
	 */
	public function admin()
    {
        return $this->hasOne('Admin','id','admin_id');
    }

	/**
	 *	模型多项关联 TagRelation 表
	 */
	public function tagRelations()
    {
        return $this->hasMany('TagRelation','book_id');
    }

    /**
	 *	模型多项关联 PlaceRelation 表
	 */
	public function placeRelation()
    {
        return $this->hasMany('PlaceRelation','book_id');
    }

    /**
	 *	模型多项关联 bookSection 表
	 */
	public function bookSections()
    {
        return $this->hasMany('BookSection','book_id');
    }


}