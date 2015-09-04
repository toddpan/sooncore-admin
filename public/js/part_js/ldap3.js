//ldap3
var classes;
function Init_staff_tag(arry, a) {
    var value = [];
    for (var i = 0; i < arry.length; i++) {
        value[i] = arry[i];
    }
    newtag = Must_chose_tag(value, a);
    return newtag;
    //$('.infoTable').append(newtag);
}
function Must_chose_tag(value, a) {
    var context = optionList(value, a);
    return context;
}
function optionList(val, a) {
    var dd_con = "";
    if (a == 1) {
        var context = '<dd class="option selected" target="0" onclick="selected_value(this,event)">请选择对应的LDAP信息</dd>';
    } else  if(a==2){
        var context = '<dd class="option selected" target="0" onclick="selected_value(this,event)">选择标签</dd>';
    }
	else if(a==3)
	{
		 var context = '<dd class="option selected" target="0" onclick="selected_value(this,event)">请选择</dd>';
	}
    for (var i = 0; i < val.length; i++) {
        dd_con = dd_con + '<dd class="option" target=" Last Name " onclick="selected_value(this,event)">' + val[i] + '</dd>'
    }
    context = context + dd_con;
    return context;
}
function selected_value(t,event) {
    if (!$(t).hasClass("selected")) {
        $(t).parent().find("dd.selected").removeClass("selected");
        $(t).addClass("selected");
        $(t).parent().parent().prev().text($(t).text()).addClass("selected");
    }
	$(t).parent().parent().hide();
	 event.cancelBubble = true;
}
function data3() {
    var count = 0;
    var Re_data = '';
    var Re_id = [];
    var Re_context = [];
    var i = 0;
    $('.ldapSetBox3 label').each(function() {
        if ($(this).attr('class') == "checkbox checked" && $(this).find('input').attr('checked') == "checked") {
            //Re_id[i]=$(this).text();
            Re_context[i] = $(this).text();
            i = i + 1;
            count = count + 1;
        }
    }) 
	if (count == 0) {
        return 0;
    } else {
        return Re_context;
    }
}
function nextStep3() {
    var count = 0;
    var Re_data = '';
    var Re_id = [];
    var Re_context = [];
    var i = 0;

    $('.ldapSetBox3 label').each(function() {
        //if ($(this).attr('class')=="checkbox checked" && $(this).find('input').attr('checked')=="checked" )
        if ($(this).hasClass("checked")) {
            Re_id[i] = $(this).text();
            Re_context[i] = $(this).text();
            i = i + 1;
            count = count + 1;
        }
    })
    //alert(111111)
    if (count == 0) {
		$('.error3').show();
        $('.error3').text("您必须选择一项");
        //alert("您必须选择一项") 
		return false;
    } else {
        //选中的标签类
		$('.error3').hide();
        classes = Re_context;
        var path = 'ldap/getLdapAttribute';
        var obj = {
            classes: Re_context,
            server_info: server_info
        };
        $("#checking").show();
        //"select_context":count,
        $.ajax({
            url: path,
            timeout: 8000,
            type: "POST",
            data: obj,
            success: function(data) {
                var json = $.parseJSON(data);
                if (json.code == 0) {

                    //loadCont('<?php // echo site_url('ecologycompany/setEcologyCompany')?>');
                    $('.ldapSetBox3').hide();
					$('.error2').hide();
                    var arry = json.other_msg;
                    // for(var i=0;i<arry.length;i)
                    var new_tag = Init_staff_tag(arry, 1); //初始化必选员工标签
                    var new_tag1 = Init_staff_tag(arry, 2); //初始化必选员工标
					var new_tag2=Init_staff_tag(arry,3);//邮箱的员工标签
                    $('.ldapSetBox4 .ldapSetCont table.infoTable .optionList dd').remove();
					$('.ldapSetBox4 .optionList').css("height","230px");
					$('.ldapSetBox4 .selectBox').find("span").text("请选择对应的LDAP信息");
                    $('.ldapSetBox4 .ldapSetCont table.infoTable .optionList').append(new_tag);
                    $('.ldapSetBox5 .ldapSetCont table.infoTable .ldap5_select dd').remove();
                    $('.ldapSetBox5 .ldapSetCont table.infoTable .ldap5_select').append(new_tag1);
					$('.ldapSetBox5 .ldapSetCont table.infoTable .ldap5_select2 dd').remove();
                    $('.ldapSetBox5 .ldapSetCont table.infoTable .ldap5_select2').append(new_tag2);
                    $('.ldapSetBox4').show();
					$('#head_label a').removeClass("selected");
					$('#head_label a').removeClass("current");
					$('#head_label').find('a:eq(3)').addClass("selected");
					$('#head_label').find('a:eq(3)').addClass("current");
					$('#head_label .innerBar').css("width","80%");
					var back_next=$('#back_next');
					$('#back_next').remove();
					$('.ldapSetBox4').after(back_next);
					$("#checking").hide();

                } else {
					
			alert(json.prmopt_text)
		  
                    /*$('#'+json.error_id).parent("div").addClass("error");*/
					$("#checking").hide();
                    return false;
                }
               
            },
            error: function() {
                $('.ldapSetBox3 .error3').show();
				$('.ldapSetBox3 .error3').text("操作超时，请稍后再试");
                $("#checking").hide();
            }
        });
    }
}
$(function()
{		
	$('.ldapSetBox3 label.checkbox input').die("mouseup")
	$('.ldapSetBox3 label.checkbox input').live('mouseup',function(event)
   {
	    if ($(this).parent().hasClass("checked")) {
			//alert(2112)
        $(this).parent().removeClass("checked");
		
		} else {
			$(this).parent().addClass("checked");
			
			//alert(222)
		}
		 event.cancelBubble = true;
   })/*
   function()
   {
	   alert(2)
	   if ($(this).hasClass("checked")) {
			//alert(2112)
        $(this).removeClass("checked");
		
		} else {
			$(this).addClass("checked");
			
			//alert(222)
		}
   })*/
})
