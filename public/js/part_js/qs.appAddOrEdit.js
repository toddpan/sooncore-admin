/**
 * 新建或编辑应用详情
 * 
 * @author xue.bai_2 2015-06-30
 */
;(function(){
	"use strict";
	
	var qs = window.qs = window.qs || {};
	if(qs.appAddOrEdit){
		return;
	}
	var _options = {};
	
	function _init() {
		_bindRadioEvent();
		
		if(typeof _options.app_info === "object" && !(_options.app_info instanceof Array)){
			_createAppInfo(_options.app_info);
		}
		
		_bindLinkEvent();
		
		_bindBarEvent();
	}
	
	function _bindRadioEvent(){
		$('#appAddOrEdit dl.radio-dl label.radio').on('change', function(){
			$(this).parent().find('label.radio_on').removeClass('radio_on');
			if(!$(this).hasClass('radio_on')){
				$(this).addClass('radio_on');
			}
		});
	}
	
	function _createAppInfo(app_info){
		$("#appAddOrEdit #app_title").val(app_info.app_title);
		$("#appAddOrEdit #app_desc").val(app_info.app_desc);
		$("#appAddOrEdit #author").val(app_info.author);
		//$("#appAddOrEdit #relative").val(app_info.relative);
		$("#appAddOrEdit .appLogo img").attr("src", app_info.app_logo);
		
		if(app_info.url != undefined){
			var url = JSON.parse(app_info.url)
			$("#appAddOrEdit #pc_url").val(url.pc_url);
			$("#appAddOrEdit #ios_url").val(url.ios_url);
			$("#appAddOrEdit #android_url").val(url.android_url);
		}
		
//		if(app_info.app_acount == 1){ // 应用账号暂时不做
//			$("#appAddOrEdit #app_acount").find("input").eq(0).parent().addClass("radio_on");
//			$("#appAddOrEdit #app_acount").find("input").eq(1).parent().removeClass("radio_on");
//		}else{
//			$("#appAddOrEdit #app_acount").find("input").eq(0).parent().removeClass("radio_on");
//			$("#appAddOrEdit #app_acount").find("input").eq(1).parent().addClass("radio_on");
//		}
		
		if(app_info.use_agent == 1){
			$("#appAddOrEdit #use_agent").find("input").eq(0).parent().addClass("radio_on");
			$("#appAddOrEdit #use_agent").find("input").eq(1).parent().removeClass("radio_on");
		}else{
			$("#appAddOrEdit #use_agent").find("input").eq(0).parent().removeClass("radio_on");
			$("#appAddOrEdit #use_agent").find("input").eq(1).parent().addClass("radio_on");
		}
		
//		if(app_info.oriented_obj == 1){		// 应用对象暂时不做	
//			$("#appAddOrEdit #oriented_obj").find("input").eq(0).parent().addClass("radio_on");
//			$("#appAddOrEdit #oriented_obj").find("input").eq(1).parent().removeClass("radio_on");
//		}else{
//			$("#appAddOrEdit #oriented_obj").find("input").eq(0).parent().removeClass("radio_on";
//			$("#appAddOrEdit #oriented_obj").find("input").eq(1).parent().addClass("radio_on");
//		}
		
		if(app_info.status == 1){
			$("#appAddOrEdit #status").find("input").eq(0).parent().addClass("radio_on");
			$("#appAddOrEdit #status").find("input").eq(1).parent().removeClass("radio_on");
		}else{
			$("#appAddOrEdit #status").find("input").eq(0).parent().removeClass("radio_on");
			$("#appAddOrEdit #status").find("input").eq(1).parent().addClass("radio_on");
		}
		
		$("#appAddOrEdit .toolBar2 .yes").attr("app_id", app_info.id);
	}
	
	function _bindLinkEvent(){
		$("#appAddOrEdit .appLogo a").click(function(){
			var app_logo = _collectAppLogo();
			if(app_logo){
				app_logo = app_logo.substr(0, 31);
			}
			var logo_url = '';
			
			if(app_logo){
				logo_url = '?app_logo=' + app_logo;
			}
			
			showDialog('app/setLogoDialog' + logo_url);
		});
	}
	
	function _bindBarEvent(){
		$("#appAddOrEdit .toolBar2 .yes").click(function(){
			save();
		});
		
		$("#appAddOrEdit .toolBar2 .btn").click(function(){
			loadCont('app/app_list');
		});
	}
	
	function _collectAppInfo(){
		$("#appAddOrEdit .error1").hide();
		
		var app_title 	= $("#appAddOrEdit #app_title").val();
		var app_desc  	= $("#appAddOrEdit #app_desc").val();
		var author 		= $("#appAddOrEdit #author").val();
		var pc_url 		= $("#appAddOrEdit #pc_url").val();
		var ios_url 	= $("#appAddOrEdit #ios_url").val();
		var android_url = $("#appAddOrEdit #android_url").val();
		var use_agent 	= $("#appAddOrEdit #use_agent label.radio_on").find("input[type='radio']").val();
		//var relative 	= $("#appAddOrEdit #relative").val();
		var status 		= $("#appAddOrEdit #status label.radio_on").find("input[type='radio']").val();
		var app_id 		= $("#appAddOrEdit .toolBar2 .yes").attr("app_id");
		var app_logo 	= _collectAppLogo();
		
		
		if(!app_title){
			$("#appAddOrEdit .error1").text("请输入应用标题").show();
			return false;
		}
		
		if(!app_desc){
			$("#appAddOrEdit .error1").text("请输入应用描述").show();
			return false;
		}
		
		if(!author){
			$("#appAddOrEdit .error1").text("请输入出版者").show();
			return false;
		}
		
		if(app_logo != ''){
			app_logo = $("#appAddOrEdit .appLogo img").attr("src");
			app_logo = app_logo.split('?');
			app_logo = app_logo[0];
		}
		
		var url = {
				pc_url:pc_url,
				ios_url:ios_url,
				android_url:android_url
		};
		
		var app_info = {
				app_title:app_title,
				app_desc:app_desc,
				author:author,
				url:url,
				use_agent:use_agent,
				//relative:relative,
				status:status,
				app_logo:app_logo,
				app_id:app_id
		};
		
		return app_info;
	}
	
	function _collectAppLogo(){
		var app_logo = $("#appAddOrEdit .appLogo img").attr("src");
		if(app_logo != ''){
			app_logo = app_logo.split('?');
			app_logo = app_logo[0];
			app_logo = app_logo.split('/');
			app_logo = app_logo[app_logo.length - 1];
			if(app_logo.length < 20){
				app_logo = '';
			}
		}
		
		return app_logo;
	}
	
	function save(){
		var app_info = _collectAppInfo();
		if(!app_info){
			return false;
		}
		
		var obj = {
				app_info: app_info
		 };
		 
		 $.ajax({
			url: "app/save_app",
			type: "post",
			data: obj,
			success: function(data) {
				var result = JSON.parse(data);
				if(result.code == 0){
					loadCont('app/app_list');
				}
				else{
					alert(result.prompt_text);
				}
			},
			error:function(){
				alert("保存失败！");
			}
		 });
	}
	
	window.qs.appAddOrEdit = {
			init: function(options){
				_options = $.extend(_options, options);
				_init();
			}
		};
	
}());