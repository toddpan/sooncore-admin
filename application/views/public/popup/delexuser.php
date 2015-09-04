<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">取消豁免</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01" >您确定要取消<?php echo $user_arr['lastName'];?>的敏感词豁免权吗？</span>
	</dd>
    <div class="dialogBottomLeft">
        <label class="checkbox"><input type="chexkbox" />下次不再提醒</label>
    </div>
	<dd class="dialogBottom">
    	
		<a class="btnBlue yes btn_confirm" ><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
checkbox();
</script>