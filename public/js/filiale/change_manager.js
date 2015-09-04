// JavaScript Document
//点击完成变更
function save_change_manager()
{
	$('.error').removeClass('error');
	var create_type='';//创建类型
	var first_name="";
	var last_name="";
	var display_name="";
	var country_code="";
	var telephone="";
	var city_code="";
	var phone="";
	var email="";
	var department_name="";
	var position="";
	var login_name="";
	var loginName='';
	var manager_info='';
	var count=0;
	if($('#first input').hasClass("checked"))
	{
		create_type=1;		
		loginName=$('#first').parent().next().find("input").val();
		if(loginName=="" || !/@/.test(loginName))
		{
			$('#first').parent().next().find("div").addClass("error");
			count++;
		}
		else
		{
			manager_info='{"login_name":"'+loginName+'"}';
		}
	}
	if($('#second input').hasClass("checked"))
	{
		create_type=2;
		first_name=$("#first_name").val();
		last_name=$("#last_name").val();
		display_name=$("#display_name").val();
		telephone=$("#telephone").val();
		country_code=$('#country_code li.selected').text();
		city_code=$("#city_code").val();
		phone=$("#phone").val();
		email=$("#email").val();
		department_name=$("#department_name").val();
		position=$("#position").val();
		login_name=''+$("#login_name").val()+'@'+$("#login_name").next().val()+'.quanshi.com';
		if(first_name=="" || first_name=="请输入姓氏")
		{
			$("#first_name").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"first_name":"'+first_name+'"},'
		}
		if(last_name=="" || last_name=="请输入名称")
		{
			$("#last_name").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"last_name":"'+last_name+'"},';
		}
		if(display_name=="")
		{
			$("#display_name").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"display_name":"'+display_name+'"},';
		}
		if(telephone=="" || !valitateTelephonNum(telephone))
		{
			$("#telephone").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"telephone":"'+telephone+'"},';
		}
		if(country_code=="" || !valitateAreaCode(country_code))
		{
			$("#country_code").parent().addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"country_code":"'+country_code+'"},';
		}
		if(city_code=="" || city_code=="区号" || !valitateAreaCode(city_code))
		{
			$("#city_code").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"city_code":"'+city_code+'"},';
		}
		if(phone=="" || phone=="电话号码" || !valitateTelephonNum(phone))
		{
			$("#phone").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"phone":"'+phone+'"},';
		}
		if(email=="" || !valitateStaffAccount(email))
		{
			$("#email").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"email":"'+email+'"},';
		}
		if(department_name=="" || department_name=="请输入部门名称")
		{
			$("#department_name").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"department_name":"'+department_name+'"},';
		}
		if(position=="" || position=="请输入职位名称")
		{
			$("#position").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"position":"'+position+'"},';
		}
		if($("#login_name").next().val()=="")
		{
			
			$("#login_name").next().addClass("error");
			count++;
		}
		if($("#login_name").val()=="")
		{
			
			$("#login_name").addClass("error");
			count++;
		}
		else
		{
			manager_info=manager_info+'{"login_name":"'+login_name+'"},';
		}
		manager_info=manager_info+'{"customerCode":"'+$('.first_list').attr("customerCode")+'"}';
		
	}
	if(create_type=='')
	{
		$('#error_tip').show();
		count++;
	}
	if(count>0)
	{
		return;
	}
	else
	{	
		var org_id=$('.first_list table').find(".click_show").parent().parent().find("#company_name").attr("user_id");	
		var obj=
		{
			"org_id":org_id,
			"create_type":create_type,
			"manager_info":manager_info
		};
		$.post('filiale/filiale/update_admin',obj,function(data)
		{
		 	if(data.code==0)
			{
				hideDialog();
			}
			else
			{
				$('#'+data.error_id+'').addClass("error");
			}
		},'json')
	}
	
}
$(function(){
$('#first input').click(function()
{
	
	$(this).addClass("checked");
	$('#second input').removeClass("checked");
	$(this).parent().parent().next().removeClass("disabled");
	$('.buildNewcompany2 tbody').addClass('disabled');
	var _mask=$('.buildNewcompany2');
	var mask="<div class='mask' id='dialog_mask' style='display:block'></div>";
	$('.buildNewcompany2').after(mask);
	var w = _mask.width();
	var h = _mask.height();
	 $('#dialog_mask').css({
				'margin-top': 185+'px',
				'margin-left': 25+'px',
				'width':w,
				'height':h,
				'position':'absolute'
			});
	
})
$('#second input').click(function()
{
	
	$(this).addClass("checked");
	$('#first input').removeClass("checked");
	$('#first').parent().next().addClass("disabled");
	$('.buildNewcompany2 tbody').removeClass('disabled');
	 $('#dialog_mask').remove();
})
$('#loginName').click(function()
{
	if($(this).parent().parent().hasClass("disabled"))
	{
		$(this).blur();
	}
})
	$('.close').click(function()
	{
		//alert(2222)
		hideDialog();
	})
	$(".kuaijie input").click(function(){
		$(".kuaijie input").removeClass("checked");
		$(this).addClass("checked");
	});
	$(document).click(function(){
		$(".select ul").hide();
	})
	$(".setManTable .select ul li").click(function(){
	  $(this).parent().find("li.selected").removeClass("selected");
		$(this).parent().prev().html($(this).text());
		$(this).parent().prev().css("color","#000");
		$(this).parent().hide();
		$(this).addClass("selected");
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
	$('#change_select .select').toggle(function()
	{
		//alert(222)
		if($(this).find("ul").hasClass("open"))
		{
			//alert(1)
			$(this).find("ul").removeClass("open").css("display","none");
		}
		else
		{
			//alert(2)
			$(this).find("ul").addClass("open").css("display","block");
		}
	},function()
	{
		if($(this).find("ul").hasClass("open"))
		{
			//alert(3)
			$(this).find("ul").removeClass("open").css("display","none");
		}
		else
		{
			//alert(4)
			$(this).find("ul").addClass("open").css("display","block");
		}
	})
	$(".setManTable ul li").mouseenter(function()
	{
		$(this).addClass("hover");
	}).mouseleave(function()
	{
		$(this).removeClass("hover");
	})
	var tips='';	
	$('#first_name,#last_name,#city_code,#country_code,#extersion,#phone,#display_code,#department_name,#position').focus(function()
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
	$('input').bind('input propertychange', function() {
			$(this).css("color","#000");
		});	
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
})