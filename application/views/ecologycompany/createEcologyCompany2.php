<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
<!--创建生态企业2.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">创建生态企业</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep qystStep">
    	<a  class="selected">1. 填写生态企业信息<b class="arrow"></b></a>
    	<a  class="selected current">2. 设置生态企业权限<b class="arrow"></b></a>
    	<a >3. 设置该企业管理员<b class="arrow"></b></a>
    	<a >4. 设置本方参与的用户<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:50%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
     <dl class="ldapSetCont">
        
        <dd style="padding: 10px;">
           <div class="setStqy"><label class="checkbox checked"><input type="checkbox" checked="checked" />允许召开网络会议</label><br />
            <label class="checkbox checked"><input type="checkbox" checked="checked" />允许召开电话会议</label><br />
            <label class="checkbox checked"><input type="checkbox" checked="checked" />允许会中外呼</label><br />
<label class="checkbox checked"><input type="checkbox" checked="checked" />允许设置呼叫转移</label><br />
<label class="checkbox checked"><input type="checkbox" checked="checked" />允许拨打电话</label></div>
        </dd>
    </dl>
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('ecologycompany/quitSetEcologyCompany')?>','main');"><span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ecologycompany/createEcologyCompany')?>');"><span class="text" style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue" href="javascript:nextStep();"><span class="text" style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">

function nextStep() {
   var set_state='';
    $('label.checkbox').each(function()
	{
	   if($(this).hasClass("checked"))
	   {
	     set_state=set_state+"{id:"+$(this).index+",name:"+$(this).find("input").val()+"},"
	   }
	})
	 set_state=DelLastComma(set_state);
	 /*
	 var path;
	 var obj={
	         "select_context":set_state  //选中的框的内容
	   };
		$.post(path,obj,function(data){
				$("#checking").show();
	           var clr = setTimeout(function(){
		       loadCont('<?php echo site_url('ecologycompany/setCompanyAdmin')?>');	
		       clearTimeout(clr);
	            },2000)
			}
	
	*/
	$("#checking").show();
	           var clr = setTimeout(function(){
		       loadCont('<?php echo site_url('ecologycompany/setCompanyAdmin')?>');	
		       clearTimeout(clr);
	            },2000)
}
	$(function(){
		
		
		
	});
</script>
</body>
</html>