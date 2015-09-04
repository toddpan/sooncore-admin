;(function(){
	"use strict";
	
	var qs = window.qs = window.qs || {};
	if(qs.tagManager){
		return;
	}
	
	var _options = {			
	}, 
	_deptCtr, 
	_optionalTagTemplate = "<div class='tag-row tag-sortable'></div>";
	
	function _init(){
		_bindCheckBoxEvent();
		
		if($.isArray(_options.necessaryTags)){
			_createNecessaryTags(_options.necessaryTags, _options.departmentLevel);
		}
		
		if($.isArray(_options.optionalTags)){
			_createOptionalTags(_options.optionalTags);
		}
		
		if(_options.displayLDAPSetting){
			_createLDAPSetting(_options.ldapSetting);
		}
		
		_bindSaveBarEvent();
	}
	
	
	
	
	function _createNecessaryTags(tags, departmentLevel){
		var html = "<div class='tag-row'><label class='tag-name' /></div>";
		
		var $container = $("#tagManager div[type='necessaryTags']");
		$.each(tags, function(i, tag){
			if(!tag["tag_code"]){
				return;
			}

			var $row = $(html).appendTo($container), $label = $row.find("label").text(tag["tag_name"]);
			
			$row.data("tag-data", tag);
			if(tag["tag_code"] == "department"){
				var $dept = $("<input class='combo selectBox dept-level' value='请选择部门层级' />").insertAfter($label);
				
				var opts = {
					queryMode : 1,
					target: $dept,
					items: [{ text: 1, value: 1	},
					        { text: 2, value: 2	},
					        { text: 3, value: 3	},
					        { text: 4, value: 4	},
					        { text: 5, value: 5	},
					        { text: 6, value: 6	},
					        { text: 7, value: 7	},
					        { text: 8, value: 8	},
					        { text: 9, value: 9	},
					        { text: 10, value: 10 }]
				};
				if(departmentLevel != "-1"){
					opts.value = departmentLevel;
				}
				
				_deptCtr = new qs.controls.selectList(opts);
			}
			
			if(tag["client_editable"] != undefined){
				var $editor = $("<div class='tag-setting'><div class='tag-client'><label class='checkbox right-space'><input type='checkbox' client-editable>允许员工变更</label><label class='title right-space'>输入长度<input type='text' class='spinner-text left-mini-space' value='0' maxlength='2' value-max-length></label></div></div>").appendTo($row);
				
				if(tag["client_editable"] === "1"){
					$editor.find("input[client-editable]").attr("checked", true).change();
				}
				qs.controls.integerFormat($editor.find("input[value-max-length]").val(tag["value_max_length"]));
			}
		});
	}
	
	function _createOptionalTags(tags){
		var $container =$("<div class='tag-rows' />").appendTo($("#tagManager div[type='optionalTags']"));

		_bindOptionalTagEvent($container);
		
		$.each(tags, function(i, tag){
			_createOptionalTag(tag, $(_optionalTagTemplate).appendTo($container));
		});	
		
		_createCustomTagBar($container.parent());
		
		$container.sortable({
		      connectWith: "div[class*='tag-sortable']"
	    });
	}
	
	function _createOptionalTag(tag, $element){
		$element.data("tag-data", tag);

		var $tagSetting = $("<div class='tag-setting'><div class='tag-client'></div></div>").appendTo($element);
		if(tag.tag_type === "2"){			
			$($("#customTagActions").html()).appendTo($tagSetting).on("click", "a", function(e){
				var $this = $(this), action = $this.attr("type");
				
				if(action == "edit"){
					var $row = $this.parent().parent().parent();
					$row.children().addClass("ele-hidden");
					
					_createEditArea($row);
				}
				else if(action == "delete"){
					$this.parent().parent().parent().remove();
				}
				
				e.preventDefault();
			});
		}
		
		var $label = $("<label class='checkbox'></label>").prependTo($element).text(tag["tag_name"]);
		
		var $checkbox = $("<input type='checkbox' tag/>").prependTo($label).val(tag["tag_name"]);		
		
		var checked = tag["selected"];
		if(checked === "1"){
			$checkbox.attr("checked", true).parent().addClass("checked");
			$checkbox.change();
		}
	}
	
	function _createCustomTagBar($container, tagName){
		var $btn = $("<div class='tag-row' type='customAction'><a class='btn_addTag' href='javascript:void(0);'>添加员工标签</a></div>").appendTo($container);
		
		$btn.on("click", "a", function(e){
			e.preventDefault();
			
			var $row = $(this).parent().addClass("ele-hidden");
			_removeEditableStatus($row.prev().children("div[action='edit']"));			
			
			var $customTemlate = $("<div class='tag-row'></div>").insertAfter($row).attr("action", "add").append($("#customTagCreation").html()).on("click", "a", function(e){
				e.preventDefault();
				
				var $this = $(this), $delegateTarget = $(e.delegateTarget);
				
				if($this.attr("type") == "confirm"){
					var $tagName = $delegateTarget.find("input"), value = $.trim($tagName.val());
					if(value == ""){
						$tagName.addClass("required").focus();
					}
					else if(_existTagName(value)){
						alert("标签名已经存在，请重新填写！");
					}
					else{
						var $tagBar = $delegateTarget.prev();
						_createOptionalTag({ tag_name: value, selected: "1", tag_type: "2" }, $(_optionalTagTemplate).appendTo($tagBar.prev()));
						
						$tagBar.removeClass("ele-hidden");
						$delegateTarget.remove();
					}
				}
				else if($this.attr("type") == "cancel"){
					$delegateTarget.prev().removeClass("ele-hidden");
					$delegateTarget.remove();
				}
			});
			qs.controls.addWatermark($customTemlate.find("input[class='normal-text']"), "请输入员工信息");
		});
	}
	
	function _createEditArea($row){
		_removeEditableStatus($row.parent().children("div[action='edit']"));
		_resetCustomTagCreation();
		
		var $customTemlate = $($("#customTagCreation").html()).appendTo($row.attr("action", "edit")).on("click", "a", function(e){
			e.preventDefault();
			
			var $this = $(this), $delegateTarget = $(e.delegateTarget);
			
			if($this.attr("type") == "confirm"){
				var $tagName = $delegateTarget.find("input"), value = $.trim($tagName.val());
				if(value == ""){
					$tagName.addClass("required").focus();
				}
				else if(_existTagName(value, $row)){
					alert("标签名已经存在，请重新填写！");
				}
				else{
					_removeEditableStatus($row);					
					
					var $label = $row.data("tag-data", $.extend($row.data("tag-data"), { tag_name: value })).children().first();
					var html = $label.html();
					$label.empty().text(value);
					$(html).prependTo($label).val(value);
				}
			}
			else if($this.attr("type") == "cancel"){
				_removeEditableStatus($row);
			}
		});
		qs.controls.addWatermark($customTemlate.find("input[class='normal-text']").val($row.data("tag-data").tag_name), "请输入员工信息");
		
		function _resetCustomTagCreation(){
			$row.parent().next().removeClass("ele-hidden").next().remove();
		}
	}
		
	function _removeEditableStatus($editRow){			
		$("div.edit-container", $editRow).remove();
		$editRow.removeAttr("action").children().removeClass("ele-hidden");
	}
	
	
	function _createLDAPSetting(ldapSetting){
		if(!ldapSetting){
			return;
		}
		
		var $setting = $($("#ldapSetting").html()).insertAfter($("#tagManager div[type='optionalTags']"));
		
		var $checkbox = $setting.find("input[type='checkbox']").change(function(){
			var $this = $(this);
			
			if($this.attr("checked")){
				$this.parent().parent().next().removeClass("ele-hidden");
			}else{
				$this.parent().parent().next().addClass("ele-hidden");
			}
		});
		
		if(ldapSetting.use_suffix == 1){
			$checkbox.attr("checked", true).change();			
		}
		
		$setting.find("input[type='text']").val(ldapSetting.suffix);
	}
	
	
	
	
	
	function _bindCheckBoxEvent(){
		$("#tagManager").on("change", "input[type='checkbox']", function(){
			var $this = $(this);
			
			if($this.attr("checked")){
				$this.parent().addClass("checked");
			}
			else{
				$this.parent().removeClass("checked");
			}
		});
	}
	
	function _bindOptionalTagEvent($container){
		$container.on("change", "input[tag]", function(){
			var $this = $(this);
			
			if($this.attr("checked")){
				var $tagSetting = $($("#customTagSettings").html()).appendTo($("div.tag-client", $this.parent().parent()));
				
				qs.controls.integerFormat($("input[value-max-length]", $tagSetting));
				
				var tagData = $this.parent().parent().data("tag-data");
				if(tagData){
					if(tagData.selected === "1"){
						if(tagData.client_searchable === "1"){
							$("input[client-searchable]", $tagSetting).attr("checked", true).change();
						}
						if(tagData.client_visible === "1"){
							$("input[client-visible]", $tagSetting).attr("checked", true).change();
						}
						if(tagData.client_editable === "1"){
							$("input[client-editable]", $tagSetting).attr("checked", true).change();
						}
						if(tagData.value_max_length != undefined){
							$("input[value-max-length]", $tagSetting).val(tagData.value_max_length);
						}
					}
				}
			}
			else{
				$this.parent().next().children("div.tag-client").empty();
			}
		});
		
		$container.on("change", "input[client-visible]", function(){
			var $this = $(this);
			
			if($this.attr("checked")){
				$this.parent().next().removeClass("ele-hidden").find("input").removeAttr("checked").change();
				$this.parent().next().next().removeClass("ele-hidden").find("input").removeAttr("checked").change();
			}
			else{
				$this.parent().next().addClass("ele-hidden");
				$this.parent().next().next().addClass("ele-hidden");
			}
		});
	}
	
	
	
	function _bindSaveBarEvent(){
		$("#tagManager div[type='saveBar']").on("click", "a[confirm]", function(e){
			e.preventDefault();
			
			save();
		}).on("click", "a[cancel]", function(e){			
			e.preventDefault();
			
			var linkPage = qs.linkPage.get();
			
			if(linkPage && linkPage.url){
				loadCont(linkPage.url);
			}
			else{
				loadPage(window.baseUrl + "main/mainPage", "main");
			}
		});
	}
	
	function _existTagName(tagName, $currentRow){
		var $rows = $("#tagManager div[type='necessaryTags']").find("div[class*='tag-row']");

		var names = {};
		$.each($rows, function(){
			var $this = $(this);

			if(!$currentRow || ($currentRow && !$currentRow.is($this))){
				names[$this.data("tag-data").tag_name] = true;
			}
		});
		
		$rows = $("#tagManager div[type='optionalTags']").find("div[class*='tag-rows']").find("div[class*='tag-row']");
		$.each($rows, function(){
			var $this = $(this);

			if(!$currentRow || ($currentRow && !$currentRow.is($this))){
				names[$this.data("tag-data").tag_name] = true;
			}
		});
		
		if(names[tagName]){
			return true;
		}
		else{
			return false;
		}
	}
		
	function _collectNecessaryTags(){
		var $tagRows = $("#tagManager div[type='necessaryTags']").find("div[class*='tag-row']");
		
		var tagDatas = [], isValid = true;
		$.each($tagRows, function(){
			var $this = $(this), tagData = $this.data("tag-data");
			
			if(tagData && tagData.tag_name){				
				if(tagData.tag_code === "department"){
					var deptLevel;
					if(_deptCtr){
						deptLevel =_deptCtr.getValue(); 
					}
					
					if(!deptLevel){
						$("input[class*='dept-level']", $this).addClass("required");
						isValid = false;
					}
				}
				
				if(tagData.client_editable != undefined){
					tagData.client_editable = $("input[client-editable]", $this).attr("checked") ? "1" : "0";
					
					if(tagData.client_editable === "1"){
						tagData.value_max_length =   $("input[value-max-length]", $this).removeClass("required").val();
						
						if(!tagData.value_max_length){
							$("input[value-max-length]", $this).addClass("required");
							isValue = false;
						}
					}
					else{
						tagData.value_max_length = 0;
					}
				}
				
				tagDatas.push(tagData);
			}
		});
		
		return isValid ? tagDatas : false;
	}
	
	function _collectOptionalTags(){
		var $tagRows = $("#tagManager div[type='optionalTags']").find("div[class*='tag-rows']").find("div[class*='tag-row']");
		
		var tagDatas = [], index = 1, isValid = true;
		$.each($tagRows, function(){
			var $this = $(this), tagData = $this.data("tag-data");
			
			if(tagData && tagData.tag_name){
				tagData.selected = $("input[tag]", $this).attr("checked") ? "1" : "0";
				
				if(tagData.selected === "1"){
					tagData.client_searchable = $("input[client-searchable]", $this).attr("checked") ? "1" : "0";
					tagData.client_visible = $("input[client-visible]", $this).attr("checked") ? "1" : "0";
					
					if(tagData.client_visible === "1"){
						tagData.client_editable = $("input[client-editable]", $this).attr("checked") ? "1" : "0";
						if(tagData.client_editable === "1"){
							tagData.value_max_length = $("input[value-max-length]", $this).removeClass("required").val();
						}
						else{
							tagData.value_max_length = "0";
						}
					}
					else{
						tagData.client_editable = "0";
						tagData.value_max_length = "0";						
					}
						
				}
				else{
					tagData.client_searchable = "0";
					tagData.client_visible = "0";
					tagData.client_editable = "0";
					tagData.value_max_length = "0";
				}
				
				if(!tagData.value_max_length){
					$("input[value-max-length]", $this).addClass("required");
					isValid = false;
					
					return false;
				}
				
				tagData.sequence = index;
				
				index++;
				
				tagDatas.push(tagData);
			}
		});
		
		return isValid ? tagDatas : false;
	}
	
	function _collectLDAPSetting(){
		var useSuffix, suffix;
		
		if(_options.displayLDAPSetting){
			var $ldapSetting = $("#tagManager div[type='ldapSetting']");
			useSuffix = $ldapSetting.find("input[type='checkbox']").attr("checked") ? "1" : "0";
			
			if(useSuffix === "1"){
				var $suffix = $ldapSetting.find("input[type='text']");
				
				suffix = $.trim($suffix.val());
				if(!suffix){
					$suffix.addClass("required").next().removeClass("ele-hidden");
					return false;
				}
				else{
					$suffix.removeClass("required").next().addClass("ele-hidden");
				}
			}
		}
		
		return { useSuffix: useSuffix, suffix: suffix };
	}
	
	function redirect(){
		var url;
		
		var linkPage = qs.linkPage.get();
		if(linkPage && linkPage.url){
			url = linkPage.url;
		}
		else{
			if(_options.displayLDAPSetting == 0){
				url = "batchimport/index";
			}
			else{
				url = "ldap/showLdapPage";
			}
		}
		
		loadCont(url);
	}
	
	function save(){
		var necessaryTags = _collectNecessaryTags();
		if(necessaryTags === false){
			return;
		}
		
		var optionalTags = _collectOptionalTags();
		if(optionalTags === false){
			return;
		}
		
		var ldapSetting = _collectLDAPSetting();
		if(ldapSetting === false){
			return;
		}
		
		var deptLevel;
		if(_deptCtr){
			deptLevel =_deptCtr.getValue(); 
		}
		if(!deptLevel){
			alert("请选择部门层级！")
			return;
		}
		
		var obj = {
				department_level: deptLevel,						// 部门层级
				necessaryTags: JSON.stringify(necessaryTags),		// 标签值
				optionalTags: JSON.stringify(optionalTags),			// 可选标签和自定义标签
				use_suffix: ldapSetting.useSuffix,					// 登录名是否使用自定义后缀
				suffix: ldapSetting.suffix							// 自定义后缀
		 };
		 
		 $.ajax({
			url: "tag/addTag",
			type: "post",
			data: obj,
			success: function(data) {
				var result = JSON.parse(data);
				if(result.code == 0){
					redirect();
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
	
	
	
	window.qs.tagManager = {
		init: function(options){
			_options = $.extend(_options, options);
			_init();
		}
	};
}());