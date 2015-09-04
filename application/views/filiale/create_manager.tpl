
<div class="pop">
	<title>创建管理员</title>
		<div class="popTitle">创建管理员<a  class="close"></a></div>
		<div class="Popbox">
    		<div class="PopMain">
  				<div class="shenpiTitle">设置系统管理员：</div>
  				<table class="buildNewcompany2">
					<tr>
						<td class="left" width="18%">姓氏：</td>
						<td><input type="text" class="textI w183" value="请输入姓氏" id="first_name"/></td>
						<td class="left">名字：</td>
						<td><input type="text" class="textI w183" value="请输入名称"  id="last_name"/></td>
					</tr>
					<tr>
						<td class="left">显示名称：</td>
						<td colspan="3">
							<input type="text" class="textI w183" id="display_name"/>
							<span class="hui">如果显示名称不是姓氏+名字的组合，请重新填写</span>
						</td>
					</tr>
					<tr>
						<td class="left">手机号：</td>
						<td colspan="3">
							<div class="select w69" id="countrycode">
								<a >国码</a>
								<ul class="w69">
								{foreach $country_code_arr as $country}
									<li>{$country}</li>
								{/foreach}
								</ul>
							</div>
							<input type="text" class="textI w183 marl10" id="telephone"/>
						</td>
					</tr>
					<tr>
						<td class="left">固定电话：</td>
						<td colspan="3">
							<div class="select w69" id="countrycode1">
								<a >国码</a>
								<ul class="w69">
									{foreach $country_code_arr as $country}
										<li>{$country}</li>
									{/foreach}
								</ul>
							</div>
							<input type="text" class="textI w46" value="区号" id="city_code"/>
							<input type="text" class="textI w95" value="电话号码" id="phone"/>
							<input type="text" class="textI w46" value="分机号" id="display_code"/>
						</td>
					</tr>
					
					<tr>
						<td class="left">电子邮箱：</td>
						<td colspan="3"><input type="text" class="textI w183" id="email"/></td>
					</tr>
					<tr>
						<td class="left">部门名称：</td>
						<td><input type="text" class="textI w183" value="请输入部门名称" id="department_name" /></td>
						<td class="left">职位名称：</td>
						<td><input type="text" class="textI w183" value="请输入职位名称"  id="position"/></td>
					</tr>
					
					<tr>
						<td class="left">指定管理员帐号：</td>
						<td colspan="3" id="login_name"><input type="text" class="textI w183" /> @ <input type="text" class="textI w183" /> .quanshi.com</td>
					</tr>
					<tr>
						<td class="left">&nbsp;  </td>
						<td colspan="3" class="hui">管理员初始化可使用这个使用者帐号登录，之后同步或导入公司员工信息后可改用公司定义的帐号</td>
					</tr>
    			</table>
  			</div>
		</div>
		<div class="popFooter"><a >取消</a><a id="create_manager">创建管理员</a></div>
	</div>
	<script type="text/javascript">
	$(function(){
		$('.close').click(function()
		{
			//alert(2222)
			hideDialog();
		})
		$(".popFooter a:eq(0)").click(function()
		{
			hideDialog();
		})
		$(".popFooter a:eq(1)").click(function()
		{
			//alert(11)
			
			//hideDialog();
		})
		
		$("#countrycode a").click(function(event){
			$("#countrycode ul").hide();
			$(this).next("ul").show();
			event.stopPropagation();
		});
		$("#countrycode1 a").click(function(event){
			$("#countrycode1 ul").hide();
			$(this).next("ul").show();
			event.stopPropagation();
		});
		$("#countrycode1 ul li").click(function(){
			$(this).parent("ul").prev("a").html($(this).text()).css("color","#000");
			$(this).parent("ul").hide();
			$(this).parent().hide();
			$(this).parent().find("li.selected").removeClass("selected");
			$(this).addClass("selected");
		});
		$("#countrycode ul li").click(function(){
			$(this).parent("ul").prev("a").html($(this).text()).css("color","#000");
			$(this).parent("ul").hide();
			$(this).parent().hide();
			$(this).parent().find("li.selected").removeClass("selected");
			$(this).addClass("selected");
			
		});
		$("ul li").mouseenter(function()
		{
			$(this).addClass("enter");
		}).mouseleave(function()
		{
			$(this).removeClass("enter");
		})
		
		$(".kuaijie input").click(function(){
			$(".kuaijie input").removeClass("checked");
			$(this).addClass("checked");
		});
		
		$(document).click(function(e)
		{
			//alert(111)
			var t=$(e.target);
			if(!t.hasClass("select"))
			{
				$('.select').find("ul").removeClass("open").css("display","none");	
			}
		})
		
		//点击创建管理员
		$('#dialog #create_manager').click(function()
		{
			
			$('.error').removeClass("error");
				$('#error1').hide();
				$('#error2').hide();
				var count=0;
				//子公司名称
				var parent_site_id=$('#parent_site_id .selectOptionBox li.selected').attr("user_id");
				var org_id=$('#parent_site_id .selectOptionBox li.selected').attr("org_id");
				 if(parent_site_id=="" || parent_site_id=="请选择公司")
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
					else
					{
						manage_type=2
					}
					
				}
				if(manage_type=="" && manage_type!=0)
				 {
					count++;
					
					$('#error1').show();
				 }
				 
				if($('.ldap input').hasClass("checked"))
				{
					is_ldap=1
				}
				if($('.bath input').hasClass("checked"))
				{
					is_ldap=0
				}
				if(is_ldap=="" && is_ldap!=0)
				 {
					count++;
					$('#error2').show();
				 }
				
				
			var first_name=$('#first_name').val();
			if(first_name==""|| first_name=="请输入姓氏")
			 {
				count++;
				$('#first_name').addClass("error");
				
			 }
			
			var last_name=$('#last_name').val();
			if(last_name=="" || last_name=="请输入名称")
			 {
				count++;
				
				$('#last_name').addClass("error");
			 }
			
			var display_name=$('#display_name').val();
			if(display_name=="")
			 {
				count++;
				
				$('#display_name').addClass("error");
			 }
			
			var countrycode=$('#countrycode a').text();
			if(countrycode=="" || !valitateAreaCode(countrycode))
			 {
				count++;
				$('#countrycode').addClass("error");
			 }
			
			var telephone=$('#telephone').val();
			if(telephone=="" || !valitateTelephonNum(telephone))
			 {
				count++;
				$('#telephone').addClass("error");
			 }
			
			var countrycode1=$('#countrycode1 a').text();
			if(countrycode1=="" || !valitateAreaCode(countrycode1))
			 {
				count++;
				$('#countrycode1').addClass("error");
			 }
			
			var phone=$('#phone').val();
			if(phone=="" || !valitateTelephonNum(phone))
			 {
				count++;
				$('#phone').addClass("error");
			 }
			
			var city_code=$('#city_code').val();
			if(city_code=="")
			 {
				count++;
				$('#city_code').addClass("error");
			 }
			
			var email=$('#email').val();
			if(email=="" || !valitateStaffAccount(email))
			 {
				count++;
				$('#email').addClass("error");
			 }
			
			var department_name=$('#department_name').val();
			if(department_name=="")
			 {
				count++;
				$('#department_name').addClass("error");
			 }
			
			var position=$('#position').val();
			if(position=="")
			 {
				count++;
				$('#position').addClass("error");
			 }
			
			var login_name=''+$('#login_name input:eq(0)').val()+'@'+$('#login_name input:eq(1)').val()+'.quanshi.com';
			if(login_name=="")
			 {
				count++;
				$('#login_name').addClass("error");
			 }
			 
			  if(count!=0)
				 {
					return ;
				 }
				
				 var obj={
				 "parent_org_id":org_id,
				 "customerCode":$('.first_list').attr("customerCode"),
				 "filiale_name":filiale_name,
				 "parent_site_id":parent_site_id,
				 "country":select_country,
				 "province":province,
				 "city":city,
				 "address":address,
				 "cor_site_url":cor_site_url,
				 "site_url":site_url,
				 "manage_type":manage_type,
				 "is_ldap":is_ldap,
				 "first_name":first_name,
				 "last_name":last_name,
				 "display_name":display_name,
				 "countrycode":countrycode,
				 "telephone":telephone,
				 "phone":phone,
				 "city_code":city_code,
				 "email":email,
				 "department_name":department_name,
				 "position":position,
				 "login_name":login_name
				 }
				  $.post("filiale/filiale/create_total_info",obj,function(data)
				 {
					
					if(data.code==0)
					{
						hideDialog();
					}
					else
				   {
				   		alert(data.prompt_text);
				   }
				 },'json')
			
		})
		var tips='';	
		$('#first_name,#last_name,#city_code,#phone,#display_code,#department_name,#position').focus(function()
		{
			tips=$(this).val();
			$(this).val('');
		}).blur(function()
		{
			if($(this).val()=="")
			{
				$(this).val(tips);
			}
		})
		$('#first_name,#last_name').blur(function()
		{
			var pre=$('#first_name').val()
			var last=$('#last_name').val();
			var text='';
			if(pre!="请输入姓氏"&& last!="请输入名称")
			{
				text=''+pre+''+last+'';
				$('#display_name').val(text).css("color","#000");
			}
		})
		$('input').bind('input propertychange', function() {
			$(this).css("color","#000");
		});
		
			
	})
	</script>

