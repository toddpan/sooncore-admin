<dl class="dialogBox D_addOrg">
    <dt class="dialogHeader">
        <span class="title">
            添加部门
        </span>
        <a class="close" onclick="hideDialog();">
        </a>
    </dt>
    <dd class="dialogBody" style="overflow: inherit">
        <table class="infoTable">
            <tbody>
                <tr>
                    <td class="tr">
                        部门名称：
                    </td>
                    <td colspan="3">
                        <div class="inputBox">
                            <label class="label">
                            </label>
                            <input class="input" id="newOrgName" value="" style="width: 200px">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="tr">
                        上级部门：
                    </td>
                    <td>
                        <div class="inputBox selectInput" id="inputVal2" style="z-index:2">
                            <a class="icon" onclick="showTreeList(event)">
                            </a>
                            <label class="label">
                            </label>
                            <input class="input" id="organizationId" value="" style="width: 180px"
                            readonly="readonly" onclick="showTreeList(event)">
                            <div id="treeOption" style="z-index: 9; display: none;">
                                <ul class="ztree" id="departmentTree"></ul>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </dd>
    <dd class="dialogBottom">
        <a class="btnBlue" onclick="saveOrg(this)">
            <span class="text">
                添加
            </span>
            <b class="bgR">
            </b>
        </a>
        <a class="btnGray btn btn_cancel" onclick="hideDialog();">
            <span class="text">
                取消
            </span>
            <b class="bgR">
            </b>
        </a>
    </dd>
</dl>
<script type="text/javascript">
    //var selectNode = getSelectNode("#ztree");
    //$("#inputVal2 #organizationId").val(selectNode.name); 
    
    function showTreeList(event) {
        if(!$("#departmentTree").text()){
            var oldTreeDOM = $("#ztree").html();
            $("#departmentTree").html(oldTreeDOM);
            //$("#departmentTree a.nodeBtn").removeClass("curSelectedNode");
        }
        $('#treeOption').toggle();
        $("body").bind("mousedown", onTreeListDown);
    }
    
    function hideTreeList() {
        $("#treeOption").fadeOut("fast");
        $("body").unbind("mousedown", onTreeListDown);
    }
    
    function onTreeListDown(event) {
        if (! (event.target.className == "icon" || event.target.className == "text" || event.target.id == "treeOption" || $(event.target).parents("#treeOption").length > 0)) {
            hideTreeList();
        }
    }




//添加保存新的组织
function saveOrg(t){
    var _t = $(t);
    var newOrgName = $(".infoTable #newOrgName").val();
    if(!newOrgName){
        $(".infoTable #newOrgName").parents(".inputBox").addClass("error");
        alert("新部门名字不能为空！");
        return false;
    }
    
    var obj = getSelectNode("#departmentTree");
    
    var checkStatus = getChildrenNode(obj.oid , newOrgName);
    //alert(checkStatus);
    if(checkStatus>0){
        $(".infoTable #newOrgName").parents(".inputBox").addClass("error");
        alert("部门名字已经存在了");
        return false;
    }
    if(!obj.name){
        $(".infoTable #organizationId").parents(".inputBox").addClass("error");
        alert("请选择上级部门");
        return false;
    }
    if(_t.hasClass("false")){
        return false;
    }
    _t.addClass("false");
    var data = {
            'id':0,
            'pId':obj.oid,
            'name':newOrgName
            };
    $.ajax({
        url: 'organize/saveNewOrg',
        async: false,
        type: "POST",
        data: data,
        success: function(data) {
            var json = $.parseJSON(data);
            if (json.code == 0) {
                //如果是新加成功
                var newDom = $("#departmentTree").html();
                $("#ztree").html(newDom);//把现有展开的部门节点复制到左边部门列表
                hideDialog();
                
                var newTree = $("#ztree a.curSelectedNode");
                
                var level = Number(newTree.parent("li").attr("level"))+1;
                
                var addStr = '<li class="level'+level+'" tabindex="0" level="'+level+'">'+
                                '<a class="nodeBtn" org_id="'+json.other_msg.org_id+'" parent_id="'+obj.oid+'" title="'+obj.title+' > '+newOrgName+'" node_code="'+obj.nodeCode+'-'+json.other_msg.org_id+'">'+
                                    '<span style="margin-left:'+(level*15)+'px" class="button level1 switch noline_docu"></span>'+
                                    '<span>'+newOrgName+'</span>'+
                                '</a>'+
                            '</li>';
                
                var pNodeStatus = newTree.children("span.button");
                
                if(pNodeStatus.hasClass("noline_docu")){//noline_docu 无下级节点；noline_close 有下级节点 未展开；noline_open 有下级节点已展开
                    //alert("noline_docu");
                    
                    addStr = '<ul class="level'+(level-1)+'" level="'+(level-1)+'">'+addStr+'</ul>';
                    newTree.after(addStr);
                    newTree.children("span.button").removeClass("noline_docu").addClass("noline_open");
                    
                }else if(pNodeStatus.hasClass("noline_close")){
                   //$("#ztree a.curSelectedNode>span.button").click();
                    
                }else if(pNodeStatus.hasClass("noline_open")){
                    
                    //alert("noline_open");
                    newTree.next("ul").append(addStr);
                }
                
                //load_staff(obj,path_user);
                _t.removeClass("false");

            } else {
                _t.removeClass("false");

                alert(json.prompt_text);
                //hideDialog();
            }

        }
    });
}




</script>