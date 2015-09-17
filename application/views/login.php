<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;" lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
		<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
		<base href="<?php echo site_url('');?>"/>
		<base target="_blank" />
		<title>sooncore平台管理中心</title>
		<link href="public/css/login.css" rel="stylesheet" />
	</head>
	<body>
		<div class="header">
		  <div class="headerInner">
			<a class="logo">
				<img src="public/images/logo.png" />
			</a>
		  </div>
		</div>
		<div class="loginCont">
		  <div class="front-scroll">
				<div class="page" id="page1" style="display: block">
					<h2 data-left="0">为移动互联网而生的企业社交协作平台</h2>
					<p data-left="0">全时sooncore平台全面支持 PC 与智能手机同步使用。<br />随时随地查看最新的消息、实时沟通与开会。</p>
					<img src="public/images/ad/img1.png" data-left="76" class="img1" width="231" height="147" alt="" />
					<img src="public/images/ad/img2.png" data-left="429" class="img2" width="79" height="139" alt="" />
					<img src="public/images/ad/img3.png" data-left="332" class="img3" width="61" height="61" alt="" />
				</div>
				<div class="page" id="page2" style="display:none">
					<h2 data-left="0">管理你的组织与员工更轻松了</h2>
					<p data-left="0">通过 LDAP 或API 同步，在全时sooncore平台有效管理企业生态的组织与员工。</p>
					<img src="public/images/ad/img4.png" data-left="182" class="img1" width="236" height="196" alt="" />
					<img src="public/images/ad/img5.png" data-left="203" class="img2" width="140" height="106" alt="" />
					<img src="public/images/ad/pic24.gif" data-left="350" data-top="248" class="img3" width="24" height="24" alt="" />
					<img src="public/images/ad/hand.gif" data-left="360" data-top="264" class="img4" width="37" height="45" alt="" />
				</div>
				<div class="page" id="page3" style="display: none">
					<h2 data-left="0">连接你的企业生态，一起提高工作效率</h2>
					<p data-left="0">马上让周边的生态圈合作伙伴跟您一起用互联网一起协作、决策。</p>
					<div class="pic-bg" data-left="132"></div>
					<div class="pic1" data-left="102"></div>
					<div class="pic2" data-left="373"></div>
					<div class="pic3" data-left="226"></div>
					<div class="pic4" data-left="206"></div>
					<div class="pic5" data-left="463"></div>
					<div class="pic6" data-left="105"></div>
					<div class="pic7" data-left="457"></div>
				</div>
				<div class="page" id="page4" style="display: none">
					<h2 data-left="0">通过应用集成，提升整个公司决策效能</h2>
					<p data-left="0">全时sooncore平台提供完整的API，让企业可以快速与内部系统集成，<br />以消息来驱动人们快速决策。</p>
					<div class="pic1" data-left="185"></div>
					<div class="pic2" data-left="263"></div>
					<div class="pic3" data-left="341"></div>
					<div class="pic4" data-left="224"></div>
					<div class="pic5" data-left="302"></div>
				</div>
				<div class="page-num">
					<a  class="active" onclick="scrollOne()">1</a>
					<a  onclick="scrollTwo()">2</a>
					<a  onclick="scrollThree()">3</a>
					<a  onclick="scrollFour()">4</a>
				</div>
			</div>
		 	<dl class="loginBox">
				<dt class="title">管理员登录</dt>
				<dd class="error"></dd>
				<dd>
					<div class="inputBox2" style="cursor: pointer">
					  <label for="userName">请输入您的帐号</label>
					  <input id="userName" name="userName" type="input" style="width: 232px" class="input" placeholder="" />
					</div>
				</dd>
				<dd>
					<div class="inputBox2" style="cursor: pointer">
						<label for="userPwd">请输入您的密码</label>
						<input id="userPwd" name="userPwd" type="password" style="width: 232px" class="input" placeholder="" />
					</div>
				 </dd>
				 <dd class="checkcode" style="{if $login_num>3}display: none;{/if}cursor: pointer">
					<div class="inputBox2">
						<label for="checkcode">请输入验证码</label>
						<input id="loginCode" name="loginCode" type="text" class="input" placeholder="" target="{$login_num}"/>
					</div>
					<img id="pwdcode" src="login/code/"+Math.random()+""  
						style="float: left; margin-right: 2px; height:36px; width:58px"  
						onclick=this.src="login/code/"+Math.random()+"" /> 
					<a onclick=document.getElementById('pwdcode').src="login/code/"+Math.random()+"">看不清，换一张
					</a>
					<div class="clearfix"></div>
				</dd>
				<dd>
					<label class="checkbox" id='remPwd' rel='0' style="cursor: pointer" >
						<input type="checkbox" />记住帐号
					</label>
					<a onclick="showDialog('resetpassword/accountPage/"+Math.random()+"');" class="forgetPwd">忘记密码</a> 
				</dd>
				<dd class="tc"> 
					<a onclick="loginChecked(this);" class="loginBtn">
						<span class="loginspan">登录</span>
					</a>
				</dd>
		 	</dl>
		 	<div class="linkBox">
				<dl class="linkArea linkArea01">
				  <dt>帮助中心</dt>
				  <dd>&gt;&nbsp;<a>下载用户手册</a></dd>
				  <dd>&gt;&nbsp;<a>常见问题FAQ</a></dd>
				</dl>
				<dl class="linkArea linkArea02">
				  <dt>下载与安装</dt>
				  <dd><a>下载最新的客户端</a></dd>
				</dl>
				<dl class="linkArea linkArea03">
				  <dt>关注全时</dt>
				  <dd><a class="sina">新浪微博</a></dd>
				  <dd><a class="qq">腾讯微博</a></dd>
				</dl>
		  </div>
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
		<script type="text/javascript" src="public/js/jquery.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript" src="public/js/placeholder.js"></script>
		<script type="text/javascript" src="public/js/scrollFront.js"></script>
		<script type="text/javascript" src="public/js/self_common.js"></script>
		<script type="text/javascript">
		var countNumber =$("#loginCode").attr("target");//登陆次数连续错误3次显示
		function loginChecked(t){
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
				return false;
			}
			if(userPwd == ''){
				$('.error').text('请输入密码');
				$('#userPwd').focus();
				return false;
			}
			if(countNumber>=3){
				if( loginCode == ''){
				   $('.error').text('请输入验证码');
				   return false;
				 }
			   }
			var path="login/loginin";
			var obj={
				"userName":userName,
				"userPwd":userPwd,
				"loginCode":loginCode
			 };
			$.post(path,obj,function(data){
				//alert(data);
				var json = $.parseJSON(data);
				if(json.code == 0){
				  if ($("#remPwd").hasClass("checked")){
					   setCookie('username',userName,30);
				  }else{
					//$('#remPwd').removeClass("checked");
					setCookie('username','',365);
				  }					 
				  location = "main/index";
				  _t.removeClass("false");	
				}else{
				   $(json.error_id).focus();
				   countNumber=json.other_msg.login_num;
				   showcode(countNumber);
				   $('.error').text(json.prompt_text);	
				   _t.removeClass("false");		   
				   return false;
				}
				
			  })
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
			$(".inputBox2 input").focus(function(){//获得焦点
				$(this).parent().addClass("focus");
				$(this).parent().find("label").hide();
			}).blur(function(){//失去焦点
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
