<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="submitTips text01">您的密码已重置为默认密码，请重新登录系统。</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="hideDialog();jump();"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
	function jump()
	{
		location.href= '{$site_url}';
	}
</script>
