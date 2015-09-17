<!doctype html>
<html>
<head>
<base href="{$tag_base_url}" />
<meta charset="utf-8">
<title>免费试用sooncore平台</title>
<link href="public/css/css_trycount/style.css" rel="stylesheet" />
</head>

<body>
<div class="header">
  <div class="headerInner"> <a class="logo" ><img src="public/images/images_trycount/logo.png" /></a> <a class="header-link" href="http://www.quanshi.com">返回全时首页</a> </div>
</div>
<!-- end header -->

<div class="main">
  <div class="breadcrumb">免费试用sooncore平台</div>
  <h2 style="margin-top: 50px;">请输入短信验证码</h2>
  <h3>短信验证码已经发送到您的手机 {$mobileNumber}上，请在下框中输入验证码。</h3>
  <div class="form-box">
        <div id="email" class="form-item error">
          <label for="userEmail" class="label-noIcon" onClick=""><span style="display: inline;">请输入验证码…</span></label>
          <input type="text" id="userCode" maxlength="45" name="userEmail" class="form-text valid" placeholder="" value="" style="padding-left:8px; margin-right: 10px; width: 225px; ">
          <a  id="resendCheckcode" onClick="resendCheckcode(this,{$phone})" class="blue-link">重新发送验证码<span></span></a>
          <div class="form-error" id="error_code" style="left: 400px;display:none">验证码错误，请重新输入</div>
          
        </div>
  </div>
  <div class="form-footer" style="margin: 50px 0;"> <input class="form-btn" id="code_next" user_id="{$user_id}"  value="下一步" /> <a href="http://www.quanshi.com" class="gray-link">取消</a></div>
</div>
<div class="footer">
  <div class="footerInner"> <span class="text rightLine">创想空间商务通信服务有限公司 @copyright 2001-2011 京ICP备0500547号</span> <span class="text">24小时服务热线：400-810-1919</span> </div>
</div>
<!-- end footer --> 

<script type="text/javascript" src="public/js/jquery.js"></script>
<script type="text/javascript" src="public/js/js_trycount/common.js"></script>
<script type="text/javascript">
	var num = 60,cleart;
	function decendNum() {
		num--;
		cleart =setTimeout(function(){
			decendNum();
		},1000)
		if(num==0){
			clearTimeout(cleart);
			$("#resendCheckcode").removeClass("disabled").find("span").text('');
		}
		$("#resendCheckcode").find("span").text('('+ num +')')
		
	}
	function resendCheckcode(t,phone) {
		if($(t).hasClass("disabled")) {
			return false;
		}
		else {
			$(t).addClass("disabled");
			
            var user_id=$('#code_next').attr("user_id");
			var obj={
                "user_id":user_id,
                "mobile_number":phone
                }
			var path='crippleware/crippleware/re_send_phone_code';
			$.post(path,obj,function(data)
			{
			    if(data.code==0)
			    {
			       num = 60;
                   decendNum()
			   }
			   else
				   {
				   		alert(data.prompt_text);
				   }


			},'json')

		}
	}
	$(function(){
	    $('#code_next').click(function()
	    {
            var code=$('#userCode').val();
            var user_id=$(this).attr("user_id");
            if(code=="")
            {
                  $('#error_code').show();
                  return;
            }
            else
            {
                var path='crippleware/crippleware/valid_phone_code';
                var obj={
                "user_id":user_id,
                "code":code
                };
                $.post(path,obj,function(data){
                    if(data.code==0)
                    {
                    	location = "crippleware/crippleware/activation_phone_suc_page?user_id=" + user_id;
                    }
					else
				   {
				   		alert(data.prompt_text);
				   }
                },'json')
            }
	    })

	})
	</script>
</body>
</html>
