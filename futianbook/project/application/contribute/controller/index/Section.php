<?php
namespace app\contribute\controller\index;

use \app\contribute\controller\Common;

use \app\contribute\model\Book;
use \app\contribute\model\BookSection;
use \app\contribute\model\User;
use \app\contribute\model\Advert;

use \think\Request;
use \think\Db;

class Section extends Common
{


	public function show(Request $Request){
		$id = $Request->route('id');
		$section = BookSection::get($id);

		if(!isset($section) || $section->check == 0 || $section->book->check == 0){	//未发布404
			return $this->returnCode();
		}
		
		if($section->attr == 2 && !( session('admin') || session('user.id') == $section->user_id ) ){	
			$this->redirect(url('HomeSectionPaySection'));
			//return $this->returnCode();
		}	//对来访者进行访问 收费章节限制
		
		$next = BookSection::get(function ($query) use ($id,$section){
			$query->where([ 'id'=>['>',$id],'book_id'=>$section->book_id,'check'=>1 ])->order('id','ASC');
		});	//下一页
		$prev = BookSection::get(function ($query) use ($id,$section){
			$query->where([ 'id'=>['<',$id],'book_id'=>$section->book_id,'check'=>1 ])->order('id','DESC');
		});	//上一页

		$this->assign('next',$next);
		$this->assign('prev',$prev);
		$this->assign('section',$section);
		return $this->fetch();
	}

	public function pay_section()
	{
		$pay_qrcode = Advert::getMark('section_pay_qrcode');
		$pay_qrcode->description = $pay_qrcode->description ? $pay_qrcode->description : '付费章节请扫码观看';

		return '<!DOCTYPE html>
					<html lang="en">

					<head>
					    <meta charset="UTF-8">
					    <meta name="viewport" content="width=device-width, initial-scale=1.0">
					    <meta http-equiv="X-UA-Compatible" content="ie=edge">
					    <title>Document</title>
					    <style>
					        html,
					        body {
					            width: 100%;
					            height: 100%;
					            margin: 0;
					            padding: 0;
					        }

					        .main {
					            position: absolute;
					            text-align: center;
					            left: 50%;
					            top: 50%;
					            transform: translate(-50%, -50%);
					        }

					        img {
					            border:1px solid #ccc;
					        }

					        span {

					            display: block;
					            color: #FF0004;
					            margin-bottom: 20px;
					            font-size: 20px;
					        }
					    </style>
					</head>

					<body>
					    <div class="main">
					        <span>' . $pay_qrcode->description . '</span>
					        <img src="' . imagePath(['table'=>'advert','category'=>'image'],$pay_qrcode->image) . '" alt="" width="160px" height="160px">
					    </div>
					</body>

					</html>';

	}




}