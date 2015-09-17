/*
 * 管理员管理中点击保存管理员的员工信息按钮
 */
function staff_save_infor(t, user_id, role_id)
	{
		var firstname 		= $('#chinese_name').val(); 						// 姓名
		var usercount 		= $('#usercount').val(); 							// 管理员账号
		var sex 			= $('#sex').find("label.radio_on").attr("target"); 	// 性别
		var position		= $('#position').val();								// 职位
		var code_prev 		= $("#add_num_1").text(); 							// 国码
		var telephone 		= $('#phoneNum').val(); 							// 手机号码
		var department 		= $('#ztree_admin').attr('ids'); 					// 部门
		var value			= '';												// 自定义标签值
		var tag 			= '';												// 所有标签及其标签值组成的json串
// 		var local_address 	= $('#location').val(); 							// 办公地址
		
		// 验证姓名
		if(!valitateStaffName(firstname))
		{
			$('#chinese_name').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"chinese_name":"' + firstname + '"},';
		}
		
		// 验证性别
		if(sex == "")
		{
			$('#sex').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"sex":"' + sex + '"},';
		}
		
		// 验证办公地址
		//		if(location=="")
		//		{
		//			count++;
		//			$('#location').parent().addClass("error");
		//		}
		//		else
		//		{
		//			tag=tag+'{"location":"'+location+'"},';
		//		}
		
		// 验证国码
		if(code_prev == '')
		{
			$('#add_num_1').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"add_num_1":"' + code_prev + '"},';
		}
		
		// 验证手机号码
		if(!valitateTelephonNum(telephone))
		{
			$('#phoneNum').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"phoneNum":"' + telephone + '"},';
		}
		
		// 验证管理员账号
		if(!valitateStaffName(usercount))
		{
			$('#usercount').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"usercount":"' + usercount + '"},';
		}
		
		// 验证职位
		if(position == "")
		{
			$('#position').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"position":"' + position + '"},';
		}
		
		// 验证部门
		if(department == '')
		{
			$('#ztree_admin').parent().addClass("error");
			return false;
		}
		else
		{
			tag = tag + '{"department":"' + department + '"},';
		}
		
		// 自定义标签
		$('#creater_three #self_label tr td:eq(1)').each(function()
		{
			value = $(this).find("input").val(); // 自定义标签的值
			if(value == '')
			{
				$(this).addClass("error");
				return false;
			}
			else
			{
				tag = tag + '{"' +$(this).attr("target_name") + '":"' + value + '"},';
			}
		});
		
		// 所有标签组成的json串（去掉最后一个”，“）
		tag = tag.substring(0, tag.length - 1);
		tag = '[' + tag + ']';
		
		// 以post方式将数据抛给PHP
		var obj = 
		{
			"user_id":user_id,
			"user_json":tag,
			"user_role_id":role_id
		}
		var path = 'manager/saveAdminInfo';
		$.post(path, obj, function(data)
		{
			if(data.code == 0)
			{
				$(t).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
				$(t).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
					$(t).show().next().addClass('hide');
					var text = $(t).next().hasClass('inputBox') ? $(t).next().find('.input').val() : $(t).next().hasClass('selectBox') ? $(t).next().find('.text').text() : '';
					$(t).text(text);
				});
			}
			else
			{
				alert(data.msg);
			}
		},'json');
}


function showMenu(t)
{
	if($(t).parent().find(".ztree").html()=='')
	{
		
		$(t).parent().parent().prev().find("dd.selected").trigger("click");
	}
	 
	$(t).parent().find('.selectOptionBox').show();
}
//员工详情下的部门树
function showMenu_staff_info(t)
{
	
	 $(t).parent().find('.selectOptionBox').show();
}

// 员工权限的确定/取消按钮
$('#dd2 label.checkbox').toggle(function(e)
{
	$("#dd2 .toolBar2").show();
	var t=$(e.target);
	if(!t.hasClass('form-text'))
	{
		//alert(555);
		if($(this).hasClass('checked'))
		{
			//alert(666);
			$(this).removeClass('checked');
			return false;
		}
		else
		{
			//alert(777);
			if(!$(this).hasClass('checked'))
			{
				//alert(888);
				$(this).addClass('checked');
				return false;
			}
		}
		//return false;

	}
},function(e)
{
	 $("#dd2 .toolBar2").show();
	var t=$(e.target);
	if(!t.hasClass('form-text'))
	{
		//alert(111);
		if(!$(this).hasClass('checked'))
		{
			//alert(222);
			$(this).addClass('checked');
			return false;
		}
		else
		{
			//alert(333);
			if($(this).hasClass('checked'))
			{
				//alert(444);
				$(this).removeClass('checked');
				return false;
			}
		}

	}

});
$('#dd2 dl.radio-dl label.radio').live('click',function()
{
	$(this).parent().find('label.radio_on').removeClass('radio_on');
	if(!$(this).hasClass('radio_on'))
	{
		$(this).addClass('radio_on');
	}
	 $("#dd2 .toolBar2").show();
});



//点击权限中的保存
function save_admin_right(user_id)
{
	var obj=right_save();
	var value={
			"power_json":obj,
			"user_id":user_id
		};
		var path = "staff/save_user_power";
		$.post(path,value,function(data){
			$("#part1").removeClass("value_change");
			var json=$.parseJSON(data);
			if(json.code==0)
			{
				$('#dd2 .toolBar2').hide();
				$('.groupLimit').hide();
			}
			else
			{
				alert(json.prompt_text)
			}
		});
}
$('#dd2 .toolBar2 a:eq(1)').click(function()
		{
			$("#part1").removeClass("value_change");
			$('.infoNav li:eq(1)').trigger("click");
		})

//点击管理员权限中的保存
function save_manger_detail(user_id){
	
			var obj='';
			var manager_info='';
			var count=0;
		$('#editBox03 table').each(function()
		{
			
			var role=$(this).find('#juese .optionList dd.selected').attr("target");
			var first=$(this).find('#admin_weidu .optionBox dd.selected').attr("target");
			if(first==2 || first==4)
			{
				var ids=$(this).find('.ztree').attr("ids");
			}
			else if(first==3)
			{
				if($(this).find('#departmentSel').parent().find('li.checked').length==0)
				{
					count++;
					$('.ztree').parent().addClass("error");
				}
				$(this).find('#departmentSel').parent().find('li.checked').each(function()
				{
					var ids=ids+$(this).find("label").text()+',';
				})
			}
			
			if(first==2)
			{
				weidu_1='{"key":"department","value":"'+ids+'"}';
			}
			else if(first==4)
			{
				weidu_1='{"key":"costcenter","value":"'+ids+'"}';
			}
			else
			{
				weidu_1='{"key":"region","value":"'+ids+'"}';
			}
			
			var second=$(this).find('#weidu02 .optionList dd.selected').attr("target");
			var ids1='';
			if(second==2 || second==4)
			{
				ids1==$(this).find('#admin_weidu_1 .ztree').attr("ids");
			}
			else if(second==3)
			{
				if($(this).find('#admin_weidu_1 #departmentSe2').parent().find('li.checked').length==0)
				{
					count++;
					$('#ztree3').parent().addClass("error");
				}
				$(this).find('#admin_weidu_1 #departmentSe2').parent().find('li.checked').each(function()
				{
					ids1=ids1+$(this).find("label").text()+',';
				})
			}
			
			if(second==2)
			{
				weidu_2='{"key":"department","value":"'+ids+'"}';
			}
			else if(second==4)
			{
				weidu_2='{"key":"costcenter","value":"'+ids+'"}';
			}
			else
			{
				weidu_2='{"key":"region","value":"'+ids+'"}';
			}
			
			manager_info += '[{"user_id":'+ user_id +',"role_id":'+ role +',"w1":'+ weidu_1 +',"w2":'+ weidu_2 +'}],';
			
			//obj='{role:'+role+',first:'+first+',ids:'+ids+',second:'+second+',ids1:'+ids1+'},'+obj;
		})
		manager_info = '[' + manager_info + ']';
		if(count!=0)
		{
			
		}
		
		var path_save='manager/modifyManager';
		var obj={
				"manager_infos":manager_info
			//"user_id":user_id,
			//"role_id":stat,
			//"w1":ids,
			//"w2":ids1
		};
		//var obj='{role:+'role'+,first:+'first'+,ids:+'ids'+,second:+'second'+,ids1:+'ids1'+}';
		$.post(path_save,obj,function(data)
		{
			if(data.code==0)
			{
				$('.btn_infoCancel2, .btn_infoSave2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
				$('#infoBox03').show();
				$('#editBox03').hide();
			}
		},'json')
}

function toggleAccount(t){
		if($(t).find("span.text").text()=="关闭帐号") {
		showDialog('弹窗_关闭sooncore平台帐号.html');
			var _this = $(t);
			$("#dialog .dialogBottom .btn_confirm").live("click",function(){
				_this.find("span.text").text("开启帐号");
				hideDialog();
			})
		}
		else {
			$(t).find("span.text").text("关闭帐号");
		}
}

	
	//点击选中维度值
function reselect_weidu(target,t,id_ztree,id_weidu,id_weidu_first)
{
	
	var ztree=$('#'+id_ztree+'');
	var first_weidu=$('#'+id_weidu+'');
	var weidu=$('#'+id_weidu_first+'');
	var weidu02=$('#'+id_weidu_first+'').parent().parent().next().find('#weidu02');
	var hideBar02=$('#'+id_weidu_first+'').parent().parent().next();
	var range;
	var path=''; 
	var t1=-1;
	
			   ztree.html('');
			   first_weidu.find('#departmentSel').val('');
			  if(!hideBar02.hasClass("hide"))
			  {				 
				  t1=weidu02.find('dd.selected').attr("target");
				 weidu02.find('dd.hide').removeClass("hide");
				  
			  }
			   if(target!="1")
			   { //alert($(this).attr("target"))
				   if(target=="2")
				   {
					   
					   range="department";
					   path="organize/get_org_tree";
					  first_weidu.find('#departmentSel').val('请选择管理部门');
					   if(target==t1)
					   {
						  // $('#weidu02 
						   weidu02.find('span').text("请选择第二管理维度").attr({"title":"请选择第二个管理维度"});
						   weidu02.parent().next().hide();
						   //alert(1212)
					   }
					   else{
						   weidu02.find('dd[target="2"]').addClass("hide");
					   }
					  
				   }
				   else if(target=="3")
				   {
					  
					   range="area"; 
					  first_weidu.find('#departmentSel').val('请选择管理地区');
					   path="manager/getRegion";
					    if(target==t1)
					   {
						  // alert(3)
						   weidu02.find('span').text("请选择第二管理维度").attr({"title":"请选择第二个管理维度"});
						   weidu02.parent().next().hide();
					   }
					    else{
						   weidu02.find('dd[target="3"]').addClass("hide");
					   }
				   }
				   else
				   {
					   
					   range="costcenter";
					   first_weidu.find('#departmentSel').val('请选择管理成本中心');
					    if(target==t1)
					   {
						   //alert(4)
						    weidu02.find('span').text("请选择第二管理维度").attr({"title":"请选择第二个管理维度"});
							weidu02.parent().next().hide();
					   }
					    else{
						   weidu02.find('dd[target="4"]').addClass("hide");
					   }
				   }
				   first_weidu.removeClass("hide");
				  
				  
				   $.post(path,[],function(data)
					  {
						  
						  if(range=="area")
						  {
							  var html='';
							  var dat=data.data.ret_data;
							 
							  for(var i=0;i<dat.length;i++)
							  { //alert(dat[0].city)
								 html=html+'<li onclick="first_area(this)"><a ><label><input name="" type="checkbox" value="" />'+dat[i].city+'</label>'+
								 '</a></li>';
							  }
							  //alert(html)
							 ztree.append(html);
						  }else
						  {
							  var zNodes=$.parseJSON(data.prompt_text);
							  var leng=zNodes.length;
							   for(var i=0; i<leng;i++)
								{
									//cost_zNodes.push(childNodes[i]);
										if(!zNodes[i].isParent)
										{
											zNodes[i].nocheck=false;
											
										}
								}
								//alert(1)
								//zNodes=[{id:1,pId:0, name:"海尔",open:true,nocheck:true}];
							   $.fn.zTree.init(ztree,weiduSetting,zNodes);
						  }
						 
					  },'json')
			   }
			   else
			   {
				   $(t).parent().parent().hide();
			   }
		   	
}
//点击选择第二维度
function reselect_weidu_1(target,t,id_ztree,id_weidu,id_weidu_second,e)
{
	var ztree=$('#'+id_ztree+'');
	var second_weidu=$('#'+id_weidu+'');
	var ran='';
	var path='';
	second_weidu.find('#departmentSel').val('');
	if(target!="1")
	   { //alert($(this).attr("target"))
		   if(target=="2")
		   {
			   ran="department";
				path="organize/get_org_tree";
				second_weidu.find('#departmentSel').val('请选择管理部门');
				second_weidu.parent().show();
			  
		   }
		   else if(target=="3")
		   {
			   ran="area"; 
			
			  second_weidu.find('#departmentSel').val('请选择管理地区');
			second_weidu.parent().show();
			  path="manager/getRegion";
		   }
		   else
		   {
			   ran="costcenter";
			   //create_weidu_2.parent().next().find('#departmentSel').val('请选择管理成本中心');
			    second_weidu.find('#departmentSel').val('请选择管理成本中心');
				second_weidu.parent().show();
		   }
	   }
	   $.post(path,[],function(data)
		  {
			  
			  if(ran=="area")
			  {
				  var html='';
				  var dat=data.data.ret_data;
				 
				  for(var i=0;i<dat.length;i++)
				  { //alert(dat[0].city)
					 html=html+'<dd class="option" target="1" onclick="area_select(this)">'+dat[i].city+'</dd>';
				  }
				  //alert(html)
				  html="<dl>"+html+"</dl>";
				 ztree.append(html);
			  }else
			  {
				  var zNodes=$.parseJSON(data.prompt_text);
				  var leng=zNodes.length;
				   for(var i=0; i<leng;i++)
					{
						//cost_zNodes.push(childNodes[i]);
							if(!zNodes[i].isParent)
							{
								zNodes[i].nocheck=false;
								
							}
					}
					//zNodes=[{id:1,pId:0, name:"海尔",open:true,nocheck:true}];
				   $.fn.zTree.init(ztree,wdSetting,zNodes);
			  }
			 
		  },'json')
							
		e.cancelBubble = true;
}
//点击选择员工角色
function reselect_role(t)
{
	if($(t).attr("target")=="1")
	{
		$(t).parent().parent().hide();
	}
	else
	{
		if($(t).attr("target")=="4")
		{
			$(t).parentsUntil("table").parent().find('.hideBar01').hide();
			$(t).parentsUntil("table").parent().find('.hideBar02').hide();
		}
		else
		{
			$(t).parentsUntil("table").parent().find('.hideBar01').show();
			$(t).parentsUntil("table").parent().find('.hideBar02').show();
		}
	}
}
//选择性别
function sex_select(t)
{
	if(!$(t).hasClass("radio_on"))
	{
		$(t).parent().find("label.radio_on").removeClass("radio_on");
		$(t).addClass("radio_on")
	}
}
	$(function(){
	//点击员工权限
	$('.infoNav li:eq(1)').click(function(){
		if(!$('.groupLimit h3').hasClass("setTitle"))
		{
			var obj={
				"user_id":$('#manger_detail_page .personName').attr("user")
			};
			//alert(obj)
			var path_power= "staff/get_user_power";
			$.post(path_power,obj,function(data)
				{
				//alert(data);
					var value= $.parseJSON(data);
					org_user_right(value.other_msg.power);
				});
			}
	})
	//点击后退
	$('#admin_goback').click(function()
	  {
		  
		  $('#manger_detail_page').remove();
		  $('.contTitle02').show();
		  $('.infor_page').show();
		  var path='manager/search';	
			var obj=
			  {
				  "keyword":'',	
				  "role_id":$('#all_manger').parent().find("dd.selected").attr("target")
			  }
		  $.post(path,obj,function(data)
		   {
			   $('.infor_page').remove();
			   $('.contTitle02').after(data);
		   })
		  
	  })
	
	$('#departmentSel').click(function()
	   {
		   $(this).next().next().show();
	   })
	/*$('#departmentSel').click(function()
		{
			
			if($(this).parent().parent().prev().find(".optionList").find("dd.selected").attr("target")==2)
			{
				var ztree=$(this).parent().find(".ztree").attr("id");
				$("#"+ztree+"").html('');
				var path="organize/get_org_tree";
				 $.post(path,[],function(data)
					  {
						 
						  var zNodes=$.parseJSON(data.prompt_text);
						  var leng=zNodes.length;
						   for(var i=0; i<leng;i++)
							{
								//cost_zNodes.push(childNodes[i]);
									if(!zNodes[i].isParent)
									{
										zNodes[i].nocheck=false;
										
									}
							}
						   $.fn.zTree.init($("#"+ztree+""),weiduSetting,zNodes); 
					  },'json')
			}
			else if($(this).parent().parent().prev().find(".optionList").find("dd.selected").attr("target")==3)
			{
				var  path="manager/getRegion";
				 $.post(path,[],function(data)
					  {
							  var html='';
							  var dat=data.data.ret_data;
							  for(var i=0;i<dat.length;i++)
							  { //alert(dat[0].city)
								 html=html+'<li onclick="first_area(this)"><a ><label><input name="" type="checkbox" value="" />'+dat[i].city+'</label>'+
								 '</a></li>';
							  }
							  //alert(html)
							 $("#"+ztree+"").html(html);
						},'json')
			}
		})*/
		$('#departmentSe2').click(function()
		{
			
			if($(this).parent().parent().prev().find(".optionList").find("dd.selected").attr("target")==2)
			{
				var ztree=$(this).parent().find(".ztree").attr("id");
				$("#"+ztree+"").html('');
				var path="organize/get_org_tree";
				 $.post(path,[],function(data)
					  {
						 
						  var zNodes=$.parseJSON(data.prompt_text);
						  var leng=zNodes.length;
						   for(var i=0; i<leng;i++)
							{
								//cost_zNodes.push(childNodes[i]);
									if(!zNodes[i].isParent)
									{
										zNodes[i].nocheck=false;
										
									}
							}
						   $.fn.zTree.init($("#"+ztree+""),weiduSetting,zNodes); 
					  },'json')
			}
			else if($(this).parent().parent().prev().find(".optionList").find("dd.selected").attr("target")==3)
			{
				var  path="manager/getRegion";
				 $.post(path,[],function(data)
					  {
							  var html='';
							  var dat=data.data.ret_data;
							  for(var i=0;i<dat.length;i++)
							  { //alert(dat[0].city)
								 html=html+'<li onclick="first_area(this)"><a ><label><input name="" type="checkbox" value="" />'+dat[i].city+'</label>'+
								 '</a></li>';
							  }
							  //alert(html)
							 $("#"+ztree+"").html(html);
						},'json')
			}
		});
		$('.selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		checkbox();
		
		$('.infoNav li').click(function(){
			
			var ind = $(this).index();
			//var len = $(this).parent("ul").children().length;
			
			//if(ind<len-1) {
			$(this).addClass('selected').siblings().removeClass('selected');
			$('.infoCont > dd').eq(ind).show().siblings().hide();
			if(ind==0)
			{
				var path="organize/get_org_tree";
				 $.post(path,[],function(data)
					  {			  			 
							  var zNodes=$.parseJSON(data.prompt_text);
							  var leng=zNodes.length;
							   for(var i=0; i<leng;i++)
								{
									//cost_zNodes.push(childNodes[i]);
										if(!zNodes[i].isParent)
										{
											zNodes[i].nocheck=false;
											
										}
								}
								//zNodes=[{id:1,pId:0, name:"海尔",open:true,nocheck:true}];
							   $.fn.zTree.init($('#ztree_admin'),wdSetting,zNodes);
						 
						 
					  },'json')
			}
			//}
		});
		
		$('.btn_infoEdit').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
			$(this).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
				if(!$(this).hasClass('userCount'))
				{
					$(this).hide().next().removeClass('hide');
				}
				
			});
		});
		$('.btn_infoCancel').click(function(){
			$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
			$(this).parents('dd').find('.infoTable .infoText').not('.dotEdit').each(function(){
				$(this).show().next().addClass('hide');
			});
		});
		
		$('.btn_infoEdit2').click(function(){
			$(this).addClass('hide').siblings('.btn_infoSave2, .btn_infoCancel2').removeClass('hide');
			$('#infoBox03').hide();
			$('#editBox03').show();
		});
		$('.btn_infoCancel2').click(function(){
			$('.btn_infoCancel2, .btn_infoSave2').addClass('hide').siblings('.btn_infoEdit2').removeClass('hide');
			$('#infoBox03').show();
			$('#editBox03').hide();
		});
		
		/*$('#juese').combo({
			redata:true,
			changedFn:function(){
					var val = $('#juese').find('input').val();
					if(val == 2 || val == 3){
						$('#editBox03 .infoTable:eq(0) .hideBar01').removeClass('hide');
					}else{
						$('#editBox03 .infoTable:eq(0) .hideBar01, #editBox03 .infoTable:eq(0) .hideBar02').addClass('hide');
					}
				}
		});
		
		
		
		$('#weidu01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu01');
					var val = _this.find('input').val();
					//console.log(val);
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					
					if(val > 1) { 
						_option.eq(val-2).removeClass('hide');
						$("#weidu02").parent().siblings("td").children().addClass("hide");
						$("#weidu02 .text").attr({"title":"请选择第二个管理维度"}).text("请选择第二个管理维度");
						$("#weidu02 input").val("1");
						$("#weidu02 dd.option").eq(val-1).hide().siblings().show().removeClass("selected");
					}
					//$('.infoTable .hideBar02').addClass('hide');
					$('#editBox03 .infoTable:eq(0) .hideBar02').removeClass('hide');
				}
		});*/
		
		/*$('#weidu02').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu02');
					var val = _this.find('input').val();
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
				}
		});*/
		
		$("#selectOption2").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#locationSel").attr("value",val);
		})
		
		$("#selectOption3").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#centerSel").attr("value",val);
		})
		
		$('#juese2').combo({
			redata:true,
			changedFn:function(){
					var val = $('#juese2').find('input').val();
					if(val == 2 || val == 3){
						$('#editBox03 .infoTable:eq(1) .hideBar01').removeClass('hide');
					}else{
						$('#editBox03 .infoTable:eq(1) .hideBar01, #editBox03 .infoTable:eq(1) .hideBar02').addClass('hide');
					}
				}
		});
		
		
		$('#weidu01_01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu01_01');
					var val = _this.find('input').val();
					//console.log(val);
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					
					if(val > 1) { 
						
						_option.eq(val-2).removeClass('hide');
						$("#weidu02_01").parent().siblings("td").children().addClass("hide");
						$("#weidu02_01 .text").attr({"title":"请选择第二个管理维度"}).text("请选择第二个管理维度");
						$("#weidu02_01 input").val("1");
						$("#weidu02_01 dd.option").eq(val-1).hide().siblings().show().removeClass("selected");
					}
					//$('.infoTable .hideBar02').addClass('hide');
					$('#editBox03 .infoTable:eq(1) .hideBar02').removeClass('hide');
				}
		});
		
		
		$('#weidu02_01').combo({
			redata:true,
			changedFn:function(){
					var _this = $('#weidu02_01');
					var val = _this.find('input').val();
					var _option = _this.parent().siblings('td').children();
					_option.addClass('hide');
					if(val > 1) _option.eq(val-2).removeClass('hide');
				}
		});
		
		$("#selectOption1_02").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#locationSel_01").attr("value",val);
		})
		
		$("#selectOption1_03").click(function(){
			isChecked = $(this).find("input:checked");
			val = "";
			isChecked.parents("li").each(function(index, element) {
                val += $(this).text()+",";
            });
			
			if (val.length > 0 ) val = val.substring(0, val.length-1);
			$("#centerSel_01").attr("value",val);
		})
		
		
		
		$(".parentLabel").click(function(){
			var isChecked = $(this).hasClass("checked");
			if(isChecked){
				$(this).next().addClass("subContentDisabled")	
			}
			else {
				$(this).next().removeClass("subContentDisabled")
			}
		})
		$(document).click(function(e)
	   {
		  
		   if($(e.target).attr("cl_id")!="weidu02" && $(e.target).attr("cl_id")!="weidu01")
		   {
			   
			   $('.optionBox').hide();
		   }
		    if($(e.target).attr("cl_id")!="part1" && $(e.target).attr("cl_id")!="part2")
		   { 
		   	if($(e.target).parentsUntil("#selectOption1").hasClass("ztree"))
			   {
				   
				   return;
			   }
			   $('.selectOptionBox').hide();
		   }
		   
	   })

	});