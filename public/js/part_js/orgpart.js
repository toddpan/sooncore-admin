// JavaScript Document


//显示添加员工
function addNewMember_one() {
    showDialog("staff/add_staff_page");
}
//显示添加部门
function addOrg() {
    showDialog("organize/add_org_page");
}
//点击设置为管理者
function showSetManager() {

        //alert(_name.text())
        showDialog("organize/set_manager");
}
//点击取消管理者身份
function showMoveManager() {
        showDialog('organize/unset_manager');
}
//点击部门权限
function toggleGroupLimit(t,e) {
        //alert(2222);
        if($(t).hasClass("false"))
        {
                return;
        }
        $(t).addClass("false");
        var _this=$(t);
        var obj = getSelectNode();
        var org_code = obj.nodeCode;
        if ( org_code != null && !$('.groupLimit h3').hasClass("setTitle")) {
           
            var obj = {
                "org_code": org_code
            };
            var path_power = "organize/get_org_power";
            $.post(path_power, obj, function(data) {
               alert(data);
		_this.removeClass("false");
                $('.groupLimit .toolBar2').after(data);
                var _e = e || window.event;
                $(".groupLimit").show();
                $("body").die("mousedown");
                $("body").live("mousedown", Group_right);
                _e.cancelBubble = true;
                _e.returnValue = false;
                $(".groupLimit .toolBar2").hide();
                return false;

            })
        } else {
            _this.removeClass("false");
            var _e = e || window.event;
            $("#org_power").toggle();
            $(".groupLimit .toolBar2").hide();
            $("body").die("mousedown");
            $("body").live("mousedown", Group_right);
            _e.cancelBubble = true;
            _e.returnValue = false;
            return false;


        }

        //checkbox();
}


//Longwei加的地方从这开始

//加载下一级节点
//event 当前事件
function onExpand(event) {
    var treeNode = $(event.target).parent(".nodeBtn");
    var orgid = treeNode.attr("org_id");
    var pName = treeNode.attr("title");//上一及部门名字
    var pLevel = treeNode.parent("li").attr("level");
    var obj;
    var j = 0;
    obj = {
        "org_id": orgid
    };

    $.ajax({
        url: "organize/get_next_OrgList",
        async: false,
        type: "POST",
        data: obj,
        success: function(data) {
            if (data != null) {
                var childNodes = $.parseJSON(data);
                var len = childNodes.length;
                if(len>0){
                    var addHtml = createTreeHtml(childNodes,pName,pLevel);
                    //zNodes.push(childNodes[i]);
                    //alert(addHtml);
                    $(event.target).parent(".nodeBtn").after(addHtml);
                }else{
                    alert("当前节点没有下级部门");
                }
            }else{
                alert("未获取到数据");
            }
        }

    });
}


//生成下级部门的HTML串
//obj 是一个JSON对象    parentName上级名字  parentLevel 上一层级
function createTreeHtml(obj,parentName,parentLevel){
    var nodes = obj;
    var icoClassName = '';
    var pLevel = parseInt(parentLevel);
    var k = 0;
    var html = '<ul id="ztree_1_ul" class="level'+pLevel+'" level="'+pLevel+'">';
    for(var i=0;i<nodes.length;i++){
        var node = nodes[i];
        if(node.childNodeCount>0){
            icoClassName = 'noline_close';
        }  else {
           icoClassName = 'noline_docu';
        }
        k=i+2;
        html += '<li id="ztree_'+(pLevel+1)+'" class="level'+(pLevel+1)+'" tabindex="0" level="'+(pLevel+1)+'">\n\
                    <a class="nodeBtn" org_id="'+node.id+'" parent_id="'+node.parentId+'" title="'+parentName+' &gt '+node.name +'" node_code="'+node.nodeCode+'">\n\
                        <span style="display: inline-block;width:'+(15*(pLevel+1))+'px"></span>\n\
                        <span class="button level1 switch '+icoClassName+'"></span>\n\
                        <span>'+node.name+'</span>\n\
                    </a>\n\
                </li>';
    }
    html += '</ul>';
    return html;
}


//点击组织页面的员工名字的事件
function staff_information1(t,user_id)
{
    if($(t).hasClass("false"))
    {
            return;
    }
    $(t).addClass("false");
    var _this=$(t);
    $('#part01 .link_limitSet').show();
    $('#part01 #part1').remove();
   // $('#part01 .tabToolBox').hide();
    $('#part01 .tabToolBar').hide();
    $('#part01 .table_org').hide();
    var path_staff_information='staff/modify_staff_page';
    var obj={
         "user_id":user_id
         };
    $.post(path_staff_information,obj,function(data)
        {
            $('#part01 .tabToolBar').eq(0).after(data);
            _this.removeClass("false");
        }
    );
}

//把当前组织节点显示到页面右侧上方 并加载被添加的节点员工列表 treeNode = obj {};
function showValue(treeNode){
    if (treeNode.oid) {
        var staff_depart = '<span>' + treeNode.title +'</span>';
        $('#orgNode .bread').text('');
        $('#orgNode .bread').html(staff_depart);
        $('#part01 .groupLimit .toolBar2').next().remove();
        //alert(treeNode.name)
        
        
        
        var org_ID = treeNode.oid; //获得当前组织id
        var parent_orgid = treeNode.pid;
        var obj = {
            "parent_orgid": parent_orgid,
            "org_id": org_ID
        };
        
        var path_user = "organize/get_users_list"; //加载的员工列表
        load_staff(obj, path_user);
    }
}

//添加功能
function addFunction(){
    $("#addMoreBox").slideToggle();
    $("#addFunction").toggleClass("hover");
}