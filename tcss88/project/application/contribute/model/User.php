<?php
namespace app\contribute\model;

use think\Model;

class User extends Model
{

	//用户登陆
	public static function login($request){

		$user = self::where([
			'name' => $request->post('name'),
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

	//用户注册
	public function upOrCreate($request,$user_id = "",$admin_id = ""){
		if($user_id != ""){
			$user = $this->get($user_id);
		}else{
			$user = $this->data([
				'create_time' 	=> time(),
				'update_time' 	=> time(),
			]);
		}

		$data = [
			'admin_id'	=> $admin_id,
			'name' 		=> $request->post('name'),
			'pen_name' 	=> $request->post('pen_name'),
			'password' 	=> passGen($request->post('password'),$user->password),
			'status' 	=> 1,
			'ip' 		=> $request->ip(),
		];

		$data = array_merge($user->getData(),$data);

		$user->data($data)->save();

		return $user->toArray();		

	}

	public static function getAll(){
		return self::order('id DESC')->cache(60)->select();
	}

	/**
	 *	通过搜索获取书籍
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function userGet($where = [],$limit){
		return $this->where($where)->order('id DESC')->paginate($limit);
	}

	/**
	 *	前端用户展示
	 *	@param where (array) 搜索条件
	 *	@param limit (int)   搜索条数
	 *	@return  think\paginator\driver\Bootstrap 分页模型
	 */

	public function homeUserShow($pattern='newest',$limit){

		switch($pattern){
			case 'newest': $order = "u.id DESC";break;
			case 'works': $order = "bookCount DESC";break;
			default : $order = "u.id DESC";break;
		}

		return $this->field("count(b.id) as bookCount,u.*")
		->alias("u")
		->join('book b','u.id = b.user_id AND b.check = 1',"LEFT")
		->group("u.id")
		->order($order)
		->paginate($limit);

	}

	/**
	 *	模型单项关联 Admin 表
	 */
	public function admin()
    {
        return $this->hasOne('Admin','id','admin_id');
    }

    /**
	 *	模型多项关联 Book 表
	 */
	public function book()
    {
        return $this->hasMany('book','user_id');
    }

}