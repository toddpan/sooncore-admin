// JavaScript Document
//企业生态网页3
var obj3_json = '';
var obj3 = {};
var tag='';
function DelLastCommaMy(value) //字符串转换成JSON串
{
    var staff_tag_post = value;
    var lastIndex = staff_tag_post.lastIndexOf(',');
    if (lastIndex > -1) {
        staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
    }
    staff_tag_post = '{' + staff_tag_post + '}';
    return staff_tag_post;
}
function input_regex()
{
	var value;
	tag='';
	var count=0;
	var firstname=$('#chinese_name').find("input:eq(0)").val();
	var lastname=$('#chinese_name').find("input:eq(1)").val();
	var sex='';
	if($('#sex').find("label.radio_on input").attr("id")=="xb_0")
	{
		sex="1";
	}
	else if($('#sex').find("label.radio_on input").attr("id")=="xb_1")
	{
		sex="2";
	}
	//var local_country=$("#add_country").text();
	var local_address=$('#location').find("input").val();
	var code_prev=$("#add_num_1").text();
	var telephone=$('#telephone_number').find('input').val();
	var usercount=$('#usercount').find("input").val();
	var status=$('#status').find("input").val();
	var email=$('#email').find("input").val();
	if(!valitateStaffName(firstname))
	{
		count++;
		//alert(9)
		$('#chinese_name div:eq(0)').addClass("error");
		
	}
	if(!valitateStaffName(lastname))
	{
		count++;
		//alert(8)
		$('#chinese_name div:eq(2)').addClass("error");
	}
	if(count==0)
	{
		tag=tag+'"firstname":"'+firstname+'",';
		
		tag=tag+'"lastname":"'+lastname+'",';
	}
	if(sex=="")
	{
		count++;
		a//lert(7)
		$('#sex').append("<label style='color:red'>请选择员工性别</label>");
	}
	else
	{
		tag=tag+'"sex":"'+sex+'",';
	}
	if(email=="")
	{
		count++;
		a//lert(7)
		$('#email').append("<label style='color:red'>请填写联系邮箱</label>");
	}
	else
	{
		tag=tag+'"email":"'+email+'",';
	}
	/*if(!valitateStaffName(local_country))
	{
		count++;
		alert(6)
		$('#add_country').next().addClass("error");
		
	}*/
	if(!valitateStaffName(local_address))
	{
		count++;
		//alert(5)
		$('#location .inputBox').addClass("error");
	}
	if(count==0)
	{
		//tag=tag+'{"name":"location","value_1":"'+local_country+'"}';
		tag=tag+'"location":"'+local_address+'",';;
	}
	//alert(code_prev)
	if(code_prev=='')
	{
		count++;
		//alert(4)
		$('#add_num_1').next().addClass("error");
		
	}
	if(!valitateTelephonNum(telephone))
	{
		count++;
		//alert(3)
		$('#telephone_number .inputBox').addClass("error");
	}
	if(count==0)
	{
		tag=tag+'"add_num_1":"'+code_prev+'",';
		tag=tag+'"telephone_number":"'+telephone+'",';
	}
	//alert(usercount)
	if(!valitateStaffName(usercount))
	{
		count++;
		//alert(2)
		$('#usercount').find("div").addClass("error");
		
	}
	if(status=="")
	{
		count++;
		//alert(1)
		$('#status').find("div").addClass("error");
	}
	if(count==0)
	{
		
		tag=tag+'"usercount":"'+usercount+'",';
		tag=tag+'"status":"'+status+'",';;
	}
	$('#creater_three #self_label tr td:eq(1)').each(function()
	{
		value=$(this).find("input").val();
		var regex//=label_regex[$(this).attr("target_name")];
		if(!regex.test(value))
		{
			count++;
			//alert(99)
			$(this).addClass("error");
			
		}
		else
		{
			tag=tag+'"'+$(this).attr("target_name")+'":"'+value+'",';
		}
		
	})
	return count;
}
function nextStep3() {
    $('.inputBox').each(function() {
        $(this).removeClass("error");
    })
	var count=input_regex(); 
    //alert(count)
	if(count!=0)
	{
		return false;
	}
	else
	{
		var post_json;
		//alert(tag);
		tag=DelLastCommaMy(tag);
		post_json = '{"sys_tag":'+tag+'}'; //组织
		//alert(post_json)
		obj3_json = post_json;
		var path = "ecology/valid_eco_3";
		var obj3 = {
			"user_json": obj3_json
		}
		$("#checking").show();
		$.ajax({
				url: path,
				timeout: 6000,
				type: "POST",
				data: obj3,
				success: function(data) {
					var json = $.parseJSON(data);
					if (json.code == 0) {
							$('#creater_three').hide();
							$('#creater_four').show();
							var back_next=$('#prev_next');
							$('#prev_next').remove();
							$('#creater_four').after(back_next);
							//$('#prev_next').find("a:eq(1)").show();
							$('#head_style a').removeClass("selected");
							$('#head_style a').removeClass("current");
							$('#head_style').find('a:eq(3)').addClass("selected");
							$('#head_style').find('a:eq(3)').addClass("current");
							$('#head_style .innerBar').css("width","100%")
							$("#checking").hide();
							var org_zNode = [];
							var org_first_path ="organize/get_first_org_user";
							var cost_get_staff="organize/get_next_orguser_list";//组织结构和成本中心部分的调入员工
							   // var org_first_path = '<?php echo site_url('organize/get_first_org_user')?>';
							$.post(org_first_path,[],
								function(data) {
									org_zNode=$.parseJSON(data);
									create_node(org_zNode);
									//$.fn.zTree.init($("#costtreeLeft"),costSetting, org_zNodes);
									$.fn.zTree.init($("#org_treeLeft"), companySetting, org_zNode);
									var dgtree = $.fn.zTree.getZTreeObj("org_treeLeft");
									var node = dgtree.getNodes();
									treeNode = node[0];
									if (treeNode.open == true) {
										 //post_add_staff(treeNode[0],cost_get_staff,zTree,1);
										add_staff_company(dgtree, treeNode, cost_get_staff);
									}
							
									//alert(cost_zNodes)
								});
							//loadCont('<?php // echo site_url('ecologycompany/setEcologyCompany')?>');
						  
					} else {
						$("#" + json.error_id + "").parent().next().addClass("error");
						$("#checking").hide();
						return ;
					}
				   
				},
				error: function() { //$('.ldapSetBox1 .error1').show();
					alert("请求服务器失败")
					$("#checking").hide();
				}
			});
	}
    
}
function select_sex(t)
{
	if(!$(t).hasClass('radio_on')) 
	{
		if($(t).next().hasClass("radio"))
		{
			$(t).next().removeClass('radio_on');
		}
		else if($(t).prev().hasClass("radio"))
		{
			$(t).prev().removeClass('radio_on');
		}
		$(t).addClass('radio_on');
	}
}
$(function() {
	$('#creater_three #sex label').click(function()
	  {
		  $('#creater_three #sex label.radio').removeClass("radio_on");
		  $(this).addClass("radio_on");
		  
	  })
	 $('#creater_three table.infoTable .selectBox').toggle(function(e) {
        $(this).find(".optionBox").addClass("_show");
        if ($(e.target).hasClass("option")) {
            $("#add_num_1").addClass("selected");
            $('#creater_three table.infoTable .selectBox dd').removeClass("selected");
            $(e.target).addClass("selected");
            //alert(2)
		}
    },
    function(e) {
      $(this).find(".optionBox").removeClass("_show");
        if ($(e.target).hasClass("option")) {
            $(e.target).parent().parent().prev().removeClass('selected');
            $(e.target).parent().parent().prev().addClass('selected');
            $(e.target).parent().parent().prev().text($(e.target).text());
            $(e.target).parent().parent().prev().attr("name", $(e.target).text());
            //$('.ldapSetBox table.infoTable .selectBox .optionBox').adddClass("_hide");
            $('#creater_three table.infoTable .selectBox dd').removeClass("selected");
            $(e.target).addClass("selected");
        }
    })

    $('#creater_three table.infoTable .selectBox dd').mouseenter(function() {
        $('#creater_three table.infoTable .selectBox dd').removeClass("hover");
        $(this).addClass("hover");
    }).mouseleave(function() {
        $(this).removeClass("hover");
    }) 
	
});