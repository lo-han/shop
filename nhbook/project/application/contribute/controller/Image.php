<?php
namespace app\contribute\controller;

use \app\contribute\controller\Common;

use \think\Request;
use \think\Response;
use \think\Db;

// +--------------------------------------------------------------------------------
// | Response 对象被改造
// +--------------------------------------------------------------------------------
// | contentType ,charset 默认的响应文本内容被修改由浏览器自行判断，或是自己设置
// +--------------------------------------------------------------------------------

class Image extends Common
{
	public function image(Request $Request){

		
		$imagePath = Db::table($Request->route('table'))->field($Request->route('category'))->where(['id' => $Request->route('id')])->cache(true)->find();	//获取thinkphp 自动生成的时间文件

		//确认是否存在图片！不存在使用默认图片
		$imagePath = $imagePath[$Request->route('category')];
		if( empty( $imagePath ) ){
			$path = ROOT_PATH . 'public/static/img/account1.png';
		}else{
			$path = ROOT_PATH . 'uploads' . DS . $Request->route('table') . DS . $Request->route('category') . DS . $imagePath;	//拼接图片的全路径
		}	

		/**
		 *	响应图片资源
		 */
		$response = Response::create(
			file_get_contents($path)
		);

		//获取图片信息
		$pathInfo = getimagesize($path);
		
		//设置response 头部
		$response->header('Content-type',$pathInfo['mime'])->header('Cache-Control','max-age=86400, pre-check=86400, private');
		
		return $response->send();
	}

	public function upload(Request $Request){

		// 获取表单上传文件 例如上传了001.jpg
	    $files = $Request->file();

	    // 移动到框架应用根目录/uploads/ 目录下
	    foreach($files as $file){

	    	if($file){
		    	$uploadPath = 'uploads' . DS . $Request->route('table') . DS . $Request->route('category');
		        $info = $file->validate(['size'=>1048576,'ext'=>'jpg,jpeg,png'])->move(ROOT_PATH . $uploadPath);
		        if($info){
		            // 成功上传后 获取上传信息
		            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
		            return $this->jsonSuccess(['code' => 1,'msg' => $info->getSaveName()]);
		        }else{
		            // 上传失败获取错误信息
		            return $this->jsonError(['code' => 0,'msg' => $file->getError()],403);
		        }
		    }

	    }

	}


	//CKEditor 编辑器图片上传
	public function uploadCKEditor(Request $Request){

		// 获取表单上传文件 例如上传了001.jpg
	    $files = $Request->file();
	    // 移动到框架应用根目录/uploads/ 目录下
	    foreach($files as $file){

	    	if($file){
		    	$uploadPath = 'uploads' . DS . 'ckeditor';
		        $info = $file->validate(['size'=>4194304,'ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . $uploadPath);
		        if($info){
		            // 成功上传后 获取上传信息
		            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
		            $retPath = config('hostImg') . '/ckeditor/' . str_replace("\\", "/", $info->getSaveName());
		            return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $Request->get('CKEditorFuncNum') . ',"' . $retPath . '","");</script>';
		        }else{
		        	// 上传失败获取错误信息
		        	return '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $Request->get('CKEditorFuncNum') . ', "/","' . $file->getError() . '");</script>';
		        }
		    }

	    }

	}
	
	



}