<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
</head>
<body>
<!--搜索_组织与帐号.html-->
<div class="contHead">
	<span class="title01 rightLine">企业生态</span><span class="title03 rightLine">搜索结果</span> 
    <div class="contHead-right">
	<div class="fr rightLine"><a class="btnLabel"  onclick="loadPage('ecologycompany/appointPage','company')"></a></div>
	
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
<div class="contMiddle">
    
    
    <!-- end conTabs -->
    <div class="toolBar2" style="margin-top: -20px; margin-bottom: 20px;">
        <a class="back fl" onclick="loadCont('ecologycompany/ecologyPage');" title="返回">&nbsp;</a></div>
            <div id="part1">
            <div class="msg-bar">
              <div class="msg-bar-left">为您找到 "<span class="red">1</span>"个相关生态企业</div>
            </div>
          	<div class="tabToolBar">
                <div class="tabToolBox fl" style="display:none;">
                  <a class="btnGray btn btnMoveManage" onclick="showDialog('ecologycompany/deleteEcologyCompany')"><span class="text">删除生态企业</span><b class="bgR"></b></a>
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
                        <td><a  onclick="loadCont('ecologycompany/ecologyInfoPage')">北京分公司</a></td>
                        <td>北京</td>
                        <td><a  onclick="loadCont('manager/ecologyAdmin')">陈总</a></td>
                        <td><a  onclick="$('.conTabsHead li:first').trigger('click')"  class="ellipsis">北京创想空间商务通信服务有限公司</a></td>
                        <td><a  onclick="loadCont('manager/ecologyAdmin')">陈总</a></td>
                    
                    </tr>
                   
                   
                    
                </tbody>
            </table>
            </div>
            <div id="part2">
             <div class="msg-bar">
              <div class="msg-bar-left">为您找到 "<span class="red">2</span>"个相关生态企业管理员</div>
            </div>
            
            <div class="tabToolBar">
                <a class="back fl" onclick="loadCont('ecologycompany/organizeStaff');" title="返回">&nbsp;</a>
                <div class="tabToolBox" style="display:none;">
                    <a class="btnGray btn btnChangeUser" ><span class="text">员工调岗</span><b class="bgR"></b></a>
                    <a class="btnGray btn btnDeleUser" ><span class="text">删除员工</span><b class="bgR"></b></a>
                    
                </div>
            </div>
            <!-- end tabToolBar -->
            <table class="table">
                <thead>
                    <tr>
                        <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                        <th>帐号操作</th>
                    </tr>
                </thead>
                <tbody>                   
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis"  onclick="loadCont('ecologycompany/staffFunctionPage');">Windy</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" /></label></td>
                        <td class="tl"><a class="userName ellipsis" onclick="loadCont('ecologycompany/staffFunctionPage');">王志良</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                </tbody>
            </table>
            <!-- end table -->
            </div>
  
        
        
    
    <!-- end contRight -->
</div>



<script type="text/javascript">
	
	$(function(){
		//checkbox();
		
		//组织结构 表格全选
		//checkall('#part1 .table thead .checkbox', '#part1 .table tbody .checkbox', '#part1 .table .checkbox', toolBarSet);

		
		
		
		//checkall('#part2 .table thead .checkbox', '#part2 .table tbody .checkbox', '#part2 .table .checkbox', toolBarSet2);
		
		//组织结构 表格操作条显隐及操作按钮显隐
		function toolBarSet(){
			var checked = $('#part1 .table .checkbox').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('#part1 .tabToolBox').show();
			}else{
				$('#part1 .tabToolBox').hide();
			}
			
		}
		
		function toolBarSet2(){
			var checked = $('#part2 .table .checkbox').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('#part2 .tabToolBox').show();
			}else{
				$('#part2 .tabToolBox').hide();
			}
			
		}
		
		//删除员工
		$('.btnDeleUser').click(function(){
			
			var _checked = $('#part2 .table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			
			
			showDialog('staff/deleteStaff');
			
			
			
		});
		
		
		
		
		
		
	});
</script>
</body></html>