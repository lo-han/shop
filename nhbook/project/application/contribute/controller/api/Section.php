<?php
namespace app\contribute\controller\api;

use \app\contribute\controller\Common;
use app\contribute\controller\ApiCheck;

use app\contribute\model\BookSection as BookSection;
use app\contribute\model\Place;
use app\contribute\model\Book;

use \think\Response;
use \think\Request;

class Section extends Common
{
	
	use ApiCheck;

	public function _initialize(){

		$this->check((new Place));

	}

	public function get(Request $Request,BookSection $section){

		$section = $section->get(function ($query) use ($Request){
			$query->field('book_id,title,sort,char,attr,content')->where('id',$Request->get('chapterid'))->where('check',1);
		});
		$book = Book::get(['id'=>$section->book_id,'check'=>1]);

		//确认章节的存在
		if(!$section || !$book){
			return $this->returnXml(['code'=>415,'msg'=>'Not your chapter'],415);
		}else{
			//确认渠道拥有书本权限
			$this->bookAuth($section->book_id);
		}

		$section->content = str_replace('</p>', "\n</p>", $section->content);	//格式完善 每一段添加\n
		$section->content = strip_tags($section->content);	//清除html标签
		$section->content = explode("\n",str_replace(array('&nbsp;',"&#13;"),'',$section->content));
        $section->content = implode(
                "\n",
                array_filter($section->content,function ($v,$k){
                        return false !== (bool)trim($v,"　");
                },ARRAY_FILTER_USE_BOTH)
        );

		$section->setAttr('attr',config('bookSection.attr')[$section->attr]);

		return $this->returnXml(['code'=>200,'msg'=>json_decode(json_encode($section),true)],200);


	}

}