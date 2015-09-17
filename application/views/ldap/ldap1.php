<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php 
		define('INSTALLDIR', realpath(dirname(__FILE__)));
		define('DIR', realpath(dirname(__FILE__).'../../../../'));
		?>
		<base href="/ucadmin/" />
		<base target="_blank" />
		<title>sooncore平台管理中心</title>
	</head>
	<body>
		<?php 
		define('INSTALLDIR', realpath(dirname(__FILE__)));
		include_once INSTALLDIR.'/step/step1.php';	//LDAP设置第一步：连接服务器
		include_once INSTALLDIR.'/step/step2.php';	//LDAP设置第二步：选择同步组织
		include_once INSTALLDIR.'/step/step3.php';	//LDAP设置第三步：指定员工信息
		include_once INSTALLDIR.'/step/step4.php';	//LDAP设置第四步：选择同步的员工信息
		include_once INSTALLDIR.'/step/step5.php';	//LDAP设置第五步：设置账号规则
		?>
		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript" src="public/js/part_js/ldap1.js"></script>
		<script type="text/javascript">
var org_info;
var property_info = {};
var filter_rule;
var tag_value;
var email_value;
var is_auto_del;
var ldap_name;
var classes;

$(function() {

    //checkbox();
});


function addOrder() {
    $(".noOrder").find("span").attr("tagert", "0");
    $('.noOrder').hide().prev().show();
    $("#orderValue").val("");
}
function addOrderSuccess(t) {
    var val = $("#orderValue").val();
    if (val == "") {
        $("#orderValue").focus().parent(".inputBox").addClass("error");
        return false;
    } else {
        $(t).parents("tr").before('<tr><td width="326">' + val + '</td><td><a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;' + '<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>' + '</td>' + ' </tr>');

        $(".noOrder").show().find("span").hide();
        $(t).parents("tr").hide();
    }
}

function deleteOrder(t) {
    var len = $(t).parents("table").find("tr").length;
    $(t).parents("tr").remove();
    if (len == 3) {
        $(".noOrder").find("span").show();
    }
}
function cancelAddOrder() {
    $('.noOrder').show().prev().hide();
}

var editVal;
function editOrder(t) {
    var val = $(t).parent("td").prev().text();
    editVal = val;
    $(t).parent("td").prev().html('<div class="inputBox w318">' + '<b class="bgR"></b>' + '<label class="label"></label>' + '<input class="input" value="' + val + '" />' + '</div>');
    $(t).parent("td").html('<a class="btnBlue yes"  onclick="editOrderSuccess(this)"><span class="text">&nbsp;确定&nbsp;</span><b class="bgR"></b></a>&nbsp; <a class="btnGray btn"  onclick="cancelEditOrder(this)"><span class="text">&nbsp;取消&nbsp;</span><b class="bgR"></b></a>')
}

function editOrderSuccess(t) {
    var val = $(t).parent("td").prev().find("input").val();
    if (val == "") {
        $(t).parent("td").prev().find("input").focus();
        return false;
    } else {
        $(t).parent("td").prev().text(val);
        $(t).parent("td").html('<a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;' + '<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>')
    }
}

function cancelEditOrder(t) {
    $(t).parent("td").prev().text(editVal);
    $(t).parent("td").html('<a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;' + '<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>');
}

$(function() {
    checkbox();
    $('.optionBox dd').click(function() {
        if ($(this).find("dd[class='option selected']").attr("target") != "0") {
            $('#select_tag').parent("div").removeClass("error");
            $('#select_two').parent("div").removeClass("error");
        }
    }) 
	$('.infoTable .selectBox').combo({
        cont: '>.text',
        listCont: '>.optionBox',
        list: '>.optionList',
        listItem: ' .option'
    });
    $("#zhType dd").click(function() {
        var index = $(this).index();
        $(".select-option").eq(index).show().siblings().hide();
        $('#select_tag').text("选择标签");
        $('#select_two').text("请选择");
    })

    $("input[name='setLabel']").click(function() {
        $(this).parents("dt").next("dd").show();
        if ($(this).attr("id") == "setLabel_1") {
            $("#setLabel_0").parents("dt").next("dd").hide();
        } else {
            $("#setLabel_1").parents("dt").next("dd").hide();
        }
    })
});

$(function() {

    $('#Select_div').click(function() {
        var rel = $(this).hasClass('selected');

    })
})
</script>
</body>
</html>