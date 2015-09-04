<dd class="staff">
  <table class="table">
    <thead>
      <tr>
        <th style="text-align: left; text-indent: 24px">姓名</th>
        <th>帐号</th>
        <th>手机</th>
        <th>上次登录</th>
        <th>帐号操作</th>
      </tr>
    </thead>
    <tbody>
   {foreach $user_arr as $v}
      <tr>
        <td class="tl">
			<a name="{$v['id']}" class="userName ellipsis" id="create_staff">{$v['displayName']}</a>
		</td>
        <td class="tl"><span class="userCount ellipsis">{$v['loginName']}</span></td>
        <td class="telephone">{$v['mobileNumber']}</td>
        <td class="logintime">{$v['lastlogintime']}</td>
        <td id="creat_staff_btnOn"><a class="countType {if $productStatus==82}btnOn{else}btnOff{/if}"></a></td>
      </tr>
  {/foreach}
    </tbody>
  </table>
</dd>
<script type="text/javascript" src="public/js/part_js/input_radio_tree.js"></script>
<script type="text/javascript">		

//var  zNodes = <?php echo $org_list_json ;?>;//初始化的组织结构
//var path="<?php echo site_url('organize/get_next_OrgList');?>";//要加载的每个组织结构
$(function() {
    $('#create_staff').click(function() {
        $('#part01 .part01_1').hide();
        var path_staff_information ='staff/modify_staff_page';
        var obj = {
            "user_id": $(this).attr("name")
        }
        //alert($(this).name);
        $.post(path_staff_information, obj,
        function(data) {
            //alert(data)
            //$('#part01 div.bread').after(data);
            $('#part01 .part01_1').after(data);
            $('#part01 .part01_1 .bread').hide();
            //$('#part01 .part01_1 .cont-wrapper').remove();
        })
    })
	$('#creat_staff_btnOn a').click(function() {
        var user_id = $('#create_staff').attr("name");
        //alert("<?php echo site_url('staff/closeAccount'); ?>" + "/" + user_id);
        var staff_account = {
            "type_id": 4,
            "user_id": user_id
        }
        if ($(this).hasClass("btnOn")) //开通状态
        {

            showDialog("staff/closeAccount" + "/" + user_id);
            var _checked = $(this);
            var count_name = _checked.parent().parent().find('.userName').text();
            var _this = $(this);
            $("#dialog .dialogBottom .btn_confirm").die("click");
            $("#dialog .dialogBottom .btn_confirm").live("click",
            function() {
                var path_setmanager ='staff/close_user';

                // alert( staff_account.user_id)
                $.post(path_setmanager, staff_account,
                function(data) {
                    //alert(data); 
                    var json = $.parseJSON(data);

                    if (json.code == 0) {
                        _this.removeClass('btnOn').addClass('btnOff');
                        hideDialog();

                    } else
				{
					alert(json.prompt_text)	
				}
                });
                //_this.removeClass('btnOn').addClass('btnOff');
                //hideDialog();
            });
            //$(this).removeClass("btnOn").addClass("btnOff");
        } else {
            var _this = $(this);
            var path_Off ='staff/open_user';
            $.post(path_Off, staff_account,
            function(data) {
                // alert(data);
                var json = $.parseJSON(data);
                if (json.code == 0) {
                    //alert(444);
                    _this.removeClass('btnOff').addClass("btnOn");
                }else
				{
					alert(json.prompt_text)	
				}
            })
            //$(this).removeClass("btnOff").addClass("btnOn");
        }
    }) 
	$('.btn_infoEdit').click(function() {
        $(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
        $('.infoTable .infoText').not('.dotEdit').each(function() {
            $(this).hide().next().removeClass('hide');
        });
    });
    $('.btn_infoEdit2').click(function() {
        $(this).addClass('hide').siblings('.btn_save2, .btn_cancel2').removeClass('hide');
        $('.setStqy label').each(function() {
            $(this).removeClass('disabled').find("input").removeAttr("disabled");
        });
    });
    $('.btn_save2').click(function() {
        $(this).addClass('hide').siblings('.btn_cancel2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
        $('.setStqy label').each(function() {
            $(this).addClass('disabled').find("input").attr("disabled", "disabled");
        });
    });
    $('.btn_cancel2').click(function() {
        $(this).addClass('hide').siblings('.btn_save2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
        $('.setStqy label').each(function() {
            $(this).addClass('disabled').find("input").attr("disabled", "disabled");
        });
    });

    $('.btn_infoCancel').click(function() {
        $(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
        $('.infoTable .infoText').not('.dotEdit').each(function() {
            $(this).show().next().addClass('hide');
        });
    });
    $('.btn_infoSave').click(function() {
        $(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
        $('.infoTable .infoText').not('.dotEdit').each(function() {
            $(this).show().next().addClass('hide');
            var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() :'';
            $(this).text(text);
        });
    });

    $(".checkbox").click(function() {
        $(".toolBar2").show();
    })

    $(".table thead input[type='checkbox']").click(function() {
        if ($(this).is(":checked")) {
            $(".table tbody input[type='checkbox']").attr("checked", "checked");
            $(".table tbody .checkbox").addClass("checked");
            var len = $(".table tbody .checked").length;
            if (len > 0) {
                $(".tabToolBar .tabToolBox").show();
            } else {
                $(".tabToolBar .tabToolBox").hide();
            }
        } else {
            $(".table tbody input[type='checkbox']").removeAttr("checked");
            $(".table tbody .checkbox").removeClass("checked");
            $(".tabToolBar .tabToolBox").hide();
        }
    })

    $(".table tbody input[type='checkbox']").live("click",
    function() {
        var len = $(".table tbody .checkbox").length;

        if ($(this).is(":checked")) {
            $(".tabToolBar .tabToolBox").show();
            var checkLen = $(".table tbody .checked").length;

            if (len == checkLen + 1) {
                $(".table thead .checkbox").addClass("checked");
                $(".table thead input[type='checkbox']").attr("checked", "checked");
            }
        } else {
            $(".table thead .checkbox").removeClass("checked");
            $(".table thead input[type='checkbox']").removeAttr("checked");
            var checkLen = $(".table tbody .checked").length;

            if (checkLen == 1) {
                $(".tabToolBar .tabToolBox").hide();
            }
        }
    })
    /*
		$("#allGroup2 .pop-box-content").treeview({
			showcheck:false,
			data:treedata
		});*/

    $(".selectGroup").click(function(event) {

        $("#allGroup2").toggle();
        event.stopPropagation();
    })

    $(".bbit-tree-node-ct li").live("click",
    function() {
        $(".part01_2").show().siblings().hide();
        $("#tree_0").removeClass("bbit-tree-selected")
    }) 
	$("#tree_0").live("click",
    function() {
        $(".part01_1").show().siblings().hide();
    })

    $(document).click(function() {
        $("#allGroup2").hide();

        //$(".datepickers").empty();	
    })
})
</script>
