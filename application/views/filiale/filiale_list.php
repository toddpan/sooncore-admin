<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>指定集团分公司</title>
<link type="text/css" href="<?php echo base_url('public/css/filiale/style.css');?>" rel="stylesheet" />
<link type="text/css" href="<?php echo base_url('public/css/filiale/zTreeStyle.css');?>" rel="stylesheet" />
</head>
<body>
<div class="header">
	<a  class="logo"></a>
</div>
<div class="topMenu">
	<div class="topMenuMain">
    	<ul>
        	<li><a  class="back" id="back_step">回到上一层</a></li>
            <li><a  class="save" id="create_filiale" style="cursor:pointer">创建分公司</a></li>
        </ul>
        <div class="topmenuName">Marc Jacobs  |  退出</div>
    </div>
</div>
<div class="first_list">
<h2 class="ucH2">集团分公司列表</h2>
<div class="jituanBox">
	<table>
    	<tr>
        	<th><div>分公司名称</div></th>
            <th><div>分公司所在地</div></th>
			<th><div>上级企业</div></th>
            <th><div>管理员</div></th>
            <th>操作</th>
        </tr>
         {foreach $data as $v}
        <tr>
        	<td><div id="company_name" user_id="">{$v["name"]}</div></td>
            <td class="tc"><div>{$v["location"]}</div></td>
			<td class="tc"><div>{$v["pre_company"]}</div></td>
            <td class="tc"><div>{$v["staff"]}</div></td>
            <td class="tc"><a  class="blue">删除</a>  <span class="line">|</span>  <a  class="blue" onclick="show_changemanager(this)">变更管理员</a></td>
        </tr>
        {/foreach}
    </table>
</div>
</div>
<div class="second_filiale" style="display:none">
<div class="setManagemain">
  <div class="setManagebox">
      <div class="setManTitle">公司信息</div>
      <div class="setManTable">
      <table>
        <tr>
			<td class="left">子公司名称：</td>
			<td colspan="3" >
				<div class="select-box hide">
					<input style="z-index:2" cl_id="part1" type="" class="textI" value="" onClick="showMenu(this);" id="departmentSel2" placeholder="请选择公司" id="company"/>
					<a class="icon" cl_id="part1" onClick="showMenu(this);"></a>
					<div class="selectOptionBox"   target='0'  style="display: none;z-index:9;width: 489px;">
						<ul class="ztree" id="ztree">
						</ul>
					</div>
				</div></td>
        </tr>
        <tr>
            <td class="left">上级企业：</td>
             <td colspan="3"><input type="text" class="textI" /></td>
        </tr>
      	<tr>
        	<td class="left">公司所在国家</td>
            <td>
				<div class="select" id="select_country">
					<a >请选择国家名称</a>
					<ul style="display:none" class="">
						<li>中国</li>
						<li>美国</li>
						<li>澳大利亚</li>
						<li>新西兰</li>
					</ul>
				</div>
			</td>
           <td class="left">省份：</td>
           <td><div class="select" id="shengfen"><a >请选择</a><ul><li>北京市</li><li>天津市</li><li>河北省</li></ul></div></td>
        </tr>
        <tr>
            <td class="left">乡镇地区：</td>
            <td><input type="text" class="textI w183" /></td>
            <td class="left">地址：</td>
            <td colspan="3"><input type="text" class="textI" /></td>
        </tr>
        <tr>
        	<td class="left">公司网址：</td>
            <td colspan="3"><input type="text" class="textI" /> <span class="hui">例如：www.abc.com.cn</span></td>
        </tr>
		 <tr>
        	<td class="left">全时站点：</td>
            <td colspan="3"><input type="text" class="textI" /> <span class="hui">例如：abc.quanshi.com</span></td>
        </tr>
      </table>
      </div>
      <div class="setManTitle">公司管理方式</div>
      <div class="setManTable">
      <table>
        <tr>
        	<td class="left" width="130">公司形态：</td>
            <td colspan="3"><label class="radioBox company" ><input type="button">子公司</label></td>
        </tr>
		<tr>
        	<td class="left" width="130"></td>
            <td colspan="3"><label class="radioBox group"><input type="button">子集团</label></td>
        </tr>
		<tr>
        	<td class="left" width="130" style="left:100px"></td>
            <td colspan="3">
			 <div class="setManagePop" style="float:left">
				<dl>
                	<dd >管理方式：</dd>
                     <dd id="ddLast"><label class="radioBox focus">
							<input type="button" />集中管理
						</label>
						
						<label class="radioBox disperse">
							<input type="button"/>分散式管理
						</label>
					</dd>
                </dl>				
               
             </div> 
			</td>
        </tr>
		<tr>
        	<td class="left" width="130" style="left:100px">员工创建方式：</td>
            <td colspan="3">				           	                	
                    <dd id="ddFirst">
					<label class="radioBox ldap">
							<input type="button" />LDAP同步
					</label>
					<label class="radioBox bath">
							<input type="button" />批量导入
					</label>						
					</dd>               				
			</td>
        </tr>
      </table>
      </div>
  </div>
</div>
<div class="footer">创想空间商务通信服务有限公司<span>/G-Net Integrated Services Co., Ltd.</span> 客服热线: <span>400-810-1919 Email: service@quanshi.com</span></div>
</div>
<div class="mask"></div>
<div id="dialog" class="dialog">
 <div class="dialogBorder"></div>
 <!-- 
  <b class="bgTL"></b>
  <b class="bgTR"></b>
  <b class="bgBL"></b>
  <b class="bgBR"></b>
  -->
  <b class="shadow"></b>
</div>
<script type="text/javascript" src="<?php echo base_url('public/js/filiale/common.js"');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/filiale/jquery1.7.2.js"');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/filiale/jquery.ztree.all-3.5.min.js"');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/filiale/self_tree.js"');?>"></script>
<script type="text/javascript">
$(function(){
	$('#company_name').die('click');
	$('#company_name').live('click',function()
	{	
		$('.first_list').hide();
		$('.second_filiale').show();
		$('#create_filiale').addClass("show_create");
		$('#create_filiale').html("完成");
		var obj={
		"id":$(this).attr("user_id")
		}
		$.post('',obj,function(data)
		{
			$('.second_filiale').append(data);
			var zNodes =[
			{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
			{ id:2, pId:1, name:"海尔产品开发部", open:true,nocheck:true},
			{ id:21, pId:2, name:"研发部"},		
			{ id:22, pId:2, name:"市场部"},
			{ id:23, pId:2, name:"营销部"},
			{ id:3, pId:1, name:"海尔生活家电事业部", open:true,nocheck:true},
			{ id:31, pId:3, name:"市场部"},
			{ id:32, pId:3, name:"营销部"},
			{ id:4, pId:1, name:"海尔电脑事业部", open:true,nocheck:true},
			{ id:41, pId:4, name:"市场部"},
			{ id:42, pId:4, name:"营销部"}];
			$.fn.zTree.init($("#ztree"), stqySetting, zNodes);
			$.post('',[],function(data)
			{
				
			})
		})
	})
	$('#create_filiale').click(function()
	{
		//alert(111)
		if($(this).hasClass("show_create"))
		{
			//showDialog("create_manager.html");
			showDialog("<?php echo site_url('filiale/filiale/create_filial_page'); ?>");
		}
		else{
			$('.first_list').hide();
			$('.second_filiale').show();
			$(this).addClass("show_create");
			$(this).html("完成");
			var zNodes =[
			{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
			{ id:2, pId:1, name:"海尔产品开发部", open:true,nocheck:true},
			{ id:21, pId:2, name:"研发部"},		
			{ id:22, pId:2, name:"市场部"},
			{ id:23, pId:2, name:"营销部"},
			{ id:3, pId:1, name:"海尔生活家电事业部", open:true,nocheck:true},
			{ id:31, pId:3, name:"市场部"},
			{ id:32, pId:3, name:"营销部"},
			{ id:4, pId:1, name:"海尔电脑事业部", open:true,nocheck:true},
			{ id:41, pId:4, name:"市场部"},
			{ id:42, pId:4, name:"营销部"}];
			$.fn.zTree.init($("#ztree"),stqySetting,zNodes);
			$.post('',[],function(data)
			{
				
			})
		}
		
	})
	$('#back_step').click(function()
	{
		if($('#create_filiale').hasClass("show_create"))
		{
			$('.first_list').show();
			$('.second_filiale').hide();
			$('#create_filiale').removeClass("show_create");
			$('#create_filiale').html("创建分公司");
		}
	})
	$('.set_list').click(function()
	{
		$('.first_list').show();
		$('.second_filiale').hide();
	})
	$(".topMenuMain li").hover(function(){
		$(this).addClass("current");
		},
	function(){
		$(this).removeClass("current");
		})
	$('.second_filiale .save').click(function()
	{
		showDialog("create_manager.html");
	})
	$(".topMenuMain li").hover(function(){
		$(this).addClass("current");
		},
	function(){
		$(this).removeClass("current");
		});
		
	$("#ddFirst input").click(function(){
		$("#ddFirst input").removeClass("checked");
		$(this).addClass("checked");
	});
	
	
	$(".radioBox input").click(function(){
		
		if($(this).parent().parent().hasClass("disabled"))
		{			
			return;
		}
			//$(".radioBox input").removeClass("checked");
			$(this).addClass("checked");
			if($(this).parent().hasClass('company'))
			{
				$('.group input').removeClass("checked")
				$('#ddLast').addClass("disabled");
				$('#ddLast .checked').removeClass("checked")
				
			}
			else if($(this).parent().hasClass('group'))
			{
				$('.company input').removeClass("checked")
				$('#ddLast').removeClass("disabled");
				
			}
			else if($(this).parent().hasClass('focus'))
			{
				
				$('.disperse input').removeClass("checked")
			}
			else if($(this).parent().hasClass('disperse'))
			{
				
				$('.focus input').removeClass("checked")
			}
			else if($(this).parent().hasClass('ldap'))
			{
				$('.bath input').removeClass("checked")
			}
			else if($(this).parent().hasClass('bath'))
			{
				$('.ldap input').removeClass("checked")
			}
		//alert($(this).parent().text())
		/*if($(this).parent().text()=="子公司")
		{
			//alert(1)
			$('.setManagePop dl:eq(1) label:eq(1)').addClass("disabled");
			$('.setManagePop dl:eq(1) label:eq(0) input').addClass("checked");
		}
		else
		{
			//alert(2)
			$('.setManagePop dl:eq(1) label:eq(0) input').removeClass("checked");
			$('.setManagePop dl:eq(1) label:eq(1)').removeClass("disabled");
		} */
	});
	
	/*$(".select a").click(function(){
		$(".select ul").show();
	});*/
	$(".second_filiale #select_country ul li").click(function(){
		$("#select_country a").html($(this).text());
		$("#select_country a").css("color","#000");
		$('#select_country').removeClass("selected");
	});
	$(document).click(function(e)
	{
		//alert(111)
		var t=$(e.target);
		if(!t.hasClass("select"))
		{
			$('.select').find("ul").removeClass("selected").css("display","none");	
		}
	})
	$('#select_country').toggle(function()
	{
		//alert(1)
		if($(this).find("ul").hasClass("selected"))
		{
			//alert(11)
			$(this).find("ul").removeClass("selected").css("display","none");
		}
		else
		{
		
			$(this).find("ul").addClass("selected").css("display","block");
				//alert(12)
			//alert($(this).find("ul").attr("display"))
		}
	},function()
	{
		//alert(2)
		if($(this).find("ul").hasClass("selected"))
		{
		//alert(3)
			$(this).find("ul").removeClass("selected").css("display","none");
		}
		else
		{
			//alert(4)
			$(this).find("ul").addClass("selected").css("display","block");
		}
	})
	$(".second_filiale ul li").mouseenter(function()
	{
		$(this).addClass("hover");
	}).mouseleave(function()
	{
		$(this).removeClass("hover");
	})	
	$(".second_filiale .back").click(function()
	{
		$(".second_filiale").hide();
		$('.first_list').show();
	})
	$(document).click(function(e)
	   {		  
		   
		    if($(e.target).attr("cl_id")!="part1")
		   { 
		   	if($(e.target).parentsUntil(".selectOptionBox").hasClass("ztree"))
			   {
				   
				   return;
			   }
			   $('.selectOptionBox').hide();
		   }
		   
	   })
	/*$('.first_list .jituanBox tbody tr td:eq(3) a:eq(1)').die('click');
	$('.first_list .jituanBox tbody tr td:eq(3) a:eq(1)').live('click',function()
	{
		
		showDialog("change_manager.html");
		$('#dialog').show();
	})*/
})
function show_changemanager(t)
{
		showDialog("change_manager.html");
		$('#dialog').show();
}
function showMenu(t)
{
	$(t).parent().find(".selectOptionBox").show()
}

</script>
</body>
</html>
