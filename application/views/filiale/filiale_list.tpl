<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<base href="{$tag_base_url}"/>
		<base target="_blank" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>指定集团分公司</title>
		<link type="text/css" href="public/css/filiale/style.css" rel="stylesheet" />
		<link type="text/css" href="public/css/filiale/zTreeStyle.css" rel="stylesheet" />
	</head>
	<body>
		<div class="header">
			<a class="logo"></a>
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
		<div class="first_list" customerCode="{$customerCode}">
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
						 {foreach $filiale_info as $v}
						<tr>
							<td>
								<div style="color:#0093d5;cursor:pointer" id="company_name" user_id="{$v["org_id"]}">{$v["filiale_name"]}</div>
							</td>
							<td class="tc"><div>{$v["area"]}</div></td>
							<td class="tc"><div>{$v["parent_name"]}</div></td>
							<td class="tc"><div>{$v["manager_name"]}</div></td>
							<td class="tc">
								<a  class="blue">删除</a> 
							 	<span class="line">|</span> 
							  	<a  class="blue" onclick="show_changemanager(this)">变更管理员</a>
							</td>
						</tr>
						{/foreach}
   					 </table>
				</div>
		</div>
		<div class="second_filiale" style="display:none">
		</div>
		<div class="mask" style="display:none"></div>
			<div id="dialog" class="dialog">
 				<div class="dialogBorder"></div>
			  	<b class="bgTL"></b>
			  	<b class="bgTR"></b>
			  	<b class="bgBL"></b>
			 	<b class="bgBR"></b>
			 	<b class="shadow"></b>
		</div>
		<script type="text/javascript" src="public/js/filiale/common.js"></script>
		<script type="text/javascript" src="public/js/filiale/jquery1.7.2.js"></script>
		<script type="text/javascript" src="public/js/filiale/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/filiale/self_tree.js"></script>
		<script type="text/javascript">
		$(function()
		{
			//点击公司名称
			$('#company_name').die('click');
			$('#company_name').live('click',function()
			{	$('.second_filiale').html('');
				$('.first_list').hide();
				$('.second_filiale').show();
				$('#create_filiale').addClass("show_create");
				$('#create_filiale').html("完成");
				var obj={
				"id":$(this).attr("user_id")
				}
				$.post('filiale/filiale/create_filial_page',obj,function(data)
				{
					$('.second_filiale').append(data);
					$.post('',[],function(data)
					{
						
					})
				})
			})
			//点击创建分工公司
			$('#create_filiale').click(function()
			{
				//alert(111)
				if($(this).hasClass("show_create"))
				{
					$('.error').removeClass("error");
					$('#error1').hide();
					$('#error2').hide();
					var count=0;
					//子公司名称
					var parent_site_id='';
					parent_site_id=$('#parent_site_id .selectOptionBox li.selected').attr("user_id");
					var parent_org_id=$('#parent_site_id .selectOptionBox li.selected').attr("org_id");
					 if(parent_site_id=="" || parent_site_id==undefined)
					 {
						count++;
						$('#parent_site_id ').addClass("error");
					 }
					 
					//上级企业
					var filiale_name=$('#filiale_name').val();
					
					if(filiale_name=="")
					 {
						count++;
						$('#filiale_name').addClass("error");
					 }
					//公司所在国家
					var select_country="";
					select_country=$('#select_country ul li.selected').text();
					
					if(select_country=="请选择国家名称" || select_country=="")
					 {
						count++;
						$('#select_country ').addClass("error");
					 }
					//省份
					var province='';
					province=$('#province ul li.selected').text();
					if(province=="请选择" || province=='')
					 {
						count++;
						$('#province ').addClass("error");
					 }
					//乡镇地区
					var city=$('#city').val();
					if(city=="")
					 {
						count++;
						$('#city ').addClass("error");
					 }
					//地址
					var address=$('#address').val();
					if(address=="")
					 {
						count++;
						$('#address ').addClass("error");
					 }
					//公司网址
					var cor_site_url=$('#cor_site_url').val();
					if(cor_site_url=="")
					 {
						count++;
						$('#cor_site_url').addClass("error");
					 }
					//全时站点
					var site_url=$('#site_url').val();
					if(site_url=="")
					 {
						count++;
						$('#site_url').addClass("error");
					 }
					//公司形态
					var filiale_type='';
					var manage_type='';
					var is_ldap='';
					if($('.company input').hasClass("checked"))
					{	
									
						manage_type=0;
					}
					if($('.group input').hasClass("checked"))
					{
						
						if($('.focus input').hasClass("checked"))
						{
							manage_type=1;
						}
						else if($('.disperse input').hasClass("checked"))
						{
							manage_type=2;
						}
					}
					
					if(manage_type=="" && manage_type!=0)
					 {
						count++;
						
						$('#error1').show();
					 }
					 
					if($('.ldap input').hasClass("checked"))
					{
						is_ldap=1;
					}
					if($('.bath input').hasClass("checked"))
					{
						is_ldap=0;
					}
					if(is_ldap=="" && is_ldap!=0)
					 {
						count++;
						$('#error2').show();
					 }
					 if(count!=0)
					 {
						return ;
					 }
					 
					 var obj={
					 "parent_org_id":parent_org_id,			 
					 "filiale_name":filiale_name,
					 "parent_site_id":parent_site_id,
					 "country":select_country,
					 "province":province,
					 "city":city,
					 "address":address,
					 "cor_site_url":cor_site_url,
					 "site_url":site_url,
					 "manage_type":manage_type,
					 "is_ldap":is_ldap
					 }
					 $.post("filiale/filiale/valid_filiale",obj,function(data)
					 {
						
						if(data.code==0)
						{
							showDialog("filiale/filiale/show_create_manager_page");
						}
					 },'json')
					 
					
				}
				else{
					
					$('.first_list').hide();
					$('.second_filiale').html('');
					
					var obj={
					"customerCode":$('.first_list').attr("customerCode"),
					"id":''			
					}			
					var _this=$(this);
					$.post('filiale/filiale/create_filial_page',obj,function(data)
					{
						$('.second_filiale').append(data);
						$('.second_filiale').show();
						_this.addClass("show_create");
						_this.html("完成");
						/*$.post('',[],function(data)
						{
							
						})*/
					})
					/*var zNodes =[
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
						
					})*/
				}
				
			})
			//点击后退
			$('#back_step').click(function()
			{
				if($('#create_filiale').hasClass("show_create"))
				{
					
					$('.first_list').show();
					$('.second_filiale').html('');
					$('#create_filiale').removeClass("show_create");
					$('#create_filiale').html("创建分公司");
					
				}
			})
			
			$(".topMenuMain li").hover(function(){
				$(this).addClass("current");
				},
			function(){
				$(this).removeClass("current");
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
			$(document).click(function(e)
			{
				//alert(111)
				var t=$(e.target);
				if(!t.hasClass("select"))
				{
					$('.select').find("ul").removeClass("selected").css("display","none");	
				}
			})
			$(".second_filiale ul li").mouseenter(function()
			{
				$(this).addClass("hover");
			}).mouseleave(function()
			{
				$(this).removeClass("hover");
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
		})
		function show_changemanager(t)
		{
			$('#first_list').find("table .click_show").removeClass("click_show");
			$(t).addClass("click_show");
			showDialog("filiale/filiale/show_update_admin_page");
			$('#dialog').show();
		}
		function showMenu(t)
		{
			$(t).parent().find(".selectOptionBox").show()
		}		
		</script>
	</body>
</html>
