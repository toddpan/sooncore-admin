var server_info;

function InitLdap_tree(Nodes) {

    $.fn.zTree.init($("#ldap_tree"), ldapSetting, Nodes);
}
function nextStep1() {
	var count=0;
	var type;
	var type1;
	$('.ldapSetBox1 table.infoTable tbody tr td div.selectBox').each(function()
			{
				$(this).removeClass("error");
				if ($(this).attr("id")=="servertype")
				{
					type=$(this).find('dd.selected').attr('target');
					 if($(this).find('span').text()=="请选择服务器类型")
					 {
						$(this).addClass("error");
						$('.ldapSetBox1 .error1').show();
		            	$('.ldapSetBox1 .error1').text("服务器类型不能为空或者不正确格式,目前只支持“Microsoft Active Directory”和“OPENDIRECTORY”类型,请正确填写");
						count++;
					 }
				}
		  	});
	$('.ldapset_1 table.infoTable tbody tr td div.inputBox').each(function()
	{
		$(this).removeClass("error");
		/*
		if($(this).attr("id")=="servertype"){
			if($(this).find('input').val() == '' || $(this).find('input').val() == 'Microsoft Active Directory'){
				type = 1;
				return true;
			}else{
				$(this).addClass('error');
				$('.ldapSetBox1 .error1').show();
				$('.ldapSetBox1 .error1').text("服务器类型不能为空或者不正确格式,目前只支持“Microsoft Active Directory”类型,请正确填写");
				count++;
				return false;
			}
		}
		*/
		if($(this).attr("id")=="protocol"){
			if($(this).find('input').val() == '' || $(this).find('input').val() == 'LDAP'){
				type1 = 1;
				return true;
			}else{
				$(this).addClass('error');
				$('.ldapSetBox1 .error1').show();
				$('.ldapSetBox1 .error1').text("服务器的连接方式不能为空或者不正确格式，目前只支持“LDAP”连接方式,请正确填写");
				count++;
				return false;
			}
		}
		if ($(this).attr("id")=="port"){
			 if($(this).find('input').val() == '' || !valitateLdpPort($(this).find('input').val()))
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
            	$('.ldapSetBox1 .error1').text("端口信息不能为空且为数字格式，请正确填写");
				count++;
				return false;
			 }
		}else{
			if(!valitateLdpUserName($(this).find('input').val()))
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
				if($(this).attr("id")=="password"){
					return true;
				}else{
					$('.ldapSetBox1 .error1').text($(this).parent().prev().text() + "信息不能为空，请填写正确信息");
					count++;
					return false;
				}
			 }
		}
  	});
    if (count > 0) {
        return false;
    } else {
		//alert(type)
		$('.ldapSetBox1 .error1').hide();
        var path = "ldap/getLdapOrganization";
        var obj = {
            //"ldap_id":ldap_id,
            "servertype": type,
            //服务器类型
            "authtype": type1,
            //连接方式
            "hostname": $('.ldapset_1 #hostname').find("input").val(),
            //LDAP服务器地址
            "port": $('.ldapset_1 #port').find("input").val(),
            //LDAP服务器端口
            "admindn": $('.ldapset_1 #admindn').find("input").val(),
            //LDAP服务器用户名
            "adminpassword": $('.ldapset_1 #password').find("input").val(),
            //LDAP服务器密码
            "basedn": $('.ldapset_1 #basedn').find("input").val(), //Base DN
    		//组织objectClass
    		"orgObjectclasses": $('.ldapset_1 #orgObjectclasses').find("input").val(),
    		//组织ID
    		"orgidproperty": $('.ldapset_1 #orgidproperty').find("input").val(),
    		//组织名称
    		"orgNameProperty": $('.ldapset_1 #orgNameProperty').find("input").val()
        };
        server_info = obj;
        obj = {
        	  server_info: server_info
        };
        var jage = 0;
        $("#checking").show();
        $.ajax({
            url: path,
            timeout:300000,
            type: "POST",
            data: obj,
            success: function(data) {
				
                var json = $.parseJSON(data);
                if (json.code == 0 && json.other_msg.length != 0) {
                   	$('.ldapSetBox1 .error1').hide();
                    
					var back_next=$('#back_next');
					$('#back_next').remove();
					$('.ldapSetBox2').after(back_next);
					$('#back_next').find("a:eq(1)").show();
					//$('.ldapSetBox1').next().remove();
                    //eval('(' +json.other_msg+ ')')
                    InitLdap_tree(json.other_msg);
                    //第五步对于非标准ldap需要勾选同步员工组织，在此初始化ldap组织树结构
                    $.fn.zTree.init($("#sync_ldap_tree"), ldapSetting, json.other_msg);
					$('#head_label a').removeClass("selected");
					$('#head_label a').removeClass("current");
					$('#head_label').find('a:eq(1)').addClass("selected");
					$('#head_label').find('a:eq(1)').addClass("current");
					$('#head_label .innerBar').css("width","40%")
					$("#checking").hide();
					$('.ldapSetBox1').hide();
                    $('.ldapSetBox2').show();

                } else{
                    $('#' + json.error_id + '').parent("div").addClass("error");
                    $('.ldapSetBox1 .error1').show();
                    $('.ldapSetBox1 .error1').text("组织信息返回数据为空,请检查输入信息是否正确,或者服务器是否有数据");
					$("#checking").hide();
                    //
                    return false;
                }
                
            },
            error: function() {
                	$('.ldapSetBox1 .error1').show();
					$('.ldapSetBox1 .error1').text("操作超时，请稍后再试");
                	$("#checking").hide();
            }
        });
    }

}
$(function() {
	$('#head_label a').removeClass("selected current");
	$('#head_label').find('a:eq(0)').addClass("selected current");
    $('.ldapSetBox1 .infoTable .selectBox').combo({
        cont: '>.text',
        listCont: '>.optionBox',
        list: '>.optionList',
        listItem: '.option'
    });
    $('#serve_type dd').click(function() {
        if ($(this).hasClass("selected") && $(this).attr("target") != "0") {
            if ($('#serve_type').parent("div").hasClass("error")) {
                $('#serve_type').parent("div").removeClass("error");
            }
        }

    });
	$('#link_type dd').click(function() {
        if ($(this).hasClass("selected") && $(this).attr("target") != "0") {
            if ($('#link_type').parent("div").hasClass("error")) {
                $('#link_type').parent("div").removeClass("error");
            }
        }

    });

});