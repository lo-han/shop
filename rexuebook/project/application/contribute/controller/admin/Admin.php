<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Admin as ModelAdmin;

use \think\Request;
use \think\Db;

class Admin extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}


	//管理员的验证规则
	protected $validateRule = [

		'name' 				=> 'require|max:10|unique:admin',
		'account' 			=> 'require|max:20|unique:admin',
		'email' 			=> 'email',
		'description' 		=> 'length:20,100',
		'role' 				=> 'in:1,2',

	];
	//管理员的报错信息
	protected $message = [

		'name.require' 				=> '名称不能为空',
		'name.max'	 				=> '名称长度不能超过10个字符',
		'name.unique' 				=> '名称已被注册',
		'account.require' 			=> '账号不能为空',
		'account.max'	 			=> '账号长度不能超过20个字符',
		'account.unique'	 		=> '账号已被注册',
		'email.email' 				=> '邮箱格式不对',
		'description.length' 		=> '简介必须在20-100字内',
		'role.in' 					=> '人员类型有误',

	];

	public function index(Request $Request,ModelAdmin $admin){
		//不是管理员的不能进入 制作人列表
		if(session('admin.role') == 2){
			return $this->returnCode(404);
		}
		$admin = $admin->adminGet(['id'=>['<>',1]],10);


		$this->assign('admin',$admin);
		return $this->fetch();
	}

	public function add(Request $Request,ModelAdmin $admin){

		if($Request->ispost()){

			//书本的 （添加&验证）
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){

				$admin->upOrCreate($Request,$Request->post('id'));

				if(session('admin.role') == 2){
					$this->redirect( url('AdminBook') );	
				}else{
					$this->redirect(url('AdminMaker'));	
				}

			}

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}

		$this->assign('role',config('role'));
		return $this->fetch();
	}

	public function edit(Request $Request){
		$admin = ModelAdmin::get($Request->route('id'));
		//不是管理员的不能进入 制作人修改页面
		if(session('admin.role') != 1 && $Request->route('id') != session('admin.id') ){
			return $this->returnCode(404);
		}

		$this->assign('role',config('role'));
		$this->assign('data',$admin);
		return $this->fetch('add');

	}



}