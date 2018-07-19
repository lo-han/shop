<?php
namespace app\contribute\controller\user;

use \app\contribute\controller\Common;
use \app\contribute\controller\UserCheck;

use \app\contribute\model\BookSection;
use \app\contribute\model\Book;

use \think\Request;
use \think\Session;

class Section extends Common
{
	/**
	 *	通过添加 UserCheck 类实现用户权限的规则
	 */
	use UserCheck;

	public function _initialize(){
		$this->check();

		$Request = Request::instance();
		$this->assign('formUrl',url('UserSectionAdd',['book_id' => $Request->route('book_id',0)]));	//提交表单的URL
	}


	//章节的验证规则
	protected $validateRule = [

		'title' 			=> 'require|max:250',
		'content' 			=> 'require|max:20000',			//编辑器内容验证
		'attr' 				=> 'require|in:1,2',

	];
	//章节验证的报错信息
	protected $message = [

		'title.require' 		=> '必须填写章节标题',
		'title.max' 			=> '标题过长最多250字内',
		'content.require' 		=> '必须填写章节内容',		//编辑器内容验证
		'content.max' 			=> '章节内容过大',			//编辑器内容验证
		'attr.require' 			=> '章节类型未勾选！！',
		'attr.in' 				=> '您选择的章节类型有误',

	];


	//章节添加
	public function add(Request $Request,BookSection $section,Book $book){
		$book_id = $Request->route('book_id');

		/*对书本审核状态判断*/
		/*if($book->where("id",$book_id)->value('check') == 0){
			$this->redirect(url('UserBookList'));	//章节列表跳转
		}*/
		
		if($Request->ispost()){
			//对章节进行验证
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){
				$error = [];	//错误变量 转换为数组

				if( $sectionData = $section->upOrCreate($Request, $book_id ,session('user.id') ) ){

					
					$this->redirect(url('UserSctionList',['book_id' => $book_id]));	//章节列表跳转
				}else{
					$error['error'] = '系统错误请重新尝试';
				}
			}

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}
		$titleDes = sprintf(config('section.default_title'),$section->number($book_id)+1); //章节标题的预描述

		$this->assign('sort',$section->number($book_id)+1);
		$this->assign('titleDes',$titleDes);
		$this->assign('book_id',$book_id);

		return $this->fetch();
	}


	//章节列表
	public function list(Request $Request,BookSection $section){
		$book_id = $Request->route('book_id');

		//获取所有书的章节
		$section = $section->where('book_id', $book_id)->order('sort DESC')->order('id DESC')->paginate(8);
		
		$this->assign('book_id',$book_id);
		$this->assign('list',$section);
		return $this->fetch();
	}


	//章节修改
	public function edit(Request $Request,BookSection $section){
		$book_id 	= $Request->route('book_id');	//书本ID
		$section 	= $section->get($Request->route('id'));		//获取章节

		if($section['check'] == 1){
			$this->redirect(url('UserSctionList',['book_id' => $book_id]));	//已审核时返回列表页
		}


		$this->assign('data',$section);
		$this->assign('book_id',$book_id);
		
		return $this->fetch('add');
	}




}