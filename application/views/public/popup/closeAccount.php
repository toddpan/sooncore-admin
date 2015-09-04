<!--弹窗_关闭蜜蜂帐号.html-->
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">关闭帐号</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定要关闭<?php echo $user_name;?>的蜜蜂帐号吗？<br />关闭后<?php echo $user_name;?>将无法登录蜜蜂</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes" id="closeAccount"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
