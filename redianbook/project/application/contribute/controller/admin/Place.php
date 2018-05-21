<?php
namespace app\contribute\controller\admin;

use \app\contribute\controller\Common;
use \app\contribute\controller\AdminCheck;

use \app\contribute\model\Place as placeModel;
use \app\contribute\model\PlaceRelation;

use \think\Request;
use \api\Key;

class Place extends Common
{

	use AdminCheck;

	public function _initialize(){
		$this->check();
	}

	public function index(placeModel $place){
		$place = $place->order('id DESC')->paginate(10);

		$this->assign('place',$place);
		return $this->fetch();
	}

	public function add(Request $Request,placeModel $place,PlaceRelation $placeRelation){
	
		if($Request->ispost()){
			$placeData = $place->upOrCreate($Request);

			$place->update([
				'id' => $placeData['id'] , 
				'apikey' => Key::apiKey($placeData['id'])->get('md5') 
			]);	//对渠道进行apikey的添加
			
			//更新渠道商的书籍关联
			$placeRelation->distributionOfBooks(
				array_filter(explode(',',$Request->post('book_ids'))),
				$placeData['id']
			);
			
			$this->redirect(url('AdminPlace'));	//添加或是修改完成跳转
		}

		return $this->fetch();		
	}

	public function edit(Request $Request,placeModel $place){
		$id = $Request->route('id');
		$place = $place->get($id);

		$this->assign('data',$place);
		return $this->fetch('add');
	}
	

}