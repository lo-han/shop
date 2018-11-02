<?php
namespace app\contribute\controller;

use \think\Controller;
use \think\Session;
use \think\Route;
use \think\Response;

use \api\Xml;

//入口控制器
class Common extends Controller
{

	//暂存错误
	public function errorFlash($errors = [],$url){
		foreach($errors as $name=>$value){
			Session::flash("__flash__." . $name,$value);
		}
		$this->redirect($url);
	}

	/**
	 *	对应的报错页面
	 * 	@param code (int) 响应错误
	 *	@return (text/html) 响应 error/$code 对应的模板 以及http状态
	 */
	public function returnCode($code = 404){
		$response = Response::create($this->fetch("error/" . $code),'',$code);
		$response->header('Content-type','text/html');
		return $response;
	}

	/**
	 *	接口的xml返回
	 * 	@param data (array) 数组格式内容
	 * 	@param code (array) http响应状态
	 *	@return (text/xml) 返回xml格式数据
	 */
	public function returnXml($data = [],$code = 200){
		$response = Response::create(
			(new Xml)->arrayToXml($data),
			'',
			$code
		);
		$response->header('Content-type','text/xml');
		return $response;
	}

	//返回json错误
	public function jsonError($data = [],$code = 403){

		$response = Response::create(json_encode($data),'',$code);
		$response->header('Content-type','application/json');
		return $response;
	}


	//返回成功json
	public function jsonSuccess($data = [],$code = 200){
		$response = Response::create(json_encode($data),'',$code);
		$response->header('Content-type','application/json');
		return $response;
	}


	//验证用户是否登陆
	public function isUserCheck(){
		return Session::has('user');
	}

	//验证管理员是否登陆
	public function isAdminCheck(){
		return Session::has('admin');
	}

}