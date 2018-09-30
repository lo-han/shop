(function($){
	$.fn.wordLimit = function(num){	
		this.each(function(){	
			if(!num){
				var copyThis = $(this.cloneNode(true)).hide().css({
					'position': 'absolute',
					'width': 'auto',
					'overflow': 'visible'
				});	
				$(this).after(copyThis);
				if(copyThis.width()>$(this).width()){
					$(this).text($(this).text().substring(0,$(this).text().length-4));
					$(this).html($(this).html()+'...');
					copyThis.remove();
					$(this).wordLimit();
				}else{
					copyThis.remove(); //娓呴櫎澶嶅埗
					return;
				}	
			}else{
				var maxwidth=num;
				if($(this).text().length>maxwidth){
					$(this).text($(this).text().substring(0,maxwidth));
					$(this).html($(this).html()+'...');
				}
			}					 
		});
	}		  
})(jQuery);