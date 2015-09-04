function delet_createLdap(t)
		{
			var ldap_ids=[];
			var _this=$(t);
			showDialog('ldap/showDeleteLdapPage');
			$('#dialog #delet_create_ldap').die("click");
			$('#dialog #delet_create_ldap').live("click",function()
			{
				_this.parent().parent().next().find("tbody label.checked").each(function()
				{
					ldap_ids.push($(this).parent().parent().attr("ldap_id"));
				});
				//alert(11)
				var path = "ldap/deleteLdap";
				var obj=
				{
					"ldap_ids":ldap_ids
				}
				$.post(path,obj,function(data)
				{
					if(data.code==0)
					{
						
						_this.parent().parent().next().find("tbody label.checked").parent().parent().remove();
						hideDialog();
						
					}
					else
					{
						alert(data.prmopt_text)
						return false;
					}
				},"json");
			})
		}
	function synControll(t,id){
			var _this = $(t);
			var html = _this.text();
	
			if(html == "关闭同步") {
				showDialog('ldap/showCloseLdapPage');
				$("#dialog #delet_create_ldap").die("click");
				$("#dialog #delet_create_ldap").live("click",function(){
					var ldap_id =id;
					var status  = 'close';
					var obj={
						ldap_id:ldap_id,
						status:status
					};
					$.post("ldap/changeLdapStatus",obj,function(data){
						//var json=$.parseJSON(data);
						if(data.code != 0){
							hideDialog();
							alert(data.msg);
						}else{
							
							_this.text('开启同步');
							hideDialog();
						}
					},'json');
				});	
			}else {
				var ldap_id =id;
				var status  = 'open';
				var obj={
						ldap_id:ldap_id,
						status:status
					};
				$.post("ldap/changeLdapStatus",obj,function(data){
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
		function ldap_detail_show(t,id)
		{
			//alert(1111);
			var path="ldap/showLdapInfoPage";
			var ldap_id=id;
			var obj=
				{
					"ldap_id":id
				}
			$.post(path,obj,function(data)
			{
				$("#contRigt_ldaplist").hide();
				$("#contRigt_ldaplist").after(data);
				$('#ldap_list').hide();
				$('#find_ldap_detail').show();
				$('#ldap_detail_head').show();
				
			});
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
			/*if(login){
				$('.poptip').hide();
			}else{
				$('.poptip').show();
			}
			//
			var obj=0;
			$('.poptip .btn_iKnow').click(function(){
				
				$('.poptip').animate(obj,300,function(){
					$('.poptip').hide();
					$('.poptip2').show();
				});
			});
			$('.poptip2 .btn_iKnow').click(function(){
				$('.poptip2').animate(obj,300,function(){
					$('.poptip2').hide();
				});
				login = 1;
			});*/
			
		});