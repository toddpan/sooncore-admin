<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">重置密码</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定要重置{$user_name}的登录密码吗？<br />重置后系统将给{$user_name}发送临时登录密码</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="assure_resetPwd()"><span class="text">确认</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
	// 重置密码
	function assure_resetPwd()
	{
		var user_id = {$user_id};
		var obj = {
				"user_id":user_id
				};
		var path = 'manager/reset_pwd';
		$.post(path, obj, function(data)
			{
				var json=$.parseJSON(data);
				if(json.code == 0)
					{
						var p_user_id = json.data.p_user_id;
						if(p_user_id == user_id)
						{
							showDialog('manager/reset_pwd_suc');
						}else{
							hideDialog();
						}
					}
				else
					{
						alert(json.msg);
					}
			}		
		);
	}
</script>