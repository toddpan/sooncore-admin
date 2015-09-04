<!--设置账号规则-->
<div class="ldapSetBox5" style="display:none" target="5">
<script type="text/javascript">
$(function(){
	//TODO 此处用于处理如果是修改LDAP信息时，用户与组织对应关系的显隐设置
});

$('.radioBox label.radio').click(function()
	{ 
 	 $(this).siblings().removeClass("radio_on checked");
 	 $(this).addClass("radio_on checked");
	});
//选择ldap映射关系处理
$(function(){
	$('#default').change(function(){
		$('.made_style').hide();
		$('.mapping_area').hide();
		$('.sync_ldap_treeBox').hide();
	});
	$('#custom_made').change(function(){
		$('.made_style').show();
		$('.mapping_area').show();
		$('.sync_ldap_treeBox').show();
	});
	$('#made1').change(function(){
		$('.made_mapping2').hide();
	});
	$('#made2').change(function(){
		$('.made_mapping2').show();
	});
});
</script>
    <dl class="ldapSetCont">
    
    	<div class="ldapMapping" style="font-size:13px;">
			<dl>
				<dt class="setTitle" style="margin:0px 0 5px;">LDAP映射关系设置</dt>
				<dd class="radioBox" style="padding:5px 10px;margin-bottom:20px;">
					<label id="default" class="radio radio_on checked"><input type="radio" checked="checked" value='0' name="ldap" />默认</label>
					<label id="custom_made" class="radio"><input type="radio" value='1' name="ldap" />定制</label>
					
					<div class="made_style" style="display:none;margin-top:10px;">
						<p class="setTitle" style="margin:0px 0 5px;">选择定制类型</p>
						<div class="radioBox">
							<label id="made1" class="radio radio_on checked"><input type="radio" checked="checked" value='0' name="custom_made" />定制1（一一对应）</label>
							<label id="made2" class="radio"><input type="radio" value='1' name="custom_made" />定制2（使用中间表对应）</label>
						</div>
					</div>
				
					<table class="mapping_area infoTable" style="display:none;">
						<thead class="made_mapping1 mappingInputBox">
							<tr class="mar10">
								<td class="wid200">用户属性：</td>
								<td><input class="userAttribute input_area" /></td>
							</tr>
							<tr class="mar10">
								<td class="wid200">组织属性：</td>
								<td><input class="orgAttribute input_area" /></td>
							</tr>
						</thead>
						<tbody class="made_mapping2 mappingInputBox" style="display:none;">
							<tr class="mar10">
								<td class="wid200">关系类型的objectClass：</td>
								<td><input class="objectClass input_area" /></td>
							</tr>
							<tr class="mar10">
								<td class="wid200">查找范围：</td>
								<td><input class="searchBase input_area" /></td>
							</tr>
							<tr class="mar10">
								<td class="wid200">查找条件：</td>
								<td><input class="searchFilter input_area" /></td>
							</tr>
							<tr class="mar10">
								<td class="wid200">关联用户属性：</td>
								<td><input class="joinUserAttribute input_area" /></td>
							</tr>
							<tr class="mar10">
								<td class="wid200">关联组织属性：</td>
								<td><input class="joinOrgAttribute input_area" /></td>
							</tr>
						</tbody>
					</table>
					<div class="sync_ldap_treeBox" style="display:none;margin-top:20px;">
						<p class="setTitle" style="margin:0px 0 5px;">对于非标准的ldap需要勾选查找同步员工的组织</p>
						<ul class="ztree" id="sync_ldap_tree"></ul>
		            </div>
				</dd>
			</dl>
		</div>
    
    	<dt class="setTitle" style="margin:0px 0 5px;">请设置蜜蜂账号</dt>
    	<dt class="error-text error5" style="color:#FF0000;display:none"></dt>
        <dd style=" margin-bottom: 10px;">
            <table class="infoTable">
                <tr>
                    <td width="176">
                        <div class="" style="width:169px;border:none;">
                            {if $use_suffix eq 0}
                            <span class="text selected" id='Select_div' name="{$ldap_id}">使用邮箱作为蜜蜂帐号</span>
                            {/if}
                            {if $use_suffix eq 1}
                            <span class="text selected" id='Select_div' name="{$ldap_id}">指定统一的标签作为帐号前缀</span>
                            {/if}
                        </div>
                    </td>
                    <td>
                   	 	<div class="select-option">
                            <div class="combo selectBox w318" style="width: 330px;float:left;margin-right:10px;">
                                <a class="icon" ></a>
                                <input class="step5Input text" value="选择标签" id='select_tag' />
                                <div class="optionBox">
                                    <dl class="optionList ldap5_select" >
                                    </dl>
                                </div>
                            </div>
                            {if $use_suffix eq 1}
                            <p class="site_domain" style="float:left;">{$site_domain}</p>
                            {/if}
                        </div>
                    </td>
                </tr>
            </table>
        </dd>
        <dd style="border: none; background: none; margin-bottom: 15px;">
			<label class="del_ldap checkbox checked">
				<input name="" type="checkbox" checked="checked" value="" />同步后，如果在 LDAP 找不到用户信息立即停用并删除
			</label>
		</dd>
        <dt class="setTitle" style="margin:30px 0 5px;">请输入不用开通蜜蜂帐号的例外规则</dt>
        <dd class="addRule">
            <table class="infoTable">
            {if $rule[0]}
            	{foreach $rule as $name}
            	<tr>
					<td width="326">{$name}</td>
					<td>
						<a class="btnGray btn" onclick="editOrder(this)">
							<span class="text"> 编辑 </span>
							<b class="bgR"></b>
						</a>
						<a id="addOrderCancel" class="btnGray btn" onclick="deleteOrder(this)">
							<span class="text"> 删除 </span>
							<b class="bgR"></b>
						</a>
					</td>
				</tr>
				{/foreach}
			{else}
                <tr class="hide_word" style="display: none">
                    <td width="326">
                        <div class="inputBox w318">
                            <label class="label" for="orderValue">您可以写入这样一个规则 OU=labourer</label>
                            <input class="input" id="orderValue" value="" />
                        </div>
                    </td>
                    <td>
                    	<a class="btnBlue yes" onclick="addOrderSuccess(this)">
							<span class="text">&nbsp;确定&nbsp;</span>
							<b class="bgR"></b>
						</a>&nbsp;
                    	<a class="btnGray btn" onclick="cancelAddOrder()">
							<span class="text">&nbsp;取消&nbsp;</span>
							<b class="bgR"></b>
						</a>
                    </td>
                </tr>
               {/if}
                <tr class="noOrder">
                    <td colspan="2" >
                     {if !$rule[0]}
						<span tagert="1">无规则</span>
					{/if}
						<a id="addOrder" class="link" onclick="addOrder()">增加规则</a>
					</td>
                </tr>
            </table> 
        </dd>
    </dl>
    <input id="updateLdapName" type="hidden" value="{if $confName}{$confName}{/if}" />
</div>
<script type="text/javascript" src="public/js/part_js/ldap5.js"></script>
