<?php
namespace app\contribute\controller\user;

use \app\contribute\controller\Common;
use \app\contribute\controller\UserCheck;

use \app\contribute\model\Book as BookModel;
use \app\contribute\model\Category;
use \app\contribute\model\Tag;
use \app\contribute\model\TagRelation;

use \think\Request;
use \think\Session;

class Book extends Common
{

	use UserCheck;

	public function _initialize(){
		$this->check();

		$this->assign('formUrl',url('UserBookAdd'));	//提交表单的URL
	}

	//书本的验证规则
	protected $validateRule = [

		'category_id' 		=> 'require',
		'title' 			=> 'require|max:50',
		'cover' 			=> 'require',
		'description' 		=> 'require|min:20|max:100',
		'copyright' 		=> 'require|in:1,2',
		'status' 			=> 'require|in:1,2',

	];
	//书本验证的报错信息
	protected $message = [

		'category_id' 		=> '必须选择类型',
		'title.require' 	=> '必须填写书名',
		'title.max' 		=> '您的书名超过了50字',
		'cover' 			=> '必须上传封面图片',
		'description.require' 		=> '必须填写简介',
		'description.min' 	=> '简介不能少于20字',
		'description.max' 	=> '您的简介超过了100字',
		'copyright.require'	=> '授权类型未勾选！！',
		'copyright.in' 		=> '您选择的类型有误',
		'status.require' 	=> '状态未勾选！！',
		'status.in' 		=> '您选择的类型有误',

	];

	//书本添加
	public function add(Request $Request,BookModel $book,Tag $tag,TagRelation $tagRelation){

		$category = Category::getAll();

		//通过是否有post 传输来进行添加数据
		if($Request->ispost()){
			
			//书本的 （添加&验证）
			if(true === $error = $this->Validate($Request->request(),$this->validateRule,$this->message,true)  ){
				$error = [];	//错误变量 转换为数组

				/**
				 *	先对tag标签进行(验证&添加)
				 */
				$tags = $tag->tagPut(tag_explode( $Request->post('tags') ));	//添加tag标签并且验证
				if( is_array($tags) ){
						
					if( $bookData = $book->upOrCreate($Request, session('user.id') ) ){

						$tagRelation->createRelation(array_column($tags, 'id'),$bookData['id']);		//创建书籍和标签的关联
						$this->redirect(url('UserBookList'));	//书本添加成功返回列表页面

					}else{
						$error['error'] = '系统错误请重新尝试';
					}

				}else{
					$error['tag'] = $tags;					
				}
			}

			if(!isset($error['cover'])){
				
				$error['cover'] = "请重新上传图片";	

			}	//只要验证没通过就必须重新上传图片

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}

		$this->assign('category',$category);

		return $this->fetch();
	}


	//书本列表
	public function list(Request $Request,BookModel $book){
		$books = $book->userBook(session('user.id'));

		$this->assign('list',$books);
		return $this->fetch();
	}


	//书本修改
	public function edit(Request $Request,BookModel $book,Tag $tag,TagRelation $tagRelation){
		$book = $book->get($Request->route('id'));
		$category = Category::getAll();

		if($book['check'] == 1){
			$this->redirect(url('UserBookList'));	//已审核时返回列表页
		}

		//获取tag 对象添加
		if( false === $tags = $tagRelation->getRelationTag($book->id)){
			$book->setAttr('tags', $tags );
		}else{
			$book->setAttr('tags',implode( ",",array_column( $tagRelation->getRelationTag($book->id) , "name") ));
		}
		

		$this->assign('data',$book);
		$this->assign('category',$category);
		return $this->fetch('add');
	}


}