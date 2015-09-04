<div class="org_right">
	<!-- 	<h3 class="setTitle">IM设置</h3> -->
	<!-- 	<label class="checkbox im_file checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	可使用全时蜜蜂 IM 互传文档</label> -->
	<!-- 	<label class="checkbox add_link checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的联系人添加到常用联系人列表</label> -->
	<!-- 	<label class="checkbox add_success checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的讨论组添加到讨论组列表</label> -->
	<h3 class="setTitle">通话设置</h3>
		<label class="checkbox accept_call checked" style="width: 155px;">
			<input type="checkbox" checked="checked" />
				允许用户设置接听策略
		</label>
		<label class="checkbox set_area checkbox2" style="width: 240px;">
			<input type="checkbox" checked="checked" />
				用户可设定接听策略到海外直线电话
		</label>
		<label class="checkbox accept_cloud checked" style="width: 155px;">
			<input type="checkbox" checked="checked">
				允许使用蜜蜂拨打电话
		</label>
		<label class="checkbox accept_areaPhone checkbox2 checked" style="width: 130px;">
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
		<label class="checkbox allow_attendee_call checked" style="width: 150px;">
			<input type="checkbox" checked="checked" />
				允许参会人自我外呼
		</label>
		<label class="checkbox record_name checked" style="width: 260px;">
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
		<label class="checkbox report_num checked" style="width: 220px;">
			<input type="checkbox" checked="checked" />
			参会人加入会议，告知参会者人数
		</label>
		<label class="checkbox warning_information checked" style="width: 325px;">
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
		<label class="checkbox accept_95 checked" style="width: 240px;">
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
		<label class="checkbox accept_inner_local" style="width: 130px;">
			<input type="checkbox" checked="checked" />
			允许国内本地接入
		</label>
		<label class="checkbox accept_40" style="width: 135px;">
			<input type="checkbox" checked="checked" />
			允许国内 400 接入
		</label>
		<label class="checkbox accept_80" style="width: 135px;">
			<input type="checkbox" checked="checked" />
			允许国内 800 接入
		</label>
		<label class="checkbox accept_hk_local" style="width: 140px;">
			<input type="checkbox" checked="checked" />
			允许香港 local 接入
		</label>
		<label class="checkbox accept_toll_free" style="width: 160px;">
			<input type="checkbox" checked="checked" />
			允许国际 toll free 接入
		</label>
		<label class="checkbox accept_local_toll" style="width: 165px;">
			<input type="checkbox" checked="checked" />
			允许国际 local toll 接入
		</label>
</div>
<script type="text/javascript">
$(function() {
    var power_json = <?php echo json_encode($power_arr) ?>;
    value = power_json;
    org_user_right(value);

    $(".groupLimit .checkbox").click(function() {
        var count = 0;
    });
    $('.groupLimit label.checkbox').toggle(function(e) {

        var t = $(e.target);
        if (!t.hasClass('form-text')) {
            if ($(this).hasClass('checked')) {
                $(this).removeClass('checked');
            } else {
                if (!$(this).hasClass('checked')) {
                    $(this).addClass('checked');
                }
            }

        }
        var count = 0;
        save_show(value, count);
    },
    function(e) {
        var t = $(e.target) 
		if (!t.hasClass('form-text')) {
            if (!$(this).hasClass('checked')) {

                $(this).addClass('checked');
            } else {
                if ($(this).hasClass('checked')) {

                    $(this).removeClass('checked');
                }
            }

        }
        var count = 0;
        save_show(value, count)
    });
    $('dl.radio-dl label.radio').live('click',
    function() {
        $(this).parent().find('label.radio_on').removeClass('radio_on');
        if (!$(this).hasClass('radio_on')) {
            $(this).addClass('radio_on');
        }
        var count = 0;
        save_show(value, count);
    });
    $('.input_right').click(function(event) {
        //alert(111)
        $(this).focus();
        $(this).parent().addClass('checked');
        $(this).prev().attr('checked', 'checked');
        var count = 0;
        save_show(value, count);
    });

    $('.input_right').keyup(function(event) {
    	//alert(444)
        if ($(this).val() == '') {
            $(this).parent().removeClass('checked');
            $(this).prev().attr('checked', '');
            //alert(222)
        }
        if ($(this).val() != '') {
            $(this).parent().addClass('checked');
            $(this).prev().attr('checked', 'checked');
            //alert(333)
        }
        var count = 0;
        save_show(value, count);
        event.stopPropagation();
    }).blur(function(event) {
        if ($(this).val() != '') {
            $(this).parent().addClass('checked');
            $(this).prev().attr('checked', 'checked');

        } else {
            $(this).parent().removeClass('checked');
            $(this).prev().attr('checked', '');

        }
        var count = 0;
        save_show(value, count);
        event.stopPropagation();
    }) 
	$('#set_right').click(function() {
		if($(this).hasClass("false"))
		{
			return;
		}
		$(this).addClass("false");
		var _this=$(this);
//         var obj = right_save('.org_right ');
        var zTree = $.fn.zTree.getZTreeObj("ztree");
        var nodes = zTree.getSelectedNodes();
        var treeNode = nodes[0];
        var id_2 = treeNode.pId;
        var org_code = '-' + treeNode.id;
        var node;
        while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
            node = zTree.getNodesByParam('id', id_2, null)[0];
            id_2 = node.pId;
            org_code = '-' + node.id + org_code;

        }
        var obj = right_save('.org_right ');
        var value = {
            "power_json": obj,
            "org_code": org_code
        };
        var path = "organize/save_org_power";
        $.post(path, value,
        function(data) {
            var json = $.parseJSON(data);
            if (json.code == 0) {
                $('.groupLimit').hide();
            }
			else
				{
					alert(json.prompt_text)	
				}
			_this.removeClass("false");
        })
    })
})
</script>
