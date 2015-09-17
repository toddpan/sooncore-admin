<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--组织与员工_员工信息.html-->
<div class="contHead">
	<span class="title01">组织管理</span>
    <div class="contHead-right">
	<div class="fr rightLine"><a class="btnSet" ></a></div>
	<!--<div class="fr rightLine"><a class="btnText" href="javascript:loadCont('组织与员工_批量导入_下载模板上传文档2.html');">批量导入</a></div>-->
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
    </div>
</div>
<!-- end contHead -->
<div class="conTabs">
	<ul class="conTabsHead">
		<li class="selected">组织结构<span class="conline"></span></li>
		<li>成本中心</li>
	</ul>
	<!-- end conTabsHead -->
	<dl class="conTabsCont">
		<dd style="display:block;">
			<div class="toolBar">
				<a class="addGroup"  title="添加组织结构"></a>
				<a class="delGroup"  title="删除组织结构"></a>
			</div>
			<ul class="tree" style="display:block;">
				<li>
					<a class="treeNode" >
						<b class="treeNodeArrow open"></b>
						<span class="treeNodeName">海尔</span>
					</a>
					<ul class="tree subTree" style="display:block;">
						<li>
							<a class="treeNode" >
								<b class="treeNodeArrow open"></b>
								<span class="treeNodeName">海尔手机电子事业部</span>
							</a>
							<ul class="tree subTree" style="display:block;">
								<li>
									<a class="treeNode selected" >
										<b class="treeNodeArrow"></b>
										<span class="treeNodeName">研发部</span>
									</a>
								</li>
								<li>
									<a class="treeNode" >
										<b class="treeNodeArrow"></b>
										<span class="treeNodeName">市场部</span>
									</a>
								</li>
								<li>
									<a class="treeNode" >
										<b class="treeNodeArrow"></b>
										<span class="treeNodeName">营销部</span>
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="treeNode" >
								<b class="treeNodeArrow close"></b>
								<span class="treeNodeName">海尔生活家电事业部</span>
							</a>
							<ul class="tree subTree">
								<li>
									<a class="treeNode" >
										<b class="treeNodeArrow"></b>
										<span class="treeNodeName">市场部</span>
									</a>
								</li>
								<li>
									<a class="treeNode" >
										<b class="treeNodeArrow"></b>
										<span class="treeNodeName">营销部</span>
									</a>
								</li>
							</ul>
						</li>
						<li>
							<a class="treeNode" >
								<b class="treeNodeArrow"></b>
								<span class="treeNodeName">海尔电脑事业部</span>
							</a>
						</li>
					</ul>
				</li>
			</ul>
			<!-- end tree -->
		</dd>
		<dd>
			<div class="toolBar">
				<a class="addGroup"  title="添加成本中心"></a>
				<a class="delGroup"  title="删除成本中心"></a>
			</div>
			<ul class="tree" style="display:block;">
				<li>
					<a class="treeNode selected" >
						<span class="treeNodeName">未指定成本中心员工</span>
					</a>
				</li>
			</ul>
			<!-- end tree -->
		</dd>
	</dl>
	<!-- end conTabsCont -->
</div>
<!-- end conTabs -->
<div class="contRight">
		<a class="link_limitSet"  onclick="toggleGroupLimit()" title="部门权限">部门权限</a>
		<div class="bread"><span>海尔</span>&nbsp;&gt;&nbsp;<span>海尔手机电子事业部</span>&nbsp;&gt;&nbsp;<span>研发部</span></div>
		<!-- end bread -->
		<div class="infoTitle">
			<a  class="pageGoBack"></a>
			<span class="personName">李想</span>
		</div>
        <div class="cont-wrapper">
		<ul class="infoNav">
			<li>员工信xxx息</li>
			<li>员工权限</li>
		</ul>
        </div>
        
        <div class="groupLimit" style="display: none">
			<b class="arrow"></b>
            <div class="groupLimitContent">
			<div class="toolBar2">
				<a class="btnBlue yes"  onclick="$('.groupLimit').hide();"><span class="text">保存</span><b class="bgR"></b></a>
				<a class="btnGray btn"  onclick="$('.groupLimit').hide();"><span class="text">取消</span><b class="bgR"></b></a>
			</div>
			<!-- end tabToolBar -->
			 <h3 class="setTitle">IM设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 可使用全时sooncore平台 IM 互传文档</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 服务器保存聊天记录</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许连接外部 IM</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设置接听策略</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设定接听策略到海外电话</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"  /> 允许使用全时sooncore平台拨打电话</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许拨打海外电话</label>
                    <h3 class="setTitle">电话会议设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许自我外呼</label>
                    <label class="checkbox checked"> <input type="checkbox" checked="checked" /> 允许参会人主动接入</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 电话会议自动报名</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 参会人加入会议语音状态</label>
                     
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 参会人退出会议语音提示</label>
                    <label class="checkbox checked"> <input type="checkbox" checked="checked" /> 主持人加入会议语音提示</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 主持人退出会议语音提示</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许使用硬件视频</label>
                    
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 接入号设置</label>
                    <label class="checkbox checked"> <input type="checkbox" checked="checked" /> 主持人未入会，只要会议有人入会，会议就开始</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 主持人退会，会议是否停止</label>

                    <h3 class="setTitle">网络会议配置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 用户默认语音接入方式</label>
                    <label class="checkbox checked"> <input type="checkbox" checked="checked" /> 允许用户邀请站点外用户加入会议</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 会议结束显示会后营销页面</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 安排会议时，根据用户使用语言配置会议模板语言</label>
              </div>       
		</div>
</div>
<!-- end contRight -->

<script type="text/javascript">
	$(function(){
		 checkbox();
		 
		 $('.conTabsHead > li').click(function(){
			var ind = $(this).index();
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.conTabsCont > dd').eq(ind).show().siblings().hide();
			$('.contRight > div').eq(ind).show().siblings().hide();
		});
	
		//组织结构树
		$('.treeNode').each(function(){
			var _this = $(this);
			var pNum = _this.parents('.tree').length;
			_this.css('padding-left', 6+(pNum-1)*16+'px');
		});
		$('.treeNodeArrow').click(function(){
			var _this = $(this);
			if(_this.hasClass('close')){
				_this.removeClass('close').addClass('open').parent().siblings('.subTree').show();
			}else if(_this.hasClass('open')){
				_this.removeClass('open').addClass('close').parent().siblings('.subTree').hide();
			}
			return false;
		});
		$('.treeNode').click(function(){
			$('.treeNode').removeClass('selected');
			$(this).addClass('selected');
		});
		
		$(".checkbox").click(function(){
			$(".toolBar2").show();
		})
	});
</script>
</body>
</html>