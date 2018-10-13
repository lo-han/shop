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
use PHPExcel_IOFactory;
use PHPExcel;

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

						$this->redirect($Request->post('referer'));	//书本添加成功返回列表页面

					}else{
						$error['error'] = '系统错误请重新尝试';
					}

				}else{
					$error['tag'] = $tags;					
				}
			}

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

	//书本添加
	public function add(Request $Request,BookModel $book,Tag $tag,TagRelation $tagRelation,PlaceRelation $PlaceRelation){

		$category 	= Category::getAll();
		$admin 		= Admin::getAll();
		$place 		= Place::getAll();
		$user 		= User::getAll();

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

			$this->assign('error',$error);
			$this->assign('data',$Request->request());

		}

		$this->assign('formUrl',url('AdminBookAdd'));
		$this->assign('category',$category);
		$this->assign('admins',$admin);
		$this->assign('places',$place);
		$this->assign('users',$user);

		return $this->fetch();
	}



	//书籍章节导入
	public function lead(Request $Request,BookSection $section)
	{


		if($Request->ispost())
		{
			$book_id 	= $Request->route('id');	//书本ID
			$user_id 	= BookModel::get($book_id)->user_id;	//用户ID
			
			$section = file_get_contents($Request->file('section')->getInfo('tmp_name'));
			$section = iconv(mb_detect_encoding($section),'UTF-8',$section);
			$size = $Request->post('size');

			if( $Request->post('category') == 2 )
			{
				$details = $this->formattingSection($section,$size);
			}else{
				$details = array_filter(explode("###",$section));
			}

			//收费开始章节
			$attrStart 	= $Request->post('attrStart');
			
			if( $Request->file('section')->checkExt('txt') )
			{
				$i=1;
				foreach($details as $key=>$val)
				{
					list($title,$content) = $this->chapterSplit($val);

					$Request->attrSave('title',$title);
					$Request->attrSave('content',$content);
					
					if($i > $attrStart)
					{
						$Request->attrSave('attr',2);
					}else{
						$Request->attrSave('attr',1);
					}

					$i++;
					
					if( !$sectionData = (new BookSection)->upOrCreate($Request, $book_id , $user_id ) )
					{
						$error['error'] = '<p>失败章节：' . $title . '</p>';
						break;
					}
		
				}

			}else{
				$error['error'] = '<p>文件格式不正确</p>';
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

	public function exportExcel()
	{
		$PHPExcel = new PHPExcel(); //实例化PHPExcel类，类似于在桌面上新建一个Excel表格
		$PHPSheet = $PHPExcel->getActiveSheet(); //获得当前活动sheet的操作对象
		$PHPSheet->setTitle('书单'); //给当前活动sheet设置名称
		$PHPSheet->setCellValue('A1','ID')
				->setCellValue('B1','书名')
				->setCellValue('C1','书本简介')
				->setCellValue('D1','作品类别')
				->setCellValue('E1','授权类型')
				->setCellValue('F1','是否连载')
				->setCellValue('G1','总字数')
				->setCellValue('H1','作者笔名');
		//给当前活动sheet填充数据，数据填充是按顺序一行一行填充的，假如想给A1留空，可以直接setCellValue(‘A1’,’’);

		$books = BookModel::checkBook()->select();
		
		foreach($books as $key=>$val)
		{
			$line = $key+2;
			$PHPSheet->setCellValue('A'.$line,$val->id)
					->setCellValue('B'.$line,$val->title)
					->setCellValue('C'.$line,$val->description)
					->setCellValue('D'.$line,$val->category->title)
					->setCellValue(
						'E'.$line,
						config('book.copyright')[$val->copyright]
					)
					->setCellValue(
						'F'.$line,
						config('book.status')[$val->status]
					)
					->setCellValue(
						'G'.$line,
						numberUnit($val->char_number)
					)
					->setCellValue('H'.$line,$val->user->pen_name);
		}
		
		$PHPWriter = PHPExcel_IOFactory::createWriter($PHPExcel,'Excel2007');//按照指定格式生成Excel文件，‘Excel2007’表示生成2007版本的xlsx，‘Excel5’表示生成2003版本Excel文件

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');//告诉浏览器输出07Excel文件
		//header('Content-Type:application/vnd.ms-excel');//告诉浏览器将要输出Excel03版本文件
        header('Content-Disposition: attachment;filename="bookinfo.xlsx"');//告诉浏览器输出浏览器名称
        header('Cache-Control: max-age=0');//禁止缓存
        $PHPWriter->save("php://output");
	}


	public function delete(Request $Request,BookSection $section,BookModel $book)
	{
		$id = $Request->route('id');

		//删除章节
		$sectionAll = $section->all(['book_id'=>$id]);

		$ids = implode(",",array_column($sectionAll, 'id'));
		$section->destroy($ids);

		//删除书籍
		$book->destroy($id);

		$this->redirect( $Request->server('HTTP_REFERER',url('AdminBook')) );
	}

	//章节标题内容分割
	protected function chapterSplit($content)
	{
		$ct = [];
		$chapterArr = array_filter(explode("\n",str_replace("\r", "", $content)));
				
		foreach($chapterArr as $k=>$v )
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

		return [$title,$content];

	}

	//按字数格式化章节
	protected function formattingSection($content,$size)
	{
		$chapterArr = array_filter(explode("\n",$content));
		$chapterArrSet = [];

		$data = []; 		//最总章节
		$chapter = [];		//章节字符串储存
		$statSize = 0;		//章节大小状态
		foreach($chapterArr as $key=>$val)
		{
			if($statSize > $size)
			{
				$chapterArrSet[] = implode("\n",$chapter);

				unset($chapter);
				$chapter[] = $val;
				$statSize = charNumber($val);
			}else{
				$chapter[] = $val;
				$statSize += charNumber($val);

				if($key == key(array_slice($chapterArr,-1,1,true)) )
				{
					$chapterArrSet[] = implode("\n",$chapter);					
				}
			}
		}

		foreach($chapterArrSet as $key=>$val)
		{
			$data[] = sprintf(config('section.default_title'),$key+1) . "\n" . $val ; //章节标题的预描述
		}

		return $data;
	}


}