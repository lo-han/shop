<?php
namespace app\contribute\model;

use think\Model;

class Admin extends Model
{

	//用户登陆
	public static function login($request){

		$user = self::where([
			'account' => $request->post('account'),
		])->find();	//获取账户密码

		if(empty($user)){
			return 101; //账号填写不正确
		}

		//判断用户账号密码是否正确
		if( $user->password == md5(md5($request->post('password'))) ){
			$user->save(['ip' => $request->ip(),'update_time' => time()]);	//修改登陆时的IP
			return $user;
		}else{
			return 102; //密码错误
		}
		
	}

	//管理员添加
	public function upOrCreate($request,$user_id = ""){
		if($user_id != ""){
			$user = $this->get($user_id);
		}else{
			$user = $this->data([
				'create_time' 	=> time(),
				'update_time' 	=> time(),
			]);
		}

		$data = [
			'name' 		=> $request->post('name'),
			'account' 	=> $request->post('account'),
			'direction' => $request->post('direction'),
			'image' 	=> $request->post('image'),
			'email' 	=> $request->post('email'),
			'qq' 		=> $request->post('qq'),
			'wechar_qrcode' => $request->post('wechar_qrcode'),
			'description' 	=> $request->post('description'),
			'password' 	=> passGen($request->post('password'),$user->password),
			'ip' 		=> $request->ip(),
		];

		if($request->has('status')){
			$data['status'] = $request->post('status');
		}
		if($request->has('role')){
			$data['role'] = $request->post('role');
		}

		$data = array_merge($user->getData(),$data);

		$user->data($data)->save();

		return $user->toArray();		

	}

	//获取所有制作人
	public static function getAll(){
		return self::order('id DESC')->where('role',2)->where('status',1)->select();
	}

	/**
	 *	通过搜索获取管理员
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function adminGet($where = [],$limit){
		return $this->where($where)->order('id DESC')->paginate($limit);
	}

	/**
	 *	模型多项关联 Book 表
	 */
	public function book()
    {
        return $this->hasMany('book','admin_id');
    }

}