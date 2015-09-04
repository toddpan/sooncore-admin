// JavaScript Document
//初始化成本中心
function Initcostcenter(Nodes) //初始化组织结构树
{
        //zNodes = <?php echo $org_list_json ;?>;
        var leng = Nodes.length;
		//var newNode;
       // create_node(Nodes);
        $.fn.zTree.init($("#ztreecostcenter"),setting, Nodes);
        //$.fn.zTree.init($("#ztree2"), Alldepartsetting, zNodes);
		$("#add_cost").bind("click", {isParent:true}, addCost);//添加一个节点
}
//删除成本中心
function showDeleteCenter(t) {
    if ($(t).hasClass("disabled")) {
        return false;
    }
	showDialog('costcenter/del_costcenter');
}
$(function()
{
	//成本中心
    //成本中心层级展示costcenter
    $('.conTabsHead li:last').click(function() {
		if($(this).hasClass("false"))
		{
			return;
		}
		$(this).addClass("false");
		var _this=$(this);
        if ($(this).attr("target") == 1) {
			var obj=
			{
				"id":0
			};
            $.post(cost_path, obj, function(data) {
				if(data.code==0)
				{
					costNode=data.data;
					Initcostcenter(data.data);
					$('.treeNode').trigger("click");
					_this.removeClass("false");
				}
                //$('#centerTree') .append(data);
            },"json");
           $(this).attr('target','2');
        }

    });
	//点击未指定成本中心
	$('.treeNode').click(function()
	{
		var zTree = $.fn.zTree.getZTreeObj("ztreecostcenter");
        var nodes = zTree.getSelectedNodes();
		zTree.cancelSelectedNode(nodes[0]);
		$(this).addClass("no_setCost");
		var staff_depart;
		staff_depart="<span>成本中心</span>&nbsp;&gt;&nbsp<span>未指定成本中心</span>";
		$('#part02 .bread').html('');
		$("#part02 .bread").append(staff_depart);
		$(".deleteCenter").addClass("disabled");
		var org_ID = 0;
		var Tree=$.fn.zTree.getZTreeObj("ztree2");
		if(Tree==null)
		{
			var org=0;
		}else
		{
			var org=Tree.getSelectedNodes();
			if(org[0]==null)
			  {
				  org=0;
			  }
			else
			  {
				  org=org[0].id;
			  }
		}
		var obj = {
			  "id":org_ID,
			  "org_id":org,
			  "count":0,
			  "page":0
			};
		load_staff_center(obj, path_cost_user);
	})
    $('#part02 table td a.btnOn').die("click");
    //关闭员工账号
    //$(document).on("click",'#part01 table td a.btnOn',function()
    $('#part02 table td a.btnOn').live('click', function() {

        var zTree = $.fn.zTree.getZTreeObj("ztree2");
        var nodes = zTree.getSelectedNodes();
        var treeNode = nodes[0];
        if (treeNode != null) {

            var org_ID = treeNode.id;
        } else {
            var org_ID = 0;
        }
        var cost_id;
        $('#centerTree li').each(function() {

            if ($(this).hasClass("selected")) {
                cost_id = $(this).find("span:first").attr("target");

            }
        });
        if (cost_id == null) {
            cost_id = 0;
        }
        var user_id = $(this).parents("tr").find("td:first").find("input").val(); //
        var staff_account = {
            //"orgid":org_ID,
            //"cost_id":cost_id,
            "type_id": 3,
            "user_id": user_id
        };
        //开通状态，点关闭按钮，关闭帐号操作					alert(user_id) 
        showDialog("staff/closeAccount");
        var _checked = $(this);
        count_name = _checked.parent().parent().find('.userName').text();


        var _this = $(this);
        $("#dialog .dialogBottom #closeAccount").die("click");
        $("#dialog .dialogBottom #closeAccount").live("click", function() {
            var path_setmanager ='staff/close_user';
            // alert( staff_account.user_id)
            $.post(path_setmanager, staff_account, function(data) {
                //alert(data); 

                var json = $.parseJSON(data);

                if (json.code == 0) {
                    _this.removeClass('btnOn').addClass('btnOff');
                    hideDialog();

                } else {}
            });
            //_this.removeClass('btnOn').addClass('btnOff');
            //hideDialog();
        });

    });
    $('#part02 table td a.btnOff').die("click");
    //开通账号
    //$(document).on("click",'#part01 table td a.btnOff',function()
    $('#part02 table td a.btnOff').live('click', function() {
        //alert(564657567)
        var zTree = $.fn.zTree.getZTreeObj("ztree2");
        var nodes = zTree.getSelectedNodes();
        var treeNode = nodes[0];
        if (treeNode != null) {
            var org_ID = treeNode.id;
        } else {
            var org_ID = 0;
        }
        var cost_id;
        $('#centerTree li').each(function() {

            if ($(this).hasClass("selected")) {
                cost_id = $(this).find("span:first").attr("target");

            }
        });
        if (cost_id == null) {
            cost_id = 0;
        }
        var user_id = $(this).parents("tr").find("td:first").find("input").val(); //
        var staff_account = {
            //"orgid":org_ID,
            //"cost_id":cost_id,
            "type_id": 3,
            "user_id": user_id
        };
        //alert(user_id) 
        var _this = $(this);
        var path_Off ='staff/open_user';
        $.post(path_Off, staff_account, function(data) {
            //alert(data);
            var json = $.parseJSON(data);
            if (json.code == 0) {
                //alert(444);
                _this.removeClass('btnOff').addClass("btnOn");
            }
        })

    });
	 $(".tabToolBar-right .selectGroup").click(function(e) {
	 	//create_node(zNodes);
        $.fn.zTree.init($("#ztree2"), Alldepartsetting, zNodes);
        var _e = e || window.event;
        //$("#allGroup2").toggle();
		$("#allGroup2").show();
        //$("#allGroup2 .pop-box-content").jScrollPane();
        $("body").die("mousedown");
        $("body").live("mousedown", onSelectGroupDown);
        _e.cancelBubble = true;
        _e.returnValue = false;
        return false;
        //alert(29877);
        //$("#ztree2").addClass("small_group");
        //$("#allGroup2").toggle();
        //$("#allGroup2").jScrollPane();
        //$("body").bind("mousedown", onSelectGroupDown);
        //event.stopPropagation();
    });
	$('.tabToolBar-right .right_btn').click(function() {
		//alert(1111)
        showDialog('organize/foldStaff');
		page=2;
        //$('#allGroup2').show();
    });

})