
<div class="contRight">
	<div id="part01">
		<div class="tabToolBar">
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
			<div class="tabToolBox" style="display:none;">
			
				<a class="btnGray btn btnChangeUser_O" >
					<span class="text">员工调岗</span>
					<b class="bgR"></b>
				</a>
				<a class="btnGray btn btnDeleUser">
					<span class="text">删除员工</span>
					<b class="bgR"></b>
				</a>
				<?php }?>
				<?php if($this->p_role_id == SYSTEM_MANAGER){?>
				<a class="btnGray btn btnBeManage" onclick="showSetManager()">
					<span class="text">指定为部门管理者</span>
					<b class="bgR"></b>
				</a>
				<a class="btnGray btn btnMoveManage"  onclick="showMoveManager()" style="display:none;">
					<span class="text">取消管理者身份</span>
					<b class="bgR"></b>
				</a>
				<?php }?>
			</div>
			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
			<ul class="menu" id="menu3" style="display: none;">
				<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
				<li>
					<a>
						<span onclick="addNewMember_one();" style="cursor: pointer">添加员工</span>
					</a>
				</li>
				<?php }?>
				<li>
					<a onclick="loadCont('staff/batchAddStaffPage');">批量添加</a>
				</li>
				
				<li>
					<a  onclick="loadCont('staff/batchModifyStaff');">批量修改</a>
				</li>
				
			</ul>
			<?php }?>
		</div>
            
                <!--员工列表位-->
                <table class="table table_org">
                    <thead>
                    <tr>
                        <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                        <th>帐号状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $user_arr = $user_arr;
                       //var_dump($user_arr);
                    foreach($user_arr as $k => $v):
                        ?>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" value="<?php echo $v['id'];?>" /></label></td>
                            <td class="tl"><a style="cursor: pointer" class="userName <?php if($v['is_org_manager'] == 1): ?> manage <?php endif;?>  ellipsis"  onclick="staff_information1(this,<?php echo $v['id'];?>)"><?php echo $v['displayName'];?></a></td>
                            <td class="tl"><span class="userCount ellipsis"><?php echo $v['loginName'];?></span></td>
                            <td class="telephone"><?php echo $v['mobileNumber'] ? $v['mobileNumber'] : $v['officePhone'];?></td>
                            <td class="logintime">
                                <?php
                                if(!bn_is_empty($v['lastlogintime'])){
                                    echo dgmdate($v['lastlogintime'], 'dt');
                                }else{
                                        echo '未登录';
                                }
                                ?>
                            </td>
                            <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
                            <td><a  class="countType <?php if($v['productStatus'] == 82): ?>  btnOn <?php else: ?> btnOff <?php endif;?>"><em class="btnFixed"></em></a></td>
                            <?php }?>
                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
                
	</div>
	<div class="groupLimit" id="org_power" style="display: none">
		<b class="arrow"></b>
		<div class="groupLimitContent">
			<div class="toolBar2">
				<a class="btnBlue yes" id="set_right"  >
					<span class="text">保存</span>
					<b class="bgR"></b>
				</a>
				<a class="btnGray btn"  onclick="$('.groupLimit').hide();">
					<span class="text">取消</span>
					<b class="bgR"></b>
				</a>
			</div>
		 </div>      
	</div>
</div>
<script type="text/javascript">
$(function() {
    var path_mag = "organize/get_manager_user_id";
    //批量选中员工，显示对员工的操作
    $('#part01 table:first thead label.checkbox').live("click",function(){
        var obj = getSelectNode(); 
        var org_ID = obj.oid; //获得当前组织id
           if ($(this).hasClass("checked")) {
               $(this).removeClass("checked");
               $(this).parents("thead").next().find("label.checkbox").removeClass("checked");
               $('#part01 .tabToolBox').hide();
           } else {
               $(this).addClass("checked");
               $(this).parents("thead").next().find("label.checkbox").addClass("checked");//选中所有
               $('#part01 .tabToolBox').show();
               if ($(this).parents("thead").next().find("label.checked").length == 1) {
                   //alert(111)
                   var user_id = $(this).parents("thead").next().find("input").val(); //
                   var staff_mag = {
                       "orgid": org_ID,
                       "user_id": user_id
                   }
                   //alert(staff_mag)
                   set_mange(staff_mag, path_mag);
               } else {
                   $('#part01 .btnBeManage').hide();
                   $('#part01 .btnMoveManage').hide();
               }
           }
       }
    );
        
        //单个选择员工的操作
    $('#part01 table.table_org tbody label.checkbox').live("click",function() {
        var obj = getSelectNode(); 
        var org_ID = obj.oid; //获得当前组织id
        
        if ($(this).hasClass("checked")) {
            //alert(99);
            $(this).removeClass("checked");//去掉选中状态
            if ($(this).parents("tbody").prev().find("label.checkbox").hasClass("checked")) {
                $(this).parents("tbody").prev().find("label.checkbox").removeClass("checked");//去掉全部选中状态
            }
            if ($(this).parents("tbody").find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parents("tbody").find("label.checked").length == 1) {
                //alert(1)
                var user_id = $(this).parents("tbody").find("label.checked input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                };
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }

        } else {
            $(this).addClass("checked");
            if ($(this).parents("tbody").find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parents("tbody").find("label.checkbox").length == $(this).parents("tbody").find("label.checked").length) {//如果被多选则在thead添加class
                if (!$(this).parents("tbody").prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parents("tbody").prev().find("label.checkbox").addClass("checked");
                }
            }
            if ($(this).parents("tbody").find("label.checked").length == 1) {
                // alert(2)
                //$('#part01 .tabToolBox ').show();
                var user_id = $(this).parents("tbody").find("label.checked input").val(); //获得USERID
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }
        }
    }  
   );
   
});
</script>