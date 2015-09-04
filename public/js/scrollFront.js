// JavaScript Document
var i = 0;
var cleart;
function initScroll() {
	scrollOne();
}

function initPosition(){
	$(".page h2,.page p, .page img, .page div").css({
		"left":"100%",
		"opacity":"0"
	});
	clearTimeout(cleart);
}
function scrollOne(){
	initPosition();
	$("#page1").show().siblings(".page").hide();
	$(".page-num a").eq(0).addClass("active").siblings().removeClass("active");
	var pageh2 = $("#page1 h2").attr("data-left");
	var pagep = $("#page1 p").attr("data-left");
	
	$("#page1 h2").stop(true,true).animate({
		"left":pageh2,
		"opacity": 1	
	},300,"swing");//
	$("#page1 p").stop(true,true).animate({
		"left": pagep,
		"opacity": 1	
	},500,"swing");
	
	$("#page1 img").each(function(index, element) {
        var tleft = $(this).attr("data-left");
		$(this).stop(true,true).animate({
			"left": tleft,
			"opacity": 1	
		},700-50*index,"swing");
    });
	
	cleart = setTimeout(function(){
		$("#page1 h2").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0	
		},300,"swing");//
		$("#page1 p").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0		
		},500,"swing");
		
		$("#page1 img").each(function(index, element) {
			var tleft = $(this).attr("data-left");
			$(this).stop(true,true).animate({
				"left":"-120%",
				"opacity": 0		
			},700+50*index,"swing");
		});
		setTimeout(function(){scrollTwo();},500)
	},5000)
}
function scrollTwo(){
	initPosition();
	$("#page2").show().siblings(".page").hide();
	$(".page-num a").eq(1).addClass("active").siblings().removeClass("active");
	var pageh2 = $("#page2 h2").attr("data-left");
	var pagep = $("#page2 p").attr("data-left");
	var img3l = $("#page2 .img3").attr("data-left");
	var img3t = $("#page2 .img3").attr("data-top");
	var img4l = $("#page2 .img4").attr("data-left");
	var img4t = $("#page2 .img4").attr("data-top");
	
	$("#page2 h2").stop(true,true).animate({
		"left":pageh2,
		"opacity": 1	
	},300,"swing");//
	$("#page2 p").stop(true,true).animate({
		"left": pagep,
		"opacity": 1	
	},500,"swing");
	
	$("#page2 img").each(function(index, element) {
        var tleft = $(this).attr("data-left");
		$(this).stop(true,true).animate({
			"left": tleft,
			"opacity": 1	
		},700-50*index,"swing");
    });
	
	cleart = setTimeout(function(){
		$("#page2 .img2").fadeIn("300");
		setTimeout(function(){
			$("#page2 .img3,#page2 .img4").fadeIn("300");
		},300)
		
		setTimeout(function(){
			$("#page2 .img3").animate({
				"top": "235px",
				"left": "319px"	
			},500)
			$("#page2 .img4").animate({
				"top": "251px",
				"left": "329px"	
			},500,function(){
				$("#page2 .img4").fadeOut()
			})
		},700)
	},800)
	cleart = setTimeout(function(){
		$("#page2 h2").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0	
		},300,"swing");//
		$("#page2 p").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0	
		},500,"swing");
		
		$("#page2 img").each(function(index, element) {
			var tleft = $(this).attr("data-left");
			$(this).stop(true,true).animate({
				"left":"-120%",
				"opacity": 0	
			},700+50*index,"swing");
		});
		
		setTimeout(function(){scrollThree();},500)
	},5000)
}
function scrollThree(){
	initPosition();
	$("#page3").show().siblings(".page").hide();
	$(".page-num a").eq(2).addClass("active").siblings().removeClass("active")
	var pageh2 = $("#page3 h2").attr("data-left");
	var pagep = $("#page3 p").attr("data-left");
	$("#page3 h2").stop(true,true).animate({
		"left":pageh2,
		"opacity": 1	
	},300,"swing");//
	$("#page3 p").stop(true,true).animate({
		"left": pagep,
		"opacity": 1	
	},500,"swing");
	$("#page3 div").each(function(index, element) {
        var tleft = $(this).attr("data-left");
		$(this).stop(true,true).animate({
			"left": tleft,
			"opacity": 1	
		},700-50*index,"swing");
    });
	

	cleart = setTimeout(function(){
		$("#page3 h2").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0	
		},300,"swing");
		$("#page3 p").stop(true,true).animate({
			"left": "-100%",
			"opacity": 0	
		},500,"swing");
		$("#page3 div").each(function(index, element) {
			var tleft = $(this).attr("data-left");
			$(this).stop(true,true).animate({
				"left": "-120%",
				"opacity": 0	
			},700+50*index,"swing");
		});
		
		setTimeout(function(){scrollFour();},700)
	},5000)
}
function scrollFour(){
	initPosition();
	$("#page4").show().siblings(".page").hide();
	$(".page-num a").eq(3).addClass("active").siblings().removeClass("active")
	var pageh2 = $("#page4 h2").attr("data-left");
	var pagep = $("#page4 p").attr("data-left");
	$("#page4 h2").stop(true,true).animate({
		"left":pageh2,
		"opacity": 1	
	},300,"swing");//
	$("#page4 p").stop(true,true).animate({
		"left": pagep,
		"opacity": 1	
	},500,"swing");
	$("#page4 div").each(function(index, element) {
        var tleft = $(this).attr("data-left");
		$(this).stop(true,true).animate({
			"left": tleft,
			"opacity": 1	
		},700-50*index,"swing");
    });
	
	
	
	cleart = setTimeout(function(){
		$("#page4 h2").stop(true,true).animate({
			"left":"-100%",
			"opacity": 0	
		},300,"swing");//
		$("#page4 p").stop(true,true).animate({
			"left": "-100%",
			"opacity": 0	
		},500,"swing");
		$("#page4 div").each(function(index, element) {
			var tleft = $(this).attr("data-left");
			$(this).stop(true,true).animate({
				"left": "-120%",
				"opacity": 0	
			},700+50*index,"swing");
		});
		setTimeout(function(){scrollOne();},700)
	},5000)
}
