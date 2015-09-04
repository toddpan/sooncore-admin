<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--组织与员工_员工信息权限.html-->
<div class="contHead">
	<span class="title01">组织管理</span>
	<div class="contHead-right"><div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"></a></div>
	<!--<div class="fr rightLine"><a class="btnText" href="javascript:loadCont('组织与员工_批量导入_下载模板上传文档2.html');">批量导入</a></div>-->
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
    
    <ul class="menu" id="menu1">
        <li><a  onclick="loadCont('staff/staffBatchImport')">员工标签管理</a></li>
            <li><a  onclick="loadCont('staff/batchModifyStaff');">批量修改</a></li>
     <!-- <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">LDAP设置</a></li> -->       
        </ul>
        
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
                <!--<div class="toolBar">
                    <a class="addGroup" id="addZuzhi" onclick="return false;"  title="添加组织结构"></a>
                    <a class="delGroup" id="deleteZuzhi" onclick="return false;"  title="删除组织结构"></a>
                </div>-->
                <div id="tree">
                	<ul class="ztree" id="ztree"></ul>
                </div>
                
                <!-- end tree -->
            </dd>
		<dd>
			 <!--<div class="toolBar">
                    <a class="addGroup addCenter"  onclick="addCenter()" title="添加成本中心"></a>
                    <a class="delGroup deleteCenter disabled"  onclick="showDeleteCenterDialog(this)" title="删除成本中心"></a>
                </div>-->
                <ul class="tree" id="centerTree" style="display:block;">
                    <li class="selected">
                        <a class="treeNode" >
                            <span class="treeNodeName">未指定成本中心</span>
                        </a>
                    </li>
                    <li>
                        <a class="treeNode" >
                            <span class="treeNodeName">成本中心二</span>
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
<div id="part01">
		<a class="link_limitSet"   onclick="toggleGroupLimit()" title="部门权限">部门权限</a>
		<div class="bread"><span>海尔</span>&nbsp;&gt;&nbsp;<span>海尔手机电子事业部</span>&nbsp;&gt;&nbsp;<span>研发部</span></div>
		<!-- end bread -->
		<div class="infoTitle">
			<a href="javascript:loadCont('组织与员工.html');" class="pageGoBack"></a>
			<span class="personName">李想</span>
            
            <div class="fr">
            	<a class="btn"  onclick="toggleAccount(this)"><span class="text">关闭帐号</span><b class="bgR"></b></a>&nbsp;
                <a class="btn"  onclick="showDialog('manager/resetPasssword')"><span class="text">重置密码</span><b class="bgR"></b></a>
            </div>
		</div>
        <div class="cont-wrapper">
		<ul class="infoNav">
			<li class="selected">员工信息</li>
			<li>员工权限</li>
		</ul>
		<dl class="infoCont">
			<dd>
				<div class="toolBar2">
					<a class="btnGray btn btn_infoEdit" ><span class="text">编辑信息</span><b class="bgR"></b></a>
					<a class="btnBlue yes btn_infoSave hide" ><span class="text" onclick="staff_change()">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
				</div>
				<table class="infoTable">
                	<tr>
						<td class="tr">&nbsp;</td>
						<td height="26">&nbsp;</td>
					</tr>
					<tr>
						<td class="tr">姓名：</td>
						<td>
							<span class="infoText">李想</span>
                            <div class="inputBox w360 hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" value="李想"  >
							</div>
						</td>
					</tr>
                    <tr>
						<td class="tr">帐号：</td>
						<td>
							<span class="infoText">liukaiwei@haier.com</span>
							<div class="inputBox w360 hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" value="liukaiwei@haier.com" />
							</div>
						</td>
					</tr>
                    <tr>
						<td class="tr">性别：</td>
						<td>
							<span class="infoText">男</span>
							<div class="radioBox hide">
								<label class="radio checked"><input name="xb" type="radio" value="男" checked="checked" /> 男</label>
                                <label class="radio"><input name="xb" type="radio" value="女" /> 女</label>
							</div>
						</td>
					</tr>
                    <tr>
						<td class="tr">部门：</td>
						<td>
							<span class="infoText">海尔手机电子事业部-研发部</span>
                            <div class="inputBox w360 hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" value="海尔手机电子事业部-研发部" />
							</div>
						</td>
					</tr>
                    <tr>
						<td class="tr">职位：</td>
						<td>
							<span class="infoText">研发经理</span>
							<div class="inputBox w360 hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" value="研发经理" />
							</div>
						</td>
					</tr>
					<tr>
						<td class="tr">手机：</td>
						<td>
							<span class="infoText">+86</span>
							<div class="combo selectBox w60 hide">
                                <a class="icon" ></a>
                                <span class="text selected">+86</span>
                                <div class="optionBox" style="display: none;">
                                    <dl class="optionList" style="height: 52px;">
                                        <dd class="option selected" target="0" style="">+86</dd>
                                        <dd class="option" target="1">+85</dd>
                                    </dl>
                                    <input type="hidden" class="val" value="0">
                                </div>
                            </div>
                            
                            -
                            <span class="infoText"s>13899336667</span>
                            <div class="inputBox w130 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="13899336667">
                            </div>
						</td>
					</tr>
				</table>
			</dd>
			<dd style="display:none;">
				<div class="toolBar2">
					<a class="btnBlue yes" ><span class="text">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn" ><span class="text">还原设置</span><b class="bgR"></b></a>
				</div>
				<!-- end tabToolBar -->
				  <h3 class="setTitle">IM设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 可使用全时蜜蜂 IM 互传文档</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 服务器保存聊天记录</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许连接外部 IM</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设置接听策略</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设定接听策略到海外电话</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"  /> 允许使用全时蜜蜂拨打电话</label>
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
                     
			</dd>
		</dl>
        </div>
</div>        
        <div id="part02" style="display:none;">
            <div class="bread"><span>成本中心</span>&nbsp;&gt;&nbsp;<span>为分类</span></div>
            <!-- end bread -->
            <div class="tabToolBar">
                <div class="tabToolBar-right"><div class="select selectGroup"><span>全部组织</span></div></div>
                <div class="tabToolBox" style="display: none">
                    <a class="btnGray btn btnMoveUserTo"  onclick="showDialog('弹窗_移动到.html')"><span class="text">移动到</span><b class="bgR"></b></a>
                    <a class="btnGray btn btnMoveUser"  onclick="showDialog('弹窗_提醒_移除成本中心员工.html')"><span class="text">移除员工</span><b class="bgR"></b></a>
                </div>
            </div>
            <!-- end tabToolBar -->
            <table class="table">
                <thead>
                    <tr>
                        <th width="30"><label class="checkbox"><input type="checkbox" /></label></th>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                        <th>状态</th>
                        <th>帐号操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >高晓波</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td>已使用</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >梁智慧</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td>已使用</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >董向然</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td>已使用</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >韩晓斌</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td>已开通</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >黄凯</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td>已开通</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >王志良</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td>已开通</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                </tbody>
            </table>
            <!-- end table -->
            <div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <div class="inputBox">
                    <b class="bgR"></b>
                    <label class="label"></label>
                    <input class="input" value="" />
                </div>
                <span class="text">页/3</span>
            </div>
        </div>
        
        <div class="groupLimit" style="display: none">
			<b class="arrow"></b>
            <div class="groupLimitContent">
			<div class="toolBar2">
				<a class="btnBlue yes"><span class="text" onclick="staff_change();">保存</span><b class="bgR"></b></a>
				<a class="btnGray btn"  onclick="$('.groupLimit').hide();"><span class="text">取消</span><b class="bgR"></b></a>
			</div>
			<!-- end tabToolBar -->
			 <h3 class="setTitle">IM设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 可使用全时蜜蜂 IM 互传文档</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 服务器保存聊天记录</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许连接外部 IM</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设置接听策略</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设定接听策略到海外电话</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"  /> 允许使用全时蜜蜂拨打电话</label>
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


<div class="pop-box" id="allGroup2" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url('public/js/jquery.ztree.all-3.5.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/tree.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/self_tree.js');?>"></script>
<script type="text/javascript">
    var  staff_information='<table class="infoTable">'+
                	'<tr>'+
						'<td class="tr">&nbsp;</td>'+
						'<td height="26">&nbsp;</td>'+
					'</tr>'+
					'<tr>'+
						'<td class="tr">姓名：</td>'+
						'<td>'+
							'<span class="infoText">李想</span>'+
                            '<div class="inputBox w360 hide">'+
								'<b class="bgR"></b>'+
								'<label class="label"></label>'+
								'<input class="input" value="李想">'+
							'</div>'+
						'</td>'+
					'</tr>'+
                    '<tr>'+
						'<td class="tr">帐号：</td>'+
						'<td>'+
							'<span class="infoText">liukaiwei@haier.com</span>'+
							'<div class="inputBox w360 hide">'+
								'<b class="bgR"></b>'+
								'<label class="label"></label>'+
								'<input class="input" value="liukaiwei@haier.com" />'+
							'</div>'+
						'</td>'+
					'</tr>'+
                    '<tr>'+
						'<td class="tr">性别：</td>'+
						'<td>'+
							'<span class="infoText">男</span>'+
							'<div class="radioBox hide">'+
								'<label class="radio checked"><input name="xb" type="radio" value="男" checked="checked" /> 男</label>'+
                                '<label class="radio"><input name="xb" type="radio" value="女" /> 女</label>'+
							'</div>'+
						'</td>'+
					'</tr>'+
                    '<tr>'+
						'<td class="tr">部门：</td>'+
						'<td>'+
							'<span class="infoText">海尔手机电子事业部-研发部</span>'+
                            '<div class="inputBox w360 hide">'+
								'<b class="bgR"></b>'+
								'<label class="label"></label>'+
								'<input class="input" value="海尔手机电子事业部-研发部" />'+
							'</div>'+
						'</td>'+
					'</tr>'+
                    '<tr>'+
						'<td class="tr">职位：</td>'+
						'<td>'+
							'<span class="infoText">研发经理</span>'+
							'<div class="inputBox w360 hide">'+
								'<b class="bgR"></b>'+
								'<label class="label"></label>'+
								'<input class="input" value="研发经理" />'+
							'</div>'+
						'</td>'+
					'</tr>'+
					'<tr>'+
						'<td class="tr">手机：</td>'+
						'<td>'+
							'<span class="infoText">+86</span>'+
							'<div class="combo selectBox w60 hide">'+
                                '<a class="icon" ></a>'+
                                '<span class="text selected">+86</span>'+
                                '<div class="optionBox" style="display: none;">'+
                                    '<dl class="optionList" style="height: 52px;">'+
                                        '<dd class="option selected" target="0" style="">+86</dd>'+
                                        '<dd class="option" target="1">+85</dd>'+
                                    '</dl>'+
                                    '<input type="hidden" class="val" value="0">'+
                                '</div>'+
                            '</div>'+
                            
                            -
                            '<span class="infoText"s>13899336667</span>'+
                            '<div class="inputBox w130 hide">'+
                                '<b class="bgR"></b>'+
                                '<label class="label"></label>'+
                                '<input class="input" value="13899336667">'+
                            '</div>'+
						'</td>'+
					'</tr>'+
				'</table>';
	function toggleAccount(t){
		if($(t).find("span.text").text()=="关闭帐号") {
		showDialog('ecologycompany/closeAccount');
			var _this = $(t);
			$("#dialog .dialogBottom .btn_confirm").live("click",function(){
				_this.find("span.text").text("开启帐号");
				hideDialog();
			})
		}
		else {
			$(t).find("span.text").text("关闭帐号");
		}
	}
	
	$(function(){
		//checkbox();
		$.fn.zTree.init($("#ztree"), setting, zNodes);
		$("#addZuzhi").bind("click", {isParent:true}, addZuzhi);
		$("#deleteZuzhi").bind("click", deleteZuzhi);
		
		var zTree = $.fn.zTree.getZTreeObj("ztree");
		zTree.selectNode(zTree.getNodeByParam("id", 21));
	
		 //checkbox();
	
		$('.conTabsHead > li').click(function(){
			var ind = $(this).index();
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.conTabsCont > dd').eq(ind).show().siblings().hide();
			$('.contRight > div').eq(ind).show().siblings().hide();
		});
		
		
		$('.infoNav li').click(function(){
			
			var ind = $(this).index();
			//var len = $(this).parent("ul").children().length;
			
			//if(ind<len-1) {
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.infoCont > dd').eq(ind).show().siblings().hide();
			//}
		});
		
		$('.btn_infoEdit').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).hide().next().removeClass('hide');
			});
		});
		$('.btn_infoCancel').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});
		});
		$('.btn_infoSave').click(function(){
		var count=0;
		var staff_change=new Array();
		var i=0;
		   $("tr").find("input").each(function()
		   {
		  
		     staff_change[i]=$(this).val(); 
		     if($(this).val()=="")
			 count++;
			 i++;
		   })
		   var staff_name=valitateStaffName(staff_change[0]);
		   var staff_account=valitateStaffAccount(staff_change[1]);
		   var staff_partment=(staff_change[4]=="")? false : true;
		   var staff_position=(staff_change[5]=="")? false : true;
		   var staff_telephone=(staff_change[7].length==11) ? true : false;
		   if(count!=0)
		    return false
			else if( staff_name && staff_account && staff_partment && staff_position && staff_telephone)
			{
		   $(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
			
		$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
				var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : $(this).next().hasClass('radioBox')?$(this).next().find(":checked").val():"";
			  $(this).text(text);
			 }) 
			 $('.groupLimit').hide();
			 }
			 else
			 {
			 return false;
			 }
	      })
		$(".checkbox").click(function(){
		  $(".toolBar2").show();
		})
		
	
		
		$(".selectGroup").click(function(event){
			
			$("#allGroup2").toggle();
			event.stopPropagation();
		})
		
		//成本中心 表格全选
		//checkall('#part02 .table thead .checkbox', '#part02 .table tbody .checkbox', '#part02 .table .checkbox',toolBarSet2);
	
		//组织结构树
		function toolBarSet2(){
			var checked = $('#part02 .table .checkbox').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('#part02 .tabToolBox').show();
			}else{
				$('#part02 .tabToolBox').hide();
			}
		}
		
		$(document).click(function(){
			$("#allGroup2").hide();
	
			//$(".datepickers").empty();	
		})
	});
</script>
</body>
</html>