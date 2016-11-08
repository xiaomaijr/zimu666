$(function() { 
var sWidth = $("#focus").width();
var len = $("#focus ul li").length;
var index = 0; 
var picTimer; 
var btn = "<div class='jslide-btn'>"; 
for(var i=0; i < len; i++) { 
  btn += "<span></span>"; 
} 
btn += "</div><div class='preNext sprite pngfix pre'></div><div class='preNext sprite pngfix next'></div>"; 
$("#focus").append(btn);
//为小按钮添加鼠标滑入事件，以显示相应的内容 
$("#focus .jslide-btn span").css("opacity",0.4).mouseover(function() { 
index = $("#focus .jslide-btn span").index(this); 
showPics(index); 
}).eq(0).trigger("mouseover"); 
//上一页、下一页按钮透明度处理 
$("#focus .preNext").css("opacity",0).hover(function() { 
$(this).stop(true,false).animate({"opacity":"1"},300); 
},function() { 
$(this).stop(true,false).animate({"opacity":"0"},300); 
}); 
//上一页按钮 
$("#focus .pre").click(function() { 
index -= 1; 
if(index == -1) {index = len - 1;} 
showPics(index); 
}); 
//下一页按钮 
$("#focus .next").click(function() { 
index += 1; 
if(index == len) {index = 0;} 
showPics(index); 
}); 

$("#focus ul").css("width",sWidth * (len)); 

$("#focus").hover(function() { 
$("#focus .preNext").stop(true,false).animate({"opacity":"0.5"},300);
clearInterval(picTimer); 
},function() { 
$("#focus .preNext").stop(true,false).animate({"opacity":"0"},300);
picTimer = setInterval(function() { 
showPics(index); 
index++; 
if(index == len) {index = 0;} 
},3000);
}).trigger("mouseleave"); 
function showPics(index) {
var nowLeft = -index*sWidth;
$("#focus ul").stop(true,false).animate({"left":nowLeft},300);
$("#focus .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300);
} 
});