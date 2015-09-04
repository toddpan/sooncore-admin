<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--组织与员工-ldap.html-->
<div class="contHead">
	<span class="title01">组织管理</span>
    <div class="contHead-right">
	<div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)"  ></a></div>
	 
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input" />
		</div>
	</div>
    <ul class="menu" id="menu1">
        <li><a  onclick="loadCont('tag/addTagPage')">员工标签管理</a></li>
       <!-- <li><a  onclick="loadCont('ldap/showLdapPage')">LDAP设置</a></li> -->
    </ul>
    </div>
</div>
<!-- end contHead -->
<div class="contMiddle">
    <b class="resizeBar"></b>
    
    <div class="conTabs">
        <ul class="conTabsHead">
            <li class="selected">组织结构<span class="conline"></span></li>
            <li>成本中心</li>
        </ul>
        <!-- end conTabsHead -->
        <dl class="conTabsCont">
            <dd style="display:block;">
                
                 <div id="tree">
                	<ul class="ztree" id="ldapTree"></ul>
                </div>
                <!--<ul class="tree" style="display:block;">
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
                </ul>-->
                <!-- end tree -->
            </dd>
            <dd>
                <div class="toolBar" style="display: none">
                    <a class="addGroup"  title="添加成本中心"></a>
                    <a class="delGroup"  title="删除成本中心"></a>
                </div>
                <ul class="tree" id="centerTree" style="display:block;">
                    <li >
                        <a class="treeNode"  style="text-decoration: none">
                            <span class="treeNodeName" style="cursor:pointer">成本中心一</span>
                        </a>
                    </li>
                     <li>
                        <a class="treeNode"  style="text-decoration: none">
                            <span class="treeNodeName" style="cursor:pointer">成本中心二</span>
                        </a>
                    </li>
                </ul>
                <!-- end tree -->
            </dd>
        </dl>
        <span class="contabs-left"></span>
        <span class="contabs-right"></span>
        <!-- end conTabsCont -->	
    </div>
    <!-- end conTabs -->
    <div class="contRight">
        <div id="part01">
            <a class="link_limitSet"  onclick="toggleGroupLimit()" title="部门权限">部门权限</a>
			<div id="test"></div>
            <!--<div class="bread"><span>海尔</span>&nbsp;&gt;&nbsp;<span>海尔手机电子事业部</span>&nbsp;&gt;&nbsp;<span>研发部</span></div>
             end bread -->
            <div class="tabToolBar">
                <a class="btnBlue yes btnAddUser" style="display: none" href="javascript:showDialog('弹窗_添加员工.html');"><span class="text">添加员工</span><b class="bgR"></b></a>
                <div class="tabToolBox" style="display:none;">
                    <a class="btnGray btn btnBeManage" ><span class="text">指定为部门管理者</span><b class="bgR"></b></a>
                    <a class="btnGray btn btnMoveManage"  style="display:none;"><span class="text">取消管理者身份</span><b class="bgR"></b></a>
                </div>
            </div>
            <!-- end tabToolBar -->
            
                  <!--  <tr>
                        <td><label class="radio"><input type="radio" name="lList" value="李想" /></label></td>
                        <td class="tl"><a class="userName manage ellipsis" onclick="loadCont('organize/staInfoPowPage');">李想</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="刘恺威" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >刘恺威</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOff"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="Windy" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >Windy</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="王志良" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >王志良</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="黄凯" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >黄凯</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="董向然" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >董向然</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td><label class="radio" style="display: none"><input type="radio" name="lList" value="吴泽坤" /></label></td>
                        <td class="tl"><a class="userName ellipsis" >吴泽坤</a></td>
                        <td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        <td><a  class="btnOn"></a></td>
                    </tr>-->
                
            <!-- end table
            <div class="page">
                <a class="disabled" >首页</a>
                <a class="disabled" >上一页</a>
                <a class="num selected" >1</a>
                <a class="num " >2</a>
                <a class="num " >3</a>
                <a class="" >下一页</a>
                <a class="" >尾页</a>
                <span class="text ml10">第</span>
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span>
            </div> -->
        </div>
        <div id="part02" style="display:none;">
            <div class="bread">
           <span>成本中心</span>&nbsp;&gt;&nbsp;<span>未分类</span>
		   </div>
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
                        <th width="30">&nbsp;</th>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                      
                        <th>帐号操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >高晓波</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                        
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >梁智慧</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                       
                       <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >董向然</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>2013/2/25&nbsp;13:55</td>
                      
                        <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >韩晓斌</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                       
                       <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >黄凯</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        
                       <td><a  class="btnOn"></a></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="tl"><a class="userName ellipsis" >王志良</a></td>
                        <td><span class="ellipsis">lixiang_001@dadao.com</span></td>
                        <td>15877779999</td>
                        <td>未登录</td>
                        
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
                <input class="page-input"  type="text" value="" size="2" />
                <span class="text">页/3</span>
            </div>
        </div>
        <div class="groupLimit" style="display: none">
			<b class="arrow"></b>
			<div class="groupLimitContent">
            <div class="toolBar2" style="display: none">
				<a class="btnBlue yes"   onclick="$('.groupLimit').hide();"><span class="text">保存</span><b class="bgR"></b></a>
				<a class="btnGray btn"  onclick="$('.groupLimit').hide();"><span class="text">取消</span><b class="bgR"></b></a>
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
                 </div>    
		</div>
    </div>
    <!-- end contRight -->
</div>

<div class="pop-box" id="allGroup2" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	<ul class="ztree" id="selectTree"></ul>
    </div>
</div>
<script type="text/javascript" src="public/js/jquery.js"></script>
<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="public/js/tree.js"></script>
<script type="text/javascript" src="public/js/jquery.jscrollpane.js"></script>
<script type="text/javascript" src="public/js/jScrollPane.js"></script>
<script type="text/javascript">
var test='<div id="test"></div>';
function InitzTree()//初始化组织结构树
{ 
   var zNodes =[
	{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:false,nocheck:true},
	{ id:21, pId:2, name:""},
	{ id:3, pId:1, name:"海尔生活家电事业部", open:false,nocheck:true},
	{ id:31, pId:3, name:"市场部"},
	{ id:32, pId:3, name:"营销部"},
	{ id:4, pId:1, name:"海尔电脑事业部", open:false,nocheck:true},
	{ id:31, pId:4, name:""}
];
$.fn.zTree.init($("#ldapTree"), ldapSetting, zNodes);
$.fn.zTree.init($("#selectTree"), selectSetting, zNodes);
var zTree = $.fn.zTree.getZTreeObj("ldapTree");

}
function staff_information()
{
 /* $(".tabToolBar").load('+"'"+'<?php echo site_url('organize/staInfoPowPage')?>'+"'"+');*/
 $('.tabToolBar').hide();
 
 $('#part01 div.bread').siblings().hide();
 $('#test').show();
 //$('#part01 div.bread').after(test);
 $("#test").load('organize/staInfoPowPage');
}
function IntiStaff()
{
   
   var staff=[];
   staff[0]="邹燕";
   staff[1]="白兰";
   staff[2]="白雪";
   staff[3]="王奕婷";
   var staff_list="";
   staff_list='<table class="table part0">'+
                '<thead>'+
                    '<tr>'+
                       '<th width="6%"></th>'+
                        '<th style="text-align: left; text-indent: 24px">姓名</th>'+
                        '<th>帐号</th>'+
                        '<th>手机</th>'+
                       '<th>上次登录</th>'+
                        '<th>帐号操作</th>'+
                    '</tr>'+
                '</thead>'+
               '<tbody class="staff_list">'+
                  '<tr>'+
	                   '<td><label class="radio"><input type="radio" name="lList" value="李想" /></label></td>'+
                       '<td class="tl"><a class="userName manage ellipsis" >'+
	'<span onclick="staff_information()">'+staff[0]+'</span>'+'</a></td>'+
                        '<td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>'+
                        '<td>15877779999</td>'+
                        '<td>2013/2/25&nbsp;13:55</td>'+
                        '<td><a  class="btnOn"></a></td>'+
               '</tr>';
			   
   for(var i=1;i<staff.length;i++)
   {
      staff_list=staff_list+'<tr>'+
	                   '<td><label class="radio" style="display: none"><input  name="lList" value="李想" /></label></td>'+
                       '<td class="tl"><a class="userName ellipsis"'+
					   '>'+staff[i]+'</a></td>'+
                        '<td class="tl"><span class="ellipsis">lixiang_001@dadao.com</span></td>'+
                        '<td>15877779999</td>'+
                        '<td>2013/2/25&nbsp;13:55</td>'+
                        '<td><a  class="btnOn"></a></td>'+
                    '</tr>';
   }
   staff_list=staff_list+'</tbody></table>'+
                   '<div class="page part0">'+
                  '<a class="disabled" >首页</a>'+
                  '<a class="disabled" >上一页</a>'+
                  '<a class="num selected" >1</a>'+
                  '<a class="num " >2</a>'+
                  '<a class="num " >3</a>'+
                  '<a class="" >下一页</a>'+
                  '<a class="" >尾页</a>'+
                  '<span class="text ml10">第</span>'+
                  '<input class="page-input"  type="text" value="" size="2" />'+
                  '<span class="text">页/3</span>'+
                  '</div>';
   
  $('.tabToolBar').eq(0).after(staff_list); 
}
function checkCookie1()
      {
        var username=getCookie('target')
        if (username!=null && username!="")
       {
	   $('#part01 div.bread').siblings().show();
	     $('#test').hide();
	   }
	  
	   
    }
                       
$(function(){
	  InitzTree();
	   $('#test').click(function()
		{
		  checkCookie1();
		})
		//点击选中触发事件的小三角，为其添加标志loadCont('组织与员工_员工信息权限-ldap.html');
		if($('span').hasClass('button'))
		{ 
		  
		  $('span.button').click(function()
		  {
		     childNodes=[{ id:22, pId:2, name:"市场部"},
	        { id:23, pId:2, name:"营销部"}];
		     $('span.button').removeAttr("target");
			 $('span.button').parent().removeAttr("target");
		     $(this).attr("target","1");
			 $(this).parent().attr("target","1");
			
	       })
	    }
		//组织结构部分，点击部门载入该部门员工，同时显示目录级
		$(".ztree li a").live("click",function()
		{
		  $(this).parents("div").addClass("first");
		  if($(this).attr("target")!="1")
		   {
		   if($("#tree").hasClass('first'))
		    {
		     $('.part0').remove();
			 var zTree = $.fn.zTree.getZTreeObj("ldapTree");
	         var nodes = zTree.getSelectedNodes();
			 var value=[];
			 value.push(nodes[0].name);
			    while(nodes[0].pId!=null)
			    {
			      nodes = zTree.getNodesByParam("id", nodes[0].pId, null);
			       value.push(nodes[0].name);
			     }
			   var  staff_depart="";
			   staff_depart=' <div class="bread part0">';
			    for(var i=value.length-1;i>-1;i--)
			     {
			       staff_depart=staff_depart+'<span>'+value[i]+'</span>&nbsp;&gt;&nbsp';
			     }
			    staff_depart=staff_depart+"</div>";
			    $('.link_limitSet').after(staff_depart);
				IntiStaff();
				$('#tree').removeClass();
			 }
			 
			}
			else
			{
			  $(this).attr("target","2")
			}
		})
	  
		
		/*$('body').find("#test").click(function()
		{
		  //alert(1);
		})*/
		
		//成本中心部分，点击成本中心组织，载入成本中心员工
		$('#centerTree li').click(function()
		{
		 /* $('#part02').find(".bread").children().remove();*/
		 var  value=$(this).find('span').text();
		 $('#part02').find("span").eq(1).text(value);
		 $('#test').text(value);
		 $("#test").load('organize/staInfoPowPage');
		  /*var select_spend='';
		  select_spend='<span>成本中心</span>&nbsp;&gt;&nbsp;<span>'+value+'</span>';
		  $('#part02').find('.bread').append(select_spend);*/
		})
		
	
		/*var zNodes =[
	{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:false,nocheck:true},
	{ id:21, pId:2, name:"研发部"},
	{ id:22, pId:2, name:"市场部"},
	{ id:23, pId:2, name:"营销部"},
		/*{ id:211, pId:21, name:"大洋"},
		{ id:212, pId:21, name:"新象"},
		{ id:213, pId:21, name:"刘杰"},
		{ id:214, pId:21, name:"占奎"},
	
	{ id:3, pId:1, name:"海尔生活家电事业部", open:false,nocheck:true},
	{ id:31, pId:3, name:"市场部"},
	{ id:32, pId:3, name:"营销部"},
	{ id:4, pId:1, name:"海尔电脑事业部", open:false,nocheck:true},
	{ id:41, pId:4, name:"市场部"},
	{ id:42, pId:4, name:"营销部"},
];*/
		
		
		
		/*zTree.selectNode(zTree.getNodeByParam("id", 21));*/
		
		
		if($.browser.msie&&$.browser.version=="6.0"){
			var cw = $(".contMiddle").outerWidth();
			var ct = $(".conTabs").outerWidth();
			$(".contRight").css({
				"width": cw-ct-10,
				"float": "right",
				"margin": 0
			})
		
		}
	
		
		
		//拖拽改变模块宽度
		$('.resizeBar').mousedown(function(e){
			var _this = $(this);
			var bL = parseInt(_this.css('left'));
			var _conTabs = $('.conTabs');
			var conTabsW = _conTabs.width();
			var _contRight = $('.contRight');
			var contRightM = parseInt(_contRight.css('margin-left'));
			var eX = e.pageX;
			var dX = 0;
			var minW = 200;
			var maxW = 400;
			var rw = $(".rightCont").width();
			$('body').noSelect(true);
			$(document).mousemove(function(e){
				console.log(parseInt(_this.css('left')));
				//if(parseInt(_this.css('left')) > 200 && parseInt(_this.css('left')) < 400){
					dX = e.pageX - eX;
				if((conTabsW + dX)>200&&(conTabsW + dX)<rw-534){
					_this.css('left', bL + dX + 'px');
					_conTabs.css('width', conTabsW + dX + 'px');
					_contRight.css('margin-left', contRightM + dX + 'px');	
				}
			}).mouseup(function(e){
				$(document).unbind('mousemove');
				$('body').noSelect(false);
			});
		});
		
		//组织结构 表格全选
		//checkall('#part01 .table thead .checkbox', '#part01 .table tbody .checkbox', '#part01 .table .checkbox', toolBarSet);
		//toolBarSet();
		
		$('#part01 .table tbody .radio').live("click",function(){
			var checked = $('#part01 .table .radio').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('#part01 .tabToolBox').show();
			}else{
				$('#part01 .tabToolBox').hide();
			}
			var manage = $('#part01 .table .manage');
			if(manage.length==1){
				if(checked.length==1 && checked.parent().siblings().find('.userName').hasClass('manage')){
					$('.btnMoveManage').show();
					$('.btnBeManage').hide();
				}else{
					$('.btnMoveManage').hide();
					$('.btnBeManage').hide();
				}
			}else{
				if(checked.length==1){
					$('.btnMoveManage').hide();
					$('.btnBeManage').show();
				}else{
					$('.btnMoveManage').hide();
					$('.btnBeManage').hide();
				}
			}
		})
		//组织结构 表格操作条显隐及操作按钮显隐
		function toolBarSet(){
			var checked = $('#part01 .table .radio').filter(function(){return $(this).hasClass('checked');});
			if(checked.length){
				$('#part01 .tabToolBox').show();
			}else{
				$('#part01 .tabToolBox').hide();
			}
			
			
			
			//单选或多选时 管理员身份的设置和取消按钮的显隐
			var manage = $('#part01 .table .manage');
			if(manage.length==1){
				if(checked.length==1 && checked.parent().siblings().find('.userName').hasClass('manage')){
					$('.btnMoveManage').show();
					$('.btnBeManage').hide();
				}else{
					$('.btnMoveManage').hide();
					$('.btnBeManage').hide();
				}
			}else{
				if(checked.length==1){
					$('.btnMoveManage').hide();
					$('.btnBeManage').show();
				}else{
					$('.btnMoveManage').hide();
					$('.btnBeManage').hide();
				}
			}
		}
		
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
		
		$('.conTabsHead > li').click(function(){
		$('.tabToolBar').show();
			var ind = $(this).index();
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.conTabsCont > dd').eq(ind).show().siblings().hide();
			$('.contRight > div').eq(ind).show().siblings().hide();
			
		});
		//初始部门管理者
		//var _userName = $('#part01 .table tbody .userName');
		
		//_userName.eq(manageIndex).addClass('manage');
		
		//指定为部门管理者
		$('.btnBeManage').live("click",function(){
			var _checked = $('#part01 .table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			
			
			showDialog('弹窗_提醒_指定为部门管理者.html');
			
			$('.D_confirm .dialogBody .text01').html('您确定要将 '+_name.text()+' 指定为该部门的管理者吗？');
			
			$('.D_confirm .btn_confirm').die("click").live("click",function(){
				_name.addClass('manage');
				_checked.parents("tr").siblings("tr").find(".radio").hide();
				_checked.click();
				hideDialog();
			});
						
		});
		
		
		//取消管理者身份
		$('.btnMoveManage').click(function(){
			var _checked = $('#part01 .table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			
			showDialog('弹窗_提醒_取消管理者身份.html');
			
			
			$('.D_confirm .dialogBody .text01').html('您确定要取消 '+_name.text()+' 的部门管理者身份吗？');
			$('.D_confirm .btn_confirm').die("click").live("click",function(){
				//alert("dddd");
				_name.removeClass('manage');
				_checked.parents("tr").siblings("tr").find(".radio").show();
				//manageIndex = _checked.index();
				_checked.removeClass("checked");
				_checked.find("input").removeAttr("checked");
				hideDialog();	
			});
			
			//$('.btnMoveManage') = null
			
		});
		
		//删除员工
		$('.btnDeleUser').click(function(){
			var _checked = $('#part01 .table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			showDialog('弹窗_提醒_删除员工.html');
			
				$('.D_confirm .dialogBody .text01').html('你确定要从组织结构中删除员工 '+_name.text()+' 吗？');
				$('.D_confirm .btn_confirm').die("click").click(function(){
					_checked.parent().parent().remove();
					$('#part01 .tabToolBox').hide();
					hideDialog();
				});
			
		});
		
		//批量导入提示气泡
		if(login){
			$('.poptip').hide();
		}else{
			$('.poptip').show();
		}
		$('.poptip .btn_iKnow').click(function(){
			$('.poptip').animate({'opacity':0},300,function(){
				$('.poptip').hide();
				$('.poptip2').show();
			});
		});
		$('.poptip2 .btn_iKnow').click(function(){
			$('.poptip2').animate({'opacity':0},300,function(){
				$('.poptip2').hide();
			});
			login = 1;
		});
		
		$(".checkbox").click(function(){
			$(".toolBar2").show();
		})
	});
</script>
</body>
</html>