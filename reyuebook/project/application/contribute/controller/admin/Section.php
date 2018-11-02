<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\BookSection;
use \app\contribute\model\Book;

use \think\Request;
use \think\Response;

class Section extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();
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

	//章节列表
	public function index(Request $Request,BookSection $section,Book $book){

		//判断是否通过某本书籍进入的
		if($Request->has('book_id')){
			//管理员权限判断
			if(session("admin.role") != 1){

				if( !$book->where('admin_id = ' . session("admin.id") . ' AND id =' .$Request->route('book_id') )->find() ){
					$this->redirect(url('AdminBook'));	//非法进入跳转
				}
			}
			
			$where['book_id'] = $Request->route('book_id');
		}else{
			$where['check'] = 0;

			//管理员权限判断
			if(session("admin.role") != 1){
				$book = $book->where('admin_id = ' . session("admin.id") )->select();
				$book_id = implode(',',array_column($book,'id'));

				$where['book_id'] = ['in',$book_id];
			}

		}


		//对章节标题进行搜索
		if($Request->post('title',null) != null){
			$where['title'] = ['like', "%" . $Request->post('title') . "%"];
		}

		//对书本名称进行搜索
		if($Request->post('bookName',null) != null){
			$book = $book->whereLike('title',"%" . $Request->post('bookName') . "%")->select();
			$book_id = implode(',',array_column($book,'id'));

			$where['book_id'] = ['in',$book_id];
		}

		$sectionPage = $section->sectionGet($where,15);	//获取章节信息
		
		$this->assign('book_id',$Request->route('book_id'));
		$this->assign('search',$Request->Request());
		$this->assign('sections',$sectionPage);

		return $this->fetch();
	}

	//对章节进行编辑
	public function edit(Request $Request,BookSection $section,Book $book){
		$book_id 	= $Request->route('book_id');	//书本ID
		$id 		= $Request->route('id');		//章节ID

		if($Request->ispost()){
			//对章节进行验证
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){
				$error = [];	//错误变量 转换为数组

				if( $sectionData = $section->upOrCreate($Request, $book_id ,$Request->post('user_id') ) ){
					
					$this->redirect($Request->post('referer'));	//章节列表跳转
				}else{
					$error['error'] = '系统错误请重新尝试';
				}
			}

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}else{
			$section 	= $section->get($id);		//获取章节

			$this->assign('data',$section);
		}

		$this->assign('book_id',$book_id);
		$this->assign('formUrl',url('AdminSectionEdit',['book_id' => $book_id,'id' => $id]));
		return $this->fetch();
	}

	public function delete(Request $Request,BookSection $section)
	{
		$id = $Request->route('id');
		$book_id = $Request->route('book_id');
		
		$section->deletes($id,$book_id);

		$this->redirect( $Request->server('HTTP_REFERER',url('AdminBook')) );
	}

	public function clearBook(Request $Request,BookSection $section)
	{
		$book_id = $Request->route('book_id');
		
		$sectionAll = $section->all(['book_id'=>$book_id]);

		$ids = implode(",",array_column($sectionAll, 'id'));
		$section->deletes($ids,$book_id);

		$this->redirect( $Request->server('HTTP_REFERER',url('AdminBook')) );
	}


	public function exportBook(Request $Request,BookSection $section,Book $book)
	{
		$book_id = $Request->route('book_id');
		$book = $book->get($book_id);

		$sectionAll = $section->where(['book_id'=>$book_id])->order('sort','ASC')->select();

		$content = "#title# " . $book->title . "\r\n";
		
		foreach($sectionAll as $val)
		{
			$val->content = str_replace("\r", "", $val->content);	//格式完善 每一段添加\n
			$val->content = str_replace("&nbsp;", "", $val->content);	//格式完善 每一段添加\n
			$val->content = str_replace('</p>', "\n</p>", $val->content);	//格式完善 每一段添加\n
			$val->content = strip_tags($val->content);	//清除html标签

			$content .= "###" . $val->title . "\r\n";
			$content .= $val->content . "\r\n";
		}
		/**
		 *	响应内容
		 */
		$response = Response::create(
			$content
		);

		//设置response 头部
		$response->header('Content-type','application/octet-stream')->header('Content-Disposition','attachment;   filename=' . $book->title . '.txt');

		return $response->send();

	}
	

}