<?php /* Smarty version Smarty-3.1.18, created on 2015-07-06 23:25:33
         compiled from "application\views\ldap\step\step1.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14721559a9ded5de5e3-20422294%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5c7a545c1197d03d18ad0e8ebd4cb06c721afe02' => 
    array (
      0 => 'application\\views\\ldap\\step\\step1.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14721559a9ded5de5e3-20422294',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'select_step1' => 0,
    'servertype' => 0,
    'type' => 0,
    'select' => 0,
    'j' => 0,
    'authtype_name' => 0,
    'link_type' => 0,
    'sel' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_559a9dee130a83_01313075',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559a9dee130a83_01313075')) {function content_559a9dee130a83_01313075($_smarty_tpl) {?><!--步骤一：连接服务器-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span>
	<span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetStep " id="head_label">
    	<a  class="selected current">1. 连接LDAP服务器
			<b class="arrow"></b>
		</a>
    	<a >2. 选择同步的组织
			<b class="arrow"></b>
		</a>
    	<a >3. 指定员工信息
			<b class="arrow"></b>
		</a>
    	<a >4. 选择同步的员工信息
			<b class="arrow"></b>
		</a>
    	<a>5. 设置帐号规则
			<b class="arrow"></b>
		</a>
        <div class="bar">
        	<div class="innerBar" style="width:20%;">
                <b class="ibgL"></b>
				<b class="ibgR"></b>
            </div>
            	<b class="bgL"></b>
				<b class="bgR"></b>
        </div>
</div> 
<div class="ldapSetBox1" target="1">
	<span class="error1" style="margin-left:160px;color:#FF0000;display:none"></span>
	<table class="infoTable">
	     <tbody>
		 	<tr>
				<td width="160"></td>
				<td height="10" class=""></td>
		 	</tr>
		 	<tr> 
				<td width="160">服务器类型：</td>
				<td>
					<div id="servertype" class="combo selectBox w318" id="servertype">
						<a class="icon"></a>
						<span title="" class="text"><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['serverType'];?>
</span>
						<div class="optionBox">
							<dl class="optionList" style="height: 130px;">
								<?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable(0, null, 0);?>
								<?php $_smarty_tpl->tpl_vars['select'] = new Smarty_variable("selected", null, 0);?>
								<?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['servertype']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value) {
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
									<dd class="option 
										<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['serverType']==$_smarty_tpl->tpl_vars['type']->value) {?><?php echo $_smarty_tpl->tpl_vars['select']->value;?>
<?php }?>" target="<?php echo $_smarty_tpl->tpl_vars['j']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['type']->value;?>

									</dd>
								<?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable($_smarty_tpl->tpl_vars['j']->value+1, null, 0);?>
								<?php } ?>                      									                     
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">连接方式：</td>
				<td>
					<div id="protocol" class="combo selectBox w318" id="protocol">
						<a class="icon"></a>
						<span title="" class="text"><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['protocol'];?>
</span>
						<div class="optionBox">
							<dl class="optionList" style="height: 78px;">
								<?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable(0, null, 0);?>
								<?php $_smarty_tpl->tpl_vars['sel'] = new Smarty_variable("selected", null, 0);?>
								<?php  $_smarty_tpl->tpl_vars['link_type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['link_type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['authtype_name']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['link_type']->key => $_smarty_tpl->tpl_vars['link_type']->value) {
$_smarty_tpl->tpl_vars['link_type']->_loop = true;
?>
									<dd class="option 
										<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['protocol']==$_smarty_tpl->tpl_vars['link_type']->value) {?><?php echo $_smarty_tpl->tpl_vars['sel']->value;?>
<?php }?>" target="<?php echo $_smarty_tpl->tpl_vars['j']->value;?>
" ><?php echo $_smarty_tpl->tpl_vars['link_type']->value;?>

									</dd>
								<?php $_smarty_tpl->tpl_vars['j'] = new Smarty_variable($_smarty_tpl->tpl_vars['j']->value+1, null, 0);?>
								<?php } ?>   
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器地址：</td>
				<td>
					<div id="hostname" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>                  
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['hostname']) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['hostname'];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器端口：</td>
				<td>
					<div id="port" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>            
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['port']) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['port'];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器用户名：</td>
				<td>
					<div id="admindn" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>   
					   <input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['admindn']) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['admindn'];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器密码：</td>
				<td>
					<div id="password" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>   
						<input type="password" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['password']) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['password'];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">Base DN：</td>
				<td>
					<div id="basedn" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>             
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value['basedn']) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value['basedn'];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织objectClass：</td>
				<td>
					<div id="orgObjectclasses" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>                  
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgObjectclasses']['index']]) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgObjectclasses']['index']];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织ID：</td>
				<td>
					<div id="orgidproperty" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>            
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgidproperty']['index']]) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgidproperty']['index']];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织名称：</td>
				<td>
					<div id="orgNameProperty" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>           
						<input type="" value="<?php if ($_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgNameProperty']['index']]) {?><?php echo $_smarty_tpl->tpl_vars['select_step1']->value[$_smarty_tpl->getVariable('smarty')->value['section']['orgNameProperty']['index']];?>
<?php }?>" class="input">
					</div>
				</td>
			</tr>
		 </tbody>
	</table>
</div>
<div class="toolBar2" id="back_next">
    	<a class="btnGray btn fl" onclick="loadPage('main/mainPage','main');">
			<span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b>
		</a>
		<a class="btnGray btn" onclick="back_step(this)" style="display:none">
			<span class="text" style="cursor: pointer">上一步</span>
			<b class="bgR"></b>
		</a>
		<a class="btnBlue yes ldapStepNext">
			<span id="idd" class="text" onclick="nextStep(this);" style="cursor: pointer">下一步</span>
			<b class="bgR"></b>
		</a>
</div>
<div id="checking"  style="display:none">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript" src="public/js/part_js/ldap1.js"></script>

<script type="text/javascript">
function nextStep(t)
{
	var pre=$(t).parent().parent().prev().attr("target");
	var ind=pre;
	var num='nextStep'+ind;
	eval(num+"()");
}
function back_step(t)
{
	if($(t).parent().prev().attr("target")==2)
	{
		var prev_page=$(t).parent().prev().prev().prev();
	}
	else
	{
		var prev_page=$(t).parent().prev().prev();
	}
	var current_page=$(t).parent().prev();
	var head=$('#head_label');
	var tom=$(t).parent();
	current_page.hide();
	$('#idd').text("下一步");
	prev_page.show();
	var ind=prev_page.attr("target");
	ind=ind-1;
	var len=20+ind*20;
	head.find(".innerBar").css('width',len+'%');
	head.find("a").removeClass("selected");
	head.find("a").removeClass("current");
	head.find('a:eq('+ind+')').addClass("selected");
	head.find('a:eq('+ind+')').addClass("current");
	var back_next=tom;
	tom.remove();
	prev_page.after(back_next);
	if(ind==0)
	{
		tom.find("a:eq(1)").hide();
	}
	
}
</script><?php }} ?>
