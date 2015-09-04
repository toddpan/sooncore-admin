// JavaScript Document
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