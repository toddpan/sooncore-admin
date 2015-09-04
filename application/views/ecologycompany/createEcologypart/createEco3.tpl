<div class="ldapSetBox" style="display:none" target="3" id="creater_three">
		<dl class="ldapSetCont">
			<dd style="padding: 10px;">
				<table class="infoTable">
					<tr>
						<td width="50">姓：</td>
						<td colspan="3" id="chinese_name">
							<div class="inputBox fl" id="firstname">
								<b class="bgR"></b>
								<label class="label"></label>
								<input value="" style="width: 110px;" class="input">
							</div>
							<div style="padding: 0 20px" class="label fl">名：</div>
							<div class="inputBox" id="lastname">
								<b class="bgR"></b>
								<label class="label"></label>
								<input value="" style="width: 122px;" class="input">
							</div>
						</td>
                	</tr>
                	<tr>
						<td>帐号：</td>
						<td colspan="3" id="usercount">
							<div class="inputBox fl">
								<b class="bgR"></b>
								<label class="label">请输入用户手机号</label>
								<input value="" style="width: 160px;" class="input">
							</div> &nbsp;{$mail_suffix}
						</td>
               	 	</tr>
                	<tr>
						<td>性别：</td>
						<td colspan="3" id="sex">
							  <label class="radio radio_on">
								<input type="radio" id="xb_0" checked="checked" value="0" name="xb">先生</label>
							  <label class="radio">
								<input type="radio" id="xb_1" value="1" name="xb">女士</label>
						</td>
                	</tr>
					<tr>
						<td>职位：</td>
						<td colspan="3" id="status">
							<div class="inputBox w318">
								<b class="bgR"></b>
								<label class="label"></label>
								<input value="" class="input">
							</div>
						</td>
					</tr>
					<tr>
						<td>手机：</td>
						<td colspan="3" id="telephone_number">
							<div style="width: 100px;" class="combo selectBox" id="telephone_number_pre">
								<a  class="icon"></a>
								<span class="text" id="add_num_1">{$telephone}</span>
								<div class="optionBox">
									<dl class="optionList" style="height: 26px;"> 
										{foreach $telephone as $phone}
										<dd target="1" class="option 
										{if $phone==$telephone} selected {/if}" style="">{$phone}</dd>
										{/foreach}
									</dl>
								</div>
							</div>
							<div class="inputBox">
								<b class="bgR"></b>
								<label for="phoneNum" class="label">电话号码</label>
								<input name="cell_phone" value="" style="width: 204px;" id="phoneNum" class="input">
							</div>
						</td>
					</tr>
                	<tr>
						<td>办公地点：</td>
						<td colspan="4" id="location">
							<div class="inputBox w318">
								<a  class="icon"></a>
								<label class="text" id="add_conutry"></label>
								<input value="" class="input">
							</div>
                        </td>
					</tr>
					<tr>
						<td>邮箱：</td>
						<td colspan="4"  id="email">
							<div class="inputBox w318">
								<a  class="icon"></a>
								<label class="text"></label>
								<input value="" class="input">
							</div>
                        </td>
					</tr>
					<div id="self_label">
					{foreach from=$label_name key=key item=item}
						{$name=$item}
					<tr>
						<td>{$name}：</td>
						<td colspan="3" target_name="{$key}">
							<div class="inputBox fl"> 
								<b class="bgR"></b>
								<label class="label">
								{if $key=="email"}
									请输入接收通知的邮箱
								{/if}
								</label>
								<input name="notice_mail" class="input" style="width: 230px;" value="" />
							</div>
						</td>
					</tr>
					{/foreach}
					</div>
				</table>
			</dd>
		</dl>
</div>
<script type="text/javascript" src="public/js/part_js/createEco3.js"></script>
<script type="text/javascript">
//var company_ecol_id = '<?php echo $org_id ; ?>';
//var cost_get_staff = '<?php echo site_url('organize / get_next_orguser_list '); ?>'; //组织结构和成本中心部分的调入员工


</script>