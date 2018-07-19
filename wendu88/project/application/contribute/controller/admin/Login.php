<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Admin;

use \think\Request;
use \think\Session;

class Login extends Common
{

	use AdminCheck;

	public function index(Request $Request){
		$this->checkLogin();	//登陆过的无法进入登陆页面

		if($Request->ispost()){
			
			$validateRule = [
				'account' 		=> 'require',
				'password' 	=> 'require',
			];

			$message = [
				'account.require' 	=> '用户名必须填写',
				'password.require' 	=> '密码必须填写',
			];

			//验证码规则添加
			if($Request->has('captcha')){
				$validateRule['captcha'] = 'require|captcha';
				$message['captcha.require'] = '验证码不能为空';
				$message['captcha.captcha'] = '验证码不正确,请重新填写提交';
			}
			
			if(true === $error = $this->Validate($Request->request(),$validateRule,$message,true)  ){
				
				$user = Admin::login($Request);

				if( $user !== 101 && $user !== 102 ){
					$this->loginSuccess($user->toArray());

					$this->redirect(url('AdminBook'));
				}else{
					$error = [];
					$error['login'] = config('userLogin.' . $user);
				}
					
			}

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}

		return $this->fetch();
	}

	//用户登陆成功
	protected function loginSuccess($value = []){
		Session::set('admin',$value);
	}

	public function loginOut(){
		Session::delete('admin');
		$this->redirect(url('AdminLogin'));
	}

}