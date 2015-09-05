<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
<link href="css/common.css" rel="stylesheet" />
<link href="css/datepicker.css" rel="stylesheet" />
<link href="css/tree.css" rel="stylesheet" />
<link href="css/ldap.css" rel="stylesheet" />
<link href="css/jquery.jscrollpane.css" rel="stylesheet" />
<link href="zTreeStyle/zTreeStyle.css" rel="stylesheet" />
</head>

<body>
<!--import-layout.html-->
	<div class="pageBody">
		<div class="header">
			<a class="logo" onclick="import-layout.html"><img src="images/logo.png" /></a>
			<!-- end logo -->
			<ul class="headerLink">
				<li class="hlItem">
					<a class="user" ><span class="text">李想</span><span class="icon"></span></a>
					<ul class="menu" style="width: 86px">
						<li><a class="changePwd" >修改密码</a></li>
						<li><a href="index.html">注销</a></li>
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
            <div class="poptip">
                您可以指定管理员帮您管理
                <a class="btnRed btn_iKnow" ><span class="text">知道了</span><b class="bgR"></b></a>
            </div>
		</div>
		<!-- end header -->
		<div class="content clearfix">
			<ul class="leftMenu">
				<li>
					<a class="main"  onclick="loadPage('init-import-home.html','main')"><span class="icon">首页</span></a>
				</li>
				<li class="selected">
					<a class="group"  onclick="loadPage('组织与员工.html','group')"><span class="icon">组织管理</span></a>
				</li>
                <li>
					<a class="company"  onclick="loadPage('init-stqy.html','company')"><span class="icon">企业生态</span></a>
				</li>
				<li>
					<a class="report"  onclick="loadPage('报告管理_财务分析报告.html','report')"><span class="icon">报告管理</span></a>
				</li>
				<li>
					<a class="app"  onclick="loadPage('应用管理.html','app')"><span class="icon">应用管理</span></a>
				</li>
				<li>
					<a class="safe"  onclick="loadPage('安全管理_密码管理.html','safe')"><span class="icon">安全管理</span></a>
				</li>
				<!--<li>
					<a class="friend" ><span class="icon">合作伙伴</span></a>
				</li>-->
				<li>
					<a class="system"  onclick="loadPage('系统设置_企业信息设置.html','system')"><span class="icon">系统设置</span></a>
				</li>
			</ul>
			<!-- end leftMenu -->
			<div class="rightCont clearfix">
				<div class="contHead">
                    <span class="title01">组织管理</span>
                    <div class="contHead-right"><div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"  ></a></div>
                    <div class="headSearch rightLine">
                        <div class="combo searchBox">
                            <b class="bgR"></b>
                            <a class="icon" ></a>
                            <label class="label">请输入查询条件</label>
                            <input class="input" />
                        </div>
                    </div>
                    
                     <ul class="menu" id="menu1">
                            <li><a  onclick="loadCont('组织与员工_批量导入.html')">员工标签管理</a></li>
                            <!--<li><a  onclick="loadCont('组织与员工_批量修改员工.html');">批量修改</a></li>-->
                            <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">添加LDAP设置</a></li>
                        </ul>
                    </div>
                    </div>
                
                <div class="feedBackBox">
                    <h3 class="conH3">成功导入1097个帐号，失败3个</h3>
                    <div class="grayBox listBox">
                        <ul class="list">
                            <li>
                                <span class="submitWarning">失败原因：</span>
                            </li>
                            <li class="errorMsgList">
                                <span class="errorText01">模板信息标签未填写完整；</span>
                                <span class="errorText01">手机或邮箱格式不正确，如：手机位数不对，邮箱格式不正确。</span>
                                <span class="errorText01">手机或邮箱信息相同。</span>
                                <span class="errorText02">
                                    您可以下载导入失败的列表，并查看其原因<br />
                                    <a class="btnGray" href="组织与员工_导入失败列表.xlsx" style="margin-bottom:20px;"><span class="text">下载失败列表</span><b class="bgR"></b></a>
                                </span>
                            </li>
                        </ul>
                        <b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b>
                    </div>
                    <a href="javascript:loadCont('组织与员工_批量导入_下载模板上传文档.html');" class="linkGoback" style="margin-left:0">&lt;&lt;&nbsp;返回继续导入&nbsp;</a>  &nbsp; &nbsp; &nbsp;<span class="rightLine">&nbsp;</span>
                    <a href="javascript:loadCont('组织与员工.html');" class="linkGoback">查看已导入的组织员工&nbsp;&gt;&gt;</a>
                </div>
				
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
	


</body>
</html>
