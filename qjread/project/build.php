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

return [
    // 生成应用公共文件
    '__file__' => ['common.php', 'config.php', 'database.php'],

    // 定义demo模块的自动生成 （按照实际定义的文件名生成）
    'contribute'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['behavior', 'controller', 'model', 'view','validate'],
        'validate'   => ['Tag'],
        'controller' => [
            //公共控制器
            'Index','Common','Ajax','UserCheck','AdminCheck','Image','ApiCheck',
            //用户前端展示页面
            'index/Index','index/Book','index/Section','index/News',
            //投稿系统控制器
            'user/Login','user/Book','user/Section','user/Info',
            //后台管理控制器
            'admin/Login','admin/Book','admin/Section','admin/Category','admin/Place','admin/User','admin/News','admin/Admin','admin/Setting',
            //API控制器
            'api/Book','api/Section',
        ],
        'model'      => ['User','Admin','Book','BookSection','Category','Place','Tag','TagRelation','TagRelation','PlaceRelation','News','Advert','Mark'],
        'view'       => [
            //错误页面模板
            'error/404',
            //展示页面模板
            'index/index/index','index/index/maker','index/index/author','index/index/we','index/book/list','index/book/show','index/section/show','index/news/list','index/news/show',
            //公共的继承模板
            'public/user','public/index','public/admin',
            //用户投稿模板
            'user/login/login','user/login/register','user/book/list','user/book/add','user/section/list','user/section/add','user/info/edit',
            //后台管理模板
            'admin/login/index','admin/book/index','admin/book/edit','admin/book/lead','admin/section/index','admin/section/edit','admin/place/index','admin/place/add','admin/category/index','admin/category/add','admin/user/index','admin/user/edit','admin/news/index','admin/news/add','admin/admin/index','admin/admin/add','admin/setting/advert','admin/setting/home',
        ],
    ],
    // 其他更多的模块定义
];
