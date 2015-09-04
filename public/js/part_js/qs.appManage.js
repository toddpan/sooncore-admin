/**
 * 应用列表
 * 
 * @author xue.bai_2 2015-06-30
 */
;(function(){
	"use strict";
	
	var qs = window.qs = window.qs || {};
	if(qs.appManage){
		return;
	}
	var _options = {};
	
	function _init() {
		_bindAddBarEvent();
		
		if($.isArray(_options.app_list)){
			_createAppList(_options.app_list);
		}
		
		_bindEditBarEvent();	
	}
	
	function _bindAddBarEvent(){
		$("#appManage #add_app").click(function(){
			loadCont('app/add_or_edit_app');
		});
	}
	
	function _createAppList(apps){
		var $container = $("#appManage #app_page");
		
		$.each(apps, function(i, app){
			var html = '<tr>' + 
							'<td>' + app.app_title + '</td>' + 
							'<td>' + app.author + '</td>' + 
							'<td>' + app.oriented_obj + '</td>' + 
							'<td>' + app.status + '</td>' + 
							'<td>' + app.update_time + '</td>' + 
							'<td><a app_id="' + app.id + '">[变更]</a></td>' + 
					   '</tr>';
			$(html).appendTo($container);
		});
	}
	
	function _bindEditBarEvent(){
		$("#appManage #app_page a").click(function(){
			var app_id = $(this).attr('app_id');
			
			if(app_id == undefined){
				return;
			}
			
			loadCont('app/add_or_edit_app?app_id=' + app_id);
		});
	}
	
	window.qs.appManage = {
			init: function(options){
				_options = $.extend(_options, options);
				_init();
			}
		};
	
}());