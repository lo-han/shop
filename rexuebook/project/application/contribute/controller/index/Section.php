<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\Book;
use \app\contribute\model\BookSection;
use \app\contribute\model\User;

use \think\Request;
use \think\Db;

class Section extends Common
{


	public function show(Request $Request){
		$id = $Request->route('id');
		$section = BookSection::get($id);

		if(!isset($section) || $section->check === 0){	//未发布404
			return $this->returnCode();
		}

		if($section->attr === 2 && !( session('admin') || session('user.id') == $section->user_id ) ){	
			return $this->returnCode();
		}	//对来访者进行访问 收费章节限制
		
		$next = BookSection::get(function ($query) use ($id,$section){
			$query->where([ 'id'=>['>',$id],'book_id'=>$section->book_id,'attr'=>1 ])->order('id','ASC');
		});	//下一页
		$prev = BookSection::get(function ($query) use ($id,$section){
			$query->where([ 'id'=>['<',$id],'book_id'=>$section->book_id,'attr'=>1 ])->order('id','DESC');
		});	//上一页

		$this->assign('next',$next);
		$this->assign('prev',$prev);
		$this->assign('section',$section);
		return $this->fetch();
	}

}