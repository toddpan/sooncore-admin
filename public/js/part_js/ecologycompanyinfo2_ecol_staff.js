$(function() {
    $('.ecol_staff .tabToolBox1 .btnDeleUser').click(function() {
        var users ='';
        showDialog('ecologycompany/ecology_partake_move');
        $('#dialog #ecol_del').die('click');
        $('#dialog #ecol_del').live('click',
        function() {
            $('.ecol_staff .table tbody span.checked').each(function() {
                if ($(this).hasClass("checked")) {
                    users = users +'' + $(this).attr("name") +',';
                }
            }) 
			var lastindex = users.lastIndexOf(',');
            if (lastindex > -1) {
                users = users.substring(0, lastindex) + users.substring(lastindex + 1, users.length);
            }
            var zTree = $.fn.zTree.getZTreeObj("stqyTree");
            var Node = zTree.getSelectedNodes();
            var obj = {
                "ecology_id": Node[0].id,
                "user_ids": users
            };

            var path ='ecologycompany/delete_partake';
            $.post(path, obj,
            function(data) {
                //  alert(data);
                var obj = {
                    "org_id": Node[0].id
                };
                var path ='ecologycompany/info2_ecol_staff';
                $.post(path, obj,
                function(data) {
                    //alert(data)
                    $('.infoCont dd').hide();
                    $('.infoCont dd.ecol_staff').remove();
                    $('.infoCont dd.qiye').after(data);
                    $('.infoCont dd.ecol_staff').show();
                    hideDialog();
                })

            })
        });
    }) 
	$('.ecol_staff .tabToolBar .btnAddUser').click(function() {
        showDialog('ecologycompany/ecologyManagerPage' +'/3');
        $('#dialog #addManager').die('click');
        $('#dialog #addManager').live('click',
        function() {
            var a = $('.treeRight a');
            if (a.length == 0) {
                alert("请指定要调入的员工") 
				return false;
            }
            var users ='';
            $('.treeRight a').each(function() {
                users = users +'{"userid":' + $(this).attr("id") +',"user_name":"' + $(this).text() +'","orgid":' + $(this).attr("nodepid") +',"org_name":"' + $(this).attr("name") +'","org_pid":"' + $(this).attr("orgpid") +'","org_code":"' + $(this).attr("orgcode") +'"},'; //加user_name
            });
            users = DelLastComma(users);
            var zTree = $.fn.zTree.getZTreeObj("stqyTree");
            var Node = zTree.getSelectedNodes();
            var id_2 = Node[0].pId;
            var org_code = "-" + Node[0].id;
            var node;
            while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
                node = zTree.getNodesByParam('id', id_2, null)[0];
                id_2 = node.pId;
                org_code ='-' + node.id + org_code;;
                // value.push(node.name);
                // id_value.push(node.id);
            }
            //alert(Node[0].id)
            var obj = {
                //"org_pid":Node[0].pId,//新建组织的父id
                "org_id": Node[0].id,
                //新建组织id
                //"org_code":org_code,//新组的id串
                //"org_name":Node[0].name,//新建组织名称
                "user_id": users
            };
            //alert(Node[0].id)
            //alert(users)
            var path_fold_staff ='ecologycompany/add_partake';
            $.post(path_fold_staff, obj,
            function(data) {
                //alert(data)
                var json = $.parseJSON(data);
                if (json.code == 0) {
                    var obj = {
                        "org_id": Node[0].id
                    };
                    var path ='ecologycompany/info2_ecol_staff';
                    $.post(path, obj,
                    function(data) {
                        //alert(data)
                        $('.infoCont dd').hide();
                        $('.infoCont dd.ecol_staff').remove();
                        $('.infoCont dd.qiye').after(data);
                        $('.infoCont dd.ecol_staff').show();
                    })
                    //var a=$('.treeRight a').text();
                    //alert(a)
                    //$('span.dotEdit').text(a);
                    /*var objN={
							"parent_orgid":Node[0].pId,
							 "org_id":Node[0].id
					 }
					
					//load_staff(objN,path_user,path_mag);*/
                    hideDialog();

                }
				else
				{
					alert(json.prmopt_text)
					return false;
				}
            })
        })
    }) 
	$('#eco_staff').click(function() {
        $('#part01 .part01_1').hide();
        var path_staff_information ='staff/modify_staff_page';
        var obj = {
            "user_id": $(this).attr("name")
        }
        //alert($(this).attr("name"));
        $.post(path_staff_information, obj,
        function(data) {
            //alert(data)
            //$('#part01 div.bread').after(data);
            $('#part01 .part01_1').after(data);

        })
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
	$('#part01 #self_staff thead span.checkbox').die().live('click',
    function() {
        if ($(this).hasClass("checked")) {
            $(this).removeClass("checked");
            $('#part01 #self_staff tbody span.checkbox').removeClass("checked");
            $("#part01 .tabToolBar .tabToolBox1").hide();
        } else {
            $(this).addClass("checked");
            $('#part01 #self_staff tbody span.checkbox').addClass("checked");
            $("#part01 .tabToolBar .tabToolBox1").show();
        }
    }) 
	$('#part01 #self_staff tbody span.checkbox').die().live('click',
    function() {
        //alert(1)
        if ($(this).hasClass("checked")) //选中的则去除
        {
            $(this).removeClass("checked");
            $('#part01 #self_staff thead span.checkbox').removeClass("checked");
            //alert($('#part01 #self_staff tbody tr td span.checked').length);
            if ($('#part01 #self_staff tbody tr td span.checked').length == 0) {

                $("#part01 .tabToolBar .tabToolBox1").hide();
                //alert(2)
            } else {
                $("#part01 .tabToolBar .tabToolBox1").show();
                //alert(3)
                //alert(222)
                //return false;
            }
            //$('#part01 #self_staff tbody label.checkbox').removeClass("checked");
        } else //去除的，则变为选中
        {
            $(this).addClass("checked");
            $("#part01 .tabToolBar .tabToolBox1").show();
            //alert(4)
            // alert($('#part01 #self_staff tbody tr td span.checked').length);
            // alert($('#part01 #self_staff tbody tr td span.checkbox').length);
            if ($('#part01 #self_staff tbody tr td span.checked').length == $('#part01 #self_staff tbody tr td span.checkbox').length) {
                $('#part01 #self_staff thead span.checkbox').addClass("checked");
                //alert(5)
            } else {
                $('#part01 #self_staff thead span.checkbox').removeClass("checked");
                //alert(6)
                ///return false;
            }
            //$('#part01 #self_staff tbody label.checkbox').addClass("checked");
        }
    })
    /* $('#part01 #self_staff label.checkbox').die('mouseup');
		 $('#part01 #self_staff label.checkbox').live('mouseup',function()
		   {
				$('#part01 #self_staff').removeClass("sel");
				$(this).parents("table").addClass("sel");
					 // alert(334);
				var count1=0;
					  //alert($(this).attr("class"))
					  //$(this).attr("target","1");
				 if($(this).parent().next().text()=="姓名")
				  {
				  	alert(222222)
					 if(!$(this).hasClass("checked"))
					 {
					   $(this).addClass("checked");
					   $(this).parents("thead").next().find("label.checkbox").addClass("checked");
					   $("#part01 .tabToolBar .tabToolBox").show();
					 }
					 else
					 { 
					   $(this).removeClass("checked");
					   $(this).parents("thead").next().find("label").removeClass("checked");
					   $('#part01 #self_staff thead label:first').removeClass("checked");
					   $("#part01  .tabToolBar .tabToolBox").hide();
					 }
					  //alert($(this).parents("thead").next().find("label").attr("class"));
				  }
			   else
			   {
				 if(!$(this).hasClass("checked"))
					 {//
					  
					   $(this).addClass("checked");
					  // alert($(this).parentsUntil('tr').parent().siblings().find("label").filter(".checked").length+1)
					  // alert(Math.floor(count.length/2-1))
					 // alert($('#part01 #self_staff tbody tr label.checked').length)
					  //alert($('#part01 #self_staff tbody tr label.checkbox').length)
				if($('#part01 #self_staff tbody tr label.checked').length==$('#part01 #self_staff tbody tr label.checkbox').length)
							{//alert(2323)

							 $('#part01 #self_staff thead label:first').addClass("checked");
						////  j++
							}
					   //$(this).parents("thead").next().find("label").addClass("checked");
						$("#part01 .tabToolBar .tabToolBox").show();
					}
				 else
					 {
					   // $('#part01 .tabToolBox').hide();
					   
					  
					   $(this).removeClass("checked");
					   $('#part01 #self_staff thead label:first').removeClass("checked"); 
					  //alert(1111)
					   //$(".tabToolBar .tabToolBox").hide();
					   //alert($('#part01 #self_staff  tbody tr label.checked').length)
					   if($('#part01 #self_staff tbody tr label.checked').length==0)
					   {
					   	 $("#part01 .tabToolBar .tabToolBox").hide();
						 
					   }
					   else
					   {
					   	 $("#part01 .tabToolBar .tabToolBox").show();
					   }
					   //$(this).parents("thead").next().find("label").removeClass("checked");
					  
					 }
			     }
			   })*/
})