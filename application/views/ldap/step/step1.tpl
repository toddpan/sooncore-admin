<!--步骤一：连接服务器-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span>
	<span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetStep " id="head_label">
    	<a  class="selected current">1. 连接LDAP服务器
			<b class="arrow"></b>
		</a>
    	<a >2. 选择同步的组织
			<b class="arrow"></b>
		</a>
    	<a >3. 指定员工信息
			<b class="arrow"></b>
		</a>
    	<a >4. 选择同步的员工信息
			<b class="arrow"></b>
		</a>
    	<a>5. 设置帐号规则
			<b class="arrow"></b>
		</a>
        <div class="bar">
        	<div class="innerBar" style="width:20%;">
                <b class="ibgL"></b>
				<b class="ibgR"></b>
            </div>
            	<b class="bgL"></b>
				<b class="bgR"></b>
        </div>
</div> 
<div class="ldapSetBox1" target="1">
	<span class="error1" style="margin-left:160px;color:#FF0000;display:none"></span>
	<table class="infoTable">
	     <tbody>
		 	<tr>
				<td width="160"></td>
				<td height="10" class=""></td>
		 	</tr>
		 	<tr> 
				<td width="160">服务器类型：</td>
				<td>
					<div id="servertype" class="combo selectBox w318" id="servertype">
						<a class="icon"></a>
						<span title="" class="text">{$select_step1['serverType']}</span>
						<div class="optionBox">
							<dl class="optionList" style="height: 130px;">
								{$j=0}
								{$select="selected"}
								{foreach $servertype as $type}
									<dd class="option 
										{if $select_step1['serverType']== $type}{$select}{/if}" target="{$j}">{$type}
									</dd>
								{$j=$j+1}
								{/foreach}                      									                     
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">连接方式：</td>
				<td>
					<div id="protocol" class="combo selectBox w318" id="protocol">
						<a class="icon"></a>
						<span title="" class="text">{$select_step1['protocol']}</span>
						<div class="optionBox">
							<dl class="optionList" style="height: 78px;">
								{$j=0}
								{$sel="selected"}
								{foreach $authtype_name as $link_type}
									<dd class="option 
										{if $select_step1['protocol']== $link_type}{$sel}{/if}" target="{$j}" >{$link_type}
									</dd>
								{$j=$j+1}
								{/foreach}   
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器地址：</td>
				<td>
					<div id="hostname" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>                  
						<input type="" value="{if $select_step1['hostname']}{$select_step1['hostname']}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器端口：</td>
				<td>
					<div id="port" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>            
						<input type="" value="{if $select_step1['port']}{$select_step1['port']}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器用户名：</td>
				<td>
					<div id="admindn" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>   
					   <input type="" value="{if $select_step1['admindn']}{$select_step1['admindn']}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器密码：</td>
				<td>
					<div id="password" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>   
						<input type="password" value="{if $select_step1['password']}{$select_step1['password']}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">Base DN：</td>
				<td>
					<div id="basedn" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>             
						<input type="" value="{if $select_step1['basedn']}{$select_step1['basedn']}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织objectClass：</td>
				<td>
					<div id="orgObjectclasses" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>                  
						<input type="" value="{if $select_step1[orgObjectclasses]}{$select_step1[orgObjectclasses]}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织ID：</td>
				<td>
					<div id="orgidproperty" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>            
						<input type="" value="{if $select_step1[orgidproperty]}{$select_step1[orgidproperty]}{/if}" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">组织名称：</td>
				<td>
					<div id="orgNameProperty" class="inputBox w318">
						<b class="bgR"></b>
						<label class="label"></label>           
						<input type="" value="{if $select_step1[orgNameProperty]}{$select_step1[orgNameProperty]}{/if}" class="input">
					</div>
				</td>
			</tr>
		 </tbody>
	</table>
</div>
<div class="toolBar2" id="back_next">
    	<a class="btnGray btn fl" onclick="loadPage('main/mainPage','main');">
			<span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b>
		</a>
		<a class="btnGray btn" onclick="back_step(this)" style="display:none">
			<span class="text" style="cursor: pointer">上一步</span>
			<b class="bgR"></b>
		</a>
		<a class="btnBlue yes ldapStepNext">
			<span id="idd" class="text" onclick="nextStep(this);" style="cursor: pointer">下一步</span>
			<b class="bgR"></b>
		</a>
</div>
<div id="checking"  style="display:none">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript" src="public/js/part_js/ldap1.js"></script>

<script type="text/javascript">
function nextStep(t)
{
	var pre=$(t).parent().parent().prev().attr("target");
	var ind=pre;
	var num='nextStep'+ind;
	eval(num+"()");
}
function back_step(t)
{
	if($(t).parent().prev().attr("target")==2)
	{
		var prev_page=$(t).parent().prev().prev().prev();
	}
	else
	{
		var prev_page=$(t).parent().prev().prev();
	}
	var current_page=$(t).parent().prev();
	var head=$('#head_label');
	var tom=$(t).parent();
	current_page.hide();
	$('#idd').text("下一步");
	prev_page.show();
	var ind=prev_page.attr("target");
	ind=ind-1;
	var len=20+ind*20;
	head.find(".innerBar").css('width',len+'%');
	head.find("a").removeClass("selected");
	head.find("a").removeClass("current");
	head.find('a:eq('+ind+')').addClass("selected");
	head.find('a:eq('+ind+')').addClass("current");
	var back_next=tom;
	tom.remove();
	prev_page.after(back_next);
	if(ind==0)
	{
		tom.find("a:eq(1)").hide();
	}
	
}
</script>