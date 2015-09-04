<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--指定标签.html-->
<div class="contHead">
	<span class="title01 rightLine">企业生态</span><span class="title03 rightLine">标签管理</span> 
</div>
<!-- end contHead -->
<dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">以下为您创建的组织员工信息</dt>
        <dd>
            <table class="infoTable">
                <tr>
                    <td width="112">姓</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>名</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>性别</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>部门</td>
                    <td>&nbsp;
                        
                    </td>
                </tr>
                <tr>
                    <td>职位</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>手机</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>办公室所在地区</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </dd>
        <dt class="setTitle" style="margin:10px 0 5px;">您是否需要增加更多的生态企业员工信息</dt>
        
         <dd>
        <table class="infoTable">
                
        	<tr class="userInfo" style="display: none" >
                    <td>
                        <div class="inputBox fl" style="margin-right: 5px;">
                             
                            <label class="label">请输入员工信息</label>
                            <input class="input" id="newInfo" name="newInfo" style="width: 332px" value="" />
                                    
                        </div>
                    
                      <label for="writeTagName01" class="radio checked fl" > <input type="radio" checked="checked" name="writeTagName" value="0" id="writeTagName01" />管理员填写</label>
			<label for="writeTagName02"  class="radio fl" ><input type="radio" name="writeTagName" id="writeTagName02" value="1" />员工填写</label>
                    
                    	<a class="btnBlue yes fl" style="margin-right: 5px;"  onclick="addNewItem()"><span class="text" style="width: 60px;">确定</span><b class="bgR"></b></a>
                    	<a class="btnGray btn fl"  onclick="cancelAddNew()"><span class="text" style="width: 60px;">取消</span><b class="bgR"></b></a>
                    </td>
                </tr>
                    
                <tr><td><a class="btn_addTag" >增加员工信息</a></td></tr>
            </table>
        </dd>
    </dl>
    
<div class="toolBar2" style="padding-top: 20px; text-align: left">
    <a class="btnBlue yes" onclick="loadCont('ecologycompany/ecologyPage');"><span class="text">完成</span><b class="bgR"></b></a>
		<a class="btnGray btn" onclick="loadCont('ecologycompany/ecologyPage');"><span class="text">取消</span><b class="bgR"></b></a>
		
	</div>
<!--[if IE 6]>
<script type="text/javascript">
	DD_belatedPNG.fix('.btn_icon, .btn_icon .iconL, .btn_icon .iconR');
</script>
<![endif]-->

<script type="text/javascript">
	function addNewItem() {
		var val = $("#newInfo").val();
		var str;
		if($("input[name='writeTagName']:checked").val()=="1"){
			str = "此标签为员工填写";
		}
		else {
			str = "此标签为管理员填写";
		}
		if(val == "") {
			$("#newInfo").focus().parent(".inputBox").addClass("error");
			return false;	
		}
		else {
			var newItem = '<tr>'+
                    '<td><label class="checkbox checked fl" style="margin-right: 5px"><input type="checkbox" checked="checked" /><span>'+ val +'</span><span class="gray">('+ str +')</span></label><a class="btnGray2  btn fl" style="margin-right: 5px; margin-top: 2px;"  onclick="editNewItem(this)"><span class="text">编辑</span><b class="bgR"></b></a> '+
                    	'<a class="btnGray2 btn fl"  onclick="deleteNewItem(this)"  style="margin-top: 2px;"><span class="text">删除</span><b class="bgR"></b></a></td>'+
               ' </tr>';
			$(".userInfo").before(newItem);
			$(".userInfo").hide().next().show();
		}
	}
	
	function cancelAddNew() {
		$(".userInfo").hide().next().show();
	}
	
	function editNewItem(t) {
		var val = $(t).parents("tr").find("span:first").text();
		$(t).parents("tr").hide();
		$(".userInfo").show().find("#newInfo").val(val);
	}
	function deleteNewItem(t){
		$(t).parents("tr").remove();
	}
	
	$(function(){
		checkbox();
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		$(".btn_addTag").click(function(){
			$(this).parents("tr").prev().show();
			$(this).parents("tr").prev().find(".input").val("");
			$(this).parents("tr").prev().find(".label").show();	
			$(this).parents("tr").hide();
		})
		
		$('.radio').click(function(){ 
			//setupLabel(); 
		}); 
		//setupLabel();
	});
</script>

</body>
</html>







