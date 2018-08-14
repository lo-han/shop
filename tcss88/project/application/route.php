<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

//网站首页
Route::get(['Home','/'],'contribute/index.index/index');
//制作人介绍页
Route::get(['HomeMaker','maker'],'contribute/index.index/maker');
//作者介绍页
Route::get(['HomeAuthor','author/[:sort]'],'contribute/index.index/author',[],['sort'=>'newest|works']);
//关于我们
Route::get(['HomeWe','we'],'contribute/index.index/we');
//书本列表
Route::get(['HomeBookList','book/list'],'contribute/index.book/list');
//书本展示
Route::get(['HomeBookShow','book/:id'],'contribute/index.book/show',[],['id'=>'[0-9]+']);
//章节展示
Route::get(['HomeSectionShow','section/:id'],'contribute/index.section/show',[],['id'=>'[0-9]+']);
//需要付费的章节
Route::get(['HomeSectionPaySection','section/pay'],'contribute/index.section/pay_section');
//需要付费的章节
Route::get(['HomeNewsList','news/list'],'contribute/index.news/list');
//需要付费的章节
Route::get(['HomeNewsShow','news/:id'],'contribute/index.news/show',[],['id'=>'[0-9]+']);

//用户投稿组
Route::group('user',function (){

	/**
	 *	栏目下的默认地址 user|book
	 */
	Route::get('','contribute/user.book/list');			// http://域名/user 目录
	Route::get('book','contribute/user.book/list');		// http://域名/book 目录
	

	//用户登陆
	Route::rule(['UserLogin','login'],'contribute/user.login/login');

	//用户登陆
	Route::post(['UserLoginUp','login/up'],'contribute/user.login/loginUp');
	
	//用户注册
	Route::post(['UserRegister','register'],'contribute/user.login/register');	

	//用户登出
	Route::get(['UserLoginOut','loginOut'],'contribute/user.login/loginOut');

	//书本列表
	Route::get(['UserBookList','book/list'],'contribute/user.book/list');	

	//书本添加
	Route::rule(['UserBookAdd','book/add'],'contribute/user.book/add');	

	//书本修改
	Route::get(['UserBookEdit','book/edit/:id'],'contribute/user.book/edit',[],['id'=>'[0-9]+']);

	//章节列表
	Route::get(['UserSctionList','section/list/:book_id'],'contribute/user.section/list',[],['book_id'=>'[0-9]+']);	

	//章节添加
	Route::rule(['UserSectionAdd','section/add/:book_id'],'contribute/user.section/add','post|get',[],['book_id'=>'[0-9]+']);	

	//章节修改
	Route::get(['UserSctionEdit','section/edit/:book_id/:id'],'contribute/user.section/edit',[],['book_id'=>'[0-9]+','id'=>'[0-9]+']);

	//用户信息完善
	Route::rule(['UserInfoEdit','info/edit'],'contribute/user.info/edit');

});

//管理员后台组
Route::group('admin',function (){

	/**
	 *	栏目下的默认地址 index
	 */
	Route::rule('','contribute/admin.login/index');			// http://域名/user 目录

	//首页书籍推介设置
	Route::get(['AdminSettingHome','setting/home'],'contribute/admin.setting/home');

	//广告设置
	Route::rule(['AdminSettingAdvert','setting/advert'],'contribute/admin.setting/advert');

	//推介书本设置修改
	Route::post(['AdminSettingSaveBook','setting/saveBook'],'contribute/admin.setting/saveBook');

	//推介广告设置修改
	Route::post(['AdminSettingSaveAdvert','setting/saveAdvert'],'contribute/admin.setting/saveAdvert');
	
	//登陆页面
	Route::rule(['AdminLogin','login'],'contribute/admin.login/index');	

	//退出登陆
	Route::get(['AdminLoginOut','loginOut'],'contribute/admin.login/loginOut');

	//书本列表
	Route::rule(['AdminBook','book'],'contribute/admin.book/index');

	//书本审核
	Route::rule(['AdminBookEdit','book/edit/:id'],'contribute/admin.book/edit','post|get',[],['id'=>'[0-9]+']);

	//书本章节导入
	Route::rule(['AdminBookLead','book/lead/:id'],'contribute/admin.book/lead','post|get',[],['id'=>'[0-9]+']);

	//书本删除
	Route::rule(['AdminBookDelete','book/delete/:id'],'contribute/admin.book/delete','post|get',[],['id'=>'[0-9]+']);

	//书单导出
	Route::rule(['AdminBookExportExcel','book/export/excel'],'contribute/admin.book/exportExcel','post|get');

	//书单导出
	Route::rule(['AdminBookAdd','book/add'],'contribute/admin.book/add','post|get');

	//章节列表
	Route::rule(['AdminSection','section/[:book_id]'],'contribute/admin.section/index','post|get',[],['book_id'=>'[0-9]+']);

	//章节审核
	Route::rule(['AdminSectionEdit','section/edit/:book_id/:id'],'contribute/admin.section/edit','post|get',[],['book_id'=>'[0-9]+','id'=>'[0-9]+']);

	//章节删除
	Route::rule(['AdminSectionDelete','section/delete/:book_id/:id'],'contribute/admin.section/delete','post|get',[],['book_id'=>'[0-9]+','id'=>'[0-9]+']);

	//全部章节删除
	Route::rule(['AdminSectionClearBook','section/clear/:book_id'],'contribute/admin.section/clearBook','post|get',[],['book_id'=>'[0-9]+']);

	//全部章节导出
	Route::rule(['AdminSectionExportBook','section/export/:book_id'],'contribute/admin.section/exportBook','post|get',[],['book_id'=>'[0-9]+']);

	//分类列表
	Route::get(['AdminCategory','category'],'contribute/admin.category/index');

	//分类添加
	Route::rule(['AdminCategoryAdd','category/add'],'contribute/admin.category/add');

	//分类修改
	Route::get(['AdminCategoryEdit','category/edit/:id'],'contribute/admin.category/edit');

	//分销列表
	Route::get(['AdminPlace','place'],'contribute/admin.place/index');

	//分销添加
	Route::rule(['AdminPlaceAdd','place/add'],'contribute/admin.place/add');

	//分销修改
	Route::get(['AdminPlaceEdit','place/edit/:id'],'contribute/admin.place/edit');

	//分销修改
	Route::rule(['AdminUser','user'],'contribute/admin.user/index');

	//分销修改
	Route::rule(['AdminUserEdit','user/edit/:id'],'contribute/admin.user/edit','post|get',[],['id'=>'[0-9]+']);

	//资讯列表
	Route::rule(['AdminNews','news'],'contribute/admin.news/index');

	//资讯添加
	Route::rule(['AdminNewsAdd','news/add'],'contribute/admin.news/add');

	//资讯修改
	Route::rule(['AdminNewsEdit','news/edit/:id'],'contribute/admin.news/edit','post|get',[],['id'=>'[0-9]+']);

	//制作人列表
	Route::get(['AdminMaker','maker'],'contribute/admin.admin/index');

	//制作人添加
	Route::rule(['AdminMakerAdd','maker/add'],'contribute/admin.admin/add');

	//制作人修改
	Route::get(['AdminMakerEdit','maker/edit/:id'],'contribute/admin.admin/edit',[],['id'=>'[0-9]+']);

	//红书汇api
	Route::get(['AdminGetBookPut','api/put'],'contribute/admin.getBook/bookPut');

	//红书汇api书籍导入实际数据库
	Route::get(['AdminGetBookBookImport','api/bookImport'],'contribute/admin.getBook/bookImport');

});

//管理员后台组
Route::group('ajax',function (){

	Route::post(['AjaxGetSection','get/section/:book_id'],'contribute/ajax/getSection',[],['book_id'=>'[0-9]+']);

});


//管理员后台组
Route::group('api',function (){
	//渠道商的书本信息
	Route::get(['Api','info'],'contribute/api.book/info');
	//获取书本接口
	Route::get(['Api','book'],'contribute/api.book/get');
	//获取章节接口		
	Route::get(['Api','chapter'],'contribute/api.section/get');	

	/** json方式获取接口 **/
	//渠道商的书本信息
	Route::get(['Api','json/info'],'contribute/api.book/infoJson');
	//获取书本接口
	Route::get(['Api','json/book'],'contribute/api.book/getJson');
	//获取章节接口		
	Route::get(['Api','json/chapter'],'contribute/api.section/getJson');
	//获取章节接口		
	Route::get(['Api','json/chapterlist'],'contribute/api.book/listJson');	

});

//文本内容图片上传
Route::post(['ImageUploadCkeditor','image/upload/ckeditor'],'contribute/image/uploadCKEditor');

//图片上传
Route::post(['ImageUpload','image/upload/:table/:category'],'contribute/image/upload',[],['table'=>'user|admin|book|place|news|advert','category'=>'image|cover|wechar_qrcode|image|big_image']);

//获取图片
Route::get(['Image','image/:table/:category/:id'],'contribute/image/image',[],['table'=>'user|admin|book|place|news|advert','category'=>'image|cover|wechar_qrcode|image|big_image','id'=>'[0-9]+']);




/*return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
*/