<?php /* Smarty version Smarty-3.1.18, created on 2015-08-22 21:32:14
         compiled from "application\views\public\part\ecologycompanyinfo.tpl" */ ?>
<?php /*%%SmartyHeaderCode:831955d879deb50ec6-61919849%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '55e8f2846c8150abc9e05ddadc6c75c422ae5f80' => 
    array (
      0 => 'application\\views\\public\\part\\ecologycompanyinfo.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '831955d879deb50ec6-61919849',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'company_name' => 0,
    'company_english' => 0,
    'company_chinese' => 0,
    'telephoneNum' => 0,
    'country_location' => 0,
    'company_introduce' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d879deec6029_87037144',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d879deec6029_87037144')) {function content_55d879deec6029_87037144($_smarty_tpl) {?><div class="cont-wrapper">
<ul class="infoNav">
	<li class="selected">企业信息</li>
</ul>
<dl class="infoCont">
	<dd>

	<table class="infoTable">
		<tr>
			<td class="tr">企业名称：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['company_name']->value;?>
</span></td>
		</tr>
		<tr>
			<td class="tr">英文简称：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['company_english']->value;?>
</span></td>
		</tr>
		<tr>
			<td class="tr">中文简称：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['company_chinese']->value;?>
</span></td>
		</tr>
		<tr>
			<td class="tr">联系电话：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['telephoneNum']->value;?>
</span></td>
		</tr>
		<tr>
			<td class="tr">国家/地区：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['country_location']->value;?>
</span></td>
		</tr>
		<tr>
			<td class="tr">公司介绍：</td>
			<td><span class="infoText dotEdit"><?php echo $_smarty_tpl->tpl_vars['company_introduce']->value;?>
</span>
			</td>
		</tr>

	</table>
	</dd>

</dl>
</div><?php }} ?>
