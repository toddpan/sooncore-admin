<div class="contHead"> <span class="title01">组织管理</span>
	<div class="contHead-right">
		<div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"  ></a></div>
		<div class="headSearch rightLine">
			<div class="combo searchBox"> <b class="bgR"></b> <a class="icon" ></a>
				<label class="label">请输入查询条件</label>
				<input class="input" />
			</div>
		</div>
		<ul class="menu" id="menu1">
			<li><a  onclick="loadCont('{site_url('tag/addTagPage')}')">员工标签管理</a></li>
			<!-- <li><a  onclick="loadCont('{site_url('ldap/showLdapPage')}')">添加LDAP设置</a></li> -->
		</ul>
	</div>
</div>
<div class="feedBackBox" >
	<h3 class="conH3">成功导入{$success_count}个帐号，失败{$fail_count}个</h3>
	<div class="grayBox listBox">
		<ul class="list">
			<li> <span class="submitWarning">失败原因：</span> </li>
			<li class="errorMsgList"> 
				<span class="errorText02"> 您可以下载导入失败的列表，并查看其原因<br />
					<a class="btnGray" href="{$fail_url}" style="margin-bottom:20px;">
						<span class="text">下载失败列表</span><b class="bgR"></b>
					</a>
				</span> 
			</li>
		</ul>
		<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b> </div>
	<a onclick="loadCont('{if $operate_type == 0 }{site_url('batchimport/index')}{else}{site_url('staff/batchModifyStaff')}{/if}');" class="linkGoback" style="margin-left:0">&lt;&lt;&nbsp;返回重新导入&nbsp;</a> 
	<a onclick="loadCont('{site_url('organize/OrgList')}');" class="linkGoback">查看已导入的组织员工&nbsp;&gt;&gt;</a> 
</div>

