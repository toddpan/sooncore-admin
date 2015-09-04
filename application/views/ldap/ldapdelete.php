<!--弹窗_删除LDAP设置.html-->
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">删除LDAP设置</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定要将LDAP设置删除吗？</span>
        <p style="padding: 10px 0">此LDAP设置已经同步的信息将被保留。</p>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue" id="delet_create_ldap"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function deleteLDAPList() {
	hideDialog();
	$(".table tbody .checked").parents("tr").remove();
	$(".table thead .checkbox").removeClass("checked");
	$(".table thead input[type='checkbox']").removeAttr("checked");
	$(".tabToolBar .tabToolBox").hide();
	if($(".table tbody tr").length<=1) {
		$(".table thead .checkbox").hide();
	}
}
</script>
