function change_admin() {
    //alert(34234)
    //alert(manager_id)
    showDialog('ecologycompany/ecologyManagerPage' +'/1');
    $('#dialog #addManager').die('click');
    $('#dialog #addManager').live('click',
    function() {
        var a = $('.treeRight a');
        if (a.length == 0) {
            alert("请指定要调入的员工")
			return false;
        }
		var user_id;
        var users ='';
        $('.treeRight a').each(function() {
            users = users +'{"userid":' + $(this).attr("id") +',"user_name":"' + $(this).text() +'","orgid":' + $(this).attr("class") +',"org_name":"' + $(this).attr("name") +'","org_pid":"' + $(this).attr("orgpid") +'","org_code":"' + $(this).attr("orgcode") +'"},'; //加user_name
			user_id=$(this).attr("id");
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
        //alert(Node[0].name)
		//alert(user_id)
        var obj = {
            //"org_pid":Node[0].pId,//新建组织的父id
            "ecology_id": Node[0].id,
            //新建组织id
            //"org_code":org_code,//新组的id串
            //"org_name":Node[0].name,//新建组织名称
            //"old_user_id": manager_id,
            "user_id": user_id
        };

        //alert(users)
        var path_fold_staff ='ecology/setEcologyManager';
        $.post(path_fold_staff, obj,
        function(data) {
            //alert(data) 
			var json = $.parseJSON(data);
            if (json.code == 0) {
                var a = $('.treeRight a').text();
                //alert(a)
                $('span.dotEdit').text(a);
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
}
$(function() {
    var company_ecol_id = qiye_org_id;
    $(function() {

        $('.infoTable .selectBox').combo({
            cont:'>.text',
            listCont:'>.optionBox',
            list:'>.optionList',
            listItem:' .option'
        });
    });
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

        var value_name = $('#company_name').val(); //公司名称
        // var value_english=$('#company_english').val();//英文简称
        var value_chinese = $('#company_chinese').val(); //中文简称
        var value_quhao = $('#area_code').val(); //区号
        var value_addNum = $('#add_num').text(); //+86
        var value_phoneNum = $('#phoneNum_1').val(); //电话号码
        var value_location = $('#country_area').val(); //国家地区
        var value_introduce = $('textarea').val(); //公司介绍
        value_introduce = value_introduce.replace(/(\r\n|\n|\r)/gm,'<br/>'); // textarea 必须要把换行转换成<br/>
        var isValid_company_name = valitateCompanyName(value_name);
        // var isValid_company_english=valitateCompanyEnglish(value_english);
        var isValid_company_chinese = valitateCompanyName(value_chinese);
        var isValid_area_code = valitateAreaCode(value_quhao);
        var isValid_phoneNum = valitateTelephonNum(value_phoneNum);
        var isValid_country_area = (value_location !='') ? true: false;
        var isValid_company_introduce = valitateCompanyIntroduce(value_introduce);

        $("div label").each(function() {
            $(this).parent("div").removeClass("error");
        }) 
		$('textarea').each(function() {
            $(this).parent("div").removeClass("error");
        })

        var count = 0;
        if (!isValid_company_name) {
            //alert(1)
            $('#company_name').parent("div").addClass("error");
            count++;
        }
        //   if(!isValid_company_english)
        //    {
        //    //alert(2)
        //     $('#company_english').parent("div").addClass("error");
        //	 count++;
        //   }
        if (!isValid_company_chinese) {
            //alert(3)
            $('#company_chinese').parent("div").addClass("error");
            count++;
        }
        if (!isValid_area_code) {
            $('#area_code').parent("div").addClass("error");
            count++;
        }

        if (!isValid_phoneNum) {

            $('#phoneNum_1').parent("div").addClass("error");
            count++;
        }
        if (!isValid_country_area) {

            $('#country_area').parent("div").addClass("error");
            count++;
        }
        if (!isValid_company_introduce) {

            $('textarea').parent('div').addClass("error");
            count++;
        }

        /* if(value_addNum==null){
			  $('#add_num').parent('div').addClass("error");
			  count++;
		  }*/

        if (count > 0) {
            return false;
        } else {

            var path ='ecologycompany/valid_eco_1'; //添加PHP要抛的地址*/
            var obj1_json ='';
            var obj1 = {};
            obj1_json +='"operate_type":"1",';
            obj1_json +='"company_name":"' + value_name +'",';
            obj1_json +='"company_chinese":"' + value_chinese +'",';
            obj1_json +='"country_code":"' + value_addNum +'",';
            obj1_json +='"area_code":"' + value_quhao +'",';
            obj1_json +='"phone_number":"' + value_phoneNum +'",';
            obj1_json +='"country_area":"' + value_location +'",';
            obj1_json +='"introduce":"' + value_introduce +'",';
            obj1_json +='"org_id":"' + company_ecol_id +'"';
            obj1_json ='{' + obj1_json +'}';
            obj1 = eval('(' + obj1_json +')');
            //alert(obj1_json)
            //		{
            //					"company_name":value_name,//公司名称
            //					//"company_english":value_english,
            //                    "company_chinese":value_chinese,//中文简称
            //                     //"phoneNum":telephoneNumber,
            //					 "country_code":value_addNum,//国码
            //					 "area_code":value_quhao,//区号
            //					 "phone_number":value_phoneNum,//电话号码
            //					 "country_area":value_location,//国家地区
            //					 "introduce":value_introduce,//公司介绍
            //					 "org_id":company_ecol_id//父组织id
            //				        };
            $.post(path, obj1,
            function(data) {
                //alert(data);
                var json = $.parseJSON(data);
                if (json.code == 0) {
                    $(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
                    $('.infoTable .infoText').not('.dotEdit').each(function() {
                        $(this).show().next().addClass('hide');
                        var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : $(this).next().find("textarea").hasClass("textarea") ? $(this).next().find("textarea").val() :'';
                        $(this).text(text);
                    });
                    $('a.btn_infoSave').addClass("hide");
                    $('a.btn_infoCancel').addClass("hide");
                    $('a.btn_infoEdit').removeClass("hide");
                    var zTree = $.fn.zTree.getZTreeObj("stqyTree");
                    var treeNode = zTree.getSelectedNodes();
                    treeNode[0].name = value_name;
                    zTree.updateNode(treeNode[0]);
                    var nodes = zTree.getSelectedNodes();
                    if (nodes[0] != null) {
                        var value = [];
                        value.push(nodes[0].name);
                        while (nodes[0].pId != null) {
                            nodes  =  zTree.getNodesByParam("id",  nodes[0].pId,  null);
                            value.push(nodes[0].name);
                        }
                        var staff_depart = "";
                        //staff_depart=' <div class="bread part0">';
                        for (var i = value.length - 1; i > 0; i--) {
                            staff_depart = staff_depart +'<span>' + value[i] +'</span>&nbsp;&gt;&nbsp';
                        }
                        staff_depart = staff_depart +'<span>' + value[i] +'</span>';
                        $('.delGroup').removeClass('disabled');
                        //staff_depart=staff_depart+"</div>";
                        // $('.link_limitSet').after(staff_depart);
                        //alert(2324);
                        $('#part01 .part01_1 .bread').find('span').text('');
                        $('#part01 .part01_1 .bread').find('span').append(staff_depart);
                    }
                    //$.fn.zTree.init($("#stqyTree"), stqySetting, stqyNodes);
                }
				else
				{
					alert(json.prmopt_text)
					return false;
				}
            });
        }

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