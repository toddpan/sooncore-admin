<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">添加生态合作员工</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<table width="100%" class="table1">
          <tr>
            <th scope="col" width="194">选择员工</th>
            <th scope="col" width="67">&nbsp;</th>
            <th scope="col" width="171">已选员工</th>
          </tr>
          <tr>
            <td><div class="combo searchBox" style="margin-bottom: 10px;">
                    <b class="bgR"></b>
                    <a class="icon" ></a>
                    <label class="label">通过关键字查找</label>
                    <input class="input" />
                </div>
                <div class="treeLeft">
                	 <div class="pop-box-content">
                        <ul class="ztree" id="treeLeft"></ul>
                    </div>
                </div>
            </td>
            <td>
            	<a  onclick="addToRight()" class="btn"><span class="text">添加 ></span><b class="bgR"></b></a> <br /><br />
                <a  onclick="deleteToLeft()" class="btn"><span class="text">< 删除</span><b class="bgR"></b></a> 
            </td>
            <td><div class="treeRight">
                	
                </div></td>
          </tr>
        </table>

	</dd>
   
	<dd class="dialogBottom">
		<a class="btnBlue btn_confirm"  onclick="hideDialog();"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
//点击调入员工
function importNewMembers(){
	var a=$('.treeRight a');
	if(a.length==0)
	{
		alert("请指定要调入的员工")
		return false;
	}
	var users='';
	$('.treeRight a').each(function()
	{
		users=users+'{"userid":'+$(this).attr("id")+',"user_name":"'+$(this).text()+'","orgid":'+$(this).attr("class")+',"org_name":"'+$(this).attr("name")+'","org_pid":"'+$(this).attr("orgpid")+'","org_code":"'+$(this).attr("orgcode")+'"},';//加user_name
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
			}
	})
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
	  if(Nodes[i].childNodeCount>0)
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
	var fold_staff='';
	$('.treeRight a').removeClass("selected");
	for(var i=0;i<Node.length;i++)
	{
		//alert(Node[i].userCount)
	     //if(Node[i].userCount>0)//有员工
        if(Node[i].identity==1)
		 {
             var common_staff=0;
             var a_leng=$('.treeRight a');
             if(a_leng.length>0)
             {
                 $('.treeRight a').each(function(){
                     if($(this).attr("id")==Node[i].id && $(this).attr("class")==Node[i].pId)
                     {
                         $(this).addClass("selected");
                         common_staff=1;

                     }
                 })
             }
             if(common_staff!=1)
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
                 fold_staff='<a id='+Node[i].id+' class='+Node[i].pId+' name=' +ND.name+ ' orgpid='+ND.pId+'  orgcode='+org_code+' style="cursor: pointer">'+Node[i].name+'</a>';							//alert(fold_staff)
                 $('div.treeRight').append(fold_staff);
             }
		   	/*var obj={
		       "org_id":Node[i].id,
			   "type":1
		   		}
			$.post(cost_get_staff,obj,function(data)
			{
				//alert(data)
			   var json=$.parseJSON(data);
			   if(json.code==0)
			   {
			   		//var json=$.parseJSON(data);
					var node=json.other_msg.users;
					var childNodes=eval('(' +node+ ')');
					//alert(node)
					//alert(childNodes.length)
					var leng=childNodes.length;
			    	for(var j=0;j<leng;j++)
					{
						if(childNodes[j].identity==1)
						{
							var common_staff=0;
							var a_leng=$('.treeRight a');
							if(a_leng.length>0)
							{
								$('.treeRight a').each(function(){
									if($(this).attr("id")==childNodes[j].id && $(this).attr("class")==childNodes[j].pId)
									{
										$(this).addClass("selected");
										common_staff=1;
										
									}
								})
							}	
							if(common_staff!=1)
							{
								fold_staff='<a id='+childNodes[j].id+' class='+childNodes[j].pId+' style="cursor: pointer">'+childNodes[j].name+'</a>';							//alert(fold_staff)
								$('div.treeRight').append(fold_staff);
							}
						}
				
					}
			      
			   }
			})		   
		 }
		 else//没有有员工，选中的是组织或者是员工
		 {
		 	if(Node[i].identity==1)//选中的为员工
			{
				var common_staff=0;
				var a_leng=$('.treeRight a');
				if(a_leng.length>0)
				{
					$('.treeRight a').each(function()
					{
						if($(this).attr("id")==Node[i].id && $(this).attr("class")==Node[i].pId)
						{
							$(this).addClass("selected");
							common_staff=1;
							
						}
				
					})
				}	
				if(common_staff!=1)
				{
					fold_staff='<a id='+Node[i].id+' class='+Node[i].pId+' style="cursor: pointer">'+Node[i].name+'</a>';
					$('div.treeRight').append(fold_staff);
				}
			}*/
		 }
        else//选中的是组织
        {
            if(Node[i].userCount>0)//选中的有员工
            {
                var obj={
                    "org_id":Node[i].id,
                    "type":1
                }
                $.post(cost_get_staff,obj,function(data)
                {
                    //alert(data)
                    var json=$.parseJSON(data);
                    if(json.code==0)
                    {
                        //var json=$.parseJSON(data);
                        var node=json.other_msg.users;
                        var childNodes=eval('(' +node+ ')');
                        //alert(node)
                        //alert(childNodes.length)
                        var leng=childNodes.length;
                        for(var j=0;j<leng;j++)
                        {
                            if(childNodes[j].identity==1)
                            {
                                var common_staff=0;
                                var a_leng=$('.treeRight a');
                                if(a_leng.length>0)
                                {
                                    $('.treeRight a').each(function(){
                                        if($(this).attr("id")==childNodes[j].id && $(this).attr("class")==childNodes[j].pId)
                                        {
                                            $(this).addClass("selected");
                                            common_staff=1;

                                        }
                                    })
                                }
                                if(common_staff!=1)
                                {
                                    var ND=zTree.getNodeByParam('id',childNodes[j].pId,null);
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
                                    fold_staff='<a id='+childNodes[j].id+' class='+childNodes[j].pId+' name=' +ND.name+ ' orgpid='+ND.pId+'  orgcode='+org_code+' style="cursor: pointer">'+childNodes[j].name+'</a>';							//alert(fold_staff)
                                    $('div.treeRight').append(fold_staff);
                                }
                            }

                        }

                    }
                })
            }
            else
            {
                alert('该部门现没有员工');
            }
        }
		 zTree.cancelSelectedNode(Node[i]);
	}
}
//删除右边的添加的员工
function deleteToLeft(){
	if($(".treeRight a.selected").length==0)
   {
   		alert('请选中指定的员工，再进行删除')
		return false;
   }
	$(".treeRight a.selected").remove();
}

	$(function(){
	
	 	var org_zNodes=[];
		var org_first_path ='organize/get_first_org_user';
        var cost_get_staff='organize/get_next_orguser_list';//组织结构和成本中心部分的调入员工
		$.post(org_first_path,[],function(data)
		{
			org_zNodes=eval('(' +data + ')');
            create_node(org_zNodes);
            //$.fn.zTree.init($("#costtreeLeft"),costSetting, org_zNodes);
            $.fn.zTree.init($("#treeLeft"),foldSetting,org_zNodes);
            var zTree = $.fn.zTree.getZTreeObj("treeLeft");//create_node(org_zNodes);
            var treeNode=zTree.getNodes();
            if(treeNode[0].open==true)
            {

                post_add_staff(treeNode[0],cost_get_staff,zTree,1);
                /*$.post(path,obj,function(data){
                    var childNodes = eval('(' +data + ')');
                    org_zNodes.push(childNodes);
                }*/
            }


            //if(org_zNodes.)
			//alert(cost_zNodes)
		})
		$('#treeLeft a').die("click");
	     $('#treeLeft a').live("click",function()
          {
		     
              disable_select();
			 //alert($("#inputVal2").val())
			 var zTree = $.fn.zTree.getZTreeObj("treeLeft");
	         var treeNode=zTree.getSelectedNodes()[0];
			// alert(treeNode.identity)
			 if(treeNode!=null && treeNode.identity!=1)
			 { //加载自组织和子员工
                 post_add_staff(treeNode,cost_get_staff,zTree,1);
			   //$('#inputVal2').val(treeNode[0].name);
			  } 



          })
		  $('.treeRight a').die("click");
	     $('.treeRight a').live("click",function()//右边选中事件
          {
		      
             $('.treeRight a').removeClass("selected");
			 $(this).addClass("selected");

          })
		// if(clear_null==0)
		// {
		 //create_node(zNodes);
		// } 
		//obj="treeLeft";
		
		
		$('.infoTable .selectBox').combo({
			cont:'>.text',
			listCont:'>.optionBox',
			list:'>.optionList',
			listItem:' .option'
		});
		
		
		
	});

</script>