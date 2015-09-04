<!--弹窗_删除LDAP设置.html-->
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">删除生态企业</span>
		<a class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">确定要将选中的生态企业删除吗？<br />删除后该企业将无法使用蜜蜂。</span>
        
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue btn_confirm" id="deleteEcology"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function assure_del()
{
    var zTree = $.fn.zTree.getZTreeObj("stqyTree");
    nodes = zTree.getSelectedNodes();
    treeNode = nodes[0];
    if(treeNode!=null)
    {//alert(5565)
        var treenode={
            "id":treeNode.id,
            "pId":treeNode.pId,
            "name":treeNode.name,
            "is_sure_del": 1//0去判断可以不可以删除[返回1\2\5]，1满足条件就可以真的删除[都可能返回]
        };

        //alert(treenode.id)
        //alert(treenode.pId)
        // alert(treenode.name)
        //删除组织：判断是否有1下级组织，2是否自己有员工3成功删除5当前组织可以进行删除4 删除失败
        var path_2 = 'organize/delOrg';
        $.post(path_2,treenode,function(data){
            //alert(data);
            var json = $.parseJSON(data);
            if(json.code == 0)
            {
                if(json.other_msg.state==3)
                {
                    var leng=stqyNodes.length
                    for(var i=0;i<leng;i++)
                    {
                        if(stqyNodes[i].id==treeNode.id && stqyNodes[i].pId==treeNode.pId)
                        {
                            stqyNodes.splice(i,1);
                            break;
                        }
                    }
                    zTree.removeNode(treeNode, true);
                }
                else if(json.other_msg.state==4)
                {
                    alert("删除失败");
                }
                hideDialog();

            }
			else
				   {
				   		alert(json.prompt_text);
				   }
        })
    }
}
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