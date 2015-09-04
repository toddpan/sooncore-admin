<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link type="text/css" href="zTreeStyle/zTreeStyle.css" rel="stylesheet" />
<link type="text/css" href="css/jquery.jscrollpane.css" rel="stylesheet" />
</head>

<body>
<!-- 管理员管理_管理员信息 -->
<div class="contHead" style="margin-bottom:20px;">
	<span class="title01">管理员管理</span>
	<div class="contHead-right"><div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"></a></div>
	
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
            <li><a  onclick="loadCont('staff/batchModifyStaff');">批量修改</a></li>
           <!--   <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">LDAP设置</a></li> -->
        </ul>
        
    </div>
</div>

<div id="">
        <div class="bread"><span>管理员管理</span>&nbsp;&gt;&nbsp;<span>系统管理员</span>&nbsp;&gt;&nbsp;<span>李想</span></div>
        <!-- end bread -->
        <div class="infoTitle">
            <a href="javascript:loadCont('管理员管理.html');" class="pageGoBack"></a>
            <span class="personName">李想</span>
            
            <div class="fr">
                <a class="btn"  onclick="toggleAccount(this)"><span class="text">关闭帐号</span><b class="bgR"></b></a>&nbsp;
                <a class="btn"  onclick="showDialog('弹窗_提醒_重置密码.html')"><span class="text">重置密码</span><b class="bgR"></b></a>
            </div>
        </div>
        <div class="cont-wrapper">
        <ul class="infoNav">
            <li>员工信息</li>
            <li>员工权限</li>
            <li class="selected">管理员权限</li>
        </ul>
        <dl class="infoCont">
            <dd style="display:none;">
                <div class="toolBar2">
                    <a class="btnGray btn btn_infoEdit" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                    <a class="btnBlue yes btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a>
                    <a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
                </div>
                <table class="infoTable">
                    <tr>
                        <td class="tr" style="width:110px;">姓名：</td>
                        <td>
                            <span class="infoText">李想</span>
                            <div class="inputBox w110 hide">
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
                            <div class="inputBox w110 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="男" />
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
                        <td class="tr">成本中心：</td>
                        <td>
                            <span class="infoText">成本中心1</span>
                            <div class="inputBox w360 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="成本中心1" />
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
                            <span class="infoText ">+86</span>
                            <div class="combo selectBox w60 hide">
                                
                                <a class="icon" ></a>
                                <span class="text selected">+86</span>
                                <div class="optionBox">
                                    <dl class="optionList">
                                        <dd class="option selected" target="0">+86</dd>
                                        <dd class="option" target="1">+85</dd>
                                    </dl>
                                    <input type="hidden" class="val" value="0" />
                                </div>
                            </div>
                            -
                            <span class="infoText">13899336667</span>
                            <div class="inputBox w130 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="13899336667" />
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
                    <a class="btnBlue yes" ><span class="text">保存</span><b class="bgR"></b></a>
                    <a class="btnGray btn" ><span class="text">还原设置</span><b class="bgR"></b></a>
                </div>
                <!-- end tabToolBar -->
                 <h3 class="setTitle">IM设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 可使用全时蜜蜂 IM 互传文档</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 自动将联系过的联系人添加到常用联系人列表</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 自动将联系过的讨论组添加到讨论组列表</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许用户设置接听策略</label>
            <label class="checkbox  checkbox2"><input type="checkbox" checked=""> 用户可设定接听策略到海外直线电话</label>
            <label class="checkbox checked"><input type="checkbox" checked="checked"> 允许使用蜜蜂拨打电话</label>
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
            <dd>
                <div class="toolBar2">
                    <a class="btnGray btn btn_infoEdit2" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                    <a class="btnBlue yes btn_infoSave2 hide" ><span class="text">保存</span><b class="bgR"></b></a>
                    <a class="btnGray btn btn_infoCancel2 hide" ><span class="text">取消</span><b class="bgR"></b></a>
                </div>
                <div id="infoBox03" style="display: none">
                    <table class="infoTable">
                        <tr>
                            <td class="tr" style="width:92px;">管理员角色：</td>
                            <td>
                                <span class="infoText">员工管理员</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">组织管理范围：</td>
                            <td>
                                <span class="infoText">第一个管理维度：部门</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                                <span class="infoText">部门一</span> <span class="infoText">部门二</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                                <span class="infoText">第二个管理维度：地区</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                                <span class="infoText">北京</span>
                            </td>
                        </tr>
                    </table>
                    <div class="setTitle"></div>
                    <table class="infoTable">
                        <tr>
                            <td class="tr" style="width:92px;">管理员角色：</td>
                            <td>
                                <span class="infoText">帐号管理员</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">组织管理范围：</td>
                            <td>
                                <span class="infoText">第一个管理维度：地区</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                                <span class="infoText">上海</span>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <div id="editBox03" style="display:block;">
                    <table class="infoTable">
                        <tr>
                            <td width="220">管理员角色：</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <div id="juese" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">员工管理员</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择管理员角色</dd>
                                            <dd class="option selected" target="2">员工管理员</dd>
                                            <dd class="option" target="3">帐号管理员</dd>
                                            <dd class="option" target="4">生态管理员</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="2" />
                                    </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="hideBar01">
                            <td>组织管理范围：</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="hideBar01">
                            <td>
                                <div id="weidu01" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">部门</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择第一个管理维度</dd>
                                            <dd class="option selected" target="2">部门</dd>
                                            <dd class="option" target="3">地区</dd>
                                            <dd class="option" target="4">成本中心</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="2" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="select-box w210">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel" value="研发部,市场部" placeholder="请选择管理的部门" />
                                    <a class="icon"  id="menuBtn" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption1" style="display: none; width: 210px;">
                                        <ul class="ztree" id="ztree3"></ul>
                                    </div>
                                </div>
                                <div class="select-box w210 hide">
                                    <input type="text" class="text" onclick="showMenu(this);" id="locationSel" placeholder="请选择管理的地区" />
                                    <a class="icon"  id="menuBtn2" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption2" style="display: none; width: 210px; height:82px;">
                                        <ul class="ztree">
                                            <li target="2"><a ><label><input name="" type="checkbox" value="" /> 北京</label></a></li>
                                            <li target="3"><a ><label><input name="" type="checkbox" value="" /> 上海</label></a></li>
                                            <li target="4"><a ><label><input name="" type="checkbox" value="" /> 广州</label></a></li>
                                        </ul>
                                    </div>  
                                </div>
                                <div class="select-box w210 hide">
                                    <input type="text" class="text" onclick="showMenu(this);" id="centerSel" placeholder="请选择管理的成本中心" />
                                    <a class="icon"  id="menuBtn3" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption3" style="display: none; width: 210px; height:82px;">
                                        <ul class="ztree">
                                            <li target="2"><a ><label><input name="" type="checkbox" value="" /> 成本中心一</label></a></li>
                                            <li target="3"><a ><label><input name="" type="checkbox" value="" /> 成本中心二</label></a></li>
                                            <li target="4"><a ><label><input name="" type="checkbox" value="" /> 成本中心三</label></a></li>
                                        </ul>
                                    </div>  
                                </div>
                            </td>
                        </tr>
                        <tr class="hideBar02">
                            <td>
                                <div id="weidu02" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">地区</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择第二个管理维度</dd>
                                            <dd class="option" target="2">部门</dd>
                                            <dd class="option selected" target="3">地区</dd>
                                            <dd class="option" target="4">成本中心</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                            </td>
                            <td>
                               <div class="select-box w210 hide">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel2" placeholder="请选择管理的部门" />
                                    <a class="icon"  onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" style="display: none; width: 210px;">
                                        <ul class="ztree" id="ztree4"></ul>
                                    </div>
                                </div>
                                <div class="combo selectBox w210 focusSelectBox">
                                    <a class="icon" ></a>
                                    <span class="text selected">北京</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option " target="1">请选择管理的地区</dd>
                                            <dd class="option selected" target="2">北京</dd>
                                            <dd class="option" target="3">上海</dd>
                                            <dd class="option" target="4">广州</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                                <div class="combo selectBox w210 hide">
                                    <a class="icon" ></a>
                                    <span class="text">请选择管理的成本中心</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option selected" target="1">请选择管理的成本中心</dd>
                                            <dd class="option" target="2">成本中心一</dd>
                                            <dd class="option" target="3">成本中心二</dd>
                                            <dd class="option" target="4">成本中心三</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div class="setTitle"></div>
                    <table class="infoTable">
                        <tr>
                            <td width="220">管理员角色：</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <div id="juese2" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">帐号管理员</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择管理员角色</dd>
                                            <dd class="option" target="2">员工管理员</dd>
                                            <dd class="option selected" target="3">帐号管理员</dd>
                                            <dd class="option" target="4">生态管理员</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="2" />
                                    </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="hideBar01">
                            <td>组织管理范围：</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr class="hideBar01">
                            <td>
                                <div id="weidu01_01" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">地区</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择第一个管理维度</dd>
                                            <dd class="option" target="2">部门</dd>
                                            <dd class="option selected" target="3">地区</dd>
                                            <dd class="option" target="4">成本中心</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="2" />
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="select-box w210 hide">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel_01" value="" placeholder="请选择管理的部门" />
                                    <a class="icon"  id="menuBtn1_01" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption1_01" style="display: none; width: 210px;">
                                        <ul class="ztree" id="ztree3_01"></ul>
                                    </div>
                                </div>
                                <div class="select-box w210">
                                    <input type="text" class="text" onclick="showMenu(this);" id="locationSel_01" placeholder="请选择管理的地区" value="上海" />
                                    <a class="icon"  id="menuBtn1_02" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption1_02" style="display: none; width: 210px; height:82px;">
                                        <ul class="ztree">
                                            <li target="2"><a ><label><input name="" type="checkbox" value="" /> 北京</label></a></li>
                                            <li target="3"><a ><label><input name="" type="checkbox" checked="checked" value="" /> 上海</label></a></li>
                                            <li target="4"><a ><label><input name="" type="checkbox" value="" /> 广州</label></a></li>
                                        </ul>
                                    </div>  
                                </div>
                                <div class="select-box w210 hide">
                                    <input type="text" class="text" onclick="showMenu(this);" id="centerSel_01" placeholder="请选择管理的成本中心" />
                                    <a class="icon"  id="menuBtn1_03" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption1_03" style="display: none; width: 210px; height:82px;">
                                        <ul class="ztree">
                                            <li target="2"><a ><label><input name="" type="checkbox" value="" /> 成本中心一</label></a></li>
                                            <li target="3"><a ><label><input name="" type="checkbox" value="" /> 成本中心二</label></a></li>
                                            <li target="4"><a ><label><input name="" type="checkbox" value="" /> 成本中心三</label></a></li>
                                        </ul>
                                    </div>  
                                </div>
                            </td>
                        </tr>
                        <tr class="hideBar02 hide">
                            <td>
                                <div id="weidu02_01" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">部门</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择第二个管理维度</dd>
                                            <dd class="option selected" target="2">部门</dd>
                                            <dd class="option" target="3">地区</dd>
                                            <dd class="option" target="4">成本中心</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                            </td>
                            <td>
                               <div class="select-box w210 ">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel2_01" placeholder="请选择管理的部门" />
                                    <a class="icon"  onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" style="display: none; width: 210px;">
                                        <ul class="ztree" id="ztree4_01"></ul>
                                    </div>
                                </div>
                                <div class="combo selectBox w210 focusSelectBox hide">
                                    <a class="icon" ></a>
                                    <span class="text">北京</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option " target="1">请选择管理的地区</dd>
                                            <dd class="option selected" target="2">北京</dd>
                                            <dd class="option" target="3">上海</dd>
                                            <dd class="option" target="4">广州</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                                <div class="combo selectBox w210 hide">
                                    <a class="icon" ></a>
                                    <span class="text">请选择管理的成本中心</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option selected" target="1">请选择管理的成本中心</dd>
                                            <dd class="option" target="2">成本中心一</dd>
                                            <dd class="option" target="3">成本中心二</dd>
                                            <dd class="option" target="4">成本中心三</dd>
                                        </dl>
                                        <input type="hidden" class="val" value="1" />
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </dd>
        </dl>
        </div>
</div>        
<script type="text/javascript" src="js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="js/tree.js"></script>
<script type="text/javascript">
	function toggleAccount(t){
		if($(t).find("span.text").text()=="关闭帐号") {
		showDialog('弹窗_关闭蜜蜂帐号.html');
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
		$.fn.zTree.init($("#ztree3"), optionSetting, zNodes);
		$.fn.zTree.init($("#ztree4"), radioSetting, zNodes);
		$.fn.zTree.init($("#ztree3_01"), optionSetting, zNodes);
		$.fn.zTree.init($("#ztree4_01"), radioSetting, zNodes);
		
		$('.selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		checkbox();
	
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
			$(this).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).hide().next().removeClass('hide');
			});
		});
		$('.btn_infoCancel').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
			$(this).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});
		});
		$('.btn_infoSave').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
			$(this).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
				var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : '';
				$(this).text(text);
			});
		});
		
		$('.btn_infoEdit2').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave2, .btn_infoCancel2').removeClass('hide');
			$('#infoBox03').hide();
			$('#editBox03').show();
		});
		$('.btn_infoCancel2, .btn_infoSave2').click(function(){
			$('.btn_infoCancel2, .btn_infoSave2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
			$('#infoBox03').show();
			$('#editBox03').hide();
		});
		
		$('#juese').combo({
			redata:true,
			changedFn:function(){
					var val = $('#juese').find('input').val();
					if(val == 2 || val == 3){
						$('#editBox03 .infoTable:eq(0) .hideBar01').removeClass('hide');
					}else{
						$('#editBox03 .infoTable:eq(0) .hideBar01, #editBox03 .infoTable:eq(0) .hideBar02').addClass('hide');
					}
				}
		});
		
		
		
		$('#weidu01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu01');
					var val = _this.find('input').val();
					//console.log(val);
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					
					if(val > 1) { 
						_option.eq(val-2).removeClass('hide');
						$("#weidu02").parent().siblings("td").children().addClass("hide");
						$("#weidu02 .text").attr({"title":"请选择第二个管理维度"}).text("请选择第二个管理维度");
						$("#weidu02 input").val("1");
						$("#weidu02 dd.option").eq(val-1).hide().siblings().show().removeClass("selected");
					}
					//$('.infoTable .hideBar02').addClass('hide');
					$('#editBox03 .infoTable:eq(0) .hideBar02').removeClass('hide');
				}
		});
		
		$('#weidu02').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu02');
					var val = _this.find('input').val();
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
				}
		});
		
		$("#selectOption2").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#locationSel").attr("value",val);
		})
		
		$("#selectOption3").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#centerSel").attr("value",val);
		})
		
		$('#juese2').combo({
			redata:true,
			changedFn:function(){
					var val = $('#juese2').find('input').val();
					if(val == 2 || val == 3){
						$('#editBox03 .infoTable:eq(1) .hideBar01').removeClass('hide');
					}else{
						$('#editBox03 .infoTable:eq(1) .hideBar01, #editBox03 .infoTable:eq(1) .hideBar02').addClass('hide');
					}
				}
		});
		
		
		$('#weidu01_01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu01_01');
					var val = _this.find('input').val();
					//console.log(val);
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					
					if(val > 1) { 
						
						_option.eq(val-2).removeClass('hide');
						$("#weidu02_01").parent().siblings("td").children().addClass("hide");
						$("#weidu02_01 .text").attr({"title":"请选择第二个管理维度"}).text("请选择第二个管理维度");
						$("#weidu02_01 input").val("1");
						$("#weidu02_01 dd.option").eq(val-1).hide().siblings().show().removeClass("selected");
					}
					//$('.infoTable .hideBar02').addClass('hide');
					$('#editBox03 .infoTable:eq(1) .hideBar02').removeClass('hide');
				}
		});
		
		
		$('#weidu02_01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu02_01');
					var val = _this.find('input').val();
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
				}
		});
		
		$("#selectOption1_02").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#locationSel_01").attr("value",val);
		})
		
		$("#selectOption1_03").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#centerSel_01").attr("value",val);
		})

	});
</script>
</body>
</html>