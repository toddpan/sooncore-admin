
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
var weiduSetting = {
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
        onExpand:check_onExpand,
        onCheck: onadminCheck
    }
};
var wdSetting = {
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
        onExpand:check_onExpand,
        onCheck:onradioCheck
    }
};
function onradioCheck(e,treeId,treeNode)
{
    var zTree = $.fn.zTree.getZTreeObj(treeId);
    var Nodes=zTree.getCheckedNodes(true);
    //alert(1)
    if(treeId=="ztree_admin")
    {
        $('#departmentSel2').val(Nodes[0].name);
        var id_2 = Nodes[0].pId;
        var org_code ='-' + Nodes[0].id;
        var node;
        while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
            node = zTree.getNodesByParam('id', id_2, null)[0];
            id_2 = node.pId;
            org_code ='-' + node.id + org_code;
        }

        $('#ztree_admin').attr("ids",org_code);

    }
    else
    {
        $('#second_level input').val('');
        var html='';
        //alert(Nodes[0].name)
        $('#second_level input').val(Nodes[0].name);

    }
    if($('#'+treeId+'').parent().prev().prev().attr("id")=="departmentSel2")
    {
        var id_2 = Nodes[0].pId;
        var org_code ='-' + Nodes[0].id;
        var node;
        while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
            node = zTree.getNodesByParam('id', id_2, null)[0];
            id_2 = node.pId;
            org_code ='-' + node.id + org_code;
        }
        $('#'+treeId+'').attr("ids",org_code);

        $('#'+treeId+'').parent().prev().prev().val(Nodes[0].name);
    }
}
function check_onExpand(e,treeId,treeNode)
{
    //alert(2)
    var zTree = $.fn.zTree.getZTreeObj(treeId);
    var path = "organize/get_next_OrgList"; //要加载的每个组织结构
    if(treeNode.isParent && treeNode.children==null)
    {
        var obj1={
            "org_id":treeNode.id
        };
        // $.post(path,obj1,function(data)
        $.ajax({
            url: path,
            async: false,
            type: "POST",
            data: obj1,
            success: function(data) {
                //alert(data)

                var childNodes = eval('(' + data + ')');
                var leng=childNodes.length;
                //alert(leng)
                for(var i=0; i<leng;i++)
                {
                    //cost_zNodes.push(childNodes[i]);
                    if(!childNodes[i].isParent)
                    {
                        childNodes[i].nocheck=false;

                    }
                }
                childNodes=zTree.addNodes(treeNode,childNodes);

            }
        });

    }
}
// JavaScript Document




function onadminCheck(e,treeId,treeNode)
{

    var zTree = $.fn.zTree.getZTreeObj(treeId);
    var Nodes=zTree.getCheckedNodes(true);
    $('#first_level input').val('');
    var html='';
    for(var i=0;i<Nodes.length;i++)
    {
        html=html+Nodes[i].name+',';
    }
    var staff_tag_post = html;
    var lastIndex = staff_tag_post.lastIndexOf(',');
    if (lastIndex > -1) {
        staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
    }
    $('#first_level input').val(staff_tag_post);
    var ids='';
    for(var i=0;i<Nodes.length;i++)
    {

        var treeNode = Nodes[i];
        var id_2 = treeNode.pId;
        var org_code ='-' + treeNode.id;
        var node;
        while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
            node = zTree.getNodesByParam('id', id_2, null)[0];
            id_2 = node.pId;
            org_code ='-' + node.id + org_code;
            // value.push(node.name);
            // id_value.push(node.id);

        }
        ids=ids+org_code+',';

    }
    var ids_dem = ids;
    var lastIndex = ids_dem.lastIndexOf(',');
    if (lastIndex > -1) {
        ids_dem = ids_dem.substring(0, lastIndex) + ids_dem.substring(lastIndex + 1, ids_dem.length);
    }
    $('#'+treeId+'').attr("ids",ids_dem);
    $('#'+treeId+'').attr("context",staff_tag_post);
    if($('#'+treeId+'').parent().prev().prev().attr("id")=="departmentSel")
    {

        $('#'+treeId+'').parent().prev().prev().val(staff_tag_post);
    }
};