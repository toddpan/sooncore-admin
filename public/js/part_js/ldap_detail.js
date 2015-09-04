// JavaScript Document
var ldap_detailsetting = {
	view: {
		selectedMulti: false,
		showLine: false,
		showIcon: false,
		dblClickExpand: false,
		txtSelectedEnable: false,
		addDiyDom: addDiyDom2
	},
	edit: {
		enable: false,
		showRemoveBtn: false,
		showRenameBtn: false,
		editNameSelectAll: false
	},
	data: {
		keep: {
			parent:false,
			leaf:false
		},
		simpleData: {
			enable: true
		}
	},
	check: {
		enable: false
	},
	callback: {
//        onClick: ldap_detail_select
    }
};
function ldap_detail_select(e, treeId, treeNode)
{
	var zTree = $.fn.zTree.getZTreeObj(treeId);
	zTree.cancelSelectedNode(nodes[0]);
}
function call_back_list(){
	$("#contRigt_ldaplist").show();
	$('#ldap_list').show();
	$('#find_ldap_detail').hide();
	$('#ldap_detail_head').hide();
	$('#ldap_detail_page').remove();
}
function initldap_tree() {
	var path = 'ldap/getOrgInfo';
	var id=$("#ldap_tree_detail").attr("value_id");
	var obj={
		"ldap_id":id
	}
	$.post(path,obj,function(data)
	 {
		$.fn.zTree.init($("#ldap_tree_detail"),ldap_detailsetting,data.prompt_text); 
	 },'json');
	
	
}
		initldap_tree();
		function detail_ldapdel(t,id)
		{
			var _this=$(t);
			showDialog('ldap/showDeleteLdapPage2?ldap_id='+id+'');
			$("#dialog #sure_delet_detail").live("click",function()
			{
				$('#contRigt_ldaplist tbody tr').each(function()
				{
					if($(this).attr("ldap_id")==id)
					{
						var path="ldap/deleteLdap";
						var obj=
						{
						"ldap_ids":id
						};
						var _this=$(this)
						$.post(path,obj,function(data)
						{
							if(data.code==0)
							{
								_this.remove();
								hideDialog();
							}
							else
							{
								alert(data.prmopt_text)
								return false;
							}
						},"json");
						
					}
				})
				call_back_list();
				
			});
		}