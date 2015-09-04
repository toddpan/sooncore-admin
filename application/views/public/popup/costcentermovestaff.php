<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">移动</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<div id="dgTree" style="height: 200px; margin: 10px 0 0; border: 1px solid #ddd; background: #fff; overflow: auto">
        	<ul class="ztree" id="costcenterTree" style="display: block">
          </ul>
        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes long"  onclick="move_cost()"><span class="text">移动到该成本中心</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>

<script type="text/javascript">
//var path='<?php echo site_url('costcenter/listCostCenterPage')?>';
/*$.post(path,[],function(data){
	 $('#cost_list') .append(data);
	})*/
//初始化成本中心列表
/*var list = '';
var leng = cost_list.length;
for (var i = 0; i < leng; i++) {
    list = list + '<li><a  name="' + cost_list[i]['id'] + '">' + cost_list[i]['name'] + '</a></li>';
}
$('#cost_list').append(list);*/
function move_cost() {
    var move_cost_id;
    var cost_id;
	var zTree1 = $.fn.zTree.getZTreeObj("ztreecostcenter");
    var nodes1 = zTree1.getSelectedNodes();
    var treeNode1 = nodes1[0];
	if(treeNode1==null)
	{
		cost_id=0;
	}
	else
	{
		cost_id=treeNode1.id;
	}
	var zTree2 = $.fn.zTree.getZTreeObj("costcenterTree");
    var nodes2 = zTree2.getSelectedNodes();
    var treeNode2 = nodes2[0];
	if(treeNode2==null)
	{
		 move_cost_id=0;
	}
	else
	{
		 move_cost_id=treeNode2.id;
	}
	if (cost_id == null) {
        cost_id = 0;
    }
    var users = '';
	var user_ids=[];
	var i=0;
    $('#part02 table tbody label').each(function() {
        if ($(this).hasClass('checked')) {
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
    // alert(org_cost_id)
    var path = 'costcenter/moveMembers';
    var obj = {
        //"org_id":org_id,
        "from_id": cost_id,
        "to_id": move_cost_id,
        "user_ids": user_ids
    }
    $.post(path, obj,
    function(data) {
        //alert(data);
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

}
function addNewWord() {
    var _this = $('.con-wrapper').eq(1);
    _this.show().siblings('.con-wrapper').hide();
    hideDialog();
}
function Initcostcenter(Nodes) //初始化组织结构树
{
        //zNodes = <?php echo $org_list_json ;?>;
        var leng = Nodes.length;
        $.fn.zTree.init($("#costcenterTree"), moveSetting, Nodes);
}
$(function() {
	Initcostcenter(costNode);
    $(".select-item li").click(function() {
        $(this).addClass("selected").siblings().removeClass("selected");
    })
})
</script>