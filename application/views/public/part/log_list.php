	<table class="table">
	  <thead>
		<tr>
		  <th>活动名称</th>
		  <th>活动说明</th>
		  <th>操作人员</th>
		  <th>IP地址</th>
		  <th>时间</th>
		</tr>
	  </thead>
	  <tbody>
		<?php foreach($log_arr as $k => $v):
			 $log_type_name = arr_unbound_value($v,'log_type_name',2,'');
			 $log_content = arr_unbound_value($v,'log_content',2,'');
			 $display_name = arr_unbound_value($v,'display_name',2,'');
			 $ip = arr_unbound_value($v,'ip',2,'');
			 $addtime = arr_unbound_value($v,'addtime',2,'');
			
			?>
			<tr>
			  <td><?php echo $log_type_name;?></td>
			  <td><?php echo $log_content;?></td>
			  <td><?php echo $display_name;?></td>
			  <td><?php echo $ip;?></td>
			  <td><?php echo $addtime;?></td>
			</tr>
		<?php endforeach;?>

	  </tbody>
	</table>