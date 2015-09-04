<div class="contHead"> <span class="title01">组织管理</span>
	<div class="contHead-right">
		<div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"  ></a></div>
		<div class="headSearch rightLine">
			<div class="combo searchBox"> <b class="bgR"></b> <a class="icon j-search" ></a>
				<label class="label">请输入查询条件</label>
				<input class="input" name="keyword"/>
			</div>
		</div>
		<ul class="menu" id="menu1">
			<li><a  onclick="loadCont('{site_url('tag/addTagPage')}')">员工标签管理</a></li>
			<!-- <li><a  onclick="loadCont('{site_url('ldap/showLdapPage')}')">添加LDAP设置</a></li> -->
		</ul>
	</div>
</div>
<div class="feedBackBox">
	<h3 class="conH3">很抱歉，此次导入失败</h3>
	<div class="pre-view">
		<h3 class="conH3">预览</h3>
		<h4>您上传的文件</h4>
		<table class="table">
			<thead>
				<tr>
					{foreach $header_data as $header}
						{if in_array($header, $undefined_tags)}
							<td style="color:red">{$header}</td>
						{else}
							<td>{$header}</td>
						{/if}
					{/foreach}	
				</tr>
			</thead>
		</table>
		<h4>您定义的模板</h4>
		<table class="table">
			<thead>
				<tr>
					{foreach $tags as $tag}
						<td>{$tag}</td>
					{/foreach}
					
				</tr>
			</thead>
		</table>
	</div>
	<a onclick="loadCont('{if $operate_type == 0 }{site_url('batchimport/index')}{else}{site_url('staff/batchModifyStaff')}{/if}')" class="linkGoback" style="margin-left:0">&lt;&lt;&nbsp;返回重新导入&nbsp;</a> 
</div>
<script type="text/javascript">
	$('.js-search').click(function() {
					//alert(111);
		var keyword = $(this).parent().find('input[name=keyword]').val();
		var reg=/\s/g;
		keyword=keyword.replace(reg,'');
		if(keyword=="")
		{
			alert("请输入需要查询的信息");
			return;
		}
		//loadCont('search/searchOrgAccountPage')?>' +'?keyword=' + keyword);
	});
</script>