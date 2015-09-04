<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">删除敏感词</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01" id="<?php echo $single_sensitive_word['id'];?>">您确定要删除敏感词“<?php echo $single_sensitive_word['Word'];?>”吗？</span>
	</dd>
    <div class="dialogBottomLeft">
        <label class="checkbox" onclick="check_tip(this)"><input type="checkbox" />下次不再提醒</label>
    </div>
	<dd class="dialogBottom">
    	
		<a class="btnBlue yes " id="del_sensitive"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function check_tip(t)
{
	if($(t).hasClass("checked"))
	{
		$(t).removeClass("checked");
	}
	else
	{
		$(t).addClass("checked");
	}
}
</script>