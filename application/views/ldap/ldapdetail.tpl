	<div id="ldap_detail_page">
		<div class="toolBar2" style="margin-top: -20px; margin-bottom: 20px;">
			<a class="back fl" onclick="call_back_list();" title="返回">&nbsp;</a>
    		<div class="infoTitle fl">
				<span class="personName">{$ldap_name}</span>
			</div>
			<a  class="btnGray fr"  onclick="detail_ldapdel(this,{$ldap_id});">
				<span class="text">删除</span>
				<b class="bgR"></b>
			</a>
    		<a class="btnBlue fr" onclick="loadCont('ldap/showLdapPage?ldap_id={$ldap_id}')">
				<span class="text">编辑</span>
				<b class="bgR"></b>
			</a>
		</div>
		<dl class="ldapSetCont">
			<dt class="setTitle" style="margin-bottom:5px;">链接LDAP设置</dt>
			<dd>
				<table class="infoTable">
					<tr>
						<td width="148">服务器类型：</td>
						<td>{$server_info['servertype']}</td>
					</tr>
					<tr>
						<td>连接方式：</td>
						<td>{$server_info['authtype']}</td>
					</tr>
					<tr>
						<td>LDAP服务器地址：</td>
						<td>{$server_info['hostname']}</td>
					</tr>
					<tr>
						<td>LDAP服务器端口：</td>
						<td>
							{$server_info['port']}
						</td>
					</tr>
					<tr>
						<td>LDAP服务器用户名：</td>
						<td>{$server_info['admindn']}</td>
					</tr>
					<tr>
						<td>LDAP服务器密码：</td>
						<td>{$server_info['adminpassword']}</td>
					</tr>
				</table>
			</dd>
			<dt class="setTitle" style="margin:15px 0 5px;">导入的组织列表</dt>
			<dd style="padding: 10px">
				<div class="treeBox">
					<ul class="ztree" id="ldap_tree_detail" style="display:block;" value_tree="{$ldap_tree}" value_id="{$ldap_id}">
					</ul>
				</div>
			</dd>
			<dt class="setTitle" style="margin:15px 0 5px;">选择的员工标签</dt>
			<dd>
				<table class="infoTable">
					{foreach $classes as $c}
						<tr>
							<td width="148">{$c}</td>
							<td>&nbsp;</td>
						</tr>
					{/foreach}
				 </table>
			   
			</dd>
		   <dt class="setTitle" style="margin:15px 0 5px;">请为您企业选择统一的蜜蜂帐号前缀</dt>
			<dd>
				<table class="infoTable">
					<tr>
						<td>{$account}</td>
					</tr>
				 </table>
			   
			</dd>
			<dt class="setTitle" style="margin:15px 0 5px;">不开通全时蜜蜂的例外规则</dt>
			<dd>
				<table class="infoTable">
					{foreach $filter_rule as $f}
						<tr>
							<td >{$f}</td>
						</tr>
					{/foreach}
				 </table>
			</dd>
		</dl>
	</div>
		<script type="text/javascript" src="public/js/part_js/ldap_detail.js"></script>
		<script type="text/javascript">
		
		</script>
	</body>
</html>










