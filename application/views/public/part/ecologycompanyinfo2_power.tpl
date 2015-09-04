<style type="text/css">
<!--
body {
	background-color: #006666;
}
-->
</style><dd class="right">
  <div class="toolBar2">
  		<a class="btnGray btn btn_infoEdit2" >
			<span class="text">编辑信息</span><b class="bgR"></b>
		</a> 
		<a class="btnBlue hide btn_save2" >
			<span class="text">保存</span><b class="bgR"></b>
		</a> 
		<a class="btnGray btn hide btn_cancel2" >
		  <span class="text">取消</span><b class="bgR"></b>
		</a>
  </div>
  <!-- end tabToolBar -->
  <div class="setStqy" id="setStq2">
  	{$j=1}
	{$length=$permissions_label_name|@count}
	{foreach $permissions_label_name as $name}
		{if $length==$j} 
			<label class="checkbox {if $name.checked}checked{/if} disabled {$name.key}">
				<input type="checkbox" disabled="disabled" checked="{if $name.checked}checked{/if}" />{$name.name}
			</label>
		{else}
			<label class="checkbox {if $name.checked}checked{/if} disabled {$name.key}">
				<input type="checkbox" disabled="disabled" checked="{if $name.checked}checked{/if}" />{$name.name}
			</label>
			<br />
		{/if}
	{/foreach}
  </div>
</dd>
<script type="text/javascript">
//初始化权限
//var data_json = '<?php echo json_encode($power_arr); ?>';
//var data_obj = $.parseJSON(data_json);
//set_ecology_power(data_obj);
$(function() {
    $('#setStq2 label.checkbox').toggle(function() {
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
        //保存权限
        var obj = save_ecology_power();
        var value = {
            "power_json": obj,
            "org_id": <?php echo $org_id; ?>
        };
		var _this=this;
        var path = "ecologycompany/info2_save_power";
        $.post(path, value,
        function(data) {
            //alert(data);
            var json = $.parseJSON(data);
            if (json.code == 0) {
					_this.hide();
				}
				else
				{
					alert(json.prompt_text)	
				}
        })
    });
    $('.btn_cancel2').click(function() {
        set_ecology_power(data_obj);
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
            var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : '';
            $(this).text(text);
        });
    });

    $(".checkbox").click(function() {
        $(".toolBar2").show();
    });
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
