<?php
namespace app\contribute\model;

use think\Model;

class News extends Model
{


	/**
	 *	对资讯进行修改
	 *	@param request 请求集
	 *	@param user_id 用户ID
	 *	@return bool 是否创建成功
	 */

	public function upOrCreate($request,$admin_id){

		if($request->has('id')){
			$news = $this->get($request->post('id'));
		}else{
			$news = $this->data([
				'create_time' 	=> time(),
				'admin_id' 		=> $admin_id,
			]);
		}
		
		$data = [
			'admin_id' 		=> $admin_id,
			'title' 		=> $request->post('title'),
			'cover' 		=> $request->post('cover'),
			'content' 		=> $request->post('content'),
			'description' 	=> $request->post('description'),
			'check'			=> $request->post('check'),
			'update_time' 	=> time(),
		];
		
		$data = array_merge($news->getData(),$data);
		$news->data($data)->save();

		return $news->toArray();

	}

	/**
	 *	通过搜索获取资讯
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function newsGet($where = [],$limit){
		return $this->where($where)->order('id DESC')->paginate($limit,false, ['query' => request()->param()]);
	}
	

	/**
	 *	模型单项关联 Admin 表
	 */
	public function admin()
    {
        return $this->hasOne('Admin','id','admin_id');
    }

}