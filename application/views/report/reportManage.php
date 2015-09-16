<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--init-报告管理.html-->
<div class="contHead">
	<span class="title01">报告管理</span>
</div>
<h3 class="conH3">您现在没有报告,请先进行组织管理的设置。</h3>
<p class="sub-content">您可以通过财务、帐号和使用情况多维度来查看企业用户的数据，帮助您更好的提高协作效率。</p>
<script type="text/javascript">
	$(function(){
		$('.link_checkReport').click(function(){
			loadPage('报告管理_财务分析报告.html','report');
		});
		$('.btn_ldapSyn').click(function(){
			$('.leftMenu > li').removeClass("selected").eq(1).addClass("selected");
			loadCont('组织与帐号_LDAP同步1.html');
		});
		
		$('.btn_toLoad').click(function(){
			//url_group = '组织与员工_批量导入.html';
			$('.leftMenu > li a').eq(1).click();
		});
	});
</script>
</body>
</html>