qs = window.qs || {};
qs.controls = qs.controls || {};

qs.controls.addWatermark = function($target, message){
	if(!$target || !message){
		return;
	}

	if($target.attr("type") !== "text"){
		return;
	}
	
	var position = $target.position(), 
	height = $target.outerHeight(), 
	marginLeft = parseInt($target.css("margin-left")), 
	marginTop = parseInt($target.css("margin-top"));
	var $label = $("<label class='watermark' />").insertBefore($target).text(message).css({
		"top": position.top + marginTop,
		"left": position.left + marginLeft,
		"height": height + "px",
		"line-height": height + "px"
	}).click(function(){
		$(this).addClass("ele-hidden");
		$target.focus();
	});
	
	if($target.val() != ""){
		$label.addClass("ele-hidden");
	}
	
	$target.on("keyup change blur", function(){
		var $label = $target.prev();
		
		if($label.hasClass("watermark")){
			if($target.val() == ""){
				$label.removeClass("ele-hidden");
			}
			else{
				$label.addClass("ele-hidden");				
			}
		}		
	}).click(function(){
		$target.prev().addClass("ele-hidden");	
	});
};

qs.controls.integerFormat = function($target, options){
	if(!$target){
		return;
	}

	if($target.attr("type") !== "text"){
		return;
	}
	
	var _opts = $.extend({}, options)
		
	$target.keydown(function(e){
		if((e.which >= 65 && e.which <= 90)
				|| (e.which >= 106 && e.which <= 111)
				|| (e.which >= 186 && e.which <= 222)
				|| e.which == 32){
			e.preventDefault();
			return;
		}
		
		if(e.which == 48 || e.which == 96){
			if($.trim($target.val()) == ""){
				e.preventDefault();
				return;
			}
		}
		var $label = $target.prev();
		
		if($label.hasClass("watermark")){
			if($target.val() != ""){
				$label.addClass("ele-hidden");
			}
			else{
				$label.removeClass("ele-hidden");
			}
		}		
	}).blur(function(){
		try{
			var value = parseInt($target.val());
			
			if(value === 0){
				$target.val(0);
			}
			else if(!value){
				$target.val("");				
			}
			else{
				$target.val(value);
			}
		}catch(e){
			$target.val("");
		}
	});	
}

qs.controls.selectList = function(options){
	var opts = $.extend({
			queryMode: 1,
			dataFields: {
				textField: "text",
				valueField: "value"
			}
		}, options), $target, $container, selectedValue = opts.value;
	
	if(!opts.targetId && !opts.target){
		return;
	}
	$target = opts.targetId ? $("#" + opts.targetId) : opts.target;
	
	if(!opts.items && !opts.url){
		return;
	}
	
	init();	
	
	function init(){
		if(selectedValue !== undefined){
			$target.val(selectedValue);
		}
		
		$container = $container || $("<div class='select-list ele-hidden' />").insertAfter($target);
		initDisplay();
		
		initQueryMode(opts.queryMode);
		
		if(opts.items){
			showItems(opts.items, selectedValue);
		}
		else{
			
		}
	}
	
	function initQueryMode(mode){
		if(mode == 1){
			$target.attr("readonly", true);
		}
		else if(mode == 2){
			$target.on("keyup", function(){
				var $this = $(this);
				var condition = $.trim($this.val()), preCondition = $this.data("preData");
				
				if(condition == ""){
					if(preCondition == undefined || preCondition == ""){
						return;
					}
					else{
						showItems(opts.items, selectedValue);
					}
				}
				else{
					if(preCondition == undefined || condition != preCondition){
						if(opts.items){
							var matchedItems = [];
							$.each(opts.items, function(i, item){
								if(getItemText(item).match(condition)){
									matchedItems.push(item);
								}
							});
							
							showItems(matchedItems, selectedValue);
						}
					}
				}
				
				$this.data("preData", condition);
			});
		}
	}
	
	function initDisplay(){
		$("body").click(function(event){
			if(!$(event.target).is($target)){
				hide();				
			}
		});
		
		$container.on("mouseenter", function(){
			$("a[class='hover-item']", $container).removeClass("hover-item");
		})
		
		$container.on("mouseenter", "a", function(){
			$(this).addClass("hover-item");
		}).on("mouseleave", "a", function(){
			$(this).removeClass("hover-item");
		}).on("click", "a", function(e){
			setDataValue($(this));
			
			e.preventDefault();
		});
		
		$target.on("keydown", function(event){
			if(event.which == 38 || event.which == 40){
				var $hover = $("a[class='hover-item']", $container)
				if($hover.length == 0){
					$("a:first", $container).addClass("hover-item");
				}
				else{					
					if(event.which == 38){
						$prev = $hover.parent().prev();
						
						if($prev.length > 0){
							$hover.removeClass("hover-item");
							$prev.find("a").addClass("hover-item");								
						}				
					}
					else{
						$next = $hover.parent().next();
						
						if($next.length > 0){
							$hover.removeClass("hover-item");
							$next.find("a").addClass("hover-item");
						}
					}
				}
			}
			else if(event.which == 13){
				var $hover = $("a[class='hover-item']", $container);
				
				if($hover.length > 0){
					setDataValue($hover);
				}
				hide();
				
				event.stopPropagation();
			}
		}).on("focus", function(){
			show();
		});
		
		function hide(){
			$container.hide(0);			
		};
		
		function show(){
			var position = $target.position(), height = $target.outerHeight();
			
			var marginLeft = parseInt($target.css("margin-left")), marginTop = parseInt($target.css("margin-top"));
						
			$container.show(0).css({top: position.top + height + marginTop, left: position.left + marginLeft}).width($target.innerWidth());				
		}
	}
	
	function showItems(items, selectedValue){
		$container.empty();
		
		if($.isArray(items) && items.length > 0){
			var $ul = $("<ul />").appendTo($container);
			
			$.each(items, function(i, item){
				var $a = $("<li><a href='javascript:void(0)'/></li>").appendTo($ul).find("a").text(getItemText(item)).data("dataValue", item);
				
				if(selectedValue == getItemValue(item)){
					$a.addClass("selected-item");
				}
			});
		}
	}
	
	function setDataValue($selected){
		$("a[class='selected-item']", $container).removeClass("selected-item");
		
		var data = $selected.addClass("selected-item").data("dataValue");		
		
		$target.data("dataValue", data).val(getItemText(data));
		selectedValue = getItemValue(data);
	}
	
	function getItemText(item){
		if(opts.dataFields){
			return item[opts.dataFields.textField]
		}
		else{
			return item;
		}
	}
	
	function getItemValue(item){		
		if(opts.dataFields){
			return item[opts.dataFields.valueField]
		}
		else{
			return item;
		}
	}
	
	this.getValue = function(){
		var $item = $container.find("a[class='selected-item']");
		
		if($item.length == 0){
			return
		}
		return getItemValue($item.data("dataValue"));
	}
};