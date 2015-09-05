<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>云企管理中心</title>
</head>
<body>
	<!--init-消息管理.html-->
	<div class="contHead">
		<span class="title01">消息管理</span>
		<div class="contHead-right">
			<div class="headSearch">
				<div class="combo searchBox">
					<a class="icon" ></a>
					<label class="label">请输入查询条件</label>
					<input class="input" />
				</div>
			</div>
		</div>
	</div>
	<!-- end contHead -->
	<div class="cont-wrapper">
		<ul class="infoNav">
			<li class="first">任务</li>
			<li>消息</li>
			<li class="selected">通知<span class="nums" style="display: none"><b>1</b></span></li>
		</ul>
		
		<div class="msg-list"  style="display: none">
			<div class="msg-bar">
				<div class="msg-bar-left">还没有任何任务</div>
				<div class="msg-bar-right">
					<div class="select" onclick="toggleSelect(this,event)"><span>查看</span>
						<ul class="menu">
							<li><a >全部</a></li>
							<li><a href="index.html">未读</a></li>
							<li><a href="index.html">已读</a></li>
						</ul>
					</div>   
					<div class="fr rightLine"><a  class="refresh">刷新</a></div>
					<div class="fr rightLine">&nbsp;</div>
				</div>
			</div>
		</div>
		
		<div class="msg-list"  style="display: none">
			<div class="msg-bar">
				<div class="msg-bar-left">还没有任何消息</div>
				<div class="msg-bar-right">
					<div class="select" onclick="toggleSelect(this,event)"><span>查看</span>
						<ul class="menu">
							<li><a >全部</a></li>
							<li><a href="index.html">未读</a></li>
							<li><a href="index.html">已读</a></li>
						</ul>
					</div>   
					<div class="fr rightLine"><a  class="refresh">刷新</a></div>
					<div class="fr rightLine">&nbsp;</div>
				</div>
			</div>
		</div>
			
		<div class="msg-list" style="display: block">
			<div class="msg-bar">
				<div class="msg-bar-left">有<span class="red">1</span>条未读通知</div>
				<div class="msg-bar-right">
					<div class="select" onclick="toggleSelect(this,event)"><span>查看</span>
						<ul class="menu">
							<li><a >全部</a></li>
							<li><a href="index.html">未读</a></li>
							<li><a href="index.html">已读</a></li>
						</ul>
					</div>   
					<div class="fr rightLine"><a  class="refresh">刷新</a></div>
					<div class="fr rightLine">&nbsp;</div>
				</div>
			</div>
			
			<ul class="msg-li"><li class="new"><a  onclick="showDetailPage('msg1.html',this)">欢迎使用全时云企</a> <span class="time">17:35</span>  </li></ul>
		</div>
	</div>
	<div class="cont-wrapper" id="detailMsg" style="display: none"></div>
</body>
<script type="text/javascript">
	function showDetailPage(url,t) {
		$("#detailMsg").show().load(url).siblings(".cont-wrapper").hide();
		var num = $(t).parents(".msg-list").find(".msg-bar-left span.red").text();
		var num2 = $(".hlItem .email span.nums b").text();
		if(num-1>0){
			$(".msg-bar-left span.red").text(num-1);
		}
		else {
			$(t).parents(".msg-list").find(".msg-bar-left").hide();
		}
		if(num2-1>0){
			$(".hlItem .email span.nums b").text(num2-1);
			$(t).parents(".msg-list").find(".msg-bar-left span.red").text(num-1)
			$(".infoNav li.selected span.nums b").text(num-1);
		}
		else {
			$(".hlItem .email span.nums").hide();
			$(".infoNav li.selected span.nums b").text("0");
		}
		$("#detailMsg").find(".back").live("click",function(){
			$("#detailMsg").hide().siblings(".cont-wrapper").show();
		})
		
		$(t).parents("li").removeClass("new")
		
	}
	$(function(){		  
		  $(".infoNav li").click(function(){
			var index = $(this).index();
			
			$(".msg-list").eq(index).show().siblings(".msg-list").hide();
			$(this).find(".nums").hide();
			
			$(this).siblings("li").each(function(){
				
				if($(this).find(".nums b").text()>0){
					$(this).find(".nums").show();
				}
				else {
					$(this).find(".nums").hide();
				}
			});
			
			$(this).addClass("selected").siblings().removeClass("selected");
		  })
		  
			$(".refresh").click(function(){
				$(this).parents(".msg-bar").append('<span class="reloading">正在刷新...</span>');
				setTimeout(function(){
					$(".reloading").remove();
				},2000)
			})	
		
		
		//组织结构树
	});
</script>
</html>