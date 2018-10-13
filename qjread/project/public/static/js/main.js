window.onload=function(){
  /*左侧导航点击效果*/
    var left_navs=document.getElementById('left_nav');
    var uls=left_navs.getElementsByTagName('li');
    var iop=left_navs.getElementsByTagName('i');
    for(var k=0;k<uls.length;k++){
      uls[k].index=k;
      uls[k].onclick=function(){
        for(var i=0;i<uls.length;i++){
          uls[i].className="";
          iop[i].style.visibility='hidden';
        }
        uls[this.index].className="active";
        iop[this.index].style.visibility='visible';
      }
    }


}


