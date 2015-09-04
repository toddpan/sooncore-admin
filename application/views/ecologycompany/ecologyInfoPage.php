<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!-- init-stqy2.html -->
<div class="contHead">
	<span class="title01">企业生态</span>
        <div class="contHead-right"><div class="fr rightLine"><!--<a class="btnSet"  onclick="toggleMenu('menu1',event)"></a>--> <a class="btnLabel"  onclick="loadPage('<?php echo site_url('ecologycompany/appointPage')?>','company')"></a></div>
	
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
		<li class="selected">生态企业<span class="conline"></span></li>
		<li>管理员</li>
	</ul>
	<!-- end conTabsHead -->
	<dl class="conTabsCont">
		<dd style="display:block;">
			<div class="toolBar">
                            <a class="addGroup"  onclick="loadPage('<?php echo site_url('ecologycompany/createEcologyCompany')?>','company')"  title="创建生态企业"></a>
				<a class="delGroup"  title="删除生态企业" onclick="showDialog('<?php echo site_url('ecologycompany/deleteEcologyCompany')?>')"></a>
			</div>
			<div id="tree"></div>
			<!-- end tree -->
		</dd>
		<dd>
			 <div class="toolBar" style="display: block">
                    <a class="addGroup"  onclick="showDialog('<?php echo site_url('ecologycompany/ecologyManagerPage')?>' + '/2')" title="添加生态管理员"></a>
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
<div id="part01">
	<div class="part01_1" style="display: none">
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
    <div class="part01_2" style="display: block">
    	
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
					<a class="btnBlue btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a>
				</div>
				<table class="infoTable">
					<tr>
						<td class="tr">生态管理员：</td>
						<td>
                                                    <span class="infoText dotEdit">JACKSON</span> &nbsp; <a   onclick="showDialog('<?php echo site_url('ecologycompany/ecologyManagerPage');?>' + '/1')">更改</a>
						</td>
					</tr>
					<tr>
						<td class="tr">企业名称：</td>
						<td>
							<span class="infoText">北京分公司</span>
                            <div class="inputBox hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" style="width: 454px" value="北京分公司" />
							</div>
						</td>
					</tr>
					<tr>
						<td class="tr">简称：</td>
						<td>
							<span class="infoText">北京分公司</span>
							<div class="inputBox hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" style="width: 454px"  value="北京分公司" />
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
							<div class="inputBox hide">
								<b class="bgR"></b>
								<label class="label"></label>
								<input class="input" style="width: 454px"  value="北京 通州区 张湾镇" />
							</div>
						</td>
					</tr>
					<tr>
						<td class="tr" style=" vertical-align: top">公司介绍：</td>
						<td>
							<span class="infoText">我们致力于通过互联网和通信协作技术来提高商业效率，帮助企业迅速获得商业动力，而不需投资昂贵的固定资产，全时已经为超过300家世界500强企业以及超过3500家中国企业提供服务，显著提高了企业商业运作的效率。全时将坚持持续帮助商务人士更高效地工作，以实现客户的商业目标。全时隶属北京创想空间商务通信服务有限公司。</span>
                           <div class="textareaBox hide"> <textarea class="textarea" style="width: 454px; height: 100px;">我们致力于通过互联网和通信协作技术来提高商业效率，帮助企业迅速获得商业动力，而不需投资昂贵的固定资产，全时已经为超过300家世界500强企业以及超过3500家中国企业提供服务，显著提高了企业商业运作的效率。全时将坚持持续帮助商务人士更高效地工作，以实现客户的商业目标。全时隶属北京创想空间商务通信服务有限公司。</textarea></div>
						</td>
					</tr>
					
				</table>
			</dd>
			<dd style="display:none;">
				<div class="toolBar2">
                	<a class="btnGray btn btn_infoEdit2" ><span class="text" style="cursor: pointer">编辑信息</span><b class="bgR"></b></a>
					<a class="btnBlue hide btn_save2" ><span class="text" style="cursor: pointer">保存</span><b class="bgR"></b></a>
					<a class="btnGray btn hide btn_cancel2" ><span class="text" style="cursor: pointer">取消</span><b class="bgR"></b></a>
				</div>
				<!-- end tabToolBar -->
				<div class="setStqy">
				<label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许召开网络会议</label>
				<label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许召开电话会议</label>
                <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许会中外呼</label>
                <label class="checkbox checked disabled"> <input type="checkbox" disabled="disabled" checked="checked" />允许设置呼叫转移</label>
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
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">李想</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">刘恺威</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOff"></a></td>
                    </tr>
                    <tr>
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">Windy</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>.html','group');">王志良</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                      
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">黄凯</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">董向然</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                       
                        <td class="tl"><a class="userName ellipsis"  onclick="loadPage('<?php echo site_url('ecologycompany/staffFunctionPage')?>','group');">吴泽坤</a></td>
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
                    <a class="btnBlue btnAddUser" onclick="showDialog('ecologycompany/addEcologyStaffWindow');"><span class="text">添加员工</span><b class="bgR"></b></a>
                    <div class="tabToolBox" style="display: none">
                       
                        <a class="btnGray btn btnDeleUser"  onclick="showDialog('弹窗_提醒_移除生态员工.html')"><span class="text">移除员工</span><b class="bgR"></b></a>
                        
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
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">李想</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                           
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">刘恺威</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                          
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">Windy</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>2013/2/25&nbsp;13:55</td>
                           
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">王志良</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                            
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">黄凯</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                          
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">董向然</a></td>
                            <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                            <td>15877779999</td>
                            <td>未登录</td>
                           
                        </tr>
                        <tr>
                            <td><label class="checkbox"><input type="checkbox" /></label></td>
                            <td class="tl"><a class="userName ellipsis"  onclick="loadPage('组织与员工_员工信息权限.html','group');">吴泽坤</a></td>
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
<div id="part02" style="display:none;">
         
            <div class="tabToolBar">
                <a class="btnBlue btnAddUser"  onclick="loadPage('创建生态企业1.html','companyAdmin')"><span class="text">创建生态企业</span><b class="bgR"></b></a> 
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
                        <td><a  onclick="loadCont('<?php echo site_url('ecologycompany/ecologyPage');?>')"  class="ellipsis">北京创想空间商务通信服务有限公司</a></td>
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
		showDialog('<?php echo site_url('ecologycompany/closeAccount')?>');
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
		var treedata = [createStqyNode()];
				
		$("#tree").treeview({
			showcheck:true,
			data:treedata
		});
		
		$("#tree_node_0").addClass("bbit-tree-selected");
		 //checkbox();
	
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
		
		$(".bbit-tree-node > div").click(function(){
			$(".bbit-tree-node div").removeClass("bbit-tree-selected");
			$(this).addClass("bbit-tree-selected");
		})
		
		$('.infoNav li').click(function(){
			
			var ind = $(this).index();
			//var len = $(this).parent("ul").children().length;
			
			//if(ind<len-1) {
			$(this).addClass('selected').siblings().removeClass('selected');
			$(this).parent(".infoNav").next('.infoCont').children().eq(ind).show().siblings().hide();
			//}
		});
		
		$('.btn_infoEdit').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).hide().next().removeClass('hide');
			});
		});
		$('.btn_infoEdit2').click(function(){
			$(this).addClass('hide').siblings('.btn_save2, .btn_cancel2').removeClass('hide');
			$('.setStqy label').each(function(){
				$(this).removeClass('disabled').find("input").removeAttr("disabled");
			});
		});
		$('.btn_save2').click(function(){
			$(this).addClass('hide').siblings('.btn_cancel2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
			$('.setStqy label').each(function(){
				$(this).addClass('disabled').find("input").attr("disabled","disabled");
			});
		});
		$('.btn_cancel2').click(function(){
			$(this).addClass('hide').siblings('.btn_save2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
			$('.setStqy label').each(function(){
				$(this).addClass('disabled').find("input").attr("disabled","disabled");
			});
		});
		
		$('.btn_infoCancel').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});
		});
		$('.btn_infoSave').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
				var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : $(this).next().hasClass('textareaBox')?$(this).next().find('textarea').val():'';
				$(this).text(text);
			});
		});
		
		
		$(".table thead input[type='checkbox']").live("click",function(){
			if($(this).is(":checked")){
				$(".table tbody input[type='checkbox']").attr("checked","checked");
				$(".table tbody .checkbox").addClass("checked");
				var len = $(".table tbody .checked").length;
				if(len>0){
					$(".tabToolBar .tabToolBox").show();
				}
				else {
					$(".tabToolBar .tabToolBox").hide();
				}
			}
			else {
				$(".table tbody input[type='checkbox']").removeAttr("checked");
				$(".table tbody .checkbox").removeClass("checked");
				$(".tabToolBar .tabToolBox").hide();
			}
		})
		
		$(".table tbody input[type='checkbox']").live("click",function(){
			var len = $(".table tbody .checkbox").length;
	
			if($(this).is(":checked")){
				$(".tabToolBar .tabToolBox").show();
				var checkLen = $(".table tbody .checked").length;
				
				if(len == checkLen+1) {
					$(".table thead .checkbox").addClass("checked");
					$(".table thead input[type='checkbox']").attr("checked","checked");
				}
			}
			else {
				$(".table thead .checkbox").removeClass("checked");
				$(".table thead input[type='checkbox']").removeAttr("checked");
				var checkLen = $(".table tbody .checked").length;
				
				if(checkLen == 1) {
					$(".tabToolBar .tabToolBox").hide();
				}
			}
		})
		
		$("#allGroup2 .pop-box-content").treeview({
			showcheck:false,
			data:treedata
		});
		
		$(".selectGroup").click(function(event){
			
			$("#allGroup2").toggle();
			event.stopPropagation();
		})
		
		$(".bbit-tree-node-ct li").live("click",function(){
			$(".part01_2").show().siblings().hide();
			$("#tree_0").removeClass("bbit-tree-selected")	
		})
		$("#tree_0").live("click",function(){
			$(".part01_1").show().siblings().hide();
		})
		
		$(document).click(function(){
			$("#allGroup2").hide();
	
			//$(".datepickers").empty();	
		})
	});
</script>
</body>
</html>
