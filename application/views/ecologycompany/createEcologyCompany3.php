<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--创建生态企业3.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">创建生态企业</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep qystStep">
    	<a  class="selected">1. 填写生态企业信息<b class="arrow"></b></a>
    	<a  class="selected">2. 设置生态企业权限<b class="arrow"></b></a>
    	<a  class="selected current">3. 设置该企业管理员<b class="arrow"></b></a>
    	<a >4. 设置本方参与的用户<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:75%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
     <dl class="ldapSetCont">
        
        <dd style="padding: 10px;">
            <table class="infoTable">
                <tr>
                    <td width="50">姓：</td>
                    <td width="250">
                        <div class="inputBox">
                            <b class="bgR"></b>
                            <label class="label"></label>
                            <input class="input" id="father_name" style="width: 230px;" value="" />
                        </div>
                    </td>
                    <td width="50">名：</td>
                    <td><div class="inputBox">
                            <b class="bgR"></b>
                            <label class="label"></label>
                            <input class="input" id="self_name" style="width: 230px;" value="" />
                        </div></td>
                </tr>
                <tr>
                    <td>帐号：</td>
                    <td colspan="3">
                        <div class="inputBox fl">
                            <b class="bgR"></b>
                            <label class="label">请输入用户手机号</label>
                            <input class="input" id="account" style="width: 230px;" value="" />
                        </div> &nbsp;@quanshi.dadao.com
                    </td>
                </tr>
                <tr>
                    <td>性别：</td>
                    <td colspan="3">

                          <label  class="radio checked">
                            <input type="radio" name="xb" value="0" checked="checked" id="xb_0" />
                            先生</label>

                          <label  class="radio">
                            <input type="radio" name="xb" value="1" id="xb_1" />
                            女士</label>
                    
                        
                    </td>
                </tr>
                
                <tr>
                    <td>职位：</td>
                    <td colspan="3">
                        <div class="inputBox w318">
                            <b class="bgR"></b>
                            <label class="label"></label>
                            <input class="input" id="position" value="" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>手机：</td>
                    <td colspan="3">
                        <div class="combo selectBox" style="width: 100px;">
                    
                            <a class="icon" ></a>
                            <span class="text">+86</span>
                            <div class="optionBox">
                                <dl class="optionList"> 
                                    <dd class="option selected" target="1">+86</dd>
                                    
                                </dl>
                                <input type="hidden" id="telephoneNum" class="val" value="0" />
                            </div>
                        </div>
                      
                        <div class="inputBox" >
                            <b class="bgR"></b>
                            <label class="label" for="phoneNum">电话号码</label>
                            <input class="input" id="phoneNum"  style="width: 204px;" value="" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>邮箱：</td>
                    <td colspan="3">
                        <div class="inputBox w318">
                            <b class="bgR"></b>
                            <label class="label"></label>
                            <input class="input" id="email" value="" />
                        </div>
                    </td>
                </tr>
                <tr><td colspan="4"><span class="fl">办公地点：</span> <div class="combo selectBox" style="width: 100px;">
                    
                            <a class="icon" ></a>
                            <span class="text">中国</span>
                            <div class="optionBox">
                                <dl class="optionList"> 
                                    <dd class="option selected" target="1">中国</dd>
                                    <dd class="option" target="2">美国</dd>
                                    <dd class="option" target="3">德国</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                        <div class="inputBox" >
                            <b class="bgR"></b>
                            <label class="label"></label>
                            <input class="input"   id="location" style="width: 204px;" value="" />
                        </div>
                        </td></tr>
                <tr>
                    <td>邮箱：</td>
                    <td colspan="3">
                        <div class="inputBox w318">
                            <b class="bgR"></b>
                            <label class="label">请输入接收通知的邮箱</label>
                            <input class="input" id="mailBox"  value="" />
                        </div>
                    </td>
                </tr>
            </table>
        </dd>
    </dl>
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('ecologycompany/quitSetEcologyCompany')?>','main');"><span class="text" style="cursor: pointer" >放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ecologycompany/setEcologyCompany')?>');"><span class="text" style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue" ><span class="text"  onclick="nextStep()" style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">

function nextStep() {
    $('.inputBox').each(function()
	{
	   $(this).removeClass("error");
	})
    var isValid_father=valitateStaffName($("#father_name").val());
	var isValid_self=valitateStaffName($("#self_name").val());
	var isValid_account=valitateTelephonNum($('#account').val());
	var isValid_position=($("#position").val()!='')?true:false;
	var isValid_telephone=valitateTelephonNum($("#phoneNum").val());
	var isValid_location=($("#location").val()!='')?true:false;
	var isValid_mail=valitateStaffAccount($("#mailBox").val());
	var count=0;
	if(!isValid_father)
	{
	  $("#father_name").parent('div').addClass("error");
	  count++;
	}
	if(!isValid_self)
	{
	   $("#self_name").parent("div").addClass("error");
	    count++;
	}
	if(!isValid_account)
	{
	   $('#account').parent("div").addClass("error");
	    count++;
	}
	if(!isValid_position)
	{
	  $("#position").parent("div").addClass("error");
	   count++;
	}
	if(!isValid_telephone)
	{
	   $("#phoneNum").parent("div").addClass("error");
	    count++;
	}
	if(!isValid_location)
	{
	   $("#location").parent("div").addClass("error");
	    count++;
	}
	if(!isValid_mail)
	{
	  $("#mailBox").parent("div").addClass("error");
	   count++;
	}
	if(count!=0)
	{
	  return false;
	}
	else
	{
	    $("#checking").show();
	           var clr = setTimeout(function(){
		       loadCont('<?php echo site_url('ecologycompany/setSelfJoinUser')?>');
		        //loadPage('创建生态企业4.html','company')	
		       clearTimeout(clr);
	           },2000)
	 /* var path;
	  var obj={
	       "father_name":$("#father_name").val(),//姓
		   "self_name":$("#self_name").val(),//名
		   "account":$('#account').val(),//账号
		   "position":$("#position").val(),//职位
		   "phoneNum":$("#phoneNum").val(),//电话
		   "location":$("#location").val(),//国家区域
		   "mailBox":$("#mailBox").val()//邮箱
	  }
	  $.post(path,obj,function(data){
	    var json = $.parseJSON(data);
		 if(json.code==0)
		    {
			  $("#checking").show();
	           var clr = setTimeout(function(){
		       loadCont('<?php echo site_url('ecologycompany/setSelfJoinUser')?>');
		        //loadPage('创建生态企业4.html','company')	
		       clearTimeout(clr);
	           },2000)
				
			}
		 else
		 {
		    $("#"+json.error_id+"").parent().addClass("error");
			return false;
		 }
	   })*/
	   
	}
}
	$(function(){
		
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
	});
</script>
</body>
</html>