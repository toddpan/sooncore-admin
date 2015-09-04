<?php /* Smarty version Smarty-3.1.18, created on 2015-07-06 23:25:33
         compiled from "application\views\ldap\ldap1.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16761559a9ded3af9e5-75361024%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0222fabffcb147ebcc9792221d38b577f155d364' => 
    array (
      0 => 'application\\views\\ldap\\ldap1.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16761559a9ded3af9e5-75361024',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tag_base_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_559a9ded5bd264_50030236',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559a9ded5bd264_50030236')) {function content_559a9ded5bd264_50030236($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<base href="<?php echo $_smarty_tpl->tpl_vars['tag_base_url']->value;?>
"/>
		<base target="_blank" />
		<title>云企管理中心</title>
	</head>
	<body>
		<?php echo $_smarty_tpl->getSubTemplate ("ldap/step/step1.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("ldap/step/step2.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("ldap/step/step3.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("ldap/step/step4.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<?php echo $_smarty_tpl->getSubTemplate ("ldap/step/step5.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/part_js/ldap1.js"></script>
		<script type="text/javascript" src="public/js/part_js/ldap2_tree.js"></script>
<!--		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>-->
	</body>
</html><?php }} ?>
