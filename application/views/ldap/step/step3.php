<!--指定员工信息-->
<div class="ldapSetBox3" style="display:none" target="3">
	<div class="ldapSetStep">
    	<a  class="selected">1. 连接LDAP服务器<b class="arrow"></b></a>
    	<a  class="selected">2. 选择同步的组织<b class="arrow"></b></a>
    	<a  class="selected current">3. 指定员工信息<b class="arrow"></b></a>
    	<a >4. 选择同步的员工信息<b class="arrow"></b></a>
    	<a >5. 设置帐号规则<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:60%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
    <dl class="ldapSetCont">
        <dt class="setTitle">请选择代表员工的标签</dt>
		<dd class="error-text error3" style="padding: 10px;"></dd>
        <dd style="padding: 10px;">
        </dd>
    </dl>
    
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('main/mainPage');?>','main');"><span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn"><span class="text" onclick="$('.ldapSetBox3').hide();$('.ldapSetBox2').show();"
		style="cursor: pointer" >上一步</span><b class="bgR"></b></a>
	<a class="btnBlue yes"><span class="text" onclick="nextStep3();" style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking" class="checking3" style="display:none">
	<span>验证设置中，请稍候...</span>
</div>
