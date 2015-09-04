
// JavaScript Document
//企业生态页面1
function nextStep1() {
    //alert(111)
	var zTree = $.fn.zTree.getZTreeObj('stqyTree');
	var treeNode=zTree.getSelectedNodes();
	//var company_ecol_id=0;
    var i = 0;
    var value_name = $('#create_company_name').val(); //公司名称
	//alert(value_name)
    var value_chinese = $('#create_company_chinese').val(); //中文简称
   //alert(value_chinese) 
   	var value_quhao = $('#create_area_code').val(); //区号
    var value_addNum = $('#create_add_num').next().find("dd.selected").text(); //+86
    var value_phoneNum = $('#create_phoneNum_1').val(); //电话号码
    var value_location = $('#create_country_area').val(); //国家地区
    var value_introduce = $('#textarea').val(); //公司介绍
    value_introduce = value_introduce.replace(/(\r\n|\n|\r)/gm, '<br/>'); // textarea 必须要把换行转换成<br/>
    //alert($('#add_num').next().find("dd.selected").text()) 
	var isValid_company_name = valitateCompanyName(value_name);
	//alert(isValid_company_name)
    // var isValid_company_english=valitateCompanyEnglish(value_english);
    var isValid_company_chinese = valitateCompanyName(value_chinese);
    var isValid_area_code = valitateAreaCode(value_quhao);
    var isValid_phoneNum = valitateTelephonNum(value_phoneNum);
    var isValid_country_area = (value_location != '') ? true: false;
    var isValid_company_introduce = valitateCompanyIntroduce(value_introduce);
    $("div label").each(function() {
        $(this).parent("div").removeClass("error");
    }) 
	$('textarea').each(function() {
        $(this).parent("div").removeClass("error");
    }) 
	var count = 0;
    if (!isValid_company_name) {
        //alert(1)
        var error_show = '<span class="error-text">请填写企业名称</span>';
        $('#create_company_name').parent("div").addClass("error");
        $('#create_company_name').parent("div").append(error_show);
        count++;
    }
    if (!isValid_company_chinese) {
        //alert(3)
        var error_show = '<span class="error-text">请填写中文简称</span>';
        $('#create_company_chinese').parent("div").addClass("error");
        $('#create_company_chinese').parent("div").append(error_show);
        count++;
    }
    if (!isValid_area_code) {
        /* var error_show='<span class="error-text">请填写英文简称</span>';
	 $('#area_code').parent("div").append(error_show); */
        $('#create_area_code').parent("div").addClass("error");
        count++;
    }
    if (!isValid_phoneNum) {
        var error_show = '<span class="error-text">请填写联系电话</span>';
        $('#create_phoneNum_1').parent("div").append(error_show);
        $('#create_phoneNum_1').parent("div").addClass("error");
        count++;
    }
    if (!isValid_country_area) {
        var error_show = '<span class="error-text">请填写国家地区</span>';
        $('#create_country_area').parent("div").append(error_show);
        $('#create_country_area').parent("div").addClass("error");
        count++;
    }
    if (!isValid_company_introduce) {
        var error_show = '<span class="error-text">请填写公司介绍</span>';
        $('#textarea').parent("div").append(error_show);
        $('#textarea').parent('div').addClass("error");
        count++;
    }
    if (value_addNum == null) {
        $('#create_add_num').parent('div').addClass("error");
        count++;
    }
    //var telephoneNumber="";
    // telephoneNumber=''+value_quhao+''+value_phoneNum+'';
    //  alert(count);
    if (count > 0) {
        return false;
    } else {
		//alert(value_addNum)
        var path = 'ecology/valid_eco_1'; //添加PHP要抛的地址*/        
        obj1_json = ''; //重置
        //obj1_json += '"operate_type":"0",';
        obj1_json += '"create_company_name":"' + value_name + '",';
        obj1_json += '"create_company_chinese":"' + value_chinese + '",';
        obj1_json += '"create_country_code":"'+ value_addNum+'",';
        obj1_json += '"create_area_code":"'+ value_quhao +'",';
        obj1_json += '"create_phone_number":"' + value_phoneNum + '",';
        obj1_json += '"create_country_area":"' + value_location + '",';
        obj1_json += '"textarea":"' + value_introduce + '",';
        obj1_json += '"org_id":"' + company_ecol_id + '"';
        obj1_json = '{' + obj1_json + '}';
        //alert(obj1_json);
        obj1 = eval('(' + obj1_json + ')');
        $("#checking").show();
        $.ajax({
            url: path,
            timeout: 6000,
            type: "POST",
            data: obj1,
            success: function(data) {
                var json = $.parseJSON(data);
                if (json.code == 0) {
                        //loadCont('<?php // echo site_url('ecologycompany/setEcologyCompany')?>');
                        $('#creater_one').hide();
                        $('#creater_two').show();
						var back_next=$('#prev_next');
						$('#prev_next').remove();
						$('#creater_two').after(back_next);
						$('#prev_next').find("a:eq(1)").show();
						$('#head_style a').removeClass("selected");
						$('#head_style a').removeClass("current");
						$('#head_style').find('a:eq(1)').addClass("selected");
						$('#head_style').find('a:eq(1)').addClass("current");
						$('#head_style .innerBar').css("width","50%")
                        $("#checking").hide();
                   
                } else {
                    if (json.error_id == "textarea") 
					{
                        $("textarea").parent().addClass("error");
                    } else {
                        $("#"+json.error_id).parent().addClass("error");
                    }
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

}
$(function() {
    $('#creater_one table.infoTable .selectBox').toggle(function(e) {
        $(this).find(".optionBox").addClass("_show");
        if ($(e.target).hasClass("option")) {
            $("#add_num").addClass("selected");
            $('#creater_one table.infoTable .selectBox dd').removeClass("selected");
            $(e.target).addClass("selected");
            //alert(2)
        }

        //})
        //$(this).find(".optionBox").addClass("del");
        //}
        //
    },
    function(e) {
      $(this).find(".optionBox").removeClass("_show");
        if ($(e.target).hasClass("option")) {
            $(e.target).parent().parent().prev().removeClass('selected');
            $(e.target).parent().parent().prev().addClass('selected');
            $(e.target).parent().parent().prev().text($(e.target).text());
            $(e.target).parent().parent().prev().attr("name", $(e.target).text());
            //$('.ldapSetBox table.infoTable .selectBox .optionBox').adddClass("_hide");
            $('#creater_one table.infoTable .selectBox dd').removeClass("selected");
            $(e.target).addClass("selected");

            //$("#add_num").text();
            //alert($("#add_num").text())
            //alert(1)
        }
    })

    $('#creater_one table.infoTable .selectBox dd').mouseenter(function() {
        $('#creater_one table.infoTable .selectBox dd').removeClass("hover");
        $(this).addClass("hover");
    }).mouseleave(function() {
        $(this).removeClass("hover");
    }) 
	$('#setS2 label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
        } else if (!$(this).hasClass("checked")) {
            $(this).addClass("checked");
        }
    },
    function() {
        if (!$(this).hasClass("checked")) {
            $(this).addClass("checked");
        } else if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
        }
    })

});