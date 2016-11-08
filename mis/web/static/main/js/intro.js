var mySwiper = new Swiper('.swiper-container',{
    pagination: '.pagination',
    paginationClickable: true,
    mode: 'vertical',
    mousewheelControl:true,
    speed:1000
})
$(function(){ 
	$("#swiperRateTab li").on("mouseover",function(){ 
		var $eles=$("#swiperRateTab li"),$jian=$("#swiperJian"),$cons=$("#swiperRateCon li");
		var $this=$(this);
		var index=$this.index();
		$eles.find("a").removeClass("on");
		$this.find("a").addClass("on");
		var left=$this.position().left+($this.width()/2-$jian.width()/2);
		$jian.stop(true,false).animate({left:left+"px"},500,function(){ 
			$cons.hide().eq(index).show();
		});
		
	})
})