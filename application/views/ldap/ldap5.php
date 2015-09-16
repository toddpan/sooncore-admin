<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>

<body>
<!--组织与帐号_LDAP同步5.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep">
    	<a  class="selected">1. 连接LDAP服务器<b class="arrow"></b></a>
    	<a  class="selected">2. 选择同步的组织<b class="arrow"></b></a>
    	<a  class="selected">3. 指定员工信息<b class="arrow"></b></a>
    	<a  class="selected">4. 选择同步的员工信息<b class="arrow"></b></a>
    	<a  class="selected current">5. 设置帐号规则<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:100%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
    <dl class="ldapSetCont">
    	<dt class="setTitle" style="margin:0px 0 5px;">请设置sooncore平台账号</dt>
    	<dd class="error">操作超时，请稍后再试</dd>
        <dd style=" margin-bottom: 10px;">
            <table class="infoTable">
                <tr>
                    <td width="326">
                        <div class="combo selectBox w318" >
                            <a class="icon" ></a>
                            <span class="text selected" id='Select_div'>使用邮箱作为sooncore平台帐号</span>
                            <div class="optionBox">
                                <dl class="optionList" id="zhType">
                                    <dd class="option selected" target="1">使用邮箱作为sooncore平台帐号</dd>
                                    <dd class="option" target="2">指定统一的标签作为帐号前缀</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
                    </td>
                    <td>
                   	 	<div class="select-option">
                            <div class="combo selectBox w318">
                                <a class="icon" ></a>
                                <span class="text selected" id='select_tag'>选择标签</span>
                                <div class="optionBox">
                                    <dl class="optionList">
                                          <dd class="option selected" target="选择标签">选择标签</dd>
                                          <dd class="option" target="Last Name"> Last Name </dd>
                                          <dd class="option" target="Name"> Name</dd>
                                          <dd class="option" target="Post"> Post</dd>
                                          <dd class="option" target="Cellular Phone "> Cellular Phone </dd>
                                          <dd class="option" target="E-mail"> E-mail</dd>
                                          <dd class="option" target="Age"> Age</dd>
                                          <dd class="option" target="Cost Center"> Cost Center</dd>
                                          <dd class="option" target="Location"> Location</dd>
                                          <dd class="option" target="Address"> Address</dd>
                                    </dl>
                                    <input type="hidden" class="val" value="0" />
                                </div>
                            </div>
                        </div>
                        <div class="select-option" style="display: none;">
                            <div class="combo selectBox" style="width: 200px;">
                                <a class="icon" ></a>
                                <span class="text selected" id='select_two' >请选择</span>
                                <div class="optionBox">
                                    <dl class="optionList">
                                        <dd class="option selected" target="选择标签">请选择</dd>
                                        <dd class="option " target="Last Name">Last name</dd>
                                        <dd class="option " target="Name">Name</dd>
                                        <dd class="option" target="Post"> Post</dd>
                                        <dd class="option" target="Cellular Phone "> Cellular Phone</dd>
                                        <dd class="option" target="E-mail"> E-mail</dd>
                                        <dd class="option" target="Age"> Age</dd>
                                        <dd class="option" target="Cost Center"> Cost Center</dd>
                                        <dd class="option" target="Location"> Location</dd>
                                        <dd class="option" target="Address"> Address</dd>
                                    </dl>
                                    <input type="hidden" class="val" value="0" />
                                </div>
                            </div>
                            @haier.dadaouc.com
                        </div>
                    </td>
                </tr>
            </table>
        </dd>
        <dd style="border: none; background: none; margin-bottom: 15px;"><label class="checkbox checked"><input name="" type="checkbox" checked="checked" value="" />同步后，如果在 LDAP 找不到用户信息立即停用并删除</label></dd>
        <dt class="setTitle" style="margin:30px 0 5px;">请输入不用开通sooncore平台帐号的例外规则</dt>
        <dd class="addRule">
            <table class="infoTable">
                <tr class="hide_word" style="display: none">
                    <td width="326">
                        <div class="inputBox w318">
                            <label class="label" for="orderValue">您可以写入这样一个规则 OU=labourer</label>
                            <input class="input" id="orderValue" value="" />
                        </div>
                    </td>
                    <td>
                    	<a class="btnBlue yes"  onclick="addOrderSuccess(this)"><span class="text">&nbsp;确定&nbsp;</span><b class="bgR"></b></a>&nbsp;
                    	<a class="btnGray btn"  onclick="cancelAddOrder()"><span class="text">&nbsp;取消&nbsp;</span><b class="bgR"></b></a>
                    </td>
                </tr>
                <tr class="noOrder">
                    <td colspan="2" ><span tagert="1">无规则</span> <a id="addOrder" class="link"  onclick="addOrder()">增加规则</a></td>
                </tr>
            </table>
			
           
        </dd>
    </dl>
    
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('main/mainPage');?>','main');"><span class="text"
		style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ldap/choseLdap');?>');"><span class="text"
		style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue yes" ><span class="text" onclick="nextStep()" style="cursor: pointer">保存设置</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">

function nextStep() {
    var select_box=0;//选框的值,如果为0是未选中，为1是选中
	var  Exception_rule=[];
	var  Re_rule=[];////设置的例外规则
    $('#select_tag').parent("div").removeClass("error");
	$('#select_Two').parent("div").removeClass("error");
	if($("dd label.checkbox").hasClass("checked"))
	{
	  select_box=1;
	}
	var i=0;
	if($("tr.noOrder").find("span").attr("tagert")=="0")
	{
	   $(".addRule tr").each(function()
	   {
	     if(!$(this).hasClass("noOrder") && !$(this).hasClass("hide_word"))
		 {
	      Exception_rule[i]=$(this).find("td:first").text();
		  i=i+1;
		 }
	   })
	}
	else
	{
	   Exception_rule=0;
	}
	var j=0;
	for(var i=0;i<Exception_rule.length;i++)
	{
	  if(Exception_rule[i]!='')
	  {
	     Re_rule[j]=Exception_rule[i];
		  j=j+1;
	  }
	}
	var strTwo=$('#Select_div').text();
	if($('#select_tag').text()!="选择标签")
	{
	  var str=$('#select_tag').text();
	 
	}
	if($('#select_two').text()!="请选择")
	{
	  var str=$('#select_two').text();
	   
	}
	if($('#select_two').text()=="请选择" && $('#select_tag').text()=="选择标签")
	{
	   $('#select_two').parent("div").addClass("error");
	    $('#select_tag').parent("div").addClass("error");
		$('dd.error').text("请设置sooncore平台账号")
		return false;
	}
	else
	{
	 var path="<?php echo site_url('ldap/handleLdap5'); ?>";
	 var obj={
					"ldap_left":strTwo,
					"select_two":str,
					"select_box":select_box,//选中框是否选中
					"Exception_rule":Re_rule //设置的规则
					
				        };
				$.post(path,obj,function(data){
				//alert(data);
					var json = $.parseJSON(data);
                    if(json.code == 0)
					{ 
					/* $("#checking").show();*/
	                 var clr = setTimeout(function(){
		            showDialog('<?php echo site_url('ldap/saveLdap');?>');	
		            clearTimeout(clr);
	                  },2)
					}
					else
					 {
					  if(json.error=="select_two")
					  {
					  $('#'+json.error_id+'').parent("div").addClass("error");
					  $('#select_tag').parent("div").addClass("error");
					   $('dd.error').text("请设置sooncore平台账号");
					  }
					   else if(json.error=="select_tag")
					  {
					    $('#'+json.error_id+'').parent("div").addClass("error");
					  $('#select_tw0').parent("div").addClass("error");
					  $('dd.error').text("请设置sooncore平台账号");
					  }
					  else
					  {
					    $('#'+json.error_id+'').parent("div").addClass("error");
					  }
					  
		               return false;
					  }
					})
		  /*$("#checking").show();
			 var clr = setTimeout(function(){
		   //loadCont('组织与帐号_LDAP同步6.html');
		      showDialog('<?php echo site_url('ldap/saveLdap');?>');	
		      $("#checking").hide();
		      clearTimeout(clr);
	          },2000)*/
		
	}
	
}
	function addOrder() {
	    $(".noOrder").find("span").attr("tagert","0");
		$('.noOrder').hide().prev().show();
		$("#orderValue").val("");
	}
	function addOrderSuccess(t) {
		var val = $("#orderValue").val();
		if(val == ""){
			$("#orderValue").focus().parent(".inputBox").addClass("error");
			return false;
		}
		else {
			$(t).parents("tr").before('<tr><td width="326">'+ val+
                   '</td><td><a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;'+
                    	'<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>'+
                    '</td>'+
               ' </tr>');
			   
			$(".noOrder").show().find("span").hide();
			$(t).parents("tr").hide();
		}
	}
	
	function deleteOrder(t) {
		var len = $(t).parents("table").find("tr").length;
		$(t).parents("tr").remove();
		if( len == 3){
			$(".noOrder").find("span").show();	
		}
	}
	function cancelAddOrder() {
		$('.noOrder').show().prev().hide();
	}
	
	var editVal; 
	function editOrder(t) {
		var val = $(t).parent("td").prev().text();
		editVal = val;
		$(t).parent("td").prev().html('<div class="inputBox w318">'+
                            '<b class="bgR"></b>'+
                            '<label class="label"></label>'+
                            '<input class="input" value="'+ val +'" />'+
                        '</div>');
		$(t).parent("td").html('<a class="btnBlue yes"  onclick="editOrderSuccess(this)"><span class="text">&nbsp;确定&nbsp;</span><b class="bgR"></b></a>&nbsp; <a class="btnGray btn"  onclick="cancelEditOrder(this)"><span class="text">&nbsp;取消&nbsp;</span><b class="bgR"></b></a>')
	}
	
	function editOrderSuccess(t) {
		var val = $(t).parent("td").prev().find("input").val();
		if(val == ""){
			$(t).parent("td").prev().find("input").focus();
			return false;	
		}
		else {
			$(t).parent("td").prev().text(val);
			$(t).parent("td").html('<a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;'+
                    	'<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>')
		}
	}
	
	function cancelEditOrder(t){
		$(t).parent("td").prev().text(editVal);
		$(t).parent("td").html('<a class="btnGray btn"  onclick="editOrder(this)"><span class="text">&nbsp;编辑&nbsp;</span><b class="bgR"></b></a> &nbsp;'+
                    	'<a id="addOrderCancel" class="btnGray btn"  onclick="deleteOrder(this)"><span class="text">&nbsp;删除&nbsp;</span><b class="bgR"></b></a>');
	}
	
	
	$(function(){
		checkbox();
		$('.optionBox dd').click(function()
		{
		   if($(this).find("dd[class='option selected']").attr("target")!="0")
		   {
		     $('#select_tag').parent("div").removeClass("error");
			 $('#select_two').parent("div").removeClass("error");
		   }
		})
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		$("#zhType dd").click(function(){
			var index = $(this).index();
			$(".select-option").eq(index).show().siblings().hide();
			$('#select_tag').text("选择标签");
			$('#select_two').text("请选择");	
		})
		
		$("input[name='setLabel']").click(function(){
			$(this).parents("dt").next("dd").show();
			if($(this).attr("id")=="setLabel_1") {
				$("#setLabel_0").parents("dt").next("dd").hide();
			}
			else {
				$("#setLabel_1").parents("dt").next("dd").hide();
			}
		})
	});
	
	$(function(){ 
	 
	 $('#Select_div').click(function(){
	 	var rel=$(this).hasClass('selected');
	  
	 
	 
	 }) 
	})
	
</script>
</body>
</html>