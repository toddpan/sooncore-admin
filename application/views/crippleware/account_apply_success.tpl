<!doctype html>
<html>
<head>
    <base href="{$tag_base_url}"/>
    <base target="_blank" />
    <meta charset="utf-8">
    <title>免费试用蜜蜂</title>
    <link href="public/css/css_trycount/style.css" rel="stylesheet" />
     <script type="text/javascript" src="public/js/jquery.js"></script>
      <script type="text/javascript" src="public/js/common.js"></script>
  <script type="text/javascript" src="public/js/js_trycount/common.js"></script>
</head>

<body>
  <div class="header">
    <div class="headerInner">
      <a class="logo" >
        <img src="public/images/images_trycount/logo.png" />
      </a>
      <a class="header-link" href="http://www.quanshi.com" target="_self">返回全时首页</a>
     </div>
  </div>

  <div class="main">
    <div class="breadcrumb">免费试用蜜蜂</div>
    <h2 style="margin-top: 50px;">帐号申请成功！</h2>
    <h3>恭喜您已经完成邮箱验证，请点击下方的按钮，验证您的手机号</h3>
    <div class="form-footer" style="margin: 50px 0;">
        <a target="_blank" href="{site_url('crippleware/crippleware/activation_phone_page?user_id')}={$user_id}" class="form-btn" value="验证手机号" id="check_phone" >验证手机号</a>
         <a href="http://www.quanshi.com" class="gray-link">取消</a>
    </div>
  </div>
  <div class="footer">
    <div class="footerInner">
      <span class="text rightLine">创想空间商务通信服务有限公司 @copyright 2001-2011 京ICP备0500547号</span>
      <span class="text">24小时服务热线：400-810-1919</span>
    </div>
  </div>
 
  
  </body>
</html>
