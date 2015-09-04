<div class="contHead">
	<span class="title01 rightLine">组织管理</span>
	<span class="title03">设置员工标签</span>
</div>
<div id="tagManager">
	<div class="area-title bottom-mini-space">必选的员工标签</div>
	<div class="area-container" type="necessaryTags">
	</div>
	<div class="area-title top-space bottom-mini-space">可选的员工标签</div>
	<div class="area-container" type="optionalTags">
	</div>
	<div class="top-space" type="saveBar" style="text-align: left;">
		<a class="btnBlue yes" href="javascript:void(0);" confirm>
			<span class="text" style="width:60px;">完成</span><b class="bgR"></b>
		</a>
		<a class="btnGray btn" href="javascript:void(0);" cancel>
			<span class="text" style="width:60px;">取消</span><b class="bgR"></b>
		</a>
	</div>
</div>


<script id="customTagSettings" type="template/html">
<label class="checkbox right-space">
	<input type="checkbox" client-searchable />
	允许搜索
</label>
<label class="checkbox right-space">
	<input type="checkbox" client-visible />
	客户端展示
</label>
<label class="checkbox right-space ele-hidden">
	<input type="checkbox" client-editable />
	允许员工变更
</label>
<label class="title right-space ele-hidden">
	输入长度
	<input type="text" class="spinner-text left-mini-space" maxLength="2" value="0" value-max-length />
</label>
</script>

<script id="customTagCreation" type="template/html">
<div class="edit-container">
<input class="normal-text" type="text" maxLength="10" style="width: 332px;" />
<div class="tag-actions">
	<a class="btnBlue right-space" type="confirm">
		<span class="text" style="width: 60px;">确定</span>
		<b class="bgR"></b>
	</a>
	<a class="btnGray" type="cancel">
		<span class="text" style="width: 60px;">取消</span>
		<b class="bgR"></b>
	</a>
</div>
</div>
</script>

<script id="customTagActions" type="template/html">
<div class="tag-actions left-space">
	<a class="btnGray2 right-space" href="javascript:void(0);" type="edit">
		<span class="text">编辑</span><b class="bgR"></b>
	</a>
	<a class="btnGray2" href="javascript:void(0);" type="delete">
		<span class="text">删除</span>
		<b class="bgR"></b>
	</a>
</div>
</script>

<script id="ldapSetting" type="template/html">
<div class="area-title top-space bottom-mini-space">LDAP站点登录名规则</div>
<div class="area-container" type="ldapSetting">
	<div class="tag-row">
		<label class="checkbox">
			<input type="checkbox" />
			登录名使用自定义后缀名
		</label>
	</div>
	<div class="tag-row ele-hidden">
		<label>自定义后缀名：</label>
		<input type="text" class="normal-text" style="width:200px">
		<span class="error1 message-info ele-hidden">请输入自定义后缀名</span>
	</div>
</div>
</script>

<script type="text/javascript" src="public/js/part_js/qs.tagManager.js"></script>

<script type="text/javascript">
$().ready(function(){
	qs.tagManager.init({
		necessaryTags: <?php echo json_encode($necessary_tags) ?>,
		optionalTags: <?php echo json_encode($optional_tags) ?>,
		departmentLevel: <?php echo $department_level ?>,
		displayLDAPSetting: <?php echo $is_LDAP ?>,
		ldapSetting: <?php echo json_encode($self_defined_suffix) ?>
	});
});
</script>