function select_manger_type(t)
{
	var path='manager/search';
	
	var obj=
	  {
		  "keyword":'',	
		  "role_id":$(t).attr("target")
	  }
  $.post(path,obj,function(data)
   {
	   $('.infor_page').remove();
	   $('.contTitle02').after(data);
   })
}
$(function(){
        $('.selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		//初始化选中全部管理员加载列表
		select_manger_type("#all_manger");
		//表格全选
		//checkall('.table thead .checkbox', '.table tbody .checkbox', '.table .checkbox', toolBarSet);
		//表格操作条显隐及操作按钮显隐
		function toolBarSet(){
                    //alert(1);
                    var checked = $('.table .checkbox').filter(function(){return $(this).hasClass('checked');});
                    if(checked.length){
                            $('.editBtnBox').show();
                    }else{
                            $('.editBtnBox').hide();
                    }
		}
	//搜索功能
	$('#search_admin').click(function()
	  {
		  var value=$(this).parent().find("input").val();
		  var path='manager/search';
		var id=$('#all_manger').parent().find("dd.selected").attr("target");
		var reg=/\s/g;
		value=value.replace(reg,'');
		if(value == ''){
			$(this).parent().find("input").val(value);
			$(this).parent().find(".label").css("display","block");
		}
			var obj=
			  {
				  "keyword":value,	
				  "role_id":id
			  }
		  $.post(path,obj,function(data)
		   {
			   $('#self_staff').remove();
			   $('.contTitle02').after(data);
		   })
		 
	  });
	// 回车搜索
	  $('#search').keydown(function(e)
			  {
		  if(e.which == 13){
			  var value=$(this).parent().find("input").val();
			  var path='manager/search';
			  var id=$('#all_manger').parent().find("dd.selected").attr("target");
			  var reg=/\s/g;
				value=value.replace(reg,'');
				if(value == ''){
					$(this).parent().find("input").val(value);
				}
			  var obj=
			  {
					  "keyword":value,	
					  "role_id":id
			  };
			  $.post(path,obj,function(data)
					  {
				  $('#self_staff').remove();
				  $('.contTitle02').after(data);
					  });
		  }
	});
	$('#select_manger_type').live('click',function()
	  {
		
		  var path='manager/search';
		  var obj=
		  {
			  "type":$(this).attr("target")
		  }
		  $.post(path,obj,function(data)
		   {
			   $('#self_staff').remove();
			   $('.contTitle02').after(data);
		   })
	  });
    $('#self_staff thead span.checkbox').die().live('click',
    function() {
		//alert(12)
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $(' #self_staff tbody span.checkbox').removeClass("checked");
            $(" #dete_btn_admin").hide();
        } else {
            $(this).addClass("checked");
            $(' #self_staff tbody span.checkbox').addClass("checked");
            $(" #dete_btn_admin").show();
        }
    }) 
	$('#self_staff tbody span.checkbox').die().live('click',
    function() {
        //alert(1)
        if ($(this).hasClass("checked")) //选中的则去除
        {
            $(this).removeClass("checked");
            $('#self_staff thead span.checkbox').removeClass("checked");
            //alert($(' #self_staff tbody tr td span.checked').length);
            if ($('#self_staff tbody tr td span.checked').length == 0) {

                $(" #dete_btn_admin").hide();
                //alert(2)
            } else {
                $(" #dete_btn_admin").show();
                //alert(3)
                //alert(222)
                //return false;
            }
            //$(' #self_staff tbody label.checkbox').removeClass("checked");
        } else //去除的，则变为选中
        {
            $(this).addClass("checked");
            $(" #dete_btn_admin").show();
            //alert(4)
            // alert($(' #self_staff tbody tr td span.checked').length);
            // alert($(' #self_staff tbody tr td span.checkbox').length);
            if ($(' #self_staff tbody tr td span.checked').length == $(' #self_staff tbody tr td span.checkbox').length) {
                $(' #self_staff thead span.checkbox').addClass("checked");
                //alert(5)
            } else {
                $(' #self_staff thead span.checkbox').removeClass("checked");
                //alert(6)
                ///return false;
            }
            //$(' #self_staff tbody label.checkbox').addClass("checked");
        }
    })
		$('.btnDeleAdmin').click(function(){
              showDialog('manager/delManagerPage');
		});
		$('.btnAddAdmin').click(function(){
              showDialog('manager/addManagerPage');
		});
	});
