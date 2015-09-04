           <table class="table  table_cost">
                <thead>
                    <tr>
                        <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
                        <th style="text-align: left; text-indent: 24px">姓名</th>
                        <th>帐号</th>
                        <th>手机</th>
                        <th>上次登录</th>
                        <th>帐号操作</th>
                    </tr>
                </thead>
                <tbody>
				    <?php foreach($members as $k => $v):?>
                    <tr>
                        <td><label class="checkbox"><input type="checkbox" value="<?php echo $v['id'];?>" /></label></td>
                        <td class="tl"><a style="cursor: pointer"  class="userName  ellipsis"  onclick="staff_information2(this,<?php echo $v['id'];?>)"><?php echo $v['name'];?></a></td>
                        <td class="tl"><span class="ellipsis"><?php echo $v['login_name'];?></span></td>
                        <td><?php echo $v['phone'];?></td>
                        <td>
						<?php echo $v['last_login'];?>
						</td>
                        <td><a  class="<?php echo $v['is_open'] ? 'btnOff' : 'btnOn';?>"></a></td>
                    </tr> 
					<?php endforeach;?>  
                </tbody>
            </table>
<script type="text/javascript">
var click_staff_name;
var box2 = $('#part02 .table:first tbody .checkbox');
if (box2.length == 0) {
    $('#part02 .table:first thead .checkbox').hide();
} else {
    $('#part02 .table:first thead .checkbox').show();
    //click_staff_name = '0' + '<?php //echo $v['id '];?>';  /**不知道是干什么用的，先注释掉，后边如果发现有用请打开。----白雪    xue.bai_2@quanshi.com   2014-12-30**/
}
function staff_information2(t,user_id) {
	if($(t).hasClass("false"))
		{
			return;
		}
	$(t).addClass("false");
	var _this=$(t);
    $('#part02 .tabToolBar').hide();
    $('#part02 div.bread').siblings().hide();
    //$('#part02 .link_limitSet').show();
    $('#part02 div.bread').show();
    //$('#part02 #test').show();
	 var path_staff_information='staff/modify_staff_page';
     var obj={
			"user_id":user_id
        }
     $.post(path_staff_information,obj,function(data)
        {
           // alert(data)
            //$('#part01 div.bread').after(data);
            $('#part02 .tabToolBar').eq(0).after(data);
			_this.removeClass("false");
		});
}
function showToolBox(count)
{
	if($('.treeNode').hasClass("no_setCost"))
	{
		$('#part02 .tabToolBox').show();
		if(count==0)
		{
			//alert(1)
			//$('#part02 .tabToolBox').hide();
			$('#part02 .tabToolBox .btnMoveUserTo').hide();
			$('#part02 .tabToolBox .btnMoveUser').hide();
		}
		else
		{
			//alert(2)
			$('#part02 .tabToolBox .btnMoveUserTo').show();
			$('#part02 .tabToolBox .btnMoveUser').hide();
			 
		}
	}
	else
	{
		if(count==0)
		{
			$('#part02 .tabToolBox').hide();
		}
		else
		{
			$('#part02 .tabToolBox').show();
		}
	}
}
$(function() {
    $('#part02 table:first thead label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            //$('#part02 .tabToolBox').hide();
			showToolBox(0);
            //$('#part01 .btnBeManage').hide();
            //$('#part01 .btnMoveManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
           // $('#part02 .tabToolBox').show();
		   showToolBox(1);
        }
    },
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").removeClass("checked");
            //$('#part02 .tabToolBox ').hide();
			showToolBox(0);
            //$('#part01 .btnBeManage').hide();
        } else {
            $(this).addClass("checked");
            $(this).parent().parent().parent().next().find("label.checkbox").addClass("checked");
           // $('#part02 .tabToolBox ').show();
		   showToolBox(1);
        }
    });
    $('#part02 table:first tbody label.checkbox').toggle(function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            if ($(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                $(this).parent().parent().parent().prev().find("label.checkbox").removeClass("checked");
            }
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
               // $('#part02 .tabToolBox ').show();
				showToolBox(1);
            } else {
                //$('#part02 .tabToolBox ').hide();
				showToolBox(0);
                //$('#part01 .btnBeManage').hide();
            };
        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
                //$('#part02 .tabToolBox ').show();
				showToolBox(1);
            } else {
               // $('#part02 .tabToolBox').hide();
				showToolBox(0);
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
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
               // $('#part02 .tabToolBox ').show();
			   showToolBox(1);
            } else {
                //$('#part02 .tabToolBox ').hide();
				showToolBox(0);
                //$('#part01 .btnBeManage').hide();
            };
        } else {
            $(this).addClass("checked");
            if ($(this).parent().parent().parent().find("label.checked").length > 0) {
               // $('#part02 .tabToolBox ').show();
				showToolBox(1)
            } else {
                //$('#part02 .tabToolBox ').hide();
				showToolBox(0)
                //$('#part01 .btnBeManage').hide();
            };
            if ($(this).parent().parent().parent().find("label.checkbox").length == $(this).parent().parent().parent().find("label.checked").length) {
                if (!$(this).parent().parent().parent().prev().find("label.checkbox").hasClass("checked")) {
                    $(this).parent().parent().parent().prev().find("label.checkbox").addClass("checked");
                }
            }
        }
    })
})

</script>