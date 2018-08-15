$(document).ready(function() {
	FancyForm.setup();

});

var FancyForm=function(){
	return{
		inputs:".FancyForm input, .FancyForm textarea",
		setup:function(){
			var a=this;
			this.inputs=$(this.inputs);
			a.inputs.each(function(){
				var c=$(this);
				a.checkVal(c)
			});
			a.inputs.live("keyup blur",function(){
				var c=$(this);
				a.checkVal(c);
			});
		},checkVal:function(a){
			a.val().length>0?a.parent("li").addClass("val"):a.parent("li").removeClass("val")
		}
	}
}();

var searchAjax=function(){};
var G_tocard_maxTips=30;

(function(){
var a1=$(".plus-tag1");
	$("a em",a1).live("click",function(){
		var c1=$(this).parents("a"),b1=c1.attr("title"),d1=c1.attr("value");
		
		var placeId = $("#zjzd").val();
		var placeArr = placeId.split(",");
		var newArr = [];		
		$.each(placeArr, function(index, val) { 
			 if(val != d1){
			 	newArr.push(val);
			 }
		});	

		$("#zjzd").val(newArr);

		/**
		 * 	删除分销商的复选框 start 
		 *	@author yuyaoyao
		 */
		var title = $(this).parent().attr('title');
		$("#tcAdd1 .layui-input-block [title="+title+"]").next("div").removeClass("layui-form-checked");
		/*  删除分销商的复选框 end */
		PlaceHasTips=function(b1){
			var d1=$("a",a1),c1=false;
			d1.each(function(){
				if($(this).attr("title")==b1){
					c1=true;
					return false
				}
			});
			return c1;
		};
		PlaceDelTips=function(b1,c1){
			if(!PlaceHasTips(b1)){
				return false
			}
			$("a",a1).each(function(){
				var d1=$(this);
				if(d1.attr("title")==b1){
					d1.remove();
					return false
				}
			});
			searchAjax(b1,c1,false);
			return true
		}
		PlaceDelTips(b1,d1);
	});
})();

$(function(){(
	function(){
		var a=$(".plus-tag");
		$("a em",a).live("click",function(){
			var c=$(this).parents("a"),b=c.attr("title"),d=c.attr("value");
			delTips(b,d);

			var allTag = $("#ycy").val();
			var tagArr = allTag.split(",");
			var newArr = [];		
			$.each(tagArr, function(index, val) { 
				 if(val != b){
				 	newArr.push(val);
				 }
			});	
			$("#ycy").val(newArr);
		});
		
		hasTips=function(b){
			var d=$("a",a),c=false;
			d.each(function(){
				if($(this).attr("title")==b){
					c=true;
					return false
				}
			});
			return c
		};
		isMaxTips=function(){
			return	
			$("a",a).length>=G_tocard_maxTips
		}
		setTips=function(c,d){
			if(hasTips(c)){
				return false
			}if(isMaxTips()){
				alert("最多添加"+G_tocard_maxTips+"个标签！");
				return false
			}
			var b=d?'value="'+d+'"':"";
			a.append($("<a "+b+' title="'+c+'" href="javascript:void(0);" ><span>'+c+"</span><em></em></a>"));
			searchAjax(c,d,true);
			return true
		};
		delTips=function(b,c){
			if(!hasTips(b)){
				return false
			}
			$("a",a).each(function(){
				var d=$(this);
				if(d.attr("title")==b){
					d.remove();
					return false
				}
			});
			searchAjax(b,c,false);
			return true
		}
		getTips=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("title"))
			});
			return b
		}
		getTipsId=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("value"))
			});
			return b
		};
		getTipsIdAndTag=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("value")+"##"+$(this).attr("title"))
			});
			return b
		}
	}
	
)()});

// 更新选中标签标签
$(function(){
	setSelectTips();
	$('.plus-tag').append($('.plus-tag a'));
});
var searchAjax = function(name, id, isAdd){
	setSelectTips();
};
// 搜索
(function(){
	var $b = $('.plus-tag-add button'),$i = $('.plus-tag-add input'); 
	//添加标签按下回车键的效果
	$i.keyup(function(e){
		if(e.keyCode == 13){
			$b.click();
		}
	});
	
	//添加标签按下确定按钮的效果
	$b.click(function(){
		var name = $i.val().toLowerCase();
		if(name != '') setTips(name,-1);
		$i.val('');
		$i.select();
		//获取id为ycy的值
		var allTag = $("#ycy").val();
		if(allTag == ""){
			$('#ycy').val(name); //获取开始用户输入数值
		}else{
			var is = true;
			var tagArr = allTag.split(",");
			//如果用户输入相同标签名则不添加标签
			$.each(tagArr, function(index, value) {
			    if(value == name){
			    	is = false;
			    }
			});
			//如果用户输入不同标签则继续添加标签
			if(is == true){
				var newTag = $('#ycy').val() + "," + name;
				$('#ycy').val(newTag);
			}	
		}

		layer.closeAll();
	});

})();
//增加分销站点
(function(){
	var demo2=$(".demo2 button");
	
	demo2.click(function(){
		var innerHtml = "";
		var layuiunselect = $(".demo2 .layui-form-checked").prev('input');
		var placeId = [];

		$.each(layuiunselect,function(index,value){

			 innerHtml += '<a value="'+ $(value).val() +'" title="'+$(value).attr('title')+'" href="javascript:void(0);"><span>' + $(value).attr('title') + '</span><em></em></a>';
			 placeId.push($(value).val());

		});

		$("#zjzd").val(placeId.join(","));
		$("#myTags1").html(innerHtml);
		var zjzdTagVal=layuiunselect.text();
		
		
		
		//console.log(fxTag);
		
		//console.log(layuiunselect.text());
	});
})();

// 推荐标签
(function(){
	var str = ['展开推荐标签', '收起推荐标签']
	$('.plus-tag-add a').click(function(){
		var $this = $(this),
				$con = $('#mycard-plus');

		if($this.hasClass('plus')){
			$this.removeClass('plus').text(str[0]);
			$con.hide();
		}else{
			$this.addClass('plus').text(str[1]);
			$con.show();
		}
	});
	$('.default-tag a').live('click', function(){
		var $this = $(this),
				name = $this.attr('title'),
				id = $this.attr('value');
		setTips(name, id);
	});
	// 更新高亮显示
	setSelectTips = function(){
		var arrName = getTips();
		if(arrName.length){
			$('#myTags').show();
		}else{
			$('#myTags').hide();
		}
		$('.default-tag a').removeClass('selected');
		$.each(arrName, function(index,name){
			$('.default-tag a').each(function(){
				var $this = $(this);
				if($this.attr('title') == name){
					$this.addClass('selected');
					return false;
				}
			})
		});
	}

})();
// 更换链接
(function(){
	var $b = $('#change-tips'),
		$d = $('.default-tag div'),
		len = $d.length,
		t = 'nowtips';
	$b.click(function(){
		var i = $d.index($('.default-tag .nowtips'));
		i = (i+1 < len) ? (i+1) : 0;
		$d.hide().removeClass(t);
		$d.eq(i).show().addClass(t);
	});
	$d.eq(0).addClass(t);
})();