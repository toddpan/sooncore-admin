<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
		<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
		<base href="{site_url('')}"/>
		<base target="_blank" />
		<title>{$UC_NAME_EN} 管理中心</title>
		<link href="public/css/login.css" rel="stylesheet" />
            <script type="text/javascript" src="public/js/jquery.js"></script>
            <script type="text/javascript" src="public/js/placeholder.js"></script>
	</head>
        <body style="overflow: hidden;">
            <div class="header">
		  <div class="headerInner">
                      <a class="logo" style="text-decoration:none;">
                            <span style="font-size:45px;">
                                <img src="public/images/guaji_logo.png" />
                            </span>
                            <span class="logoText">管理中心</span>
			</a>
			<span style="float:right;font-size:15px;margin:40px 30px 0 0;"><i>www.sooncore.com</i></span>
		  </div>
		</div>
            <div class="mainBox">
                <div id="mainBody">
                    <div id="cloud1" class="cloud"></div>
                    <div id="cloud2" class="cloud"></div>
                </div>
                <div id="weather">
                        <img src="public/images/cloud.png" width="300">
                </div>
                <script type="text/javascript" src="public/js/cloud.js"></script>
             </div>
                
		<div class="loginCont">
                <script type="text/javascript">
                    //设置窗口高度
                    var windowHeight = $(window).height();
                    var newHeight = windowHeight-88-47;
                    if(newHeight>650){
                        //alert(windowHeight);
                        $(".loginCont").css("height",newHeight+"px");
                    }
                </script>
		 	<dl class="loginBox">
                            <div class="loginBg"></div>
                            <div class="loginC">
				<dt class="title">管理员登录</dt>
				<dd class="error"></dd>
				<dd>
					<div class="inputBox2" style="cursor: pointer">
					  <!--//<label for="userName">请输入您的帐号</label>//-->
					  <input id="userName" name="userName" type="input" style="width: 232px" class="input" placeholder="请输入您的帐号" />
					</div>
				</dd>
				<dd>
					<div class="inputBox2" style="cursor: pointer">
						<!--//<label for="userPwd">请输入您的密码</label>//-->
						<input id="userPwd" name="userPwd" type="password" style="width: 232px" class="input" placeholder="请输入您的密码" />
					</div>
				 </dd>
				 <dd class="checkcode" style=" {if $login_num<3 }display: none;{/if} cursor: pointer">
					<div class="inputBox2">
						<!--//<label for="checkcode">请输入验证码</label>//-->
						<input id="loginCode" name="loginCode" type="text" class="input" placeholder="请输入验证码" target="{$login_num}"/>
					</div>
					<img id="pwdcode" src="login/code/"+Math.random()+"" onclick=this.src="login/code/"+Math.random()+"" /> 
                                        <a style="display:none;" onclick=document.getElementById('pwdcode').src="login/code/"+Math.random()+"">看不清，换一张
					</a>
					<div class="clearfix"></div>
				</dd>
				<dd>
					<label class="checkbox" id='remPwd' rel='0' style="cursor: pointer" >
						<input type="checkbox" />记住帐号
					</label>
                                    <a onclick="showDialog('resetpassword/index');" class="forgetPwd">忘记密码</a>
                                    <a href="register/" target="_blank" class="register">注册用户</a> 
				</dd>
				<dd class="tc"> 
					<a onclick="loginChecked(this);" class="loginBtn">
						<span class="loginspan" style="curson:pointer">登 录</span>
					</a>
				</dd>
                            </div>
		 	</dl>
		</div>
		<div class="footer">
		  <div class="footerInner"> 
			  <span class="text rightLine">
			  {$COMPANY_NAME}
			  {$COMPANY_COPR}
			  {$COMPANY_ICP}
			  </span> 
			  <span class="text">24小时服务热线：{$COMPANY_SERVER_TEL}</span> 
		  </div>
		</div>
		<div class="mask"></div>
		<div id="dialog" class="dialog">
		  <div class="dialogBorder"></div>
		  <b class="bgTL"></b>
		  <b class="bgTR"></b>
		  <b class="bgBL"></b>
		  <b class="bgBR"></b>
		  <b class="shadow"></b>
		</div>
                
		<script type="text/javascript" src="public/js/self_common.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript">
		var countNumber=$("#loginCode").attr("target");//登陆次数连续错误3次显示
		function loginChecked(t){
			showcode(countNumber);
			if($(t).hasClass("false"))
			{
				return;
			}
			$(t).addClass("false");
			var _t=$(t);
			var userName = $("#userName").val();
			var userPwd = $("#userPwd").val();
			var loginCode = $("#loginCode").val();//登陆连续错误3次才会有码
			if(userName == ''){
				$('.error').text('请输入用户名');
				$('#userName').focus();
				 _t.removeClass("false");
				return false;
			}
			if(userPwd == ''){
				$('.error').text('请输入密码');
				$('#userPwd').focus();
				 _t.removeClass("false");
				return false;
			}
			if(countNumber>=3){
				if( loginCode == ''){
				   $('.error').text('请输入验证码');
				    _t.removeClass("false");
				   return false;
				 }
			   }
                        $('.error').text("");
                        _t.children(".loginspan").text("验证中");
			var path="login/loginin";
			var obj={
				"userName":userName,
				"userPwd":userPwd,
				"loginCode":loginCode
			 };
                         $.ajax({
                            url:path,
                            type:"POST",
                            data:obj,
                            timeout:10000,
                            dataType:"json",
                            success:function(data){
                              //var msgJson = eval(data); dataType为json，就不用转了
				if(data.code === 0){
				  if ($("#remPwd").hasClass("checked")){
					   setCookie('username',userName,30);
				  }else{
					//$('#remPwd').removeClass("checked");
					setCookie('username','',365);
				  }
                                  _t.children(".loginspan").text("验证通过");
				  location = "{site_url('main/index')}";
				  _t.removeClass("false");	
				}else{
				   $(data.error_id).focus();
				   countNumber=data.other_msg.login_num;
				   showcode(countNumber);
				   $('.error').text(data.prompt_text);	
                                    _t.children(".loginspan").text("登 录");
				   _t.removeClass("false");		   
				   return false;
				}
                            },
                            error:function(){
                                _t.removeClass("false");
                                _t.children(".loginspan").text("登 录");
                                $('.error').text("服务器无响应，请检查您的网络是否通畅");
                            }
                          });
			  
			}
			 
		 	function get_user_frmcookies()
			 {
			   username= getCookie('username')
			   if (username!=null && username!=""){
				 $('#userName').val(username);
				 $('#remPwd').addClass('checked');
			   }else{
				 $('#userName').val("");
			   }  
			}	
			function showcode(countNumber){
				if(countNumber >= 3){
					$('.checkcode').show();
				}else{
				   $('.checkcode').hide();
				}
			}
			$(function(){			
				PlaceHolder.init();
				checkbox();		
				initScroll();
				
				get_user_frmcookies();
				showcode(countNumber);
				//点击填写账号
				var count=$('#userName').val();
				$('#userName').blur(function()
				{
					if($(this).val()!=count)
					{
						$('.checkcode').hide();
						countNumber=0;
					}
				})
			
			//点击
			$(".inputBox2").click(function(){
				$(this).find("input").focus();	
			})
			$(".inputBox2 input").focus(function(){
				$(this).parent().addClass("focus");
				$(this).parent().find("label").hide();
			}).blur(function(){
				$(this).parent().removeClass("focus");
				if($(this).val()==""){
					$(this).parent().find("label").show();	
				}
			})	
			$(".inputBox2 input").each(function(index, element) {
				if($(this).val()!=""){
					$(this).parent().find("label").hide();	
				}
			});
			$("body").keydown(function(e)
			  {
			   if (e.keyCode == 13)
				{
				   loginChecked();
				}
			  })
		   })
		</script>
	</body>
</html>
