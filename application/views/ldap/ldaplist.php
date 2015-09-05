<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>云企管理中心</title>
		<base href="/ucadmin/"/>
		<base target="_blank" />
	</head>
	<body>
		<div class="contHead">
			<span class="title01 rightLine">组织管理</span>
			<span class="title03">LDAP列表</span>
		</div>
		<div class="contMiddle">
		    <div class="contRight" style="margin-left: 0">
		         <div class="tabToolBar">
						<a class="btnBlue yes fr" onclick="loadPage(ldap/showLdapPage)">
							<span class="text">新建</span>
							<b class="bgR"></b>
						</a>
						<a class="back fl"  onclick="loadPage('organize/ldaporg','group')" title="返回">&nbsp;</a>
						<div class="tabToolBox fl" style="display:none;">
							<a class="btnGray btn btnMoveManage"  onclick="delet_createLdap(this);">
								<span class="text">删除</span>
								<b class="bgR"></b>
							</a>
						</div>
						<span class="green-style fl">每天的凌晨将会为您自动同步</span>
            	</div>
            	<table class="table">
                	<thead>
                    	<tr>
							<th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
							<th width="14%" style="text-align:left; text-indent: 24px;">名称</th>
							<th>创建时间</th>
							<th>最后一次同步时间</th>
							<th width="16%">操作</th>  
                    	</tr>
                	</thead>
                	<tbody>
						{foreach $ldap_list as $ldap}	
						<tr>
							<td>
								<label class="checkbox"><input type="checkbox" /></label>
							</td>
							<td align="center" class="tl">
								<a class="userName ellipsis" onclick="loadContldap/showLdapInfoPage?ldap_id="+{$ldap["ldap_id"]}+"">{$ldap['ldap_name']}
								</a>
							</td>
							<td align="center">{$ldap['create_time']}</td>
							<td align="center">
								<span class="ellipsis">{$ldap['last_sync_date']}</span>
							</td>
							<td>
								<a  onclick="synControll(this,{$ldap["ldap_id"]})"><{$ldap['is_auto_sync']}</a></td>
						</tr>
                   		{/foreach}
                	</tbody>
            	</table>
            	<div class="page" style="display: none">
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
		<script type="text/javascript">
		function delet_createLdap(t)
		{
			var ldap_ids=[];
			var _this=$(t);
			showDialog('ldap/showDeleteLdapPage');
			$('#dialog .btn_confirm').live("click",function()
			{
				_this.parent().parent().next().find("tbody label.checked").each(function()
				{
					ldap_ids.push($(this).parent().prev().val());
				})
				//alert(11)
				var path = "ldap/deleteLdap";
				$.post(path,{ldap_ids:ldap_ids},function(data)
				{
					if(data.code==0)
					{
						
						_this.parent().parent().next().find("tbody label.checked").parent().parent().remove();
						hideDialog();
						
					}
				},"json")
			})
		}
		function synControll(t,id){
			var _this = $(t);
			var html = _this.text();
	
			if(html == "关闭同步") {
				showDialog('ldap/showCloseLdapPage');
				$("#dialog .btn_confirm").die("click");
				$("#dialog .btn_confirm").live("click",function(){
					var ldap_id =id;
					var status  = 'close';
					$.post("ldap/changeLdapStatus",{ldap_id:ldap_id,status:status},function(data){
						//var json=$.parseJSON(data);
						if(data.code != 0){
							hideDialog();
							alert(data.msg);
						}else{
							
							_this.text('开启同步');
							hideDialog();
						}
					},'json');
				})	
			}else {
				var ldap_id =id;
				var status  = 'open';
				$.post("ldap/changeLdapStatus",{ldap_id:ldap_id,status:status},function(data){
				//alert(data)
				//var json=$.parseJSON(data);
					if(data.code != 0){
						alert(data.msg);
					}else{
						_this.text('关闭同步');
					}
				},'json');
			}
		}
		$(function(){
					
			$(".table thead input[type='checkbox']").click(function(){
				if($(this).is(":checked")){
					$(this).parent().addClass('checked');
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
					$(this).parent().removeClass('checked');
					$(".table tbody input[type='checkbox']").removeAttr("checked");
					$(".table tbody .checkbox").removeClass("checked");
					$(".tabToolBar .tabToolBox").hide();
				}
			})
			
			$(".table tbody input[type='checkbox']").live("click",function(){
				var len = $(".table tbody .checkbox").length;
				if($(this).is(":checked")){
					$(this).parent().addClass("checked");
					$(".tabToolBar .tabToolBox").show();
					var checkLen = $(".table tbody .checked").length;
					
					if(len == checkLen) {
						$(".table thead .checkbox").addClass("checked");
						$(".table thead input[type='checkbox']").attr("checked","checked");
					}
				}
				else {
					$(this).parent().removeClass("checked");
					$(".table thead .checkbox").removeClass("checked");
					$(".table thead input[type='checkbox']").removeAttr("checked");
					var checkLen = $(".table tbody .checked").length;
					
					if(checkLen == 0) {
						$(".tabToolBar .tabToolBox").hide();
					}
				}
			})
	
			
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
			
		});
		</script>
	</body>
</html>