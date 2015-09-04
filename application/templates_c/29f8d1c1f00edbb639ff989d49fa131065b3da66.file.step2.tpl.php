<?php /* Smarty version Smarty-3.1.18, created on 2015-07-06 23:25:34
         compiled from "application\views\ldap\step\step2.tpl" */ ?>
<?php /*%%SmartyHeaderCode:962559a9dee2132f0-90306967%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '29f8d1c1f00edbb639ff989d49fa131065b3da66' => 
    array (
      0 => 'application\\views\\ldap\\step\\step2.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '962559a9dee2132f0-90306967',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_559a9dee225de6_45538588',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_559a9dee225de6_45538588')) {function content_559a9dee225de6_45538588($_smarty_tpl) {?><!--步骤二：选择同步组织-->
<div class="ldapSetBox2" style="display:none" target="2">
    <dl class="ldapSetCont">
        <dt class="setTitle">请选择要同步的组织结构：</dt>
		<dt class="error2" style="color:#FF0000;display:none"></dt>
        <dd style="padding: 10px 0;">
            <div class="treeBox">
			<ul class="ztree" id="ldap_tree"></ul>
            </div>
        </dd>
    </dl>
</div>
<script type="text/javascript" src="public/js/part_js/ldap2.js"></script>
<script type="text/javascript">
</script><?php }} ?>
