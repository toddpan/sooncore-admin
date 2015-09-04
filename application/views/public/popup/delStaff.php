<?php 
 $task_title = '删除员工';
 $current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');
 $current_position = arr_unbound_value($task_arr,'current_position',2,'');
 $current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');
 $user_id = arr_unbound_value($task_arr,'user_id',2,'');
 $display_name = arr_unbound_value($task_arr,'display_name',2,'');
 $avatar = arr_unbound_value($task_arr,'avatar',2,'');
 $task_des = '李想申请将离职员工' .  $display_name . '删除 ';  
 $show_url = site_url('information/delStaff');       
 $on_click = 'showDeletDialog(this,' . $task_id . ')';  
	?>
<dl class="dialogBox D_addAccounts">
	<dt class="dialogHeader">
		<span class="title">删除员工</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
    	<div class="user-avatar">
   	    <img src="public/images/avatar.jpg" width="99" height="98" alt="" /> </div>
        <div class="dg-right">
		<table class="infoTable">
			<tr>
				<th class="tr">姓名：</th>
				<td>
					<?php echo $display_name ;?>
				</td>
            </tr>
            <tr>
				<th class="tr">调至部门：</th>
				<td>
					<?php echo $current_dept_name ;?>
				</td>
			</tr>
			<tr>
				<th class="tr">职位：</th>
				<td>
					<?php echo $current_position ;?>
				</td>
			
			</tr>
			
		</table>
        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes" id="del_staff"><span class="text">删除</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>

