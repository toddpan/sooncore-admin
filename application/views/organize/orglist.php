<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--init-group.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title02">欢迎使用sooncore平台管理后台，管理组织与员工请先为员工设置统一的标签。</span>
</div>
<!--<div class="contTitle"><span class="tips">您可以通过以下两种方式创建组织管理。</span></div>
<a class="btn_icon btn_ldapSyn"  onclick="loadCont('组织与帐号_LDAP同步1.html');"><span class="iconL"><span class="iconR">LDAP同步</span></span></a>-->
<a class="setLabelBtn"  onclick="loadPage('tag/addTagPage');">设置员工标签</a>

<script type="text/javascript">
	$('.group').removeClass("false");
	$(function(){
		$('.link_checkReport').click(function(){
			//loadPage('报告管理_财务分析报告.html','report');
			loadPage('ldap2.php','report');
		});
	
	});
</script>
</body>
</html>
