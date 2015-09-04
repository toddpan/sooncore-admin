<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">你确定要从成本中心一中删除选中的员工吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm"  onclick="assure_del()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function assure_del() {
    var cost_id;
    var zTreecost = $.fn.zTree.getZTreeObj("ztreecostcenter");
    var nodescost = zTreecost.getSelectedNodes();
    var treeNodecost = nodescost[0];
	 if (treeNodecost != null) {
         cost_id = treeNodecost.id;
    } else {
         cost_id = 0;
    }
    var users = '';
	var user_ids=[];
	var i=0;
    $('#part02 table tbody label').each(function() {
        if ($(this).hasClass('checked')) {
            //alert($(this).find('input').val()) 
			users = users + $(this).find('input').val() + ',';
			user_ids[i]=$(this).find('input').val();
			i++;
        }
    }) 
	var lastIndex = users.lastIndexOf(',');
    if (lastIndex > -1) {
        users = users.substring(0, lastIndex) + users.substring(lastIndex + 1, users.length);
    }
    //alert(users)
    //alert(cost_id)
    //alert(org_id)
    var path = 'costcenter/delMembers';
    //alert('<?php echo site_url('costcenter/move_cost_user');?>');
    var obj = {
        "id": cost_id,
        //"org_id": org_id,
        "user_ids": user_ids
    }
    $.post(path, obj,
    function(data) {
        //alert(data)
		var json = $.parseJSON(data);
        if (json.code == 0) {
			cost_del_staff();
            hideDialog();
        }
		else
		{
			alert(json.msg);
			hideDialog();
		}
    })
    //
    //hideDialog();
    //showDialog('<?php echo site_url('costcenter/addstaff'); ?>');

}
</script>