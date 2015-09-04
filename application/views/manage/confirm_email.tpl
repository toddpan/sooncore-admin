	
<!-- 弹窗_管理员管理_添加管理员.html -->
<dl class="dialogBox D_addAdmin">
	<dt class="dialogHeader">
		<span class="title">添加管理员</span>
		<a class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody" style="overflow: inherit">
		<table class="infoTable">
			<tr>
				<td>通知邮箱：</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
					<div class="inputBox w300" style="z-index:100">
						<label class="label" ></label>
						<input class="input" value="{$email}" id="email" />
					</div>
                </td>
				<td style="text-align: right;">&nbsp;<span id="error" style="display:none;color:red;margin-right: 20px;text-align: center;">请输入正确的邮箱</span></td>
			</tr>
		</table>
	</dd>
	<dd class="dialogBottom">
		<!-- <a class="btnGray" style="margin-right:100px;"><span class="text">上一步</span><b class="bgR"></b></a> -->
		<a class="btnBlue"><span class="text" style="width:100px;">完成并发送通知</span><b class="bgR"></b></a>
		<a class="btnGray" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>


<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="public/js/part_js/addmangerdialog.js"></script>
<script type="text/javascript" src="public/js/part_js/addmanger_tree.js"></script>
<script type="text/javascript" src="public/js/tree.js"></script>
<script type="text/javascript" src="public/js/self_tree.js"></script>
<script type="text/javascript">
$(function(){
	if($('#email').val() == ''){
		$('#email').prev().find('label').text('请输入邮箱');
	}
});
var user_id = {$user_id};
var id = {$id};

// 当输入框获得焦点时，清空提示消息
$('#email').focus(function(){
			$(this).prev().find('label').text('');
		});
		
// 当输入框失去焦点时，如果input的value为空，则显示提示信息
$('#email').blur(function(){
	if($(this).val() == ''){
		$(this).prev().find('label').text('请输入邮箱');
	}
});

// 点击“完成并发送通知”按钮，验证邮箱格式，发送邮件，关闭对话框，并跳转到管理员详情页面
$('.btnBlue').click(function(){
	$('#error').hide();
	
	email = $('#email').val();
	reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	
	if(!reg.test(email)){
		$('#email').parent().addClass("error");
		$('#error').show();
		return false;
	}
	
	var sendObj = { "user_id":user_id,"email":email };
	$.post('manager/send_mail', sendObj, function(data){
		adminstaff_infor(this, user_id, id);
		hideDialog();
	});
});

</script>