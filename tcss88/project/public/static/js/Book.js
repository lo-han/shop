$(function (){
  /*授权类型选择 独家 非独家*/
	$("#copyright-pare a").click(function (){
      if($(this).data('is') == 'yes'){
        $(this).addClass('sqau1').parent().find("a[data-is=no]").removeClass('sqau2');
       	$("#copyright").val($(this).data('val'));
      }else{
        $(this).addClass('sqau2').parent().find("a[data-is=yes]").removeClass('sqau1');
        $("#copyright").val($(this).data('val'));
      }
    });
  /*状态选择 连载 完结*/
	$("#status-pare a").click(function (){
      if($(this).data('is') == 'yes'){
        $(this).addClass('sqau3').parent().find("a[data-is=no]").removeClass('sqau4');
        $("#status").val($(this).data('val'));
      }else{
        $(this).addClass('sqau4').parent().find("a[data-is=yes]").removeClass('sqau3');
        $("#status").val($(this).data('val'));
      }
    });
  /*审核状态  通过 不通过*/
  $("#shstatus-pare a").click(function (){
      if($(this).data('is') == 'yes'){
        $(this).addClass('shsqau5').parent().find("a[data-is=no]").removeClass('shsqau6');
        $("#shstatus").val($(this).data('val'));
      }else{
        $(this).addClass('shsqau6').parent().find("a[data-is=yes]").removeClass('shsqau5');
        $("#shstatus").val($(this).data('val'));
      }
    });
  /*发布状态  发布 未发布*/
  $("#fbstatus-pare a").click(function (){
      if($(this).data('is') == 'yes'){
        $(this).addClass('fbsqau7').parent().find("a[data-is=no]").removeClass('fbsqau8');
        $("#fbstatus").val($(this).data('val'));
      }else{
        $(this).addClass('fbsqau8').parent().find("a[data-is=yes]").removeClass('fbsqau7');
        $("#fbstatus").val($(this).data('val'));
      }
    });
  /*章节类型选择 免费 收费*/
  $("#zjlxchose a").click(function (){
      if($(this).data('is') == 'yes'){
        $(this).addClass('sqau5').parent().find("a[data-is=no]").removeClass('sqau6');
        $("#zjlxxz").val($(this).data('val'));
      }else{
        $(this).addClass('sqau6').parent().find("a[data-is=yes]").removeClass('sqau5');
        $("#zjlxxz").val($(this).data('val'));
      }
    });

    //侧边栏导航伸缩效果
    $("#xsyc").click(function (){
        var xsyc=document.getElementById('xsyc');
        var xlcd=document.getElementById('xlcd');
        var left_nav=document.getElementById('left_nav');
        var Rgh=document.getElementById('rights');
        if(xsyc.className.indexOf("xsyc1") > -1){
          xsyc.classList.remove("xsyc1");
          left_nav.style.width="10.75%";  
          xlcd.style.display="block";
          xlcd.style.opacity="1";
          Rgh.style.width='89%';
        }else{
          xsyc.classList.add("xsyc1");
          left_nav.style.width="2.2%";
          left_nav.style.height="100%";
          left_nav.style.background="#666";
          xlcd.style.display="block";
          xlcd.style.opacity="0";
          Rgh.style.width='97.8%';
        }
    });

    //删除提示
    $(".delete-prompt").click(function (){
      if (confirm("你确定删除吗？")) { 
          window.location.href = $(this).data("href");
        } 
    });
    
});