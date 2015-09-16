<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--搜索_组织与帐号.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03 rightLine">搜索结果</span> 
    <div class="contHead-right">
	<div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)"  ></a></div>
	
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
                        <a class="icon js-search" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" type="text" name="keyword" id="search_staff"/>
		</div>
	</div>
     <ul class="menu" id="menu1">
            <li><a  onclick="loadCont('组织与员工_批量导入.html')">员工标签管理</a></li>
            <li><a  onclick="loadCont('staff/batchModifyStaff');">批量修改</a></li>
     <!--       <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">LDAP设置</a></li>  -->
        </ul>
    </div>
</div>
<!-- end contHead -->
<div class="contMiddle" id="part01">
    
    <!-- end conTabs -->
        <?php $ret_cnt = count($ret_data); ?>
            <div class="msg-bar">
              <div class="msg-bar-left">为您找到 "<span class="red"><?php echo $ret_cnt; ?></span>"个相关结果</div>
            </div>
          
            <!-- end bread -->
            <div class="tabToolBar">
                <a class="back fl" onclick="loadCont('organize/OrgList');" title="返回">&nbsp;</a>
                <div class="tabToolBox" style="display:none;">
                    <a class="btnGray btn" id="search_move_staff">
						<span class="text">员工调岗</span><b class="bgR"></b>
					</a>
                    <a class="btnGray btn" id="search_delete_staff"><span class="text">删除员工</span><b class="bgR"></b></a>
                    
                </div>
            </div>
            <!-- end tabToolBar -->
            <table class="table">
                <thead>
                    <tr>
                    	<?php if(!empty($ret_cnt)){ ?>
                        <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
                    	<?php } ?>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                        <th>帐号操作</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                foreach($ret_data as $user):?>   
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" value="<?php echo $user['userId'];?>"/></label></td>
                        <td class="tl"><a class="userName ellipsis"  onclick="staff_information1(this,<?php echo $user['userId'];?>);"><?php echo $user['displayName'];?></a></td>
                        <td class="tl"><span class="ellipsis"><?php echo $user['loginName'];?></span></td>
                        <td><?php echo $user['mobileNumber'];?></td>
                        <td><?php echo $user['lastlogintime'];?></td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>          
</div>
<script type="text/javascript">
	
	$(function(){
		 $('#part01 table:first thead label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            $('#part01 .tabToolBox').hide();
            //$('#part01 .btnBeManage').hide();
            //$('#part01 .btnMoveManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
            $('#part01 .tabToolBox').show();
            if ($(this).parent().parent().parent().next().find("label.checked").length == 1) {
                //alert(111)
                var user_id = $(this).parent().parent().parent().next().find("input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                //set_mange(staff_mag, path_mag);
            } else {
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            }
        }
    },
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            $('#part01 .tabToolBox ').hide();
            //$('#part01 .btnBeManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
            $('#part01 .tabToolBox ').show();
            if ($(this).parent().parent().parent().next().find("label.checked").length == 1) {
                var user_id = $(this).parent().parent().parent().next().find("input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
               // set_mange(staff_mag, path_mag);
            } else {
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            }
        }
    }) 
	$('#part01 table:first tbody label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            if ($(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                $(this).parent().parent().parent().prev().find("label.checkbox").removeClass("checked");
            }
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            

        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
            }
            
        }
    },
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            if ($(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                $(this).parent().parent().parent().prev().find("label.checkbox").removeClass("checked");
            }
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };            

        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
            }
           
        }
    });
	 //点击员工调岗
    // $(".btnChangeUser_O").die("click");
    $("#search_move_staff").click(function() {
		if($(this).hasClass("false"))
		{
			return;
		}
		$(this).addClass("false");
		var _this=$(this);
        var user_id ='';       
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
            var dgtree = $.fn.zTree.getZTreeObj("dgmoveorg");
            var nodes1 = dgtree.getSelectedNodes();
            var treeNode1 = nodes1[0];
            if (treeNode1 != null) {
                var neworgid = treeNode1.id;
                var parent_orgid = treeNode1.pId;
                // alert(orgid1)
            } else {
                alert("请选择要调入的部门！");
                return false;
            }
            id_2 = treeNode1.pId;
            var org_code1 ='-' + treeNode1.id;           
           /* var orgname = "";
            orgname = treeNode.name;*/
            var neworgname = "";
            neworgname = treeNode1.name;
            // alert(orgname);
            //alert(neworgname);
            //alert(user_id)
            var staff = {
                //"parent_orgid":parent_orgid,
                //"old_org_code":org_code,
               // "orgid": orgid,
               // "orgname": orgname, //新的部门名称//
                "user_id": user_id,
               'neworgid': neworgid,
                //'new_org_code':org_code1,
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
                    hideDialog();
                } else {					
					alert(json.prompt_text)		  	
					hideDialog();
				}
				_t.removeClass("false");
			
            })
        })

    });		
	 //选中员工，点击删除员工
    $('#search_delete_staff').click(function() {
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
    	
	 	//alert(user_id)
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
							
							alert(json.prmopt_text)
									
							hideDialog();
						}
			
			_t.removeClass("false");
		})
		
		 //返回code是否成功，如果成功：重新加载当前组织帐号列表。
	})
 });	
		 //员工搜索js事件
		$('.js-search').click(function(){
			var keyword = $(this).parent().find('input[name=keyword]').val();
			var reg=/\s/g;
			keyword=keyword.replace(reg,'');
			if(keyword=="")
					{
// 						alert("请输入需要查询的信息");
						$(this).parent().find("input").val(keyword);
 						$(this).parent().find(".label").css("display","block");
						return;
					}
			loadCont('search/searchOrgAccountPage'+'?keyword='+keyword);
		});
		$('#search_staff').keydown(function(e){
	 	    if(e.which == 13){
	 	    	var keyword = $(this).val();
	 			var reg=/\s/g;
	 			keyword=keyword.replace(reg,'');
	 			if(keyword=="")
	 					{
// 	 						alert("请输入需要查询的信息");
 							$(this).parent().find("input").val(keyword);
	 						return;
	 					}
	 			loadCont('search/searchOrgAccountPage'+'?keyword='+keyword);
	 	    }
		});
});

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
        $('#part01 .tabToolBar').hide();
        $('#part01 .table').hide();
        var path_staff_information='staff/search_staff_info_page';
        var obj={
			"user_id":user_id,
			"flag":"search",
        }
        $.post(path_staff_information,obj,function(data)
        {
            $('#part01 .tabToolBar').eq(0).after(data);
			_this.removeClass("false");
		});
    }
</script>
</body>
</html>