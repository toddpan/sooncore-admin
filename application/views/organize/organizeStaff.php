<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>通讯录</title>
	</head>
	<body>
		<?php 
		define('INSTALLDIR', realpath(dirname(__FILE__)));
		include_once INSTALLDIR.'/organizepart/orgpart1.php';	//气泡
		include_once INSTALLDIR.'/organizepart/orgpart2.php';	//左组织结构树
		include_once INSTALLDIR.'/organizepart/orgpart3.php';   //右侧列表
		?>
		<script type="text/javascript">
			var page;//判断调入员工是组织结构还是成本中心
			var path = "organize/get_next_OrgList"; //要加载的每个组织结构
			var judge; //判断禁用添加删除
			var path_get_staff = "organize/get_users_json_by_orgid"; //加载选定组织下的员工信息
			var path_user = "organize/get_users_list"; //加载的员工列表
			var path_mag ='organize/get_manager_user_id'; //设置管理者
			var path_new_org = 'organize/saveNewOrg';//
			var drag_path ='organize/move_org'; //拖拽结束后当前的组织ID处理
			var cost_get_staff ='organize/get_next_orguser_list'; //组织结构和成本中心部分的调入员工
			var dG = 0; //0为删除员工，1为需要删除组织时，删除员工
			var clear_null = 1; //是否清除了空的节;点
		</script>
                <script type="text/javascript">
                    $(function() {
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
<!--		<script type="text/javascript" src="public/js/self_tree.js"></script>-->
		<script type="text/javascript" src="public/js/common.js"></script>
		<script type="text/javascript" src="public/js/part_js/orgpart.js"></script>
		<script type="text/javascript" src="public/js/part_js/orgpartinit.js"></script>
                <div id="addMoreBox" style="position: fixed; left: 50%; top: 50%; width: 400px; height: 280px; margin-left: -200px; margin-top: -140px; z-index: 10; background: #fff; border: 1px solid #eee; box-shadow: 5px 5px 12px rgba(0,0,0,0.1);">
                    <ul class="addMoreUl">
                        <li>
                            <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
                            <a class="btnBlue yes btnAddUser">
                                    <span class="text" onclick="addNewMember_one()">添加员工</span>
                                    <span id="add_up" class="more">&nbsp;</span>
                                    <b class="bgR"></b>
                            </a>
                            <?php }?>
                        </li>
                        <li>
                            <a class="addGroup" id="addZuzhi" onclick="addZuzhi(event);return false;"  style="cursor: pointer" title="添加部门">添加部门</a>
                        </li>
                        <li>
                            <a href="">团队邀请</a>
                        </li>
                        <li>
                            <a href="">批量处理</a>
                        </li>
                    </ul>
                </div>
        <style>
            .addMoreUl li{}
        </style>
	</body>
</html>