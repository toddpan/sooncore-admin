<?php /* Smarty version Smarty-3.1.18, created on 2015-07-06 23:25:34
         compiled from "application\views\ldap\step\step4.tpl" */ ?>
<?php /*%%SmartyHeaderCode:778559a9dee2772c9-60333716%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '91781d0b566dba3ffb7d3f669b7b5d5620132625' => 
    array (
      0 => 'application\\views\\ldap\\step\\step4.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '778559a9dee2772c9-60333716',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'ldap_relative' => 0,
    'key' => 0,
    'item' => 0,
    'i' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_559a9dee3ed4d4_66216075',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559a9dee3ed4d4_66216075')) {function content_559a9dee3ed4d4_66216075($_smarty_tpl) {?><!--选择同步的员工信息-->
<div class="ldapSetBox4" style="display:none" target="4">
    <dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">必选的员工标签</dt>
        <dt class="error-text error4" style="color:#FF0000;display:none"></dt>
        <table class="infoTable">
				<tr>
					<td width="112" name="lastnameAttribute">姓</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon"></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="firstnameAttribute">名</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="genderAttribute">性别</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="positionAttribute">职位</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="mobileAttribute">手机</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr> 
				<tr>
					<td width="112" name="idAttribute">ldap用户唯一标识</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<span id="4-0-0" class="text">
							请选择对应的LDAP信息
							</span>
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>  
				<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable(0, null, 0);?>
				<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['ldap_relative']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
					<tr>
						<td width="112" name="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
：</td>
						<td>
							<div class="combo selectBox" style="width:340px">
								<a class="icon" ></a>
								<span id="4-0-0" class="text">
								请选择对应的LDAP信息
								</span>
								<div class="optionBox">
									<dl class="optionList" style="overflow:scroll">
									</dl>
								</div>
							</div>
						</td>
					</tr>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_variable($_smarty_tpl->tpl_vars['i']->value+1, null, 0);?>
				<?php } ?>
            </table>
    </dl>
</div>
<script type="text/javascript" src="public/js/part_js/ldap4.js"></script>
<script type="text/javascript"></script><?php }} ?>
