<table class="table">
	<thead>
		<tr>
			<th>姓名</th>
			<th>帐号</th>
			<th>手机</th>
			<th width="">所在部门</th>
			<th>上次登录时间</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		//print_r($exeptusers);
		foreach($exeptusers as $value){
			//print_r($value);
			$user_id = $value['user_id'];
			//echo $user_id;
			$user_arr = $this->StaffLib->get_user_by_id($user_id);
			//print_r($user_arr);
			//break;
	?>
		<tr>
			<td><?php echo $user_arr['lastName']; ?></td>
			<td><?php echo $user_arr['loginName']; ?></td>
			<td><?php $user_arr['mobileNumber'] = ltrim($user_arr['mobileNumber'], "+86");echo $user_arr['mobileNumber']; ?></td>
			<td><?php echo $user_arr['organizationName']; ?></td>
			<td><?php echo date("Y-m-d H:i:s", $user_arr['lastlogintime']); ?></td>
			<td><a  class="cancel_hm" name="<?php echo $user_id;?>">取消豁免</a></td>
		</tr>
	<?php }?>
	</tbody>
</table>
<script type="text/javascript">
var no_warn='<?php echo $is_check; ?>';//标记是否以后提醒豁免弹窗1是不弹窗，0是弹窗;
//alert(no_warn)
$(function()
{
	$('#hm_staff table tbody  .cancel_hm').click(function()
	{
		//alert(2222)
		var _this=$(this);
		if(no_warn==1)
		{
			var path="exemptuser/exemptUserPage";
			$.post(path,[],function(data)
			{
				//alert(data)
				$('#hmMsg').hide();
				$('#hmMsg').next().css("text-align","right");
				$('#hm_staff').find("table").remove();
				$('#hm_staff').append(data);
				//hideDialog();
			});
		}else
		{
			showDialog('exemptuser/remindDelExemptUser'+'/'+_this.attr("name"));
			$('#dialog .btn_confirm').die('click');
			$('#dialog .btn_confirm').live('click',function()
			{
				var not_dialog=0;//1以后不提醒豁免员工
				if($('#dialog .dialogBottomLeft').find("label.checkbox").hasClass("checked"))
				{
					not_dialog=1;
				}
				//alert(not_dialog)
				var obj={
				"is_check":not_dialog,
				"user_id":_this.attr("name")
				};
				//alert(_this.attr("name"))
				var path="exemptuser/delExemptUser";
				//alert(22222)
				$.post(path,obj,function(data)
				{
					//alert(data)
					var json=$.parseJSON(data);
					if(json.code==0)
					{
						var path="exemptuser/exemptUserPage";
						$.post(path,[],function(data)
						{
							//alert(data)
							$('#hmMsg').hide();
							$('#hmMsg').next().css("text-align","right");
							$('#hm_staff').find("table").remove();
							$('#hm_staff').append(data);
							hideDialog();
						})
					
					}
					else
				{
					alert(json.prompt_text)	
				}
				})

			})
		}
	})
})
</script>
