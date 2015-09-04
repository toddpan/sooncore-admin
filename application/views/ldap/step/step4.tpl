<!--选择同步的员工信息-->
<div class="ldapSetBox4" style="display:none" target="4">
    <dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">必选的员工标签</dt>
        <dt class="error4" style="color:#ec6764;display:none"></dt>
        <table class="infoTable">
				<tr>
					<td width="112" name="lastnameAttribute">姓氏</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon"></a>
							<input class="attrInput text" value="请选择对应的LDAP信息" />
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="firstnameAttribute">名字</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<input class="attrInput text" value="请选择对应的LDAP信息" />
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="positionAttribute">职位</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<input class="attrInput text" value="请选择对应的LDAP信息" />
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td width="112" name="idAttribute">ldap用户唯一标识</td>
					<td>
						<div class="combo selectBox" style="width:340px">
							<a class="icon" ></a>
							<input class="attrInput text" value="请选择对应的LDAP信息" />
							<div class="optionBox">
								<dl class="optionList" style="overflow:scroll">
								</dl>
							</div>
						</div>
					</td>
				</tr>  
				{$i=0}
				{foreach from=$ldap_relative  key=key item=item}
					<tr>
						<td width="112" name="{$key}">{$item}：</td>
						<td>
							<div class="combo selectBox" style="width:340px">
								<a class="icon" ></a>
								<input class="attrInput text" value="请选择对应的LDAP信息" />
								<div class="optionBox">
									<dl class="optionList" style="overflow:scroll">
									</dl>
								</div>
							</div>
						</td>
					</tr>
					{$i=$i+1}
				{/foreach}
            </table>
    </dl>
</div>
<script type="text/javascript" src="public/js/part_js/ldap4.js"></script>
<script type="text/javascript"></script>