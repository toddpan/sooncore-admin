
<dd class="qiye">
	<div class="toolBar2"><a class="btnGray btn btn_infoEdit"
		><span class="text">编辑信息</span><b class="bgR"></b></a>
	<a class="btnBlue btn_infoSave hide" ><span
		class="text">保存</span><b class="bgR"></b></a> <a
		class="btnGray btn btn_infoCancel hide" ><span
		class="text">取消</span><b class="bgR"></b></a></div>
	<table class="infoTable">
		<tr>
			<td class="tr">生态管理员：</td>
			<td><span class="infoText dotEdit">{$manager_name}</span> &nbsp; <a
				 onclick="change_admin()">更改</a></td>
		</tr>
		<tr>
			<td class="tr">企业名称：</td>
			<td><span class="infoText">{$name}</span>
			<div class="inputBox w360 hide"><b class="bgR"></b> <label
				class="label"></label> <input class="input"  id="company_name"  value="{$name}" /></div>
			</td>
		</tr>
		<tr>
			<td class="tr">简称：</td>
			<td><span class="infoText">{$abbreviation}</span>
			<div class="inputBox w360 hide"><b class="bgR"></b> <label
				class="label"></label> <input class="input"  id="company_chinese" value="{$abbreviation}" /></div>
			</td>
		</tr>
		<tr>
			<td class="tr">联系电话：</td>
			<td>
				<span class="infoText" >{$countryCode}</span>
				<div class="combo selectBox focusSelectBox hide" style="width: 100px; z-index: 3;">
					<a class="icon" ></a> 
					<span class="text selected" id="add_num">{$countryCode}></span>
					<div class="optionBox" style="display: none; width: 102px; left: -1px;">
						<dl class="optionList" style="height: 26px;">
							<!--<dd class="option selected" target="1" style="">+86</dd>-->
							
							{foreach $country_arr as $c_v} 
							<dd class="option {if $c_v.is_selected ==1}selected{/if}" target="{$c_i}">{$c_v.country_code}</dd> 
							
							{/foreach}
						</dl>
					</div>
				</div>
				<span class="infoText">{$areaCode}</span>
				<div class="inputBox hide">
					<b class="bgR"></b> 
					<label class="label" for="quhao" style="display: none">区号</label>
					<input class="input" id="area_code"  value="{$areaCode}" style="width: 72px;">
				</div>
				<span class="infoText">{$mobileNumber}</span>
				<div class="inputBox hide">
					<b class="bgR"></b> 
					<label class="label" for="phoneNum" style="display: none">电话号码</label>
					<input class="input" id="phoneNum_1" value="{$mobileNumber}" style="width: 262px;">
				</div>
			</td>
		</tr>
		<tr>
			<td class="tr">国家/地区：</td>
			<td><span class="infoText">{$country_location}</span>
			<div class="inputBox w360 hide"><b class="bgR"></b> <label
				class="label"></label> <input class="input" id="country_area" value="{$country_location}" /></div>
			</td>
		</tr>
		<tr>
			<td class="tr">公司介绍：</td>
			<td><span class="infoText">{$introduction}</span>
			<div class="hide"><textarea class="textarea"
				style="width: 600px; height: 80px;">{$introduction_textarea}</textarea></div>
			</td>
		</tr>

	</table>
</dd>
<script type="text/javascript">
var qiye_org_id = {$org_id};
</script>
<script type="text/javascript" src="public/js/part_js/ecologycompany_qiye.js"></script>