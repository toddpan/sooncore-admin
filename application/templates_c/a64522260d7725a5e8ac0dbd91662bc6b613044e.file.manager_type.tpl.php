<?php /* Smarty version Smarty-3.1.18, created on 2015-07-05 18:52:52
         compiled from "application/views/manage/manager_type.tpl" */ ?>
<?php /*%%SmartyHeaderCode:139210930455990c84e98fd0-35324944%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a64522260d7725a5e8ac0dbd91662bc6b613044e' => 
    array (
      0 => 'application/views/manage/manager_type.tpl',
      1 => 1429669986,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '139210930455990c84e98fd0-35324944',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'v' => 0,
    'page_text' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55990c850c7f01_28811364',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55990c850c7f01_28811364')) {function content_55990c850c7f01_28811364($_smarty_tpl) {?><div class="infor_page">
<table class="table" id="self_staff">
    <thead>
        <tr>
            <th width="6%"><?php if (!is_string($_smarty_tpl->tpl_vars['data']->value)) {?><span class="checkbox"><input type="checkbox" /></span><?php }?></th>
            <th width="10%" style="text-align: left; text-indent: 24px">姓名</th>
            <th>角色</th>
            <th>蜜蜂帐号</th>
            <th>手机</th>
            <th>上次登录时间</th>
        </tr>
    </thead>
    <tbody>
    	<?php if (is_string($_smarty_tpl->tpl_vars['data']->value)) {?>
    		<tr>
    			<td></td>
    			<td><?php echo $_smarty_tpl->tpl_vars['data']->value;?>
</td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    		</tr>
    	<?php } else { ?>
        <?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value) {
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
            <tr>
                <td><span class="checkbox"><input type="checkbox" /></span></td>
                <td class="tl"><a target="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" class="userName manage ellipsis" onclick="adminstaff_infor(this,<?php echo $_smarty_tpl->tpl_vars['v']->value['user_id'];?>
,<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
)"><?php echo $_smarty_tpl->tpl_vars['v']->value['display_name'];?>
</a></td>
                <td><?php echo $_smarty_tpl->tpl_vars['v']->value['role'];?>
</td>
                <td class="tl"><span class="ellipsis"><?php echo $_smarty_tpl->tpl_vars['v']->value['login_name'];?>
</span></td>
                <td><?php echo $_smarty_tpl->tpl_vars['v']->value['mobile_number'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['v']->value['last_login_time'];?>
</td>
            </tr>
        <?php } ?>
        <?php }?>
    </tbody>
</table>
<div class="page" <?php if ($_smarty_tpl->tpl_vars['page_text']->value=='') {?>style="display:none"<?php }?>>
    <?php echo $_smarty_tpl->tpl_vars['page_text']->value;?>

</div>
</div>

<script type="text/javascript">

function adminstaff_infor(t,user_id,id)
    {
		if($(t).hasClass("false"))
		{
			return;
		}
		$(t).addClass("false");
		var _this=$(t);
		$('.contTitle02').hide();
		$('.infor_page').hide();
        var path_staff_information='manager/managerInfoPage';
        var obj={
			"user_id":user_id,
			"id":id
        }
        $.post(path_staff_information,obj,function(data)
        {
			$('#ri_admin .contHead').after(data);
			_this.removeClass("false");
		});
    }
</script>
<?php }} ?>
