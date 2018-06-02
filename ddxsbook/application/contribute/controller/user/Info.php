<?php
namespace app\contribute\controller\user;

use \app\contribute\controller\Common;
use \app\contribute\controller\UserCheck;

use \app\contribute\model\User;

use \think\Request;
use \think\Session;

class Info extends Common
{

	use UserCheck;

	public function _initialize(){
		$this->check();

	}

	protected $validateRule = [
		'description' 	=> 'length:20,100',
	];

	protected $message = [
		'description.length' 	=> '简介必须在20-100字内',
	];

	//用户信息完善
	public function edit(Request $Request,User $user){

		if($Request->ispost()){

			//验证修改信息
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){

				$user = $user->get(session("user.id"));
				$data = [
					'image' 		=> $Request->post('image',''),
					'description' 	=> $Request->post('description',''),
					'update_time' 	=> time(),
				];

				$user->save($data); //用户信息修改

				$this->redirect(url('UserInfoEdit'));
			}

			$data = $Request->request();
			$this->assign("data",$data);
			$this->assign('error',$error);
		}else{
			$data = $user->get(session("user.id"));
			$this->assign("data",$data);
		}
		
		return $this->fetch();
	}

}