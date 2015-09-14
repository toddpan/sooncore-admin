function valitateUserName(value) {
    //var ASCIIStrValid =new RegExp("[!#$&()*+,-./%.:;<=>?@[]^_{|}~\"' + '\\' + "\'"]");
    var strlen = 0;
    var tmpchr, i;
    strlen = value.length;
    if (value == '' || strlen < 4 || strlen > 40) return false;
    var half = /^[0-9a-zA-Z\!\#\$\&\(\)\*\+\,\-\.\/\%\.\:\;\<\=\>\?\@\[\]\^\_\{\|\}\~\"\'+'\\'+"\'"]*$/;
    if (half.test(value)) {
        return true;
    } else {
        return false;
    }
    var user = /^[A-Za-z0-9]+$/;
    if (user.test(value)) return true;
    else return false;
};

function valitateUserPwd(value, chose) {
    if (chose == 1) {
        if (value.length >= 6 && value.length <= 30) {
            return true;
        } else return false;
    }
    if (chose == 2) {
        /* var patt=/^(\d|[a-zA-Z]){1,}+$/;
	  if(patt.test(value))
	  {
	  return true;
	  }
	  else
	  {
	  return false;
	  }*/
        var patt = new RegExp("[a-zA-Z]");
        var mat = new RegExp("[0-9]");
        if (value.length >= 6 && value.length < 30) {

            if (valitateUserName(value) == true) {
                if (value.match(patt) != null) {
                    if (value.match(mat) != null) return true;
                    else return false;
                } else return false;
            } else {
                return false;
            }
        } else return false;
    }
    if (chose == 3) {
        /*  var patt=/^(\d|[a-zA-Z]|^[\d\sa-zA-Z]){1,}+$/;
	   if(patt.test(value))
	  {
	  return true;
	  }
	  else
	  {
	  return false;
	  }
	  */
        var mat = new RegExp("[0-9]");
        var pat = new RegExp("[a-Z]");
        var spe = new RegExp("[!@#$%~^&*()-=_+{}[]?]");
        if (valitateUserPwd(value, 1) == true) {
            for (k = 0; k < value.length; k++) {
                val = value.charAt(k);
                if ((val >= '0' && val <= '9') || (val >= 'A' && val <= 'Z') || (val >= 'a' && val <= 'z') || val == "!" || val == "@" || val == "\\" || val == "#" || val == "%" || val == "~" || val == "^" || val == "*" || val == "&" || val == "(" || val == ")" || val == "-" || val == "=" || val == "_" || val == "+" || val == "{" || val == "}" || val == "[" || val == "]" || val == "?") {
                    continue;
                } else return false;
            }
        } else return false;
        if (value.match(pat) != null) {
            if (value.match(mat) != null) {
                if (value.match(spe) != null) {
                    return true;
                } else return false;
            } else return false;
        } else return false;

    }
}
function valitateLoginCode(value) {
    var login = /^[A-Za-z0-9]+$/;
    if (value.length == 4) {
        if (login.test(value)) {
            return true;
        } else return false;
    } else return false;
}
function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=")
		if (c_start != -1)
		{
            c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end == -1) 
			c_end = document.cookie.length
            return unescape(document.cookie.substring(c_start, c_end))
        }
    }
    return ""
}
function setCookie(c_name, value, expiredays) {
    var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays) 
	document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "": ";expires=" + exdate.toGMTString())
}

function valitatesendMeg(value) {
    if (value.length > 0) return true;
    else return false;
}
function valitateNoZero(value) {
    var login = /^[1-9]+$/;
    if (value.charAt(0) == 0 && login.test(value.charAt(1))) return false;
    else return true;
}
function valitateLdpAddress(value) {

    var login = /^[A-Za-z]+$/;
    var login_two = /^[0-9]+$/;
    if (login.test(value.charAt(0))) return true;
    else {
        var ldp = value.split(".");
        if (ldp.length != 4) return false;
        else {
            if (valitateNoZero(ldp[0]) && valitateNoZero(ldp[1]) && valitateNoZero(ldp[2]) && valitateNoZero(ldp[3])) {
                if (login_two.test(ldp[0]) && login_two.test(ldp[1]) && login_two.test(ldp[2]) && login_two.test(ldp[3])) {
                    if (ldp[0] == 0 && ldp[1] == 0 && ldp[2] == 0 && ldp[3] == 0) {
                        return false;
                    } else {
                        if (255 >= ldp[0] >= 0 && 255 >= ldp[1] >= 0 && 255 >= ldp[2] >= 0 && 255 >= ldp[3] >= 0) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
}
function valitateLdpPort(value) {
    var login = /^[0-9]+$/;
    if (!login.test(value)) {
        return false;
    }
    if (!valitateNoZero(value)) return false;
    if ((value - 0) < 1 || (value - 0) > 65535) {
        return false;
    } else {
        return true;
    }

}
function valitateLdpUserName(value) {
    if (value == "") {
        return false
    } else {
        return true;
    }
}
function valitateLdpName(value) {
    var rag = new RegExp("[\u4E00-\u9fa5]+", "g");
    var login = /^[A-Za-z0-9]+$/;
    if (value == "") {
        return false
    } else {
        if (0 < value.length < 100) {
            if (rag.test(value) || login.test(value)) return true;
            else return false;
        } else {
            return false;
        }
    }
}
function valitateStaffName(value) {
    var spe = new RegExp("[!@#$%~^&*()-=_+{}[]?]");
    if (value.length > 50 || value.length == 0) return false;
    else if (spe.test(value)) {
        return false
    } else return true;
}
function valitateStaffAccount(value) {
    var half = /^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/;
    if (half.test(value)) return true
    else return false;
}
function valitateCompanyName(value) {
    if (0 < value.length < 100 && value != "") {
        for (var i = 0; i < value.length; i++) {
            if (value.charAt(i) == '') {
                return false
            }
        }
        return true;
    } else {
        return false;
    }
}
function valitateCompanyEnglish(value) {
    var rag = new RegExp("[\u4E00-\u9fa5]+", "g");
    if (rag.test(value)) {
        return false;
    } else {
        return valitateCompanyName(value);
    }
}
function valitateAreaCode(value) {
    var login = /^[0-9]+$/;
    var count = 0;
    if (0 < value.length <= 20 && login.test(value)) {
        if (count == value.length) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
function valitateTelephonNum(value) {
    var login = /^[0-9]+$/;
    var count = 0;
    if (0 < value.length <= 20 && login.test(value)) {
        if (value.charAt(0) == 0) {
            return false;
        }
        for (var i = 0; i < value.length; i++) {
            if (value.charAt(i) == 0) {
                count++;
            }
        }
        if (count == value.length) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
function valitateCompanyIntroduce(value) {
    if (value == '') {

        return false;
    }
    if (value.length > 1000) {

        return false
    } else {
        /*for(var i=0;i<value.length;i++)
	       {
		    if(value.charAt(i)=='')
			{
				return false
			}
	      }*/
        return true;
    }
}
function DelLastComma(value) //字符串转换成JSON串
{
    var staff_tag_post = value;
    var lastIndex = staff_tag_post.lastIndexOf(',');
    if (lastIndex > -1) {
        staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
    }
    staff_tag_post = '[' + staff_tag_post + ']';
    return staff_tag_post;
}
function EnterInput(value) {
    $("body").keydown(function(e) {
        if (e.keyCode == 13) {
            value();
        }
    })
}
function valitateRepeatPwd(value1, value2) {
    var leng = value1.length;
    if (leng === value2.length) {
        var i = 0;
        while (i + 1 <= leng) {
            if (value1.charAt(i) === value2.charAt(i)) {
                i = i + 1;
            } else {
                return false;
            }
        }
        return true;
    } else {
        return false;
    }
}
//
function toolBarSet() {
    //alert(3443)
    var checked = $('#part01 .table:first tbody .checkbox').filter(function() {
        return $(this).hasClass('checked');
    });
    if (checked.length) {
        $('#part01 .tabToolBox ').show();
        $('#part01 .btnBeManage').hide();
    } else {
        $('#part01 .tabToolBox').hide();
    }
}


//设置管理者
function set_mange(staff_id, path_mag) {
    //var checked = $('#part01 .table:first .checkbox').filter(function(){return $(this).hasClass('checked');});
    //alert(staff_id.user_id)									//alert(checked.length)
    //if(checked.length==1){
    //根据组织id，获得当前组织的管理者id,如果当前组织没有管理者，返回0
    $.post(path_mag, staff_id,
    function(data) {
        //alert(data);
        var json = $.parseJSON(data)

        if (json.code == 0) {
            if (json.other_msg.manager_user_id != 0) {
                //有管理者，且管理者就是选中的员工，则显示取消管理按钮
                //alert(545)
                // $('.btnMoveManage').show();
                // $('.btnBeManage').show();
                // alert(json.other_msg.manager_user_id)
                // alert(staff_id.user_id)
                if (json.other_msg.manager_user_id == staff_id.user_id) {
                    //alert(23)
                    $('#part01 .btnMoveManage').show();
                    $('#part01 .btnBeManage').hide();

                } else {
                    //alert(11)
                    $('#part01 .btnMoveManage').hide();
                    $('#part01 .btnBeManage').hide();
                }
                //else//有管理者，且管理者不是选中的员工，则不做处理  
            } else //为0，则当前组织没有管理者，需要设置当前选中的员工为管理者，显示设置管理者按钮
            {
                //alert(34) 
                $('#part01 .btnBeManage').show();
                $('#part01 .btnMoveManage').hide();

            }
            // alert(147);
        } else
				{
					alert(json.prompt_text)	
				}
    })

    //}else{
    //alert(3);
    //$('.btnMoveManage').hide();
    // $('.btnBeManage').hide();
    //}
}
//点击组织，加载员工 
function load_staff(obj, path_user) {
    
    var loadStr = '<div class="org_loading"><span class="msg">正在加载请稍候。</span></div>';
    $('#part01 .tabToolBar').after(loadStr);
    
    //var org_ID = obj.org_id;
    //alert(org_ID);
    // var path="<?php echo site_url('organize/get_users_list');?>";
    $.ajax({
        url: path_user,
        //async: false,
        type: "POST",
        data: obj,
        success: function(data) {
            //alert(data)
            //var count = data.split('tr');
            $('#part01 .org_loading').remove();
            $('#part01 #part1').remove();
            //$('#part01 .link_limitSet').show();
            //$('#part01 .tabToolBar').show();
            $('#part01 .table_org').remove();
            $('#part01 .tabToolBar').eq(0).after(data);
            //$('#part01 .tabToolBox').hide();
            $('.group').removeClass("false");
        }
    });
}
function load_staff_center(obj, path_user) {
    var org_ID = obj.org_id;
    //alert(org_ID)
    // var path="<?php echo site_url('organize/get_users_list');?>";
    $.post(path_user, obj,function(data) {
        //alert(data)
        var count = data.split('tr');
        //alert(count.length)
        $('#part02 #part1').remove();
        //$('.link_limitSet').show();
        $('#part02 .tabToolBar').show();
        $('#part02 .table').next().hide();
        $('#part02 .table').remove();
        $('#part02 .tabToolBar').eq(0).after(data);
        $('#part02 .tabToolBox').hide();
		//alert(1)
    });
	
    //选中员工，显示对员工的操作
}
function set_ecology_power(value) {
    if (value.UC_passDoc.value == '2') {
        $('label.open_web_meet').addClass('checked');
        //$('.groupLimit label.open_web_meet').find('input').attr('checked','checked');
    } else if (value.UC_passDoc.value == '1') {
        $('label.open_web_meet').removeClass('checked');
        //$('.groupLimit label.open_web_meet').find('input').attr('checked','');
    } else {
        if (value.UC_passDoc.default_value == '1') {
            $('label.open_web_meet').removeClass('checked');
            //$('.groupLimit label.open_web_meet').find('input').attr('checked','');
        } else {
            $('label.open_web_meet').addClass('checked');
            $('label.open_web_meet').find('input').attr('checked', 'checked');
        }
    }
    /* if(value.UC_passDoc.value=='2')
    {
        $('label.open_tel_meet').addClass('checked');
        //$('.groupLimit label.open_web_meet').find('input').attr('checked','checked');
    }else if(value.UC_passDoc.value=='1')
    {
        $('label.open_tel_meet').removeClass('checked');
        //$('.groupLimit label.open_web_meet').find('input').attr('checked','');
    }else{
        if(value.UC_passDoc.default_value=='1')
        {
            $('label.open_tel_meet').removeClass('checked');
            //$('.groupLimit label.open_web_meet').find('input').attr('checked','');
        }
        else
        {
            $('label.open_tel_meet').addClass('checked');
            $('label.open_tel_meet').find('input').attr('checked','checked');
        }
    }	*/
}
function save_ecology_power() {
    var obj = '';
    if ($('label.open_web_meet').hasClass('checked')) {
        obj = obj + '"UC_passDoc":"2",'; //
    } else {
        obj = obj + '"UC_passDoc":"1",';
    }
    if ($('label.open_tel_meet').hasClass('checked')) {
        obj = obj + '"UC_aaa":"2"'; //
    } else {
        obj = obj + '"UC_aa":"1"';
    }
    obj = '{' + obj + '}';
    //alert(obj)
    /* var value={
        "power_json":obj
    };*/
    return obj;
}
function org_user_right(value) {
	// 可使用全时云企 IM 互传文档
	//    if (value.UC_passDoc.value == '2') {
	//        $('label.im_file').addClass('checked');
	//        //$('.groupLimit label.im_file').find('input').attr('checked','checked');
	//    } else if (value.UC_passDoc.value == '1') {
	//        $('label.im_file').removeClass('checked');
	//        //$('.groupLimit label.im_file').find('input').attr('checked','');
	//    } else {
	//        if (value.UC_passDoc.default_value == '1') {
	//            $('label.im_file').removeClass('checked');
	//            //$('.groupLimit label.im_file').find('input').attr('checked','');
	//        } else {
	//            $('label.im_file').addClass('checked');
	//            $('label.im_file').find('input').attr('checked', 'checked');
	//        }
	//    }

	// 允许用户设置接听策略
    if (value.UC_answerStrategy.value == '1') {
        $('label.accept_call').addClass('checked');
    } else if (value.UC_answerStrategy.value == '2') {
        $('label.accept_call').removeClass('checked');
    } else {
        if (value.UC_answerStrategy.default_value == '2') {
            $('label.accept_call').removeClass('checked');
        } else {
            $('label.accept_call').addClass('checked');
        }
    }
    
    // 用户可设定接听策略到海外直线电话
    if (value.UC_answerStrategyOverseas.value == '1') {
        $('label.set_area').addClass('checked');
    } else if (value.UC_answerStrategyOverseas.value == '2') {
        $('label.set_area').removeClass('checked');
    } else {
        if (value.UC_answerStrategyOverseas.default_value == '2') {
            $('label.set_area').removeClass('checked');
        } else {
            $('label.set_area').addClass('checked');
        }
    }
    
    // 允许使用云企拨打电话
    if (value.UC_isCall.value == '1') {
        $('label.accept_cloud').addClass('checked');
    } else if (value.UC_isCall.value == '2') {
        $('label.accept_cloud').removeClass('checked');
    } else {
        if (value.UC_isCall.default_value == '2') {
            $('label.accept_cloud').removeClass('checked');
        } else {
            $('label.accept_cloud').addClass('checked');
        }
    }
    
    // 允许拨打海外电话
    if (value.UC_allowcallOverseas.value == '1') {
        $('label.accept_areaPhone').addClass('checked');
    } else if (value.UC_allowcallOverseas.value == '2') {
        $('label.accept_areaPhone').removeClass('checked');
    } else {
        if (value.UC_allowcallOverseas.default_value == '2') {
            $('label.accept_areaPhone').removeClass('checked');
        } else {
            $('label.accept_areaPhone').addClass('checked');
        }
    }
    
    // 允许用户使用语音接入方式
    $('dd.pc_warning label').removeClass('radio_on');
    if (value.UC_allowUserVoice.value == '0') {
        $('dd.pc_warning ').find('label:eq(0)').addClass('radio_on');
    } else if (value.UC_allowUserVoice.value == '1') {
        $('dd.pc_warning').find(' label:eq(1)').addClass('radio_on');
    } else if (value.UC_allowUserVoice.value == '2') {
        $('dd.pc_warning').find('label:eq(2)').addClass('radio_on');
    } else if(value.UC_allowUserVoice.value == '3'){
    	 $('dd.pc_warning').find('label:eq(3)').addClass('radio_on');
    }else{
        if (value.UC_allowUserVoice.default_value == '0') {
            $('dd.pc_warning').find('label:eq(0)').addClass('radio_on');
        } else if (value.UC_allowUserVoice.default_value == '1') {
            $('dd.pc_warning').find('label:eq(1)').addClass('radio_on');
        } else if(value.UC_allowUserVoice.default_value == '2'){
        	$('dd.pc_warning').find('label:eq(2)').addClass('radio_on');
        }else{
            $('dd.pc_warning').find('label:eq(3)').addClass('radio_on');
        }
    }
    
    // 允许参会人自我外呼
    if (value.summit_allowAttendeeCall.value == '1') {
        $('label.allow_attendee_call').addClass('checked');
    } else if (value.summit_allowAttendeeCall.value == '0') {
        $('label.allow_attendee_call').removeClass('checked');
    } else {
        if (value.summit_allowAttendeeCall.default_value == '1') {
            $('label.allow_attendee_call').removeClass('checked');
        } else {
            $('label.allow_attendee_call').addClass('checked');
        }
    }
    
    // 所有参会者在加入会议时，允许录制姓名
    if (value.summit_ParticipantNameRecordAndPlayback.value == '1') {
        $('label.record_name').addClass('checked');
    } else if (value.summit_ParticipantNameRecordAndPlayback.value == '0') {
        $('label.record_name').removeClass('checked');
    } else {
        if (value.summit_ParticipantNameRecordAndPlayback.default_value == '1') {
            $('label.record_name').removeClass('checked');
        } else {
            $('label.record_name').addClass('checked');
        }
    }
    
    // 主持人加入会议语音提示
    $('dd.add_warning label').removeClass('radio_on');
    if (value.summit_Pcode2InTone.value == '0') {
        $('dd.add_warning').find('label:eq(0)').addClass('radio_on');
    } else if (value.summit_Pcode2InTone.value == '1') {
        $('dd.add_warning').find('label:eq(1)').addClass('radio_on');
    } else if (value.summit_Pcode2InTone.value == '2') {
        $('dd.add_warning').find('label:eq(2)').addClass('radio_on');
    } else {
        if (value.summit_Pcode2InTone.default_value == '0') {
            $('dd.add_warning').find('label:eq(0)').addClass('radio_on');
        } else if (value.summit_Pcode2InTone.default_value == '1') {
            $('dd.add_warning').find('label:eq(1)').addClass('radio_on');
        } else {
            $('dd.add_warning').find('label:eq(2)').addClass('radio_on');
        }
    }
    
    // 主持人退出会议语音提示
    $('dd.present_exit label').removeClass('radio_on');
    if (value.summit_Pcode2OutTone.value == '0') {
        $('dd.present_exit').find('label:eq(0)').addClass('radio_on');
    } else if (value.summit_Pcode2OutTone.value == '1') {
        $('dd.present_exit').find('label:eq(1)').addClass('radio_on');
    } else if (value.summit_Pcode2OutTone.value == '2') {
        $('dd.present_exit').find('label:eq(2)').addClass('radio_on');
    } else {
        if (value.summit_Pcode2OutTone.default_value == '0') {
            $('dd.present_exit').find('label:eq(0)').addClass('radio_on');
        } else if (value.summit_Pcode2OutTone.default_value == '1') {
            $('dd.present_exit').find('label:eq(1)').addClass('radio_on');
        } else {
            $('dd.present_exit').find('label:eq(2)').addClass('radio_on');
        }
    }
    
    // 主持人未入会，参会人入会时的初始状态
    $('dd.initial_state label').removeClass('radio_on');
    if (value.summit_Pcode2Mode.value == 'T') {
        $('dd.initial_state').find('label:eq(0)').addClass('radio_on');
    } else if (value.summit_Pcode2Mode.value == 'M') {
        $('dd.initial_state').find('label:eq(1)').addClass('radio_on');
    } else {
        if (value.summit_Pcode2Mode.default_value == 'T') {
            $('dd.initial_state').find('label:eq(0)').addClass('radio_on');
        } else{
            $('dd.initial_state').find('label:eq(1)').addClass('radio_on');
        }
    }
    
    // 参会人加入会议语音提示
    $('dd.warning_radio label').removeClass('radio_on');
    if (value.summit_Pcode1InTone.value == '0') {
        $('dd.warning_radio').find('label:eq(0)').addClass('radio_on');
    } else if (value.summit_Pcode1InTone.value == '1') {
        $('dd.warning_radio').find('label:eq(1)').addClass('radio_on');
    } else if (value.summit_Pcode1InTone.value == '2') {
        $('dd.warning_radio').find('label:eq(2)').addClass('radio_on');
    } else {
        if (value.summit_Pcode1InTone.default_value == '0') {
            $('dd.warning_radio').find('label:eq(0)').addClass('radio_on');
        } else if (value.summit_Pcode1InTone.default_value == '1') {
            $('dd.warning_radio').find('label:eq(1)').addClass('radio_on');
        } else {
            $('dd.warning_radio').find('label:eq(2)').addClass('radio_on');
        }
    }
    
    // 参会人退出会议语音提示
    $('dd.exit_warning label').removeClass('radio_on');
    if (value.summit_Pcode1OutTone.value == '0') {
        $('dd.exit_warning').find('label:eq(0)').addClass('radio_on');
    } else if (value.summit_Pcode1OutTone.value == '1') {
        $('dd.exit_warning').find('label:eq(1)').addClass('radio_on');
    } else if (value.summit_Pcode1OutTone.value == '2') {
        $('dd.exit_warning').find('label:eq(2)').addClass('radio_on');
    } else {
        if (value.summit_Pcode1OutTone.default_value == '0') {
            $('dd.exit_warning').find('label:eq(0)').addClass('radio_on');
        } else if (value.summit_Pcode1OutTone.default_value == '1') {
            $('dd.exit_warning').find('label:eq(1)').addClass('radio_on');
        } else {
            $('dd.exit_warning').find('label:eq(2)').addClass('radio_on');
        }
    }
    
    // 参会人加入会议时，是否通知其会议中的参与方数
    if (value.summit_ValidationCount.value == '0') {
        $('label.report_num').removeClass('checked');
        $('label.report_num').find('input').attr('checked', '');
    } else if (value.summit_ValidationCount.value == '1') {
        $('label.report_num').addClass('checked');
        $('label.report_num').find('input').attr('checked', 'checked');
    } else {
        if (value.summit_ValidationCount.default_value == '0') {
            $('label.report_num').removeClass('checked');
            $('label.report_num').find('input').attr('checked', '');
        } else {
            $('label.report_num').addClass('checked');
            $('label.report_num').find('input').attr('checked', 'checked');
        }
    }
    
    // 第一方与会者是否听到“您是第一个入会者”的提示
    if (value.summit_FirstCallerMsg.value == '0') {
        $('label.warning_information').removeClass('checked');
        $('label.warning_information').find('input').attr('checked', '');
    } else if (value.summit_FirstCallerMsg.value == '1') {
        $('label.warning_information').addClass('checked');
        $('label.warning_information').find('input').attr('checked', 'checked');
    } else {
        if (value.summit_FirstCallerMsg.default_value == '0') {
            $('label.warning_information').removeClass('checked');
            $('label.warning_information').find('input').attr('checked', '');
        } else {
            $('label.warning_information').addClass('checked');
            $('label.warning_information').find('input').attr('checked', 'checked');
        }
    }
    
    // 主持人离开会议时，何时结束会议
    $('dd.meeting_leave label').removeClass('radio_on');
    if (value.tang_time2.value == '0') {
        $('dd.meeting_leave').find('label:eq(0)').addClass('radio_on');
    } else if (value.tang_time2.value != '0') {
        $('dd.meeting_leave').find('label:eq(1)').addClass('radio_on');
    } else {
        if (value.tang_time2.default_value == '0') {
            $('dd.meeting_leave').find('label:eq(0)').addClass('radio_on');
        } else {
            $('dd.meeting_leave').find('label:eq(1)').addClass('radio_on');
        }
    }
    
    // 主持人退出会议时，会议是否自动终止
    $('dd.meeting_end label').removeClass('radio_on');
    if (value.tang_stopwhenoneuser.value == '0') {
        $('dd.meeting_end').find('label:eq(0)').addClass('radio_on');
    } else if (value.tang_stopwhenoneuser.value == '1') {
        $('dd.meeting_end').find('label:eq(1)').addClass('radio_on');
    } else {
        if (value.tang_stopwhenoneuser.default_value == '0') {
            $('dd.meeting_end').find('label:eq(0)').addClass('radio_on');
        } else {
            $('dd.meeting_end').find('label:eq(1)').addClass('radio_on');
        }
    }
    
    // VoIP 音频质量
    //alert(value.tang_5.value);
    $('dd.voip_quality label').removeClass('radio_on');
    if (value.tang_5.value == '11') {
        $('dd.voip_quality').find('label:eq(0)').addClass('radio_on');
    } else if (value.tang_5.value == '13') {
        $('dd.voip_quality').find('label:eq(1)').addClass('radio_on');
    } else {
        if (value.tang_5.default_value == '11') {
            $('dd.voip_quality').find('label:eq(0)').addClass('radio_on');
        } else if(value.tang_5.default_value == '13'){
            $('dd.voip_quality').find('label:eq(1)').addClass('radio_on');
        }
    }
    
    //TODO 数据会议结束后，是否立即结束电话会议
    
    
    // 会议最大方数
    if (value.tang_confscale.value != '') {
        //$('label.accept_max').addClass('checked');
        //$('label.accept_max').find('input').attr('checked', 'checked');
        $('.input_right').val(value.tang_confscale.value);
    } else{
        var vp = value.tang_confscale.default_value;
        $('.input_right').val(vp);
        //$('label.accept_max').addClass('checked');
        //$('label.accept_max').find('input').attr('checked', 'checked');
    }
    
    // 允许的接入号
    //var tell = new RegExp("[1,2,3,4,5,7]");
    if(value.summit_ConfDnisAccess.value != ''){
    	var conf_acess_arr = value.summit_ConfDnisAccess.value.split(',');
//    	alert(value.summit_ConfDnisAccess.value);
//    	alert(conf_acess_arr);
    	var i = 0;
    	for(i; i < conf_acess_arr.length; i++){
    		switch(conf_acess_arr[i]){
    			case '1':
    				$('label.accept_inner_local').addClass('checked'); // 国内本地接入
    				break;
    			case '2':
    				$('label.accept_40').addClass('checked'); // 国内 400 接入
    				break;
    			case '3':
    				$('label.accept_80').addClass('checked'); // 国内 800 接入
    				break;
    			case '4':
    				$('label.accept_local_toll').addClass('checked'); // 国际 local toll 接入
    				break;
    			case '5':
    				$('label.accept_toll_free').addClass('checked'); // 国际 toll free 接入
    				break;
    			case '7':
    				$('label.accept_hk_local').addClass('checked'); // 允许香港 local 接入
    				break;
    			default:
    				break;
    		}
    	}
    }else{
    	var conf_acess = value.summit_ConfDnisAccess.default_value;
		switch(conf_acess){
			case 1:
				$('label.accept_inner_local').addClass('checked'); // 国内本地接入
				break;
			case 2:
				$('label.accept_40').addClass('checked'); // 国内 400 接入
				break;
			case 3:
				$('label.accept_80').addClass('checked'); // 国内 800 接入
				break;
			case 4:
				$('label.accept_local_toll').addClass('checked'); // 国际 local toll 接入
				break;
			case 5:
				$('label.accept_toll_free').addClass('checked'); // 国际 toll free 接入
				break;
			case 7:
				$('label.accept_hk_local').addClass('checked'); // 允许香港 local 接入
				break;
			default:
				break;
		}
    }
}
function right_save() {
    var obj = '';
//    if ($('label.im_file').hasClass('checked')) {
//        obj = obj + '"UC_passDoc":"2",'; //允许IM互传文档
//    } else {
//        obj = obj + '"UC_passDoc":"1",';
//    }
    if ($('label.accept_call').hasClass('checked')) {
        obj = obj + '"UC_answerStrategy":"1",'; //允许用户设置接听策略
    } else {
        obj = obj + '"UC_answerStrategy":"2",';
    }
    if ($('label.set_area').hasClass('checked')) {
        obj = obj + '"UC_answerStrategyOverseas":"1",'; //允许用户接听海外直线电话
    } else {
        obj = obj + '"UC_answerStrategyOverseas":"2",';
    }
    if ($('label.accept_cloud').hasClass('checked')) {
        obj = obj + '"UC_isCall":"1",'; //允许用户使用云企电话
    } else {
        obj = obj + '"UC_isCall":"2",';
    }
    if ($('label.accept_areaPhone').hasClass('checked')) {
        obj = obj + '"UC_allowcallOverseas":"1",'; //允许拨打海外电话
    } else {
        obj = obj + '"UC_allowcallOverseas":"2",';
    }
    
    if ($('dd.pc_warning label:eq(0)').hasClass('radio_on')) {
    	//alert(0);
    	obj = obj + '"UC_allowUserVoice":"0",'; //允许用户使用语音接入方式
    } else if($('dd.pc_warning label:eq(1)').hasClass('radio_on')){
    	//alert(1);
        obj = obj + '"UC_allowUserVoice":"1",';
    }else if($('dd.pc_warning label:eq(2)').hasClass('radio_on')){
    	obj = obj + '"UC_allowUserVoice":"2",';
    }else if($('dd.pc_warning label:eq(3)').hasClass('radio_on')){
    	obj = obj + '"UC_allowUserVoice":"3",';
    }
   //alert(obj);
    if ($('label.allow_attendee_call').hasClass('checked')) {
        obj = obj + '"summit_allowAttendeeCall":"1",'; //允许参会人自我外呼
    } else {
        obj = obj + '"summit_allowAttendeeCall":"0",';
    }
    if ($('label.record_name').hasClass('checked')) {
        obj = obj + '"summit_ParticipantNameRecordAndPlayback":"1",'; //所有参会者在加入会议时，允许录制姓名
    } else {
        obj = obj + '"summit_ParticipantNameRecordAndPlayback":"0",';
    }
    if ($('dd.add_warning label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2InTone":"0",'; //主持人加入会议提示音
    } else if ($('dd.add_warning label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2InTone":"1",';
    } else if ($('dd.add_warning label:eq(2)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2InTone":"2",';
    }
    if ($('dd.present_exit label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2OutTone":"0",'; //主持人退出会议提示音
    } else if ($('dd.present_exit label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2OutTone":"1",';
    } else if ($('dd.present_exit label:eq(2)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2OutTone":"2",';
    }
    if ($('dd.initial_state label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2Mode":"M",'; //主持人未入会，参会人入会时的初始状态
    } else if ($('dd.initial_state label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode2Mode":"T",';
    }
    if ($('dd.warning_radio label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1InTone":"0",'; //允许参会人加入会议提示音
    } else if ($('dd.warning_radio label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1InTone":"1",';
    } else if ($('dd.warning_radio label:eq(2)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1InTone":"2",';
    }
    if ($('dd.exit_warning label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1OutTone":"0",'; //允许参会人退出会议提示音
    } else if ($('dd.exit_warning label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1OutTone":"1",';
    } else if ($('dd.exit_warning label:eq(2)').hasClass('radio_on')) {
        obj = obj + '"summit_Pcode1OutTone":"2",';
    }
    
    if ($('label.report_num').hasClass('checked')) {
        obj = obj + '"summit_ValidationCount":"1",'; //参会人进入会议，告知参会人会议人数
    } else {
        obj = obj + '"summit_ValidationCount":"0",';
    }
    if ($('label.warning_information').hasClass('checked')) {
        obj = obj + '"summit_FirstCallerMsg":"1",'; //第一个入会后的提示音
    } else {
        obj = obj + '"summit_FirstCallerMsg":"0",';
    }
    if ($('dd.meeting_leave label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"tang_time2":"5",'; //主持人离开会议时，何时结束会议
    } else if ($('dd.meeting_leave label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"tang_time2":"0",';
    }
    if ($('dd.meeting_end label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"tang_stopwhenoneuser":"1",'; //主持人退出会议时，会议是否自动终止
    } else if ($('dd.meeting_end label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"tang_stopwhenoneuser":"0",';
    }
    if ($('dd.voip_quality label:eq(0)').hasClass('radio_on')) {
        obj = obj + '"tang_5":"11",'; //VoIP 音频质量
    } else if ($('dd.voip_quality label:eq(1)').hasClass('radio_on')) {
        obj = obj + '"tang_5":"13",';
    }
    var scale_value = $('#accept_max_input').val();
    //alert(scale_value);
    obj = obj + '"tang_confscale":"' + scale_value +'",'; //会议允许最大方数
    var set = '';
    if ($('label.accept_inner_local').hasClass('checked')) {
        set = '1,'; //允许国内本地接入
    }
    if ($('label.accept_40').hasClass('checked')) {
        set = set + '2,'; //允许国内 400 接入
    }
    if ($('label.accept_80').hasClass('checked')) {
        set = set + '3,'; //允许国内 800 接入
    }
    if ($('label.accept_hk_local').hasClass('checked')) {
        set = set + '7,'; //允许香港 local 接入
    }
    if ($('label.accept_toll_free').hasClass('checked')) {
        set = set + '5,'; //允许国际 toll free 接入
    }
    if ($('label.accept_local_toll').hasClass('checked')) {
        set = set + '4,'; //允许国际 local toll 接入
    }
    if (set == "") {
        obj = obj + '"summit_ConfDnisAccess":"0"';
    } else {
        var lastIndex = set.lastIndexOf(',');
        if (lastIndex > -1) {
            set = set.substring(0, lastIndex) + set.substring(lastIndex + 1, set.length);
        }
        obj = obj + '"summit_ConfDnisAccess":"' + set + '"';
    }
    obj = '{' + obj + '}';
    return obj;
}
function save_show(value, count) {
    var obj = right_save();
    //alert(obj)
    //alert(count)
    obj = eval('(' + obj + ')');
    //alert(obj)
    //alert(obj.UC_passDoc)
    //obj= $.parseJSON(obj);
    //alert(value.UC_passDoc.value)
    // alert(obj[0].UC_passDoc)
//    if (value.UC_passDoc.value != obj.UC_passDoc && value.UC_passDoc.value != '') {
//        count++;
//        //$(".toolBar2").show();
//        //alert(1222)
//        //$('.groupLimit label.im_file').find('input').attr('checked','checked');
//    } else if (value.UC_passDoc.value == '') {
//        if (value.UC_passDoc.default_value != obj.UC_passDoc) {
//            count++;
//            // $(".toolBar2").show();
//            //$('.groupLimit label.im_file').find('input').attr('checked','');
//        }
//    }
    //alert(value.UC_answerStrategy.value)
    //alert(obj[1].UC_answerStrategy)
    // alert(count)
    if (value.UC_answerStrategy.value != obj.UC_answerStrategy && value.UC_answerStrategy.value != '') {

        count++; //alert(count)
        // $(".toolBar2").show();
    } else if (value.UC_answerStrategy.value == '') {
        if (value.UC_answerStrategy.default_value != obj.UC_answerStrategy) {
            //alert(2)
            count++;
            // $(".toolBar2").show();
        }
    }
    if (value.UC_answerStrategyOverseas.value != '' && value.UC_answerStrategyOverseas.value != obj.UC_answerStrategyOverseas) {
        count++;
    } else if (value.UC_answerStrategyOverseas.value == '') {
        if (value.UC_answerStrategyOverseas.default_value != obj.UC_answerStrategyOverseas) {
            count++;
        }
    }
    if (value.UC_isCall.value != '' && value.UC_isCall.value != obj.UC_isCall) {
        count++;
    } else if (value.UC_isCall.value == '') {
        if (value.UC_isCall.default_value != obj.UC_isCall) {
            count++;
        }
    }
    if (value.UC_allowcallOverseas.value != '' && value.UC_allowcallOverseas.value != obj.UC_allowcallOverseas) {
        count++;
    } else if (value.UC_allowcallOverseas.value == '') {
        if (value.UC_allowcallOverseas.default_value != obj.UC_allowcallOverseas) {
            count++;
        }
    }
    if (value.UC_allowUserVoice.value != '' && value.UC_allowUserVoice.value != obj.UC_allowUserVoice) {
        count++;
    } else if (value.UC_allowUserVoice.value == '') {
        if (value.UC_allowUserVoice.default_value != obj.UC_allowUserVoice) {
            count++;
        }
    }
    //$('dd.warning_radio label').removeClass('radio_on');
    if (value.summit_allowAttendeeCall.value != '' && value.summit_allowAttendeeCall.value != obj.summit_allowAttendeeCall) {
        count++;
    } else if (value.summit_allowAttendeeCall.value == '') {
        if (value.summit_allowAttendeeCall.default_value != obj.summit_allowAttendeeCall) {
            count++;
        }
    }
    if (value.summit_ParticipantNameRecordAndPlayback.value != '' && value.summit_ParticipantNameRecordAndPlayback.value != obj.summit_ParticipantNameRecordAndPlayback) {
        count++;
    } else if (value.summit_ParticipantNameRecordAndPlayback.value == '') {
        if (value.summit_ParticipantNameRecordAndPlayback.default_value != obj.summit_ParticipantNameRecordAndPlayback) {
            count++;
        }
    }
    if (value.summit_Pcode2InTone.value != '' && value.summit_Pcode2InTone.value != obj.summit_Pcode2InTone) {
        count++;
    } else if (value.summit_Pcode2InTone.value == '') {
        if (value.summit_Pcode2InTone.default_value != obj.summit_Pcode2InTone) {
            count++;
        }
    }
    if (value.summit_Pcode2OutTone.value != '' && value.summit_Pcode2OutTone.value != obj.summit_Pcode2OutTone) {
        count++;
    } else if (value.summit_Pcode2OutTone.value == '') {
        if (value.summit_Pcode2OutTone.default_value != obj.summit_Pcode2OutTone) {
            count++;
        }
    }
    if (value.summit_Pcode2Mode.value != '' && value.summit_Pcode2Mode.value != obj.summit_Pcode2Mode) {
        count++;
    } else if (value.summit_Pcode2Mode.value == '') {
        if (value.summit_Pcode2Mode.default_value != obj.summit_Pcode2Mode) {
            count++;
        }
    }
    if (value.summit_Pcode1InTone.value != '' && value.summit_Pcode1InTone.value != obj.summit_Pcode1InTone) {
        count++;
    } else if (value.summit_Pcode1InTone.value == '') {
        if (value.summit_Pcode1InTone.default_value != obj.summit_Pcode1InTone) {
            count++;
        }
    }
    if (value.summit_Pcode1OutTone.value != '' && value.summit_Pcode1OutTone.value != obj.summit_Pcode1OutTone) {
        count++;
    } else if (value.summit_Pcode1OutTone.value == '') {
        if (value.summit_Pcode1OutTone.default_value != obj.summit_Pcode1OutTone) {
            count++;
        }
    }
    if (value.summit_ValidationCount.value != '' && value.summit_ValidationCount.value != obj.summit_ValidationCount) {
        count++;
    } else if (value.summit_ValidationCount.value == '') {
        if (value.summit_ValidationCount.default_value != obj.summit_ValidationCount) {
            count++;
        }
    }
    if (value.summit_FirstCallerMsg.value != '' && value.summit_FirstCallerMsg.value != obj.summit_FirstCallerMsg) {
        count++;
    } else if (value.summit_FirstCallerMsg.value == '') {
        if (value.summit_FirstCallerMsg.default_value != obj.summit_FirstCallerMsg) {
            count++;
        }
    }
    if (value.tang_time2.value != '' && value.tang_time2.value != obj.tang_time2) {
        count++;
    } else if (value.tang_time2.value == '') {
        if (value.tang_time2.default_value != obj.tang_time2) {
            count++;
        }
    }
    if (value.tang_stopwhenoneuser.value != '' && value.tang_stopwhenoneuser.value != obj.tang_stopwhenoneuser) {
        count++;
    } else if (value.tang_stopwhenoneuser.value == '') {
        if (value.tang_stopwhenoneuser.default_value != obj.tang_stopwhenoneuser) {
            count++;
        }
    }
    //alert(value.UC_attendeeSurvey.value)
    // alert(obj[18].UC_attendeeSurvey)
    if (value.tang_5.value != '' && value.tang_5.value != obj.tang_5) {
        //alert(232434)
        count++;
    } else if (value.tang_5.value == '') {
        if (value.tang_5.default_value != obj.tang_5 && value.tang_5.default_value != '') {
            count++;
        }
    }
    if (value.tang_confscale.value != '' && value.tang_confscale.value != obj.tang_confscale) {
        //alert(232434)
        count++;
    } else if (value.tang_confscale.value == '') {
        if (value.tang_confscale.default_value != obj.tang_confscale && value.tang_confscale.default_value != '') {
            count++;
        }
    }
    if (value.summit_ConfDnisAccess.value != '' && value.summit_ConfDnisAccess.value != obj.summit_ConfDnisAccess) {
        //alert(232434)
        count++;
    } else if (value.summit_ConfDnisAccess.value == '') {
        if (value.summit_ConfDnisAccess.default_value != obj.summit_ConfDnisAccess && value.summit_ConfDnisAccess.default_value != '') {
            count++;
        }
    }
    /**/
    //alert(count)
    if (count != 0) {
        $(".toolBar2").show();
    } else {
        $(".toolBar2").hide();
    }
}

// @author yanzou 
//通过Ajax方式刷新内容
function loadPageExpand(url, div) {
    //var url = encodeURIComponent(url);
    /*$('.rightCont').load(url,function(){
		alert(url)	
	});*/

    $.ajax({
        url: url,
        cache: false,
        success: function(html) {
            $('.rightCont').html(html);
        },
        error: function() {
            $('.rightCont').html(errorText);
        }
    });

    //alert(url)
    location.hash = "#" + h;
    /*var hash = location.hash.substring(1);
		 alert(hash);*/
    //alert(location.hash)
    var _this = $("." + h).parent("li");
    _this.addClass('selected').siblings().removeClass('selected');
    initContentHeght();
}
//指定的 id  或  class 换为指定的内容
//url http地址
//div 加载内空的div '#name'  或class '.rightCont'
function loadContExpand(url, div) {
    //var url = encodeURIComponent(url);
    $(div).load(url);
    initContentHeght();
}
//ajax请求数据[不能这样，因为是异步的]
// url = "test.php";
//in_data = '{ "func": "getNameAndTime" }';
//function loadContPostAjax(url,in_data){
//	var re_data = '';
//	$.post(url,in_data,function(data){
//       re_data = data;
//	});
//	alert(re_data);
//	return re_data;
//}
//ajax请求html[异步执行]
// url = "test.php";
//in_data = '{ "func": "getNameAndTime" }';
//div 加载内空的div '#name'  或class '.rightCont'
function locadConttPostAjaxhtml(url, in_data, div) {
    $.post(url, in_data,
    function(data) {
        $(div).html(data);
        initContentHeght();
    });
}
function loaddiv(div) {
    //alert(div);
    $(div).html('<center><br/><br/><span class="reloading" >正在加载...</span><br/><br/></center>');
}
function re_page(div, path) {

    //在于1秒会显示
    var intervalId = setInterval("loaddiv('" + div + "')", 1); //1秒钟去执行定时器
    $.post(path,[],
    function(data) {
        //关闭定时器显示内容
        clearInterval(intervalId);
		$(div).html('');
        $(div).html(data);
    });
}

function org_del_staff()
{
	$('#part01 table tbody label').each(function() {
		if ($(this).hasClass('checked')) {
			$(this).parent().parent().remove();
		}
	}) 
	$('#part01 .tabToolBox').hide();
	if($('#part01 table tbody tr').length==0)
	{
		$('#part01 table thead tr:eq(0)').find("th:eq(0)").remove();
	}
}