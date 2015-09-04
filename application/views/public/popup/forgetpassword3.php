<!--弹窗_忘记密码3.html-->
<dl class="dialogBox D_forgetPwd">
	<dt class="dialogHeader">
		<span class="title">忘记密码</span>
		<a  class="close"  onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
	<span class="text01">1、新密码必须与旧密码不同</span>
		<span class="text01">2、<?php echo $complexity_arr['title']; ?></span>
		<table class="infoTable">
            <tr>
				<th style="width: 6em">&nbsp;</th>
				<td>
					<span class="errorMsg"></span>
				</td>
			
			</tr>
			<tr>
				<th style="width: 5em">新密码：</th>
				<td>
					<div class="inputBox">
						<label class="label">请输入新密码</label>
						<input class="input" type="password" id="new_password" name="new_password" style="width: 330px" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<th style="width: 5em">再次输入：</th>
				<td>
					<div class="inputBox">
						<label class="label">请再次输入新密码</label>
						<input class="input" type="password" id="confirm_password" name="confirm_password" value="" style="width: 330px;" />
					</div>
				</td>
				
			</tr>
		</table>
	</dd>
	<dd class="dialogBottom">
		<a class="btn disable"  onclick="sendPassword(this);"><span class="text">确定</span><b class="bgR"></b></a>
		
	</dd>
</dl>
<script type="text/javascript">
var password_complexity = <?php echo $complexity_arr['id']; ?>; //1、8-30位，不限制类型2、8-30位数字与字母组合3、8-30位数字、符号与字母组合
	function sendPassword(t) {
		if($(t).hasClass("disable")) {
			return false;	
		}
		else {
			var user_id = <?php echo $user_id; ?>;
			var new_password = $("#new_password").val();
			var confirm_password = $("#confirm_password").val();
			//js校验
			var isValid_new_passW=valitateUserPwd(new_password,password_complexity);
			var isValid_Repeat_passW=(new_password==confirm_password)? true  :  false
			if (!isValid_new_passW)
			{
			  $('.errorMsg').text("新密码输入错误");
			  $('#new_password').focus();
		      $('.errorMsg').show();
			  return false
			}
			else if(!isValid_Repeat_passW)
			{
			   $('.errorMsg').text("再次输入的密码错误");
			   $('#confirm_password').focus();
			   $('.errorMsg').show();
			}
			else
			{
			var path="resetpassword/reset_pwd";
			var obj={
				"user_id":user_id,
				"pwd":new_password,
				"confirm_pwd":confirm_password
			};
			$.post(path,obj,function(data){
				var json = $.parseJSON(data);
				if(json.code == 0){
					showDialog('resetpassword/reset_pwd_suc_page');
				}else{
					  $('.errorMsg').text(json.msg);
				      $('.errorMsg').show(); 
				      //return false;
				}
			})
		   }
		}
	}
	$(function(){
	     $(".inputBox").click(function(){
		   $(this).find("input").focus();	
			})
			$(".inputBox input").focus(function(){
				$(this).parent().addClass("focus");
				$(this).parent().find("label").hide();
			}).blur(function(){
				$(this).parent().removeClass("focus");
				if($(this).val()==""){
					$(this).parent().find("label").show();	
				}
			})
			
		$(".D_forgetPwd .inputBox input").keyup(function(){
		var val1 = $.trim($(this).val());
		var val2 = $.trim($(this).parents("tr").siblings().find("input").val());
		if(val1 != "" && val2 != ""){
			$(".D_forgetPwd .btn:first").removeClass("disable").addClass("yes")
		}
		else {
			$(".D_forgetPwd .btn:first").addClass("disable").removeClass("yes")
		}
	})	
	});
</script>
