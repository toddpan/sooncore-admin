// JavaScript Document
var curMenu = null, zTree_Menu = null;
var log, className = "dark";
var setting = {
	view: {
		selectedMulti: false,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		txtSelectedEnable: true,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: true,
		showRemoveBtn: false,
		showRenameBtn: true,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	},
	callback: {
		/*beforeDrag: beforeDrag,*/
		beforeRename: beforeRename,
		onRename: zTreeOnRename,
		onClick: showValue
	}
};
//LDAP列表树设置
var ldapSetting = {
	view: {
		selectedMulti: false,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	}
};

//企业管理员列表树设置
var adminSetting = {
	view: {
		selectedMulti: false,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	}
};

//生态企业列表树设置
var stqySetting = {
	view: {
		selectedMulti: false,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	},
	callback: {
		beforeClick: showInfo,
		onClick: showInfo
	}
};


//部门多选列表树设置
var optionSetting = {
	view: {
		selectedMulti: true,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom2
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	},
	check: {
		enable: true
	},
	callback: {
		beforeClick: beforeClick,
		onCheck: onCheck
	}
};

var radioSetting = {
		check: {
			enable: true,
			chkStyle: "radio",
			radioType: "all"
		},
		view: {
			selectedMulti: true,
			showLine: false,
			showIcon: false,
			dblClickExpand: false,
			addDiyDom: addDiyDom2
		},
		data: {
			simpleData: {
				enable: true
			}
		},
		callback: {
			beforeClick: beforeClick,
			onCheck: onCheck
		}
	};

var selectSetting = {
	view: {
		selectedMulti: true,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom
	},
	data: {
		simpleData: {
			enable: true
		}
	},
	callback: {
		beforeClick: selectBeforeClick,
		onClick: selectOnClick
	}
};

var inputSetting = {
	view: {
		selectedMulti: true,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom
	},
	data: {
		simpleData: {
			enable: true
		}
	},
	callback: {
		onClick: inputOnClick
	}
};

//部门多选列表树设置
var userSetting = {
	view: {
		selectedMulti: true,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		addDiyDom: addDiyDom2
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: true
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	},
	check: {
		enable: true,
		chkboxType : {"Y": "ps", "N": "ps"}
	},
	callback: {
		beforeClick: beforeClick
	}
};

/*var zNodes =[
	{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:true,nocheck:true},
	{ id:21, pId:2, name:"研发部"},
		/*{ id:211, pId:21, name:"大洋"},
		{ id:212, pId:21, name:"新象"},
		{ id:213, pId:21, name:"刘杰"},
		{ id:214, pId:21, name:"占奎"},
	{ id:22, pId:2, name:"市场部"},
	{ id:23, pId:2, name:"营销部"},
	{ id:3, pId:1, name:"海尔生活家电事业部", open:true,nocheck:true},
	{ id:31, pId:3, name:"市场部"},
	{ id:32, pId:3, name:"营销部"},
	{ id:4, pId:1, name:"海尔电脑事业部", open:true,nocheck:true},
	{ id:41, pId:4, name:"市场部"},
	{ id:42, pId:4, name:"营销部"}
];

var userNodes =[
	{ id:1, pId:0, name:"海尔", open:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:true},
	{ id:21, pId:2, name:"研发部", open:true},
		{ id:211, pId:21, name:"大洋"},
		{ id:212, pId:21, name:"新象"},
		{ id:213, pId:21, name:"刘杰"},
		{ id:214, pId:21, name:"占奎"},
	{ id:22, pId:2, name:"市场部", open:true},
		{ id:221, pId:22, name:"王志良"},
		{ id:222, pId:22, name:"黄凯"},
		{ id:223, pId:22, name:"董向然"},
		{ id:224, pId:22, name:"全斌"},
	{ id:23, pId:2, name:"营销部", open:true},
		{ id:231, pId:23, name:"卢志新"},
		{ id:232, pId:23, name:"李想"}
];
*/
var adminNodes =[
	{ id:1, pId:0, name:"陈总", open:true,nocheck:true}
];
var stqyNodes =[
	{ id:1, pId:0, name:"创想空间商务通信服务有限公司", open:true,nocheck:true}
];

function addDiyDom(treeId, treeNode) {
	var spaceWidth = 15;
	var switchObj = $("#" + treeNode.tId + "_switch"),
	icoObj = $("#" + treeNode.tId + "_ico");
	switchObj.remove();
	icoObj.before(switchObj);

	if (treeNode.level > 0) {
		var spaceStr = "<span style='display: inline-block;width:" + (spaceWidth * treeNode.level)+ "px'></span>";
		switchObj.before(spaceStr);
	}
}

function addDiyDom2(treeId, treeNode) {
	var spaceWidth = 15;
	var switchObj = $("#" + treeNode.tId + "_check"), 
	switchObj2 = $("#" + treeNode.tId + "_switch"),
	icoObj = $("#" + treeNode.tId + "_ico");
	switchObj.remove();
	switchObj2.remove();
	
	icoObj.before(switchObj2);
	icoObj.before(switchObj);

	if (treeNode.level > 0) {
		var spaceStr = "<span style='display: inline-block;width:" + (spaceWidth * treeNode.level)+ "px'></span>";
		switchObj2.before(spaceStr);
	}
}

function zTreeOnRename(event, treeId, treeNode, isCancel) {
	$("#pop").addClass("pop");
	//showDialog("<?php echo site_url('organize/addOrg'); ?>");

	//alert(treeNode.tId + ", " + treeNode.name);
	//alert(isCancel)
	if($("#"+treeNode.tId+"_a").hasClass("curSelectedNode_Edit")) {
		alert("sss")
	}
	
		//showDialog('弹窗_提醒_添加组织结构.html');
	
}
function beforeDrag(treeId, treeNodes) {
	return false;
}

function addStAdmin(e) {
	var zTree = $.fn.zTree.getZTreeObj("adminTree"),
	//isParent = e.data.isParent,
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	
	$(".treeRight a").each(function(index, element) {
		var name = $(this).text();
		if (treeNode) {
			treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:false, name:name});
		} else {
			treeNode = zTree.addNodes(null, {id:(100 + newCount), pId:0, isParent:false, name:name});
		}
	})
	
	hideDialog(); 
	/*if (treeNode) {
		zTree.editName(treeNode[0]);
	} else {
		alert("叶子节点被锁定，无法增加子节点");
	}*/
};

function addStqy() {
	var zTree = $.fn.zTree.getZTreeObj("stqyTree"),
	//isParent = e.data.isParent,
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	
	var name = "北京总部";
	
	if (treeNode) {
		treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:false, name:name});
		zTree.selectNode(zTree.getNodeByParam("id", (100 + newCount)));
	} 

};

function removeStAdmin(e) {
	var zTree = $.fn.zTree.getZTreeObj("adminTree"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (nodes.length == 0) {
		$("#delStAdmin").addClass("disabed");
		return;
	}
	//var callbackFlag = $("#callbackTrigger").attr("checked");
	zTree.removeNode(treeNode);
};

function beforeRemove(treeId, treeNode) {
	className = (className === "dark" ? "":"dark");
	//showLog("[ "+getTime()+" beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
	/*if(dG==1){
		showDialog('弹窗_提醒_删除部门2.html');
		$("#dialog .yes").live("click",function(){
			deleteZuzhi();
			hideDialog();
			dG = 0;
		})
	}*/
	//return confirm("确认删除组织 -- " + treeNode.name + " 吗？");
}
function onRemove(e, treeId, treeNode) {
	//alert("[ onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
}
function showValue(e, treeId, treeNode){
	$("#part01 table:first tr").show();
	$("#part01 table:first td").find("input:checked").click();
	$("#novalueTable").hide().prev("table").show();
	$("#novalueTable").next(".page").show();
	
}
function showInfo(e, treeId, treeNode){
	var id = treeNode.id;
	$(".part01_"+id).show().siblings().hide();
}
function beforeRename(treeId, treeNode, newName) {
	if (newName.length == 0) {
		alert("节点名称不能为空.");
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		setTimeout(function(){zTree.editName(treeNode)}, 10);
		return false;
	}
	return true;
}


function beforeClick(treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	zTree.checkNode(treeNode, !treeNode.checked, null, true);
	return false;
}

function onCheck(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId),
	nodes = zTree.getCheckedNodes(true),
	v = "";
	for (var i=0, l=nodes.length; i<l; i++) {
		v += nodes[i].name + ",";
	}
	if (v.length > 0 ) v = v.substring(0, v.length-1);
	if(treeId == "ztree3"){
		var cityObj = $("#departmentSel");
	}
	else if(treeId == "ztree4"){
		var cityObj = $("#departmentSel2");
	}
	if(treeId == "ztree3_01"){
		var cityObj = $("#departmentSel_01");
	}
	else if(treeId == "ztree4_01"){
		var cityObj = $("#departmentSel2_01");
	}
	cityObj.attr("value",v);
}

function getSelectUser(e, treeId, treeNode){
	var zTree = $.fn.zTree.getZTreeObj("userTree"),
	nodes = zTree.getCheckedNodes(true),
	v = "", isParent = false;
	
	for (var i=0, l=nodes.length; i<l; i++) {
		isParent = nodes[i].isParent;
		if(!isParent){
			v += '<a href="javascript:;">'+ nodes[i].name +'</a>'; 
		}
	}
	if (v.length > 0 ) v = v.substring(0, v.length-1);
	$(".treeRight").append(v);
}

function showMenu(t) {
	//var cityObj = $("#departmentSel");
	//var cityOffset = $("#departmentSel").position();
	$(t).parent(".select-box").find(".selectOptionBox").slideDown("fast");
	
	$("body").bind("mousedown", onBodyDown);
}
function hideMenu() {
	$(".selectOptionBox").fadeOut("fast");
	$("body").unbind("mousedown", onBodyDown);
}
function onBodyDown(event) {
	if (!(event.target.className == "icon" || event.target.className == "text" || event.target.className == "selectOptionBox" || $(event.target).parents(".selectOptionBox").length>0)) {
		hideMenu();
	}
	
}

function selectBeforeClick(treeId, treeNode) {
	//var check = (treeNode && !treeNode.isParent);
	//if (!check) alert("只能选择城市...");
	//return check;
}

function selectOnClick(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId),
	nodes = zTree.getSelectedNodes(),
	v = "";
	nodes.sort(function compare(a,b){return a.id-b.id;});
	for (var i=0, l=nodes.length; i<l; i++) {
		v += nodes[i].name + ",";
	}
	if (v.length > 0 ) v = v.substring(0, v.length-1);
	var cityObj = $(".selectGroup span");
	cityObj.text(v);
}

function inputOnClick(e, treeId, treeNode) {
	var zTree = $.fn.zTree.getZTreeObj(treeId),
	nodes = zTree.getSelectedNodes(),
	v = "";
	nodes.sort(function compare(a,b){return a.id-b.id;});
	for (var i=0, l=nodes.length; i<l; i++) {
		v += nodes[i].name + ",";
	}
	if (v.length > 0 ) v = v.substring(0, v.length-1);
	var cityObj = $("#inputVal2");
	cityObj.val(v);
}

/** 
* 批量导入 组织列表 相关操作
*/
//添加组织
var newCount = 1;
var dG=0;
function addZuzhi(e) {
	var zTree = $.fn.zTree.getZTreeObj("ztree"),
	isParent = e.data.isParent,
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	if (treeNode) {
		treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:isParent, name:"新建节点" + (newCount++)});
	} else {
		treeNode = zTree.addNodes(null, {id:(100 + newCount), pId:0, isParent:isParent, name:"新建节点" + (newCount++)});
	}
	if (treeNode) {
		zTree.editName(treeNode[0]);
	} else {
		alert("叶子节点被锁定，无法增加子节点");
	}
	$("#novalueTable").show().prev("table").hide();
	$("#novalueTable").next(".page").hide();
}
//删除组织
function deleteZuzhi(e) {
	dG=1;
	var zTree = $.fn.zTree.getZTreeObj("ztree"),
	nodes = zTree.getSelectedNodes(),
	treeNode = nodes[0];
	
	var preNode = treeNode.getPreNode(), parentNode = treeNode.getParentNode(), nextNode = treeNode.getNextNode()

	var node = preNode != null? preNode:nextNode!=null?nextNode:parentNode!=null?parentNode:null;
	if (nodes.length == 0) {
		alert("请先选择一个节点");
		return;
	}
	var isDisplay = $("#novalueTable").is(":visible");
	if(isDisplay){
	//var callbackFlag = $("#callbackTrigger").attr("checked");
	
		
		zTree.removeNode(treeNode, true);
		zTree.selectNode(node, true);
		$("#novalueTable").hide().prev("table").show();
		if($("#part01 table thead .checkbox").hasClass("checked")) {
			$("#part01 table thead .checkbox").click();
		}
	}
	else if(!isDisplay && $("#novalueTable").prev("table").find("tbody").children("tr").length == 0){
		showDialog('弹窗_提醒_删除部门.html');
		/*$("#dialog .yes").live("click",function(){
			hideDialog();
			
			zTree.removeNode(treeNode, true);
			zTree.selectNode(node, true);
			$("#novalueTable").hide().prev("table").show();
			if($("#part01 table thead .checkbox").hasClass("checked")) {
				$("#part01 table thead .checkbox").click();
			}
		})	*/
	}
	else {
		$(".poptip3").fadeIn();
	}
};

//显示指定为部门管理者窗口
function showSetManagerDialog(){
	var _checked = $('#part01 .table:first tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	//alert(_name.text())
	showDialog('弹窗_提醒_指定为部门管理者.html');
	
	setTimeout(function(){
	$('#dialog .dialogBody .text01').html('您确定要将 '+_name.text()+' 指定为该部门的管理者吗？');},10
	)
}

//指定管理员
function setManager(){
	var _checked = $('#part01 .table tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	
	_name.addClass('manage');
	//_checked.parents("tr").siblings("tr").find(".checkbox").hide();
	_checked.click();
	hideDialog();
}

//取消管理员身份窗口
function showMoveManagerDialog(){
	showDialog('弹窗_提醒_取消管理者身份.html');
		
	var _checked = $('#part01 .table:first tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	setTimeout(function(){
		$('.D_confirm .dialogBody .text01').html('您确定要取消 '+_name.text()+' 的部门管理者身份吗？');
	},10)
}

//取消管理员身份
function moveManager(){
	var _checked = $('#part01 .table tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	
	_name.removeClass('manage');
	//_checked.parents("tr").siblings("tr").find(".radio").show();
	//manageIndex = _checked.index();
	_checked.click();
	
	hideDialog();
}

//删除组织成员
function deleteZuzhiUser(){
	var _checked = $('#part01 .table:first tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	hideDialog();
		
		//var $selectedItem = $("#tree .bbit-tree-selected");
		
		//$('.D_confirm .dialogBody .text01').html('你确定要从组织结构中删除员工 '+_name.text()+' 吗？');

		if(_checked.length == $('#part01 .table:first tbody tr').length){
			$("#novalueTable").show().prev("table").hide();
			$('#part01 .table:first tbody tr').show();
			//alert("ddddd: "+ dG)
			if(dG==1){
				alert("eeeee: "+ dG)
				showDialog('弹窗_提醒_删除部门2.html');
			}
		}else {
			_checked.parent().parent().hide();
		}
		$(".tabToolBox").hide();
}

$(function(){
	$(".selectGroup").click(function(e){
		var _e = e || window.event;
		$("#allGroup2").toggle();
		$("#allGroup2 .pop-box-content").jScrollPane();
		$("body").bind("mousedown", onSelectGroupDown);
		_e.cancelBubble = true;
		_e.returnValue = false;
		return false;
		//event.stopPropagation();
	})
})

		
		
function onSelectGroupDown(event) {
	if (!($(event.target).hasClass("selectGroup") || event.target.id == "allGroup2" || $(event.target).parents("#allGroup2").length>0)) {
		$("#allGroup2").hide();
		$("body").unbind("mousedown", onBodyDown);
	}
}