

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
    $("#part01 table:first tr").show();
    $("#part01 table:first td").find("input:checked").click();
    $("#novalueTable").hide().prev("table").show();
    $("#novalueTable").next(".page").show();

}




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
// JavaScript Document
