// JavaScript Document
$(function(){
		//checkbox();
		/*if($('.conTabs li:eq(0)').hasClass("selected"))
		{
			//alert(23324)
			 Initstqy();//初始化生态企业
		}
       */
        $("#stqyTree li a").die("click");
        $("#stqyTree li a").live("click",function(e)
        {
          //company_apart_information(this);
            $(this).parents("div").addClass("first");
			if(!$(e.target).hasClass("button"))
			{
				if($(this).attr("target")!="1")
				{
					//alert(1)
					if($("#tree_company").hasClass('first'))
					{
						// $('.part0').remove();
						var zTree = $.fn.zTree.getZTreeObj("stqyTree");
						var nodes = zTree.getSelectedNodes();
						if(nodes[0]!=null)
						{
							var value=[];
							value.push(nodes[0].name);
							while(nodes[0].pId!=null)
							{
								nodes=zTree.getNodesByParam("id",nodes[0].pId,null);
								value.push(nodes[0].name);
							}
							var  staff_depart="";
							//staff_depart=' <div class="bread part0">';
							for(var i=value.length-1;i>0;i--)
							{
								staff_depart=staff_depart+'<span>'+value[i]+'</span>&nbsp;&gt;&nbsp';
							}
							staff_depart=staff_depart+'<span>'+value[i]+'</span>';
							$('.delGroup').removeClass('disabled');
							//staff_depart=staff_depart+"</div>";
							// $('.link_limitSet').after(staff_depart);
							//alert(2324);
							$('#part01 .part01_1 .bread').find('span').text('');
							$('#part01 .part01_1 .bread').find('span').append(staff_depart);
							//$('.groupLimit .toolBar2').next().remove();
							var zTree = $.fn.zTree.getZTreeObj("stqyTree");
							nodes = zTree.getSelectedNodes();
							treeNode = nodes[0];
							
							//alert(treeNode.pId)
							if(treeNode.pId==null)
							{
								var obj={
									"org_id":treeNode.id
								};
								//alert(treeNode.id)
								var path_first="ecologycompany/info";
								$.post(path_first,obj,function(data)
								{
								   // alert(data)
								   $('.part01_1').find(".cont-wrapper").remove();
									$('#part01 .part01_1').show();
									//$('.part01_1 .cont-wrapper').find("infoCont").remove();
									$('.part01_1 .bread').show();
									$('#part01 #part1').remove();
									$('.part01_1 .bread').after(data);
									//$('.part01_1').show();
								})
							}
							else 
							{
								var obj={
									"org_id":treeNode.id
								};
								//alert(treeNode.id)
								var path_other ="ecologycompany/info2";
								$.post(path_other,obj,function(data)
								{
								    //alert(data)
									 $('.part01_1').find(".cont-wrapper").remove();
									$('#part01 .part01_1').show();
									//$('.part01_1 .cont-wrapper').find("infoCont").remove();
									$('.part01_1 .bread').show();
									$('#part01 #part1').remove();
									$('.part01_1 .bread').after(data);
									//$('.part01_1').show();
								})
								
							}
							
							//alert(treeNode.isDisabled)
							/*if($(this).hasClass("curSelectedNode") && treeNode.isDisabled==false )
							{
								var org_ID=treeNode.id;//获得当前组织id
								var parent_orgid=treeNode.pId;
								//alert(org_ID);
								//alert(parent_orgid)
							  
								load_staff(obj,path_user,path_mag);
							}*/
						}
						//根据组id，异步获得员工信息
						//$('#tree').removeClass();
					}
		
				}
				else
				{
					$(this).attr("target","2")
				}
			}
        });
        //点击增加生态企业
      $('.addGroupcompany').click(function()
      {
          var dgtree = $.fn.zTree.getZTreeObj("stqyTree");
          var nodes1 = dgtree.getSelectedNodes();
          var treeNode1 = nodes1[0];
          var orgid="";
          if(treeNode1!=null)
          {
              orgid=treeNode1.id;

              // alert(orgid1)
          }
          id_2=treeNode1.pId;
          var org_code1='-'+treeNode1.id;
          while(dgtree.getNodesByParam('id',id_2,null)[0]!=null)
          {
              node=dgtree.getNodesByParam('id',id_2,null)[0];
              id_2=node.pId;
              org_code1='-'+node.id+org_code1;
              // value.push(node.name);
              // id_value.push(node.id);

          }
          var obj={
               "orgid":orgid,
              "org_code":org_code1
          };
		  var path="ecology/showEcology";
		   //loadPage('<?php echo site_url('ecologycompany/createEcologyCompany')?>' + '/' + orgid ,'company');
		   $.post(path,obj,function(data)
		   {
		   		$('.new_ecology').remove();
				$('.init_stqy_page').hide();
		   		$('.init_stqy_page').after(data);
				
		   });
		  /* $('#setStqySuccess').click(function()
		   {
		   	
		   })*/
         // loadPage('<?php echo site_url('ecologycompany/createEcologyCompany')?>','company');
         /* var path='<?php echo site_url('ecologycompany/valid_eco_2')?>';
          $.post(path,obj,function(data)
          {
              var json= $.parseJSON(data);
              if(json.code==0)
              {
                  loadPage('<?php echo site_url('ecologycompany/createEcologyCompany')?>' + '/' . orgid ,'company');
              }
          })*/

      });
       //点击删除组织
	  $('#delet_group').click(function()
      {
          	showDialog("ecology/deleteEcologyCompany");
			var zTree = $.fn.zTree.getZTreeObj("stqyTree");
			var treeNode = zTree.getSelectedNodes();
			$('#dialog #deleteEcology').die('click');
			$('#dialog #deleteEcology').live('click',function()
			{
				var obj={
				"ecology_id":treeNode[0].id
				};
				var path="ecology/deleteEcology";
				$.post(path,obj,function(data)
				{
					//alert(data)
					var json=$.parseJSON(data);
					if(json.code==0)
					{
						//alert(1111)
						zTree.removeNode(treeNode[0],true);
						var nodes =zTree.getNodes();
						zTree.selectNode(nodes[0]);
						 var Node = zTree.getSelectedNodes();
						if(Node[0].pId==null)
						{
							var obj={
								"org_id":Node[0].id
							};
							//alert(treeNode.id)
							var path_first="ecologycompany/info";
							$.post(path_first,obj,function(data)
							{
							//alert(data)
								$('.part01_1').find(".cont-wrapper").remove();
								$('.part01_1 .bread').after(data);
								var org_ID=Node[0].id;//获得当前组织id
								var parent_orgid=Node[0].pId;
								//alert(org_ID);
								//alert(parent_orgid)
								//var nodes = zTree.getSelectedNodes();
								if(Node[0]!=null)
								{
									var value=[];
									value.push(Node[0].name);
									/*while(nodes[0].pId!=null)
									{
										nodes = zTree.getNodesByParam("id", nodes[0].pId, null);
										if(nodes[0]!=null)
										{
										  value.push(nodes[0].name);
										}
									}*/
									//alert(32243)
									var  staff_depart="";
									//staff_depart=' <div class="bread part0">';
									for(var i=value.length-1;i>0;i--)
									{
										staff_depart=staff_depart+'<span>'+value[i]+'</span>&nbsp;&gt;&nbsp';
									}
									staff_depart=staff_depart+'<span>'+value[i]+'</span>';
									//staff_depart=staff_depart+"</div>";
									// $('.link_limitSet').after(staff_depart);
									//alert(2324);
									$('#part01 .part01_1 .bread').find('span').text('');
									$('#part01 .part01_1 .bread').find('span').append(staff_depart);
								}
							})
						}
						hideDialog();
					}
					else
					{
						alert(json.prompt_text);
					}
				})
				//alert(treeNode[0].id)
			});
      });
	  //添加管理员
		$('.addGroupstaff').click(function()
		{
			showDialog("ecologycompany/ecologyManagerPage" + '/2');
			$('#dialog #addManager').die('click');
			$('#dialog #addManager').live('click',function()
			{
				var a=$('.treeRight a');
				if(a.length==0)
				{
					alert("请指定要调入的员工")
					return false;
				}
				var users='';
				var user_id=[];
				var i=0;
				$('.treeRight a').each(function()
				{
					users=users+'{"userid":'+$(this).attr("id")+',"user_name":"'+$(this).text()+'","orgid":'+$(this).attr("class")+',"org_name":"'+$(this).attr("name")+'","org_pid":"'+$(this).attr("orgpid")+'","org_code":"'+$(this).attr("orgcode")+'"},';//加user_name           
					user_id[i]=$(this).attr("id");
					//alert(user_id[i])
					i++;
				});
				//alert(user_id.length)
				users=DelLastComma(users);
				var zTree = $.fn.zTree.getZTreeObj("adminTree");
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
					//"org_pid":Node[0].pId,//新建组织的父i
					//"p_id":Node[0].id,
					//"org_id":Node[0].id,//新建组织id
					//"org_code":org_code,//新组的id串
					//"org_name":Node[0].name,//新建组织名称
					"user_id":user_id
				};
				//alert(Node[0].name)
				//alert(users)
				var path_fold_staff = "ecology/addEcologyManager";
				$.post(path_fold_staff,obj,function(data)
				{
					//alert(data)
					var json=$.parseJSON(data);
					if(json.code==0)
					{	
						//addStAdmin();
						var zTree = $.fn.zTree.getZTreeObj("adminTree"),
	//isParent = e.data.isParent,
						nodes = zTree.getSelectedNodes(),
						treeNode = nodes[0];
						var newCount=json.data.id;
						var childNodes=[];
						$("#dialog .treeRight a").each(function() {
							var admin = $(this).text();
							var Nodes ={id:newCount,pId:treeNode.id,name:""+admin+"",open:false,nocheck:true};
							childNodes.push(Nodes);
							newCount++;
						})
						treeNode.childNodeCount=true;
						//alert(treeNode.childNodeCount)
						if (treeNode) {
								treeNode = zTree.addNodes(treeNode,childNodes);
							} else {
								treeNode = zTree.addNodes(null,childNodes);
							}
						zTree.updateNode(treeNode);
						//alert(treeNode.childNodeCount)
						//load_staff(objN,path_user,path_mag);
						hideDialog();
						
					}
					else
					{
						alert(json.prmopt_text)
						return false;
					}
				})
					
			})
		});
		//加载右边的企业生态列表
		$("#adminTree li a").die("click");
        $("#adminTree li a").live("click",function(e)
        {

            $(this).parents("div").addClass("first");
			if(!$(e.target).hasClass("button"))
			{
				if($(this).attr("target")!="1")
				{
					//alert(1)
					if($("#tree_admin").hasClass('first'))
					{
						// $('.part0').remove();
						$('.delStAdmin').removeClass('disabled');
						var zTree = $.fn.zTree.getZTreeObj("adminTree");
						var nodes = zTree.getSelectedNodes();
						//if(nodes[0]!=null)
						//{
							treeNode = nodes[0];//alert(2)
							//alert(treeNode.pId)
							if(treeNode.pId==null)
							{
								//alert(11)
								$(".delStAdmin").addClass("disabled");
							}
							//alert(treeNode.pId)
							//if(treeNode.pId!=null)
							//{
								var obj={
									"id":treeNode.id
								};
								//alert(treeNode.id)
								var path_first="ecology/ecologyList";
								$.post(path_first,obj,function(data)
								{
								//alert(data)
									// $('.part01_1').find(".cont-wrapper").remove();
									//$('#part01 .part01_1').show();
									//$('.part01_1 .cont-wrapper').find("infoCont").remove();
									//$('.part01_1 .bread').show();
									$('#part02 #part1').remove();
									//$('.part01_1 .bread').after(data);
									$('#part02').find(".table").remove();
									$('#part02').find(".page").remove();
									$('#part02 .tabToolBar').after(data);
								})
							//}
							//else 
							//{
/*
								var obj={
									"org_id":treeNode.id
								};
								//alert(treeNode.id)
								var path_other ='<?php echo site_url('ecologycompany/info2'); ?>';
								$.post(path_other,obj,function(data)
								{
								//alert(data)
									$('.part02_1').find(".cont-wrapper").remove();
									$('.part02_1 .bread').after(data);
								})
								
							}
							*/
							//alert(treeNode.isDisabled)
							/*if($(this).hasClass("curSelectedNode") && treeNode.isDisabled==false )
							{
								var org_ID=treeNode.id;//获得当前组织id
								var parent_orgid=treeNode.pId;
								//alert(org_ID);
								//alert(parent_orgid)
							  
								load_staff(obj,path_user,path_mag);
							}*/
						//}
						//根据组id，异步获得员工信息
						//$('#tree').removeClass();
					}
		
				}
				else
				{
					$(this).attr("target","2")
				}
			}
           
        });
		//删除生态企业
		$('.tabToolBox .btnMoveManage').click(function()
		{
			showDialog("ecology/showDelEcologyPage");
			$('#dialog .btn_confirm').die('click');
			$('#dialog .btn_confirm').live('click',function()
			{
				var users='';
				$('#part02 .table tbody label.checkbox').each(function()
				{
					if($(this).hasClass("checked"))
					{
						users=users+''+$(this).attr("name")+',';
					}
				})
				var lastindex=users.lastIndexOf(',');
				if(lastindex>-1)
				{
					users=users.substring(0,lastindex)+users.substring(lastindex + 1, users.length);
				}
				var obj={
				"ecology_id":users
				};
				//alert(users)
				var path = "ecologycompany/delete_ecology";
				$.post(path,obj,function(data)
				{
					//alert(data)
				  var json=$.parseJSON(data);
				  if(json.code==0)
				  {
				  		var zTree = $.fn.zTree.getZTreeObj("adminTree");
						var nodes = zTree.getSelectedNodes();
						//if(nodes[0]!=null)
						//{
							treeNode = nodes[0];//alert(2)
							//alert(treeNode.pId)
							//if(treeNode.pId!=null)
							//{
								var obj={
									"org_id":treeNode.id
								};
								//alert(treeNode.id)
								var path_first="ecologycompany/ecologycompanylist";
								$.post(path_first,obj,function(data)
								{
									//alert(data)
									// $('.part01_1').find(".cont-wrapper").remove();
									//$('#part01 .part01_1').show();
									//$('.part01_1 .cont-wrapper').find("infoCont").remove();
									//$('.part01_1 .bread').show();
									$('#part02 #part1').remove();
									//$('.part01_1 .bread').after(data);
									$('#part02').find(".table").remove();
									$('#part02').find(".page").remove();
									$('#part02 .tabToolBar').after(data);
								})
								hideDialog();
								
				  }
				 else
					{
						alert(json.prmopt_text)
						return false;
					}
				})
			})
		})
});