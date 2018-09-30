window.onload=function(){
	/*banner轮播效果-start*/
	var mySwiper = new Swiper ('#home-swiper-container', {
		    direction: 'horizontal',
		    loop: true,
		    // 如果需要分页器
		    pagination: {
		        el: '.swiper-pagination',
		        clickable: true,
		        bulletElement:'div',
		        paginationType: 'custom',
		        renderBullet: function (index, className) {
		          return '<div class="' + className + '" style="background-image:url(static/img/s0' + (index+1)+'.jpg)"><div class="mask"></div></div>';
		        },
		    },
		    slidesPerView : 'auto',
	    	//paginationClickable:true,
	    	spaceBetween: 40,
	    	centeredSlides: true,
	    	/* 如果需要前进后退按钮
	    	navigation: {
		      nextEl: '.swiper-button-next',
		      prevEl: '.swiper-button-prev',
		    },*/
		    slideToClickedSlide: true,
		    slideActiveClass:'swiper-slide-active', 
		    autoplay: {
			   delay: 2000,//2秒切换一次
			   disableOnInteraction : false,
		    },
    
    })
	/*banner轮播效果-end*/
	/*作品页章节列表tab选项*/
	var lbnr = $('.lbnrc').children('a');
	for(var i=0;i<lbnr.length;i++){
		lbnr[i].index=i;
		lbnr[i].onclick=function(){
			for(var i=0;i<lbnr.length;i++){
	          lbnr[i].className="nr";
	        }
	        lbnr[this.index].className="nr active";
		}
	}
	
}