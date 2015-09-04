
<table class="table table_org">
    <thead>
    <tr>
        <th width="6%" <?php if(!$this->functions['multiChoose']) { echo "style='display:none;'"; }?>><label class="checkbox"><input type="checkbox" /></label></th>
        <th style="text-align: left; text-indent: 24px">姓名</th>
        <th>帐号</th>
        <th>手机</th>
        <th>上次登录</th>
        <th>帐号操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($user_arr as $k => $v):
	$productStatus = arr_unbound_value($v,'productStatus',2,'0');
	?>
        <tr>
            <td <?php if(!$this->functions['multiChoose']) { echo "style='display:none;'"; }?>><label class="checkbox"><input type="checkbox" value="<?php echo $v['id'];?>" /></label></td>
            <td class="tl"><a style="cursor: pointer" class="userName <?php if($v['is_org_manager'] == 1){?> manage <?php }?>  ellipsis"  onclick="staff_information1(this,<?php echo $v['id'];?>)"><?php echo $v['displayName'];?></a></td>
            <td class="tl" title="<?php echo $v['loginName']; ?>"><span class="userCount ellipsis"><?php echo $v['loginName'];?></span></td>
            <td class="telephone"><?php echo $v['mobileNumber'];?></td>
            <td class="logintime">
                <?php
                if(!bn_is_empty($v['lastlogintime'])){
                    echo dgmdate($v['lastlogintime'], 'dt');
                }else{
                	echo '未登录';
                }
                ?>
            </td>
            <td><a  class="countType <?php if($productStatus == 82): ?>  btnOn <?php else: ?> btnOff <?php endif;?>"></a></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<script type="text/javascript">
    var click_staff_name;
    var box = $('#part01 .table:first tbody .checkbox');
    //alert(box.length)
    if(box.length==0)
    {
        $('#part01 .table:first thead .checkbox').hide();
    }
    else
    {
        $('#part01 .table:first thead .checkbox').show();
       
    }
    function staff_information1(t,user_id)
    {
		if($(t).hasClass("false"))
		{
			return;
		}
		$(t).addClass("false");
		var _this=$(t);
        //alert(user_id);
        /* $(".tabToolBar").load('+"'"+'<?php //echo site_url('organize/staInfoPowPage') . '/'?>'+user_id+"'"+');*/
        $('#part01 .link_limitSet').show();
        $('#part01 #part1').remove();
       // $('#part01 .tabToolBox').hide();
        $('#part01 .tabToolBar').hide();
        $('#part01 .table_org').hide();
        var path_staff_information='staff/modify_staff_page';
        var obj={
			"user_id":user_id
        }
        $.post(path_staff_information,obj,function(data)
        {
           // alert(data)
            //$('#part01 div.bread').after(data);
            $('#part01 .tabToolBar').eq(0).after(data);
			_this.removeClass("false");
		});
        //$('#part01 #test').load('<?php //echo site_url('staff/modify_staff_page') . '/';?>' + user_id);
        //alert(4343);
    }
$(function() {
    //alert($('.contRight').attr("class"));
    //选中员工，显示对员工的操作
    var zTree = $.fn.zTree.getZTreeObj("ztree");
    var nodes = zTree.getSelectedNodes();
    var org_ID = nodes[0].id; //获得当前组织id
    $('#part01 table:first thead label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            $('#part01 .tabToolBox').hide();
            //$('#part01 .btnBeManage').hide();
            //$('#part01 .btnMoveManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
            $('#part01 .tabToolBox').show();
            if ($(this).parent().parent().parent().next().find("label.checked").length == 1) {
                //alert(111)
                var user_id = $(this).parent().parent().parent().next().find("input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            } else {
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            }
        }
    },
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            $('#part01 .tabToolBox ').hide();
            //$('#part01 .btnBeManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
            $('#part01 .tabToolBox ').show();
            if ($(this).parent().parent().parent().next().find("label.checked").length == 1) {
                var user_id = $(this).parent().parent().parent().next().find("input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            } else {
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            }
        }
    }) 
	$('#part01 table:first tbody label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            if ($(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                $(this).parent().parent().parent().prev().find("label.checkbox").removeClass("checked");
            }
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checked").length == 1) {
                //alert(1)
                var user_id = $(this).parent().parent().parent().find("label.checked input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }

        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
            }
            if ($(this).parent().parent().parent().find("label.checked").length == 1) {
                // alert(2)
                //$('#part01 .tabToolBox ').show();
                var user_id = $(this).parent().parent().parent().find("label.checked input").val();; //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }
        }
    },
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            if ($(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                $(this).parent().parent().parent().prev().find("label.checkbox").removeClass("checked");
            }
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checked").length == 1) {
                //alert(1)
                var user_id = $(this).parent().parent().parent().find("label.checked input").val(); //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }

        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                $('#part01 .tabToolBox ').show();
                $('#part01 .btnBeManage').hide();
                $('#part01 .btnMoveManage').hide();
            } else {
                $('#part01 .tabToolBox ').hide();
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
            }
            if ($(this).parent().parent().parent().find("label.checked").length == 1) {
                // alert(2)
                //$('#part01 .tabToolBox ').show();
                var user_id = $(this).parent().parent().parent().find("label.checked input").val();; //
                var staff_mag = {
                    "orgid": org_ID,
                    "user_id": user_id
                }
                //alert(staff_mag)
                set_mange(staff_mag, path_mag);
            }
        }
    })
})
</script>