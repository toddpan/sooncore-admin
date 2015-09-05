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
        onClick: ldap_detail_select
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
	var data =[
	{ id:1, pId:0, name:"海尔", open:true,nocheck:true},
	{ id:2, pId:1, name:"海尔产品开发部", open:true,nocheck:true},
	{ id:21, pId:2, name:"研发部"},
		{ id:211, pId:21, name:"大洋"},
		{ id:212, pId:21, name:"新象"},
		{ id:213, pId:21, name:"刘杰"},
		{ id:214, pId:21, name:"占奎"},
	{ id:22, pId:2, name:"市场部"},
	{ id:23, pId:2, name:"营销部"},
	{ id:3, pId:1, name:"海尔生活家电事业部", open:true,nocheck:true},
	{ id:31, pId:3, name:"市场部"},
	{ id:32, pId:3, name:"营销部"},
	{ id:4, pId:1, name:"海尔电脑事业部", open:true,nocheck:true},
	{ id:41, pId:4, name:"市场部"},
	{ id:42, pId:4, name:"营销部"}
];
//	var value=$('#ldap_tree_detail').attr("value_tree");
//	for(var i=0;i<value.length;i++)
//		{
//		alert(value[i]['id']);
//		}
	var path = 'ldap/getOrgInfo';
	var id=$("#ldap_tree_detail").attr("value_id");
	var obj={
		"ldap_id":id
	}
	$.post(path,obj,function(data)
	 {
		
//		$.parseJSON(data.prompt_text)
//		alert(data.prompt_text)
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