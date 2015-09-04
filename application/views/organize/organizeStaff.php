<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>蜜蜂管理中心</title>
	</head>
	<body>
		<?php 
		define('INSTALLDIR', realpath(dirname(__FILE__)));
		include_once INSTALLDIR.'/organizepart/orgpart1.php';	
		include_once INSTALLDIR.'/organizepart/orgpart2.php';	
		include_once INSTALLDIR.'/organizepart/orgpart3.php';		
		?>
		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<!--<script type="text/javascript" src="public/js/part_js/org_tree.js"></script>-->
		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript" src="public/js/part_js/input_radio_tree.js"></script>
		<script type="text/javascript" src="public/js/part_js/orgpart.js"></script>
		<script type="text/javascript" src="public/js/part_js/costcenterpart.js"></script>
		<script type="text/javascript" src="public/js/part_js/orgpartinit.js"></script>
		<script type="text/javascript">
			var page;//判断调入员工是组织结构还是成本中心
			var path = "organize/get_next_OrgList"; //要加载的每个组织结构
			var judge; //判断禁用添加删除
			var path_get_staff = "organize/get_users_json_by_orgid"; //加载选定组织下的员工信息
			var zNodes;
			var costNode;
			var cost_path ='costcenter/nextGroups';//加载第一级成本中心
			var path_user = "organize/get_users_list"; //加载的员工列表
			var path_cost_user = "costcenter/members"; //加载的成本中心员工列表
			var path_mag ='organize/get_manager_user_id'; //设置管理者
			var path_new_org = 'organize/saveNewOrg';//
			var cost_get_staff ='organize/get_next_orguser_list'; //组织结构和成本中心部分的调入员工
			var drag_path ='organize/move_org'; //拖拽结束后当前的组织ID处理
			var cost_next='costcenter/nextGroups';//点击小三角加载下一级的成本中心
			var add_cost='costcenter/addGroup';//点击增加成本中心
			var cost_org_staff='costcenter/unGroupedMembers';//在成本中心下，选中全部组织中的某一个部门加载该部门下成本中心员工
			var change_cost="costcenter/modifyGroup";//修改成本中心的名称
			var add_cost_dialog="costcenter/addCostCenterStaff";//添加了成本中心后弹窗
			var dG = 0; //0为删除员工，1为需要删除组织时，删除员工
			var count_name;
			var clear_null = 1; //是否清除了空的节;点
			$(function() {
				//切换组织结构和成本中心
				$('.conTabsHead  li').click(function() {
					var ind = $(this).index();
					$(this).addClass('selected').siblings().removeClass('selected');
					$('.conTabsCont  dd').eq(ind).show().siblings().hide();
					$('.contRight > div').eq(ind).show().siblings().hide();
				});
				//员工搜索js事件
				$('.js-search').click(function() {
					var keyword = $(this).parent().find('input[name=keyword]').val();
					var reg=/\s/g;
					keyword=keyword.replace(reg,'');					
					if(keyword=="")
					{
// 						alert("请输入需要查询的信息");
						$(this).parent().find("input").val(keyword);
 						$(this).parent().find(".label").css("display","block");
						return;
					}
					loadCont('search/searchOrgAccountPage' +'?keyword=' + keyword);
				});
			});
		</script>
	</body>
</html>