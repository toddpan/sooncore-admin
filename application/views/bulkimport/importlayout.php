<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>

<body>
<!--import-layout.html-->
	<div class="pageBody">
		<div class="header">
			<a class="logo" onclick="import-layout.html"><img src="public/images/logo.png" /></a>
			<!-- end logo -->
			<ul class="headerLink">
				<li class="hlItem">
					<a class="user" ><span class="text">李想</span><span class="icon"></span></a>
					<ul class="menu" style="width: 86px">
						<li><a class="changePwd" >修改密码</a></li>
						<li><a onclick="index.html">注销</a></li>
					</ul>
				</li>
				<li class="hlItem">
					<a class="email" ><span class="text">消息</span><span class="icon">5</span></a>
                    <span class="hArrow"></span>
				</li>
                <li class="hlItem">
					<a class="admin" ><span class="text">管理员管理</span></a>
                    <span class="hArrow"></span>
                </li>
				<li class="hlItem">
					<a class="help" ><span class="text">帮助中心</span></a>
                    <span class="hArrow"></span>
				</li>
			</ul>
			<!-- end headerLink -->
		</div>
		<!-- end header -->
		<div class="content clearfix">
			<ul class="leftMenu">
				<li class="selected">
					<a class="main"  onclick="loadPage('main/mainPage','main')"><span class="icon">首页</span></a>
				</li>
				<li>
					<a class="group"  onclick="loadPage('ecologycompany/organizeStaff','group')"><span class="icon">组织管理</span></a>
				</li>
                <li>
					<a class="company"  onclick="loadPage('ecologycompany/ecologyPage','company')"><span class="icon">企业生态</span></a>
				</li>
				<li>
					<a class="report"  onclick="loadPage('report/financialAnalysisReport','report')"><span class="icon">报告管理</span></a>
				</li>
				<li>
					<a class="app"  onclick="loadPage('应用管理.html','app')"><span class="icon">应用管理</span></a>
				</li>
				<li>
					<a class="safe"  onclick="loadPage('<?php echo site_url('password/PWDManagePage');?>','safe')"><span class="icon">安全管理</span></a>
				</li>
				<!--<li>
					<a class="friend" ><span class="icon">合作伙伴</span></a>
				</li>-->
				<li>
					<a class="system"  onclick="loadPage('<?php echo site_url('setsystem/setSystemPage');?>','system')"><span class="icon">系统设置</span></a>
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
		<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b><b class="shadow"></b>
	</div>
	
	<script type="text/javascript" src="public/js/jquery.js"></script>
	<script type="text/javascript" src="public/js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="public/js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="public/js/jquery.ui.datepicker.js"></script>
	<script type="text/javascript" src="public/js/jquery.tree.js"></script>
    <script type="text/javascript" src="public/js/jquery.jscrollpane.min.js"></script>
<script type="text/javascript" src="public/js/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="public/js/common.js"></script>
	 <script type="text/javascript" src="public/js/self_common.js"></script>
	<!--处理IE6下透明png图片js-->
	<!--[if IE 6]>
	<script type="text/javascript" src="public/js/DD_belatedPNG.js" ></script>
	<script type="text/javascript">
		DD_belatedPNG.fix('.logo, .logo img');
	</script>
	<![endif]-->
    <script type="text/javascript">
		$(function(){
			var hash = location.hash.substring(1);
	
			switch(hash){
				case "":
				loadPage("main.html","main");
				break;
				case "msg3":
				loadCont('消息管理.html');
				$('.leftMenu > li').removeClass('selected');
				break;
				case "admin":
				loadPage('组织与员工-管理员.html','group');
				break;
				case "self":
				loadPage('组织与员工.html','group');
				login = 0;
				break;
				case "companyAdmin":
				loadCont('init-stqy3.html');
				$('.leftMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
				break;
				default:
				$(".leftMenu a."+hash).trigger("click");
			}

			$('.headerLink .email').click(function(){
				loadPage("消息管理.html","msg3");
				$('.leftMenu > li').removeClass('selected');
			});
		});
	</script>
</body>
</html>
