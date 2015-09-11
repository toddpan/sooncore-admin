<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">您可以从现有的组织结构中直接调入员工，或者下载带有员工信息标签的模板批量添加。</span>
	</dd>
	<dd class="dialogBottom">
<!--  		<a class="btn yes"  onclick="org_move(this)"><span class="text" style="width: 50px;">调入</span><b class="bgR"></b></a>-->
        <a class="btn yes"  onclick="hideDialog(); loadCont('batchimport/index')"><span class="text">下载模板并上传</span><b class="bgR"></b></a>
		<a class="btn"  onclick="hideDialog();"><span class="text" style="width: 50px;">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function org_move(t)
{//alert(1111)
	showDialog('organize/foldStaff');
	page=1;
	//hideDialog();
}
</script>