var org_info;
//ldap2
function click_ldap3(t,event) {
	var x=event.target;
	//alert($(x).attr("class"))
	if($(x).hasClass("label_check"))
	{
		if ($(t).hasClass("checked")) {
        $(t).removeClass("checked");
		//return false;
		} else {
			$(t).addClass("checked");
			//return false;
		}
	}
}
function click_ldap3_input(t,event)
{
	var x=event.target;
	//alert($(x).attr("class"))
	if(!$(x).hasClass("label_check"))
	{
		//alert(2)
		if ($(t).parent().hasClass("checked")) {
        $(t).parent().removeClass("checked");
		//return false;
		} else {
			$(t).parent().addClass("checked");
			//return false;
		}
	}
}
function nextStep2() {
    //var Re_context=$(".treeBox").getTSNs();//获取到选中的框的数量
    var zTree = $.fn.zTree.getZTreeObj("ldap_tree");
    var Re_context = zTree.getCheckedNodes(true);
    //console.log(ldap_tree);
	var cont=[];
    //alert(Re_context.length)
    if (Re_context.length == 0) {
		$('.error2').show();
        $('.error2').text("您必须选择一项");
		return false;
    } else {
		$('.error2').hide();
        //alert(Re_context.getCheckStatus().half) var cont = [];
        for (var i = 0; i < Re_context.length; i++) {
            //alert(json.half)
            if (Re_context[i].getCheckStatus().half == false) {
                cont.push(Re_context[i]);
            }
        }
        Re_context = cont;
        //alert(Re_context.length);
        var Re_data = '';
        for (var i = 0; i < Re_context.length; i++) {
            ///Re_data=Re_data+'{id:'+Re_con[i]+',pid:'+Re_con[i+1]+',name:'+Re_con[i+2]+',},';
            Re_data = Re_data + Re_context[i].id + ';';
        }
        //Re_data=DelLastComma(Re_data);
        org_info = Re_data;
        var path = "ldap/getLdapClass";
        var obj = {
            server_info: server_info
        };
        $("#checking").addClass('_show').show();
        $.ajax({
            url: path,
            timeout: 60000,
            type: "POST",
            data: obj,
            success: function(data) {
                var json = $.parseJSON(data);
                if (json.code == 0) {
                   	$('.error2').hide();
                    var html = '';
                    var arry = json.other_msg;
                    for (var i = 0; i < arry.length; i++) {
                        html = html + "<label  class='checkbox label_check' onclick='' onmouseup='click_ldap3(this,event)'><input type='checkbox'/>" + arry[i] + "</label>" + "<br />";
                    }
                    //alert(html);
                    $('.ldapSetBox3 .ldapSetCont dd:last').append(html);

                    //loadCont('<?php // echo site_url('ecologycompany/setEcologyCompany')?>');
                    $('.ldapSetBox2').hide();
                    $('dd.error2').text("");
                    $('.ldapSetBox3').show();
					$('#head_label a').removeClass("selected");
					$('#head_label a').removeClass("current");
					$('#head_label').find('a:eq(2)').addClass("selected");
					$('#head_label').find('a:eq(2)').addClass("current");
					$('#head_label .innerBar').css("width","60%");
					var back_next=$('#back_next');
					$('#back_next').remove();
					$('.ldapSetBox3').after(back_next);
					$("#checking").hide();

                } else {
				
					alert(json.prmopt_text)
		  	
                    /* $('#'+json.error_id).parent("div").addClass("error");/*/
					$("#checking").hide();
                    return false;
                };
                //$(".checking2").hide();
            },
            error: function() {
                $('.ldapSetBox2 .error2').show();
				$('.ldapSetBox2 .error2').text("操作超时，请稍后再试");
              	$("#checking").hide();
            }
        });
    }
}
