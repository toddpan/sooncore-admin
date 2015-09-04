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
			//alert(type)
			 if($(this).find('span').text()=="请选择服务器类型")
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
            	$('.ldapSetBox1 .error1').text("请填写正确信息");
				count++;
			 }
		}
		else if($(this).attr("id")=='protocol')
		{
			type1=$(this).find('dd.selected').attr('target');
			if($(this).find('span').text()=="请选择连接方式")
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
            	$('.ldapSetBox1 .error1').text("请填写正确信息");
				count++;
			 }
		}
  	})
	$('.ldapSetBox1 table.infoTable tbody tr td div.inputBox').each(function()
	{
		$(this).removeClass("error");
		if ($(this).attr("id")=="port")
		{
			 if(!valitateLdpPort($(this).find('input').val()))
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
            	$('.ldapSetBox1 .error1').text("请填写正确信息");
				count++;
			 }
		}
		else
		{
			if(!valitateLdpUserName($(this).find('input').val()))
			 {
				$(this).addClass("error");
				$('.ldapSetBox1 .error1').show();
				if ($(this).attr("id")=="password" || $(this).attr("id")=="hostname")
				{
            		$('.ldapSetBox1 .error1').text("用户或密码错误");
					count++;
				}
				else
				{
					$('.ldapSetBox1 .error1').text("请填写正确信息");
					count++;
				}
			 }
		}
  	})
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
            "hostname": $('#hostname').find("input").val(),
            //LDAP服务器地址
            "port": $('#port').find("input").val(),
            //LDAP服务器端口
            "admindn": $('#admindn').find("input").val(),
            //LDAP服务器用户名
            "adminpassword": $('#password').find("input").val(),
            //LDAP服务器密码
            "basedn": $('#basedn').find("input").val(), //Base DN
    		//组织objectClass
    		"orgObjectclasses": $('#orgObjectclasses').find("input").val(),
    		//组织ID
    		"orgidproperty": $('#orgidproperty').find("input").val(),
    		//组织名称
    		"orgNameProperty": $('#orgNameProperty').find("input").val()
        };
        server_info = obj;
        obj = {
        	  server_info: server_info,
        };
        var jage = 0;
        $("#checking").show();
        $.ajax({
            url: path,
            timeout:60000,
            type: "POST",
            data: obj,
            success: function(data) {
				
                var json = $.parseJSON(data);
                if (json.code == 0) {
                   	$('.ldapSetBox1 .error1').hide();
                    
					var back_next=$('#back_next');
					$('#back_next').remove();
					$('.ldapSetBox2').after(back_next);
					$('#back_next').find("a:eq(1)").show();
					//$('.ldapSetBox1').next().remove();
                    //eval('(' +json.other_msg+ ')')
					
                    InitLdap_tree(json.other_msg);
					$('#head_label a').removeClass("selected");
					$('#head_label a').removeClass("current");
					$('#head_label').find('a:eq(1)').addClass("selected");
					$('#head_label').find('a:eq(1)').addClass("current");
					$('#head_label .innerBar').css("width","40%")
					$("#checking").hide();
					$('.ldapSetBox1').hide();
                    $('.ldapSetBox2').show();

                } else {
                    $('#' + json.error_id + '').parent("div").addClass("error");
                    $('.ldapSetBox1 .error1').show();
                    $('.ldapSetBox1 .error-text').text("请填写正确信息");
					$("#checking").hide();
                    //
                    return false;
                };
                
            },
            error: function() {
                	$('.ldapSetBox1 .error1').show();
					$('.ldapSetBox1 .error-text').text("操作超时，请稍后再试");
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

    }) 
	$('#link_type dd').click(function() {
        if ($(this).hasClass("selected") && $(this).attr("target") != "0") {
            if ($('#link_type').parent("div").hasClass("error")) {
                $('#link_type').parent("div").removeClass("error");
            }
        }

    })

});