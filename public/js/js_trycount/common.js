// JavaScript Document
// 兼容其他不支持placeholder的浏览器
var FormText = { 
    init: function() { 
		 $('input.form-text').each(function(){
			if($(this).val()==""){
				$(this).prev("label").find("span").show();
				$(this).focus(function(){
					$(this).prev("label").find("span").hide();
				}).blur(function(){
					if($(this).val() ==''){
						$(this).prev("label").find("span").show();	
					}
				})
			}
			else {
				$(this).prev("label").find("span").hide();
			}
		}) 
    }
};

function setupLabel(){ 
	if($('.label-checkbox input[type="checkbox"]').length) { 
		$('.label-checkbox').each(function(){ 
			$(this).removeClass('checked'); 
		}); 
		$('.label-checkbox input[type="checkbox"]:checked').each(function(){ 
			$(this).parents('label').addClass('checked'); 
		}); 
	}; 
	if($('.label_radio input[type="radio"]').length) { 
		$('.label_radio').each(function(){ 
			$(this).removeClass('checked'); 
		}); 
		$('.label_radio input[type="radio"]:checked').each(function(){ 
			$(this).parents('label').addClass('checked'); 
		}); 
	};
}

function scrollToAnchor(id) {
	$('html, body').animate({
		scrollTop:$('#'+id).offset().top
	})
}
function showDialog(id) {
	$("#shadow").show();
	$("#"+id).show();
	$("#"+id).css({
		"margin-top": -$("#"+id).outerHeight()/2 + "px"	
	})
	if($.browser.msie&&$.browser.version==6.0){
		$("#shadow").css({
			"height": $(document).height()	
		})
		
		$("html, body").animate({
			scrollTop: 0	
		})
	}
}
function showZyDialog(t) {
	var zyContent = $(t).attr("data-txt");
	var zyLogo = $(t).attr("date-src");
	
	$("#dialog .dialog-content-main p").html(zyContent);
	$("#dialog .dialog-logo").html('<img src="'+ zyLogo +'" />');
	
	showDialog("dialog");
}
function showMapDialog(t) {
	var mapAddress = $(t).attr("date-src");
	
	$("#map .map-content").html('<img src="'+ mapAddress +'" />');
	
	showDialog("map");
}
function closeDialog(id){
	$("#shadow").hide();
	$("#"+id).hide();
}

function getVideo(videoname){
	var videobox=$('<div id="videobox"><div class="v_content">'+
	'<object type="application/x-shockwave-flash" data="'+videoname+'" width="932" height="524">'+
	'<param name="movie" value="'+videoname+'" />'+
	'<param name="wmode" value="opaque" />'+
	'<param name="wmode" value="transparent" />'+
	'<embed src="'+videoname+'" width="932" height="524" wmode="opaque"></embed>'+
	'</object>'
	+'</div><a href="javascript: void(0)" class="close" title="close"></a></div>');
	videobox.appendTo("body");
	showDialog("videobox");
	$("#videobox a.close").click(function(){
		closeDialog("videobox");
	})
}
function initMainHeight(){
	var hh = $(".header").outerHeight(), fh = $(".footer").outerHeight(), wh = $(window).height();
	
	var mainHeight = $(".main").outerHeight();
	if(mainHeight<wh-hh-fh) {
		$(".main").height(wh-hh-fh-40);	
	}	
}
$(function(){
	$("input.form-text").focus(function(){
		if($(this).val()==""){
			$(this).parents(".form-item").addClass("focus");	
		}
	}).blur(function(){
		$(this).parents(".form-item").removeClass("focus");
		if($(this).val()!=""){
			$(".error-msg").html("")	
		}
	})	
	
	initMainHeight();
	$(window).resize(function(){
		initMainHeight();
	})
})