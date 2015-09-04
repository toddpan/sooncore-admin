// JavaScript Document
var radioSetting = {
		check: {
			enable:true,
			chkStyle:"radio",
			radioType:"all"
		},
		view: {
			selectedMulti:true,
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
			beforeExpand:radiobeforeExpand,
		    onExpand:radio_onExpand,
			beforeClick: beforeClick,
			onCheck: radio_onCheck
		}
	};
function radiobeforeExpand(treeId,treeNode)
{
	//alert(1);
   var obj="ztree4";
	var zTree = $.fn.zTree.getZTreeObj(obj);
	//var treeParent=zTree.getNodes()[0].children;
	//var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function radio_onExpand(event,treeId,treeNode)
{
	
	var orgid=treeNode.id;
	var obj="ztree4";
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	//if()
	
	if(treeNode.children==null && treeNode.isParent)
	{
		
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
	             if(childNodes[i].isParent>0)
	               {  
	                 /*var node={id:1,pId:childNodes[i].id,name:'',nocheck:true};
	                 childNodes.push(node);*/
					 childNodes[i].nocheck=true
	               }
				   else
				   {
					   childNodes[i].nocheck=false;
				   }
	         }
		  childNodes=zTree.addNodes(treeNode,childNodes);
		  //zTree.removeNode(treeNode.children[0]);
		  //expand_node(childNodes);
		})
	}
	
}
function radio_onCheck(event,treeId,treeNode)
{
	$('#departmentSel2').val(treeNode.name);
	if(treeId=="ztree4")
	{
		$("#part1").removeClass("value_change");
		$("#part1").addClass("value_change");
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
	//var treeParent=zTree.getNodes()[0].children;
	//var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function input_onExpand(event,treeId,treeNode)
{
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