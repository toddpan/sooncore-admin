<!doctype html>
<html>
<head>
<base href="{$tag_base_url}" />
<meta charset="utf-8">
<title>免费试用蜜蜂</title>
<link href="public/css/css_trycount/style.css" rel="stylesheet" />
</head>

<body>
	<div class="header">
		<div class="headerInner">
			<a class="logo" ><img src="public/images/images_trycount/logo.png" /></a>
			
            <a class="header-link" href="http://www.quanshi.com">返回全时首页</a>
		</div>
	</div>
	<!-- end header -->
	
    <div class="main">
    	<div class="breadcrumb">免费试用蜜蜂</div>
        <h2>创建您的管理员密码</h2>
        
        <div class="form-box" style="width:540px; margin-top: 10px; margin-bottom: 60px">
		    <div class="form-box-bar" style="">
企业域名： 　{$site_url}<br>
<span style="font-size:12px;color:#9f9f9f;display:block;padding:0 0 10px 100px;">您可以通过企业域名访问蜜蜂管理中心，正式用户可以定制自己的企业域名。</span>
管理员帐号：{$loginName}
			</div>
			
		    <div id="email" class="form-item error">
		      <span style="display: inline-block; width: 7em;">管理员密码</span>
		      <input type="password" id="admin_password" maxlength="45" name="userEmail" class="form-text valid" placeholder="" value="" style="padding-left:8px; width: 225px">
              <div style="display: none; left: 360px" class="form-error">错误信息</div>
		      
		    </div>
		    
		    <div class="form-item">
		      <span style="display: inline-block; width: 7em;">再次输入密码</span>
		      <input type="password" id="admin_password_again" maxlength="45" name="userName" class="form-text valid" placeholder="" value="" style="padding-left:8px;  width: 225px">
              <div style="display:none; left: 360px" class="form-error">密码不一致</div>
		    </div>
		    
		    
	  </div>
      <div class="form-footer" style="margin-bottom: 50px;">
            	<a  class="form-btn" id="sbmit_info" user_id="{$user_id}">提 交</a>
                <a href="http://www.quanshi.com" class="gray-link">取 消</a>
            </div>
    </div>
    
	<div class="footer">
		<div class="footerInner">
			<span class="text rightLine">创想空间商务通信服务有限公司 @copyright 2001-2011 京ICP备0500547号</span>
			<span class="text">24小时服务热线：400-810-1919</span>
		</div>
	</div>
	<!-- end footer -->

<script type="text/javascript" src="public/js/jquery.js"></script>
<script type="text/javascript" src="public/js/js_trycount/common.js"></script>
    <script type="text/javascript">
	$(function(){
		$('#sbmit_info').click(function()
		{
            var admin_password=$('#admin_password').val();
            var admin_password_again=$('#admin_password_again').val();
            var count=0;
            if(admin_password=="")
            {
                $('#admin_password').parent().addClass("error");
                $('#admin_password').next().show();
                count++;
            }
            if(admin_password_again=="" || admin_password_again!=admin_password)
            {
                 $('#admin_password_again').parent().addClass("error");
                 $('#admin_password_again').next().show();
                 count++;
            }
            if(count!=0)
            {
                 return;
            }
            else
            {
                var obj={
                "user_id":$(this).attr("user_id"),
                "password":admin_password,
                "confirm_password":admin_password_again
                }
                var path = 'crippleware/crippleware/save_manage_pwd';
                $.post(path,obj,function(data)
                {
                	//alert(data)
                   if(data.code==0)
                   {
                   		location = "crippleware/crippleware/apply_crippleware_suc?user_id=" + user_id;
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
