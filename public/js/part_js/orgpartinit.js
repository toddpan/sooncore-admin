// JavaScript Document
$(function()
{
    //得到组织结构部门的员工列表
   $("#ztree .nodeBtn").live("click",function(){
       //alert($(this).attr("title"));
       
       if(!$(event.target).hasClass("button")){ //排除节点前三角箭头的点击事件
            $(".curSelectedNode").removeClass("curSelectedNode");
            $(this).addClass("curSelectedNode");
            $(".tabToolBox").css("display","none");
            var obj = getSelectNode();
            
            var addStaffBtn = $("#addMoreBox a.addUser");
            var editOrgBtn = $("#orgNode .editBtn");
            if(obj.pid!=0){
                addStaffBtn.removeClass("false");
                editOrgBtn.removeAttr("style");
            }else{
                addStaffBtn.addClass("false");
                editOrgBtn.css({'display':'none'});
            }

            var obj2 = {
                org_id : obj.oid,
                parent_id : obj.pid
            };
            load_staff(obj2, "organize/get_users_list");
            var orgNameStr = '<span>'+$(this).attr("title")+'</span>';
            $('#orgNode .bread').html(orgNameStr);
       
       }
       
   });
   
   //点击三角箭头加载下一级节点
   $("a.nodeBtn>.button").live("click",function(){
       //alert($(event.target).hasClass("button"));
       var status = $(this).hasClass("noline_close");
       var childrenNode = $(this).parent(".nodeBtn").next("ul");//获得子部门
       childrenNode.slideToggle();
       if(status){//判断节点是否展开
           $(this).removeClass("noline_close").addClass("noline_open");
           //alert(childrenNode.length);
           
           if(childrenNode.length===0){//如果子部门为空则加载
                onExpand(event);
            }
            
       }else{
           $(this).removeClass("noline_open").addClass("noline_close");
       }
   });
   
   
   //添加员工的 选择部门事件
   $("#departmentTree .nodeBtn").live("click",function(){
       var _this = $(event.target);
        if(!_this.hasClass("button")){ //排除节点前 三角箭头的点击事件
            $("#departmentTree .curSelectedNode").removeClass("curSelectedNode");
            $(this).addClass("curSelectedNode");
            $("#inputVal2").removeClass("error");
            //var selectNodeName = _this.attr("title").split(" > ");
            $("#inputVal2").find("input").val($.trim($(this).text()));
            $("#treeOption").hide();//隐藏浮出的层
       }
       
   });
   
  
   
    //点击员工调岗
    // $(".btnChangeUser_O").die("click");
    $(".btnChangeUser_O").click(function() {
        if($(this).hasClass("false"))
        {
                return;
        }
        $(this).addClass("false");
        var _this=$(this);
        var user_id ='';
        var treeNode = getSelectNode();
        if (treeNode.oid == null) {
            alert("未能获取到部门信息");
            return false;
        }
        var id_2 = treeNode.pid;
        var orgid = treeNode.oid;
        
        $('#part01 table tbody label').each(function() {
            if ($(this).hasClass("checked")) {
                var value = $(this).find('input').val();
				//alert(value)
                var name = $(this).parent().next().find("a").text();
                user_id = user_id +'{"userid":' + value +',"user_name":"' + name +'"},';
            }
        });
        //alert(user_id);
        var lastIndex = user_id.lastIndexOf(',');
        if (lastIndex > -1) {
            user_id = user_id.substring(0, lastIndex) + user_id.substring(lastIndex + 1, user_id.length);
        }
        //alert(user_id)
        user_id ='[' + user_id +']';
		
        //返回code是否成功，如果成功：重新加载当前组织帐号列表。
        showDialog("staff/moveStaff");
		_this.removeClass("false");
        $('#dialog .dialogBottom #move_staff_part').die('click');
        $('#dialog .dialogBottom #move_staff_part').live('click', function() {
            /*if($(this).hasClass("false"))
            {
                    return;
            }
            $(this).addClass("false");*/
            var _t=$(this);
            var dgtree = $("#dgmoveorg a.curSelectedNode");
            var new_org_id = dgtree.attr("org_id");
            var new_title = $.trim(dgtree.text());
            if (new_org_id != null) {
                var neworgid = new_org_id;
                // alert(orgid1)
            } else {
                alert("请选择要调入的部门！");
                return false;
            }
            if(new_org_id == orgid ){
                alert("新部门不能为当前所在部门");
                return false;
            }
            var orgname = treeNode.name;
            var neworgname = new_title;
            var staff = {
                "orgid": orgid,
                "orgname": orgname, //老的部门名称//
                "user_id": user_id,
               'neworgid': neworgid,
                "neworgname": neworgname //新的部门名称
            };
            //alert(parent_orgid)
            var path_staff ="staff/save_move_staff";
            $.post(path_staff, staff, function(data) {
               // alert(data);
                var json = $.parseJSON(data);

                if (json.code == 0) {
                    //重新加载当前组织帐号列表
			org_del_staff();
                  /*  var obj = {
                        "parent_orgid": parent_orgid,
                        "org_id": orgid
                    };
                    load_staff(obj, path_user, path_mag);*/
                    hideDialog();
                } else {
                    alert(json.prmopt_text);
                    hideDialog();
		}
		_t.removeClass("false");
			
            });
        });

    });
    //选中员工，点击删除员工
    $('.btnDeleUser').click(function() {
		//alert(1)
		if($(this).hasClass("false"))
		{
			return;
		}
		$(this).addClass("false");
		var _this=$(this);
        showDialog("staff/deleteStaff");
		_this.removeClass("false");
	$('#dialog .dialogBottom  #deleteStaff').die('click');
        $('#dialog .dialogBottom  #deleteStaff').live('click', function() {
			/*if($(this).hasClass("false"))
			{
				return;
			}
			$(this).addClass("false");*/
			var _t=$(this);
			var user_id='';
                        //alert(user_id.length)
	   		var  treeNode = getSelectNode("#ztree");
			if(treeNode.oid!=null)
                        {
                         var orgid=treeNode.oid;
                         var parent_orgid=treeNode.pid;
                        }
    	 $('#part01 table tbody label').each(function()
		 {
	   //alert(3);
	  		 if($(this).hasClass("checked"))
	  		 {
	    		var value=$(this).find('input').val();
	      		user_id=user_id +value+',';
	  		 }
		}) 
	 	 var lastIndex =user_id.lastIndexOf(',');
      	 if (lastIndex > -1) {
            user_id = user_id.substring(0,lastIndex) + user_id.substring(lastIndex + 1,user_id.length);
           }
	 	var staff={
	 	//"parent_orgid":parent_orgid,
		//"orgid": orgid,
       // "org_code":org_code,
		"user_id":user_id
		 };
		 //alert(parent_orgid)
		 var All_delete=0;
		 if(_checked.length == $('#part01 .table:first tbody tr').length){
			//$("#novalueTable").show().prev("table").hide();
			//$('#part01 .table:first tbody tr').show();
			//alert("ddddd: "+ dG)
			All_delete=1;
			/*if(dG==1){
				//alert("eeeee: "+ dG)
				
			}*/
		}
			//_checked.parent().parent().hide();
		var path_delete_staff ="staff/save_delete_staff";
		$.post(path_delete_staff,staff,function(data)
  		{
	 			//alert(data);
			 	var json=$.parseJSON(data);
				if(json.code==0)
					{
						/*var obj={
						"parent_orgid":parent_orgid,
						"org_id":orgid
							}
							load_staff(obj,path_user,path_mag);*/
							org_del_staff();
							hideDialog();
							if(All_delete==1 && dG==1)
							{
							   showDialog("organize/sure_del_org2");
							}
					 }else
						{
							
							alert(json.prmopt_text);
									
							hideDialog();
						}
			
			_t.removeClass("false");
		});
		
		 //返回code是否成功，如果成功：重新加载当前组织帐号列表。
	});
 });
   

    //删除组织
    $('#deleteZuzhi').click(function() {
        // alert(43324)
		if($(this).hasClass("disabled") || $(this).hasClass("false"))
		{
			return;
		}
		$(this).addClass("false");
        var zTree = $.fn.zTree.getZTreeObj("ztree");
        nodes = zTree.getSelectedNodes();
        treeNode = nodes[0];
        if (treeNode != null) {
            var treenode = {
                "id": treeNode.id,
                "pId": treeNode.pId,
                "name": treeNode.name,
                "is_sure_del": 0 //0去判断可以不可以删除[返回1\2\5]，1满足条件就可以真的删除[都可能返回]
            };
			var _this=$(this);
            //alert(treenode.id)
            //alert(treenode.pId)
            // alert(treenode.name)
            //删除组织：判断是否有1下级组织，2是否自己有员工3成功删除5当前组织可以进行删除4 删除失败
            var path_2 ="organize/delOrg";
            $.post(path_2, treenode, function(data) {
                //alert(data);
                var json = $.parseJSON(data);
                if (json.code == 0) {

                    if (json.other_msg.state == 1) {
                        dG = 1;

                        $(".poptip3").fadeIn();
                        //alert(dG)
                    } else if (json.other_msg.state == 2) {
                        dG = 1;
                        $(".poptip3").fadeIn();
                    } else if (json.other_msg.state == 5) {
                        //$(".poptip3").fadeIn();

                        showDialog("organize/sure_del_org");
                    } else {
                        alert("删除失败");
						_this.removeClass('false');
                    }
					
                }
				else
				{
					alert("操作失败")
					_this.removeClass('false');
				}
				
            })
        }
    });
    //alert(2)
    $('#part01 table td a.btnOn').die("click");
    //关闭员工账号
    //$(document).on("click",'#part01 table td a.btnOn',function()
    $('#part01 table td a.btnOn').live('click', function() {
        //alert(2);
        var obj = getSelectNode();
        var org_ID = obj.oid;
        //var parent_orgid = obj.pid;
        var user_id = $(this).parents("tr").find("td:first").find("input").val(); //
        var staff_account = {
            //"parent_orgid":parent_orgid,
            //"orgid":org_ID,
            "type_id": 1,
            "user_id": user_id
        };
        //开通状态，点关闭按钮，关闭帐号操作
        //alert(user_id) 
        showDialog("staff/closeAccount" + "/" + user_id);
		
        var _checked = $(this);
        count_name = _checked.parent().parent().find('.userName').text();


        var _this = $(this);
        $("#dialog .dialogBottom #closeAccount").die("click");
        $("#dialog .dialogBottom #closeAccount").live("click", function() {
			if($(this).hasClass("false"))
			{
				return;
			}
			$(this).addClass("false");
			var _t=$(this);
            var path_setmanager ="staff/close_user";
           //  alert( staff_account.user_id)
            $.post(path_setmanager, staff_account, function(data) {
              // alert(data);
                var json = $.parseJSON(data);

                if (json.code == 0) {
                    _this.animate({backgroundPositionX: "-40px"}).removeClass('btnOn').addClass('btnOff');
                    hideDialog();

                } else {
					
				alert(json.prmopt_text)
		
					hideDialog();
				}
				_t.removeClass("false");
            });
            //_this.removeClass('btnOn').addClass('btnOff');
            //hideDialog();
        });
    });
    $('#part01 table td a.btnOff').die("click");
    //开通账号
    //$(document).on("click",'#part01 table td a.btnOff',function()
    $('#part01 table td a.btnOff').live('click', function() {
        if($(this).hasClass("false"))
        {
                return;
        }
        $(this).addClass("false");
        // alert(564657567)
        var user_id = $(this).parents("tr").find("td:first").find("input").val();
        var staff_account = {
            "type_id": 1,
            "user_id": user_id
        };
        //alert(user_id)
        var _this = $(this);
        var path_Off ="staff/open_user";
        $.post(path_Off, staff_account, function(data) {
           // alert(data);
            var json = $.parseJSON(data);
            if (json.code == 0) {
                //alert(444);
                _this.animate({backgroundPositionX: "0px"}).removeClass('btnOff').addClass("btnOn");
            }
			else
			{
				alert(json.prmopt_text);
				return false;
			}
			_this.removeClass("false");
        });

    });

	 $("#add_up").click(function(event) {
        $("#menu3").toggle();
        event.stopPropagation();
    });
    $(document).click(function() {
        //$("#allGroup2").hide();
        $("#menu3").hide();

        //$(".datepickers").empty();	
    });
    //在组织结构中按回车键查找员工
    $('#search_staff').keydown(function(e){
 	    if(e.which == 13){
 	    	var keyword = $(this).val();
 			var reg=/\s/g;
 			keyword=keyword.replace(reg,'');
 			if(keyword=="")
 					{
// 						alert("请输入需要查询的信息");
 						$(this).parent().find("input").val(keyword);
 						return;
 					}
 			loadCont('search/searchOrgAccountPage'+'?keyword='+keyword);
 	    }
	});
})