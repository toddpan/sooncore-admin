<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">删除成本中心</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">删除该成本中心后，员工将移至“未指定 成本中心“，确定要删除吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="deleteCenter_assure();"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function deleteCenter_assure()
{
	//alert(121)
	
	//alert(cost_id)
	var zTree = $.fn.zTree.getZTreeObj("ztreecostcenter");
	var treeNode=zTree.getSelectedNodes();
	var delete_cost="costcenter/delGroup";//点击删除成本中心
	//var path = '<?php echo site_url('costcenter/save_del_costcenter')?>';
	var obj={
		"id":treeNode[0].id
	}
	$.post(delete_cost,obj,function(data)
	{
		//alert(data)
		var json=$.parseJSON(data);
		if(json.code==0)
		{
			 zTree.removeNode(treeNode[0], true);
			/*$("#centerTree li.selected").remove();
			$("#centerTree li:first").click();*/
		}
		else
		{
			if(json.code==30000 || json.code==200000)
			{
				alert(json.msg)
			}
		}
	})
	
	
	hideDialog();
}
$(function(){
		
})
</script>