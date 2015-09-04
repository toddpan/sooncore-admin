<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<base href="<?php echo site_url('');?>"/>
		<base target="_blank" />
		<title>云企管理中心</title>
	</head>
	<body>
		<div class="contHead">
			<span class="title01 rightLine">组织管理</span>
			<span class="title03">设置员工标签</span>
		</div>
		<dl class="ldapSetCont">
        	<dt class="setTitle" style="margin-bottom:5px;">必选的员工标签</dt>
        	<dd>
            	<table class="infoTable">
					<tr>
						<td width="112">姓</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="112">名</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="112">性别</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="112">部门</td>
						<td>
							<div style="width: 200px;" class="combo selectBox">
								<a class="icon"></a>
								<span id="depart_level" class="text selected" title="2">2</span>
								<div class="optionBox" style="display: none;">
									<dl class="optionList" style="height: 286px;">
										<dd target="0" class="option" style="">请选择部门层级</dd>
										<dd target="1" class="option ">1</dd>
										<dd target="2" class="option">2</dd>
										<dd target="3" class="option ">3</dd>
										<dd target="4" class="option ">4</dd>
										<dd target="5" class="option ">5</dd>
										<dd target="6" class="option">6</dd>
										<dd target="7" class="option">7</dd>
										<dd target="8" class="option">8</dd>
										<dd target="9" class="option">9</dd>
										<dd target="10" class="option">10</dd>
									</dl>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td width="112">职位</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td width="112">手机</td>
						<td>&nbsp;</td>
					</tr>           
            	</table>
        	</dd>
        	<dt class="setTitle" style="margin:10px 0 5px;">可选的员工标签</dt>
        	<dd>
				<table class="infoTable">
					<?php 
					if ( is_array($not_must_tag_arr)):
					foreach ($not_must_tag_arr as $key => $ns_tag_arr):
						$ns_id = isset($ns_tag_arr['id'])?$ns_tag_arr['id']:0;
						$ns_field = isset($ns_tag_arr['field'])?$ns_tag_arr['field']:'';
						$ns_title = isset($ns_tag_arr['title'])?$ns_tag_arr['title']:'';
						?>
						<tr>
							<td width="148"><label class="checkbox">
								<input type="checkbox" value="<?php echo $ns_title ?>" /> <?php echo $ns_title ?></label>
							</td>
							<td width="326">&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
		
					<?php endforeach;
					endif;
					 ?>
				</table>
        	</dd>
       		<dt class="setTitle" style="margin:10px 0 5px;">自定义更多的员工标签</dt>
        	<dd>
        	<?php if(!($this->p_role_id == EMPPLOYEE_MANAGER)){?>
        		<table class="infoTable" id="self_staff_tag">
                	<tr>
						<td>
							<a class="btn_addTag" onclick="add_staff_tag(this)">添加员工标签</a>
						</td>
					</tr>
            	</table>
            <?php }?>
       	 	</dd>
    	</dl>
    	<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
		<div class="toolBar2" style="padding-top: 20px; text-align: left">
			<a class="btnBlue yes" style="margin-left: 0">
				<span class="text" onclick="staff_finished(<?php echo $page_type; ?>,<?php echo $is_LDAP; ?>,<?php echo $is_LDAP; ?>)" style="cursor: pointer">完成</span>
				<b class="bgR"></b>
			</a>
			<a class="btnGray btn" onclick="loadPage('<?php echo site_url('main/mainPage');?>','main');">
				<span class="text" style="cursor: pointer">取消</span>
				<b class="bgR"></b>
			</a>
		</div>
    	<div style="width: 100px; height:48px; border: 1px solid #ddd; line-height: 24px; background: #fff; position: absolute; display: none" id="lsBox">
			
			<a  onclick="bulk_import(<?php echo $page_type; ?>,<?php echo $is_LDAP; ?>,0)">批量导入入口</a><br />
			<a onclick="bulk_import(<?php echo $page_type; ?>,<?php echo $is_LDAP; ?>,1)">LDAP同步入口</a>
			<?php }?>
		</div>
		<script type="text/javascript" src="public/js/jquery.js"></script>
		<script type="text/javascript" src="public/js/jquery.ui.core.js"></script>
		<script type="text/javascript" src="public/js/jquery.ui.widget.js"></script>
		<script type="text/javascript" src="public/js/jquery.ui.datepicker.js"></script>
		<script type="text/javascript" src="public/js/jquery.tree.js"></script>
		<script type="text/javascript" src="public/js/self_common.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript" src="public/js/part_js/tag.js"></script>
		<script type="text/javascript">
		
		$('.setLabelBtn').removeClass("false");
		var is_LDAP = '<?php echo $is_LDAP; ?>'; //0：否（批量导入）；1：是（LDAP导入）；2：全部都可以
		var page_type = '<?php echo $page_type; ?>';//0新加页面1修改标签
		var department_level = '<?php echo $department_level; ?>';//后台传过来的部门层级值
		//alert(department_level)
		var system_tag_names = '<?php echo $system_tag_names; ?>';//所有的系统员工标签名,多个用，号分隔
		var seled_not_must_tag_arr = '<?php echo json_encode($seled_not_must_tag_arr); ?>';
		var seled_not_must_tag_names = '<?php echo $seled_not_must_tag_names; ?>';//系统选中的可选员工标签名，多个用，号分隔
		var user_defined_tag_arr = '<?php echo json_encode($user_defined_tag_arr); ?>';//自定义员工标签数组
		var  newEdit='<tr class="userInfo" style="display: none;" id="edit_staff_message">'+
						  '<td>'+
							'<div class="inputBox fl" style="margin-right:5px;">'+
							  '<label class="label">请输入员工信息</label>'+
							  '<input id="newInfo" class="input" value="" style="width: 332px;">'+
							'</div>'+
							 '<label class="radio fl checked"  id="admin_write" for="writeTagName01">'+
							   '<input id="writeTagName01" checked="checked" type="radio" value="0" name="writeTagName" >管理员填写</label>'+
							 '<label class="radio fl" for="writeTagName02" id="staff_write">'+
							   '<input id="writeTagName02" type="radio" value="1" name="writeTagName">员工填写</label>'+
							 '<a class="btnBlue yes fl" onclick="addNewItem_Two(this)" style="margin-right: 5px;">'+
							   '<span class="text" style="width: 60px;" style="cursor: pointer">确定</span>'+
							   '<b class="bgR"></b></a>'+
							 '<a class="btnGray btn fl" onclick="cancelAddNew(this)">'+
							 '<span class="text" style="width: 60px;" style="cursor: pointer">取消</span>'+
							 '<b class="bgR"></b></a>'+
						 '</td>';

		</script>
	</body>
</html>