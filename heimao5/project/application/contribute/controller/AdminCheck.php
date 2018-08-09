<?php
namespace app\contribute\controller;

use \think\Session;
use traits\controller\Jump;

trait AdminCheck
{
	use Jump;

	//检验管理员是否登陆
	public function check(){
		
		if( !Session::has('admin') ){
			$this->redirect(url('AdminLogin'));
		}

	}

	public function checkLogin(){

		if( Session::has('admin') ){
			$this->redirect(url('AdminBook'));
		}

	}

}