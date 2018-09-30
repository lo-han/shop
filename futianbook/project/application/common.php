<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\contribute\model\BookSection;


/*获取资源集*/
if (! function_exists('asset')) {

    function asset($url){
        
    	return 'http://' . $_SERVER['HTTP_HOST'] . $url;

    }

}

/**
 *	对用户传输的标签字符串处理
 *	@param str 标签字符串
 */

if (! function_exists('tag_explode')) {

    function tag_explode(string $str){

    	if(empty($str)){ //如果不存在就返回空数组
    		return [];
    	}
        
    	if(strrpos($str,",") === false){
    		return [$str];	//数组形式返回
    	}else{
    		/**
    		 *	对字符串标签操作
    		 *	@behavior explode 执行拆分数组
    		 *	@behavior array_filter 空删除
    		 *	@behavior array_unique 去除重复的标签
    		 */
    		
    		return array_unique(array_filter(explode(",",$str))); 
    	}

    }

}

/**
 *	默认空值返回函数
 *	@param str 输入值
 *	@param default 默认值
 */

if (! function_exists('defaults')) {

    function defaults($str = '',$default = ''){
        
    	if(empty($str)){
    		return $str;
    	}elseif(empty($default)){
    		return $default;
    	}else{
    		return '';
    	}

    }

}

/**
 *	获取书本的最新章节
 *	@param str 输入值
 *	@param default 默认值
 */

if (! function_exists('bookSectionNewest')) {

    function bookSectionNewest($book_id){
    	$BookSection = BookSection::where('book_id', $book_id)->order('sort DESC')->order('id DESC')->find();

		if(empty($BookSection)){
			return new BookSection;
		}else{
			return $BookSection;
		}

    }

}

/**
 *  右侧导航 路由判断
 *  @param name (string) 输入值
 *  @return boole 是否存在
 */

if (! function_exists('routerJudge')) {

    function routerJudge($name){

        $route = implode("/",request()->routeInfo()['rule']);

        if($route == "/"){  //对根目录进行特殊判断
            $route = "index";    //重新构造根目录标示符
        }

        if(strpos($route,$name) === false){
            return false;
        }else{
            return true;
        } 

    }

}

/**
 *  对万单位的数字进行格式化
 *  @param number (string) 数字
 *  @return (string) 格式化的字数统计
 */

if (! function_exists('numberUnit')) {

    function numberUnit(int $number){
    
        return sprintf("%.2f",$number / 10000) . "万";
        
    }

}

/**
 *  统计过滤了 html 标签的文字
 *  @param char (string) 字符串
 *  @return (int) 一个过滤后的字数统计
 */

if (! function_exists('charNumber')) {

    function charNumber(string $char){
    
        return mb_strlen( 
            str_replace(["&nbsp;"," ","\n","\r"], "", strip_tags( $char ) )
        );

    }

}

/**
 *  实现图片资源路径返回
 *  @param catalogue ([table,category]) 数组
 *  @param upPath TP 自动生成的时间目录和文件名
 *  @return 一个图片支援路径
 */

if (! function_exists('imagePath')) {

    function imagePath($catalogue = [],$upPath = ""){

        $routePath = $catalogue['table'] . "/" . $catalogue['category'] . "/" . $upPath;
        $file = ROOT_PATH . 'uploads' . DS . $routePath;

        $path = config('hostImg') . "/" . $routePath;

        $isfile = true;
        if( $upPath == "" || !is_file($file) ){
            $isfile = false;
        }   //判断是否存在图片 图片不存 需要给某些栏目提供默认图片
        
        if($isfile === false && in_array($catalogue['table'], ['user','admin']) ){
            return config('hostWWW') . "/static/img/defaultHd.jpg";
        }

        return $path;

    }

}

/**
 *  作用于session::flush (未开发)
 *  @param $key (string) 字符串
 *  @param $default 默认值
 *  @return 暂存的字符串
 */

if (! function_exists('formatDate')) {

    function formatDate($time,$pattern=0){
        
        $week = ["日","一","二","三","四","五","六"]; //周期格式化成中文

        switch($pattern){
            case 0: return date("Y.m.d",$time) . " 星期" . $week[date("w",$time)] ;break;
        }

    }

}

/**
 *  密码生成
 *  @param $key (string) 字符串
 *  @param $default 默认值
 *  @return 暂存的字符串
 */

if (! function_exists('passGen')) {

    function passGen($password,$default=""){
    
        if(empty($password)){

            if(empty($default)){
                return '';    
            }else{
                return $default;
            }
            
        }

        return md5(md5($password));

    }

}

/**
 *  作用于session::flush (未开发)
 *  @param $key (string) 字符串
 *  @param $default 默认值
 *  @return 暂存的字符串
 */

if (! function_exists('old')) {

    function old($key,$default=""){
    
        return false;

    }

}




