<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01" id="staff_name"></span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes" onclick="assure_setManager()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>

<script type="text/javascript">
var _checked = $('#part01 .table:first tbody .checked');
var _name = _checked.parent().next().find('.userName').text();
setTimeout(function(){
$('#dialog .dialogBody .text01').html('您确定要将 '+_name+' 指定为该部门的管理者吗？');},10)
function assure_setManager(){
	  var user_id;
	  //alert(user_id.length)
      var zTree = $.fn.zTree.getZTreeObj("ztree");
		nodes = zTree.getSelectedNodes();
	    treeNode = nodes[0];
		if(treeNode!=null)
		{
		   var orgid=treeNode.id;
		   var parent_orgid=treeNode.pId;
		    //alert(orgid)
		}
     $('#part01 table tbody label').each(function()
	 {
	   //alert(3);
	   if($(this).hasClass("checked"))
	   {
	      user_id=$(this).find("input").val();
	   }
	   
	 }) 
	 var staff={
		   "orgid": orgid,
		   "parent_orgid":parent_orgid,
		   "user_id":user_id
		 }
		 //code0成功,重新加载用户列表
		 var path_setmanager = 'organize/save_set_manager';
		 $.post(path_setmanager,staff,function(data)
		 {
		  // alert(data);
		   var json=$.parseJSON(data);
		   if(json.code==0)
		   {
		   		 /*$('#part01 table tbody label').each(function()
				 {
				   //alert(3);
				   if($(this).hasClass("checked"))
				   {
					 // user_id=$(this).find("input").val();
					 $(this).parent().next().find("a").addClass("manage");
					 $(this).trigger("click");
					 return false;
				   }
				   
				 })*/
				var obj={
			  "parent_orgid":parent_orgid,
			  "org_id":orgid
			  }
			  load_staff(obj,path_user,path_mag);
			  hideDialog();
		   }else
				{
					alert(json.prompt_text)	
				}
		 }) 
}
$(function(){
	//setTimeout(function(){
		var _checked = $('#part01 .table tbody .checked');
			var _name = _checked.parent().next().find('.userName');
			
		
		//$('.D_confirm .btn_confirm') = null;
	//},100)	
})
</script>