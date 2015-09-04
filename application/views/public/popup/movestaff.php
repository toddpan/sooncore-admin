<dl class="dialogBox D_addAccounts">
	<dt class="dialogHeader">
		<span class="title">调岗员工</span> <a class="close"
			onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">请选择想要调岗的部门</span>
		<div
			style="height: 200px; margin: 10px 0 0; border: 1px solid #ddd; background: #fff; overflow: auto">
			<!--<div class="pop-box-content">-->
			<ul class="ztree" id="dgmoveorg">
			</ul>
			<!--</div>-->
		</div>
	</dd>
	<dd class="dialogBottom">
		<a class="btn yes" id="move_staff_part"><span class="text">调岗到该部门</span><b
			class="bgR"></b></a> <a class="btnGray btn btn_cancel"
			onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
var key=0;
 $(".btnChangeUser_O").removeClass("false");
 //点击调岗的事件在organizestaff里面
clear_null++;
function showTreeList(event) {
	//var cityObj = $("#departmentSel");
	//var cityOffset = $("#departmentSel").position();
	$('#treeOption').toggle();
	
	$("body").bind("mousedown", onTreeListDown);
}
function hideTreeList() {
	$("#treeOption").fadeOut("fast");
	$("body").unbind("mousedown", onTreeListDown);
}
function onTreeListDown(event) {
	if (!(event.target.className == "icon" || event.target.className == "text" || event.target.id == "treeOption" || $(event.target).parents("#treeOption").length>0)) {
		hideTreeList();
	}
}
function disable_select()
{
    //alert(1);
 	var zTree = $.fn.zTree.getZTreeObj("dgmoveorg");
	var treeNode=zTree.getSelectedNodes();
	if(treeNode[0]!=null)
	{
	 
	   if(treeNode[0].isDisabled==true)
	    {
		  //alert(21);
	      zTree.cancelSelectedNode(treeNode[0]);
	    }
	}
}
function Initmoveorg(node) //初始化组织结构树
    {
       	
		//create_node(node);
		//var zTree = $.fn.zTree.getZTreeObj("ztree");
		//var treeNode=zTree.getSelectedNodes();
		$.fn.zTree.init($("#dgmoveorg"),moveSetting,node);
		//var org=$.fn.zTree.getZTreeObj("dgmoveorg");
		//var Nodes=org.getNodesByParam("id",treeNode[0].id,  null);
		/*org.hideNode(Nodes[0]);
		if(Nodes[0].isParent)
		{
			org.showNode(Nodes[0].children);
			//alert(1212)
		}*/
		/*var parent=Nodes[0].getParentNode();
		var nodes =org.getNodesByParam("isHidden",true,parent);
		var child=parent.children;
		if(child.length==nodes.length)
		{
			parent.isParent=0;
			org.updateNode(parent);
		}*/
    }
$(function(){
		 $('#dgmoveorg a').die("click")
	     $('#dgmoveorg a').live("click",function()
          {
		      
              disable_select();
          })
		Initmoveorg(zNodes);
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		 $(window).keydown(function(e){
			if(e.ctrlKey){
				key=1;
			}else if(e.shiftKey){
				key=2;
			}
		}).keyup(function(){
				key=0;
		});
			
	});

	
</script>
