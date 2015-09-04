// JavaScript Document
//生态企业网页4
function addToRight(e) {
    var zTree = $.fn.zTree.getZTreeObj("org_treeLeft");
    var checkNode = zTree.getCheckedNodes();
	var Node=[];
    if (checkNode == null) {
        alert("请选中要指定的员工，再添加");
        return false;
    }
	for(var i=0;i<checkNode.length;i++)
	{
		if(checkNode[i].identity==1)
		{
			Node.push(checkNode[i]);
			zTree.checkNode(checkNode[i],false,false);
		}
		else
		{
			zTree.checkNode(checkNode[i],false,false);
		}
	}
    var fold_staff = '';
    $('.treeRight a').removeClass("selected");
    for (var i = 0; i < Node.length; i++) {
		if(Node[i].identity==1)
		 {
			 	var ND=zTree.getNodeByParam('id',Node[i].pId,null);
				var id_2=ND.pId;
				var org_code='-'+ND.id;
				var node;
				while(zTree.getNodesByParam('id',id_2,null)[0]!=null)
				{
					 node=zTree.getNodesByParam('id',id_2,null)[0];
					id_2=node.pId;
					org_code ='-'+node.id+org_code;
				   // value.push(node.name);
				   // id_value.push(node.id);
			
				}
				if(Node[i].getNextNode()==null)
				{
					node =0;
				}
				else
				{
					node=Node[i].getNextNode();
				}
                fold_staff='<a id='+Node[i].id+' nodepid='+Node[i].pId+' name=' +ND.name+ ' orgpid='+ND.pId+'  orgcode='+org_code+' next='+node.id+' style="cursor: pointer">'+Node[i].name+'</a>';							//alert(fold_staff)
                $('div.treeRight').append(fold_staff);
				zTree.hideNode(Node[i]);
				zTree.checkNode(Node[i],false,false);
				var parent=Node[i].getParentNode();
				var nodes = zTree.getNodesByParam("isHidden",true,parent);
				var child=parent.children;
				if(child.length==nodes.length)
				{
					parent.isParent=0;
					zTree.updateNode(parent);
				}
				
		 }
    }

}

function deleteToLeft() {
	var zTree = $.fn.zTree.getZTreeObj("org_treeLeft");
	if($(".treeRight a.selected").length==0)
   {
   		alert('请选中指定的员工，再进行删除')
		return false;
   }
   $(".treeRight a.selected").each(function()
   {
   		var newNode= {id:$(this).attr("id"), pId:$(this).attr("nodepid"), isParent:false, name:$(this).text(),identity:1};
		var treeNode= zTree.getNodesByParam("id", newNode.id, null);
		zTree.showNode(treeNode[0]);
		var parentNode= zTree.getNodesByParam("id", newNode.pId, null);
		//alert(parentNode[0].isParent)
		if(!parentNode[0].isParent)
		{
			parentNode[0].isParent=true;
			zTree.updateNode(parentNode[0]);
		}
   })
    $(".treeRight a.selected").remove();
}
function nextStep4(event) {
    var users = '';
    $('.treeRight a').each(function() {
        //staff=staff+'{id:'+$(this).attr("id")+',name:'+$(this).text()+'},'
        users = users + '{"userid":' + $(this).attr("id") + ',"orgid":' + $(this).attr("nodepid") + '},';
    });

    users = DelLastComma(users); //员工
    var obj4 = {
        "company_information": obj1_json,
        "company_power": obj2_json,
        "company_adminstrator": obj3_json,
        "company_staff": users,
        "org_id": company_ecol_id
    };
    //alert(obj1_json)
    //alert(obj2_json)
    //alert(obj3_json)
    //alert(users)
    if (users != '') {
        var path = 'ecology/creatEcologyCompany';
        $.post(path, obj4,
        function(data) {
            //alert(data);
            var json = $.parseJSON(data);
            if (json.code == 0) {
				var dgtree = $.fn.zTree.getZTreeObj("stqyTree");
                var nodes1 = dgtree.getSelectedNodes();
				var new_node=json.other_msg;
                dgtree.addNodes(nodes1[0],new_node);
				var newNode=dgtree.getNodesByParam("id", new_node.id, null);
				dgtree.updateNode(nodes1[0]);
				dgtree.selectNode(newNode[0]);
				$('#stqyTree').find("a.curSelectedNode").trigger("click");
				$('.new_ecology').html('');
                $('.init_stqy_page').show();
            }
			else
				{
					alert(json.prompt_text);
				}
        })
    }
    /*  var hash = location.hash;
    if(hash == "#company"){
        loadPage('<?php echo site_url('ecologycompany/ecologyPage')?>','company');
    }else{
        loadPage('init-stqy3.html','companyAdmin');
    }*/
}
function create_node(Nodes) {
    var leng = Nodes.length;
    for (var i = 0; i < leng; i++) {
        if (Nodes[i].userCount > 0) {
            Nodes[i].nocheck = false;
			Nodes[i].isParent=true;
        }
    }
}
$(function() {
    //var treedata3 = [createNode3()];
   
    /*$(".ldapSetCont .treeLeft").treeview({
     showcheck:true,
     data:treedata3
     });*/
    $(".treeRight a").die("click");
    $(".treeRight a").live("click",
    function() {
        $(this).addClass("selected");
    })

    $('.searchBox .icon').click(function() {
        $('.treeLeft div').each(function() {

            if ($(this).attr("title") == $('.searchBox input').val()) {
                $(this).addClass("bbit-tree-selected");
            }
        })
    })
})