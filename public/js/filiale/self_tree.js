// JavaScript Document
var curMenu = null, zTree_Menu = null;
var log, className = "dark";
//组织结构部分
var setting = {
	view: {
		isSimpleData: true,
		selectedMulti: false,
		treeNodeKey: "id",
		treeNodeParentKey: "pId",
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		txtSelectedEnable: true,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: true,
		showRemoveBtn: false,
		showRenameBtn: setBtn,
		editNameSelectAll: true,
		/*drag: {
			autoExpandTrigger: true
		}*/
	},
	data: {
		keep: {
			parent: false,
			leaf: false
		},
		simpleData: {
			enable: true
		}
	},
	callback: {
		/*beforeDrag:zTreebeforeDrag,*/
		onDrag:zTreeonDrag,
		beforeDragOpen:zTreebeforeDragOpen,
		onDrop: zTreeonDrog,
		beforeDrop: zTreebeforeDrop,
		onExpand: onExpand,
		beforeExpand: zTreebeforeExpand,
		beforeRename: beforeRename,
		onRename: zTreeOnRename,
		onClick: showValue,
		beforeClick: zTreeBeforeClick,
		beforeDrag: zTreeBeforeDrag,
		/*onDragMove: zTreeOnDragMove,*/
		onMouseDown: zTreeOnMouseDown
	}
};
//组织结构树的所有回调函数
//是否禁用展开
function zTreebeforeExpand(treeId, treeNode) {
	//alert(1);
	var obj;
	obj = "ztree";
	//var zTree = $.fn.zTree.getZTreeObj(obj);
	//var treeParent=zTree.getNodes()[0].children;
	//var leng=treeParent.length;
	if (treeNode.isDisabled == true) {
		//$('#addZuzhi').addClass("disabled");
		// $('#deleteZuzhi').addClass("disabled");
		judge = 1;
		return false;
	}
}
//加载下一级节点
function onExpand(event, treeId, treeNode) {
	var expand_path;
	var len = zNodes.length;
	var orgid = treeNode.id;
	var obj;
	//obj="ztree";
	//obj = treeId;
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	//create_node11(zNodes);
	var j = 0;
	if(treeId=="ztree")
	{
		expand_path=path;
		obj = {
				"org_id": orgid
			};
		//if (treeNode.children != null) {
		// alert(treeNode.children[1].name);
		if (j == 0 && treeNode.isParent && treeNode.children==null) { //alert(2);
			//var path="<?php echo site_url('organize/get_next_OrgList');?>";
			
			//$.post(path,obj,function(data)
			$.ajax({
				url: expand_path,
				async: false,
				type: "POST",
				data: obj,
				success: function(data) {
					if (data != null) {
						var childNodes = eval('(' + data + ')');
						var leng = childNodes.length;
						for (var i = 0; i < leng; i++) {
							var count = 0;
							for (var j = 0; j < zNodes.length; j++) {
								if (childNodes[i].id == zNodes[j].id && childNodes[i].pId == zNodes[j].pId) {
									count++;
								}
							}
							if (count == 0) {
								//alert(12121)
								zNodes.push(childNodes[i]);
							}

						}
						childNodes = zTree.addNodes(treeNode, childNodes);
						//zTree.removeNode(treeNode.children[0]);
					}

					//alert(treeNode.children[0].id);
					//expand_node(childNodes);
				}
			})
		}
	//}
	}
	else
	{
		expand_path=cost_next;
		obj = {
				"id": orgid
			};
		if (treeNode.children != null) {
		// alert(treeNode.children[1].name);
		if (j == 0 && treeNode.children==null && treeNode.isParent) { //alert(2);
			//var path="<?php echo site_url('organize/get_next_OrgList');?>";
			
			//$.post(path,obj,function(data)
			$.ajax({
				url: expand_path,
				async: false,
				type: "POST",
				data: obj,
				success: function(data) {
					if (data != null) {
						
						data=$.parseJSON(data);
						var childNodes=data.data;
						var leng = childNodes.length;
						costNode.push(childNodes);
					}
					childNodes = zTree.addNodes(treeNode, childNodes);
					//zTree.removeNode(treeNode.children[0]);
				}
			})
		}
	}
	}
}
//禁用重命名
function setBtn(treeId, treeNode) {
	if (treeNode.isrename == false || treeNode.isDisabled == true) {
		return ! treeNode;
	} else {
		return true;
	}
}
var isCan; //取消编辑名称
var isadd; //增加的节点
function borther_name_test(treeId,treeNode)
{
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var nodes=zTree.getNodes();
	var num=0;
	for(var i=0;i<nodes.length;i++)
	{
		if(nodes[i].name==treeNode.name && nodes[i].id !=treeNode.id)
		{
			alert("您创建的成本中心已存在，请重新命名");
			num++;
			return false;
		}
	}
	if(!num)
	{
		php_rename(treeId,treeNode)
		//return true;
	}
	
}
var drag_value=0;
function zTreeOnMouseDown(event,treeId, treeNode)
{
	drag_value=1;
}
var i=0;
function zTreeBeforeDrag(treeId, treeNodes) {
	i++;
	if(i%2==1)
	{
		if(rename_value)
		{
			rename_value=1;
		}
		return false;
	}
	else
	{
		if(rename_value)
		{
			rename_value=0;
			return false;
		}
		return true;
	}
	
};
function zTreeonDrag(event,treeId,treeNodes) {
	
	var x=event.target;
	var reg=/ztree/;
	if(!reg.test(x.id))
	{
		
	}
	//alert()
	//return true;
};
function zTreeOnDragMove(event,treeId,treeNodes)
{
	var x=event.target;
	var reg=/ztree/;
	if(!reg.test(x.id))
	{
		
	}
}
var rename_value=0;
function php_rename(treeId,treeNode,newName)
{
	var return_num;
	var Node=treeNode;
	//newName=newName.replace(/^\s+|\s+$/g,'');
	//alert(newName.charAt(0))
	//newName.charAt(0)="我";
	//alert(newName)
	if(treeId=="ztree")
	{
		var obj;
		obj = "ztree";
		//alert(111)
		var zTree = $.fn.zTree.getZTreeObj(obj);
		var treenode = {
			"id": treeNode.id,
			//$('#pop').attr("target_id")
			"pId": treeNode.pId,
			//$("#pop").attr("target_pId")
			"name": newName //$("#pop").attr("target_name")
		};
		$.ajax({
			url: path_new_org,
			async: false,
			type: "POST",
			data: treenode,
			success: function(data) {
				var json = $.parseJSON(data);
				if (json.code == 0) {
					treeNode.name=newName;
					zTree.updateNode(treeNode);
					//alert(treeNode.name)
					$('#ztree .curSelectedNode').find("input.rename").attr("value",newName);
					$('#ztree .curSelectedNode').attr("title",newName);
					//如果是新加成功
					if (treeNode.id == 0) {
						showDialog('organize/addOrg');
						treeNode.id = json.other_msg.org_id;
						zTree.updateNode(treeNode);
						 var obj = {
								"parent_orgid": treeNode.pId,
								"org_id": treeNode.id
							};
						load_staff(obj, path_user, path_mag);
						//console.log(treeNode);
						zNodes.push(treeNode);
					} else {
						//alert(treeNode.name)
						var leng = zNodes.length;
						for (var i = 0; i < leng; i++) {
							if (zNodes[i].id == treenode.id && zNodes[i].pId == treenode.pId) {
								zNodes[i].name = newName;
								break;
							}
						}
					}
				$('#addZuzhi').removeClass("false");
				//alert(1111)
				return_num=true;
				//return true;
	
				} else {
					$('#addZuzhi').removeClass("false");
					alert('操作失败');
					setTimeout(function() {
					zTree.editName(treeNode)
					},
					10);
					return_num=false;
					//return false;
					//hideDialog();
				}
				//rename_value=0;
				
			}
		});
	}
	else
	{
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		//如果是新加成功
		//alert(1)
		if(treeNode.id==0)
		{	
			var id=treeNode.pId;
			if(treeNode.pId==null)
			{
				id=0;
			}
			var treenode = {
				"id": id,
				"name": treeNode.name //$("#pop").attr("target_name")
			};
			$.ajax({
				url: add_cost,
				async: false,
				type: "POST",
				data: treenode,
				success: function(data) {
					$('#add_cost').removeClass("false");
					var json = $.parseJSON(data);
					if (json.code == 0) {
						treeNode.name=newName;
						zTree.updateNode(treeNode);
						//alert(treeNode.name)
						$('#ztreecostcenter .curSelectedNode').find("input.rename").attr("value",newName);
						$('#ztreecostcenter .curSelectedNode').attr("title",newName);
						showDialog(add_cost_dialog);
						treeNode.id = json.data.id;
						return_num=true;
						//return return_num;
						//zTree.updateNode(treeNode);
						/* var obj = {
								"parent_orgid": treeNode.pId,
								"org_id": treeNode.id
							};
						load_staff(obj, path_user, path_mag);*/
						
						//treeNode.id = json.other_msg.org_id;
						// alert(treenode)
						//zNodes.push(treenode);
					}
					else
					{
						//alert(1)
						alert(json.msg)
						setTimeout(function() {
						zTree.editName(treeNode)
						},
						10);
						//isCancel=false;
						return_num=false;
						//return return_num;
						//alert(212)
						//var zTree = $.fn.zTree.getZTreeObj(treeId);
						//zTree.editName(treeNode);
						//return;
						//alert(2)
					}
				}
			});
		}
		else {
				var zTree = $.fn.zTree.getZTreeObj(obj);
				var id=treeNode.id;
				var treenode = {
					"id": id,
					"name": treeNode.name
				};
				$.ajax({
				url: change_cost,
				async: false,
				type: "POST",
				data: treenode,
				success: function(data) {
					$('#add_cost').removeClass("false");
					var json = $.parseJSON(data);
					if (json.code == 0) {
						treeNode.name=newName;
						zTree.updateNode(treeNode);
						//alert(treeNode.name)
						$('#ztreecostcenter .curSelectedNode').find("input.rename").attr("value",newName);
						$('#ztreecostcenter .curSelectedNode').attr("title",newName);
						return_num=true;
						//return return_num;
						//zTree.cancelEdit();
						//showDialog(add_cost_dialog);
						//treeNode.id = json.other_msg.org_id;
						//zTree.updateNode(treeNode);
						//zNodes.push(treenode);
					}
					else
					{
						return_num=false;
						alert(json.msg)
						setTimeout(function() {
						zTree.editName(treeNode)
						},
						10);
						//var zTree = $.fn.zTree.getZTreeObj(treeId);
						//zTree.editName(Node);
						//return return_num;
					}
				}
			})
					//hideDialog();
		}
			
	}
	//alert(return_num)
	if(return_num)
	{
		treeNode.name=newName;
		zTree.updateNode(treeNode);
	}
	return return_num;
}
var isCan; //取消编辑名称
var isadd; //增加的节点
function beforeRename(treeId, treeNode, newName, isCancel) {
	newName=newName.replace(/^(\s|\u00A0)+/,'').replace(/(\s|\u00A0)+$/,'');  
	rename_value=1;
	var obj;
	obj = treeId;
	var zTree = $.fn.zTree.getZTreeObj(obj);
	if(obj=="ztree")
	{
		var treeParent = treeNode.getParentNode();
		var childNode = treeParent.children;
		var Num = new RegExp("新建节点");
		var Nul = /^\S+$/;
		var count = 0;
		for (var i = 0; i < newName.length; i++) {
			if (!Nul.test(newName.charAt(i))) {
				count++;
			}
		}
		if (treeNode.name == newName) {
			//alert(2);
			isCan = true;
	
			//zTree.cancelEditName(newName);
			//return true;
		}
		var new_test = Num.test(newName);
		//var rt1=zTree.getNodesByParam("name",newName,treeParent);
		var rt1;
		if (newName == treeParent.name) {
			rt1 = false;
		} else {
			rt1 = true;
		}
		var rt3 = true; //=zTree.getNodesByParam("name",newName,childNode);
		for (var i = 0; i < childNode.length; i++) {
	
			if (newName == childNode[i].name && treeNode.id != childNode[i].id) {
				//	alert(childNode[i].name)
				rt3 = false;
			}
		}
		//alert(rt1)
		// alert(rt3)
		if (treeNode.id == 0) {
			if (newName.length == 0 || count == newName.length) {
				alert("节点名称不能为空.");
				//$('body').attr('onselectstart', 'return false');
				//$("body").css("-moz-user-select", "none");
				var zTree = $.fn.zTree.getZTreeObj("ztree");
				setTimeout(function() {
				zTree.editName(treeNode)
				},
				10);
				
				return false;
			} else if (!rt1 || !rt3) {
				//alert(2)
				alert("您创建的新部门已存在，请重新输入");
				//$('body').attr('onselectstart', 'return false');
				//$("body").css("-moz-user-select", "none");
				$('body').trigger('click');
				setTimeout(function() {
				zTree.editName(treeNode)
				},
				10);
				return false;
			} else if (newName.length > 60) {
				alert("您创建的新部门名称过长，请重新输入");
				
				
				setTimeout(function() {
				zTree.editName(treeNode)
				},
				10);
				return false;
			} else {
				return php_rename(treeId,treeNode,newName);
				//$('body').attr('onselectstart', '');
				//return true;
			}
			//}
		} else if (newName.length == 0 || count == newName.length) {
			alert("节点名称不能为空.");
			var zTree = $.fn.zTree.getZTreeObj("ztree");
			setTimeout(function() {
				zTree.editName(treeNode)
			},
			10);
			return false;
		} else if (!rt1 || !rt3) {
			//alert(111)
			alert("不能与上级部门或兄弟部门名称相同！");
			//$('body').attr('onselectstart', 'return false');
			//$("body").css("-moz-user-select", "none");
			
			//$('body').trigger('click');
			var zTree = $.fn.zTree.getZTreeObj("ztree");
			setTimeout(function() {
				zTree.editName(treeNode)
			},
			10);
			return false;
		} else if (newName.length > 60) {
			alert("您创建的部门名称过长，请重新输入");
			//$('body').attr('onselectstart', 'return false');
			//$("body").css("-moz-user-select", "none");
			//$('body').trigger('click');
			setTimeout(function() {
				zTree.editName(treeNode)
			},
			10);
			//$('body').trigger('click');
			return false;
		} else {
			return php_rename(treeId,treeNode,newName);
			//$('body').attr('onselectstart', '');
			//$("body").css("-moz-user-select","none");
			//return true;
		}
	}
	else
	{
		var n=zTree.getNodes();
		if(n[0].id==0 && n[0].pId==null)
		{
			var zTree = $.fn.zTree.getZTreeObj(treeId);
			//zTree.editName(treeNode)
			//alert(php_rename(treeId,treeNode))
			 return php_rename(treeId,treeNode,newName);
		}
		else
		{
			return borther_name_test(treeId,treeNode,newName);
		}
		//return false;
	}
	
	

}
function zTreeOnRename(event, treeId, treeNode, isCancel) {
	//alert(treeNode.name)
	//treeNode.name="2121324324"
	var value=$('#ztree .curSelectedNode').find("input.rename").val();
	treeNode.name=value;
}
function zTreeBeforeClick(treeId, treeNode, clickFlag)
{
	//alert(treeNode.name)
	var re;
	if(treeId=="ztree")
	{
		var du=$('#part1');
		if(!infor_click_org(du))
		{
			re=false;
		}
		else
		{
			re=true;
		}
	}
	else
	{
		var du=$('#part02');
		if(!infor_click_org(du))
		{
			re=false;
		}
		else
		{
			re=true;
		}
	}
	return re;
}
function infor_click_org(t)
{
	var ass=1;
	if(t.length>0)
	{
		if(t.hasClass("value_change"))
		{
			ass=confirm("您确定不保存修改的员工信息吗？")
		}
	}
	return ass;
}
function showValue(e, treeId, treeNode){
	if(treeId=="ztree")
	{
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		if (treeNode != null) {
			 var value = [];
			 value.push(treeNode.name);
			 var nodes=treeNode;
			 while (nodes.pId != null) {
				   nodes  =  zTree.getNodesByParam("id", nodes.pId,  null);
				   value.push(nodes[0].name);
				   nodes=nodes[0];
				  }
			 var staff_depart = "";
						//staff_depart=' <div class="bread part0">';
			 for (var i = value.length - 1; i > 0; i--) {
				   staff_depart = staff_depart +'<span>' + value[i] +'</span>&nbsp;&gt;&nbsp';
				  }
			 staff_depart = staff_depart +'<span>' + value[i] +'</span>';
			 $('#part01').children("div.bread").text('').append(staff_depart).addClass("part0");
			 $('#part01 .groupLimit .toolBar2').next().remove();
						//alert(treeNode.name)
			 if (treeNode.isDisabled == false || treeNode.isDisable==null) {
				  var org_ID = treeNode.id; //获得当前组织id
				  var parent_orgid = treeNode.pId;
							//alert(org_ID);
						   // alert(parent_orgid)
				  var obj = {
								"parent_orgid": parent_orgid,
								"org_id": org_ID
							};
				  load_staff(obj, path_user, path_mag);
				 }
		  }
	}
	else
	{
		$('.treeNode').removeClass("no_setCost");
		
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		if (treeNode != null) {
			var value = [];
			value.push(treeNode.name);
			var nodes=treeNode;
			while (nodes.pId != null) {
				   nodes  =  zTree.getNodesByParam("id", nodes.pId,  null);
				   value.push(nodes[0].name);
				   nodes=nodes[0];
				  }
			var staff_depart = "";
						//staff_depart=' <div class="bread part0">';
			for (var i = value.length - 1; i > 0; i--) {
				   staff_depart = staff_depart +'<span>' + value[i] +'</span>&nbsp;&gt;&nbsp';
				  }
			staff_depart = staff_depart +'<span>' + value[i] +'</span>';
			staff_depart="<span>成本中心</span>&nbsp;&gt;&nbsp"+staff_depart;
			$('#part02 .bread').html('');
			$("#part02 .bread").append(staff_depart);
			 if (treeNode.isDisabled == false || treeNode.isDisable==null) {
				$(".deleteCenter").removeClass("disabled");
				var org_ID = treeNode.id; //获得当前组织id
				var parent_orgid = treeNode.pId;
				var Tree=$.fn.zTree.getZTreeObj("ztree2");
				//var org=Tree.getSelectedNodes();
					if(Tree==null)
					{
						var org=0;
					}else
					{
						var org=Tree.getSelectedNodes();
						if(org[0]==null)
						  {
							  org=0;
						  }
						else
						  {
							  org=org[0].id;
						  }
					}
				 var obj = {
					  "id":org_ID,
					  "org_id":org,
					  "count":0,
					  "page":0
					};
					 load_staff_center(obj, path_cost_user);
					// alert(111)
				  //load_staff(obj, path_user, path_mag);
				 }
		  }
	}
	
}
//拖拽节点之后的回调函数
function zTreebeforeDrop(treeId, treeNodes, targetNode, moveType) 
{
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var old_org_parent=treeNodes[0].getParentNode();
	var old_org_brother=old_org_parent.children;
	var new_org_parent;
	var new_org_brother=[];
	var childNodes=[];
	var type;
	if(targetNode.isParent && targetNode.children==null)
	 {
		var  obj = {
				"org_id": targetNode.id
			};
		 $.ajax({
				url: path,
				async: false,
				type: "POST",
				data: obj,
				success: function(data) {
					if (data != null) {
						childNodes = eval('(' + data + ')');
						var leng = childNodes.length;
						for (var i = 0; i < leng; i++) {
							var count = 0;
							for (var j = 0; j < zNodes.length; j++) {
								if (childNodes[i].id == zNodes[j].id && childNodes[i].pId == zNodes[j].pId) {
									count++;
								}
							}
							if (count == 0) {
								//alert(12121)
								zNodes.push(childNodes[i]);
							}

						}
						//
						//zTreeOnCollapse(event, treeId, treeNode) 
						//zTree.removeNode(treeNode.children[0]);
					}

					//alert(treeNode.children[0].id);
					//expand_node(childNodes);
				}
			})
	 }
	//alert(new_org_brother)
	if(moveType=="inner")
	{
		new_org_parent=targetNode;
		
		if(!targetNode.isParent)
		{
			//alert(1)
			new_org_brother=[];
			//alert(2)
		}
		else
		{
			new_org_brother=targetNode.children;
			if(new_org_brother==undefined)
			{
				new_org_brother=childNodes;
			}
			
		}
		if(treeNodes[0].name==targetNode.name)
		{
			alert("您拖动的部门名称和目标部门相同，请为该部门重命名后再进行拖动")
			return false;
		}
		else
		{
			if(new_org_brother!=null)
			{
				for(var i=0;i<new_org_brother.length;i++)
				{
					if(treeNodes[0].id!=new_org_brother[i].id)
					{
						if(treeNodes[0].name==new_org_brother[i].name)
						{
							return false;
						}
					}
				}
			}
			
		}
		//type=2;
		//alert(new_org_brother)
	}
	else if(moveType=="prev" || moveType=="next")
	{
		//type=1;
		if(targetNode.pId!=treeNodes[0].pId)
		{
			//type=2;
		}
		else
		{
			//type=1;
			return false;
		}
		//alert(1)
		if(targetNode.pId==null)
		{
			return false;
		}
		new_org_parent=targetNode.getParentNode();
		if(new_org_parent.childNodecount==0)
		{
			new_org_brother=[];
		}
		else
		{
			//alert(3)
			
			if(moveType=="prev")
			{
				//for(var i=0;i<)
				var j=0;
				var brother=new_org_parent.children;
				for(var i=0;i<brother.length;i++)
				{
					if(brother[i].id!=treeNodes[0].id)
					{
						if(brother[i].id==targetNode.id)
						{
							new_org_brother[j]=treeNodes[0];
							j=j+1;
							new_org_brother[j]=targetNode;
							//alert(targetNode.name)
							//alert(j)
						}
						else 
						{
							new_org_brother[j]=brother[i];
						}
						j++;
					}
				}
			}else
			{
				var j=0;
				var brother=new_org_parent.children;
				for(var i=0;i<brother.length;i++)
				{
					if(brother[i].id!=treeNodes[0].id)
					{
						if(brother[i].id==targetNode.id)
						{
							new_org_brother[j]=targetNode;
							j=j+1;
							new_org_brother[j]=treeNodes[0];
						}
						else 
						{
							new_org_brother[j]=brother[i];
						}
						j++;
					}
				}
			}
			//alert(4)
			//new_org_brother=targetNode.children;
		}
		if(treeNodes[0].name==new_org_parent.name)
		{
			return false;
		}
		else
		{
			if(new_org_brother!=null)
			{
				for(var i=0;i<new_org_brother.length;i++)
				{
					if(treeNodes[0].id!=new_org_brother[i].id)
					{
						if(treeNodes[0].name==new_org_brother[i].name)
						{
							//alert(4)
							return false;
						}
					}
				}
			}
			
		}
		
	}
	//alert(new_org_brother)
	var old_parent=''+old_org_parent.id+'';
	var old_brother='';
	if(old_org_brother!=undefined)
	{
		for(var i=0;i<old_org_brother.length;i++)
		{
			
				//if(i==old_org_brother.length-1)
				//{
					if(old_org_brother[i].id!=treeNodes[0].id)
					{
					old_brother=old_brother+''+old_org_brother[i].id+',';
					}
				//}else
				//{
					//if(old_org_brother[i].id!=treeNodes[0].id)
					//{
					//old_brother=old_brother+''+old_org_brother[i].id+',';
					//}
			//	}
			
		}
		var staff_tag_post = old_brother;
		var lastIndex = staff_tag_post.lastIndexOf(',');
		if (lastIndex > -1) {
		   staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
		}
		old_brother=staff_tag_post;
	}
	var new_parent=''+new_org_parent.id+'';
	var new_brother='';
	if(new_org_brother!=undefined)
	{
		for(var i=0;i<new_org_brother.length;i++)
		{
			
			/*if(i==new_org_brother.length-1)
			{
				new_brother=new_brother+''+new_org_brother[i].id+'';
			}else
			{
				new_brother=new_brother+''+new_org_brother[i].id+',';
			}*/
			//if(new_org_brother[i].id!=treeNodes[0].id)
			//{
			new_brother=new_brother+''+new_org_brother[i].id+',';
			//}
		}
		var staff_tag_post = new_brother;
		var lastIndex = staff_tag_post.lastIndexOf(',');
		if (lastIndex > -1) {
		   staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
		}
		new_brother=staff_tag_post;
	}
	var obj={
		"move_org_id":treeNodes[0].id,
		"old_parent":old_parent,
		"old_brother":old_brother,
		"new_parent":new_parent,
		"new_brother":new_brother
	};
	var return_num=true;
	//alert(obj)
	$.ajax({
				url: drag_path,
				async: false,
				type: "POST",
				data: obj,
				success: function(data) {
					//alert(data)
					var json=$.parseJSON(data);
					if(json.code==0)
					 {
						 if(moveType=="inner")
						 {
							 childNodes = zTree.addNodes(targetNode, childNodes);
						 }
					 }
					 else
					 {
						 alert("操作失败");
						// $()
						 return_num=false;
						 //return false;
					 }
				}
		   });
	//alert(return_num)
	return return_num;
	//onExpand(event,treeId,targetNode);
}
function  zTreeonDrog(event,treeId, treeNodes, targetNode, moveType)
{
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	if(moveType=="inner")
	 {
		 var span;
		 if($('#ztree li').find(".curSelectedNode").find("span").length==5)
		 {
			//if($('#ztree li').find(".curSelectedNode").parent().parent().parent().find("a:eq(0)").find("span").length==4)
			///{
				span=$('#ztree li').find(".curSelectedNode").parent().parent().parent().find("a:eq(0)").find("span:eq(0)");
				//alert(span.css("width"))
				//$('#ztree li').find(".curSelectedNode").find("span:eq(0)").before(span);
				var inter=parseInt(span.css("width"));
				inter=inter+15;
				$('#ztree li').find(".curSelectedNode").find("span:eq(0)").css("width",inter);
				
			//}
		 }
	 }
	 else if(moveType=="prev")
	 {
		 var span;
		 if($('#ztree li').find(".curSelectedNode").find("span").length==5)
		 {
			if($('#ztree li').find(".curSelectedNode").parent().next().find("a:eq(0)").find("span").length==4)
			{
				span=$('#ztree li').find(".curSelectedNode").parent().next().find("a:eq(0)").find("span:eq(0)");
				//alert(span.css("width"))
				//$('#ztree li').find(".curSelectedNode").find("span:eq(0)").before(span);
				$('#ztree li').find(".curSelectedNode").find("span:eq(0)").css("width",span.css("width"));
				
			}
		 }
	 }
	 else if(moveType=="next")
	 {
		 var span;
		 if($('#ztree li').find(".curSelectedNode").find("span").length==5)
		 {
			if($('#ztree li').find(".curSelectedNode").parent().prev().find("a:eq(0)").find("span").length==4)
			{
				span=$('#ztree li').find(".curSelectedNode").parent().prev().find("a:eq(0)").find("span:eq(0)");
				//alert(span.css("width"))
				//$('#ztree li').find(".curSelectedNode").find("span:eq(0)").before(span);
				$('#ztree li').find(".curSelectedNode").find("span:eq(0)").css("width",span.css("width"));
				
			}
		 }
		 
	 }
	
	// alert(2)
}
function zTreebeforeDragOpen(treeId,treeNode)
{
	return false;
}
/** 
* 批量导入 组织列表 相关操作
*/
//添加组织
var target=1;
var newCount = 1;
//var dG=0;
function addZuzhi(e) {
	//alert(11111);
	if($('#addZuzhi').hasClass("false"))
	{
		return false;
	}
	$('#addZuzhi').addClass("false");
	var t=$(e.target);
	if(t.hasClass("disabled"))
	{
		return;
	}
	//alert(2)
	var zTree = $.fn.zTree.getZTreeObj("ztree");
	//alert(1);
	isParent = e.data.isParent;
	nodes = zTree.getSelectedNodes();
	treeNode = nodes[0];
	if(treeNode==null)
	{
		$('#addZuzhi').removeClass("false");
		return false;
	}
	if(treeNode.isParent)
	{
	 onExpand(e,"ztree",treeNode);
	};
	
	if (treeNode) {
	
		var newNode= {id:(0), pId:treeNode.id, isParent:false, name:"新建节点" + (newCount++)};
		treeNode1= zTree.addNodes(treeNode,newNode);
		$('#addZuzhi').removeClass("false");
	} else {
		treeNode = zTree.addNodes(null, {id:(0), pId:0, isParent:false, name:"新建节点" + (newCount++)});
		$('#addZuzhi').removeClass("false");
	}
	if (treeNode1) {
		zTree.editName(treeNode1[0]);
		
	} else {
		alert("叶子节点被锁定，无法增加子节点");
	}
	//$('body').trigger('click');
	$("#novalueTable").show().prev("table").hide();
	$("#novalueTable").next(".page").hide();
}
function addCost(e) {
	//alert(11111);
	var treeNode1;
	if($('#add_cost').hasClass("false"))
	{
		return false;
	}
	$('#add_cost').addClass("false");
	var t=$(e.target);
	if(t.hasClass("disabled"))
	{
		return;
	}
	var zTree = $.fn.zTree.getZTreeObj("ztreecostcenter");
	var getNode=zTree.getNodes();
	//alert(getNode)
	if(getNode=='')
	{
		var newNode= {id:0, pId:0, isParent:false, name:"新建节点" + (newCount++)};
		treeNode1=zTree.addNodes(null,newNode);
		/*var nodes  =  zTree.getNodesByParam("id", newNode.id,  null);
		treeNode1=zTree.moveNode(getNode[0],nodes[0],"prev");
		zTree.editName(treeNode1)
		var node  =  zTree.getNodesByParam("id", 10,  null);
		zTree.removeNode(node[0]);*/
	}else
	{
		isParent = e.data.isParent;
		nodes = zTree.getSelectedNodes();
		treeNode = nodes[0];
		if(treeNode==null)
		{
			return false;
			
		}
		if(treeNode.isParent)
		{
		 onExpand(e,"ztreecostcenter",treeNode);
		};
		if (treeNode) {
			//alert(1);
			var newNode= {id:(0), pId:treeNode.id, isParent:false, name:"新建节点" + (newCount++)};
			//treeNode1= zTree.addNodes(treeNode,newNode);
			treeNode1=zTree.addNodes(null,newNode);
			var node=zTree.getNodesByParam("id", newNode.id,  null);
			zTree.moveNode(treeNode,node[0],"next");
		} else {
			treeNode = zTree.addNodes(null, {id:(0), pId:0, isParent:false, name:"新建节点" + (newCount++)});
		}
	}
	
	if (treeNode1) {
		zTree.editName(treeNode1[0]);
		
	} else {
		alert("叶子节点被锁定，无法增加子节点");
	}
	//$('body').trigger('click');
	$("#novalueTable").show().prev("table").hide();
	$("#novalueTable").next(".page").hide();
}
var Alldepartsetting = {
	view: {
		isSimpleData:true,
		selectedMulti: false,
		treeNodeKey:"id",
		treeNodeParentKey:"pId",
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		txtSelectedEnable: true,
		addDiyDom: addDiyDom
	},
	edit: {
		enable: true,
		showRemoveBtn: false,
		showRenameBtn:false,
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
		onExpand:AlldepartonExpand,
		beforeExpand:AlldepartbeforeExpand,
		onClick: showValuedepart

	}
};
function AlldepartbeforeExpand(treeId,treeNode)
{
	//alert(1);
	if(treeId=="ztree2")
	{
		var obj="ztree2";
		var zTree = $.fn.zTree.getZTreeObj(obj);
		var treeParent=zTree.getNodes()[0].children;
		var leng=treeParent.length;
		if(treeNode.isDisabled==true)
		{
			//$('#addZuzhi').addClass("disabled");
		   // $('#deleteZuzhi').addClass("disabled");
			judge=1;
			return false;
		}	
	}
	else
	{
		
	}
}
//加载下一级节点

function AlldepartonExpand(event,treeId,treeNode)
{
	if(treeId=="ztree2")
	{
		var orgid=treeNode.id;
		var obj="ztree2";
		var zTree = $.fn.zTree.getZTreeObj(obj);
		if(treeNode.children==null && treeNode.isParent)
		{
			 obj={
				"org_id":orgid
					};		
			$.post(path,obj,function(data)
			{
			//alert(data);
				 var childNodes = eval('(' +data + ')'); 
			  var leng=childNodes.length;
			  for(var i=0; i<leng;i++)
				 {
					 zNodes.push(childNodes[i]);
					 if(childNodes[i].childNodeCount>0)
					   {  
						 var node={id:1,pId:childNodes[i].id,name:''};
						 childNodes.push(node);
					   }
				 }
			 
			   var i=0;
							 while(zNodes[i]!=null)
								 {
									if(zNodes[i].pId==treeNode.id && zNodes[i].name=='')
										{
		  //alert(1);
										  zNodes.splice(i,1);
		  //zNodes.pop(zNodes[i]);
										}else
										{
											i++;
										}
								 }
				childNodes=zTree.addNodes(treeNode,childNodes);
			 // zTree.removeNode(treeNode.children[0]);
			 // alert(childNodes)
			  //expand_node(childNodes);
			})
		}
	}
	
	
}
function showValuedepart(e, treeId, treeNode){
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		if (treeNode != null) {
				 var org_ID = treeNode.id; //获得当前组织id
				 var obj = {
					  "org_id":org_ID,
					  "count":0,
					  "page":0
					};
		 load_staff_center(obj,cost_org_staff);
					// alert(111)
				  //load_staff(obj, path_user, path_mag);
		}
}

function onExpandLdap()
{
	var obj;
	obj="ldapTree";
	/*if($("#ldapTree").hasClass("small_group"))
	{
		
	}
	else
	{
		obj="ldapTree";
	}*/
	var zTree = $.fn.zTree.getZTreeObj(obj);
	var treeParent=zTree.getNodes()[0].children;
	if($('span').hasClass('button'))
		{ 
		  $('span.button').each(function()
		  {
		     if($(this).attr("target")==1)
			 {
			  /* var selectId=$(this).parent().attr("id"); 
               var Num=new RegExp("[1-9]");
			   var se_id=Num.exec(selectId);*/
			   var selectText=$(this).parent().text();
			  
			  /* var?nodes?=?zTree.getNodesByParam("id",selectId,zTree);
			   alert(nodes.name);
			   var node=zTree.getNodesByParam("name",selectText,treeParent);*/
               for(var i=0;i<treeParent.length;i++)
	            {
		            if(treeParent[i].name==selectText && treeParent[i].children[0].name=="")
		               { 
						 /*  zTree.removeChildNodes?(treeParent[i]);
			            zTree.selectNode(treeParent[i]);*/
						
			            childNodes=zTree.addNodes(treeParent[i],childNodes);
						zTree.removeNode(treeParent[i].children[0]);
						 
		               }
	            }
			   
			 }
			/* $(this).removeClass("online_close");
			 $(this).addClass("online_open");
			 $(this).next().removeClass("online_close");
			 $(this).next().addClass("online_open");
			 $(this).parent().next().show();*/
			 
		  })
		}
}
//企业管理员列表树设置
/*var adminSetting = {
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
	
};*/


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
		beforeExpand:selectbeforeExpand,
		onExpand:select_onExpand,
		beforeClick: selectBeforeClick,
		onClick: selectOnClick
	}
};
function selectbeforeExpand(treeId,treeNode)
{
	//alert(1);
   var obj="selectTree";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	var treeParent=zTree.getNodes()[0].children;
	var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function select_onExpand(event,treeId,treeNode)
{
	var orgid=treeNode.id;
	var obj="selectTree";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	//if()
	if(treeNode.children[0].name=="" && treeNode.children[0].id=="1")
	{
		//alert(1);
	//var path="<?php echo site_url('organize/get_next_OrgList');?>";
		 obj={
			"org_id":orgid
				};		
		$.post(path,obj,function(data)
		{
		//alert(data); 
		  var childNodes = eval('(' +data + ')'); 
		  var leng=childNodes.length;
          for(var i=0; i<leng;i++)
	         {
				 zNodes.push(childNodes[i]);
	             if(childNodes[i].childNodeCount>0)
	               {  
	                 var node={id:1,pId:childNodes[i].id,name:''};
	                 childNodes.push(node);
	               }
	         }
		 
		   var i=0;
						 while(zNodes[i]!=null)
      						 {
    							if(zNodes[i].pId==treeNode.id && zNodes[i].name=='')
									{
	  //alert(1);
	 								  zNodes.splice(i,1);
      //zNodes.pop(zNodes[i]);
									}else
									{
										i++;
									}
							 }
		 // alert(childNodes)
		  childNodes=zTree.addNodes(treeNode,childNodes);
		  zTree.removeNode(treeNode.children[0]);
		  //expand_node(childNodes);
		})
	}
	
}
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
		beforeExpand:inputbeforeExpand,
		onExpand:input_onExpand,
		onClick:click_input_node
		//onClick: inputOnClick
	}
};
//是否禁用展开
function inputbeforeExpand(treeId,treeNode)
{
	//alert(1);
   var obj="departmentTree";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	var treeParent=zTree.getNodes()[0].children;
	var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function input_onExpand(event,treeId,treeNode)
{
	//alert(3333)
	var orgid=treeNode.id;
	var obj="departmentTree";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	//if()
	if(treeNode.children==null && treeNode.isParent)
	{
		//alert(1);
	//var path="<?php echo site_url('organize/get_next_OrgList');?>";
		 obj={

			"org_id":orgid
				};		
		$.post(path1,obj,function(data)
		{
		//alert(data); 
		  var childNodes = eval('(' +data + ')'); 
		  var leng=childNodes.length;
          for(var i=0; i<leng;i++)
	         {
				 zNodes1.push(childNodes[i]);
	             if(childNodes[i].childNodeCount>0)
	               {  
	                 var node={id:1,pId:childNodes[i].id,name:''};
	                 childNodes.push(node);
	               }
	         }
		 
		  /* var i=0;
						 while(zNodes1[i]!=null)
      						 {
    							if(zNodes1[i].pId==treeNode.id && zNodes1[i].name=='')
									{
	  //alert(1);
	 								  zNodes1.splice(i,1);
      //zNodes.pop(zNodes[i]);
									}else
									{
										i++;
									}
							 }*/
		 // alert(childNodes)
		  childNodes=zTree.addNodes(treeNode,childNodes);
		  //zTree.removeNode(treeNode.children[0]);
		 // alert(2222)
		  //expand_node(childNodes);
		})
	}
	
}
function click_input_node(e, treeId, treeNode)
{
	//alert(treeNode.name)
	$('#inputVal2').find("input").val(treeNode.name);
	$("#inputVal2").removeClass('error');
}
var moveSetting = {
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
		beforeExpand:movebeforeExpand,
		onExpand:move_onExpand,
		onClick:show_movevalue
		//onClick: inputOnClick
	}
};
function show_movevalue(e, treeId, treeNode){
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var zTree1= $.fn.zTree.getZTreeObj("ztree");
	var treeNode1=zTree1.getSelectedNodes();
	//alert(treeNode1[0].name)
	if(treeNode1[0].id==treeNode.id)
	{
		//alert(1212)
		alert("不需要调入到自己的部门")
		 zTree.cancelSelectedNode(treeNode);
	}
}
//是否禁用展开
function movebeforeExpand(treeId,treeNode)
{
	//alert(1);
	if(treeId=="dgmoveorg")
	{
		var obj="dgmoveorg";
		var zTree = $.fn.zTree.getZTreeObj(obj);
		var treeParent=zTree.getNodes()[0].children;
		var leng=treeParent.length;
		if(treeNode.isDisabled==true)
		{
			return false;
		}	
	}
}
function move_onExpand(event,treeId,treeNode)
{
	if(treeId=="dgmoveorg")
	{
		var orgid=treeNode.id;
		var obj="dgmoveorg";
		var zTree = $.fn.zTree.getZTreeObj(obj);
		if(treeNode.children==null && treeNode.isParent)
		{
			 obj={
				"org_id":orgid
					};		
			$.post(path,obj,function(data)
			{
			//alert(data); 
			  var childNodes = eval('(' +data + ')'); 
			  var leng=childNodes.length;
			  for(var i=0; i<leng;i++)
				 {
					 zNodes.push(childNodes[i]);
				 }
			  childNodes=zTree.addNodes(treeNode,childNodes);
			})
		}
	}
	else
	{
		//alert(11)
		//alert(treeId)
		var zTree = $.fn.zTree.getZTreeObj(treeId);
		//alert(zTree)
		var orgid=treeNode.id;
		expand_path=cost_next;
		obj = {
				"id": orgid
			};
		//if (treeNode.children != null) {
		// alert(treeNode.children[1].name);
		if (treeNode.children==null && treeNode.isParent) { //alert(2);
			//var path="<?php echo site_url('organize/get_next_OrgList');?>";
			
			//$.post(path,obj,function(data)
			$.ajax({
				url: expand_path,
				async: false,
				type: "POST",
				data: obj,
				success: function(data) {
					if (data != null) {
						//alert(data)
						data=$.parseJSON(data);
						var childNodes=data.data;
						var leng = childNodes.length;
					}
					childNodes = zTree.addNodes(treeNode, childNodes);
					//zTree.removeNode(treeNode.children[0]);
				}
			})
		}
		//}
	}
	
}
var foldSetting = {
	view: {
		selectedMulti:true,
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
		beforeExpand:foldbeforeExpand,
		onExpand:fold_onExpand,
		beforeClick:foldselectNode,
		onClick:foldafterselectedNode
	}
};
function foldselectNode(treeId, treeNode, clickFlag)
{
	//alert(clickFlag)
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var Node=zTree.getSelectedNodes();
	var count=0;
	if(Node!=undefined)
	{
		for(var i=0;i<Node.length;i++)
		{
			if(Node[i]==treeNode)
			{
				//alert(1)zTree.getNodesByParam("id",treeNode.id, Node);
				count++;
				
				//tag=1;
			}
		}
		if(count)
		{
			//alert(2)
			zTree.cancelSelectedNode(treeNode);
			return false;
		}
		else
		{
			//alert(1)
			zTree.selectNode(treeNode,true);
			if(treeNode!=null && (treeNode.isParent || treeNode.userCount>0 || treeNode.identity==1))
			 { //加载自组织和子员工
				
				 var child=treeNode.children;//alert(child.length)
				if(child!=undefined)
				{
				 if(child[0].name=='' && child[0].id==1)
				 {
				 post_add_staff(treeNode,cost_get_staff,zTree,1);
				 }
				}
				
			   //$('#inputVal2').val(treeNode[0].name);
			  } 
			return false;
		}
	}
	else
	{
		zTree.selectNode(treeNode,true);
		if(treeNode!=null && (treeNode.isParent || treeNode.userCount>0 || treeNode.identity==1))
		 { //加载自组织和子员工
		 	//alert(2)
			 var child=treeNode.children;
			// alert(child.length)
			if(child!=undefined)
			{
			 if(child[0].name=='' && child[0].id==1)
			 {
			 post_add_staff(treeNode,cost_get_staff,zTree,1);
			 }
			}
			
		 }
		return false;
		   //$('#inputVal2').val(treeNode[0].name);
		 // } 
		//alert(1)
		//tag=1;
	}
}
function foldafterselectedNode(event, treeId, treeNode, clickFlag)
{
	//alert(2);
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	if(treeNode!=null  && (treeNode.isParent || treeNode.userCount>0 || treeNode.identity==1))
		 { //加载自组织和子员工
		 	//alert(2)
			 var child=treeNode.children;
			// alert(child.length)
			  if(child!=undefined)
				{
				 if(child[0].name=='' && child[0].id==1)
				 {
				 post_add_staff(treeNode,cost_get_staff,zTree,1);
				 }
				}
		 }
}
//是否禁用展开
function foldbeforeExpand(treeId,treeNode)
{
	//alert(1);
   //var obj="treeLeft";
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var treeParent=zTree.getNodes()[0].children;
	var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function fold_onExpand(event,treeId,treeNode)
{
	var orgid=treeNode.id;
	//var obj="treeLeft";
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	post_add_staff(treeNode,cost_get_staff,zTree,1);
}
var costSetting = {
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
		beforeExpand:costbeforeExpand,
		onExpand:cost_onExpand
		//onClick: inputOnClick
	}
};
//是否禁用展开
function costbeforeExpand(treeId,treeNode)
{
	//alert(1);
   var obj="costtreeLeft";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	var treeParent=zTree.getNodes()[0].children;
	var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function cost_onExpand(event,treeId,treeNode)
{
	var orgid=treeNode.id;
	var obj="costtreeLeft";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	post_add_staff(treeNode,cost_get_staff,zTree,2);
}
//点击选中，点击小三角，在调入员工时，加载自组织，和子员工
function  post_add_staff(treeNode,cost_get_staff,zTree,type)
{
	 if(treeNode.children[0].name=="" && treeNode.children[0].id=="1")
			  {
                 
					var obj={
				   "org_id":treeNode.id,
				   "type":type
					};
				$.post(cost_get_staff,obj,function(data)
				{
				   var json=$.parseJSON(data);
				   if(json.code==0)
				   {
					   
						//var json=$.parseJSON(data);

						if(treeNode.isParent>0)
						{
							var node=json.other_msg.orgs;
							var childNodes=eval('(' +node+ ')');
							var leng=childNodes.length;
						//alert(leng)
							for(var i=0; i<leng;i++)
	        	 			{
					 				//cost_zNodes.push(childNodes[i]);

								if(childNodes[i].isParent>0 || childNodes[i].userCount>0)
	             				 {
										var node={id:1,pId:childNodes[i].id,name:''};
	             	 					 childNodes.push(node);
	             				 }
	        				 }
		  					childNodes=zTree.addNodes(treeNode,childNodes);
							if(treeNode.children[0].name=='')
							{
							zTree.removeNode(treeNode.children[0]);
							}
						}
						if(treeNode.userCount>0)
						{
							var node=json.other_msg.users;
							var childNodes=eval('(' +node+ ')');
							var leng=childNodes.length;
						//alert(leng)
							for(var i=0; i<leng;i++)
	        	 			{
					 				//cost_zNodes.push(childNodes[i]);

								if(childNodes[i].isParent>0 || childNodes[i].userCount>0)
	             				 {
										var node={id:1,pId:childNodes[i].id,name:''};
	             	 					 childNodes.push(node);
	             				 }
	        				 }
		  					childNodes=zTree.addNodes(treeNode,childNodes);
							if(treeNode.children[0].name=='')
							{
							zTree.removeNode(treeNode.children[0]);
							}
		  				 //zTree.removeNode(treeNode.children[0]);
						}


					}
				});

			 }
}

//生态企业列表树设置
var stqySetting = {
    view: {
        isSimpleData:true,
        selectedMulti: false,
        treeNodeKey:"id",
        treeNodeParentKey:"pId",
        showLine: false,
        showIcon: false,
        dblClickExpand: false,
        addDiyDom: addDiyDom
    },
    edit: {
        enable: false,
        showRemoveBtn: false,
        showRenameBtn:setBtn,
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
        onExpand:onstqyExpand,
        beforeExpand:stqybeforeExpand,
        onClick: stqyshowValue
    }
};
//组织结构树的所有回调函数
//是否禁用展开
function stqybeforeExpand(treeId,treeNode)
{
    //alert(1);
   // var zTree = $.fn.zTree.getZTreeObj(treeId);
    //var treeParent=zTree.getNodes()[0].children;
   // var leng=treeParent.length;
    if(treeNode.isDisabled==true)
    {
        //$('#addZuzhi').addClass("disabled");
        // $('#deleteZuzhi').addClass("disabled");
        judge=1;
        return false;
    }
}
//加载下一级节点
function onstqyExpand(event,treeId,treeNode)
{
    var len=stqyNodes.length;
    var orgid=treeNode.id;
    var obj=treeId;
    var zTree = $.fn.zTree.getZTreeObj(obj);
    //create_node11(zNodes);
    var j=0;
    if(j==0 && treeNode.children==null && treeNode.isParent)
        { //alert(2);
            //var path="<?php echo site_url('organize/get_next_OrgList');?>";
			 //zTree.removeNode(treeNode.children[0]);
            obj={
                "org_id":orgid
            };
            //$.post(path,obj,function(data)
            $.ajax({
                url:path,
                async:false,
                type:"POST",
                data:obj,
                success:function(data)
                {
                    if(data!=null)
                    {
						//alert(data)
                        var childNodes = eval('(' +data + ')');
						
                        var leng=childNodes.length;
                        for(var i=0; i<leng;i++)
                        { var count=0;
                            for(var j=0;j<stqyNodes.length;j++)
                            {
                                if(childNodes[i].id==stqyNodes[j].id && childNodes[i].pId==stqyNodes[j].pId)
                                {
                                    count++;
                                }
                            }
							//alert(2222)
                            if(count==0)
                            {
                                //alert(12121)
                                stqyNodes.push(childNodes[i]);
                            }

                        }
                        childNodes=zTree.addNodes(treeNode,childNodes);
                       
                    }

                    //alert(treeNode.children[0].id);
                    //expand_node(childNodes);
                }
            })
        }
}
//禁用重命名
function stqysetBtn(treeId,treeNode)
{
    if(treeNode.isrename==false || treeNode.isDisabled==true)
    {
        return !treeNode;
    }
    else
    {
        return true;
    }
}
var isCan;//取消编辑名称
var isadd;//增加的节点
function stqyshowValue(e, treeId, treeNode){
	
   $('#departmentSel2').val(treeNode.name);
	$('#departmentSel2').attr("company_user_id",treeNode.id);
}
/**
 * 批量导入 组织列表 相关操作
 */
//添加组织
var target=1;
var newCount = 1;
//var dG=0;
function addZuzhistqy(e) {
    //alert(11111);
    var zTree = $.fn.zTree.getZTreeObj("ztree");
    isParent = e.data.isParent;
    nodes = zTree.getSelectedNodes();
    treeNode = nodes[0];
    if(treeNode==null)
    {
        return false;
    }
    /* if(nodes[0].children==null)
     {
     //zTree.removeNode(nodes[0].children[0]);
     }
     else
     {
     if(nodes[0].children[0].name=='' )
     {
     zTree.removeNode(nodes[0].children[0]);
     }
     }*/
    //
    //alert(treeNode.children.length)
    // zTree.removeNode(treeNode.children[0]);
    //var newNode= {id:(1), pId:treeNode.id, isParent:!isParent, name:""};
    //treeNode1= zTree.addNodes(treeNode,newNode);
    onExpand(e,treeNode.id,treeNode);
    if (treeNode) {
        //alert(1);
        var newNode= {id:(0), pId:treeNode.id, isParent:false, name:"新建节点" + (newCount++)};
        treeNode1= zTree.addNodes(treeNode,newNode);
    } else {
        treeNode = zTree.addNodes(null, {id:(0), pId:0, isParent:false, name:"新建节点" + (newCount++)});
    }
    if (treeNode1) {
        zTree.editName(treeNode1[0]);
    } else {
        alert("叶子节点被锁定，无法增加子节点");
    }
    $("#novalueTable").show().prev("table").hide();
    $("#novalueTable").next(".page").hide();
}

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
    },
	 callback: {
        onExpand:onadminExpand,
        //beforeExpand:adminbeforeExpand,
        onClick: adminshowValue
    }
};
//加载下一级节点
function onadminExpand(event,treeId,treeNode)
{
    var len=adminNodes.length;
    var orgid=treeNode.id;
    var obj=treeId;
    var zTree = $.fn.zTree.getZTreeObj(obj);
    //create_node11(zNodes);
    var j=0;
    
        // alert(treeNode.children[1].name);
    if(j==0 && treeNode.children==null && treeNode.isParent)
        { //alert(2);
            //var path="<?php echo site_url('organize/get_next_OrgList');?>";
            obj={
                "id":orgid
            };
            //$.post(path,obj,function(data)
            $.ajax({
                url:adminpath,
                async:false,
                type:"POST",
                data:obj,
				dataType:"json",
                success:function(data)
                {
                    if(data!=null)
                    {
						//alert(data)
                        //var childNodes = eval('(' +data + ')');
						var childNodes=data.other_msg;
                        var leng=childNodes.length;
                        for(var i=0; i<leng;i++)
                        { var count=0;
                            for(var j=0;j<adminNodes.length;j++)
                            {
                                if(childNodes[i].id==adminNodes[j].id && childNodes[i].pId==adminNodes[j].pId)
                                {
                                    count++;
                                }
                            }
							//alert(2222)
                            if(count==0)
                            {
                                //alert(12121)
                                adminNodes.push(childNodes[i]);
                            }

                         /*   if(childNodes[i].childNodeCount>0)
                            {
                                var node={id:1,pId:childNodes[i].id,name:''};
                                childNodes.push(node);
                            }*/
                        }
                        childNodes=zTree.addNodes(treeNode,childNodes);
                       // zTree.removeNode(treeNode.children[0]);
                    }

                    //alert(treeNode.children[0].id);
                    //expand_node(childNodes);
                }
            })
        //}
    }


}
function adminshowValue()
{
	$("#part01 table:first tr").show();
    $("#part01 table:first td").find("input:checked").trigger("click");
    $("#novalueTable").hide().prev("table").show();
    $("#novalueTable").next(".page").show();
}
function onExpandLdapDepart()
{
	var obj;
	obj="selectTree";
	/*if($("#ldapTree").hasClass("small_group"))
	{
		
	}
	else
	{
		obj="ldapTree";
	}*/
	var zTree = $.fn.zTree.getZTreeObj(obj);
	var treeParent=zTree.getNodes()[0].children;
	if($('span').hasClass('button'))
		{ 
		  $('span.button').each(function()
		  {
		     if($(this).attr("target")==1)
			 {
			  /* var selectId=$(this).parent().attr("id"); 
               var Num=new RegExp("[1-9]");
			   var se_id=Num.exec(selectId);*/
			   var selectText=$(this).parent().text();
			  
			  /* var?nodes?=?zTree.getNodesByParam("id",selectId,zTree);

			   alert(nodes.name);
			   var node=zTree.getNodesByParam("name",selectText,treeParent);*/
               for(var i=0;i<treeParent.length;i++)
	            {
		            if(treeParent[i].name==selectText && treeParent[i].children[0].name=="")
		               { 
						 /*  zTree.removeChildNodes?(treeParent[i]);
			            zTree.selectNode(treeParent[i]);*/
						
			            childNodes=zTree.addNodes(treeParent[i],childNodes);
						zTree.removeNode(treeParent[i].children[0]);
						 
		               }
	            }
			   
			 }
			/* $(this).removeClass("online_close");
			 $(this).addClass("online_open");
			 $(this).next().removeClass("online_close");
			 $(this).next().addClass("online_open");
			 $(this).parent().next().show();*/
			 
		  })
		}
}
//LDAP列表树设置
var ldapSetting={
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
	check:{
		enable:true,
		chkboxType:{"Y":"ps","N":"ps"}
	}
};
function onldapCheck(event,treeId,treeNode)
{
	var zTree = $.fn.zTree.getZTreeObj("ldap_tree");
	var treeObj=zTree;
	var treeChild=[];
	//treeNode.halfCheck=false;
	//alert(treeNode.isParent)
	if(treeNode.isParent)
	{
		treeChild=treeNode.children;
	}
	else
	{
		treeChild=0;
	}
	
	//alert(treeChild.length)
	if(treeChild==0)//没有子节点的节点只需要考虑其父节点
	{
		var Parent=treeNode.getParentNode();
		var tree=treeNode;
		while(Parent.level!=0)
		{
			var count=0;
			if(tree.checked==true)
			{
				for(var i=0;i<Parent.children.length;i++)
				{
					if(Parent.children[i].checked==true)
					{
						count++;
					}
				}
				if(count==Parent.children.length)
				{
					Parent.checked=true;
					treeObj.updateNode(Parent);
				}
				else
				{
					Parent.checked=false;
					treeObj.updateNode(Parent);
				}
			}
			else
				{
					Parent.checked=false;
					treeObj.updateNode(Parent);
				
				}
			tree=Parent;
			Parent=Parent.getParentNode();	
		}
		
		//}
	}else//有子节点的节点，考虑有父节点和没有父节点，没有父节点的一定是根节点
	{
		if(treeNode.checked==true)//操作子节点
		{
			for(var i=0;i<treeChild.length;i++)
			{
				//alert(1)
			 // zTree.selectNode(treeChild[i]);
			 treeChild[i].checked=true;
			 var node=treeChild[i];
			 var nodeChild;
			 while(node.isParent)
			 {
				 nodeChild=node.children;
				 for(var j=0;j<nodeChild.length;j++)
				 {
					 nodeChild[j].checked=true;
					 treeObj.updateNode(nodeChild[j]);
				 }
				 
			 }
			 treeObj.updateNode(treeChild[i]);
			}
		}else
		{
			for(var i=0;i<treeChild.length;i++)
			{
				//alert(1)
			 // zTree.selectNode(treeChild[i]);
			 treeChild[i].checked=false;
			 treeObj.updateNode(treeChild[i]);
			}
		}
		if(treeNode.level!=0)//操作父节点
		{
			var Parent=treeNode.getParentNode();
			var count=0;
			if(treeNode.checked==true)
			{
				for(var i=0;i<Parent.children.length;i++)
				{
					if(Parent.children[i].checked==true)
					{
						count++;
					}
				}
				if(count==Parent.children.length)
				{
					Parent.checked=true;
					treeObj.updateNode(Parent);
				}
				else
				{
					Parent.checked=false;
					treeObj.updateNode(Parent);
				}
			}
			else
				{
					Parent.checked=false;
					treeObj.updateNode(Parent);
				}
			
		}
	}
}
/*var zNodes =[
	{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:true,nocheck:true},
	{ id:21, pId:2, name:"研发部"},
		
	{ id:22, pId:2, name:"市场部"},
	{ id:23, pId:2, name:"营销部"},
	{ id:3, pId:1, name:"海尔生活家电事业部", open:true,nocheck:true},
	{ id:31, pId:3, name:"市场部"},
	{ id:32, pId:3, name:"营销部"},
	{ id:4, pId:1, name:"海尔电脑事业部", open:true,nocheck:true},
	{ id:41, pId:4, name:"市场部"},
	{ id:42, pId:4, name:"营销部"},
];*/


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

//设置节点的部门名称



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
	cityObj.attr("value",v);
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


function beforeExpand(treeNode)
{
	
	
	
	
	/**/
	
			/* */
}

	/*zTree.addNodes(treeNode,{id:(100 + newCount), pId:treeNode.id, name:"新建节点" + (newCount++)});*/

//显示指定为部门管理者窗口
/*function showSetManagerDialog(){
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
}*/

//取消管理员身份窗口
/*function showMoveManagerDialog(){
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
}*/

//删除组织成员
/*function deleteZuzhiUser(){
	var _checked = $('#part01 .table:first tbody .checked');
	var _name = _checked.parent().next().find('.userName');
	hideDialog();
		
		//var $selectedItem = $("#tree .bbit-tree-selected");
		
		//$('.D_confirm .dialogBody .text01').html('你确定要从组织结构中删除员工 '+_name.text()+' 吗？');

		if(_checked.length == $('#part01 .table:first tbody tr').length){
			$("#novalueTable").show().prev("table").hide();
			$('#part01 .table:first tbody tr').show();
			alert("ddddd: "+ dG)
			if(dG==1){
				alert("eeeee: "+ dG)
				showDialog('弹窗_提醒_删除部门2.html');
			}
		}else {
			_checked.parent().parent().hide();
		}
		$(".tabToolBox").hide();
}*/
$(function(){
	$(".tabToolBar-right .selectGroup").click(function(e){
			//alert(3545)
		var _e = e || window.event;
		$("#allGroup2").toggle();
		//$("#allGroup2 .pop-box-content").jScrollPane();
		$("body").die("mousedown");
		$("body").live("mousedown", onSelectGroupDown);
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
function Group_right(event) {
    if (!($(event.target).hasClass("link_limitSet")|| event.target.id == "org_power" || $(event.target).parents("#org_power").length>0 ))
    {
        $("#org_power").hide();

        $("body").unbind("mousedown", onBodyDown);
    }
}
// JavaScript Document
