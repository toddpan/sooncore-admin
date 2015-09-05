<?php /* Smarty version Smarty-3.1.18, created on 2015-08-23 23:35:32
         compiled from "application\views\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2020455a92b584ae312-96726014%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2f4aa51728c40a8b9404663b83c32537b9f5514d' => 
    array (
      0 => 'application\\views\\login.tpl',
      1 => 1440344125,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2020455a92b584ae312-96726014',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_55a92b588e0745_00588887',
  'variables' => 
  array (
    'login_num' => 0,
    'COMPANY_NAME' => 0,
    'COMPANY_COPR' => 0,
    'COMPANY_ICP' => 0,
    'COMPANY_SERVER_TEL' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55a92b588e0745_00588887')) {function content_55a92b588e0745_00588887($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
		<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
		<base href="<?php echo site_url('');?>
"/>
		<base target="_blank" />
		<title>云企管理中心</title>
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
                            <span class="logoText">呱唧用户注册</span>
			</a>
			<span style="float:right;font-size:15px;margin:40px 30px 0 0;"><i>guaji.yikaihui.com</i></span>
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
				 <dd class="checkcode" style=" <?php if ($_smarty_tpl->tpl_vars['login_num']->value<3) {?>display: none;<?php }?> cursor: pointer">
					<div class="inputBox2">
						<!--//<label for="checkcode">请输入验证码</label>//-->
						<input id="loginCode" name="loginCode" type="text" class="input" placeholder="请输入验证码" target="<?php echo $_smarty_tpl->tpl_vars['login_num']->value;?>
"/>
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
			  <?php echo $_smarty_tpl->tpl_vars['COMPANY_NAME']->value;?>

			  <?php echo $_smarty_tpl->tpl_vars['COMPANY_COPR']->value;?>

			  <?php echo $_smarty_tpl->tpl_vars['COMPANY_ICP']->value;?>

			  </span> 
			  <span class="text">24小时服务热线：<?php echo $_smarty_tpl->tpl_vars['COMPANY_SERVER_TEL']->value;?>
</span> 
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
				  location = "<?php echo site_url('main/index');?>
";
				  _t.removeClass("false");	
				}else{
				   $(data.error_id).focus();
				   countNumber=data.other_msg.login_num;
				   showcode(countNumber);
				   $('.error').text(data.prompt_text);	
				   _t.removeClass("false");		   
				   return false;
				}
                            },
                            error:function(){}
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
<?php }} ?>
