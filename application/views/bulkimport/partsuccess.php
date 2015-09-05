<div class="contHead"> <span class="title01">组织管理</span>
	<div class="contHead-right">
		<div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"></a></div>
		<div class="headSearch rightLine">
			<div class="combo searchBox"> <b class="bgR"></b> <a class="icon" ></a>
				<label class="label">请输入查询条件</label>
				<input class="input" />
			</div>
		</div>
		<ul class="menu" id="menu1">
			<li><a onclick="loadCont('tag/addTagPage')">员工标签管理</a></li>
			<li><a onclick="loadCont('ldap/showLdapPage')">添加LDAP设置</a></li>
		</ul>
	</div>
</div>
<div class="feedBackBox" style="display:none">
	<h3 class="conH3">成功导入<?php echo $success_count; ?>个帐号，失败<?php echo $fail_count; ?>个</h3>
	<div class="grayBox listBox">
		<ul class="list">
			<li> <span class="submitWarning">失败原因：</span> </li>
			<li class="errorMsgList"> <span class="errorText01"><?php echo $err_msg;?></span>
				<!--<span class="errorText01">模板信息标签未填写完整；</span>
				<span class="errorText01">手机或邮箱格式不正确，如：手机位数不对，邮箱格式不正确。</span>
				<span class="errorText01">手机或邮箱信息相同。</span>-->
				<span class="errorText02"> 您可以下载导入失败的列表，并查看其原因<br />
				<a class="btnGray" onclick="bulkimport/downloadFailFile/$fail_file" style="margin-bottom:20px;"><span class="text">下载失败列表</span><b class="bgR"></b></a> </span> </li>
		</ul>
		<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b> </div>
	<a onclick="loadCont('<?php if($operate_type == 0){echo site_url('bulkimport/showBulkImportPage');}else{echo site_url('staff/batchModifyStaff');} ?>');" class="linkGoback rightLine">&lt;&lt;&nbsp;返回继续导入&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a> <a onclick="loadCont('<?php echo site_url('organize/OrgList');?>');" class="linkGoback">查看已导入的组织员工&nbsp;&gt;&gt;</a> </div>
<div class="feedBackBox" style="display:none">
	<h3 class="conH3">很抱歉，此次导入失败</h3>
	<div class="grayBox listBox">
		<ul class="list">
			<li> <span class="submitWarning">失败原因：</span> </li>
			<li class="errorMsgList"> <span class="errorText01"><?php echo $err_msg;?></span> </li>
		</ul>
		<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b> </div>
	<div class="pre-view">
		<h3 class="conH3">预览</h3>
		<h4>您上传的文件</h4>
		<table class="table">
			<thead>
				<tr>
					<?php foreach($excel_head as $value1){
                            if(in_array($value1, $system_tag_head)){
                            	?>
					<th><?php echo $value1;?></th>
					<?php }else{?>
					<th style="color:red"><?php echo $value1;?></th>
					<?php }}?>
				</tr>
			</thead>
		</table>
		<h4>您定义的模板</h4>
		<table class="table">
			<thead>
				<tr>
					<?php foreach ($system_tag_head as $value2){?>
					<th><?php echo $value2;?></th>
					<?php }?>
				</tr>
			</thead>
		</table>
	</div>
	<a onclick="loadCont('<?php if($operate_type == 0){echo site_url('bulkimport/showBulkImportPage');}else{echo site_url('staff/batchModifyStaff');} ?>');" class="linkGoback" style="margin-left:0">&lt;&lt;&nbsp;返回重新导入&nbsp;</a> </div>
<script type="text/javascript">
var type = <?php echo $err_type;?>;// 错误类型：0、无错；1、格式或文件大小错误；2、模板定义不一致；三、文件内容不正确
//alert(type)
$(function(){
	//alert(type)
	if(type==1)
	{
		$('.feedBackBox:eq(1)').show();
		$('.feedBackBox:eq(1) .pre-view').hide();
		
	}
	else if(type==2)
	{
		$('.feedBackBox:eq(1)').show();
	}else if(type==3)
	{
		$('.feedBackBox:eq(0)').show();
	}
	else
	{		
		loadCont('organize/OrgList');
	}
});
</script>
