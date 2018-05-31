<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Book as BookModel;
use \app\contribute\model\BookSection;
use \app\contribute\model\Category;
use \app\contribute\model\User;
use \app\contribute\model\Admin;
use \app\contribute\model\Tag;
use \app\contribute\model\TagRelation;
use \app\contribute\model\PlaceRelation;
use \app\contribute\model\Place;

use \think\Request;
use \think\Db;

class Book extends Common
{

	use AdminCheck;

	public function _initialize(){

		$this->check();

	}


	//书本的验证规则
	protected $validateRule = [

		'category_id' 		=> 'require',
		'title' 			=> 'require|max:50',
		'cover' 			=> 'require',
		'description' 		=> 'require|length:20,100',
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
		'description.length'=> '简介必须在20-100字内',
		'copyright.require'	=> '授权类型未勾选！！',
		'copyright.in' 		=> '您选择的类型有误',
		'status.require' 	=> '状态未勾选！！',
		'status.in' 		=> '您选择的类型有误',

	];

	public function index(Request $Request,BookModel $books,User $user,Admin $admin){
		$category = Category::getAll();		//获取所有类型
		

		/* 管理员权限判断 */
		if(session("admin.role") == 1){
			$where = [];

			//对应制作人的书本进行查询
			if($Request->request('adminName','') != null){
				$admin = $admin->whereLike('name',"%" . $Request->request('adminName') . "%")->select();
				$admin_id = implode(',',array_column($admin,'id'));

				$where['admin_id'] = ['in',$admin_id];
			}

		}else{
			$where['admin_id'] = session("admin.id");
		}
		

		//ID 搜索条件
		if($Request->request('id','') != null){
			$where['id'] = $Request->request('id');
		}

		//标题 搜索条件
		if($Request->request('title','') != null){
			$where['title'] = ['like',"%" . $Request->request('title') . "%"];
		}

		//对应用户名的书本进行查询
		if($Request->request('userName','') != null){
			$user = $user->whereLike('pen_name',"%" . $Request->request('userName') . "%")->select();
			$user_id = implode(',',array_column($user,'id'));

			$where['user_id'] = ['in',$user_id];
		}

		//作品类型 搜索条件
		if($Request->request('category_id','') != null){
			$where['category_id'] = $Request->request('category_id');
		}

		//授权 搜索条件
		if($Request->request('copyright','') != null){
			$where['copyright'] = $Request->request('copyright');
		}

		//状态 搜索条件
		if($Request->request('status','') != null){
			$where['status'] = $Request->request('status');
		}

		//审核 搜索条件
		if($Request->request('check','') != null){
			$where['check'] = $Request->request('check');
		}
		
		$count = $books->bookCount($where);	//获取统计条数
		$booksPage = $books->bookGet($where,5);	//获取数据
		
		
		$this->assign('copyright',config('book.copyright'));	//授权类型
		$this->assign('status',config('book.status'));			//状态
		$this->assign('check',config('check'));					//审核状态
		$this->assign('count',$count);						//书本总量
		$this->assign('category',$category);
		$this->assign('search',$Request->Request());
		$this->assign('books',$booksPage);
		return $this->fetch();
	}


	//书本审核编辑
	public function edit(Request $Request,BookModel $book,Tag $tag,TagRelation $tagRelation,PlaceRelation $PlaceRelation){
		$category 	= Category::getAll();
		$admin 		= Admin::getAll();
		$place 		= Place::getAll();

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
						
					if( $bookData = $book->upOrCreate($Request, $Request->post('user_id') ) ){

						$tagRelation->createRelation(array_column($tags, 'id'),$bookData['id']);		//创建书籍和标签的关联

						$PlaceRelation->createRelation( array_filter(explode(',',$Request->post('place'))) ,$bookData['id']);		//创建书籍和分销商的关联

						$this->redirect(url('AdminBook'));	//书本添加成功返回列表页面

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

		}else{	//对书本进行展示
			$book = $book->get($Request->route('id'));

			//获取tag 对象添加
			$book->setAttr('tags',$tagRelation->getRelationTag($book->id));

			//获取tag并且拼接
			$book->setAttr('place',$PlaceRelation->getRelationPlace($book->id));

			$this->assign('data',$book);
		}

		$this->assign('formUrl',url('AdminBookEdit',['id'=>$Request->route('id')]));
		$this->assign('category',$category);
		$this->assign('admins',$admin);
		$this->assign('places',$place);

		return $this->fetch();
	}


	//书籍章节导入
	public function lead(Request $Request,BookSection $section)
	{


		if($Request->ispost())
		{
			$book_id 	= $Request->route('id');	//书本ID
			$user_id 	= BookModel::get($book_id)->user_id;	//用户ID
			
			$attrStart 	= $Request->post('attrStart');
			$details = array_filter(explode("###",$Request->post('section')));
			$Request->request();
			$i=1;
			foreach($details as $key=>$val)
			{

				$ct = [];
				
				foreach(explode("\n",$val) as $k=>$v )
				{
					if( $k == 0)
					{
						$title = $v;
					}
					else
					{
						$ct[] = '<p>' . $v . '</p>';
					}
				}
				
				$content = implode("",$ct);

				$Request->attrSave('title',$title);
				$Request->attrSave('content',$content);
				
				if($i > $attrStart){
					$Request->attrSave('attr',2);
				}else{
					$Request->attrSave('attr',1);
				}

				/*if($i == 2){
					$Request->attrSave('section',"1");
					var_dump($Request->request(),$book_id , $user_id );exit;
				}*/

				$i++;
				
				if( !$sectionData = (new BookSection)->upOrCreate($Request, $book_id , $user_id ) )
				{
					$error['error'] .= '<p>失败章节：' . $title . '</p>';
					break;
				}
	
			}

			if( !isset($error) )
			{
				$this->redirect($Request->post('referer'));	//书本添加成功返回列表页面
			}
			$this->assign('error',$error);
		}

		$this->assign('formUrl',url('AdminBookLead',['id'=>$Request->route('id')]));
		return $this->fetch();

	}


}