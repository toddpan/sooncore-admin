<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">删除部门</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">该部门内的员工已移除完毕，确定要删除该部门吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btn yes"  onclick="assure_delete_org()"><span class="text" style="width: 50px;">确定</span><b class="bgR"></b></a>
		<a class="btn"  onclick="hideDialog();"><span class="text" style="width: 50px;">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
 function assure_delete_org()
 {
   var zTree = $.fn.zTree.getZTreeObj("ztree");
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
					   var leng=zNodes.length
					   for(var i=0;i<leng;i++)
					   {
					      if(zNodes[i].id==treeNode.id && zNodes[i].pId==treeNode.pId)
						  {
						     zNodes.splice(i,1);
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
					alert(json.prompt_text)	
				}
			})
		}
 }
 </script>