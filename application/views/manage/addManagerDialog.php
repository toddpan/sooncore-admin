	
<!-- 弹窗_管理员管理_添加管理员.html -->
<dl class="dialogBox D_addAdmin">
	<dt class="dialogHeader">
		<span class="title">添加管理员</span>
		<a class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody" style="overflow: inherit">
		<table class="infoTable">
			<tr>
				<td>管理员：</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					<div class="inputBox w210" style="z-index:100">
						<label class="label" >输入员工姓名或帐号</label>
						<input class="input" value="" id="admin_name"/>
						<div id="search_admin1" class="" style="overflow:auto">
						</div>
					</div>
                </td>
				<td>&nbsp;<span id="error" style="display:none;color:red">请输入正确的账号</span></td>
			</tr>
			<tr>
				<td>管理员角色：</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
                    <div id="juese" class="combo w210" style="z-index:auto">
                        <a class="icon" ></a>
                        <span class="text">请选择管理员角色</span>
                        <div class="option_Box" style="display: none">
                            <dl class="optionList" id="admin_option">
                               <dd class="option selected" target="1" onclick="role_select(this)">请选择管理员角色</dd>
								<dd class="option" target="2" role_id="3" onclick="role_select(this)">员工管理员</dd>
								<dd class="option" target="3" role_id="4" onclick="role_select(this)">帐号管理员</dd>
<!--  								<dd class="option" target="4" role_id="5" onclick="role_select(this)">生态管理员</dd> -->
                            </dl>
                        </div>
                    </div>
                </td>
				<td>&nbsp;</td>
			</tr>
			<tr class="hideBar01 hide">
				<td>组织管理范围：</td>
				<td>&nbsp;</td>
			</tr>
			<tr class="hideBar01 hide">
				<td>
                    <div id="weidu01" class="combo selectBox w210">
                        <a class="icon" cl_id="weidu01"></a>
                        <span class="text" cl_id="weidu01">请选择第一个管理维度</span>
                        <div class="optionBox">
                            <dl class="optionList">
                                <dd class="option selected" target="1">请选择第一个管理维度</dd>
                                <dd class="option" target="2">部门</dd>
                                <dd class="option" target="3">地区</dd>
                                <dd class="option" target="4">成本中心</dd>
                            </dl>
                        </div>
                    </div>
                </td>
				<td>
                    <div class="select-box w210 hide" id="first_level" style="z-index:10000">
                        <input type="text" class="text" cl_id="part1" onclick="showMenu(this);" id="departmentSel" placeholder="请选择管理的部门" />
                        <a class="icon" id="menuBtn" cl_id="part1" onclick="showMenu(this);"></a>
                        <div class="selectOptionBox" cl_id="part1" id="selectOption1" style="display: none; width: 210px;">
                            <ul class="ztree" cl_id="part1" id="ztree3" ids=""></ul>
                        </div>
                    </div>
                </td>
			</tr>
		</table>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue" onclick="add_admin()"><span class="text">添加</span><b class="bgR"></b></a>
		<a class="btnGray"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>


<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="public/js/part_js/addmangerdialog.js"></script>
<script type="text/javascript" src="public/js/part_js/addmanger_tree.js"></script>
<script type="text/javascript" src="public/js/tree.js"></script>
<script type="text/javascript" src="public/js/self_tree.js"></script>
<script type="text/javascript">
</script>