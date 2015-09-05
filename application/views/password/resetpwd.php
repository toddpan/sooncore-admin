<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">重置密码</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定要重置刘恺威的登录密码吗？<br />重置后系统将给刘恺威发送临时登录密码</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="assure_resetPwd()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
$('#dialog .dialogBody .text01').html('确定要重置'+count_name+'的登录密码吗？<br />重置后系统将给'+count_name+'发送临时登录密码');
function  assure_resetPwd()
{
	var zTree = $.fn.zTree.getZTreeObj("ztree");
	var nodes = zTree.getSelectedNodes();
	var treeNode = nodes[0];
	var org_ID=treeNode.id;
	var  staff_account={
		"orgid":org_ID,
		"user_id":click_staff_name
	 };
	// alert(org_ID)
	// alert(click_staff_name)
	 var path_resetPwd = 'staff/reset_pwd';
													// alert( staff_account.user_id)
	$.post(path_resetPwd,staff_account,function(data)
		{
			//alert(data);                                    
			 var json=$.parseJSON(data);
			 
			 if(json.code==0)
				{
					hideDialog();
				}
				else
				   {
				   		alert(json.prompt_text);
				   }
		})
	 
}
</script>