<?php /* Smarty version Smarty-3.1.18, created on 2015-08-19 22:43:25
         compiled from "application\views\manage\manager.tpl" */ ?>
<?php /*%%SmartyHeaderCode:82455d4960d9d0542-33655758%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82a951d7c81598bf0014bc736753bc24f83c13e6' => 
    array (
      0 => 'application\\views\\manage\\manager.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '82455d4960d9d0542-33655758',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'tag_base_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55d4960dc902b0_80397644',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55d4960dc902b0_80397644')) {function content_55d4960dc902b0_80397644($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<base href="<?php echo $_smarty_tpl->tpl_vars['tag_base_url']->value;?>
"/>
		<base target="_blank" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<!-- 管理员管理 -->
<div class="contHead" style="margin-bottom:20px;">
	<span class="title01">管理员管理</span>
	<div class="contHead-right"><div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)"></a></div>
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" id="search_admin"></a>
			<label class="label">请输入查询条件</label>
			<input class="input" id="search" />
		</div>
	</div>
    
    <ul class="menu" id="menu1">
            <li><a onclick="loadCont('tag/addTagPage' + '/0')">员工标签管理</a></li>
          <!--  <li><a onclick="loadCont('ldap/showLdapPage')">LDAP设置</a></li> -->
        </ul>
        
    </div>
</div>
<div class="contTitle02" style="margin-bottom:20px;">
    <a class="btn yes fl btnAddAdmin"><span class="text">新增管理员</span><b class="bgR"></b></a>
    <div class="editBtnBox fl" style="display:none;" id="dete_btn_admin">
        <a class="btnGray btn btnDeleAdmin" ><span class="text">移除管理员</span><b class="bgR"></b></a>
    </div>
    <div class="combo selectBox w130" style="float:right">
        <a class="icon" ></a>
        <span class="text selected">全部管理员</span>
        <div class="optionBox">
            <dl class="optionList" >
                <dd id="all_manger" class="option selected" target="0" onclick="select_manger_type(this)">全部管理员</dd>
                <dd class="option" target="1" onclick="select_manger_type(this)">系统管理员</dd>
                <dd class="option" target="2" onclick="select_manger_type(this)">组织管理员</dd>
                <dd class="option" target="3" onclick="select_manger_type(this)">员工管理员</dd>
                <dd class="option" target="4" onclick="select_manger_type(this)">帐号管理员</dd>
                
         <!--       <dd class="option" target="5" onclick="select_manger_type(this)">生态管理员</dd>
				<dd class="option" target="6" onclick="select_manger_type(this)">渠道管理员</dd> -->
            </dl>
        </div>
    </div>
</div>

<script type="text/javascript" src="public/js/part_js/manger.js"></script>
<script type="text/javascript" src="public/js/self_common.js"></script>
</body>
</html>
<?php }} ?>
