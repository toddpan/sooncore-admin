
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">你确定要从组织结构中删除选中的员工吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm" id="deleteStaff"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
$('.btnDeleUser').removeClass("false");
var _checked = $('#part01 .table:first tbody .checked');
//var _name = _checked.parent().next().find('.userName')
$('.D_confirm .dialogBody .text01').html('你确定要从组织结构中删除员工 <?php echo $display_name;?> 吗？');
 function assure_delete()
 {
    var user_id='';
	  //alert(user_id.length)
      var zTree = $.fn.zTree.getZTreeObj("ztree");
		nodes = zTree.getSelectedNodes();
	    treeNode = nodes[0];
		if(treeNode!=null)
		{
		   var orgid=treeNode.id;
		    var parent_orgid=treeNode.pId;
		}
     $('table.sel tbody label').each(function()
	 {
	   //alert(3);
	   if($(this).hasClass("checked"))
	   {
	     var value=$(this).find('input').val();
	      user_id=user_id +value+',';
	   }
	 }) 
	  var lastIndex =user_id.lastIndexOf(',');
       if (lastIndex > -1) {
         user_id = user_id.substring(0,lastIndex) + user_id.substring(lastIndex + 1,user_id.length);
           }
     var id_2=treeNode.pId;
     var org_code='-'+treeNode.id;
     var node;
     while(zTree.getNodesByParam('id',id_2,null)[0]!=null)
     {
         node=zTree.getNodesByParam('id',id_2,null)[0];
         id_2=node.pId;
         org_code ='-'+node.id+org_code;

     }
	 //alert(user_id)
	 var staff={
	 	//"parent_orgid":parent_orgid,
		//"orgid": orgid,
       // "org_code":org_code,
		"user_id":user_id
		 };
		 //alert(parent_orgid)
		 var All_delete=0;
		 if(_checked.length == $('#part01 .table:first tbody tr').length){
			//$("#novalueTable").show().prev("table").hide();
			//$('#part01 .table:first tbody tr').show();
			//alert("ddddd: "+ dG)
			All_delete=1;
			/*if(dG==1){
				//alert("eeeee: "+ dG)
				
			}*/
		}
			//_checked.parent().parent().hide();
				var path_delete_staff ='staff/save_delete_staff';
				$.post(path_delete_staff,staff,function(data)
  					{
	 					 //salert(data);
						 var json=$.parseJSON(data);
							 if(json.code==0)
								 {
									var obj={
									"parent_orgid":parent_orgid,
		  							"org_id":orgid
		 						 		}
		 						 		load_staff(obj,path_user,path_mag);
		   								hideDialog();
										if(All_delete==1 && dG==1)
										{
										   showDialog('organize/sure_del_org2');
										}
								 }else
									{
										alert(json.prompt_text)	
									}
  					})
		
		 //返回code是否成功，如果成功：重新加载当前组织帐号列表。
	  
 }
</script>
