// JavaScript Document
function Initadmin()//初始化管理员结构树
{
	var obj={
	"id":0};
	$.ajax({
			   url:adminpath,
			   async:false,
			   type:"POST",
			   data:obj,
			   dataType:"json",
			   success:function(data)	   
		        {
					//alert(1)
					//alert(data)
					 admin_Nodes=data.other_msg;
				}
	});
    var leng=admin_Nodes.length;
    $.fn.zTree.init($("#adminTree"), adminSetting, admin_Nodes);
    var zTree = $.fn.zTree.getZTreeObj("adminTree");
   	var nodes =zTree.getNodes();
    zTree.selectNode(nodes[0]);
	
	var treeNode = zTree.getSelectedNodes();
	//alert(treeNode[0].id)
	var obj={
		"id":treeNode[0].id
	};
	//alert(treeNode[0].id)
	var path_first="ecology/ecologyList";
	$.post(path_first,obj,function(data)
	{
	//alert(data)
		$('#part02').find(".table").remove();
		$('#part02 .tabToolBar').after(data);
	})
}
//点击管理员下面的删除按钮
function deleteStAdmin(t){
	if($(t).hasClass("disabled")){
		return false;	
	}
	else {
		var zTree = $.fn.zTree.getZTreeObj("adminTree"),
			//isParent = e.data.isParent,
		nodes = zTree.getSelectedNodes();
		treeNode = nodes[0];
		//alert(treeNode.id)
		showDialog("ecology/showDelEcologyManagerPage");
		//alert(11)
		$('#dialog .confirm_btn').die('click');
		$('#dialog .confirm_btn').live('click',function()
		{
			//zTree.removeNode(treeNode, true);
			var obj=
			{
				"id":treeNode.id
			}
			var path = "ecology/delEcologymanager";
			$.post(path,obj,function(data)
			{
			  // alert(data);
				var json=$.parseJSON(data);
				if(json.code==0)
				{
					if(treeNode.childNodeCount>0)
					{
						//alert(111)
//						var child=treeNode.children;
						var parent=treeNode.getParentNode();
						zTree.removeChildNodes(parent);
						//zTree.updateNode(parent);
//						var next=treeNode.getNextNode();
//						if(child[0].name=="")
//						{
							var obj={
								"id":parent.id
						};
//								//$.post(path,obj,function(data)
								$.ajax({
									url:adminpath,
									async:false,
									type:"POST",
									data:obj,
									dataType:"json",
									success:function(data)
									{
										var childNodes=data.other_msg;
										//var childNodes = eval('(' +data + ')');
										//alert(childNodes)
										var leng=childNodes.length;
										create_node(childNodes);
										zTree.addNodes(parent,childNodes);
										hideDialog();
										// zTree.removeNode(parent.chilidren, true);
										 
//										var id=childNode.id;
//										var nodes = zTree.getNodesByParam("id", id, null);
//										zTree.moveNode(treeNode,nodes,"next");
//										/*var p_child=parent.children;
//										for(var i=p_child.length-childNodes.length;i<p_child.length;i++)
//											{
//												zTree.moveNode(next,p_child[i],"prev");
//												//next=
//											
//											}	*/
//										zTree.removeNode(treeNode, true);
//										hideDialog();
//									// alert(childNodes)
//									//childNodes=zTree.addNodes(treeNode,childNodes);
//									//zTree.removeNode(treeNode.children[0]);
//									//var p_child=parent.children;
//									//zTree.moveNode(next,p_child[p_child.length-1],"prev");
									}
							})
						
				   }
				   else
				   {
				   	  zTree.removeNode(treeNode, true);
					   hideDialog();
				   }
				}
			})
		})
		
	}
}
//添加管理员
function addStAdmin() {
	//alert(213)
	var zTree = $.fn.zTree.getZTreeObj("adminTree"),
	//isParent = e.data.isParent,
	nodes = zTree.getSelectedNodes();
	treeNode = nodes[0];
	
	$("#dialog .treeRight a").each(function(index, element) {
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
function admin_stqy_name(zTree,user_id)
{
	var common=zTree.getNodesByParam("id", user_id, null);
	if(common[0]!=undefined)
	{
		$('.conTabsHead li:eq(0)').trigger("click");
		zTree.selectNode(common[0]);
		$('#stqyTree').find("a.curSelectedNode").trigger("click");
	}
	else
	{
		var path;
		var obj=
		{
			"id":user_id
		}
		$.post(path,obj,function(data)
		 {
			 for(var i=0;i<data.length;i++)
			 {
				 var search_id=zTree.getNodesByParam("id",data[i].id, null);
				 if(search_id[0]==undefined)
				 {
					 var search_pId=zTree.getNodesByParam("id",data[i].pId, null);
					 zTree.addNodes(search_pId,data[i]);
				 }
			 }
		 },'json')
		var find_node=zTree.getNodesByParam("id", user_id, null);
		$('.conTabsHead li:eq(0)').trigger("click");
		zTree.selectNode(find_node[0]);
		$('#stqyTree').find("a.curSelectedNode").trigger("click");
	}
}
$(function()
{
	//管理员
		$('.conTabsHead li:last').click(function(){
			if(!$(this).hasClass("second"))
			{
			 $(this).addClass("second");
			 Initadmin();//初始化管理员
			}
		});
		
		//生态企业名称
		$('#part02 table tbody td:eq(1) a').click(function()
		{
			//$('.conTabs li').removeClass("selected");
			//$('.conTabs li:eq(0)').addClass("selected");
			var zTree = $.fn.zTree.getZTreeObj("stqyTree");
			var user_id=$(this).attr("user");
			admin_stqy_name(zTree,user_id)
		})
			//点击上级生态企业名称
		$('#part02 table tbody td:eq(4) a').click(function()
		{
			//$('.conTabs li').removeClass("selected");
			//$('.conTabs li:eq(0)').addClass("selected");
			var zTree = $.fn.zTree.getZTreeObj("stqyTree");
			var user_id=$(this).attr("user");
			admin_stqy_name(zTree,user_id)
			/*var t=
			company_apart_information(this);*/
		})
		//点击新建生态企业
		$('#part02 #admin_create_stqy').click(function()
		{
			  var admintree = $.fn.zTree.getZTreeObj("adminTree");
	          var nodes1 = admintree.getSelectedNodes();
	          var treeNode1 = nodes1[0];
	          var admin_id="";
	          if(treeNode1!=null)
	          {
	             admin_id=treeNode1.id;

	              // alert(orgid1)
	          }
	          id_2=treeNode1.pId;
	          var admin_code1='-'+treeNode1.id;
	          while(dgtree.getNodesByParam('id',id_2,null)[0]!=null)
	          {
	              node=admintree.getNodesByParam('id',id_2,null)[0];
	              id_2=node.pId;
	              admin_code1='-'+node.id+admin_code1;
	          }
			  var obj={
		               "admin_id":admin_id,
		              "admin_code":admin_code1
		          };
			  var path="ecology/showEcology";
			   //loadPage('<?php echo site_url('ecologycompany/createEcologyCompany')?>' + '/' + orgid ,'company');
			   $.post(path,obj,function(data)
			   {
			   		$('.new_ecology').remove();
					$('.init_stqy_page').hide();
			   		$('.init_stqy_page').after(data);
					
			   });
		})
		//企业负责人
		$('#part02 table tbody td:eq(3) a').click(function()
		{
			var path_staff_information='manager/managerInfoPage';
			var obj={
				"user_id":$(this).attr("name")
			}
			//alert($(this).name);
			$.post(path_staff_information,obj,function(data)
			{
				
				$('#part02 .page').after(data);
				$('#part02 .tabToolBar').hide();
				$('#part02 .table').remove();
				$('#part02 .page').hide();
			})
		})
		//生态管理员
		$('#part02 table tbody td:eq(5) a').click(function()
		{
			var path_staff_information='manager/managerInfoPage';
			var obj={
				"user_id":$(this).attr("name")
			}
			//alert($(this).name);
			$.post(path_staff_information,obj,function(data)
			{
				//alert(data)
				//$('#part01 div.bread').after(data);
				$('#part02 .page').after(data);
				$('#part02 .tabToolBar').hide();
				$('#part02 .table').remove();
				$('#part02 .page').hide();
				
				
			})
		})
})