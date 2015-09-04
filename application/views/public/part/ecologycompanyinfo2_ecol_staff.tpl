<dd class="ecol_staff">
	<div class="tabToolBar">
		<a class="btnBlue btnAddUser">
			<span class="text">添加员工</span>
			<b class="bgR"></b>
		</a>
	<div class="tabToolBox1" style="display: none"><a
		class="btnGray btn btnDeleUser"><span class="text">移除员工</span><b
		class="bgR"></b></a></div>
	</div>
	<table class="table" id="self_staff">
		<thead>
			<tr>
				<th width="6%"><span class="checkbox"><input type="checkbox" /></span></th>
				<th style="text-align: left; text-indent: 24px">姓名</th>
				<th>帐号</th>
				<th>手机</th>
				<th>上次登录</th>

			</tr>
		</thead>
		<tbody>
    		{foreach $user_arr as $v}
			<tr>
				<td><span class="checkbox" ><input type="checkbox" value="{$v['id']}"/></span></td>
				<td class="tl"><a class="userName ellipsis" name="{$v['id']}" id="eco_staff">{$v['displayName']}</a></td>
				<td class="tl"><span class="userCount ellipsis">{$v['loginName']}</span></td>
				<td class="telephone">{$v['mobileNumber']}</td>
				<td class="logintime">{$v['lastlogintime']}</td>
			</tr>
		  {/foreach}
		</tbody>
	</table>
	</dd>
<script type="text/javascript" src="public/js/part_js/input_radio_tree.js"></script>
<script type="text/javascript" src="public/js/part_js/ecologycompanyinfo2_ecol_staff.js"></script>