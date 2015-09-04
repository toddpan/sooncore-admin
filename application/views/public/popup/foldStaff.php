<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">调入员工</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<table width="100%" class="table1">
          <tr>
            <th scope="col" width="194">选择员工</th>
            <th scope="col" width="67">&nbsp;</th>
            <th scope="col">已指定员工</th>
          </tr>
          <tr>
            <td><div class="combo searchBox" style="margin-bottom: 10px;">
                    <b class="bgR"></b>
                    <a class="icon" ></a>
                    <label class="label">通过关键字查找</label>
                    <input class="input" />
                </div>
                <div class="treeLeft" >
                    <div class="pop-box-content">
                        <ul class="ztree" id="treeLeft"></ul>
                    </div>
                </div>
            </td>
            <td>
            	<a  onclick="addToRight()" class="btn"><span class="text">添加 ></span><b class="bgR"></b></a> <br /><br />
                <a  onclick="deleteToLeft()" class="btn"><span class="text">< 删除</span><b class="bgR"></b></a> 
            </td>
            <td>
				<div class="treeRight"> 	
                </div>
			</td>
          </tr>
        </table>

	</dd>
   
	<dd class="dialogBottom">
		<a class="btnBlue yes"  onclick="importNewMembers(this)"><span class="text">调入员工</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
var org_first_path ='organize/get_first_org_user';
var cost_get_staff='organize/get_next_orguser_list';//组织结构和成本中心部分的调入员工
//点击调入员工
function importNewMembers(t){
	if($(t).hasClass("false"))
	{
		return;
	}
	$(t).addClass("false");
	var _t=$(t);
	var a=$('.treeRight a');
	if(a.length==0)
	{
		alert("请指定要调入的员工");
		$(t).removeClass("false");
		return false;
	}
	var users='';
	$('.treeRight a').each(function()
	{
		users=users+'{"userid":'+$(this).attr("id")+',"user_name":"'+$(this).text()+'","orgid":'+$(this).attr("nodepid")+',"org_name":"'+$(this).attr("name")+'","org_pid":"'+$(this).attr("orgpid")+'","org_code":"'+$(this).attr("orgcode")+'"},';//加user_name
	});
	users=DelLastComma(users);
	var zTree = $.fn.zTree.getZTreeObj("ztree");
	var Node=zTree.getSelectedNodes();
	var id_2=Node[0].pId;
	var org_code="-"+Node[0].id;
	var node;
	while(zTree.getNodesByParam('id',id_2,null)[0]!=null)
	{
		 node=zTree.getNodesByParam('id',id_2,null)[0];
		id_2=node.pId;
		org_code ='-'+node.id+org_code;;
	   // value.push(node.name);
	   // id_value.push(node.id);

	}
	//alert(Node[0].id)
	if(page==1)
	{
		var obj={
			"org_pid":Node[0].pId,//新建组织的父id
			"org_id":Node[0].id,//新建组织id
			"org_code":org_code,//新组的id串
			"org_name":Node[0].name,//新建组织名称
			"user_id":users
		};
		//alert(users)
		var path_fold_staff = 'staff/neworg_move_staff';
		$.post(path_fold_staff,obj,function(data)
		{
			//alert(data)
			var json=$.parseJSON(data);
			if(json.code==0)
			{	
				var objN={
						"parent_orgid":Node[0].pId,
						 "org_id":Node[0].id
				 }
				load_staff(objN,path_user,path_mag);
				hideDialog();
			}
			else
			{
				
					alert(json.prompt_text)	
				
				hideDialog();
			}
			_t.removeClass("false");
		})
	}
	else
	{
			var obj={
			//"org_pid":Node[0].pId,//新建组织的父id
			"id":Node[0].id,//新建组织id
			//"org_code":org_code,//新组的id串
			//"org_name":Node[0].name,//新建组织名称
			"user_ids":users
		};
		//alert(users)
		var path_fold_staff = 'costcenter/addMembers';
		$.post(path_fold_staff,obj,function(data)
		{
			//alert(data)
			var json=$.parseJSON(data);
			if(json.code==0)
			{	
				var Tree=$.fn.zTree.getZTreeObj("ztree2");
				var org=Tree.getSelectedNodes();
				if(org[0]==null)
				  {
					  org=0;
				  }
				  else
				  {
					  org=org[0].id;
				  }
				var obj = {
						  "id":Node[0].id,
						  "org_id":org,
						  "count":0,
						  "page":0
					};
				load_staff_center(obj, path_cost_user);
				hideDialog();
			}
			else
			{
				
					alert(json.prompt_text)	
				
				hideDialog();
			}
			_t.removeClass("false");
		})
	}
	//$("#novalueTable").hide().prev("table").show();
	//$("#tree .bbit-tree-selected").parent("li").removeClass("new-node");
	
}
function create_node(Nodes)
{
  var leng=Nodes.length;
  //alert(Len)
  // alert(leng)
 
   for(var i=0; i<leng;i++)
	{
	  if(Nodes[i].isParent>0)
	  {   var count=0;
	    for(var j=0;j<leng;j++)
		{
		    if(Nodes[i].id==Nodes[j].pId)
			{
			 // alert(Nodes[i].name)
			  count++;
			}
		}
		if(count==0)
	    {
	   //alert(count)
	    var node={id:1,pId:Nodes[i].id,name:''};
	    Nodes.push(node);
	    }
		//Nodes[i].=false;
	 }
	 if(Nodes[i].userCount>0)
	 {
	     var node={id:1,pId:Nodes[i].id,name:''};
	    Nodes.push(node);
	 }
   }
}
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
 	var zTree = $.fn.zTree.getZTreeObj("treeLeft");
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
//从左组织添加员工到右边
function addToRight() {
	var zTree = $.fn.zTree.getZTreeObj("treeLeft");
	var Node=zTree.getSelectedNodes();
	if(Node==null)
	{
		alert("请选中要指定的员工，再添加");
		return false;
	}
	//var path_getOrg_staff;
	var tip=0;
	$('.treeRight a').removeClass("selected");
	for(var i=0;i<Node.length;i++)
	{
		var fold_staff='';
		//alert(Node[i].identity)
	     //if(Node[i].userCount>0)//有员工
        if(Node[i].identity==1 && !Node[i].isHidden)
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
				var parent=Node[i].getParentNode();
				var nodes = zTree.getNodesByParam("isHidden",true,parent);
				var child=parent.children;
				if(child.length==nodes.length)
				{
					parent.isParent=0;
					zTree.updateNode(parent);
				}
		 }
        else//选中的是组织
        {
			
            if(Node[i].userCount>0)//选中的有员工
            {
				if(!Node[i].isParent)
				{
					//alert("该部门员工已经全部添加，当前没有员工");
					tip++;
					zTree.cancelSelectedNode(Node[i]);
					continue;
					//
					//return;
				}
				
				var num=0;
				var childNode=Node[i].children;
				var staff_length=0;
				var hide_staff=0;
				for(var aa=0;aa<childNode.length;aa++)
				{
					if(childNode[aa].identity==1)
					{
						staff_length++;
					}
					if(childNode[aa].isHidden)
					{
						hide_staff++;
					}
				}
				if(staff_length==hide_staff)
				{
					//alert("该部门员工已经全部添加，当前没有员工");
					if(Node[i].isParent)
					{
						tip++;
					}
					zTree.cancelSelectedNode(Node[i]);
					continue;
					//
					//return;
				}
				var ND=zTree.getNodeByParam('id',Node[i].id,null);
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
				//alert(childNode.length)
				for(var j=0;j<childNode.length;j++)
				{
					if(childNode[j].identity==1 && !childNode[j].isHidden)
					{
						//alert(childNode[j].name)
						//var node =childNode[j].getNextNode();
						if(childNode[j].getNextNode()==null)
						{
							node =0;
						}
						else
						{
							node=childNode[j].getNextNode();
						}
						fold_staff=fold_staff+'<a id='+childNode[j].id+' nodepid='+childNode[j].pId+' name=' +Node[i].name+ ' orgpid='+Node[i].pId+'  orgcode='+org_code+' next='+node.id+' style="cursor: pointer">'+childNode[j].name+'</a>';	
						num++;
						zTree.hideNode(childNode[j]);
						hide_staff=0;
						//var nodes = zTree.getNodesByParam("isHidden",true,Node[i]);
						for(var aa=0;aa<childNode.length;aa++)
						{
							if(childNode[aa].isHidden)
							{
								hide_staff++;
							}
						}
						if(childNode.length==hide_staff)
						{
							Node[i].isParent=0;
							zTree.updateNode(Node[i]);
						}
					}
					
				}
				//alert(fold_staff)
				$('div.treeRight').append(fold_staff);
				
            }
            else
            {
                //alert('该部门现没有员工');
				tip++;
            }
        }
		 zTree.cancelSelectedNode(Node[i]);
	}
	if(tip==Node.length)
	{
		alert("当前没有可调入的员工")
	}
}
//删除右边的添加的员工
function deleteToLeft(){
	var zTree = $.fn.zTree.getZTreeObj("treeLeft");
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

	$(function(){
		
		$.post(org_first_path,[],function(data)
		{
			var org_zNodes=[];
			org_zNodes=eval('(' +data + ')');
            create_node(org_zNodes);
            //$.fn.zTree.init($("#costtreeLeft"),costSetting, org_zNodes);
            $.fn.zTree.init($("#treeLeft"),foldSetting,org_zNodes);
            var zTree = $.fn.zTree.getZTreeObj("treeLeft");//create_node(org_zNodes);
            var treeNode=zTree.getNodes();
            if(treeNode[0].open==true)
            {

                post_add_staff(treeNode[0],cost_get_staff,zTree,1);
            }


            //if(org_zNodes.)
			//alert(cost_zNodes)
		})
		$('#treeLeft a').die("click");
	    $('#treeLeft a').live("click",function()
          {
              disable_select();
          })
		 $('.treeRight a').die("click");
	     $('.treeRight a').live("click",function()//右边选中事件
          {
		      if($(this).hasClass("selected"))
			{
				$(this).removeClass("selected");
			}
			else
			{
				$(this).addClass("selected");
			}
          })
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		
		
	});

</script>