// JavaScript Document
//是否禁用节点
    //禁用选中状态
function disable_select() {
        var zTree = $.fn.zTree.getZTreeObj("ztree");
        var treeNode = zTree.getSelectedNodes();
        if (treeNode[0] != null) {

            if (treeNode[0].isDisabled == true) {
                zTree.cancelSelectedNode(treeNode[0]);
                $('#addZuzhi').addClass("disabled");
                $('#deleteZuzhi').addClass("disabled");
            }
        }
    }
//是否可以添加组织
function disable_add() {
        var zTree = $.fn.zTree.getZTreeObj("ztree");
        var treeNode = zTree.getSelectedNodes();
        if (treeNode[0] != null) {
            if (treeNode[0].isaddnext == false || treeNode[0].isDisabled == true) {
                $('#addZuzhi').addClass("disabled");
            }
        }
    }
//是否可以删除组织
function disable_del() {
    var zTree = $.fn.zTree.getZTreeObj("ztree");
    var treeNode = zTree.getSelectedNodes();
    if (treeNode[0] != null) {
        if (treeNode[0].isdel == false || treeNode[0].isDisabled == true) {
            $('#deleteZuzhi').addClass("disabled");
        }
    }
}
//初始化组织结构树
function InitzTree() 
{
        //zNodes = <?php echo $org_list_json ;?>;
		var tree_path="organize/get_org_tree";
		$.post(tree_path,[],function(data)
		{
			if(data.code==0)
			{
			zNodes=$.parseJSON(data.prompt_text);
			//alert(zNodes)
			//zNodes=data.prompt_text;
			var leng = zNodes.length;
			//create_node(zNodes);
			$.fn.zTree.init($("#ztree"),setting,zNodes);
			//$.fn.zTree.init($("#ztree2"), Alldepartsetting, zNodes);
			$("#addZuzhi").bind("click", {isParent: true}, addZuzhi); //添加一个节点
			//$("#deleteZuzhi").bind("click", deleteZuzhi);//删除一个节点
			var zTree = $.fn.zTree.getZTreeObj("ztree");
			var nodes = zTree.getNodes();
			//alert(nodes[0])
			zTree.selectNode(nodes[0]);
			$('#deleteZuzhi').addClass("disabled");
			//$('.ztree li a').trigger('click');
	
			var org_ID = nodes[0].id; //获得当前组织id
			var parent_orgid = nodes[0].pId;
			//alert(org_ID);
			//alert(parent_orgid)
			var nodes = zTree.getSelectedNodes();
			if (nodes[0] != null) {
				var value = [];
				value.push(nodes[0].name);
				while (nodes[0].pId != null) {
					nodes  =  zTree.getNodesByParam("id", nodes[0].pId, null);
					value.push(nodes[0].name);
				}
				var staff_depart = "";
				//staff_depart=' <div class="bread part0">';
				for (var i = value.length - 1; i > 0; i--) {
					staff_depart = staff_depart +'<span>' + value[i] +'</span>&nbsp;&gt;&nbsp';
				}
				staff_depart = staff_depart +'<span>' + value[i] +'</span>';
				//staff_depart=staff_depart+"</div>";
				// $('.link_limitSet').after(staff_depart);
				//alert(2324);

				$('#part01').children("div.bread").text('').append(staff_depart).addClass("part0");
			}
			var obj = {
				"parent_orgid": parent_orgid,
				"org_id": org_ID
			};
			load_staff(obj, path_user, path_mag);
			}
			else
			{
				alert(data.prmopt_text)
				return false;
			}
		},"json")
}
function addNewMember_one() {
    showDialog("staff/add_staff_page");
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
        //alert(2222)
		if($(t).hasClass("false"))
		{
			return;
		}
		$(t).addClass("false");
		var _this=$(t);
        var zTree = $.fn.zTree.getZTreeObj("ztree");
        var nodes = zTree.getSelectedNodes();
        var treeNode = nodes[0];
        var id_2 = treeNode.pId;
        var org_code ='-' + treeNode.id;
        var node;
        while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
            node = zTree.getNodesByParam('id', id_2, null)[0];
            id_2 = node.pId;
            org_code ='-' + node.id + org_code;
        }
        if (treeNode != null && !$('.groupLimit h3').hasClass("setTitle")) {
            var obj = {
                "org_code": org_code
            };
            var path_power = "organize/get_org_power";
            $.post(path_power, obj, function(data) {
                //alert(data);
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