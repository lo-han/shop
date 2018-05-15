<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Category as categoryModel;

use \think\Request;

class Category extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();

	}

	//分类列表
	public function index(){
		$category = categoryModel::order('sort DESC')->order('id DESC')->paginate(20);		//获取所有类型

		$this->assign("category",$category);
		return $this->fetch();
	}

	//添加分类
	public function add(Request $Request,categoryModel $category){
		
		if($Request->ispost()){
			$category->upOrCreate($Request);

			$this->redirect(url('AdminCategory'));	//添加或是修改完成跳转
		}

		return $this->fetch();		
	}

	//修改分类
	public function edit(Request $Request,categoryModel $category){
		$id = $Request->route('id');
		$catagory = $category->get($id);

		$this->assign('data',$catagory);
		return $this->fetch("add");
	}
	
}