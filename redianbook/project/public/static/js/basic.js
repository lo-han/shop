//等待dom元素加载完毕.
$(function(){
	
	//点击左侧导航栏创建作品
	$(".a_create").click(function(){
		$(this).addClass("current");    //添加点击后的样式
		$(".ico1").attr("src","/novel/static/img/creat_light.png");
		$(".ico2").attr("src","/novel/static/img/book.png");
		$(".ico3").attr("src","/novel/static/img/author.png");
		$(".ico4").attr("src","/novel/static/img/change.png");
        $(".ico5").attr("src","/novel/static/img/money.png");
		$(".a_book,.a_author,.a_change,.a_money").removeClass("current");//兄弟元素移除current样式
	});//点击创建作品
		
	//点击左侧导航栏作品管理
	$(".a_book").click(function(){
		$(this).addClass("current");    //添加点击后的样式
		$(".ico2").attr("src","/novel/static/img/book_light.png");
		$(".ico1").attr("src","/novel/static/img/creat.png");
		$(".ico3").attr("src","/novel/static/img/author.png");
		$(".ico4").attr("src","/novel/static/img/change.png");
        $(".ico5").attr("src","/novel/static/img/money.png");
		$(".a_create,.a_author,.a_change,.a_money").removeClass("current");//兄弟元素移除current样式
	});//点击左侧导航栏作品管理

    //点击左侧导航栏结算中心
    $(".a_money").click(function(){
        $(this).addClass("current");    //添加点击后的样式
        $(".ico5").attr("src","/novel/static/img/money_light.png");
        $(".ico1").attr("src","/novel/static/img/creat.png");
        $(".ico2").attr("src","/novel/static/img/book.png");
        $(".ico3").attr("src","/novel/static/img/author.png");
        $(".ico4").attr("src","/novel/static/img/change.png");
        $(".a_create,.a_book,.a_author,.a_change").removeClass("current");//兄弟元素移除current样式
    });//点击左侧导航栏结算中心
	
	//点击左侧导航栏作家资料
	$(".a_author").click(function(){
		$(this).addClass("current");    //添加点击后的样式
		$(".ico3").attr("src","/novel/static/img/author_light.png");
		$(".ico1").attr("src","/novel/static/img/creat.png");
		$(".ico2").attr("src","/novel/static/img/book.png");
		$(".ico4").attr("src","/novel/static/img/change.png");
        $(".ico5").attr("src","/novel/static/img/money.png");
		$(".a_create,.a_book,.a_change,.a_money").removeClass("current");//兄弟元素移除current样式
	});//点击左侧导航栏作家资料
	//点击左侧导航栏更改密码
	$(".a_change").click(function(){
		$(this).addClass("current");    //添加点击后的样式
		$(".ico4").attr("src","/novel/static/img/change_light.png");
		$(".ico1").attr("src","/novel/static/img/creat.png");
		$(".ico2").attr("src","/novel/static/img/book.png");
		$(".ico3").attr("src","/novel/static/img/author.png");
        $(".ico5").attr("src","/novel/static/img/money.png");
		$(".a_create,.a_book,.a_author,.a_money").removeClass("current");//兄弟元素移除current样式
	});//点击左侧导航栏更改密码

    $(".a_create").hover(function(){
        $(".ico1").attr("src","/novel/static/img/creat_light.png");
    },function(){
        if($(".a_create").hasClass('current')){
            $(".ico1").attr("src","/novel/static/img/creat_light.png");
        }else {
            $(".ico1").attr("src","/novel/static/img/creat.png");
        }
    });

    $(".a_book").hover(function(){
        $(".ico2").attr("src","/novel/static/img/book_light.png");
    },function(){
        if($(".a_book").hasClass('current')){
            $(".ico2").attr("src","/novel/static/img/book_light.png");
        }else {
            $(".ico2").attr("src","/novel/static/img/book.png");
        }
    });

    $(".a_money").hover(function(){
        $(".ico5").attr("src","/novel/static/img/money_light.png");
    },function(){
        if($(".a_money").hasClass('current')){
            $(".ico5").attr("src","/novel/static/img/money_light.png");
        }else {
            $(".ico5").attr("src","/novel/static/img/money.png");
        }
    });

    $(".a_author").hover(function(){
        $(".ico3").attr("src","/novel/static/img/author_light.png");
    },function(){
        if($(".a_author").hasClass('current')){
            $(".ico3").attr("src","/novel/static/img/author_light.png");
        }else {
            $(".ico3").attr("src","/novel/static/img/author.png");
        }
    });

    $(".a_change").hover(function(){
        $(".ico4").attr("src","/novel/static/img/change_light.png");
    },function(){
        if($(".a_change").hasClass('current')){
            $(".ico4").attr("src","/novel/static/img/change_light.png");
        }else {
            $(".ico4").attr("src","/novel/static/img/change.png");
        }
    });

});