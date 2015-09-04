<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">您是否要从现有的组织结构中指定员工到此成本中心当中？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes"  onclick="assure_cost()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>

<script type="text/javascript">
function assure_cost()
{
	//hideDialog();

		showDialog('costcenter/addstaff');
		//showDialog('<?php echo site_url('organize/foldStaff'); ?>');
		
	
}
</script>