<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\User as userModel;
use \app\contribute\model\Admin;

use \think\Request;

class User extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();

	}

	protected $validateRule = [
		'description' 	=> 'length:20,100',
	];

	protected $message = [
		'description.length' 	=> '简介必须在20-100字内',
	];


	public function index(Request $Request,userModel $user,Admin $admin){

		/* 管理员权限判断 */
		if(session("admin.role") == 1){

			$where = [];

			//审核 搜索条件
			if($Request->post('adminName','') != null){
				$admin = $admin->whereLike('name',"%" . $Request->post('adminName') . "%")->select();
				$admin_id = implode(',',array_column($admin,'id'));

				$where['admin_id'] = ['in',$admin_id];
			}

		}else{
			$where['admin_id'] = session("admin.id");
		}

		//ID 搜索条件
		if($Request->post('name','') != null){
			$where['name'] = ['like', '%' . $Request->post('name') . '%'];
		}

		//审核 搜索条件
		if($Request->post('pen_name','') != null){
			$where['pen_name'] = ['like', '%' . $Request->post('pen_name') . '%'];
		}

		//获取用户数据
		$userPage = $user->userGet($where,10);

		$this->assign('search',$Request->Request());
		$this->assign('users',$userPage);
		return $this->fetch();
	}

	//用户修改
	public function edit(Request $Request,userModel $user){
		$admin 	= Admin::getAll();

		if($Request->ispost()){

			//验证修改信息
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){
				
				$user = $user->get($Request->post('id'));
				$data = [
					'image' 		=> $Request->post('image',''),
					'description' 	=> $Request->post('description',''),
					'update_time' 	=> time(),
				];

				if($Request->has('admin_id')){
					$data['admin_id'] = $Request->post('admin_id');
				}
				$user->save($data); //用户信息修改

				$this->redirect(url('AdminUser'));
			}

			$data = $Request->request();
			$this->assign("data",$data);
			$this->assign('error',$error);
		}else{
			$data = $user->get($Request->route('id'));
			$this->assign("data",$data);
		}

		$this->assign('admins',$admin);
		return $this->fetch();
	}

}