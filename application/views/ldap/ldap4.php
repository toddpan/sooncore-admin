<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--组织与帐号_LDAP同步4.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep">
    	<a  class="selected">1. 连接LDAP服务器<b class="arrow"></b></a>
    	<a  class="selected">2. 选择同步的组织<b class="arrow"></b></a>
    	<a  class="selected">3. 指定员工信息<b class="arrow"></b></a>
    	<a  class="selected current">4. 选择同步的员工信息<b class="arrow"></b></a>
    	<a >5. 设置帐号规则<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:80%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
    <dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">必选的员工标签</dt>
        <dd class="error">操作超时，请稍后再试</dd>
            <table class="infoTable">
                <tr>
                    <td width="112">姓：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span id="4-0-0" class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="last_name" />
                            </div>
                        </div>
                    </td>
                </tr>
               <!-- <tr>
                    <td>名：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="first_name" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>性别：</td>
                    <td>
                        <div class="combo selectBox w318">
                           
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="sex" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>部门：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                               <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="department" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>职位：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="position" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>手机：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="telno" />
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>办公室所在地区：</td>
                    <td>
                        <div class="combo selectBox w318">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" name="district" />
                            </div>
                        </div>
                    </td>
                </tr>-->
            </table>
        </dd>
        <!--<dt class="setTitle" style="margin:30px 0 5px;">可选的员工标签</dt>
        <dd id="otherLdap">
            <table class="infoTable">
                <tr>
                    <td width="148"><label class="checkbox"><input name="" type="checkbox" value="" /> 邮箱</label></td>
                    <td width="326">
                        <div class="combo selectBox w318" style="display: none">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input name="" type="checkbox" value="" /> 工作电话</label></td>
                    <td>
                        <div class="combo selectBox w318" style="display: none">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input name="" type="checkbox" value="" /> 成本中心</label></td>
                    <td>
                        <div class="combo selectBox w318" style="display: none">
                          
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input name="" type="checkbox" value="" /> 员工ID</label></td>
                    <td>
                        <div class="combo selectBox w318" style="display: none">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr  class="userInfo" style="display: none">
                    <td>
                        <div class="inputBox w140">
                         
                            <label class="label" for="userLabel">请输入您的标签</label>
                            <input class="input" value="" id="userLabel" />
                        </div>
                    </td>
                    <td>
                        <div class="combo selectBox w318">
                         
                            <a class="icon" ></a>
                            <span class="text">请选择对应的LDAP信息</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择对应的LDAP信息</dd>
                                    <dd class="option" target=" Last Name "> Last Name </dd>
                                    <dd class="option" target=" Name"> Name</dd>
                                    <dd class="option" target=" Post"> Post</dd>
                                    <dd class="option" target=" Cellular Phone "> Cellular Phone </dd>
                                    <dd class="option" target=" E-mail"> E-mail</dd>
                                    <dd class="option" target=" Age"> Age</dd>
                                    <dd class="option" target=" Cost Center"> Cost Center</dd>
                                    <dd class="option" target=" Location"> Location</dd>
                                    <dd class="option" target=" Address"> Address</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>
                    	<a class="btnBlue"  onclick="addNewItem()"><span class="text" style="width: 60px;">确定</span><b class="bgR"></b></a>
                    	<a class="btnGray"  onclick="cancelAddNew()"><span class="text" style="width: 60px;">取消</span><b class="bgR"></b></a>
                    </td>
                </tr>
                <tr><td colspan="3"><a class="btn_addTag" >添加员工标签</a></td></tr>
            </table>
        </dd>-->
    </dl>
    
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('main/mainPage');?>','main');"><span class="text"
		style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ldap/loadLdap');?>');"><span class="text" style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue yes"><span class="text" onclick="nextStep();" style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">

function nextStep() {
   
    var ldap4_value=[];
	var ldap4_id=[];
	var i=0;
    var count=0;
    $('.selectBox span').each(function()
	{
	  $(this).parent("div").removeClass("error");
	  if($(this).text()=="请选择对应的LDAP信息")
	  {
	   $(this).parent("div").addClass("error");
	   count=count+1;
	  }
	  ldap4_id[i]=$(this).attr("id");
	  ldap4_value[i]=$(this).text();
	  i++;
	})
	if(count!=0)
	{
	 $('dd.error').text("请为员工信息指定对应标签");
	  return false;
	}else
	{
	   var Re_data='';
	   for(var i=0;i<ldap4_id.length;i++)
	     {
	    /* Re_data=Re_data+'{id:'+Re_con[i]+',pid:'+Re_con[i+1]+',name:'+Re_con[i+2]+',},';*/
		   Re_data=Re_data+'{id:'+ldap4_id[i]+',name:'+ldap4_value[i]+'},';
	     }
	   Re_data=DelLastComma(Re_data);
	   var path="<?php echo site_url('ldap/setRules'); ?>";
	   var obj={
					"ldap_value":Re_data
					
				        };
				$.post(path,obj,function(data){
				
					var json = $.parseJSON(data);
                    if(json.code == 0)
					{ 
					 $("#checking").show();
	                 var clr = setTimeout(function(){
		            loadCont('<?php echo site_url('ldap/setRules1');?>');	
		            clearTimeout(clr);
	                  },2)
					}
					else
					 {
					  $('#'+json.error_id+'').parent("div").addClass("error");
					   $('dd.error').text("请为员工信息指定对应标签");
		               return false;
					  }
					})
	
	}
}
  function Init_staff_tag()
  {
    var value=[];
	value[0]="名";
	value[1]="性别";
	value[2]="账号";
	value[3]="账户";
	value[4]="部门";
	value[5]="职位";
	value[6]="手机";
	value[7]="办公地址";
	var id=[];
	id[0]="4-0";
	id[1]="4-1";
	id[2]="4-2";
	id[3]="4-3";
	id[4]="4-4";
	id[5]="4-5";
	id[6]="4-6";
	id[7]="4-7";
    var newtag;
     newtag=Must_chose_tag(value,id);
	 $('.infoTable').append(newtag);
  }
  function Must_chose_tag(val,id)
  {
      var value=[];
	     value[0]="last name";
		 value[1]="name";
		 value[2]="post";
		 value[3]="cellular phone";
		 value[4]="e_mail";
		 value[5]=" Age";
		 value[6]="Cost Center";
		 value[7]="Location";
		 value[8]="Address";
		 var context=optionList(value);
    var newItem='';
    for(var i=0;i<val.length;i++)
	{
     newItem =newItem+'<tr>'+
                    '<td>'+ val[i]+' :</td>'+
                    '<td>'+
                        '<div class="combo selectBox w318">'+
                            '<a class="icon" ></a>'+
                            '<span id="'+id[i]+'" class="text">请选择对应的LDAP信息</span>'+
                            '<div class="optionBox">'+context+'<input type="hidden" class="val" value="0" />'+
                            '</div>'+
                        '</div>'+
                   '</td>'+
                '</tr> ';
	}
	return newItem;			
  }
  function optionList(val)
  {
    var dd_con="";
    var context='<dl class="optionList"><dd class="option selected" target="0">请选择对应的LDAP信息';
    for(var i=0;i<val.length;i++)
	{
	 dd_con=dd_con+'<dd class="option" target=" Last Name ">'+val[i]+' </dd>'                
	}
	context=context+dd_con+'</dd></dl>';
	return context;
  }
	function addNewItem(){
		var val = $("#userLabel").val();
		if(val == "") {
			$("#userLabel").focus().parent(".inputBox").addClass("error");
			return false;	
		}
		else {
			var newItem = '<tr>'+
                    '<td><label class="checkbox checked"><input name="" type="checkbox" value="" checked="checked" /> '+ val +'</label></td>'+
                    '<td>'+
                        '<div class="combo selectBox w318" onclick="toggleOption(this)">'+
                            '<a class="icon" ></a>'+
                            '<span class="text">请选择对应的LDAP信息</span>'+
                            '<div class="optionBox">'+
                                '<dl class="optionList">'+
                                    '<dd class="option selected" target="0">请选择对应的LDAP信息</dd>'+
                                    '<dd class="option" target=" Last Name "> Last Name </dd>'+
                                    '<dd class="option" target=" Name"> Name</dd>'+
                                    '<dd class="option" target=" Post"> Post</dd>'+
                                    '<dd class="option" target=" Cellular Phone "> Cellular Phone </dd>'+
                                    '<dd class="option" target=" E-mail"> E-mail</dd>'+
                                    '<dd class="option" target=" Age"> Age</dd>'+
                                    '<dd class="option" target=" Cost Center"> Cost Center</dd>'+
                                    '<dd class="option" target=" Location"> Location</dd>'+
                                    '<dd class="option" target=" Address"> Address</dd>'+
                                '</dl>'+
                                '<input type="hidden" class="val" value="0" />'+
                            '</div>'+
                        '</div>'+
                   '</td>'+
                    '<td><a class="btnGray btn"  onclick="deleteNewItem(this)"><span class="text">删除</span><b class="bgR"></b></a></td>'+
                '</tr> ';
			$(".userInfo").before(newItem);
			$(".userInfo").hide().next().show();
		}
	}
	function cancelAddNew(){
		$(".userInfo").hide().next().show();
	}
	function deleteNewItem(t){
		$(t).parents("tr").remove();	
	}
	$(function(){
	     Init_staff_tag();//初始化必选员工标签
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		/*var context= '<dl class="optionList">'+
                                    '<dd class="option selected" target="0">请选择对应的LDAP信息</dd>'+
                                    '<dd class="option" target=" Last Name "> Last Name </dd>'+
                                    '<dd class="option" target=" Name"> Name</dd>'+
                                    '<dd class="option" target=" Post"> Post</dd>'+
                                    '<dd class="option" target=" Cellular Phone "> Cellular Phone </dd>'+
                                    '<dd class="option" target=" E-mail"> E-mail</dd>'+
                                    '<dd class="option" target=" Age"> Age</dd>'+
                                    '<dd class="option" target=" Cost Center"> Cost Center</dd>'+
                                    '<dd class="option" target=" Location"> Location</dd>'+
                                    '<dd class="option" target=" Address"> Address</dd>'+
                     '</dl>';
		*/
		
		/*$('div.combo').click(function()
		{
		 var value=[];
		 value[0]="last name";
		 value[1]="name";
		 value[2]="post";
		 value[3]="cellular phone";
		 value[4]="e_mail";
		 var context=optionList(value);
		  $(this).find("div.optionBox").prepend(context);
		  $(this).find("dl.optionList").show();
		})
		$(".selectBox").click(function()
		{
		var i=0,j=0;
		 var tag_staff=new Array();
		$(".selectBox span").each(function()
		 {
		   tag_staff[i]=$(this).text();
		   i++;
		  })
		$(".optionList dd").each(function()
		   {
		     var dd1_text=$(this).text();
			 if(dd1_text!=tag_staff[0] && dd1_text!=tag_staff[1] && dd1_text!=tag_staff[2] && dd1_text!=tag_staff[3] && dd1_text!=tag_staff[4] && dd1_text!=tag_staff[5] && dd1_text!=tag_staff[6])
			 {
			   $(this).show();
			 }
			 else
			 $(this).hide();
		   })
		  var size=tag_staff.length;
		  while(size!=0)
		  {
		   $(".optionList dd").each(function()
		   {
		     var dd_text=$(this).text();
			  if (tag_staff[j]==dd_text)
			  {
			    $(this).hide();
			  }
		   })
		   size=size-1;
		 }
		  
	   })
		$(".w318").click(function()
		{
		 //alert(1);
		 $(t).find(".optionBox").toggle();
		})
		*/
		
		/*$(".optionList dd").live("click",function(){
		$(".optionList").children("dd").show();
		var txt = $(this).text();
		$(this).parents(".selectBox").find(".text").text(txt);
		$(this).hide();
	    })*/
		$(".btn_addTag").click(function(){
			$(this).parents("tr").prev().show();
			$(this).parents("tr").prev().find(".input").val("");
			$(this).parents("tr").prev().find(".label").show();	
			$(this).parents("tr").hide();
		})
		
		
		
		$("#otherLdap .checkbox").live("click",function(){
			if($(this).hasClass("checked")){
				$(this).parents("td").next().find(".combo").show();	
			}
			else {
				$(this).parents("td").next().find(".combo").hide();	
			}
		})
	   $('.selectBox dd').click(function()
	    {
	      if($(this).attr("target")!="0")
		  {
		    if($(this).parents("div").hasClass("error"))
			{
		      $(this).parents("div").removeClass("error");
			}
		  }
		 })
	});
</script>
</body>
</html>