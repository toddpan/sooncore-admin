<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>sooncore平台管理中心</title>
		<base href="{$tag_base_url}"/>
		<base target="_blank" />
	</head>
	<body>
		<div class="contHead">
			<span class="title01 rightLine">组织管理</span>
			<span class="title03" id="ldap_list">LDAP列表</span>
			<span class="title03" id="find_ldap_detail" style="display: none">查看LDAP详情</span>
			<div class="contHead-right" style="display:none" id="ldap_detail_head">
				<div class="fr rightLine">
					<a class="btnSet" onclick="toggleMenu('menu1',event)"></a>
				</div>
				<div class="headSearch rightLine">
					<div class="combo searchBox">
						<b class="bgR"></b>
						<a class="icon" ></a>
						<label class="label">请输入查询条件</label>
						<input class="input"/>
					</div>
				</div>
    			<ul class="menu" id="menu1">
					<li>
						<a onclick="set_tag()">员工标签管理</a>
					</li>
					<li>
						<a onclick="loadCont('login/loginPage');">管理员设置</a>
					</li>
					<li>
						<a onclick="loadCont('ldap/showLdapPage');">LDAP设置</a>
					</li>
    			</ul>
    		</div>
		</div>
		
		<div class="contMiddle">
		    <div class="contRight" style="margin-left: 0" id="contRigt_ldaplist">
		         <div class="tabToolBar">
						<a class="btnBlue yes fr" onclick="loadPage('ldap/showLdapPage')">
							<span class="text">新建</span>
							<b class="bgR"></b>
						</a>
						<a class="back fl" onclick="loadPage('organize/OrgList','group')" title="返回">&nbsp;</a>
						<div class="tabToolBox fl" style="display:none;">
							<a class="btnGray btn btnMoveManage" onclick="delet_createLdap(this);">
								<span class="text">删除</span>
								<b class="bgR"></b>
							</a>
						</div>
						<span class="green-style fl">每天的凌晨将会为您自动同步</span>
            	</div>
            	<table class="table">
                	<thead>
                    	<tr>
							<th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
							<th width="14%" style="text-align:left; text-indent: 24px;">名称</th>
							<th>创建时间</th>
							<th>最后一次同步时间</th>
							<th width="16%">操作</th>  
                    	</tr>
                	</thead>
                	<tbody>
						{foreach $ldap_list as $ldap}	
						<tr ldap_id="{$ldap['ldap_id']}">
							<td>
								<label class="checkbox"><input type="checkbox" /></label>
							</td>
							<td align="center" class="tl">
								<a class="userName ellipsis" onclick="ldap_detail_show(this,{$ldap['ldap_id']})">{$ldap['ldap_name']}
								</a>
							</td>
							<td align="center">{$ldap['create_time']}</td>
							<td align="center">
								<span class="ellipsis">{$ldap['last_sync_date']}</span>
							</td>
							<td>
								<a onclick="synControll(this,{$ldap["ldap_id"]})">{$ldap['is_auto_sync']}</a></td>
						</tr>
                   		{/foreach}
                	</tbody>
            	</table>
            	<div class="page" style="display: none">
					<a class="disabled" >首页</a>
					<a class="disabled" >上一页</a>
					<a class="num selected" >1</a>
					<a class="num " >2</a>
					<a class="num " >3</a>
					<a class="" >下一页</a>
					<a class="" >尾页</a>
					<span class="text ml10">第</span>
					<div class="inputBox">
						<b class="bgR"></b>
						<label class="label"></label>
						<input class="input" value="" />
					</div>
               		<span class="text">页/3</span>
          		</div>
    		</div>
		</div>
		<script type="text/javascript" src="public/js/part_js/ldap_list.js"></script>
		<script type="text/javascript">
		function set_tag()
		{
			$('.pageBody .leftMenu li').removeClass("selected");
			$('.pageBody .leftMenu li').find('.company').parent().addClass("selected");
			loadCont('tag/addTagPage/0','company');
		}
		</script>
	</body>
</html>