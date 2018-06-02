<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\News as ModelNews;

use \think\Request;

class News extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();

	}


	//资讯的验证规则
	protected $validateRule = [

		'title' 			=> 'require|max:50',
		'cover' 			=> 'require',
		'content' 			=> 'require',
		'description' 		=> 'require|min:20|max:500',
		'check' 			=> 'require|in:0,1',

	];
	//资讯验证的报错信息
	protected $message = [

		'title.require' 	=> '必须填写标题',
		'title.max' 		=> '您的标题超过了50字',
		'cover' 			=> '必须上传封面图片',
		'description.require' 		=> '必须填写简介',
		'description.min' 	=> '简介不能少于20字',
		'description.max' 	=> '您的简介超过了500字',
		'content.require'	=> '必须填写内容！',
		'check.require' 	=> '状态未勾选！！',
		'check.in' 			=> '您选择的类型有误',

	];


	//资讯列表
	public function index(ModelNews $news){
		$news = $news->newsGet([],10);


		$this->assign('news',$news);
		return $this->fetch();
	}


	//资讯添加
	public function add(Request $Request,ModelNews $news){

		if($Request->ispost()){

			//资讯的 （添加&验证）
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){
				$error = [];	//错误变量 转换为数组

				if( $newsData = $news->upOrCreate($Request, session("admin.id") ) ){

					$this->redirect(url('AdminNews'));	//添加成功返回列表页面

				}

			}

			//报错时传入错误以及原有信息
			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}

		return $this->fetch();
	}

	//资讯修改
	public function edit(Request $Request,ModelNews $news){
		$news = $news->get($Request->route('id'));

		$this->assign('data',$news);
		return $this->fetch('add');
	}

}