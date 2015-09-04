/* Chinese initialisation for the jQuery UI date picker plugin. */
;(function(factory) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define([ "../jquery.ui.datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}(function(datepicker) {
	datepicker.regional['zh-CN'] = {
		closeText: '关闭',
		prevText: '&#x3C;上月',
		nextText: '下月&#x3E;',
		currentText: '今天',
		monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		monthNamesShort: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
		dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
		dayNamesShort: ['周日','周一','周二','周三','周四','周五','周六'],
		dayNamesMin: ['日','一','二','三','四','五','六'],
		weekHeader: '周',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '年'
	};
	datepicker.setDefaults(datepicker.regional['zh-CN']);

	return datepicker.regional['zh-CN'];

}));


;(function(){
	"use strict";
	
	var qs = window.qs = window.qs || {};
	if(qs.linkPage){
		return;
	}
	
	var _pages = [];
	
	function _setPage(page){
		if(!page || !page.url){
			return false;
		}
		
		page.url = $.trim(page.url);
		
		if(page.url){
			_pages.push(page);			
			return true;
		}
		
		return false;
	}
	
	function _getPage(){
		if(_pages.length > 0){
			return _pages.pop();
		}
	}
	
	qs.linkPage = {
		set: function(page){
			return _setPage(page);
		},
		get: function(){
			return _getPage();
		}
	}
}());
