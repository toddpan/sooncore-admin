<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
<link href="<?php echo base_url('public/css/common.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/self_common.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/datepicker.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/tree.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/ldap.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('public/css/jquery.jscrollpane.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('public/zTreeStyle/zTreeStyle.css');?>" rel="stylesheet" />
</head>

<body>

	<div class="pageBody">
		<div class="header">
			<a class="logo" style="cursor: pointer"><img src="<?php echo base_url('public/images/logo.png');?>" /></a>
			<!-- end logo -->
			<ul class="headerLink">
				<li class="hlItem">
					<a class="link user" style="cursor: pointer"><span class="text">李想</span><span class="icon"></span></a>
					<div class="menu" style="width: 90px;">
						<dt><a class="changePwd">修改密码</a></dt>
						<dt><a onclick="login/loginPage">注销</a></dt>
					</div>
				</li>
				<li class="hlItem">
					<a class="link email"><span class="text">消息</span><span class="icon">5</span></a>
					<span class="hArrow"></span>
                </li>
                <li class="hlItem">
					<a class="admin" ><span class="text">管理员管理</span></a>
                    <span class="hArrow"></span>
                </li>
				<li class="hlItem">
					<a class="link help" ><span class="text">帮助中心</span></a>
					<span class="hArrow"></span>
                </li>
			</ul>
			<!-- end headerLink -->
		</div>
		<!-- end header -->
		<div class="content clearfix">
			<ul class="leftMenu">
				<li class="selected">
					<a class="main" onclick="loadPage('main/mainPage1','main')"><span class="icon" style="cursor: pointer">首页</span></a>
				</li>
				<li>
					<a class="group" onclick="loadPage('organize/ldaporg','group')"><span class="icon" style="cursor: pointer" >组织管理</span></a>
				</li>
                 <li>
					<a class="company" onclick="loadPage('ecologycompany/ecologyPage','company')"><span class="icon" style="cursor: pointer">企业生态</span></a>
				</li>
				<li>
					<a class="report" onclick="loadPage('report/financialAnalysisReport','report')"><span class="icon" style="cursor: pointer">报告管理</span></a>
				</li>
				<li>
					<a class="app" onclick="loadPage('application/showAppPage','app')"><span class="icon" style="cursor: pointer">应用管理</span></a>
				</li>
				<li>
					<a class="safe" onclick="loadPage('password/PWDManagePage','safe')"><span class="icon" style="cursor: pointer">安全管理</span></a>
				</li>
				<!--<li>
					<a class="friend"><span class="icon">合作伙伴</span></a>
				</li>-->
				<li>
					<a class="system"  onclick="loadPage('setsystem/setSystemPage','system')"><span class="icon" style="cursor: pointer">系统设置</span></a>
				</li>
			</ul>
			<!-- end leftMenu -->
			<div class="rightCont clearfix">
				
				<!-- 此处内容为ajax加载 -->
				
			</div>
			<!-- end rightCont -->
		</div>
		<!-- end content -->
		<div class="footer">
			<span class="text rightLine">创想空间商务通信服务有限公司 @copyright 2001-2011 京ICP备0500547号</span>
			<span class="text">24小时服务热线：400-810-1919</span>
		</div>
		<!-- end footer -->
	</div>
	<!-- end pageBody -->
	
	<!--遮罩层-->
	<div class="mask"></div>
	
	<div id="dialog" class="dialog">
		<div class="dialogBorder">

		</div>
		<!-- 
		<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b><b class="shadow"></b>
		 -->
	</div>
	
<script type="text/javascript" src="public/js/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="public/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="public/js/tree.js"></script>
<script type="text/javascript" src="public/js/self_tree.js"></script>
<script type="text/javascript">
$(function() {
    var hash = location.hash.substring(1);
    switch (hash) {
    case "":

        loadPage("init-ldap2-home.html", "main");
        break;
    case "msg3":
        loadCont('information/infoManPage');
        $('.leftMenu > li').removeClass('selected');
        $('.headerLink .hlItem:eq(1)').addClass("bg").siblings().removeClass("bg");
        break;
    case "admin":
        loadPage('组织与员工-管理员-ldap.html', 'group');
        break;
    case "self":
        loadPage('organize/ldaporg', 'group');
        break;
    case "companyAdmin":

        loadCont('init-stqy3.html');
        $('.leftMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
        break;
    case "ldapList":

        loadCont('ldap/getLdapList');
        $('.leftMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
        break;
    default:
        $(".leftMenu a." + hash).trigger("click");
    }

    $('.headerLink .email').click(function() {
        loadPage("information/infoManPage2", "msg3");
        $('.leftMenu > li').removeClass('selected');
        $('.headerLink .hlItem:eq(1)').addClass("bg").siblings().removeClass("bg");
    });
    $(".leftMenu a").click(function() {
        $('.headerLink .hlItem').removeClass("bg");
    }) $('.link .icon').click(function() {
        $('.menu_1').toggle();
    }) $('a.logo').click(function() {
        loadPage('main/mainPage1', 'main');
    })
    /*$('.changePwd').click(function(){
		loadCont("mixture/resetPwd");
		$('.leftMenu > li').removeClass('selected');
	});*/
});
	</script>
</body>
</html>
