// JavaScript Document
function initLoadPage()
  {
     
        staff_department();//员工部门设置
		staff_chose_tag();//可选员工标签
		staff_write_tag();//自定义员工标签
		
  }
  //设置员工所在部门
 function staff_department()
 {
     $(".optionList dd").each(function()
		{
		  
		  if(department_level==$(this).attr("target"))
		   { 
		    $(this).addClass("selected");
			$('#depart_level').attr("title",department_level).addClass("selected").text(department_level);
		    }
		})
 }
 //设置可选标签
 function staff_chose_tag()
 {
     var seled_array=seled_not_must_tag_names.split(",");
	 var i=0;
	 var size=seled_array.length;
	 while(size!=0)
	 {
		$('.checkbox input').each(function()
		{
		if(seled_array[i]==$(this).val())
		   {
		    $(this).attr("checked","checked");
			$(this).parent().addClass("checked");
		    }
		 })
		   i=i+1;
		   size=size-1;
	}
 }
 //设置自定义员工标签
function staff_write_tag()
{
//	
	var json=$.parseJSON(user_defined_tag_arr);
	//alert(user_defined_tag_arr)
    //var json = eval("("+user_defined_tag_arr+")")
	//alert(json)
	var j=0;
	//alert(json.length)
	var newItem='';
	while(j<json.length)
	{
		var tag_name_W=json[j].tag_name;
		var tag_scope_T=json[j].tag_scope;

		var tag_scope;
	   if(tag_scope_T=="1")
		  {
			tag_scope= "此标签为管理员填写";
		  }else 
		     {
		      tag_scope="此标签为员工填写";
		     }
        if(json[j].enable=="1")
        {
            newItem=newItem+'<tr style="display:block;" class="edit_staff_class" id="edit_staff_tag" tagert="'+json[j].id+'">'+
                '<td>'+
                '<label class="checkbox checked fl" style="margin-right: 5px">'+
                '<input type="checkbox" checked="checked"/>'+
                '<span>'+ tag_name_W+'</span>'+
                '<span class="gray">('+ tag_scope +')</span>'+
                '</label>'+
                '<a class="btnGray2 btn fl" style="margin-right: 5px; margin-top: 2px">'+
                '<span class="text"  onclick="editNewItem_Two(this)" style="cursor: pointer">编辑</span>'+
                '<b class="bgR"></b>'+
                '</a>'+
                '<a class="btnGray2 btn fl"  style="margin-top: 2px;" onclick="deleteNewItem(this)">'+
                '<span class="text" style="cursor: pointer">删除</span>'+
                '<b class="bgR"></b>'+
                '</a>'+
                '</td>'+
                '</tr>';
        }
        else
        {
            newItem=newItem+'<tr style="display:block;" class="edit_staff_class" id="edit_staff_tag" tagert="'+json[j].id+'">'+
                '<td>'+
                '<label class="checkbox  fl" style="margin-right: 5px">'+
                '<input type="checkbox" />'+
                '<span>'+ tag_name_W+'</span>'+
                '<span class="gray">('+ tag_scope +')</span>'+
                '</label>'+
                '<a class="btnGray2 btn fl" style="margin-right: 5px; margin-top: 2px">'+
                '<span class="text"  onclick="editNewItem_Two(this)" style="cursor: pointer">编辑</span>'+
                '<b class="bgR"></b>'+
                '</a>'+
                '<a class="btnGray2 btn fl"  style="margin-top: 2px;" onclick="deleteNewItem(this)">'+
                '<span class="text" style="cursor: pointer">删除</span>'+
                '<b class="bgR"></b>'+
                '</a>'+
                '</td>'+
                '</tr>';
        }

		
	       j=j+1;
		   
	}	
    $(".btn_addTag").parents("tr").before(newItem);
	 var m=0;
	 var tag_enable;
	 
	 $('.edit_staff_class').each(function()
	 {
	     tag_enable=json[m].enable;
		if(tag_enable==1)
		{
		$(this).find("label:first").addClass("checked");
		}
		m++;
	 })
	 $('#edit_staff_tag').show();
} 
//点击批量导
//点击LDAP入
function bulk_import(pagetype,isLDAP,current_type)
{
	$('#lsBox').removeClass("allshow").hide();
	if(pagetype == 1)
	{
			 if(current_type == 0){//0（批量导入）
				 gotourl = "organize/listOrgPage";
				 //loadCont(gotourl);
				//return false;
			}else//1：是（LDAP导入）
			{
				 gotourl = "organize/ldaporg"; 
				 //loadCont(gotourl);
				 //return false;
			}
			
	}else
	{//0新加页面
			//alert(current_type)
			 if(current_type == 0){//0（批量导入）
			 //alert(111)
				 gotourl = "batchimport/index";
				// loadCont(gotourl);
				  //return false;
			///alert(222)
			}else//1：是（LDAP导入）
			{
				 gotourl = "ldap/showLdapPage";
				  //loadCont(gotourl);
				  //return false;
			}
			 
	}
	loadCont(gotourl);
}


 //完成按钮事件
 //pagetype0新加页面1修改标签
 //isLDAP0：否0（批量导入）；1：是（LDAP导入）；2：全部都可以
 //current_type 当前类型0（批量导入） 1：是（LDAP导入）；2：全部都可以
 function staff_finished(pagetype,isLDAP,current_type)
 { 
   //2：全部都可以
	 $('#self_defined_suffix').siblings('.error1').hide();

	var  gotourl = "";
	
	//默认为组
//	alert(current_type)
	//1修改标签,跳转到组织结构列表
	if(current_type == 2){
		 $('#lsBox').show();
		 $('#lsBox').addClass("allshow");
		 //return false;
		}
	else
	{
		//alert(pagetype)
		if(pagetype == 1){
			 if(current_type == 0){//0（批量导入）
				 gotourl = "organize/listOrgPage";
				 //loadCont(gotourl);
				//return false;
			}else//1：是（LDAP导入）
			{
				 gotourl = "organize/ldaporg"; 
				 //loadCont(gotourl);
				 //return false;
			}
			
		}else{//0新加页面
			//alert(current_type)
			 if(current_type == 0){//0（批量导入）
			 //alert(111)
				 gotourl = "batchimport/index";
				// loadCont(gotourl);
				  //return false;
			///alert(222)
			}else//1：是（LDAP导入）
			{
				 gotourl = "ldap/showLdapPage";
				  //loadCont(gotourl);
				  //return false;
			}
			 
		}
	} 
   var path;
   var i=0;
   var j=0;
   var jj=0;
   var json = eval("("+seled_not_must_tag_arr+")")//$.parseJSON(seled_not_must_tag_arr);
   var json_one= eval("("+user_defined_tag_arr+")")//$.parseJSON(user_defined_tag_arr);
    var  staff_value=[];//可选标签
	var  staff_defined=[];//自定义标签选中的
	var  staff_defined2=[];//自定义标签没选中的
	var  admin_staff=[];//选中的是管理员填写还是员工填写
	var  admin_staff2=[];//没选中的是管理员写还是员工填写
	var  staff_id=[];//选中的自定义标签的ID
	var  staff_id2=[];//没选中的自定义标签的ID
    var depart_value=$("#depart_level").text(); 
    // if(depart_value=="请选择部门层级"){
	    // $('#depart_level').parent("div").css("border"," 1px solid #FF0000");
	 	// return false; 
	// }else{//可选标签选中的标签
	   $("label").each(function()
	   {
	    if($(this).attr("class")=="checkbox checked")
		    {
			 staff_value[i]=$(this).find("input").val();
			 i++;
			}
		
	   })
	     
	   //自定义标签选中的和没选中的
	   $("tr[id='edit_staff_tag']").each(function()
	    {
	   if($(this).find("label").hasClass("checked") )
		   {
		     if($(this).attr("tagert"))
			 {
			   staff_id[j]=$(this).attr("tagert");
			 }
			 else
			 {
			   staff_id[j]=0;
			 }
		   staff_defined[j]=$(this).find("span:first").text();
		   if($(this).find("span:[class='gray']").text()=="(此标签为管理员填写)")
		     admin_staff[j]=1;
			else if($(this).find("span:[class='gray']").text()=="(此标签为员工填写)")
			  admin_staff[j]=2;
		   j++;
		  }else {
		     if($(this).attr("tagert"))
			 {
			   staff_id2[jj]=$(this).attr("tagert");
			 }
			 else
			 {
			   staff_id2[jj]=0;
			 }
		   staff_defined2[jj]=$(this).find("span:first").text();
		   if($(this).find("span:[class='gray']").text()=="(此标签为管理员填写)"){
		     admin_staff2[jj]=1;}
			else{
			  admin_staff2[jj]=2;}
		   jj++;
		  }
		})
		//构造的JSON串
		//可选标签的JSON串
	   var staff_tag_post='';
	   for (var s=0;s<staff_value.length;s++)
	   {
	     var mcount=0;
	      for(var p=0;p<json.length;p++)
		  {
			if(staff_value[s]==json[p].tag_name)
			{
 staff_tag_post+='{"id":"'+json[p].id+'","tag_name":"'+staff_value[s]+'","enable":1,"tag_scope":1,"tag_type":1},'
			}
			else
			{
			  mcount++;  
			}
		  }
		  if(mcount==json.length){
		  staff_tag_post+='{"id":0,"tag_name":"'+staff_value[s]+'","enable":1,"tag_scope":1,"tag_type":1},'
	      }
	   }
	   //自定义标签的JSON串
	   //选中的JSON串
	   for (var s=0;s<staff_defined.length;s++)
	   {
 staff_tag_post+='{"id":'+staff_id[s]+',"tag_name":"'+staff_defined[s]+'","enable":1,"tag_scope":'+admin_staff[s]+',"tag_type":2},'
	   }
	   //没选中的JSON串
	  for (var s=0;s<staff_defined2.length;s++)
	   {
	    staff_tag_post+='{"id":'+staff_id2[s]+',"tag_name":"'+staff_defined2[s]+'","enable":0,"tag_scope":'+admin_staff2[s]+', "tag_type":2},'
	   }
	   //去掉构造的JSON串最后一个的,
	  var lastIndex = staff_tag_post.lastIndexOf(',');
       if (lastIndex > -1) {
         staff_tag_post = staff_tag_post.substring(0,lastIndex) + staff_tag_post.substring(lastIndex + 1,staff_tag_post.length);
           }
      staff_tag_post='['+staff_tag_post+']';
      
      
     // LDAP站点登录名设置
     var use_suffix = $('#use_self_defined_suffix label input').val();
     var suffix = $('#self_defined_suffix input').val();
     
     if(use_suffix == 1 && suffix == ''){
    	 $('#self_defined_suffix').siblings('.error1').text('请输入自定义后缀名').show();
    	 return false;
     }
      
	 var path="tag/addTag";
	 var obj={
			"page_type":page_type,				// 0新加页面1修改标签
			"department_level":depart_value,	// 部门层级
			"tag_json":staff_tag_post,			// 标签值
			"use_suffix":use_suffix,			// 登录名是否使用自定义后缀
			"suffix":suffix						// 自定义后缀
		};
	/**
	 * 由于使用ajax的post()请求方法无法设置请求同步会导致一个在请求未完成时
	 * 点击“LDAP导入方式”页面乱跳的bug，所以注释掉，改为ajax()方法
	 * 
	 
	 $.post(path,obj,function(data){
		//alert(data);
		var json = $.parseJSON(data);
		if(json.code == 0)
		{
			
		  //$('#lsBox').show();
		  if(!$('#lsBox').hasClass("allshow"))
			{
		  		 loadCont(gotourl);
		  	}
		 
		  //location = gotourl;
		}
		else
		{
			alert(json.prompt_text);
		  	return false;
		}
		});
		*/
	 
	 $.ajax({
		url: path,
		type: 'POST',
		async: false,
		data: obj,
		success: function(data){
			var json = $.parseJSON(data);
			if(json.code == 0)
			{
			  //$('#lsBox').show();
			  if(!$('#lsBox').hasClass("allshow"))
				{
			  		 loadCont(gotourl);
			  	}
			}
			else
			{
				alert(json.prompt_text);
			  	return false;
			}
		},
	 	error: function(data){
	 		var json = $.parseJSON(data);
	 		alert(json.prompt_text);
		  	return false;
	 	}
	 });
 }
	
 // 添加员工标签事件

function show_addNumber()
{
 
}
//取消按钮事件
function cancelAddNew(t) 
{
   $(t).parents("tr").hide();
   if($('#self_staff_tag').find("a.btn_addTag").length==0)
   {
   		$(t).parents('tr').after('<tr><td><a class="btn_addTag" onclick="add_staff_tag(this);" style="cursor: pointer">添加员工标签</a></td></tr>');
		$("tr[class='userInfo']").remove();
   }
   else
   {
   		//add_staff_tag(t)
		
		$("tr[class='userInfo']").next().show();
	    $("tr[class='userInfo']").remove();
   }
}
//编辑按钮事件

// 展示的自定义标签的编辑事件
function editNewItem_Two(t)
{
	if($('.userInfo').length>0)
	{
		//$('.userInfo').remove();
		if($('#self_staff_tag').find("a.btn_addTag").length==0)
		   {
				$(t).parents('tr').after('<tr><td><a class="btn_addTag" onclick="add_staff_tag(this);" style="cursor: pointer">添加员工标签</a></td></tr>');
				$('.userInfo').remove();
		   }
		   else
		   {
		   		//$(".btn_addTag").parents("tr").show();
	   			$('.userInfo').next("#edit_staff_tag").show();
				$('.userInfo').remove();
				//add_staff_tag(t)
		   }
	}
     var value=$(t).parents("tr").find("span:first").text();
	 $(t).parents("tr").before(newEdit);
	 $(t).parents("tr").hide();
	/* $('#EditNew').find('label').show();*/
	 $(t).parents("tr").prev().show().find("label").hide();
	 
	 $(t).parents("tr").prev().find("label[id='admin_write']").css("display","block" ).show();
	 $(t).parents("tr").prev().find("label[id='staff_write']").css("display","block" ).show();
	
	 $(t).parents("tr").prev().find("input[id='newInfo']").val(value);
	/* $(t).parents("tr").find("span:first").text("");*/
	 /* $(t).parents("tr").prev().find("input[id='newInfo']")*/

	 if($(t).parents("tr").find("span:[class='gray']").text()=="(此标签为管理员填写)")
	 {
	  $(t).parents("tr").prev().find("label[id='admin_write']").addClass("checked");
	   $(t).parents("tr").prev().find("label[id='staff_write']").removeClass("checked"); 
	 }
	 else if($(t).parents("tr").find("span:[class='gray']").text()=="(此标签为员工填写)")
	 {
	   $(t).parents("tr").prev().find("label[id='staff_write']").addClass("checked");
	   $(t).parents("tr").prev().find("label[id='admin_write']").removeClass("checked");
	 }
	 
}
//点击添加自定义标签
function add_staff_tag(t)
{
	 $('#edit_staff_tag').each(function()
	          {
	           $("tr[class='userInfo']").next().show();
	           $("tr[class='userInfo']").remove();
	         })
			//alert(22)
			$(t).parents("tr").before(newEdit);
			$(t).parents("tr").prev().show();
			$(t).parents("tr").remove();
			$(t).parents("tr").prev().find(".input").val("");
			$(t).parents("tr").prev().find(".label").show();	
			
			
}
//自定义标签的确定按钮
function addNewItem_Two(t,tag_scope)
{ 
     var str_t;
	 var str;
      if($(t).parents("tr").find("label[id='admin_write']").hasClass("checked"))
	    {
			str = "(此标签为管理员填写)";
			str_t=1;
	    }
	 else 
	   {
			str = "(此标签为员工填写)";
			str_t=2;
	   }
	  var value=$(t).parents("tr").find("input[id='newInfo']").val();
	  var system_Tname=system_tag_names.split(',');
	  var size=system_Tname.length;
	  var i=0;
	  var count=0;
	  var tag_context=new Array();
	  var m=0;
	   $("tr").find("span:first").each(function()
	   {
		   if($(this).parent().parent().parent().prev().attr("id")!="edit_staff_message")
		   {
			   //continue;
			   tag_context[m]=$(this).text();
				m++;
		   }
	    	
	   })
	   //	var rag=new RegExp("[\u4E00-\u9fa5]+","g");
		var login=/^[a-zA-Z0-9\u4e00-\u9fa5]+$/;
		//var chin=rag.test(value);
		//var eng=login.test(value);
		var sp=0;
		for(var i=0;i<value.length;i++)
		{
			//if(!rag.test(value.charAt(i)))
			//{
				if(!login.test(value.charAt(i)))
				{
					sp++;
				}
			//}
		}
	   while(size!=0)
	   {
	      if(value==system_Tname[i] || value=="" || value.length>30)
		   {
			  
		     $(t).parents("tr").find("input[id='newInfo']").focus().parent(".inputBox").addClass("error");
	         count++;
		   }
		   else if(sp>0)
		   {
		   	 //if(!eng)
			// {
				
			 	 $(t).parents("tr").find("input[id='newInfo']").focus().parent(".inputBox").addClass("error");
	        	 count++;
			// }
		   }
		 i++;
		 size--;
	   }
	   var tag_size=tag_context.length;
	   var j=0;
	   while(tag_size!=0)
	   { 
	     if(value==tag_context[j])
		 {
		  $(t).parents("tr").find("input[id='newInfo']").focus().parent(".inputBox").addClass("error");
	       count=count+1;
		 }
		 j++;
		 tag_size--;
	   }
	   if(count!=0)
	   {
	     return false
	   }
	   else
	   {
	   	 var self_tag='<tr><td><a class="btn_addTag" onclick="add_staff_tag(this);" style="cursor: pointer">添加员工标签</a></td></tr>';
	    /*$(t).parents("tr").hide();*/
		if($(t).parents("tr").next().attr("id")=="edit_staff_tag")
		{
	      $(t).parents("tr").next().show();
	      $(t).parents("tr").next().find("span:first").text(value);
		  $(t).parents("tr").next().find("span[class='gray']").text(str);
	      $(t).parents("tr").remove();
		}
		else
		{
		  var staff_newItem=
                 '<tr style="display:block;" id="edit_staff_tag">'+
                    '<td>'+
					  '<label class="checkbox checked fl" style="margin-right: 5px">'+
		                 '<input type="checkbox" checked="checked"/>'+
						 '<span>'+value+'</span>'+
						 '<span class="gray">('+ str +')</span>'+
					 '</label>'+
					 '<a class="btnGray2 btn fl" style="margin-right: 5px; margin-top: 2px">'+
					   '<span class="text"  onclick="editNewItem_Two(this,'+ str_t +')">编辑</span>'+
					   '<b class="bgR"></b>'+
					 '</a>'+
                     '<a class="btnGray2 btn fl"  style="margin-top: 2px;" onclick="deleteNewItem(this)">'+
					    '<span class="text">删除</span>'+
						'<b class="bgR"></b>'+
					 '</a>'+
				  '</td>'+
               '</tr>';
		   $(t).parents("tr").after(staff_newItem);
		  
	      $(t).parents("tr").next().show();
	      $(t).parents("tr").next().find("span:first").text(value);
		  $(t).parents("tr").next().find("span:[class='gray']").text(str);
	      $(t).parents("tr").remove();
		  //var index=$('.infoTable tbogy tr').length;
		 //alert(index) 
		 //$('#self_staff_tag tbody').addClass("tag");
		  $('#self_staff_tag tbody tr:last').after(self_tag); 
		}
	   /*tag_context.push(value);*/
	  }
}
//删除按钮事件
function deleteNewItem(t)
{
		$(t).parents("tr").remove();
}
function setupLabel(){
    }

$(function(){
		checkbox();
		initLoadPage();
		$('.infoTable .selectBox').combo({		
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:'.option'
		});
		$('label.radio').die('click');
		$('label.radio').live('click',function(){ 
			if(!$(this).hasClass("checked"))
			{
				if($(this).attr("id")=="admin_write")
				{
					$(this).addClass("checked");
					$(this).next().removeClass("checked");
				}else
				{
					$(this).addClass("checked");
					$(this).prev().removeClass("checked");
				}
			}
			
			//setupLabel(); 
		}); 
		//setupLabel();
});