<!--设置账号规则-->
<div class="ldapSetBox5" style="display:none" target="5">
    <dl class="ldapSetCont">
    	<dt class="setTitle" style="margin:0px 0 5px;">请设置sooncore平台账号</dt>
    	<dt class="error-text error5" style="color:#FF0000;display:none"></dt>
        <dd style=" margin-bottom: 10px;">
            <table class="infoTable">
                <tr>
                    <td width="326">
                        <div class="combo selectBox w318" >
                            <a class="icon" ></a>
                            <span class="text selected" id='Select_div' name="{$ldap_id}">使用邮箱作为sooncore平台帐号</span>
                            <div class="optionBox">
                                <dl class="optionList" id="zhType">
                                    <dd class="option selected" target="1" onclick="ldapf_select(this)">使用邮箱作为sooncore平台帐号</dd>
                                    <dd class="option" target="2" onclick="ldapf_select(this)">指定统一的标签作为帐号前缀</dd>
                                </dl>
                            </div>
                        </div>
                    </td>
                    <td>
                   	 	<div class="select-option">
                            <div class="combo selectBox w318">
                                <a class="icon" ></a>
                                <span class="text selected" id='select_tag'>选择标签</span>
                                <div class="optionBox">
                                    <dl class="optionList ldap5_select" >
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <div class="select-option" style="display: none;" name="{$site_domain}">
                            <div class="combo selectBox" style="width: 250px;">
                                <a class="icon" ></a>
								<span id='select_two' style="width: 176px;" class="selected text">请选择</span>
                                <div class="optionBox">
                                    <dl class="optionList ldap5_select2">
                                    </dl>
                                </div>
                            </div>
                           {$site_domain}
                        </div>
                    </td>
                </tr>
            </table>
        </dd>
        <dd style="border: none; background: none; margin-bottom: 15px;">
			<label class="checkbox checked">
				<input name="" type="checkbox" checked="checked" value="" />同步后，如果在 LDAP 找不到用户信息立即停用并删除
			</label>
		</dd>
        <dt class="setTitle" style="margin:30px 0 5px;">请输入不用开通sooncore平台帐号的例外规则</dt>
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
</div>
<script type="text/javascript" src="public/js/part_js/ldap5.js"></script>
<script type="text/javascript">
	//$('#idd').text("完成设置");
</script>