<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--修改密码.html-->
<div class="contHead">
	<span class="title01">修改密码</span>
</div>
<div class="contText01">
	1. 新密码必须与旧密码不同。<br />
	2.
	<?php
		echo '密码由'. $current_pwd_arr['title'];
	?>
</div>
<table class="infoTable">
	<tr>
		<td class="tr">&nbsp;</td>
		<td width="160">
			<span class="errorMsg"></span>
		</td>
        <td>&nbsp;</td>
	</tr>
	<tr>
		<td class="tr">旧密码</td>
		<td width="160">
			<div class="inputBox w150">
				<label class="label"></label>
				<input type="password" id="oldPwd" class="input" value="" />
			</div>
		</td>
		<td>&nbsp;<!--<b class="formIcon formRight"></b>--></td>
	</tr>
	<tr>
		<td class="tr">新密码</td>
		<td>
			<div class="inputBox w150">
				<label class="label"></label>
				<input type="password" id="newPwd" class="input" />
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td class="tr">确认新密码</td>
		<td>
			<div class="inputBox w150">
				<label class="label"></label>
				<input type="password" id="repeatPwd" class="input" />
			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<a class="btnBlue yes btnSubmit" ><span class="text" onclick=" checkPassword();">确定 </span><b class="bgR"></b></a>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>

<script type="text/javascript">
 var regex=<?php echo $current_pwd_arr['regexptxt']; ?>;
function checkPassword() {
	$('.errorMsg').text('');
    var password_complexity = <?php echo $current_pwd_arr['id']; ?>;
    var oldPwd=$("#oldPwd").val();
	var newPwd=$("#newPwd").val();
	var repeatPwd=$("#repeatPwd").val();
	var isNewValid=regex.test(newPwd);
	var istrue=(oldPwd===newPwd)?false:true;
	var isRepeatValid=(newPwd===repeatPwd)?true:false;
	if(oldPwd=="")
	{
// 		$("#oldPwd").focus().parent().addClass("error");
		$("#oldPwd").parent().addClass("error");
		$(".errorMsg").text("请输入旧密码");
		return false;	
	}
	else
	{
		$('#oldPwd').parent().parent().next().find(".formIcon").remove();
		$('#oldPwd').parent().parent().next().append('<b class="formIcon formRight"></b>');
	}
   if(newPwd=="") {
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("请输入新密码");
		return false;	
	}
	else if(!isNewValid) {
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("<?php echo '密码由'. $current_pwd_arr['title']; ?>");
		return false;
	}
	else if(!istrue)
	{
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("新密码不能和旧密码相同");
		return false;
	}
    else
	{
		$('#newPwd').parent().parent().next().find(".formIcon").remove();
		$('#newPwd').parent().parent().next().append('<b class="formIcon formRight"></b>');
	}
	 if(repeatPwd=="") {
// 		$("#repeatPwd").focus().parent().addClass("error");
		$("#repeatPwd").parent().addClass("error");
		
		$(".errorMsg").text("请确认新密码");
		return false;	
	}
	else if(!isRepeatValid) {
// 		$("#repeatPwd").focus().parent().addClass("error");
		$("#repeatPwd").parent().addClass("error");
		
		$(".errorMsg").text("两次输入的新密码不相同，请重新输入");
		return false;	
	}
	else {
		$('#repeatPwd').parent().parent().next().find(".formIcon").remove();
		$('#repeatPwd').parent().parent().next().append('<b class="formIcon formRight"></b>');
		$(".errorMsg").text("");
		  var path="mixture/saveNewPwd";
		   var obj={
					"oldPwd":$("#oldPwd").val(),//旧密码
					"newPwd":$("#newPwd").val(),//新密码
					"repeatPwd":$("#repeatPwd").val()//确认密码
			};		
			$.post(path,obj,function(data){
				var json = $.parseJSON(data);
				if(json.code == 0)
				{
					showDialog('mixture/resetpwdsuc');
				}
			else
				{
				$('.errorMsg').text(json.prompt_text);
			   	$('#'+json.error_id+'').parent("div").addClass("error");
			   	$('#'+json.error_id+'').parent().parent().next().find('b').remove();
			   return false;
			 }
			})
	}
	
}
function first()
{
		var password_complexity = <?php echo $current_pwd_arr['id']; ?>;
    	var oldPwd=$("#oldPwd").val();
		var isOldValid;
		if($("#oldPwd").val()==""){
			$("#oldPwd").parent().addClass("error");
// 			$('#oldPwd').focus();
			$(".errorMsg").text("请输入旧密码");
			$('#oldPwd').addClass('first');
			return false;
		}
		else {
			$(".errorMsg").text("");
		 	$('#oldPwd').parents("td").next().html('<b class="formIcon formRight"></b>');
			$('#oldPwd').removeClass('first');
		}
}
function second()
{
	$(this).parents("td").next().html();
	var newPwd=$("#newPwd").val();
	var oldPwd=$("#oldPwd").val();
	var isNewValid=regex.test(newPwd);
	var isTrue=(newPwd===oldPwd)?false:true;
	if($("#newPwd").val()=="") {
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("请输入新密码");
// 		$('#newPwd').focus();
		$('#newPwd').addClass("second");
		return false;	
	}
	else if(!isNewValid) {
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("<?php echo '密码由'. $current_pwd_arr['title']; ?>");
// 		$('#newPwd').focus();
		$('#newPwd').addClass("second");
		return false;
	}
	else if(!isTrue)
	{
// 		$("#newPwd").focus().parent().addClass("error");
		$("#newPwd").parent().addClass("error");
		$(".errorMsg").text("新密码不能和旧密码相同");
// 		$('#newPwd').focus();
		$('#newPwd').addClass("second");
		return false;
	}
	else {
		$(".errorMsg").text("");
		$('#newPwd').parents("td").next().html('<b class="formIcon formRight"></b>');
		$('#newPwd').removeClass("second");
	}
}
function third()
{
	//$(this).parents("td").next().html();
		var newPwd=$("#newPwd").val();
		var repeatPwd=$("#repeatPwd").val();
		//var isRepeatValid=valitateRepeatPwd(newPwd,repeatPwd);
		var isRepeatValid=(newPwd===repeatPwd)?true:false;
		//alert(isRepeatValid)
		if($("#repeatPwd").val()=="") {
// 			$("#repeatPwd").focus().parent().addClass("error");
			$("#repeatPwd").parent().addClass("error");
			$(".errorMsg").text("请确认新密码");
// 			$("#repeatPwd").focus();
			$('#repeatPwd').addClass("third");
			return false;	
		}
		else if(!isRepeatValid) {
// 			$("#repeatPwd").focus().parent().addClass("error");
			$("#repeatPwd").parent().addClass("error");
			$(".errorMsg").text("两次输入的新密码不相同，请重新输入");
// 			$("#repeatPwd").focus();
			$('#repeatPwd').addClass("third");
			return false;	
		}
		else {
			$(".errorMsg").text("");
		 	$(this).parents("td").next().html('<b class="formIcon formRight"></b>');
			$('#repeatPwd').removeClass("third");
		}
}
$(function(){
	$("#oldPwd").click(function()
	{
		//$('#newPwd').removeClass("s");
		//$('#repeatPwd').removeClass("t");
		if($("#oldPwd").parent().hasClass("error"))
		{
// 			$(this).focus();
			return false;
		}
		$(this).addClass('f');
		if($('#newPwd').hasClass("s"))
		{
		 second();
		}
		if($('#repeatPwd').hasClass("t"))
		{
		  third();
		}
		if(!$('#newPwd').hasClass("second") && !$('#repeatPwd').hasClass("third"))
		{
// 		  $(this).focus();
		}
		else if($('#newPwd').hasClass("second"))
		{
			$('#newPwd').removeClass("second")
		}
		else if($('#repeatPwd').hasClass("third"))
		{
			$('#repeatPwd').removeClass("third")
		}
	});
	$("#newPwd").click(function()
	{
		//$('#oldPwd').removeClass("f");
		//$('#repeatPwd').removeClass("t");
		if($("#oldPwd").parent().hasClass("error"))
		{
// 			$("#oldPwd").focus();
			return false;
		}
		if($("#newPwd").parent().hasClass("error"))
		{
// 			$(this).focus();
			return false;
		}
		$(this).addClass('s');
		if($('#oldPwd').hasClass("f"))
		{
		  first();
		}
		/*if($('#repeatPwd').hasClass("t"))
		{
		  third();
		}*/
		if(!$('#oldPwd').hasClass("first") && !$('#repeatPwd').hasClass("third"))
		{
// 			$(this).focus();
		}
		else if($('#oldPwd').hasClass("first"))
		{
			$('#oldPwd').removeClass("first")
		}
		else if($('#repeatPwd').hasClass("third"))
		{
			$('#repeatPwd').removeClass("third")
		}
		
	})
	$("#repeatPwd").click(function()
	{
		//$('#oldPwd').removeClass("f");
		//$('#newPwd').removeClass("s");
		$(this).addClass('t');
		/*if($('#oldPwd').hasClass("f"))
		{
		  first();
		}*/
		if($("#oldPwd").parent().hasClass("error"))
		{
// 			$("#oldPwd").focus();
			return false;
		}
		if($("#newPwd").parent().hasClass("error"))
		{
// 			$("#newPwd").focus();
			return false;
		}
		if($('#newPwd').hasClass("s"))
		{
		  second();
		}
		if(!$('#oldPwd').hasClass("first") && !$('#newPwd').hasClass("second"))
		{
// 			$(this).focus();
		}
		else if($('#oldPwd').hasClass("first"))
		{
			$('#oldPwd').removeClass("first")
		}
		else if($('#newPwd').hasClass("second"))
		{
			$('#newPwd').removeClass("second")
		}	
	})
	
/**  --------------该方法为解决[B150306-001]而注释掉----------------------//
//	$(document).click(function(e)
//	{
//		var t=$(e.target);
//		if(t.attr('type')!='password')
//		{
// 				if($('#oldPwd').hasClass("f"))
// 					{
// 		  				first();
// 					}
// 				if($('#newPwd').hasClass("s"))
// 					{
// 					  second();
// 					}
				
// 				if($('#repeatPwd').hasClass("t") && $('#repeatPwd').val()!='')
// 					{
// 					  third();
// 					}
//		}
//	})  
    --------------该方法为解决[B150306-001]而注释掉---------------------- **/

    
	/*$("#oldPwd").blur(function(){
		
		var password_complexity = <?php //echo $current_pwd_arr['complexity_type']; ?>;
    	var oldPwd=$("#oldPwd").val();
		var isOldValid=valitateUserPwd(oldPwd,password_complexity);
		if($("#oldPwd").val()=="")
		{
			$("#newPwd").blur();
			$("#oldPwd").parent().addClass("error");
			$('#oldPwd').focus();
			$(".errorMsg").text("请输入旧密码");
			return false;
		}
		if($("#oldPwd").val()==""){
			$("#oldPwd").parent().addClass("error");
			$('#oldPwd').focus();
			$(".errorMsg").text("请输入旧密码");
			return false;
		}
		else if(!isOldValid){
			$("#oldPwd").focus().parent().addClass("error");
			$(".errorMsg").text("您的密码输入错误");
			return false;
		}
		else {
			$(".errorMsg").text("");
		 	$(this).parents("td").next().html('<b class="formIcon formRight"></b>');
		}
	})
	
	$("#newPwd").blur(function(){
	var newPwd=$("#newPwd").val();
	var isNewValid=valitateUserPwd(newPwd,2);
		if($("#newPwd").val()=="") {
			$("#newPwd").focus().parent().addClass("error");
			$(".errorMsg").text("请输入新密码");
			return false;	
		}
		else if(!isNewValid) {
			$("#newPwd").focus().parent().addClass("error");
			$(".errorMsg").text("请输入6-16位的密码");
			return false;
		}
		else {
			$(".errorMsg").text("");
		 	$(this).parents("td").next().html('<b class="formIcon formRight"></b>');
		}
	})	
	
	$("#repeatPwd").blur(function(){
	var newPwd=$("#newPwd").val();
	var repeatPwd=$("#repeatPwd").val();
	var isRepeatValid=valitateRepeatPwd(newPwd,repeatPwd);
		if($("#repeatPwd").val()=="") {
			$("#repeatPwd").focus().parent().addClass("error");
			$(".errorMsg").text("请确认新密码");
			return false;	
		}
		else if(!isRepeatValid) {
			$("#repeatPwd").focus().parent().addClass("error");
			$(".errorMsg").text("密码输入错误，请重新输入");	
			return false;	
		}
		else {
			$(".errorMsg").text("");
		 	$(this).parents("td").next().html('<b class="formIcon formRight"></b>');
		}
	})*/
})
</script>
</body>
</html>