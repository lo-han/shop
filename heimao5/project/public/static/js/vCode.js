var code ; //在全局定义验证码   
//产生验证码  
//window.onload = VerCode();
window.onload=function(){
    function createCode(){  
         code = "";   
         var codeLength = 4;//验证码的长度  
         var checkCode = document.getElementById("code");   
         var random = new Array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R',  
         'S','T','U','V','W','X','Y','Z');//随机数  
         for(var i = 0; i < codeLength; i++) {//循环操作  
            var index = Math.floor(Math.random()*36);//取得随机数的索引（0~35）  
            code += random[index];//根据索引取得随机数加到code上  
        }  
        checkCode.value = code;//把code值赋给验证码  
    }

    //校验验证码  
    function validate(){  
        var inputCode = document.getElementById("yanzhengma").value.toUpperCase(); //取得输入的验证码并转化为大写        
         
        if(inputCode != code){
        	('#sumbitBtn').attr('disabled',true);
        }else{
        	$('#sumbitBtn').attr('disabled',false);
        }
    }
    /*function VerCode(){
        var imgSrc = $('#yzbth-img').attr('src');
        $('#yzbth-img').attr('src');
        var srcImg=imgSrc +'?'+ RndNum(4);

        var imgSrcs = $('#yzbth-img').attr('src',srcImg);
    }
    */
}
$(function(){
    $('#yzbth-img').click(function(){
        var imgSrc = $('#yzbth-img').attr('src');
        if(imgSrc.indexOf('?') >= 0){
            var imgSrcTwo = imgSrc.split('?');
            var srcImg=imgSrcTwo[0]+'?'+RndNum(4);
            $('#yzbth-img').attr('src',srcImg);
        }else{
            var srcImg=imgSrc+'?'+RndNum(4);
            $('#yzbth-img').attr('src',srcImg);
        }  
    });
});


function RndNum(n){
    var rnd="";
    for(var i=0;i<n;i++){
        rnd+=Math.floor(Math.random()*10);
    }
    return rnd;
}