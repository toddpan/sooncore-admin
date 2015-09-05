<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;" lang="zh-cn">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
		<meta http-equiv="pragma" content="no-cache">
		<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
		<meta http-equiv="expires" content="Wed, 26 Feb 1997 08:21:57 GMT">
		<base href="{site_url('')}"/>
		<base target="_blank" />
		<title>新用户注册 - {$COMPANY_NAME}</title>
		<link href="public/css/login.css" rel="stylesheet" />
		<link href="public/css/register.css" rel="stylesheet" />
        <script type="text/javascript" src="public/js/jquery.js"></script>
        <script type="text/javascript" src="public/js/register.js"></script>
	</head>
	<body>
		<div class="header">
		  <div class="headerInner">
                      <a class="logo" style="text-decoration:none;" href="main" target="_self">
                            <span style="font-size:45px;">
                                <img src="public/images/guaji_logo.png" />
                            </span>
                            <span class="logoText">呱唧用户注册</span>
			</a>
			<span style="float:right;font-size:15px;margin:40px 30px 0 0;"><i>guaji.yikaihui.com</i></span>
		  </div>
		</div>
                <div class="regCont">
                    <script type="text/javascript">
                        //设置窗口高度
                        var windowHeight = $(window).height();
                        var newHeight = windowHeight-88-47;
                        if(newHeight>650){
                            //alert(windowHeight);
                            $(".regCont").css("height",newHeight+"px");
                        }
                    </script>
                    <div class="regBox">
                        <div class="leftBox">
                            <div class="stepBox">
                                <ul>
                                    <li {if $REGISTER_STEP>=1 }class="stepHover"{/if}>
                                        <i>1</i><span>验证邮箱</span>
                                    </li>
                                    <li {if $REGISTER_STEP>=2 }class="stepHover"{/if}>
                                        <i>2</i><span>完善资料</span>
                                    </li>
                                    <li {if $REGISTER_STEP==3 }class="stepHover"{/if}>
                                        <i>3</i><span>完成</span>
                                    </li>
                                </ul>
                            </div>
                            
                                {if ($REGISTER_STEP==1)}
                                <!--//STEP1//-->
                                <form id="step1" method="post" target="_self" action="register/index/2">
                                    <table class="infoTable">
                                        <tbody>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <td colspan="3">
                                                        <span class="errorMsg" style="display: none;">错误提示</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>你的邮箱：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                                <input class="input" name="user_email" id="user_email" value="" placeholder="请输入你的邮箱">
                                                        </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>图片验证码：</th>
                                                <td width="118">
                                                        <div class="inputBox" style="width:118px;">
                                                            <input class="input" value="" onchange="checkCode(this.value);" name="pass_word_code" placeholder="输入图片验证码" id="pass_word_code" style="width: 110px;">
                                                        </div>
                                                </td>
                                                <td class="code">
                                                  <div style="padding-left:10px;">
                                                      <img id="forgetpwdcode" src="register/code/{time()}" width="78" height="26" title="看不清，换一张？" onclick="this.src='register/code/'+Math.random();" style="cursor: pointer;vertical-align: top;">
                                                      <span class="sendCode" onclick="sendMail();" style="cursor: pointer">发送验证邮件</span>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>邮件验证码：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                                <input class="input" name="mail_code" id="mail_code" value="" placeholder="请输入你从邮箱中获得的验证码">
                                                        </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="submit" style="text-align:center;">
                                        <input type="submit" class="regBtn" id="regBtn" onclick="checkStep(1);" value="下一步" />
                                    </div>
                                </form>
                                {elseif ($REGISTER_STEP==2)}
                                <!--//STEP2//-->
                                <form id="submitForm">
                                <table class="infoTable">
                                    <tbody>
                                        <tr>
                                                <th>&nbsp;</th>
                                                <td colspan="3">
                                                        <span class="errorMsg" style="display: none;">错误提示</span>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>登陆帐号：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                                <input class="input" name="login_name" id="login_name" value="{$USER_EMAIL}" placeholder="输入你的登录账号(email格式)">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr style="display: none;">
                                                <th>邮箱：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                            <input class="input" name="user_email" id="user_email" value="{$USER_EMAIL}" readonly="readonly" placeholder="email">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>新的密码：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                            <input class="input" type="password" name="user_pwd" id="user_pwd" value="" placeholder="请输入你的密码" maxlength="20">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>重复密码：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                                <input class="input" type="password" name="user_cfPwd" id="user_cfPwd" value="" placeholder="重复上面的密码" maxlength="20">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>你的名字：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                            <input class="input" name="display_name" id="display_name" value="" placeholder="怎么称呼您">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>企业名称：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                            <input class="input" name="company_name" id="company_name" value="" placeholder="你的公司名称">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>公司网址：</th>
                                                <td colspan="3">
                                                        <div class="inputBox">
                                                            <input class="input" name="site_url" id="site_url" value="" placeholder="例如 www.yourdomain.com">
                                                        </div>
                                                </td>
                                        </tr>
                                        <tr>
                                                <th>联系电话：</th>
                                                <td colspan="3">
                                                        <div class="inputBox" style="width: 40px; float: left; margin-right: 5px;">
                                                            <input class="input" name="country_code" id="country_code" value="86" placeholder="国码" style="width: 28px; text-align: center;">
                                                        </div>
                                                        <div class="inputBox" style="width: 50px; float: left; margin-right: 5px;">
                                                            <input class="input" name="area_code" id="area_code" value="" placeholder="区号" style="width: 38px; text-align: center;">
                                                        </div>
                                                        <div class="inputBox" style="width: 254px; float: left;">
                                                            <input class="input" name="mobile_number" id="mobile_number" value="" placeholder="电话号码" style=" width: 242px;">
                                                        </div>
                                                </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit" style="text-align:center;">
                                    <input type="button" class="regBtn" id="regBtn" onclick="submitForm();" value="下一步" />
                                </div>
                            </form>                  
                            {elseif ($REGISTER_STEP==3)}
                            <!--//STEP3//-->
                            <div class="finsh">
                                <div class="finsh-msg">恭喜，帐户创建成功！</div>
                                <a href='login/loginPage' target="_self">马上登陆</a>
                            </div>
                            {/if}
                        </div>
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
                
            <script type="text/javascript">
            //绑定焦点事件，改变背景色
            $(".inputBox>.input").bind("focus",function(){
                    //alert(this.value);
                    $(this).parent(".inputBox").addClass("focus");
                }
             );
            $(".inputBox>.input").bind("blur",function(){
                    $(this).parent(".inputBox").removeClass("focus");
                }
             );

             $("#login_name").bind("change",function(){
                checkLoginN(this);//判断登录名是否正确
             });

            $("#user_email").bind("change",function(){
                var emailStatus = checkLoginN(this);//判断EMAIL是否正确
                if(emailStatus===false){
                    $(".sendCode").css("display","none");
                }else{
                    $(".sendCode").css("display","inline-block");
                }
            });
            
            $("#mail_code").bind("change",function(){
                checkEmailCode(this);//判断Email收到的验证码
            });

            $("#site_url").bind("change",function(){
                checkUrl($(this).val());//判断URL
            });
            
            $("#user_cfPwd").bind("change",function(){
                checkPwd($(this));//判断URL
            });
            </script>
	</body>
        <div id="msgBox" style="display:none;">
            <div id="msgBg"></div>
            <div id="content">
                请稍等片刻，正在处理数据...
            </div>
        </div>
</html>
