<?php /* Smarty version Smarty-3.1.18, created on 2015-07-23 19:48:38
         compiled from "application\views\filiale\create_filiale.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2123455b0d4962a8f39-08192224%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e8e0996f36be6ec6f3b34fadb94856a7e769a8a' => 
    array (
      0 => 'application\\views\\filiale\\create_filiale.tpl',
      1 => 1434809345,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2123455b0d4962a8f39-08192224',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'cor_info_arr' => 0,
    'cor_info' => 0,
    'country_name_arr' => 0,
    'country' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55b0d4966e6497_23710511',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55b0d4966e6497_23710511')) {function content_55b0d4966e6497_23710511($_smarty_tpl) {?>
<div class="setManagemain">
  <div class="setManagebox">
      <div class="setManTitle">公司信息</div>
      <div class="setManTable">
      <table>
        <tr>
			<td class="">子公司名称：</td>
			<td colspan="3" >
				<input type="text" class="textI" id="filiale_name"/>
			</td>
        </tr>
        <tr>
            <td class="left">上级企业：</td>
             <td colspan="3">
			 	<div class="select-box hide" id="parent_site_id">
				<input style="z-index:2" cl_id="part1" type="" class="textI" value="" placeholder="请选择公司" id="company" onFocus="showMenu(this);"/>
					<a class="icon" cl_id="part1" onClick="showMenu(this);"></a>
					<div class="selectOptionBox"   target='0'  style="display: none;z-index:9;width: 489px;">
						<ul class="ztree" id="ztree">
						<?php  $_smarty_tpl->tpl_vars['cor_info'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cor_info']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cor_info_arr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cor_info']->key => $_smarty_tpl->tpl_vars['cor_info']->value) {
$_smarty_tpl->tpl_vars['cor_info']->_loop = true;
?>
							<li user_id="<?php echo $_smarty_tpl->tpl_vars['cor_info']->value['site_id'];?>
" org_id="<?php echo $_smarty_tpl->tpl_vars['cor_info']->value['id'];?>
" ><?php echo $_smarty_tpl->tpl_vars['cor_info']->value['name'];?>
</li>
						<?php } ?>
						</ul>
					</div>
				</div></td>
        </tr>
      	<tr>
        	<td class="">公司所在国家:</td>
            <td>
				<div class="select" id="select_country">
					<a onclick="show_menu(this)">请选择国家名称</a>
					<ul style="display:none" class="">
					<?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country_name_arr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value) {
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
						<li><?php echo $_smarty_tpl->tpl_vars['country']->value;?>
</li>
					<?php } ?>
					</ul>
				</div>
			</td>
           <td class="left">省份：</td>
           <td>
			   <div class="select" id="province">
				<a onclick="show_menu(this)">请选择</a>
				<ul style="display:none">
					<li>北京市</li>
					<li>天津市</li>
					<li>河北省</li>
				</ul>
			</div>
			</td>
        </tr>
        <tr>
            <td class="left">乡镇地区：</td>
            <td><input type="text" class="textI w183" id="city"/></td>
            <td class="left">地址：</td>
            <td colspan="3"><input type="text" class="textI" id="address"/></td>
        </tr>
        <tr>
        	<td class="left">公司网址：</td>
            <td colspan="3"><input type="text" class="textI" id="cor_site_url"/> <span class="hui">例如：www.abc.com.cn</span></td>
        </tr>
		 <tr>
        	<td class="left">全时站点：</td>
            <td colspan="3"><input type="text" class="textI" id="site_url"/> <span class="hui">例如：abc.quanshi.com</span></td>
        </tr>
      </table>
      </div>
      <div class="setManTitle">公司管理方式</div>
      <div class="setManTable">
      <table>
        <tr>
        	<td class="left" width="130">公司形态：</td>
            <td colspan="3">
				<label class="radioBox company" >
					<input type="button">子公司
				</label>
				<label id="error1" style="display:none;color:red">请选择公司形态</label>
			</td>
        </tr>
		<tr>
        	<td class="left" width="130"></td>
            <td colspan="3"><label class="radioBox group"><input type="button">子集团</label></td>
        </tr>
		<tr>
        	<td class="left" width="130" style="left:100px"></td>
            <td colspan="3">
			 <div class="setManagePop" style="float:left">
				<dl>
                	<dd >管理方式：</dd>
                     <dd id="ddLast"><label class="radioBox focus">
							<input type="button" />集中管理
						</label>
						
						<label class="radioBox disperse">
							<input type="button"/>分散式管理
						</label>
					</dd>
                </dl>				
               
             </div> 
			</td>
        </tr>
		<tr>
        	<td class="left" width="130" style="left:100px">员工创建方式：</td>
            <td colspan="3">				           	                	
                    <dd id="ddFirst">
					<label class="radioBox ldap">
							<input type="button" />LDAP同步
					</label>
					<label class="radioBox bath">
							<input type="button" />批量导入
					</label>
					<label id="error2" style="display:none;color:red">请选择员工创建方式</label>						
					</dd>               				
			</td>
        </tr>
      </table>
	  </div>
	  <div class="footer">创想空间商务通信服务有限公司<span>/G-Net Integrated Services Co., Ltd.</span> 客服热线: <span>400-810-1919 Email: service@quanshi.com</span></div>
 </div>
<script type="text/javascript">
function show_menu(t)
{
	$(t).next().show();
}
$(function(){
	$(".topMenuMain li").hover(function(){
		$(this).addClass("current");
		},
	function(){
		$(this).removeClass("current");
		});
		
	$("#ddFirst input").click(function(){
		$("#ddFirst input").removeClass("checked");
		$(this).addClass("checked");
	});
	
	$("#ddLast input").click(function(){
		if(!$(this).parent().hasClass("disabled"))
			{
				$("#ddLast input").removeClass("checked");
				$(this).addClass("checked");
			}
	});
	
	/*$(".select a").click(function(){
		$(".select ul").show();
	});*/
	$(".select ul li").click(function(){
		$(this).parent().prev().html($(this).text()).css('color','#000');
		$(this).parent().find("li.selected").removeClass("selected");
		$(this).addClass("selected");
		
		
	});
	$("#ztree li").click(function(){
		$("#parent_site_id input").val($(this).text()).css('color','#000');
		$(this).parent().find("li.selected").removeClass("selected");
		$(this).addClass("selected");
		$(this).parent().parent().hide();
	});
	$(document).click(function(e)
	{
		//alert(111)
		var t=$(e.target);
		if(!t.hasClass("select"))
		{
			$('.select').find("ul").removeClass("open").css("display","none");	
		}
	})
	$('.select').toggle(function()
	{
		//alert(222)
		if($(this).find("ul").hasClass("open"))
		{
			//alert(1)
			$(this).find("ul").removeClass("open").css("display","none");
		}
		else
		{
			//alert(2)
			$(this).find("ul").addClass("open").css("display","block");
		}
	},function()
	{
		if($(this).find("ul").hasClass("open"))
		{
			//alert(3)
			$(this).find("ul").removeClass("open").css("display","none");
		}
		else
		{
			//alert(4)
			$(this).find("ul").addClass("open").css("display","block");
		}
	})
	$("ul li").mouseenter(function()
	{
		$(this).addClass("enter");
	}).mouseleave(function()
	{
		$(this).removeClass("enter");
	})
	
	$(".radioBox input").click(function(){
		
		if($(this).parent().parent().hasClass("disabled"))
		{			
			return;
		}
			//$(".radioBox input").removeClass("checked");
			$(this).addClass("checked");
			if($(this).parent().hasClass('company'))
			{
				$('.group input').removeClass("checked")
				$('#ddLast').addClass("disabled");
				$('#ddLast .checked').removeClass("checked")
				
			}
			else if($(this).parent().hasClass('group'))
			{
				$('.company input').removeClass("checked")
				$('#ddLast').removeClass("disabled");
				
			}
			else if($(this).parent().hasClass('focus'))
			{
				
				$('.disperse input').removeClass("checked")
			}
			else if($(this).parent().hasClass('disperse'))
			{
				
				$('.focus input').removeClass("checked")
			}
			else if($(this).parent().hasClass('ldap'))
			{
				$('.bath input').removeClass("checked")
			}
			else if($(this).parent().hasClass('bath'))
			{
				$('.ldap input').removeClass("checked")
			}
		//alert($(this).parent().text())
		/*if($(this).parent().text()=="子公司")
		{
			//alert(1)
			$('.setManagePop dl:eq(1) label:eq(1)').addClass("disabled");
			$('.setManagePop dl:eq(1) label:eq(0) input').addClass("checked");
		}
		else
		{
			//alert(2)
			$('.setManagePop dl:eq(1) label:eq(0) input').removeClass("checked");
			$('.setManagePop dl:eq(1) label:eq(1)').removeClass("disabled");
		} */
	});
	$("#cor_site_url").blur(function()
	{
		var text=$(this).val();
		if(text=="")
		{
			return;
		}
		var split_address=text.split(".");
		var value=''+split_address[1]+'.quanshi.com/uc';
		$("#site_url").val(value);
	})	
})
</script>

<?php }} ?>
