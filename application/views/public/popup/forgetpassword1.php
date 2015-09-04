
<!--弹窗_忘记密码1.html-->
<dl class="dialogBox D_forgetPwd">
	<dt class="dialogHeader">
		<span class="title">忘记密码</span>
		<a  class="close"  onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">验证短信将发送到帐号对应的手机中，请注意查收。
		</span>
        <form id="formid" method="post" action="">
		<table class="infoTable">
        	<tr>
				<th>&nbsp;</th>
				<td colspan="3">
					<span class="errorMsg">错误提示</span>
				</td>
			</tr>
			<tr style="cursor: pointer">
				<th>帐号：</th>
				<td colspan="3">
					<div class="inputBox">
						<label>请输入您的账号</label>
						<input class="input" style="width: 330px" name="user_account" id="user_account" value="" />
					</div>
				</td>
			</tr>
			<tr style="cursor: pointer">
				<th>验证码：</th>
				<td width="140px">
					<div class="inputBox">
						<label>请输入验证码</label>
						<input class="input" value="" name="pass_word_code" id="pass_word_code" style="width: 110px;" />
					</div>
				</td>
				<td  class="code" >
				  <div style="padding-left:10px;width: 200px;">
				  <img id="forgetpwdcode" src="" width="78" height="26"  onclick="this.src='resetpassword/generate_img_code'+'/'+Math.random()" style="cursor: pointer;vertical-align: top;"><span class="changeCode"  onclick="document.getElementById('forgetpwdcode').src='<?php echo site_url('resetpassword/generate_img_code').'/'?>'+Math.random()" style="cursor: pointer">看不清，换一张？</span>
				  </div>   
				  
				</td>
				
			</tr>
		</table>
		</form>
	</dd>
	<dd class="dialogBottom">
		<a class="btn long disable"  onclick="sendPassword(this);"><span class="text" style="cursor: pointer">发送验证密码</span><b class="bgR"></b></a>
		<a class="btn"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
    document.getElementById('forgetpwdcode').src="<?php echo site_url('resetpassword/generate_img_code').'/'?>"+Math.random() ;
	function sendPassword(t)
	 {
		if($(t).hasClass("disable"))
		 {
			return false;	
		 }
		else 
		{
           var  user_account = $("#user_account").val();
		   var pass_word_code = $("#pass_word_code").val();
			
			//js验证
			var isValid_userN=valitateUserName(user_account);
			var isValid_loginCode=valitateLoginCode(pass_word_code);
			if(!isValid_userN)
			{
			  $('.errorMsg').text("请输入正确的账号");
			  $('.errorMsg').show();
			  $('#user_account').focus();
			  return false;
			}else if(!isValid_loginCode)
			{
			  $('.errorMsg').text("请输入正确的验证码");
			  $('.errorMsg').show();
			  $('#pass_word_code').parent().addClass("focus");
			   $('#pass_word_code').focus();
			   return false;
			}
			else
			{
			var path="resetpassword/valid_account";
			var obj={
				"user_account":user_account,
				"pass_word_code":pass_word_code
			};
			$.post(path,obj,function(data){
			//alert(data)
				var json = $.parseJSON(data);
				if (json.code==0)//成功
				showDialog('resetpassword/input_msgcode_page/' + json.data.user_id + '/' + json.data.mobile);
				else
				{
			      $('.errorMsg').text(json.msg);
				  $('.errorMsg').show();
				}
			})
		  }
		}
	}
	$(function(){
	    $('.errorMsg').hide();
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
