/**
* jQuery基础扩展
* sea
*/
;(function($){
	$.jsc=$.jsc||{version:0.1};
	var ua=navigator.userAgent;
	function defaultTrue(bool){
		return (bool!==undefined)?bool:true;	
	};
	$.extend($.jsc,{
		uuid:1,
		zIndexBase:1000,
		isIe:$.browser.msie,
		isIe6:$.browser.msie&&($.browser.version == "6.0")&&!$.support.style,
		isIe7:$.browser.msie&&($.browser.version == "7.0"),
		isMoz:$.browser.mozilla && /gecko/i.test(ua),
		isWebkit:$.browser.safari && /Safari\/[5-9]/.test(ua),		
		now:function(){return +new Date;},
		rt:function(){return true;},
		rf:function(){return false;},		
		noDrag:function(bool){
			$('body').attr('onDragStart','return '+!defaultTrue(bool)+';');
		},
		noContentMenu:function(bool){
			$('body').attr('onContextMenu','return '+!defaultTrue(bool)+';');
		},
		contains:function(target,test){
		return ( ( test[0] || test.left ) >= target.left && ( test[0] || test.right ) <= target.right
			&& ( test[1] || test.top ) >= target.top && ( test[1] || test.bottom ) <= target.bottom ); 
		},
		pane:function(conf){
			var opt=$.extend({},conf);
			return $('<'+(opt.nodeName||'div')+'/>').html(opt.content||'').addClass(opt.className||'').attr(opt.attr||{});			
		}		
	});
	$.fn.extend({
		disable:function(){
			this.addClass('disabled');
			if(this[0].nodeName.toLowerCase()=="input"){
				return this.attr('disabled','disabled');
			}
			return this.find(':input').attr('disabled','disabled').end();
		},
		enable:function(){
			this.removeClass('disabled');
			if(this[0].nodeName.toLowerCase()=="input"){
				return this.removeAttr('disabled');
			}
			return this.find(':input').removeAttr('disabled').end();
		},
		isDisabled:function(){
			return this.hasClass('disabled')||!!this.parents('.disabled').length;
		},
		noDrag:function(bool){
			return this.attr('onDragStart','return '+!defaultTrue(bool)+';');
		},
		noContentMenu:function(bool){
			return this.attr('onContextMenu','return '+!defaultTrue(bool)+';');
		},		
		noSelect:function(bool){
			bool=defaultTrue(bool);
			this[ bool ? "bind" : "unbind" ]("selectstart",$.jsc.rf)
				.attr("unselectable", bool ? "on" : "off" )
				.css("MozUserSelect", bool ? "none" : "" );
			return this;
		},
		getPBM:function(WH,bool){
			if(typeof (WH)=='boolean'){
				bool=WH;
				WH='w';
			}
			WH=WH||'width',bool=defaultTrue(bool);
			var res=0;
			if(WH=='w'||WH=='width'){
				res=this.outerWidth(bool)-this.width();
			}else if(WH=='h'||WH=='height'){
				res=this.outerHeight(bool)-this.height();
			}
			return res;			
		},
		getCssVal:function(css){
			var val = parseInt(this.css(css));
			if (isNaN(val)) {
				return 0;
			} else {
				return val;
			}
		},
		ellipsis:function(){
			var self=this;
			var interspace=arguments[0]||10;
			this.removeClass('ellipsis');
			this.each(function(){
				var parent=$(this).parent();
				var width=parent.width()-interspace;
				$(this).siblings().each(function(){
					width-=$(this).outerWidth(true);
				});
				$(this).width(width);
				if($.jsc.isMoz){
					var text=self.text();
					var t = self.clone().css('position', 'absolute').hide()
					.css('width', 'auto').css('overflow', 'visible');
					self.after(t);
					while(text.length > 0 && t.width() > width){
						text = text.substr(0, text.length - 1);
						t.html(text + "…");
					}
					self.html(t.html());					
					t.remove();					
				}
				parent.bind('jscResize.jsc',function(){
					self.ellipsis(conf);
				});
			});
			return this.addClass('ellipsis');
		},
		mousewheel: function(fn) {
			return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
		},
		unmousewheel: function(fn) {
			return this.unbind("mousewheel", fn);
		}
	});
	$.event.special.mousewheel = {
		setup: function() {
			var handler = $.event.special.mousewheel.handler;
			// Fix pageX, pageY, clientX and clientY for mozilla
			if ( $.browser.mozilla )
				$(this).bind('mousemove.mousewheel', function(event) {
					$.data(this, 'mwcursorposdata', {
						pageX: event.pageX,
						pageY: event.pageY,
						clientX: event.clientX,
						clientY: event.clientY
					});
				});
			if ( this.addEventListener )
				this.addEventListener( ($.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel'), handler, false);
			else
				this.onmousewheel = handler;
		},
		teardown: function() {
			var handler = $.event.special.mousewheel.handler;
			
			$(this).unbind('mousemove.mousewheel');
			
			if ( this.removeEventListener )
				this.removeEventListener( ($.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel'), handler, false);
			else
				this.onmousewheel = function(){};
			
			$.removeData(this, 'mwcursorposdata');
		},
		handler: function(event) {
			var args = Array.prototype.slice.call( arguments, 1 );
			event = $.event.fix(event || window.event);
			// Get correct pageX, pageY, clientX and clientY for mozilla
			$.extend( event, $.data(this, 'mwcursorposdata') || {} );
			var delta = 0, returnValue = true;
			if ( event.wheelDelta ) delta = event.wheelDelta/120;
			if ( event.detail     ) delta = -event.detail/3;
			if ( $.browser.opera  ) delta = -event.wheelDelta;
			event.data  = event.data || {};
			event.type  = "mousewheel";
			// Add delta to the front of the arguments
			args.unshift(delta);
			// Add event to the front of the arguments
			args.unshift(event);
			return $.event.handle.apply(this, args);
		}
	};
})(jQuery);

;(function($){
	$.fn.combo = function(conf){
		var opt = this.data('combo');
		if(opt && !conf.redata){
			return this;
		}else{
			opt = $.extend({}, comboConf, opt, conf);
			return this.each(function(){
				var $this = $(this);
				$this.data('combo', opt);
				var arrow = $(opt.arrow,$this); 	
				var cont = $(opt.cont,$this);
				var listCont = opt.listCont != '' ? $(opt.listCont,$this) : false; 	
				var list = listCont ? $(opt.list,listCont) : $(opt.cont,$this); 	
				var trueList = listCont ? listCont : list;
				var listItem = list.find(opt.listItem);
				var itemLen = listItem.length;
				var handle;
				if(opt.showNum != -1 && itemLen > opt.showNum){
					//if($this.parents('#selectFile')) alert(listItem.outerHeight(true));
					list.height(listItem.outerHeight(true) * opt.showNum);
				}else{
					list.height(listItem.outerHeight(true) * itemLen);
				}
				if(cont){
					if(listItem.filter('.selected').length == 0) listItem.eq(0).addClass('selected');
					cont.text(listItem.filter('.selected').text());
					trueList.find('.val').val(listItem.filter('.selected').attr('target'));
				}
				if(opt.arrowOnly){
					handle = arrow;
				}else{
					handle = $this;
				}
				if(opt.redata){
					handle.unbind('click');
					listItem.unbind('click mouseenter mouseleave');
				}
				handle.on('click', function(event){
					var w = $(this).outerWidth();
					if(trueList){
						
						if($('.focusSelectBox').find('.optionBox')){
							$('.focusSelectBox').find('.optionBox').hide();
						}else{
							$('.focusSelectBox').find('.optionList').hide();
						}
						$('.focusSelectBox').removeClass('focusSelectBox').css('z-index','1');
						$this.css('z-index','3').addClass('focusSelectBox');
						/*if($this.parents('#regulateDiv').length){
							if(($this.offset().top+arrow.height()+list.height()) > ($('#regulateDiv').offset().top+$('#regulateDiv').height())){
								opt.up = true;
							}
						}*/
						if(opt.up) trueList.css('top', -trueList.outerHeight(true));
						if(trueList.is(':visible')){
							trueList.hide();
							$this.removeClass(opt.changeCla);
						}else{
							/*$('.selectList').hide();
							$('.sSelected').removeClass('sSelected');
							$('.sSelected2').removeClass('sSelected2');
							$('.sSelectedG02').removeClass('sSelectedG02');*/
							trueList.show().width(w).css("left","-1px");
							$this.addClass(opt.changeCla);
						}
					}
					event.cacelBubble = true;
					event.returnValue = false;
					return false;
				}); 
					
				listItem.hover(
					function(){
						$(this).addClass('hover');
					},
					function(){
						$(this).removeClass('hover');
					}
				).on('click', function(event){
					trueList.hide();
					if(cont){
						cont.text($(this).text()).addClass("selected");
						trueList.find('.val').val($(this).attr('target'));
						if(cont.attr('title') != '') cont.attr('title', $(this).text());
					}
					$(this).addClass('selected').siblings().removeClass('selected');
					$this.removeClass(opt.changeCla);
					opt.changedFn.call();
					event.cacelBubble = true;
					event.returnValue = false;
					return false;
				});
				
				$(document).click(function(){
					if($(this).not($this)){
						//$('.selectList').hide();
						trueList.hide();
						$this.removeClass(opt.changeCla);
					}
				});
			});
		}
	}
	var comboConf = {
			redata:false,
			arrow:'>.icon',
			arrowOnly:false,
			cont:'',
			list:'',
			listCont:'',
			listItem:'',
			listItemHover:'hover',
			showNum:-1,
			up:false,
			changeCla:'comboSelected',
			changedFn:function(){}	
		}
})(jQuery);

function initContentHeght() {
	var conH = $('.content').outerHeight();
	var headerH = $('.header').outerHeight();
	var footerH = $('.footer').outerHeight();
	var wH = $(window).height();
	
	if(conH+headerH+footerH<wH){
		$('.content').height(wH-conH-headerH-footerH);
	}
}

function toggleOption(t) {
	$(t).find(".optionBox").toggle();	
}
	
//模拟checkbox
function checkbox(a,b){
	
	var elem = a&&typeof(a)=='string' ? a : '.checkbox';

	$(elem).click(function(){						   
		var _this = $(this);
		var _input = _this.children('input');
		if(_this.hasClass('checked')){
			_input.removeAttr('checked');
			_this.removeClass('checked');
		}else{
			_input.attr('checked','checked');
			_this.addClass('checked');
		}
		return b&&typeof(b)=='function' ? b.call() : false;
	});
}
var errorText = '<em style="display:block;text-align:center;line-height:100px;font-size:18px;color:#ccc;">您的浏览器不支持静态页面加载AJAX</em>';

//通过Ajax方式刷新内容

function loadPage(url,h) {
	//var url = encodeURIComponent(url);
	/*$('.rightCont').load(url,function(){
		alert(url)	
	});*/

	$.ajax({
	  url: url,
	  cache: false,
	  success: function(html){
		$('.rightCont').html(html);
	  },
	  error: function(){
		 $('.rightCont').html(errorText);
	  }
	});	
	
	//alert(url)
	location.hash = "#"+h;
	/*var hash = location.hash.substring(1);
		 alert(hash);*/
	//alert(location.hash)
	var _this = $("."+h).parent("li");
	_this.addClass('selected').siblings().removeClass('selected');
	initContentHeght();	
}

function loadCont(url){

	//var url = encodeURIComponent(url);
	$('.rightCont').load(url);
	initContentHeght();
}

//弹窗
function showDialog(url){
	hideDialog();
	//var url = encodeURIComponent(url);
	var _dialog = $('#dialog').show();
	var _mask = $('.mask').height($('body').height()).show();
	if(url!='' && typeof(url)=='string'){
		_dialog.find('.dialogBorder').load(url,function(){
			var w = _dialog.width();
			var h = _dialog.height();
			_dialog.css({
				'margin-top': -h/2+'px',
				'margin-left': -w/2+'px'
			});	
		});
	}
}

function hideDialog(){
	
	$('.dialog').hide();
	$('.mask').hide();
	$('.dialog').find('.dialogBorder').empty();
}

function closeDatepicker() {
	$('#dateSelectedBox').hide();
	$(".datepickers").empty();
}
function sureAddGroup(t) {
	var val = $(t).val();
	if(val=="") {
		deleteGroup();
	}
	else {
		$(t).parent("div").html(val);
		showDialog('弹窗_提醒_添加组织结构.html');
	}
}
var dG= 0;
function deleteGroup(){
	dG = 1;
	var $selectedItem = $(".bbit-tree-selected");
	var isDisplay = $("#novalueTable").is(":visible");
	if(isDisplay && $($selectedItem).parent("li").hasClass("new-node")) {
		if($(".new-node").siblings("li").length == 0){
			$selectedItem.parent().parent("ul").remove();
		}
		else {
		
			$(".new-node").siblings("li").eq(0).find(".bbit-tree-node-el").click();
			$(".new-node").remove();
		}
		//$("#novalueTable").hide().prev("table").show();
		//$("#novalueTable").next(".page").show();
	}
	else if(!isDisplay && $("#novalueTable").prev("table").find("tbody").children("tr").length == 0){
		//showDialog('弹窗_提醒_删除部门.html');
		$("#dialog .yes").on("click",function(){
			hideDialog();
			$selectedItem.parent("li").remove();
			$selectedItem.parent("li").siblings().eq(0).find(".bbit-tree-node-el").addClass("bbit-tree-selected");
			
		})	
	}
	else {
		$(".poptip3").fadeIn();
	}
}
function toggleMenu(id,e) {
	var _event = e || window.event;
	$("#"+id).toggle();
	_event.cancelBubble = true;
	_event.returnValue = false;
	return false;
}

function toggleSelect(t,e) {
	var _event = e || window.event;
	$(t).find(".menu").toggle();
	_event.cancelBubble = true;
	_event.returnValue = false;
	return false;
}
//初始管理员索引
	//初始管理员索引
var manageIndex = 10000;
//var login = 0;
$(function(){
	initContentHeght();
	if($(window).width()<1050){
		$(".pageBody").width(1050);
	}
	else {
		$(".pageBody").width("auto");
	}
	if($.browser.msie&&$.browser.version=="6.0") {
		initContentWidth();	
	}
	$(window).resize(function(){
		if($(this).width()<1050){
			$(".pageBody").width(1050);
		}
		else {
			$(".pageBody").width("auto");
		}
		
		if($(".contRight").prev().is(".conTabs")){
			$(".conTabs").width(202);
			if(!($.browser.msie&&$.browser.version=="6.0")) {
				$(".contRight").css("margin-left","208px");
			}
			$(".contMiddle .resizeBar").css("left","202px");
		}
		initContentHeght();
	})
	//setupLabel();
	$('.radio').live("click",function(){ 
		//setupLabel(); 
	}); 
	if ($.browser.msie && $.browser.version == "6.0") {
        var cw = $(".contMiddle").outerWidth();
        var ct = $(".conTabs").outerWidth();
        $(".contRight").css({
            "width": cw - ct - 10,
            "float": "right",
            "margin": 0
        })

    }
	//拖拽改变模块宽度
	$('.resizeBar').mousedown(function(e){
		var _this = $(this);
		var bL = parseInt(_this.css('left'));
		var _conTabs = $('.conTabs');
		var conTabsW = _conTabs.width();
		var _contRight = $('.contRight');
		var contRightM = parseInt(_contRight.css('margin-left'));
		var eX = e.pageX;
		var dX = 0;
		var minW = 200;
		var maxW = 400;
		var rw = $(".rightCont").width();
		
		$('body').noSelect(true);
		$(document).mousemove(function(e){
			console.log(parseInt(_this.css('left')));
			//if(parseInt(_this.css('left')) > 200 && parseInt(_this.css('left')) < 400){
				dX = e.pageX - eX;
				if((conTabsW + dX)>200&&(conTabsW + dX)<rw-534){
				_this.css('left', bL + dX + 'px');
				_conTabs.css('width', conTabsW + dX + 'px');
				if(!($.browser.msie&&$.browser.version=="6.0")){
					_contRight.css('margin-left', contRightM + dX + 'px');
				}
				
				
				}
			//}
		}).mouseup(function(e){
			$(document).unbind('mousemove');
			$('body').noSelect(false);
		});
	});
    
	//输入框提示文字显示隐藏
	$(document).on('keyup', '.input', function(){
											 
		var _this = $(this);
		var _label = _this.siblings('.label');
		if(_label.length){
			if(_this.val() != ''){
				_label.hide();
				_this.parent(".inputBox").removeClass("error");
			}else{
				_label.show();
			}
		}
	});
	$(document).on('click', '.label', function(){
		var _input = $(this).siblings('.input');
		if(_input.length){
			_input.focus();
		} 
	});
	
	$(document).on('focus', '.inputBox .input', function(){
		$(this).parents('.inputBox').addClass('focus');
	});
	$(document).on('blur', '.inputBox .input', function(){
		$(this).parents('.inputBox').removeClass('focus');
	});
	
	$(document).on('click', '.datepicker .icon', function(){
		$(this).siblings('.input').focus();
	});
	
	//失去焦点菜单自动收起
	$(document).click(function(){
		$('.menu').hide();
	});
	
	//头部右侧下拉菜单
	$('.headerLink li.hlItem:not(:first)').click(function(){
		var _this = $(this);
		var _hlItem = _this.siblings('.hlItem');
		_this.addClass("bg");
		_hlItem.removeClass("bg");
	});
	$('.headerLink .user').click(function(){
		var _this = $(this);
		//_this.parent(".hlItem").addClass("bg").siblings('.hlItem').removeClass("bg");
		var _menu = _this.siblings('.menu');
		if(_menu.length && _menu.is(':hidden')){
			_menu.show();
			return false;
		}
	});
	$(".btnChangeUser").live("click",function(){
		showDialog('弹窗_员工调岗2.html')
	})
	
	$(".optionList dd").live("click",function(){
		var txt = $(this).text();
		$(this).parents(".selectBox").find(".text").text(txt)	
	})
	function move_dialog(drag_)
	{
		if(drag_)
		{
			  style.position="absolute";var temp1=offsetLeft;var temp2=offsetTop;
                //clientX 事件属性返回当事件被触发时鼠标指针向对于浏览器页面（或当前窗口）的水平坐标。
                x=oevent(e).clientX;y=oevent(e).clientY;
                document.onmousemove=function(e){
                    //取消拖动
					//alert(drag_)
					
                  /*  if(!drag_)
					{
						//alert(11111)
					  return false;
					  
					}*/
				with(this)
					{
                        //obj距离左边距离 + 外边距 + 鼠标移动距离
                        style.left=temp1-Number(style.marginLeft.substring(0,style.marginLeft.length-2))+oevent(e).clientX-x+"px";
                        style.top=temp2-Number(style.marginTop.substring(0,style.marginTop.length-2)) +oevent(e).clientY-y+"px";
                    }
                   
              }
			 
		}
		else
		{
			return false;
		}
	}
	//当页面在编辑员工信息页签时，离开该页面进行提示
	
	
});





