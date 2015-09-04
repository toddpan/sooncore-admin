<div class="contRight" style="">
	<div id="part01" style="">
	<?php if($this->functions['orgAuthority']){?>
		<a class="link_limitSet"  onclick="toggleGroupLimit(this,event)" title="部门权限">部门权限</a>
	<?php }?>
		<div class="bread"></div>
		<div class="tabToolBar">
			<?php if($this->functions['employeeAdd']){?>
			<a class="btnBlue yes btnAddUser">
				<span class="text" onclick="addNewMember_one()">添加员工</span>
				<span id="add_up" class="more">&nbsp;</span>
				<b class="bgR"></b>
			</a>
			<?php }?>
			<div class="tabToolBox" style="display:none;">
				<?php if($this->functions['employeeChange']){ ?>
				<a class="btnGray btn btnChangeUser_O" >
					<span class="text">员工调岗</span>
					<b class="bgR"></b>
				</a>
				<!--a class="btnGray btn btnDeleUser">
					<span class="text">删除员工</span>
					<b class="bgR"></b>
				</a-->
				<?php 
				}
				if($this->functions['employeeAsManager']){ 
				?>
				<a class="btnGray btn btnBeManage" onclick="showSetManager()">
					<span class="text">指定为部门管理者</span>
					<b class="bgR"></b>
				</a>
				<a class="btnGray btn btnMoveManage"  onclick="showMoveManager()" style="display:none;">
					<span class="text">取消管理者身份</span>
					<b class="bgR"></b>
				</a>
				<?php } ?>
			</div>
			<ul class="menu" id="menu3" style="display: none;">
				<?php if($this->functions['employeeAdd']){?>
				<li>
					<a onclick="addNewMember_one();">添加员工</a>
				</li>
				<?php }?>
				<?php if($this->functions['employeeBatch']){?>
				<li>
					<a onclick="loadCont('staff/batchAddStaffPage');">批量添加</a>
				</li>
				
				<li>
					<a  onclick="loadCont('staff/batchModifyStaff');">批量修改</a>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<div id="part02" style="display:none;">
		<div class="bread">
			<span>成本中心</span>&nbsp;&gt;&nbsp;<span>未指定成本中心</span>
		</div>
		<div id="test"></div>
		<div class="tabToolBar">
		   <div class="tabToolBar-right" style="padding: 0;">
				<a class="right_btn btnBlue" style="float: left; margin-right: 5px;">
					<span class="text">调入员工</span><span class="more">&nbsp;</span>
					<b class="bgR"></b>
				</a>
				<div class="select selectGroup" style="margin-top: 5px;">
					<span>全部组织</span>
				</div>
			</div>
			<div class="tabToolBox" style="display: none">
				<a class="btnGray btn btnMoveUserTo"  onclick="showDialog('costcenter/move_staff')">
					<span class="text">移动到</span>
					<b class="bgR"></b>
				</a>
				<a class="btnGray btn btnMoveUser"  onclick="showDialog('costcenter/del_staff')">
					<span class="text">移除员工</span>
					<b class="bgR"></b>
				</a>
			</div>
		</div>
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
