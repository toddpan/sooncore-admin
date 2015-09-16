<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--安全管理_密码管理.html-->
<div class="contHead">
	<span class="title01">安全管理</span>
	<ul class="nav02">
		<?php if($this->p_role_id == SYSTEM_MANAGER){?>
		<li class="first selected"><a >密码管理</a></li>
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
<!--  		<li><a onclick="loadCont('sensitiveword/sensitiveWordPage/1');">敏感词管理</a></li>-->
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
		<li><a onclick="loadCont('log/logPage');">日志管理</a></li>
		<?php }?>
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
<!-- 		<li class="last"><a onclick="loadCont('useraction/userActionPage');">用户活动查询</a></li> -->
		<?php }?>
	</ul>
</div>
<div class="contTitle" style="margin-bottom: 20px;"><span class="text">密码变更设置</span></div>
<style type="text/css">
.listBox .list li {
	border: none;
}
</style>
<div class="listBox">
	<ul class="list">
		<li>
			<span class="text02" style="color: #555758; text-align: left">用户密码有效期</span>
			<div class="combo selectBox w150">
				
				<a class="icon" ></a>
				<span class="text selected">90天</span>
				<div class="optionBox part1" target="0">
					<dl class="optionList">
						<dd class="option <?php if($pwdArr['expiry_day_type'] == 1){?>selected<?php }?>" target="1">30天</dd>
						<dd class="option <?php if($pwdArr['expiry_day_type'] == 2){?>selected<?php }?>" target="2">60天</dd>
						<dd class="option <?php if($pwdArr['expiry_day_type'] == 3){?>selected<?php }?>" target="3">90天</dd>
						<dd class="option <?php if($pwdArr['expiry_day_type'] == 4){?>selected<?php }?>" target="4">180天</dd>
						<dd class="option <?php if($pwdArr['expiry_day_type'] == 5){?>selected<?php }?>" target="5">不需要变更</dd>
					</dl>
					<input type="hidden" class="val" value="2" />
				</div>
			</div>
		</li>
		<li>
			<span class="text02" style="color: #555758; text-align: left">密码历史记忆</span>
			<div class="combo selectBox w150">
				
				<a class="icon" ></a>
				<span class="text selected">3次</span>
				<div class="optionBox part2" target="0">
					<dl class="optionList">
						<dd class="option <?php if($pwdArr['history_type'] == 1){?>selected<?php }?>" target="1">3次</dd>
						<dd class="option <?php if($pwdArr['history_type'] == 2){?>selected<?php }?>" target="2">5次</dd>
						<dd class="option <?php if($pwdArr['history_type'] == 3){?>selected<?php }?>" target="3">10次</dd>
						<dd class="option <?php if($pwdArr['history_type'] == 4){?>selected<?php }?>" target="4">不记忆</dd>
					</dl>
					<input type="hidden" class="val" value="0" />
				</div>
			</div>
		</li>
		<li>
			<span class="text02" style="color: #555758; text-align: left">密码复杂性要求</span>
			<div class="combo selectBox" style="width: 222px;">
				
				<a class="icon" ></a>
				<span class="text selected">8-30位数字与字母组合</span>
				<div class="optionBox part3" target="0">
					<dl class="optionList">
						<dd class="option <?php if($pwdArr['complexity_type'] == 1){?>selected<?php }?>" target="1">8-30位，不限制类型</dd>
						<dd class="option <?php if($pwdArr['complexity_type'] == 2){?>selected<?php }?>" target="2">8-30位数字与字母组合</dd>
						<dd class="option <?php if($pwdArr['complexity_type'] == 3){?>selected<?php }?>" target="3">8-30位数字、符号与字母组合</dd>
					</dl>
					<input type="hidden" class="val" value="2" />
				</div>
			</div>
		</li>
	</ul>
	<b class="bgTL"></b><b class="bgTR"></b><b class="bgBL"></b><b class="bgBR"></b>
</div>

<div class="btnBox01" style="display: none">
	<a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a>
	<a class="btnGray btn"  onclick="cancel_resetPwd()"><span class="text">取消</span><b class="bgR"></b></a>
</div>
<script type="text/javascript">
$('.safe').removeClass("false");
var validity = $('ul.list .optionBox').eq(0).find('dd.selected').attr('target');
var momery = $('ul.list .optionBox').eq(1).find('dd.selected').attr('target');
var complexity = $('ul.list .optionBox').eq(2).find('dd.selected').attr('target');
function saveSuccess() {
    var expiry_day_type;
    var history_type;
    var complexity_type;
    $('.part1 dd').each(function() {
        if ($(this).hasClass("selected")) {
            expiry_day_type = $(this).attr("target");
        }
    }) 
	$('.part2 dd').each(function() {
        if ($(this).hasClass("selected")) {
            history_type = $(this).attr("target");
        }
    }) 
	$('.part3 dd').each(function() {
        if ($(this).hasClass("selected")) {
            complexity_type = $(this).attr("target");
        }
    }) 
	var path = "password/modifyPWDManage";
    var obj = {
        "expiry_day_type": expiry_day_type,
        "history_type": history_type,
        "complexity_type": complexity_type
    };
    $.post(path, obj,
    function(data) {
        //alert(data)
        var json = $.parseJSON(data);
        if (json.code == 0) {
            validity = expiry_day_type;
            momery = history_type;
            complexity = complexity_type;
            $(".rightCont").append('<div class="successMsg">保存成功</div>');
            setTimeout(function() {
                $(".successMsg").remove();
                $(".btnBox01").hide();
            },
            2000)
        } else
				   {
				   		alert(json.prompt_text);
						return false;
				   }
    })
}

function cancel_resetPwd() {
    $('ul.list .optionBox dd').removeClass('selected');
    $('ul.list .optionBox:first dd').each(function() {
        if ($(this).attr('target') == validity) {
            $(this).addClass('selected');
            $('ul.list .selectBox:eq(0) span.text').text($(this).text());
        }
    });
    $('ul.list .optionBox:eq(1) dd').each(function() {
        if ($(this).attr('target') == momery) {
            $(this).addClass('selected');
            $('ul.list .selectBox:eq(1) span.text').text($(this).text());
        }
    });
    $('ul.list .optionBox:last dd').each(function() {
        if ($(this).attr('target') == complexity) {
            $(this).addClass('selected');
            $('ul.list .selectBox:eq(2) span.text').text($(this).text());
        }
    });
    $('.btnBox01').hide();
}
$(function() {
    $('.selectBox').combo({
        cont: '>.text',
        listCont: '>.optionBox',
        list: '>.optionList',
        listItem: ' .option'
    });
    $('.selectBox').toggle(function() {
        // $(this).siblings('.optionBox').show();
        //alert(23)
        //
        //alert($(this).siblings('.optionBox').attr('target'))
        if ($(this).find('.optionBox').attr('target') == '0') {
            // alert(1)
            $('.optionBox').hide();
            $('.optionBox').attr('target', '0');
            $(this).find('.optionBox').show();
            $(this).find('.optionBox').attr('target', '1');
            $(".btnBox01").show();
        } else {
            // alert(2)
            $(this).find('.optionBox').hide();
            $(this).find('.optionBox').attr('target', '0');
        }

    },
    function() {
        if ($(this).find('.optionBox').attr('target') == '0') {
            // alert(1)
            $('.optionBox').hide();
            $('.optionBox').attr('target', '0');
            $(this).find('.optionBox').show();
            $(".btnBox01").show();
            $(this).find('.optionBox').attr('target', '1');
        } else {
            // alert(2)
            $(this).find('.optionBox').hide();
            $(this).find('.optionBox').attr('target', '0');
        }
        //$(this).siblings('.optionBox').hide();
    });
    $(document).click(function(e) {
        var t = $(e.target);
		if (!t.hasClass('selectBox')) {
            $('.optionBox').hide();
            $('.optionBox').attr('target', '0');
        }
    })
	$('.optionBox dd').click(function() {

        $('.optionBox').hide();
        $('.optionBox').attr('target', '0');
    })
});
</script>
</body>
</html>