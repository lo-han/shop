<?php
namespace app\contribute\controller\user;

use \app\contribute\controller\Common;
use \app\contribute\controller\UserCheck;

use \app\contribute\model\User;

use \think\Request;
use \think\Session;


class Login extends Common
{

	use UserCheck;

	public function loginUp(Request $Request){

		$this->checkLogin();	//登陆过的无法进入登陆页面

		if($Request->ispost()){
			
			$validateRule = [
				'name' 		=> 'require',
				'password' 	=> 'require',
			];

			$message = [
				'name.require' 	=> '用户名必须填写',
				'password.require' 	=> '密码必须填写',
			];

			//验证码规则添加
			if($Request->has('captcha')){
				$validateRule['captcha'] = 'require|captcha';
				$message['captcha.require'] = '验证码不能为空';
				$message['captcha.captcha'] = '验证码不正确,请重新填写提交';
			}
			
			if(true === $error = $this->Validate($Request->request(),$validateRule,$message,true)  ){
				
				$user = User::login($Request);

				if( $user !== 101 && $user !== 102 ){

					$this->loginSuccess($user->toArray());

					$this->redirect(url('UserBookList'));
				}else{
					$error = [];
					$error['login'] = config('userLogin.' . $user);
				}
					
			}

			$this->errorFlash([ 'login'=>['error' => $error,'data' => $Request->request()] ],url('UserLogin'));
		}
		
	}

	public function register(Request $Request,User $user){

		$this->checkLogin();	//登陆过的无法进入注册页面

		if($Request->ispost()){

			$validateRule = [
				'name' 		=> 'require|max:10|unique:user',
				'pen_name' 	=> 'require|max:20|unique:user',
				'password' 	=> 'require|min:6|max:50|confirm:password_affirm',
				'password_affirm' => 'require',
			];

			$message = [
				'name.require' 	=> '用户名必须填写',
				'name.unique' 	=> '用户名已存在',
				'name.max' 		=> '用户名不能大于10个文字',
				'pen_name.require' 	=> '笔名必须填写',
				'pen_name.max' 		=> '笔名不能大于30个文字',
				'pen_name.unique' 	=> '笔名已存在',
				'password.require' 	=> '密码必须填写',
				'password.min' 		=> '密码不能小于6个字符',
				'password.max' 		=> '密码不能大于12个字符',
				'password.confirm' 	=> '密码的确认字段不一致',
				'password_affirm.confirm' => '确认密码不能为空',
			];

			//验证码规则添加
			if($Request->has('captcha')){
				$validateRule['captcha'] = 'require|captcha';
				$message['captcha.require'] = '验证码不能为空';
				$message['captcha.captcha'] = '验证码不正确';
			}
			
			//验证注册
			if(true ===  $error = $this->Validate($Request->request(),$validateRule,$message,true)  ){

				if($Request->has('id')){ //注册时不允许出现ID提交
					$this->redirect(url('UserRegister'));
				}

				$user = $user->upOrCreate($Request); //创建新用户

				$this->loginSuccess($user);		//记录用户session

				$this->redirect(url('UserBookList'));
			}

			$this->errorFlash([ 'register'=>['error' => $error,'data' => $Request->request()] ],url('UserLogin'));
			
		}

	}

	public function login(){

		$this->checkLogin();	//登陆过的无法进入注册页面
		
		if( session('__flash__.register') !== null ){	//判断是登陆还是注册
			$judge = "register";
		}else{
			$judge = "login";
		}

		$this->assign("judge",$judge);
		$this->assign( session('__flash__') );
		return $this->fetch();
	}

	//用户登陆成功
	protected function loginSuccess($value = []){
		Session::set('user',$value);
	}

	//退出登陆
	public function loginOut(){
		Session::delete('user');
		$this->redirect(url('UserLogin'));
	}


}