$(function () {
	
	var result={};
	var $ent=$(".ent");
	var step=uri_search("step");
	if(!step){
		$(".to-index").show();
		$(".start").on("click",function () {
			$(this).parent().hide();
			$(".step-group").show();
			alTeam();
		});
		
	}else if(step==16){
		$(".step2").show();
		toStepTo();
	}else if(step==8){
		$(".step3").show();
		toStepTo();
	}else if(step==4){
		$(".step4").show();
		toStepTo();
	}else if(step==2){
		$(".step5").show();
		toStepTo2();
	}else if(step=='line'){
		$(".guess-line").show();
		showLine();
	}
	function alTeam(){
		var str='';
		datas.forEach(function (item,i,arr) {
			str+='<div class="line"></div><div class="group">';
					str+='<h2>'+item['group']+'</h2>';
					str+='<div class="b-clear" group="'+i+'">'
					item['list'].forEach(function(n,j){
						str+='<div class="fl item"><div class="logo-area"><div class="rad-border"><img src="'+n['logo']+'"/></div><p>'+n['name']+'</p></div>'+
						'<div class="select"><span class="sel-txt"></span><div class="out-circle"><div class="inner-circle"></div></div></div></div>';
					});	
				str+='</div></div>';
		});
		str+='<a href="javascript:;" class="to-submit">下一步</a>';
		$ent.html(str);
		
		$ent.on("click",".item",function () {
			var $item=$(this);
			var p_i=$item.parent().attr("group");
			var num=$item.index();
			result[p_i]=checkArr(result[p_i],num+1);
			var arr=result[p_i].split(",");
			$item.parent().find(".item").removeClass("selected").find(".sel-txt").text('');
			if(arr[0]){
				arr.forEach(function(item,i){
					$item.parent().find(".item").eq(item-1).find(".sel-txt").text(i+1);
					$item.parent().find(".item").eq(item-1).addClass("selected");
				})
				
			}
			
//			console.log(result);
		}); 
		$ent.on("click",".to-submit",function () {
			for(i=0;i<8;i++){
				if(!result[i]){
					alert("请选择所有16支晋级球队哦");
					return false;
				}
				if(result[i].indexOf(",")==-1){
					alert("请选择所有16支晋级球队哦");
					return false;
				}
			}
			
			//转化为16强阵容
			var step16=[],i=0;
			while(true){
				if(!result[i]){
					break;
				}
				if(i%2==0){
					var arr_l=result[i].split(",");
					var arr_r=result[i+1].split(",");
					var item_l=datas[i]['list'][arr_l[0]-1];
					var item_r=datas[i+1]['list'][arr_r[1]-1];
				}else{
					var arr_l=result[i].split(",");
					var arr_r=result[i-1].split(",");
					var item_l=datas[i]['list'][arr_l[0]-1];
					var item_r=datas[i-1]['list'][arr_r[1]-1];
				}
				step16.push({
					item_l:item_l,
					item_r:item_r
				})
				
				i++;
			}
			var steps={
				step16:step16
			}

			localStorage.steps=JSON.stringify(steps);
			location.href=location.pathname+"?step=16";
		});
	}
	function checkArr(arrStr,num){
		var arrStr=arrStr||'';
		var arr=[];
		if(arrStr){
			arr=arrStr.split(",");
		}
		for(var i=0,flag=true;i<arr.length;i++){
			if(arr[i]==num){
				arr.splice(i,1);
				flag=false;
			}
		}
		if(flag){
			if(arr.length<2)
			arr.push(num);
		}
		return arr.join();
	}
	
	function toStepTo(){
		var steps=localStorage.steps;
		if(!steps){
			location.href=location.pathname;
			return ;
		}
		steps=JSON.parse(steps);
		var _result=steps['step'+step];
		
		var str='<div class="line"></div>';
		if(step==4){
			for(var i=0;i<_result.length;i++){
				var item=_result[i];
				str+='<div class="group group-s4 b-clear" group="'+i+'">';
					str+='<div class="fl item item-l" val="'+item['item_l']['num']+'">'+
						'<div class="select"><div class="circle"></div></div>'+
						'<div class="area-s4"><div class="rad-area-s4"><img src="'+item['item_l']['logo']+'"/></div><p class="cn-s4">'+item['item_l']['name']+'</p></div>'+
					'</div><div class="fl vs-mk" >VS</div>';
					str+='<div class="fr item item-r" val="'+item['item_r']['num']+'">'+
						'<div class="area-s4"><div class="rad-area-s4"><img src="'+item['item_r']['logo']+'"/></div><p class="cn-s4">'+item['item_r']['name']+'</p></div>'+
						'<div class="select"><div class="circle"></div></div>'+
					'</div>';
					str+='</div>';
			}
		}else{
			for(var i=0;i<_result.length;i++){
				var item=_result[i];
				str+='<div class="group b-clear" group="'+i+'">';
					str+='<div class="fl item item-l" val="'+item['item_l']['num']+'">'+
						'<div class="select"><div class="circle"></div></div>'+
						'<div class="area"><div class="fl rad-area"><img src="'+item['item_l']['logo']+'"/></div><p class="cn">'+item['item_l']['name']+'</p></div>'+
					'</div><div class="fl vs-mk" >VS</div>';
					str+='<div class="fr item item-r" val="'+item['item_r']['num']+'">'+
						'<div class="area"><div class="fr rad-area"><img src="'+item['item_r']['logo']+'"/></div><p class="cn">'+item['item_r']['name']+'</p></div>'+
						'<div class="select"><div class="circle"></div></div>'+
					'</div>';
					str+='</div>';
			}
		}
		
		str+='<a href="javascript:;" class="to-submit">下一步</a>';
		$ent.html(str);
		$ent.on("click",".item",function () {
			var $item=$(this);
			var p_i=$item.parent().attr("group");
			var num=$item.index();
			result[p_i]=$item.attr("val");
			$item.parent().find(".item").removeClass("selected");
			$item.addClass("selected");
//			console.log(result);
		});
		$ent.on("click",".to-submit",function () {
			for(var i=0;i<step/2;i++){
				if(!result[i]){
					alert("请选择所有"+step/2+"支晋级球队哦");
					return ;
				}
			}
			var steps=JSON.parse(localStorage.steps);
			if(step==4){
				var step_arr=[];
				var x_l=result[0].charAt(0),y_l=result[0].charAt(1);
				var item_l=datas[x_l]['list'][y_l-1];
				var x_r=result[1].charAt(0),y_r=result[1].charAt(1);
					var item_r=datas[x_r]['list'][y_r-1];
				step_arr.push({
					item_l:item_l,
					item_r:item_r
				});
				steps['step2']=step_arr;
			}else{
				var _step=jinji(result);
				steps['step'+step/2]=_step;
			}
			localStorage.steps=JSON.stringify(steps);
			location.href=location.pathname+"?step="+step/2;
		});
	}
	function toStepTo2(){
		var steps=localStorage.steps;
		if(!steps){
			location.href=location.pathname;
			return ;
		}
		steps=JSON.parse(steps);
		var _result=steps['step2'];
		var str='<div class="line"></div>';
		for(var i=0;i<_result.length;i++){
			var item=_result[i];
			str+='<div class="group group-s4 b-clear" group="'+i+'">';
					str+='<div class="fl item item-l" val="'+item['item_l']['num']+'">'+
						'<div class="select"><div class="circle"></div></div>'+
						'<div class="area-s4"><div class="rad-area-s4"><img src="'+item['item_l']['logo']+'"/></div><p class="cn-s4">'+item['item_l']['name']+'</p></div>'+
					'</div><div class="fl vs-mk" >VS</div>';
					str+='<div class="fr item item-r" val="'+item['item_r']['num']+'">'+
						'<div class="area-s4"><div class="rad-area-s4"><img src="'+item['item_r']['logo']+'"/></div><p class="cn-s4">'+item['item_r']['name']+'</p></div>'+
						'<div class="select"><div class="circle"></div></div>'+
					'</div>';
					str+='</div>';
		}
		str+='<a href="javascript:;" class="to-submit">下一步</a>';
		$ent.html(str);
		$ent.on("click",".item",function () {
			var $item=$(this);
			var p_i=$item.parent().attr("group");
			var num=$item.index();
			result[p_i]=$item.attr("val");
			$item.parent().find(".item").removeClass("selected");
			$item.addClass("selected");
//			console.log(result);
		});
		$ent.on("click",".to-submit",function () {
			if(!result[0]){
				alert("请选择冠军球队");
				return ;
			}
			var steps=JSON.parse(localStorage.steps);
			var x=result[0].charAt(0),y=result[0].charAt(1);
			var champion=datas[x]['list'][y-1];
			steps['first']=champion;
			localStorage.steps=JSON.stringify(steps);
			$(".content").find(".champion").show()
			.find(".c-logo img").attr("src",champion['logo']).parent().next(".txt-info")
			.find(".c-cn").text(champion['name']+"队");
			
		});
		$(".content").on("click",".r-once",function () {
			localStorage.removeItem("steps");
			location.href=location.pathname;
		});
		$(".content").on("click",".share",function () {
			location.href=location.pathname+"?step=line";
		});
	}
	function jinji(result){
		var step_arr=[],i=0;
		while(true){
			if(!result[i+3]){
				break;
			}
			var x_l=result[i].charAt(0),y_l=result[i].charAt(1);
				var item_l=datas[x_l]['list'][y_l-1];
			var x_r=result[i+2].charAt(0),y_r=result[i+2].charAt(1);
				var item_r=datas[x_r]['list'][y_r-1];
			step_arr.push({
				item_l:item_l,
				item_r:item_r
			});
			var x_l=result[i+1].charAt(0),y_l=result[i+1].charAt(1);
				var item_l2=datas[x_l]['list'][y_l-1];
			var x_r=result[i+3].charAt(0),y_r=result[i+3].charAt(1);
				var item_r2=datas[x_r]['list'][y_r-1];
			step_arr.push({
				item_l:item_l2,
				item_r:item_r2
			});
			
			
			i+=4;
		}
		return step_arr;
	}
	function showLine(){
		var steps=localStorage.steps;
		if(!steps){
			location.href=location.pathname;
			return ;
		}
		steps=JSON.parse(steps);
//		console.log(steps);
		$(".content").addClass("line-bg").find(".ent").hide();
		var g_cn=[{"l":"A1","r":"B2"},{"l":"A2","r":"B1"},{'l':"C1",'r':"D2"},{'l':"C2",'r':"D1"},
		{'l':"E1",'r':"F2"},{'l':"F1",'r':"E2"},{'l':"G1",'r':"H2"},{'l':"H1",'r':"G2"},];
		var step16=steps['step16']
			step8=steps['step8'],
			step4=steps['step4'],
			step2=steps['step2'],
			first=steps['first'];
		step16.forEach(function (item,i,arr) {
			
			$(".f16to8_"+i+"_l").find(".pos-box").html('<img src="'+item['item_l']['logo']+'" />').prev(".g-cn").text(g_cn[i]['l']);
			$(".f16to8_"+i+"_r").find(".pos-box").html('<img src="'+item['item_r']['logo']+'" />').prev(".g-cn").text(g_cn[i]['r']);
			if(first['name']==item['item_l']['name']){
				$(".f16to8_"+i+"_l").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line16to8_"+i+"_l").css({"border-color":"#F5C202"});
			}else if(first['name']==item['item_r']['name']){
				$(".f16to8_"+i+"_r").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line16to8_"+i+"_r").css({"border-color":"#F5C202"});
			}
		});
		step8.forEach(function (item,i,arr) {
			$(".f8to4_"+i+"_l").find(".pos-box").html('<img src="'+item['item_l']['logo']+'_big.png" />');
			$(".f8to4_"+i+"_r").find(".pos-box").html('<img src="'+item['item_r']['logo']+'" />');
			if(first['name']==item['item_l']['name']){
				$(".f8to4_"+i+"_l").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line8to4_"+i+"_l").css({"border-color":"#F5C202"});
			}else if(first['name']==item['item_r']['name']){
				$(".f8to4_"+i+"_r").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line8to4_"+i+"_r").css({"border-color":"#F5C202"});
			}
		});
		step4.forEach(function (item,i,arr) {
			$(".f4to2_"+i+"_l").find(".pos-box").html('<img src="'+item['item_l']['logo']+'" />');
			$(".f4to2_"+i+"_r").find(".pos-box").html('<img src="'+item['item_r']['logo']+'" />');
			if(first['name']==item['item_l']['name']){
				$(".f4to2_"+i+"_l").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line4to2_"+i+"_l").css({"border-color":"#F5C202"});
			}else if(first['name']==item['item_r']['name']){
				$(".f4to2_"+i+"_r").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line4to2_"+i+"_r").css({"border-color":"#F5C202"});
			}
		});
		step2.forEach(function (item,i,arr) {
			$(".f2to1_"+i+"_l").find(".pos-box").html('<img src="'+item['item_l']['logo']+'" />');
			$(".f2to1_"+i+"_r").find(".pos-box").html('<img src="'+item['item_r']['logo']+'" />');
			if(first['name']==item['item_l']['name']){
				$(".f2to1_"+i+"_l").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line2to1_"+i+"_l").css({"border-color":"#F5C202","z-index":"2"});
			}else if(first['name']==item['item_r']['name']){
				$(".f2to1_"+i+"_r").find(".pos-box").css({"border-color":"#F5C202","border-width":"2px"});
				$(".line2to1_"+i+"_r").css({"border-color":"#F5C202","z-index":"2"});
			}
		});
		$(".first").find(".pos-box").html('<img src="'+first['logo']+'" />').css({"border-color":"#F5C202"});;
		
	}
	//获取url参数值
	function uri_search(name, url){
		url  = url?url:self.window.document.location.href;
		var start	= url.indexOf(name + '=');
		if (start == -1) return '';
		var len = start + name.length + 1;
		var end = url.indexOf('&',len);
		if (end == -1) end = url.length;
		return url.substring(len,end);
	}
});
