<dl class="dialogBox D_addOrg">
    <dt class="dialogHeader">
        <span class="title">
            编辑部门
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
        <a class="btnRed floatLeft" onclick="delOrg(this)">
            <span class="text">
                删除部门
            </span>
            <b class="bgR">
            </b>
        </a>
        <a class="btn" onclick="editSaveOrg(this)">
            <span class="text">
                修改
            </span>
            <b class="bgR">
            </b>
        </a>
        <a class="btn btn_cancel" onclick="hideDialog();">
            <span class="text">
                取消
            </span>
            <b class="bgR">
            </b>
        </a>
    </dd>
</dl>
<script type="text/javascript">
$(document).ready(function() {
    var selectNode = getSelectNode("#ztree");
    $(".infoTable #newOrgName").val(selectNode.name);
    var parentOrgName = $.trim($("#ztree a[org_id='" + selectNode.pid + "']").text());
    $("#inputVal2 #organizationId").val(parentOrgName);
    if (!$("#departmentTree").text()) {
        var oldTreeDOM = $("#ztree").html();
        $("#departmentTree").html(oldTreeDOM);
        $("#departmentTree a.curSelectedNode").remove();
        $("#departmentTree a[org_id='" + selectNode.pid + "']").addClass("curSelectedNode");
    }
});

function showTreeList(event) {
    $('#treeOption').toggle();
    if ($('.optionBox').attr('target') == '1') {
        $('.optionBox').attr('target', '0');
    }
    $("body").bind("mousedown", onTreeListDown);
}

function hideTreeList() {
    $("#treeOption").fadeOut("fast");
    $("body").unbind("mousedown", onTreeListDown);
}

function onTreeListDown(event) {
    if (!(event.target.className == "icon" || event.target.className == "text" || event.target.id == "treeOption" || $(event.target).parents("#treeOption").length > 0)) {
        hideTreeList();
    }
}

//编辑组织后提交
function editSaveOrg(t) {
    var _t = $(t);
    var newOrgName = $(".infoTable #newOrgName").val();
    if (!newOrgName) {
        $(".infoTable #newOrgName").parents(".inputBox").addClass("error");
        alert("部门名字不能为空");
        return false;
    }
    var obj = getSelectNode("#departmentTree");
    var obj1 = getSelectNode("#ztree");
    if (newOrgName == obj1.name && obj1.pid == obj.oid) { //如果新名和旧名一样 且 新的父ID和旧的父ID一样说明没有改动，直接停止事件
        //alert(999);
        hideDialog();
        return false;
    }
    var checkStatus = getChildrenNode(obj.oid, newOrgName);
    //alert(checkStatus);
    if (checkStatus > 0) {
        $(".infoTable #newOrgName").parents(".inputBox").addClass("error");
        alert("“" + newOrgName + "”这个名字已经存在于同级部门中，请换一个");
        return false;
    }
    if (!obj.name) {
        $(".infoTable #organizationId").parents(".inputBox").addClass("error");
        alert("请选择上级部门");
        return false;
    }
    if (_t.hasClass("false")) {
        return false;
    }
    _t.addClass("false");
    if (obj1.oid == obj.oid) {
        alert("上级部门不能为当前部门");
        return false;
    }
    var data = {
        'id': obj1.oid,
        'pId': obj.oid,
        'name': newOrgName
    };
    $.ajax({
        url: 'organize/saveNewOrg',
        async: false,
        type: "POST",
        data: data,
        success: function(data) {
            var json = $.parseJSON(data);
            if (json.code == 0) {
                //如果是修改成功
                location.reload(); //重新加载页面 原本设想为直接用JQUERY重构部门节点部分的代码，种种原因。。以后再优化
                //hideDialog();
                //_t.removeClass("false");
            } else {
                _t.removeClass("false");
                alert(json.prompt_text);
                //hideDialog();
            }
        }
    });
}




//删除组织
function delOrg(t) {
    var _this = $(t);
    if (_this.hasClass("disabled") || _this.hasClass("false")) {
        return;
    }
    _this.addClass("false");
    var node = getSelectNode("#ztree");
    if (node.oid != null) {
        var obj = {
            "id": node.oid,
            "pId": node.pid,
            "name": node.name,
            "is_sure_del": 0 //0去判断可以不可以删除[返回1\2\5]，1满足条件就可以真的删除[都可能返回]
        };
        $.post("organize/delOrg", obj, function(data) {
            //alert(data);
            var json = $.parseJSON(data);
            if (json.code == 0) {
                $("#ztree a.curSelectedNode").remove();
                $("#ztree a.nodeBtn[org_id='"+node.pid+"']").click();
                //alert("删除成功");
            } else {
                alert(json.prompt_text);
            }
            _this.removeClass('false');
            hideDialog();
        });
    }
}

</script>