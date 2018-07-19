<?php
namespace app\contribute\controller;

use \app\contribute\controller\Common;

use \app\contribute\model\BookSection;

use \think\Response;
use \think\Request;

class Ajax extends Common
{
	
	//获取20个章节
	public function getSection(Request $Request){

		if($Request->isAjax()){

			$section = BookSection::all(function ($query) use($Request){
				$query->field('id,attr,sort')->where('book_id',$Request->route('book_id'))->where('check',1)->order('sort','asc')->order('id','asc')->limit($Request->post('degree')*20,20);
			});

			if( empty($section) ){
				$ret = [
					'code' => 0,
					'data' => "",
				];
			}else{
	
				foreach($section as $value){
					$data[] = [
						'url' 	=> $value->attr == 1 ? url('HomeSectionShow',['id'=>$value->id]) : url('HomeSectionPaySection'),
						'attr' 	=> $value->attr,
						'title' => sprintf("第%d章",$value->sort),
					];
				}

				$ret = [
					'code' => 1,
					'data' => $data,
				];
			}

			return $this->jsonSuccess($ret);
			
		}else{
			return $this->jsonError('~~~error request~~~',403);
		}
		
	}


}