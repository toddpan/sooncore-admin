<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
<!--生态企业-生态管理员.html-->
<div class="contHead">
	<span class="title01">企业生态</span>
	<div class="contHead-right"><!--<div class="fr rightLine"><a class="btnSet"  onclick="toggleMenu('menu1',event)"></a></div>-->
	<div class="fr rightLine"><a class="btnLabel"  onclick="loadPage('指定标签.html','company')"></a></div>
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
    
   <!-- <ul class="menu" id="menu1">
            <li><a  onclick="loadCont('组织与员工_批量导入.html')">员工标签管理</a></li>
            <li><a onclick="loadCont('staff/batchModifyStaff');">批量修改</a></li>
            <li><a  onclick="loadCont('组织与帐号_LDAP同步1.html')">LDAP设置</a></li>
        </ul>-->
        
    </div>
</div>
<!-- end contHead -->
<div class="conTabs">
	<ul class="conTabsHead">
		<li onclick="loadPage('ecologycompany/ecologyPage','company')">生态企业<span class="conline"></span></li>
		<li class="selected">管理员</li>
	</ul>
	<!-- end conTabsHead -->
	<dl class="conTabsCont">
		<dd style="display:none;">
			<div class="toolBar">
				<a class="addGroup"  onclick="loadPage('创建生态企业1.html','company')"  title="创建生态企业"></a>
				<a class="delGroup"  title="删除生态企业" onclick="showDialog('弹窗_删除生态企业.html')"></a>
			</div>
			<div id="tree"></div>
			<!-- end tree -->
		</dd>
		<dd style="display: block">
			 <div class="toolBar" style="display: block">
                    <a class="addGroup"  onclick="showDialog('弹窗_添加生态管理员.html')" title="添加生态管理员"></a>
                    <a class="delGroup delStAdmin disabled" onclick="deleteStAdmin(this);"  title="删除生态管理员"></a>
                </div>
                <ul class="tree" style="display:block;">
                    <li class="selected">
                        <a class="treeNode selected" >
                            <span class="treeNodeName">陈总</span>
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
	<div id="part01" style="display: none">
        <div class="part01_1">
            <div class="bread"><span>创想空间商务通信服务有限公司</span></div>
            <!-- end bread -->
            
            <div class="cont-wrapper">
            <ul class="infoNav">
                <li class="selected">企业信息</li>
            </ul>
            <dl class="infoCont">
                <dd>
                    
                    <table class="infoTable">
                        <tr>
                            <td class="tr">企业名称：</td>
                            <td>
                                <span class="infoText dotEdit">创想空间商务通信服务有限公司</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">英文简称：</td>
                            <td>
                                <span class="infoText dotEdit">wanlil</span>
                            
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">中文简称：</td>
                            <td>
                                <span class="infoText dotEdit">创想空间</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">联系电话：</td>
                            <td>
                                <span class="infoText dotEdit">+86 10 5993 3636</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">国家/地区：</td>
                            <td>
                                <span class="infoText dotEdit">北京 朝阳区 酒仙桥地区</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">公司介绍：</td>
                            <td>
                                <span class="infoText dotEdit">我们致力于通过互联网和通信协作技术来提高商业效率，帮助企业迅速获得商业动力，而不需投资昂贵的固定资产，全时已经为超过300家世界500强企业以及超过3500家中国企业提供服务，显著提高了企业商业运作的效率。全时将坚持持续帮助商务人士更高效地工作，以实现客户的商业目标。全时隶属北京创想空间商务通信服务有限公司。</span>
                            </td>
                        </tr>
                        
                    </table>
                </dd>
                
            </dl>
            </div>
        </div>
        <div class="part01_2" style="display: none">
            
            <div class="bread"><span>创想空间商务通信服务有限公司</span>&nbsp;&gt;&nbsp;<span>北京分公司</span></div>
            <!-- end bread -->
            
            <div class="cont-wrapper">
            <ul class="infoNav">
                <li class="selected">企业信息</li>
                <li>企业权限</li>
                <li>企业员工</li>
                <li>生态员工</li>
            </ul>
            <dl class="infoCont">
                <dd>
                    <div class="toolBar2">
                        <a class="btnGray btn btn_infoEdit" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                        <a class="btnBlue yes btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a>
                        <a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
                    </div>
                    <table class="infoTable">
                        <tr>
                            <td class="tr">生态管理员：</td>
                            <td>
                                <span class="infoText dotEdit">JACKSON</span> &nbsp; <a   onclick="showDialog('弹窗_添加生态管理员.html')">更改</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">企业名称：</td>
                            <td>
                                <span class="infoText">北京分公司</span>
                                <div class="inputBox w360 hide">
                                    <b class="bgR"></b>
                                    <label class="label"></label>
                                    <input class="input" value="北京分公司" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">简称：</td>
                            <td>
                                <span class="infoText">北京分公司</span>
                                <div class="inputBox w360 hide">
                                    <b class="bgR"></b>
                                    <label class="label"></label>
                                    <input class="input" value="北京分公司" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">联系电话：</td>
                            <td>
                        <span class="infoText">+86</span>
                        <div class="combo selectBox focusSelectBox hide" style="width: 100px; z-index: 3;">
                                
                                <a class="icon" ></a>
                                <span class="text selected">+86</span>
                                <div class="optionBox" style="display: none; width: 102px; left: -1px;">
                                    <dl class="optionList" style="height: 26px;">
                                         
                                        <dd class="option selected" target="1" style="">+86</dd>
                                        
                                    </dl>
                                    <input type="hidden" class="val" value="1">
                                </div>
                            </div>
                         <span class="infoText">10</span> 
                         <div class="inputBox hide">
                            <b class="bgR"></b>
                            <label class="label" for="quhao" style="display: none">区号</label>
                            <input class="input" id="quhao" value="10" style="width: 72px;">
                        </div>
                         <span class="infoText">5993 3636</span>
                         <div class="inputBox hide">
                                <b class="bgR"></b>
                                <label class="label" for="phoneNum" style="display: none">电话号码</label>
                                <input class="input" id="phoneNum" value="5993 3636" style="width: 262px;">
                            </div>
						</td>
                        </tr>
                        <tr>
                            <td class="tr">国家/地区：</td>
                            <td>
                                <span class="infoText">北京 通州区 张湾镇</span>
                                <div class="inputBox w360 hide">
                                    <b class="bgR"></b>
                                    <label class="label"></label>
                                    <input class="input" value="北京 通州区 张湾镇" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">公司介绍：</td>
                            <td>
                                <span class="infoText">我们致力于通过互联网和通信协作技术来提高商业效率，帮助企业迅速获得商业动力，而不需投资昂贵的固定资产，全时已经为超过300家世界500强企业以及超过3500家中国企业提供服务，显著提高了企业商业运作的效率。全时将坚持持续帮助商务人士更高效地工作，以实现客户的商业目标。全时隶属北京创想空间商务通信服务有限公司。</span>
                               <div class="hide"> <textarea class="textarea" style="width: 600px; height: 80px;">我们致力于通过互联网和通信协作技术来提高商业效率，帮助企业迅速获得商业动力，而不需投资昂贵的固定资产，全时已经为超过300家世界500强企业以及超过3500家中国企业提供服务，显著提高了企业商业运作的效率。全时将坚持持续帮助商务人士更高效地工作，以实现客户的商业目标。全时隶属北京创想空间商务通信服务有限公司。</textarea></div>
                            </td>
                        </tr>
                        
                    </table>
                </dd>
                <dd style="display:none;">
                    <div class="toolBar2">
                        <a class="btnGray btn btn_infoEdit2" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                        <a class="btnBlue yes hide btn_save2" ><span class="text">保存</span><b class="bgR"></b></a>
                        <a class="btnGray btn hide btn_cancel2" ><span class="text">取消</span><b class="bgR"></b></a>
                    </div>
                    <!-- end tabToolBar -->
                    <div class="setStqy">
                    <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许召开网络会议</label><br />
                    <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许召开电话会议</label><br />
                    <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许会中外呼</label><br />
                    <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许设置呼叫转移</label><br />
                    <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许拨打电话</label>
                    </div>
                </dd>
                <dd style="display:none;">
                    <table class="table">
                    <thead>
                        <tr>
                           
                            <th style="text-align: left; text-indent: 24px">姓名</th>
                            <th>帐号</th>
                            <th>手机</th>
                            <th>上次登录</th>
                            <th>帐号操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">陈总</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">刘恺威</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                            <td><a  class="btnOff"></a></td>
                        </tr>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">Windy</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">王志良</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                        <tr>
                          
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">黄凯</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">董向然</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                        <tr>
                           
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">吴泽坤</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                            <td><a  class="btnOn"></a></td>
                        </tr>
                    </tbody>
                </table>
                 </dd>
                 <dd style="display: none">
                    <div class="tabToolBar">
                        <a class="btnBlue  yes btnAddUser" href="javascript:showDialog('弹窗_添加生态合作员工.html');"><span class="text">添加员工</span><b class="bgR"></b></a>
                        <div class="tabToolBox" style="display: none">
                           
                            <a class="btnGray btn btnDeleUser"  onclick="showDialog('弹窗_提醒_移除生态员工.html')"><span class="text">删除员工</span><b class="bgR"></b></a>
                            
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
                                <th style="text-align: left; text-indent: 24px">姓名</th>
                                <th>帐号</th>
                                <th>手机</th>
                                <th>上次登录</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">陈总</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>2013/2/25&nbsp;13:55</td>
                               
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >刘恺威</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>2013/2/25&nbsp;13:55</td>
                              
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >Windy</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>2013/2/25&nbsp;13:55</td>
                               
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >王志良</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>未登录</td>
                                
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >黄凯</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>未登录</td>
                              
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >董向然</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>未登录</td>
                               
                            </tr>
                            <tr>
                                <td><label class="checkbox"><input type="checkbox" /></label></td>
                                <td class="tl"><a class="userName ellipsis" >吴泽坤</a></td>
                                <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                                <td>15877779999</td>
                                <td>未登录</td>
                            </tr>
                        </tbody>
                    </table>
                 </dd>
            </dl>
            </div>
        </div>
    </div>
    <div id="part02" style="display:block;">
         <div class="part02_1" style="display: none">
            <div class="tabToolBar">
                <a class="btnBlue yes btnAddUser"  onclick="loadPage('创建生态企业1.html','companyAdmin')"><span class="text">创建生态企业</span><b class="bgR"></b></a>
                
                <div class="tabToolBox fl" style="display:none;">
                  <a class="btnGray btn btnMoveManage"  onclick="showDialog('弹窗_删除生态企业.html')"><span class="text">删除生态企业</span><b class="bgR"></b></a>
                </div> 
            </div>
            <!-- end tabToolBar -->
            <table class="table">
                <thead>
                    <tr>
                        <th width="30"><label class="checkbox"><input type="checkbox" /></label></th>
                        <th>生态企业名称</th>
                        <th>所在区域</th>
                        <th>企业负责人</th>
                        <th>上级生态企业</th>
                        <th>生态管理员</th>
                       
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td><a  onclick="loadCont('init-stqy2.html')">北京分公司</a></td>
                        <td>北京</td>
                        <td><a  onclick="loadCont('生态企业-生态管理员.html')">陈总</a></td>
                        <td><a  onclick="$('.conTabsHead li:first').trigger('click')"  class="ellipsis">北京创想空间商务通信服务有限公司</a></td>
                        <td><a  onclick="loadCont('生态企业-生态管理员.html')">陈总</a></td>
                    
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td><a  onclick="loadCont('init-stqy2.html')">北京分公司</a></td>
                        <td>北京</td>
                        <td><a  onclick="loadCont('生态企业-生态管理员.html')">陈总</a></td>
                        <td><a  onclick="$('.conTabsHead li:first').trigger('click')" class="ellipsis">北京创想空间商务通信服务有限公司</a></td>
                        <td><a  onclick="loadCont('生态企业-生态管理员.html')">陈总</a></td>
                    
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
        <div class="part02_2" style="display: block">
        	<div class="infoTitle">
                <a  onclick="$('.part02_1').show().siblings().hide()" class="pageGoBack"></a>
                <span class="personName">陈总</span>
                
                <div class="fr">
                    <a class="btn"  onclick="toggleAccount(this)"><span class="text">关闭帐号</span><b class="bgR"></b></a>&nbsp;
                    <a class="btn"  onclick="showDialog('manager/resetPasssword')"><span class="text">重置密码</span><b class="bgR"></b></a>
                </div>
            </div>
            <div class="cont-wrapper">
                <ul class="infoNav">
                    <li class="selected">员工信息</li>
                    <li>员工权限</li>
                    <li>管理员权限</li>
                </ul>
                <dl class="infoCont">
                    <dd style="display:block;">
                        <div class="toolBar2">
                            <a class="btnGray btn btn_infoEdit" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                            <a class="btnBlue yes btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a>
                            <a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
                        </div>
                        <table class="infoTable">
                            <tr>
                                <td class="tr" style="width:110px;">姓名：</td>
                                <td>
                                    <span class="infoText">陈总</span>
                                    <div class="inputBox w110 hide">
                                        <b class="bgR"></b>
                                        <label class="label"></label>
                                        <input class="input" value="陈总" />
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
                                <td class="tr">办公室所在地区：</td>
                                <td>
                                    <span class="infoText">中国</span>
                                    <div class="combo selectBox w110 hide">
                                        <a class="icon" ></a>
                                        <span class="text selected">中国</span>
                                        <div class="optionBox">
                                            <dl class="optionList">
                                                <dd class="option selected" target="0">中国</dd>
                                                <dd class="option" target="1">美国</dd>
                                            </dl>
                                            <input type="hidden" class="val" value="0" />
                                        </div>
                                    </div>
                                    <span class="infoText">北京</span>
                                    <div class="combo selectBox w110 hide">
                                        <a class="icon" ></a>
                                        <span class="text selected">北京</span>
                                        <div class="optionBox">
                                            <dl class="optionList">
                                                <dd class="option selected" target="0">北京</dd>
                                                <dd class="option" target="1">上海</dd>
                                            </dl>
                                            <input type="hidden" class="val" value="0" />
                                        </div>
                                    </div>
                                    <span class="infoText">通州区</span>
                                    <div class="combo selectBox w110 hide">
                                        <a class="icon" ></a>
                                        <span class="text selected">通州区</span>
                                        <div class="optionBox">
                                            <dl class="optionList">
                                                <dd class="option" target="0">朝阳区</dd>
                                                <dd class="option selected" target="1">通州区</dd>
                                            </dl>
                                            <input type="hidden" class="val" value="1" />
                                        </div>
                                    </div>
                                    <span class="infoText">张湾镇</span>
                                    <div class="combo selectBox w110 hide">
                                        <a class="icon" ></a>
                                        <span class="text selected">张湾镇</span>
                                        <div class="optionBox">
                                            <dl class="optionList">
                                                <dd class="option selected" target="0">张湾镇</dd>
                                                <dd class="option" target="1">张湾镇</dd>
                                            </dl>
                                            <input type="hidden" class="val" value="0" />
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
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 可使用全时云企 IM 互传文档</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 服务器保存聊天记录</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许连接外部 IM</label>
                    <h3 class="setTitle">通话设置</h3>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设置接听策略</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked" /> 允许用户设定接听策略到海外电话</label>
                    <label class="checkbox checked"><input type="checkbox" checked="checked"  /> 允许使用全时云企拨打电话</label>
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
                    <dd style="display: none">
                        <div class="toolBar2">
                            <a class="btnGray btn btn_infoEdit2" ><span class="text">编辑信息</span><b class="bgR"></b></a>
                            <a class="btnBlue yes btn_infoSave2 hide" ><span class="text">保存</span><b class="bgR"></b></a>
                            <a class="btnGray btn btn_infoCancel2 hide" ><span class="text">取消</span><b class="bgR"></b></a>
                        </div>
                        <div id="infoBox03">
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
                                        <span class="infoText">部门一</span>
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
                        <div id="editBox03" style="display:none;">
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
                                        <div class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">部门一</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理的部门</dd>
                                                    <dd class="option selected" target="2">部门一</dd>
                                                    <dd class="option" target="3">部门二</dd>
                                                    <dd class="option" target="4">部门三</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">请选择管理的地区</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option selected" target="1">请选择管理的地区</dd>
                                                    <dd class="option" target="2">北京</dd>
                                                    <dd class="option" target="3">上海</dd>
                                                    <dd class="option" target="4">广州</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="1" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">请选择管理的成本中心</span>
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
                                <tr class="hideBar02">
                                    <td>
                                        <div id="weidu02" class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">地区</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择第二个管理维度</dd>
                                                    <dd class="option selected" target="2">地区</dd>
                                                    <dd class="option" target="3">成本中心</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">北京</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理的地区</dd>
                                                    <dd class="option selected" target="2">北京</dd>
                                                    <dd class="option" target="3">上海</dd>
                                                    <dd class="option" target="4">广州</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">请选择管理的成本中心</span>
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
                                        <div id="juese_1" class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">帐号管理员</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理员角色</dd>
                                                    <dd class="option" target="2">员工管理员</dd>
                                                    <dd class="option selected" target="3">帐号管理员</dd>
                                                    <dd class="option" target="4">生态管理员</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="3" />
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
                                        <div id="weidu01_1" class="combo selectBox w210">
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
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">部门一</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理的部门</dd>
                                                    <dd class="option selected" target="2">部门一</dd>
                                                    <dd class="option" target="3">部门二</dd>
                                                    <dd class="option" target="4">部门三</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">上海</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理的地区</dd>
                                                    <dd class="option" target="2">北京</dd>
                                                    <dd class="option selected" target="3">上海</dd>
                                                    <dd class="option" target="4">广州</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="3" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">请选择管理的成本中心</span>
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
                                <tr class="hideBar02 hide">
                                    <td>
                                        <div id="weidu02_1" class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">地区</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择第二个管理维度</dd>
                                                    <dd class="option selected" target="2">地区</dd>
                                                    <dd class="option" target="3">成本中心</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="combo selectBox w210">
                                            <a class="icon" ></a>
                                            <span class="text selected">北京</span>
                                            <div class="optionBox">
                                                <dl class="optionList">
                                                    <dd class="option" target="1">请选择管理的地区</dd>
                                                    <dd class="option selected" target="2">北京</dd>
                                                    <dd class="option" target="3">上海</dd>
                                                    <dd class="option" target="4">广州</dd>
                                                </dl>
                                                <input type="hidden" class="val" value="2" />
                                            </div>
                                        </div>
                                        <div class="combo selectBox w210 hide">
                                            <a class="icon" ></a>
                                            <span class="text selected">请选择管理的成本中心</span>
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
    </div>     
</div>	

<!-- end contRight -->


<div class="pop-box" id="allGroup2" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	
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
	var treeNodeStqyText = [
		{'text':'北京分公司'/*, 'children':[{'text':'研发部'},{'text':'市场部'},{'text':'营销部'}]*/}/*,
		
		{'text':'上海分公司'},
		{'text':'深圳分公司'},
		{'text':'香港分公司'},
		{'text':'西安分公司'}*/
	];
	function createStqyNode(){
		  var root = {
			"id" : "0",
			"text" : "创想空间商务通信服务有限公司",
			"value" : "86",
			"showcheck" : false,
			"complete" : true,
			"isexpand" : true,
			"checkstate" : 0,
			"hasChildren" : true
		  };
		  var arr = [];
		  for(var i=0;i<treeNodeStqyText.length; i++){
			var subarr = [];
			if(treeNodeStqyText[i]['children']){
				for(var j=0;j<treeNodeStqyText[i]['children'].length;j++){
				  var value = "node-" + i + "-" + j; 
				  subarr.push( {
					 "id" : value,
					 "text" : treeNodeStqyText[i]['children'][j]['text'],
					 "value" : value,
					 "showcheck" : false,
					 "complete" : true,
					 "isexpand" : true,
					 "checkstate" : 1,
					 "hasChildren" : false
				  });
				}
			}
			arr.push( {
			  "id" : "node-" + i,
			  "text" : treeNodeStqyText[i]['text'],
			  "value" : "node-" + i,
			  "showcheck" : false,
			  "complete" : true,
			  "isexpand" : true,
			  "checkstate" : 0,
			  "hasChildren" : subarr.length?true:false,
			  "ChildNodes" : subarr
			});
		  }
		  root["ChildNodes"] = arr;
		  return root; 
		}
		
	function deleteStAdmin(t){
		if($(t).hasClass("disabled")){
			return false;	
		}
		else {
			showDialog('弹窗_删除生态管理员.html');	
		}
	}
	$(function(){
		//checkbox();
		$('.selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		var treedata = [createStqyNode()];
				
		$("#tree").treeview({
			showcheck:true,
			data:treedata
		});
		
		$("#tree_node_0").addClass("bbit-tree-selected");
		 checkbox();
	
		/*$('.conTabsHead > li').click(function(){
			var ind = $(this).index();
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.conTabsCont > dd').eq(ind).show().siblings().hide();
			$('.contRight > div').eq(ind).show().siblings().hide();
		});*/
		
		
		$('.infoNav li').click(function(){
			
			var ind = $(this).index();
			//var len = $(this).parent("ul").children().length;
			
			//if(ind<len-1) {
			$(this).addClass('selected').siblings().removeClass('selected');
			$(this).parent(".infoNav").next('dl').children().eq(ind).show().siblings().hide();
			//}
		});
		
		$('.btn_infoEdit').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
			$(this).parent().next('.infoTable').find('.infoText').not('.dotEdit').each(function(){
				$(this).hide().next().removeClass('hide');
			});
		});
		$('.btn_infoCancel').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
			$$(this).parent().next('.infoTable').find('.infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});
		});
		$('.btn_infoSave').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
			$(this).parent().next('.infoTable').find('.infoText').not('.dotEdit').each(function(){
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
					if(val > 1) _option.eq(val-2).removeClass('hide');
					$('#editBox03 .infoTable:eq(0) .hideBar02').addClass('hide');
					if(val == 2) $('#editBox03 .infoTable:eq(0) .hideBar02').removeClass('hide');
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
		
		$('#juese_1').combo({
			redata:true,
			changedFn:function(){
					var val = $('#juese_1').find('input').val();
					if(val == 2 || val == 3){
						$('#editBox03 .infoTable:eq(1) .hideBar01').removeClass('hide');
					}else{
						$('#editBox03 .infoTable:eq(1) .hideBar01, #editBox03 .infoTable:eq(1) .hideBar02').addClass('hide');
					}
				}
		});
		
		$('#weidu01_1').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu01_1');
					var val = _this.find('input').val();
					//console.log(val);
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
					$('#editBox03 .infoTable:eq(1) .hideBar02').addClass('hide');
					if(val == 2) $('#editBox03 .infoTable:eq(1) .hideBar02').removeClass('hide');
				}
		});
		
		$('#weidu02_1').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu02_1');
					var val = _this.find('input').val();
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
				}
		});

		
		$(".checkbox").click(function(){
			$(".toolBar2").show();
		})
		
		$("#allGroup2 .pop-box-content").treeview({
			showcheck:false,
			data:treedata
		});
		
		$(".selectGroup").click(function(event){
			
			$("#allGroup2").toggle();
			event.stopPropagation();
		})
		
		$(document).click(function(){
			$("#allGroup2").hide();
	
			//$(".datepickers").empty();	
		})
	});
</script>
</body>
</html>