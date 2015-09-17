<!--组织与员工_批量导入.html-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">设置员工标签</span>
	
</div>
<!-- end contHead -->
<dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">必选的员工标签</dt>
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
                    <td>
                        <div class="combo selectBox" style="width: 200px;">
                            
                            <a class="icon" ></a>
                            <span class="text">请选择部门层级</span>
                            <div class="optionBox">
                                <dl class="optionList">
                                    <dd class="option selected" target="0">请选择部门层级</dd>
                                    <dd class="option" target="1">1</dd>
                                    <dd class="option" target="2">2</dd>
                                    <dd class="option" target="3">3</dd>
                                    <dd class="option" target="4">4</dd>
                                    <dd class="option" target="5">5</dd>
                                    <dd class="option" target="6">6</dd>
                                    <dd class="option" target="7">7</dd>
                                    <dd class="option" target="8">8</dd>
                                    <dd class="option" target="9">9</dd>
                                    <dd class="option" target="10">10</dd>
                                </dl>
                                <input type="hidden" class="val" value="0" />
                            </div>
                        </div>
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
                    <td>国家</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>办公室所在地区</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </dd>
        <dt class="setTitle" style="margin:10px 0 5px;">可选的员工标签</dt>
        <dd>
            <table class="infoTable">
                <tr>
                    <td width="148"><label class="checkbox"><input type="checkbox" /> 邮箱</label></td>
                    <td width="326">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input type="checkbox" /> 工作电话</label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input type="checkbox" /> 成本中心</label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><label class="checkbox"><input type="checkbox" /> 员工ID</label></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                
            </table>
        </dd>
        <dt class="setTitle" style="margin:10px 0 5px;">自定义更多的员工标签</dt>
        <dd>
        <table class="infoTable">
        	<tr class="userInfo" style="display: none">
                    <td>
                        <div class="inputBox fl" style="margin-right:5px;">
                            
                            <label class="label">请输入员工信息</label>
                            <input class="input" id="newInfo" style="width: 332px;"  value="" />
                        </div>
                  
                      <label for="writeTagName01" class="radio fl checked" > <input type="radio" checked="checked" name="writeTagName" value="0" id="writeTagName01" />管理员填写</label>
			<label for="writeTagName02"  class="radio fl" ><input type="radio" name="writeTagName" id="writeTagName02" value="1" />员工填写</label>
                  
                    	<a class="btnBlue yes fl" style="margin-right: 5px;"  onclick="addNewItem()"><span class="text" style="width: 60px;">确定</span><b class="bgR"></b></a>
                    	<a class="btnGray btn fl"  onclick="cancelAddNew()"><span class="text" style="width: 60px;">取消</span><b class="bgR"></b></a>
                    </td>
                </tr>
                <tr><td><a class="btn_addTag" >添加员工标签</a></td></tr>
            </table>
        </dd>
    </dl>
    
<div class="toolBar2" style="padding-top: 20px; text-align: left">
	<a class="btnBlue yes" style="margin-left: 0"   onclick="$('#lsBox').show()"><span class="text">完成</span><b class="bgR"></b></a>
		<a class="btnGray btn" onclick="loadPage('mainHome.html','main');"><span class="text">取消</span><b class="bgR"></b></a>
	</div>
    
<div style="width: 100px; height:48px; border: 1px solid #ddd; line-height: 24px; background: #fff; position: absolute; display: none" id="lsBox">
<a  onclick="loadCont('组织与员工_批量导入_下载模板上传文档.html');">批量导入入口</a><br />
<a  onclick="loadCont('组织与帐号_LDAP同步1.html');">LDAP同步入口</a>
</div>
<!--[if IE 6]>
<script type="text/javascript">
	DD_belatedPNG.fix('.btn_icon, .btn_icon .iconL, .btn_icon .iconR');
</script>
<![endif]-->

<script type="text/javascript">
	function addNewItem() {
	
		var val = $("#newInfo").val();
		var isValid=/*(val.length==30)? true : false*/ (val!='')?true:false
		var str;
		if($("input[name='writeTagName']:checked").val()=="1"){
			str = "此标签为员工填写"
		}
		else {
			str = "此标签为管理员填写";
		}
		if(!isValid) {
			$("#newInfo").focus().parent(".inputBox").addClass("error");
			return false;	
		}
		else {
			var newItem = '<tr>'+
                    '<td><label class="checkbox checked fl" style="margin-right: 5px"><input type="checkbox" checked="checked" /><span>'+ val +'</span><span class="gray">('+ str +')</span></label><a class="btnGray2 btn fl" style="margin-right: 5px; margin-top: 2px"  onclick="editNewItem(this)"><span class="text">编辑</span><b class="bgR"></b></a> '+
                    	'<a class="btnGray2 btn fl"  style="margin-top: 2px;"  onclick="deleteNewItem(this)"><span class="text">删除</span><b class="bgR"></b></a></td>'+
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







