<?php
namespace app\contribute\controller;

use \think\Session;
use traits\controller\Jump;

trait UserCheck
{
	use Jump;

	//检验用户是否登陆
	public function check(){

		if( !Session::has('user') ){
			$this->redirect(url('UserLogin'));
		}

	}

	public function checkLogin(){

		if( Session::has('user') ){
			$this->redirect(url('UserBookList'));
		}

	}

	
}