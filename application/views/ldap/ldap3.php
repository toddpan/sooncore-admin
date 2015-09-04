<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--组织与帐号_LDAP同步3.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetBox">
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
		<dd class="error" style="padding: 10px;">操作超时，请稍后再试</dd>
        <dd style="padding: 10px;">
        <label id="first" class="checkbox"><input type="checkbox" checked="checked" /> People</label>
          <br />
          <label id="second" class="checkbox"><input type="checkbox" checked="checked" /> Printer</label>
          <br />
          <label id="third" class="checkbox"><input type="checkbox" checked="checked" /> User</label>
          <br />
          <label id="forth" class="checkbox"><input type="checkbox" checked="checked" /> Meeting</label>
          <br />
          <label id="five" class="checkbox"> <input type="checkbox" checked="checked" /> Room</label>
          <br />
          <label id="six" class="checkbox"><input type="checkbox" checked="checked" /> Computer</label>
          <br />
          <label id="seven" class="checkbox"><input type="checkbox" checked="checked" /> Dispenser</label>
          <br />
		  <label id="eight" class="checkbox"><input type="checkbox" checked="checked" /> Server</label>
        </dd>
    </dl>
    
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('main/mainPage');?>','main');"><span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn"><span class="text" onclick="loadCont('<?php echo site_url('ldap/isLdapLink1');?>');"
		style="cursor: pointer" >上一步</span><b class="bgR"></b></a>
		<a class="btnBlue yes"><span class="text" onclick="nextStep();" style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">
function nextStep() {
var count=0;
var Re_data='';
var Re_id=[];
var Re_context=[];
var i=0;
   $('label').each(function()
	{
	  if ($(this).attr('class')=="checkbox checked" && $(this).find('input').attr('checked')=="checked" )
	  { 
	      Re_id[i]=$(this).attr("id");
	      Re_context[i]=$(this).text(); 
		  i=i+1;
	    count=count+1;
	  }
	})
	if(count==0)
	{
	$('dd.error').text("您必须选择一项")
	 return false;
	}
	else
	{
	for(var i=0;i<Re_id.length;i++)
	  {
	    /* Re_data=Re_data+'{id:'+Re_con[i]+',pid:'+Re_con[i+1]+',name:'+Re_con[i+2]+',},';*/
		Re_data=Re_data+'{id:'+Re_id[i]+',name:'+Re_context[i]+'},';
	  }
	   Re_data=DelLastComma(Re_data);
	   var path='<?php echo site_url('ldap/choseLdap1'); ?>';
	   var obj={
	         "select_context":Re_data  //选中的框的内容
	   };
		$.post(path,obj,function(data){
			var json = $.parseJSON(data);
			if(json.code == 0)
			{ 
				 $("#checking").show();
              	 var clr = setTimeout(function(){
		         loadCont('<?php echo site_url('ldap/choseLdap');?>');	
		         clearTimeout(clr);
	              },2)
			}
		else
		 {
				alert(json.prompt_text);
				  
		   /*$('#'+json.error_id).parent("div").addClass("error");*/
		   return false;
		 }
	   })
	  /*  $("#checking").show();
	  var clr = setTimeout(function(){
		loadCont('<?php echo site_url('ldap/choseLdap');?>');	
		clearTimeout(clr);
	   },2)*/
	   
	
	}
  }
	$(function(){
		checkbox();
	});
</script>
</body>
</html>