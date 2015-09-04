		<ul class="msg-li">
			<?php 
			foreach($task_arr as $k => $v):
				 $task_id = arr_unbound_value($v,'id',2,'');
				 $task_type = arr_unbound_value($v,'type',2,'');
				 $task_status = arr_unbound_value($v,'status',2,'');
				 $task_created = arr_unbound_value($v,'created',2,'');
				 $task_info = arr_unbound_value($v,'task_info',2,'');
				 $task_keyword = arr_unbound_value($v,'keyword',2,'');
				 $task_arr = json_decode($task_info,true);
				 $task_title = '';
				 $task_des = '';
				 $show_url = '';
				 $on_click = '';
				 switch ($task_type){
					 case 1://1-add 
					     $task_title = '新增员工';
						 $operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作人员
						 $operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作人
						 $display_name = arr_unbound_value($task_arr,'display_name',2,'');//新加人姓名
						 $mobile = arr_unbound_value($task_arr,'mobile',2,'');//手机号
						 $current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						 $current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');//当前部门名称
						 $position = arr_unbound_value($task_arr,'position',2,'');//职位
						 $account_enable = arr_unbound_value($task_arr,'account_enable',2,'');//是否是管理员
						 $task_des = $operator_name . '申请新增员工' . $display_name . ' ';						 
				         $task_keyword = empty_to_value($task_keyword,task_des);
						 $show_url = site_url('Staff/add_staff_taskpage');
						 $on_click = 'showAddDialog(this,' . $task_id . ')';
						 break;
					 case 2://2-transfer  
					     $task_title = '调岗员工';
						 $operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作者id
						 $operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作者姓名
						 $move_user_name = arr_unbound_value($task_arr,'move_user_name',2,'');//移除员工姓名
						 $move_user_id = arr_unbound_value($task_arr,'move_user_id',2,'');//移除员工id
						 $current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						 $current_dept_name = arr_unbound_value($task_arr,'current_dept_name',2,'');//当前部门名称
						 $target_dept_id = arr_unbound_value($task_arr,'target_dept_id',2,'');//目标部门id
						 $target_dept_name = arr_unbound_value($task_arr,'target_dept_name',2,'');//目标部门名称
						 $position = arr_unbound_value($task_arr,'position',2,'');//职位
						 $task_des = $operator_name . '申请' .  $move_user_name . '调到' . $target_dept_name. ' ';
				         $task_keyword = empty_to_value($task_keyword,task_des);
						 $show_url = site_url('information/staffTransfer');  
						 $on_click = 'showDgDialog(this,' . $task_id . ')';           
						break;
					 case 3://3-delete 
					     $task_title = '删除员工';
						 $operator_id = arr_unbound_value($task_arr,'operator_id',2,'');//操作人id
						 $operator_name = arr_unbound_value($task_arr,'operator_name',2,'');//操作人姓名
						 $delete_user_id = arr_unbound_value($task_arr,'delete_user_id',2,'');//删除的用户id
						 $current_dept_id = arr_unbound_value($task_arr,'current_dept_id',2,'');//当前部门id
						 $delete_user_name = arr_unbound_value($task_arr,'delete_user_name',2,'');//删除的用户姓名
						 $avatar = arr_unbound_value($task_arr,'avatar',2,'');
						 $task_des = $operator_name . '申请将离职员工' .  $delete_user_name . '删除 ';  						 
				         $task_keyword = empty_to_value($task_keyword,task_des);
						 $show_url = site_url('information/delStaff');       
						 $on_click = 'showDeletDialog(this,' . $task_id . ')';       
						break;
					 default:
						break;
				 } 
				 
				?>
				  <li  <?php  if( $task_status == 1 ): ?>  class="new" <?php  endif ;?>>
				    <span class = 'task_id' style="display: none" ><?php echo  $task_id?></span>
					<a  onclick="if($(this).parent().hasClass('new')) {<?php echo $on_click; ?>}<!--showDialog('<?php echo $show_url ;?>')-->"><?php echo  $task_title?></a> <br />
					<?php echo  $task_keyword?> 
					<span class="time"><?php echo  dgmdate($task_created, 'd') ;?></span>
					<div class="li-ml">
					     <?php
						 switch ($task_status): 
						       case 1:
									 ?>  
									<a class="btn yes fl"  onclick="<?php echo $on_click; ?>">
									<span class="text">立即处理</span>
									<b class="bgR"></b>
									</a>
									<a  class="normal-link">关闭任务</a>
									<?php
									break;
						       case 20:
									 ?>
									 已处理
									<?php
									break;
						       case 40:
									 ?>
									 已关闭
									<?php
									break;
						endswitch;
						?>	
	
					</div>
				  </li>
		   <?php endforeach;?>
		</ul>
		<div class="page" >
		  <?php echo $page_text;?>
		</div>
		<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
		<script type="text/javascript" src="public/js/self_tree.js"></script>
		<script type="text/javascript">
		if($('.infor_page ul.msg-li li').length==0)
			{
				$('.infor_page div.page').remove();
			}
		$("a.normal-link").click(function(){
			
		    var close_task_id = $(this).parents("li").find("span.task_id").text();
			//alert(close_task_id);
			var path = 'information/close_task';
			var obj={
				"task_id":close_task_id
			}
			var li_obj = $(this).parents("li");
			var li_ml_obj = $(this).parent(".li-ml");
			$.post(path,obj,function(data){
			   // alert(data);
				var data_json = eval('(' +data + ')');
				if(data_json.code == 0){
					li_obj.removeClass("new");
					li_ml_obj.html("已关闭");
					var val=$('#infor_list li:eq(0) span b').text();
					//$('#infor_list li:eq(0) span b').text(val-1);
					if(val==1)
					{
						$('.msg-list .msg-bar-left').hide();
					}
				}
				else
				{
					alert(data_json.prompt_text)	
				}
			});
		})
		</script>