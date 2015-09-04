// JavaScript Document
//企业生态页面2
function nextStep2() {
    var set_state = '';
    $('#creater_two label.checkbox').each(function() {
        if ($(this).hasClass("checked") && $(this).attr("target") != "allow_callPhone") {
            set_state = set_state + '"' + $(this).attr("target") + '":"1",';
        } else if (!$(this).hasClass("checked") && $(this).attr("target") != "allow_callPhone") {
            set_state = set_state + '"' + $(this).attr('target') + '":"0",';
        } else if ($(this).hasClass("checked") && $(this).attr("target") == "allow_callPhone") {
            set_state = set_state + '"' + $(this).attr("target") + '":"1"';
        } else if (!$(this).hasClass("checked") && $(this).attr("target") == "allow_callPhone") {
            set_state = set_state + '"' + $(this).attr("target") + '":"0"';
        }
    });
	var staff_tag_post = set_state;
    var lastIndex = staff_tag_post.lastIndexOf(',');
    if (lastIndex > -1) {
        staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
    }
    set_state = '{' + staff_tag_post + '}';
    obj2_json = set_state;
    //set_state=DelLastComma(set_state);
    /* $('.ldapSetBox').eq(1).hide();
    $('.ldapSetBox').eq(2).show();*/
    // alert(set_state);
    //alert(company_ecol_id);
    var path = 'ecology/valid_eco_2';;
    obj2 = {
        "power_json": obj2_json //选中的框的内容
        //"org_id":company_ecol_id
    }; 
	$("#checking").show();
	$.ajax({
            url: path,
            timeout: 6000,
            type: "POST",
            data: obj2,
            success: function(data) {
                var json = $.parseJSON(data);
                if (json.code == 0) {
                        //loadCont('<?php // echo site_url('ecologycompany/setEcologyCompany')?>');
                        $('#creater_two').hide();
						$('#creater_three').show();
						var back_next=$('#prev_next');
						$('#prev_next').remove();
						$('#creater_three').after(back_next);
						//$('#prev_next').find("a:eq(1)").show();
						$('#head_style a').removeClass("selected");
						$('#head_style a').removeClass("current");
						$('#head_style').find('a:eq(2)').addClass("selected");
						$('#head_style').find('a:eq(2)').addClass("current");
						$('#head_style .innerBar').css("width","75%")
						$("#checking").hide();
                } else {
					$("#checking").hide();
					return ;
                }
               
            },
            error: function() { //$('.ldapSetBox1 .error1').show();
				alert("请求服务器失败")
                $("#checking").hide();
            }
        });

}