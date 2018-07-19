<?php
namespace app\contribute\model;

use think\Model;

class BookSection extends Model
{


	/**
	 *	对书籍进行修改或是添加
	 *	@param request 请求集
	 *	@param book_id 书本ID
	 *	@param user_id 用户ID
	 *	@return bool 是否创建成功
	 */

	public function upOrCreate($request,$book_id,$user_id){

		/**
		 * @param $char	需要对书本的总数字进行统计，所以需要统计出更新的字和原本的差值
		 */
		
		if($request->has('id','post')){
			$bookSection = $this->get($request->post('id'));
			$char = charNumber($request->post('content'))-$bookSection->getAttr('char');	
		}else{
			$bookSection = $this->data([
				'create_time' 	=> time(),
				'sort'			=> $this->number($book_id)+1,	//sort 自增
			]);
			$char = charNumber($request->post('content'));
		}

		$data = [
			'user_id' 		=> $user_id,
			'book_id' 		=> $book_id,
			'title' 		=> $request->post('title'),
			'content' 		=> $request->post('content'),
			'char' 			=> charNumber($request->post('content')),
			'attr' 			=> $request->post('attr'),
			'check' 		=> $request->post('check',0),
			'update_time' 	=> time(),
		];
		//当前篇章的序号
		//只有对sort 修改才可以修改 否则默认自增
		if($request->has('sort')){
			$data['sort'] = $request->post('sort');
		}

		
		$data = array_merge($bookSection->getData(),$data);
		$bookSection->data($data)->save();

		db('Book')->where(['id' => $book_id])->setInc('char_number',$char); //找到相应的书籍进行字数统计

		return $bookSection->toArray();

	}


	public function deletes($ids,$book_id)
	{
		$char = 0;
		$all = $this->all($ids);

		foreach($all as $val)
		{
			$char += charNumber($val->content);
		}

		$this->destroy($ids);

		db('Book')->where(['id' => $book_id])->setDec('char_number',$char); //找到相应的书籍进行字数统计
	}


	/**
	 *	确认某本书的章节量
	 */

	public function number($book_id){
		return $this->where(['book_id' => $book_id])->count();
	}


	/**
	 *	通过搜索获取章节
	 *	@param where (array) 搜索条件
	 *	@return  int 统计条数
	 */

	public function sectionCount($where = []){
		return $this->where($where)->count();
	}

	/**
	 *	通过搜索获取章节
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function sectionGet($where = [],$limit){
		return $this->where($where)->order('id DESC')->paginate($limit);
	}

	/**
	 *	通过搜索获取章节
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function sectionFind($where = []){
		return $this->where($where)->order('id DESC')->find();
	}

	/**
	 *	模型单项关联 book 表
	 */
	public function book()
    {
        return $this->hasOne('Book','id','book_id');
    }

    /**
	 *	模型单项关联 user 表
	 */
	public function user()
    {
        return $this->hasOne('User','id','user_id');
    }


}