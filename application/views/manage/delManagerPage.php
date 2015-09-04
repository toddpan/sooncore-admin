<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">您确定要移除所选的管理员吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue btn_confirm" onclick="dele_admin()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function dele_admin()
{
	var admin_ids='';
	$('#self_staff tbody span.checkbox').each(function()
	{
		if($(this).hasClass("checked"))
		{
			admin_ids=admin_ids+$(this).parent().next().find("a.manage").attr("target")+',';
		}
	})
	var staff_tag_post = admin_ids;
    var lastIndex = staff_tag_post.lastIndexOf(',');
    if (lastIndex > -1) {
        staff_tag_post = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
    }
    admin_ids =staff_tag_post;
	var path='manager/delManager';
	var obj={
		"ids":admin_ids
	};
	$.post(path,obj,function(data)
	{
		if(data.code==0)
		{
			$('#self_staff tbody span.checked').parent().parent().remove();
			hideDialog();
			$(" #dete_btn_admin").hide();
		}
		else
		{
			alert(data.msg);
			hideDialog();
		}
	},'json')
}
</script>
