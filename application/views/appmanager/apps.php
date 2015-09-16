<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--init-应用管理.html-->
<div class="contHead">
	<span class="title01">应用管理</span>
</div>
<h3 class="conH3">您可将企业应用与sooncore平台进行集成，让员工能通过sooncore平台接收各项应用消息，提高工作效率。</h3>
<p class="sub-content">您目前并未集成任何应用，请使用全时提供的 API 进行集成。了解更多关于 API 集成方式，<a >请按这里</a>。</p>
<script type="text/javascript">
	$(function(){
		$('.link_checkReport').click(function(){
			loadPage('report/financialAnalysisReport','report');
		});
		$('.btn_ldapSyn').click(function(){
			$('.leftMenu > li').removeClass("selected").eq(1).addClass("selected");
			loadCont('ldap/ldapList');
		});
		
		$('.btn_toLoad').click(function(){
			//url_group = '组织与员工_批量导入.html';
			$('.leftMenu > li a').eq(1).click();
		});
	});
</script>
</body>
</html>