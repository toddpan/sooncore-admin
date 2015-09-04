<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定收回<?php echo $manager_name;?>的生态企业管理员权限吗？<br /> 收回后<?php echo $manager_name;?>管理的生态企业将由他的上级<?php echo $manager_pname;?>管理。</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes confirm_btn"  ><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
