<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">提醒</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<span class="text01">您确定要取消李想的部门管理者身份吗？</span>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes"  onclick="assure_moveManager()"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
var _checked = $('#part01 .table:first tbody .checked');
var _name = _checked.parent().next().find('.userName');
setTimeout(function(){
            $('.D_confirm .dialogBody .text01').html('您确定要取消 <font color="red">'+_name.text()+'</font> 的部门管理者身份吗？');
    },10);
function assure_moveManager(){
    var user_id;
      //alert(user_id.length)
    treeNode = getSelectNode("#ztree");
    if(treeNode.oid != null)
    {
       var orgid=treeNode.oid;
       var parent_orgid=treeNode.pid;
        //alert(orgid)
    }
    $('#part01 table tbody label').each(function()
	 {
	   //alert(3);
	   if($(this).hasClass("checked"))
	   {
	      user_id=$(this).find("input").val();
	   }
	 });
	 var staff = {
		   "orgid": orgid,
		    "parent_orgid":parent_orgid,
		   "user_id":user_id
		 };
        // alert(user_id);
         //code0成功,重新加载用户列表
         var path_setmanager = 'organize/save_unset_manager';
         $.post(path_setmanager,staff,function(data){
            // alert(data);
            var json=$.parseJSON(data);
            if(json.code==0)
            {
                hideDialog();
                $("#ztree a.curSelectedNode").click();//模拟点击当前选择的节点以代表load_staff

            }else{
                alert(json.prompt_text);
            }
        });
}
</script>