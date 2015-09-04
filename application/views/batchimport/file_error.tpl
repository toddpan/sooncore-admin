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
	<h3 class="conH3">很抱歉，此次导入失败</h3>
	<div class="grayBox listBox">
		<ul class="list">
			<li> <span class="submitWarning">失败原因：</span> </li>
			<li class="errorMsgList"> 
				{$msg}
			</li>
		</ul>
	</div>
	<a onclick="loadCont('{if $operate_type == 0 }{site_url('batchimport/index')}{else}{site_url('staff/batchModifyStaff')}{/if}')" class="linkGoback" style="margin-left:0">&lt;&lt;&nbsp;返回重新导入&nbsp;</a> 
</div>
