<?php 

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
	?>
<dl class="dialogBox D_addAccounts">
	<dt class="dialogHeader">
		<span class="title">调岗员工</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
    	<div class="user-avatar">
   	    <img src="<?php echo $avatar;?>" width="99" height="98" alt="" /> </div>
        <div class="dg-right">
		<table class="infoTable">
			<tr>
				<th class="tr_r">姓名：</th>
				<td style="vertical-align:top">
					<label class="label" style="margin-top:10px"><?php echo $move_user_name;?></label>
				</td>
            </tr>
            <tr>
				<th class="tr_r">调至部门：</th>
				<td>
						<label class="label"><?php echo $target_dept_name;?></label>
				</td>
			</tr>
			<tr>
				<th class="tr">职位：</th>
				<td>
					<div class="inputBox">
						<label class="label">输入文本...</label>
						<input class="input" value="<?php echo $position;?>" style="width: 180px" />
					</div>
				</td>
			
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td>
					<label class="checkbox checked"><input type="checkbox" checked="checked" />设为部门管理者</label>
				</td>
			</tr>
			
		</table>
        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes" id="transfer_staff"><span class="text" style="cursor: pointer">完成调岗</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text" style="cursor: pointer">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
function move_staff()
{
  var staff=[];
  var i=0;
  var count=0;
  $('.inputBox').each(function()
   {
     staff[i]=$(this).find("input").val();
     i=i+1;
   })
  for(var i=0;i<staff.length;i++)
   {
     if(staff[i]=='')
     {
	   count++;
      }
    }
	if(count>0)
	{
	  return false;
	}
	else
	{
	  hideDialog();
	}
	
}
$(function()
{
       $(".inputBox").click(function(){
				$(this).find("input").focus();	
			})
			$(".inputBox input").focus(function(){
				$(this).parent().addClass("focus");
				$(this).parent().find("label").hide();
			}).blur(function(){
				$(this).parent().removeClass("focus");
				if($(this).val()==""){
					$(this).parent().find("label").show();	
				}
			})
		$('#dialog label.checkbox').click(function()
		{
			if($(this).hasClass("checked"))
			{
				$(this).removeClass("checked");
			}
			else
			{
				$(this).addClass("checked");
			}
		})
})
	
</script>>
