<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>云企管理中心</title>
<link rel="stylesheet" href="public/css/jquery.Jcrop.css">
</head>
<body>
<!--系统设置_企业信息设置.html-->
<div class="contHead"> <span class="title01">系统设置</span>
	<ul class="nav02">
		<li class="selected"><a >企业信息设置</a></li>
		<li class="last"><a >站点应用设置</a></li>
	</ul>
</div>
<div class="company_infor">
	<dl class="bisinessLogo">
		<dt class="title">企业LOGO</dt>
		<dd class="img"><img id="logo_finished" src="<?php echo $data['url'] ?>" /></dd>
		<dd class="tc"><a onclick="showDialog('systemset/setLogoDialog');" class="contLink">修改</a></dd>
	</dl>
	<dl class="bisinessInfo"  style="position: relative">
		<!--<dt class="toolBar2" style="position: absolute; top: -15px; right: 10px;"> <a class="btnGray btn btn_infoEdit" ><span class="text">编辑</span><b class="bgR"></b></a> <a class="btnBlue yes btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a> </dt>-->
		<dd class="info">
			<table class="infoTable">
				<tr>
					<td class="tr">公司名称</td>
					<td><span class="infoText"><?php echo $data['name'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['name'];?>" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">公司地址</td>
					<td><span class="infoText"><?php echo $data['address'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['address'];?>" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">国家/城市</td>
					<td><span class="infoText"><?php echo $data['country'];?></span>
						<div class="combo selectBox w110 hide"> <a class="icon" ></a> <span class="text selected">中国</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">中国</dd>
									<dd class="option" target="1">美国</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						<span class="infoText"><?php echo $data['city'];?></span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['city'];?>" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">公司电话</td>
					<td><span class="infoText "><?php echo $data['phone'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59933636</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59933636" />
						</div--></td>
				</tr>
				<tr>
					<td class="tr">传真</td>
					<td><span class="infoText"><?php echo $data['fax'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59933555</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59933555" />
						</div--></td>
				</tr>
				<tr>
					<td class="tr">公司网站</td>
					<td><span class="infoText"><?php echo $data['website'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['website'];?>" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">主要联系人姓名</td>
					<td><span class="infoText dotEdit"><?php echo $data['m_name'];?></span> </td>
				</tr>
				<tr>
					<td class="tr">主要联系人电话</td>
					<td><span class="infoText"><?php echo $data['m_tel'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59937423</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59937423" />
						</div--></td>
				</tr>
				<tr>
					<td class="tr">主要联系人邮箱</td>
					<td><span class="infoText"><?php echo $data['m_email'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['m_email'];?>" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">财务联系人姓名</td>
					<td><span class="infoText dotEdit"><?php echo $data['f_name'];?></span> </td>
				</tr>
				<tr>
					<td class="tr">财务联系人电话</td>
					<td><span class="infoText"><?php echo $data['f_tel'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59937424</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59937424" />
						</div--></td>
				</tr>
				<!--<tr>
					<td class="tr">接收提醒邮箱</td>
					<td><span class="infoText tip_email">123@quanshi.com</span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="xiaolei.han@quanshi.com" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">系统通知</td>
					<td><span class="infoText system_inform">开</span>
						<div class="radioBox hide">
							<label class="radio radio_on">
							<input type="radio" checked="checked" name="sysNotice"/>
							开
							</label>
							<label class="radio">
							<input type="radio" name="sysNotice"/>
							关
							</label>
							<!--<input name="sysNotice" type="radio" class="radio" id="sysNotice_01" checked="checked" />
							<label for="sysNotice_01">开</label>
							<input name="sysNotice" type="radio" class="radio" id="sysNotice_02" />
							<label for="sysNotice_02">关</label>
						</div></td>
				</tr>-->
			</table>
		</dd>
	</dl>
</div>
<div class="groupLimit2" style="display: none">
	<div class="toolBar2" style="position: absolute; top: -15px; right: 10px; display: none"> <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a> </div>
	<!-- end tabToolBar -->
	<!-- 	<h3 class="setTitle">IM设置</h3> -->
	<!-- 	<label class="checkbox im_file checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	可使用全时云企 IM 互传文档</label> -->
	<!-- 	<label class="checkbox add_link checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的联系人添加到常用联系人列表</label> -->
	<!-- 	<label class="checkbox add_success checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的讨论组添加到讨论组列表</label> -->
	<h3 class="setTitle">通话设置</h3>
		<label class="checkbox accept_call checked">
			<input type="checkbox" checked="checked" />
				允许用户设置接听策略
		</label>
		<label class="checkbox set_area checkbox2">
			<input type="checkbox" checked="checked" />
				用户可设定接听策略到海外直线电话
		</label>
		<label class="checkbox accept_cloud checked">
			<input type="checkbox" checked="checked">
				允许使用云企拨打电话
		</label>
		<label class="checkbox accept_areaPhone checkbox2 checked">
			<input type="checkbox" checked="checked" />
				允许拨打海外电话
		</label>
	<h3 class="setTitle">会议设置</h3>
		<dl class="radio-dl">
			<dt>允许用户使用语音接入方式</dt>
			<dd class="pc_warning">
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="0" checked="checked" id="xb_1" />
					电话+VoIP
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					电话
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="2" id="xb_1" />
					VoIP
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="3" id="xb_1" />
					VoIP+国内本地接入
				</label>
			</dd>
		</dl>
		<label class="checkbox allow_attendee_call checked">
			<input type="checkbox" checked="checked" />
				允许参会人自我外呼
		</label>
		<label class="checkbox record_name checked">
			<input type="checkbox" checked="checked" />
				所有参会者在加入会议时，允许录制姓名
		</label>
		<dl class="radio-dl">
			<dt>主持人加入会议语音提示</dt>
			<dd class="add_warning">
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>主持人退出会议语音提示</dt>
			<dd class="present_exit">
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>主持人未入会，参会人入会时的初始状态</dt>
			<dd class="initial_state">
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="T" checked="checked" id="xb_0" />
					可听可讲
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="M" id="xb_1" />
					静音
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>参会人加入会议语音提示</dt>
			<dd class="warning_radio">
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>参会人退出会议语音提示</dt>
			<dd class="exit_warning">
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<label class="checkbox report_num checked">
			<input type="checkbox" checked="checked" />
			参会人加入会议，告知参会者人数
		</label>
		<label class="checkbox warning_information checked">
			<input type="checkbox" checked="checked" />
			第一方与会者是否听到“您是第一个入会者”的提示
		</label>
		<dl class="radio-dl">
		<dt>主持人离开会议时，何时结束会议</dt>
			<dd class="meeting_leave">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="0" checked="checked" id="zcrTh_0" />
					是，立即结束
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="1" id="zcrTh_1" />
					否，会议继续进行
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
		<dt>主持人退出会议时，会议是否自动终止</dt>
			<dd class="meeting_end">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="0" checked="checked" id="zcrTh_0" />
					是，立即结束
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="1" id="zcrTh_1" />
					否，会议继续进行
				</label>
			</dd>
		</dl>
		<label class="checkbox accept_95 checked">
			<input type="checkbox" checked="checked" />
			数据会议结束后，立即结束电话会议
		</label>
		<dl class="radio-dl">
		<dt>VoIP 音频质量</dt>
			<dd class="voip_quality">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="11" checked="checked" id="zcrTh_0" />
					高保真音质
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="13" id="zcrTh_1" />
					标准音质
				</label>
			</dd>
		</dl>
		<label class="accept_max">
			会议允许最大方数
			<input id="accept_max_input" name="accept_max_input" class="form-text input_right"  style="width: 60px;" value="" placeholder="" type="text"/>方
			<span class="gray-style">(只限数字，最大2000方，最小2方)</span>
		</label>
		<label class="checkbox accept_inner_local">
			<input type="checkbox" checked="checked" />
			允许国内本地接入
		</label>
		<label class="checkbox accept_40">
			<input type="checkbox" checked="checked" />
			允许国内 400 接入
		</label>
		<label class="checkbox accept_80">
			<input type="checkbox" checked="checked" />
			允许国内 800 接入
		</label>
		<label class="checkbox accept_hk_local">
			<input type="checkbox" checked="checked" />
			允许香港 local 接入
		</label>
		<label class="checkbox accept_toll_free">
			<input type="checkbox" checked="checked" />
			允许国际 toll free 接入
		</label>
		<label class="checkbox accept_local_toll">
			<input type="checkbox" checked="checked" />
			允许国际 local toll 接入
		</label>
	<div class="toolBar2" style="display: none; clear: both"> <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a> </div>
</div>
<script type="text/javascript" src="public/js/self_common.js"></script>
<script type="text/javascript" src="public/js/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="public/js/ajaxfileupload.js"></script>
<script type="text/javascript">
$('.system').removeClass("false");
	$(function(){
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		$('.infoTable .radioBox label.radio').click(function()
		{
			$('.infoTable .radioBox label.radio_on').removeClass("radio_on");
			$(this).addClass("radio_on");
			
		})
		/*$(".infoText").click(function(event){
			var _event = event||window.event;
			$(this).not('.dotEdit').hide().next().removeClass('hide');
			$(".toolBar2").show();
			_event.cancelBubble = true;
			_event.returnValue = false;
			return false;
		})
		
		$(document).click(function(){
			$(".infoText").show().next().addClass("hide");
			$(".toolBar2").hide();
		})*/
		
		
		
		$('.btn_infoEdit').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
			$('.infoTable .infoText').not('.dotEdit').each(function(){
			if($(this).hasClass("tip_email") || $(this).hasClass("system_inform"))
			{
				$(this).hide().next().removeClass('hide');
			}
			});
		});
		$('.btn_infoCancel').click(function(){
			//$(".toolBar2").hide();
			$('.infoTable .tip_email').show().next().addClass('hide');
			$('.infoTable .system_inform').show().next().addClass('hide');
			$(this).addClass('hide').siblings('.btn_infoSave').addClass('hide').siblings('.btn_infoEdit').removeClass('hide');
			/*$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});*/
		});
		$('.btn_infoSave').click(function(){
			//$(".toolBar2").hide();
			var email=$('.tip_email').next().find('input').val();
			var system_inform=$('.system_inform').next().find("label.radio_on").text();
			var sys;
			if(system_inform=="开")
			{
				sys=1;
			}
			else
			{
				sys=0;
			}
			$(this).addClass('hide').siblings('.btn_infoCancel').addClass('hide').siblings('.btn_infoEdit').removeClass('hide');
			$('.infoTable .tip_email').text(email);
			$('.infoTable .tip_email').show().next().addClass('hide');
			$('.infoTable .system_inform').text(system_inform);
			$('.infoTable .system_inform').show().next().addClass('hide');
			/*$('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
				var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : '';
				$(this).text(text);
				
			});*/
		});
		var va_post;
        $(".nav02 li:eq(1)").click(function()
        {
            $(".nav02 li").removeClass("selected");
            $(this).addClass("selected");
            $('.company_infor').hide();
			
            var obj=[];
            var path_power= "setsystem/get_sys_power";
            $.post(path_power,obj,function(data)
            {
				//alert(data);
                var value= $.parseJSON(data);
                value=value.other_msg.power;
                va_post=value;
                org_user_right(value);
                //save_show(va_post,count);
            });
            $('.groupLimit2').show();
        });
		
        $(".nav02 li:eq(0)").click(function()
        {
            $(".nav02 li").removeClass("selected");
            $(this).addClass("selected");
            $('.company_infor').show();

            $('.groupLimit2').hide();
        });
        //checkbox();
        $(".checkbox").click(function(){
            // $(".toolBar2").show();
        });
        $('.groupLimit2 label.checkbox').toggle(function(e)
        {

            var t=$(e.target);
            if(!t.hasClass('form-text'))
            {
               // alert(111);
                if($(this).hasClass('checked'))
                {//alert(222);
                    $(this).removeClass('checked');

                }
                else
                {///alert(333);
                    if(!$(this).hasClass('checked'))
                    {//alert(444);
                        $(this).addClass('checked');

                    }
                }

            }
            var count=0;
            save_show(va_post,count);
        },function(e)
        {
            var t=$(e.target)
            if(!t.hasClass('form-text'))
            {//alert(888);
                if(!$(this).hasClass('checked'))
                {
                	//alert(555);
                    $(this).addClass('checked');

                }
                else
                {//alert(666);
                    if($(this).hasClass('checked'))
                    {
                    	//alert(777);
                        $(this).removeClass('checked');

                    }
                }

            }
            var count=0;
            save_show(va_post,count);
        });
        $('dl.radio-dl label.radio').live('click',function()
        {
            //$('.link_limitSet').addClass('target_org');
            $(this).parent().find('label.radio_on').removeClass('radio_on');
            if(!$(this).hasClass('radio_on'))
            {
                $(this).addClass('radio_on');
            }
            var count=0;
            save_show(va_post,count);
        });
        $('.input_right').click(function(event)
        {
            $(this).focus();
            $(this).parent().addClass('checked');
            $(this).prev().attr('checked','checked');
            var count=0;
            save_show(va_post,count);
            // event.stopPropagation();
        });

        $('.input_right').keyup(function(event)
        {
            if($(this).val()=='')
            {
                $(this).parent().removeClass('checked');
                $(this).prev().attr('checked','');

            }
            if($(this).val()!='')
            {
                $(this).parent().addClass('checked');
                $(this).prev().attr('checked','checked');
            }
            var count=0;
            save_show(va_post,count);
            event.stopPropagation();
        }).blur(function(event)
            {
                if($(this).val()!='')
                {
                    $(this).parent().addClass('checked');
                    $(this).prev().attr('checked','checked');
                }
                else
                {
                    $(this).parent().removeClass('checked');
                    $(this).prev().attr('checked','');
                }
                var count=0;
                save_show(va_post,count);
                event.stopPropagation();
            })
	});
    function saveSuccess() {
        var obj=right_save();
		var value={
		"power_json":obj
		};
		
        var path = "setsystem/save_sys_power";
        $.post(path,value,function(data){
            //alert(data);
            var json=$.parseJSON(data);
            if(json.code==0)
            {
                $(".rightCont").append('<div class="successMsg">保存成功</div>');
                setTimeout(function(){
                    $(".successMsg").remove();
                    $(".toolBar2").hide();
                },2000)
            }
			else
				{
					alert(json.prompt_text)	
				}
        })

    }

</script>
</body>
</html>
