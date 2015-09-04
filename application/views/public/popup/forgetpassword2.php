<!--弹窗_忘记密码2.html-->
<dl class="dialogBox D_forgetPwd">
	<dt class="dialogHeader">
		<span class="title">忘记密码</span>
		<a  class="close"  onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">短信验证码已经发送到您的手机<?php echo $mobile_str ;?>上，请在下框中输入验证码。如果未收到短信验证码请点击重新发送。</span>
		<table class="infoTable">
			<tbody>
            <tr>
				<th style="width: 6em">&nbsp;</th>
				<td>
					<span class="errorMsg"></span>
				</td>
			
			</tr>
			<tr>
				<th style="width: 6em">短信验证码：</th>
				<td>
					<div class="inputBox">
						<label class="label">请输入短信验证码</label>
						<input class="input" value="" id="sms_code_ppp" name="sms_code" style="width: 330px;">
					</div>
				</td>
			
			</tr>
		</tbody></table>
        
        <p style="text-align: center; padding-top: 20px;"><a  id="repeatSend" class="btn disable" onclick="repeatSend(this)"><span class="text" style="width:95px;">重新发送(60)</span><b class="bgR"></b></a></p>
	</dd>
	<dd class="dialogBottom">
		<a class="btn disable"   onclick="sendMsgPassword(this);"><span class="text">确定</span><b class="bgR"></b></a>
	</dd>
</dl>

<script type="text/javascript">
function descendNum(num){
	if(num == 0 ){
	    //ajax重新发送短信
		$("#repeatSend span.text").text("重新发送");
		$("#repeatSend").removeClass("disable").addClass("yes");
		return false;
	}else{
		$("#repeatSend").removeClass("yes").addClass("disable");
		$("#repeatSend span.text").text("重新发送("+num+")");
		num=num-1;
		setTimeout(function(){descendNum(num)}, 1000);
	}
  }
function repeatSend(t) {
	if($(t).hasClass("disable")){

		return false;	
	}
	else {
            
            //ajax重新发送短信开始
			var path="resetpassword/send_msg_code/" + <?php echo $user_id;?> + '/' + <?php echo $mobile;?>;
			var obj={};
			$(t).addClass("disable").removeClass("yes");
			$("#repeatSend  span.text").text("重新发送(60)");
     		//descendNum(60)
			$.post(path,obj,function(data){
				var json = $.parseJSON(data);
			   if(json.code==0)
			   {
				   //	alert(1111);
			   		//发送结束
		     		$(t).addClass("disable").removeClass("yes");
		     		$("#repeatSend  span.text").text("重新发送");
		     		descendNum(60)
			 	}
			 		else
			 	{
			 			$('.errorMsg').text(json.msg);
			 			  //$('#sms_code_ppp').focus();
			 			  $('.errorMsg').show();
			 	}
			})
		
	}
}
function sendMsgPassword(t) {

	if($(t).hasClass("disable")) {
		return false;	
	}
	
	else {
		var user_id = <?php echo $user_id; ?>;
        var sms_code_v = $("#sms_code_ppp").val();
		var isValid = valitatesendMeg(sms_code_v);
		if(!isValid)
		{
		  $('.errorMsg').text("短信验证码输入错误");
		  $('#sms_code_ppp').focus();
		  $('.errorMsg').show();
		  return false;
		}
		else
		{
		var path="resetpassword/valid_msgcode";
		var obj={
			"user_id":user_id,
			"msg_code":sms_code_v
		};
		
	
		$.post(path,obj,function(data){
		  /* alert(data);*/
		  var json = $.parseJSON(data);
		    if (json.code==0)//成功
			{
			  showDialog('resetpassword/inut_new_pwd_page/' + user_id);
			}
			else
			{
			  //$(json.error_id).focus();
			  $('.errorMsg').text(json.msg);
		      $('.errorMsg').show(); 
			}
			//eval(data);
			
		  
		})
	   }
	}
}
$(function(){
	descendNum(60);
	$('.inputBox').click(function()
	{
	  $(this).children("input").focus();
	  $(this).children("label").hide();
	})
	$('.inputBox input').blur(function()
	{
	   if(this.val=="")
	   $(this).siblings("label").show();
	})

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
		
		if(val1 != ""){
			$(".dialogBottom .btn").removeClass("disable").addClass("yes")
		}
		else {
			$(".dialogBottom .btn").addClass("disable").removeClass("yes")
		}
	})
})
</script>
