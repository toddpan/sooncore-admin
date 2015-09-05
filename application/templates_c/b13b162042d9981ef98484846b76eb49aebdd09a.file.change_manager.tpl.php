<?php /* Smarty version Smarty-3.1.18, created on 2015-07-31 20:22:32
         compiled from "application\views\filiale\change_manager.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2081755bb68883afa40-89503744%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b13b162042d9981ef98484846b76eb49aebdd09a' => 
    array (
      0 => 'application\\views\\filiale\\change_manager.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2081755bb68883afa40-89503744',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'country_code_arr' => 0,
    'country' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55bb68884fdf75_00043439',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55bb68884fdf75_00043439')) {function content_55bb68884fdf75_00043439($_smarty_tpl) {?><div class="pop">
<title>变更管理员</title>
	<div class="popTitle">变更管理员<a  class="close"></a></div>
	<div class="Popbox">
    <div class="PopMain">
<!--<h4 align="left" class="shenpiTitle"  style="padding-left:50px;font-family:Arial;font-size:16px;font-weight:bold;font-style:normal;text-decoration:none;color:#333333;">变更管理员</h4>-->
<div class="setManTable" id="change_select">
	<div style="color:red;display:none" id="error_tip">
		<label >请选择一种创建方式
		</label>
	</div>
    <table class="buildNewcompany">
		<tr>
			<td class="left"  width="35%"  colspan="3">
				
					<label class="radioBox" id="first"><input type="button"/>从现有用户挑选
					</label>
			</td>			
			<td width="15%" >
				<div align="left">
					<input name="text" type="text"  class="textii" value="" id="loginName"/>
				</div>
			</td>
			<td width="63%">
				<div align="left">（输入用户账号，然后回车）</div>
			</td>
		</tr>
	</table>
	<table width="342" class="buildNewcompany">
	<tr>
		<td class="left" colspan="3" >
			<label class="radioBox" id="second"><input type="button">不存在此用户，创建一个新的管理员
			</label>
		</td>
		<!--<td class="left" width="99%"><div align="left"></div></td>-->
	</tr>
	</table>
		<table class="buildNewcompany2">
    	<tr>
        	<td class="left" width="18%">姓氏：</td>
            <td><input type="text" class="textI w183" value="请输入姓氏" id="first_name"/></td>
            <td class="left">名字：</td>
            <td><input type="text" class="textI w183" value="请输入名称"  id="last_name"/></td>
        </tr>
        <tr>
        	<td class="left">显示名称：</td>
            <td colspan="3"><input type="text" class="textI w183" id="display_name"/> <span class="hui">如果显示名称不是姓氏+名字的组合，请重新填写</span></td>
        </tr>
        <tr>
        	<td class="left">手机号：</td>
            <td colspan="3">
				<div class="select w69" id="countrycode">
					<a >国码</a>
					<ul class="w69" id="country_code">
						<?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country_code_arr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value) {
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
							<li><?php echo $_smarty_tpl->tpl_vars['country']->value;?>
</li>
						<?php } ?>
					</ul>
				</div>
				<input type="text" class="textI w183 marl10" id="telephone"/></td>
        </tr>
        <tr>
        	<td class="left">固定电话：</td>
            <td colspan="3">
				<div class="select w69" id="countrycode1">
					<a >国码</a>
					<ul class="w69">
						<?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country_code_arr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value) {
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
							<li><?php echo $_smarty_tpl->tpl_vars['country']->value;?>
</li>
						<?php } ?>
					</ul>
				</div>
				<input type="text" class="textI w46" value="区号" id="city_code"/>
				<input type="text" class="textI w95" value="电话号码" id="phone"/>
				<input type="text" class="textI w46" value="分机号" id="extersion"/></td>
        </tr>
        
        <tr>
        	<td class="left">电子邮箱：</td>
            <td colspan="3">
				<input type="text" class="textI w183" id="email"/>
			</td>
        </tr>
        <tr>
        	<td class="left">部门名称：</td>
            <td><input type="text" class="textI w183" value="请输入部门名称" id="department_name"/></td>
            <td class="left">职位名称：</td>
            <td><input type="text" class="textI w183" value="请输入职位名称"  id="position"/></td>
        </tr>
        <tr>
        	<td class="left">指定管理员帐号：</td>
            <td colspan="3" ><input type="text" class="textI w183" id="login_name"/> @ <input type="text" class="textI w183" /> .quanshi.com</td>
        </tr>
        <tr>
        	<td class="left">&nbsp;  </td>
            <td colspan="3" class="hui">管理员初始化可使用这个使用者帐号登录，之后同步或导入公司员工信息后可改用公司定义的帐号</td>
        </tr>
    </table>
	
	</div>
	</div>
</div>
<div class="popFooter"><a onclick="save_change_manager()">变更完成</a><a onclick="hideDialog()" style="cursor:pointer">取消</a></div>
</div>
<script type="text/javascript" src="public/js/filiale/change_manager.js"></script>
<script type="text/javascript">

</script><?php }} ?>
