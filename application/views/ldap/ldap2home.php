<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
<!--init-ldap2-home.html-->
<div class="contHead">
	<span class="title01 rightLine">首页</span><span class="title02">欢迎使用云企管理后台。</span>
</div>

<div class="block" style="padding-top: 0; margin: 0; border: none">
	<h2>帐号情况</h2>
    <div class="block-content">
    	<div class="block-content-left">
   	    	<div class="chatAccount"><dl><dt>共有10,000名员工</dt>
<dd class="c1">已开通帐号7,000个</dd>
<dd class="c2">未开通帐号3,000个</dd></dl></div>
        </div>
        <div class="block-content-right">
        	<div class="chatAccount"><dl><dt>共有7,000个帐号</dt>
<dd class="c1">已启用帐号5,000个</dd>
<dd class="c2">未启用帐号2,000个</dd></dl></div> 
        </div>
    </div>
</div>

<div class="block">
	<h2>过去七天登录情况</h2>
    <div class="block-content">
    	<div class="chatOneWeek"><img src="public/images/chart13_1.jpg" width="143" height="19" alt="" /></div>
    </div>
</div>

<div class="block">
	<div class="block-left">
   	  <h2><span class="rl">最新通知</span> <a  class="more more_list_1">更多</a></h2>
        <div class="block-content">
       	  <ul class="news-list">
       	    <li><a >研发部管理者王明把赵然调岗到营销部</a></li>
       	    <li><a >市场部管理者吴晓琪将王小薇设为离职</a></li>
       	    <li><a >销售部与华为建立了合作伙伴</a></li>
       	    <li><a >研发部李想添加了一位新员工赵欣然</a></li>
   	      </ul>
   	    </div>
    </div>
    <div class="block-right">
   	  <h2><span class="rl">最新消息</span> <a  class="more more_list_2">更多</a></h2>
        <div class="block-content">
       	  <ul class="news-list">
       	    <li>暂无消息</li>
   	      </ul>
   	    </div>
    </div>
</div>
<script type="text/javascript" src="public/js/common.js"></script>
<script type="text/javascript" src="public/js/self_common.js"></script>
<script type="text/javascript">
	$(function() {
    $('.link_checkReport').click(function() {
        loadPage('报告管理_财务分析报告.html', 'report');
    });
    $('.more_list_1').click(function() {
        loadCont("<?php echo site_url('information/infoManPage')?>");
        $('.leftMenu > li').removeClass('selected');
        var target = 1;
        setCookie('more_target', target, 30);
        //$('.headerLink .hlItem:eq(1)').addClass("bg").siblings().removeClass("bg");
    });
    $('.more_list_2').click(function() {
        loadCont("<?php echo site_url('information/infoManPage')?>");
        $('.leftMenu > li').removeClass('selected');
        var target = 2;
        setCookie('more_target', target, 30);
        //$('.headerLink .hlItem:eq(1)').addClass("bg").siblings().removeClass("bg");
    });

});
function toAdminPage() {
    location.href = 'ldap-layout.html#admin';
}
function adminBySelf() {
    location.href = 'ldap-layout.html#self';
}
</script>
</body>
</html>