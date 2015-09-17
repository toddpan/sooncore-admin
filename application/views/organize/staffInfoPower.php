<div id="part01">
    <div class="infoTitle">
			<a   class="pageGoBack"></a><!--loadCont('组织与员工-ldap.html');-->
			<span class="personName">李想</span>
            <div class="fr">
     <a class="btn"  onClick="toggleAccount(this)"><span class="text" style="text-decoration: none">关闭帐号</span><b class="bgR"></b></a>&nbsp;
     <a class="btn"  onClick="showDialog('password/showTempPWD')"><span class="text"style="text-decoration: none" >重置密码</span><b class="bgR"></b></a>
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
					<a class="btnBlue btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
				</div>
				<table class="infoTable">
					<tr>
						<td class="tr">姓名：</td>
						<td>
							<span class="infoText">李想</span>
                            <div class="inputBox w360 hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" value="李想" />
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
						<td class="tr">账户：</td>
						<td>
							<span class="infoText">创想空间北京有限公司</span>
                            <div class="combo selectBox hide">
                                <a class="icon" ></a>
                                <span class="text selected">创想空间北京有限公司</span>
                                <div class="optionBox" style="width: 208px; display: none">
                                    <dl class="optionList" style="height: 52px;">
                                        <dd class="option selected" target="0" style="">创想空间北京有限公司</dd>
                                        <dd class="option" target="1">创想空间上海有限公司</dd>
                                    </dl>
                                    <input type="hidden" class="val" value="0">
                                </div>
                            </div>
							
						</td>
					</tr>
                    <tr>
						<td class="tr">性别：</td>
						<td>
							<span class="infoText">男</span>
							<div class="radioBox hide">
								<label class="radio radio_on"><input name="xb" type="radio" value="男" checked="checked" /> 男</label>
                                <label class="radio"><input name="xb" type="radio" value="女" /> 女</label>
							</div>
						</td>
					</tr>
                    <tr>
						<td class="tr">部门：</td>
						<td>
							<span class="infoText">海尔手机电子事业部-研发部</span>
                            <div class="select-box w210 hide">
                                <input type="text" class="text" value="海尔手机电子事业部-研发部" onClick="showMenu(this);" id="departmentSel2" placeholder="请选择管理的部门" />
                                <a class="icon"  onClick="showMenu(this); return false;"></a>
                                <div class="selectOptionBox" style="display: none; width: 210px;">
                                    <ul class="ztree" id="ztree4"></ul>
                                </div>
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
                    <tr>
                    	<td class="tr">办公地址：</td>
						<td>
                                	<span class="infoText ">北京朝阳区酒仙桥北路甲10号院</span>
                                    <div class="combo selectBox hide" style="width: 208px;">
                                        <a class="icon" ></a>
                                        <span class="text selected">北京朝阳区酒仙桥北路甲10号院</span>
                                        <div class="optionBox">
                                            <dl class="optionList" style="height: 52px;">
                                                <dd class="option selected" target="0" style="">北京朝阳区酒仙桥北路甲10号院</dd>
                                                <dd class="option" target="1">北京朝阳区酒仙桥北路甲11号院</dd>
                                            </dl>
                                            <input type="hidden" class="val" value="0">
                                        </div>
                                    </div>
                                </td>
                    </tr>
				</table>
			</dd>
			<dd style="display:none;">
				<div class="toolBar2">
					<a class="btnBlue" ><span class="text">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn" ><span class="text">还原设置</span><b class="bgR"></b></a>
				</div>
				<!-- end tabToolBar -->
				  <h3 class="setTitle">IM设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 可使用全时sooncore平台 IM 互传文档</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 自动将联系过的联系人添加到常用联系人列表</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 自动将联系过的讨论组添加到讨论组列表</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许用户设置接听策略</label>
            <label class="checkbox  checkbox2"><input type="checkbox" checked=""> 用户可设定接听策略到海外直线电话</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许使用sooncore平台拨打电话</label>
            <label class="checkbox checkbox2 checked"><input type="checkbox" checked="checked"> 允许拨打海外电话</label>
                    <h3 class="setTitle"><label class="checkbox checked"><input type="checkbox" checked="checked">电话会议设置(允许召开电话会议)</label></h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许会中外呼</label>
            <label class="checkbox checked"> <input type="checkbox" checked="checked"> 允许参会人主动接入</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 电话会议自动报名</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 与会者使用数据库保存的电话号码接入，自动切换为用户名称</label>
           	<label class="checkbox checked"><input type="checkbox" checked="checked"> 参会人加入会议语音状态</label>   
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 参会人退出会议语音提示</label>
            <label class="checkbox checked"> <input type="checkbox" checked="checked"> 主持人加入会议语音提示</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 主持人退出会议语音提示</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 外呼屏蔽 *1 功能</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 参会人加入会议，告知参会者人数</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 第一个入会是否需要听到您是第一个到会者讯息</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 主持人未入会，参会人可使用 PSTN</label>
            
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许使用硬件视频</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 会议允许最大方数</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许追呼次数</label>
            <label class="checkbox checked"> <input type="checkbox" checked="checked"> 允许设置本地接入号</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许设置95057 接入号</label>
			<label class="checkbox checked"><input type="checkbox" checked="checked"> 允许设置400/800 国内接入号</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许设置海外接入号</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 主持人未入会，只要会议有人入会，会议就开始</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 主持人退会，会议是否停止</label>

                    <h3 class="setTitle"><label class="checkbox checked"><input type="checkbox" checked="checked">网络会议配置(允许召开网络会议)</label></h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许召开网络会议</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 用户默认语音接入方式</label>
            <label class="checkbox checked"> <input type="checkbox" checked="checked"> 允许用户邀请站点外用户加入会议</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 会议结束显示会后营销页面</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 安排会议时，根据用户使用语言配置会议模板语言</label>
                     
			</dd>
		</dl>
</div>
</div>
<script type="text/javascript">
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
	
	$('.infoNav li').click(function(){
			
			var ind = $(this).index();
			//var len = $(this).parent("ul").children().length;
			
			//if(ind<len-1) {
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.infoCont > dd').eq(ind).show().siblings().hide();
			//}
		});
		
	$('.pageGoBack').click(function()
	{
	  // $('#part01').toggle();
	   var target=1;
	   setCookie('target',target,30);
	  })
	});
</script>