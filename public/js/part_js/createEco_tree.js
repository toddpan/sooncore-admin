// JavaScript Document
var companySetting = {
    check: {
        enable:true,
        chkStyle:"checkbox",
        chkboxType:{"Y":"","N":""}
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
        beforeExpand:companybeforeExpand,
        onExpand:company_onExpand,
        onCheck: oncompanyCheck
    }
};
function companybeforeExpand(treeId,treeNode)
{
    //alert(1);
    var obj="org_treeLeft";
    var zTree = $.fn.zTree.getZTreeObj(obj);
    //var treeParent=zTree.getNodes()[0];
    //var leng=treeParent.length;
   	if(treeNode.isDisabled==true)
    {
        return false;
	}
}
function add_staff_company(zTree,treeNode,path)
{
    if(treeNode.isParent && treeNode.children==null)
    {
        var obj1={
            "org_id":treeNode.id,
            "type":1
        };
       // $.post(path,obj1,function(data)
		$.ajax({
				url: path,
				async: false,
				type: "POST",
				data: obj1,
				success: function(data) {
            //alert(data)
            	var json=$.parseJSON(data);
           		if(json.code==0)
            		{
                    var node=json.other_msg.orgs;
                    var childNodes=eval('(' +node+ ')');
                    var leng=childNodes.length;
                    //alert(leng)
                    for(var i=0; i<leng;i++)
                    {
                        //cost_zNodes.push(childNodes[i]);
                            if(childNodes[i].userCount>0 || childNodes[i].identity==1)
                            {
                                childNodes[i].nocheck=false;
								if(childNodes[i].userCount>0)
								{
									childNodes[i].isParent=true;
								}
								
								if(treeNode.checked==true)
                                {
                                    zTree.checkNode(childNodes[i],true,true);
                                }
                            }
                    }
                    childNodes=zTree.addNodes(treeNode,childNodes);
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
										childNodes[i].isParent=true;
	             				 }
	        				 }
		  					childNodes=zTree.addNodes(treeNode,childNodes);
		  				 //zTree.removeNode(treeNode.children[0]);
						}
            	}
				else
					{
						alert(json.prompt_text);
					}
			}
        });

    }
}
function company_onExpand(event,treeId,treeNode)
{
    var orgid=treeNode.id;
    var obj="org_treeLeft";
    var zTree = $.fn.zTree.getZTreeObj(obj);
    add_staff_company(zTree,treeNode,cost_get_staff);
}
function oncompanyCheck(e,treeId,treeNode)
{
    var obj="org_treeLeft";
    var zTree = $.fn.zTree.getZTreeObj(obj);
    if(treeNode.identity!=1)
    {
        if(treeNode.userCount>0)
        {
            add_staff_company(zTree,treeNode,cost_get_staff);
			
        }
    }
    var childNodes=treeNode.children;
    if(childNodes!=null || childNodes!=undefined)
    {
        for(var i=0;i<childNodes.length;i++)
        {
            if(childNodes[i].identity==1 && treeNode.checked==true)
            {
				//alert(childNodes[i].name)
                zTree.checkNode(childNodes[i],true,true);
            }
			if(childNodes[i].identity!=1 && treeNode.checked==true)
            {
				//alert(childNodes[i].name)
                zTree.checkNode(childNodes[i],false,false);
            }
            if(childNodes[i].identity==1 && treeNode.checked==false)
            {
                zTree.checkNode(childNodes[i],false,false);
            }
        }
    }
    var parentNode=treeNode.getParentNode();
	var bortherNode=parentNode.children;
    if(parentNode!=null)
    {
       if(treeNode.checked==true && treeNode.identity==1)
         {
			 var count=0;
			 var num=0;
			 for(var i=0;i<bortherNode.length;i++)
			 {
				 if(bortherNode[i].identity==1)
				 {
					 num++;
					 if(bortherNode[i].checked==true)
					 {
						 count++;
					 }
				 }
			 }
			 if(count==num)
			 {
                zTree.checkNode(parentNode,true,true);
			 }
                //break;
         }
       if(treeNode.checked==false && treeNode.identity==1)
        {
            zTree.checkNode(parentNode,false,false);
        }
      // parentNode=parentNode.getParentNode();
    }
}
var adminOneSetting = {
	view: {
		selectedMulti:false,
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
		beforeExpand:adminone_beforeExpand,
		onExpand:adminone_onExpand,
		beforeClick:adminone_selectNode
	}
};
//是否禁用展开
function adminone_beforeExpand(treeId,treeNode)
{
	//alert(1);
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	var treeParent=zTree.getNodes()[0].children;
	var leng=treeParent.length;
	if(treeNode.isDisabled==true)
	{
		return false;
	}	
}
function adminone_onExpand(event,treeId,treeNode)
{
	var orgid=treeNode.id;
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	post_add_staff(treeNode,cost_get_staff,zTree,1);
}
function adminone_selectNode(treeId, treeNode, clickFlag)
{
	if(treeNode.identity!=1)
	{
		//alert("请选中一个员工进行添加");
		return false;
	}
	else
	{
		return true;
	}
}