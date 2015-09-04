<table class="table">
	<thead>
		<tr>
			<th>序号</th>
			<th>敏感词</th>
			<th>添加时间</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ($sensitiveArr as $v1){
	?>
		<tr>
			<td><?php echo $v1['id']; ?></td>
			<td><?php echo $v1['Word']; ?></td>
			<td><?php echo $v1['time']; ?></td>
			<td><?php if($v1['type'] == 2){ ?><a   class="dele_sesitive">删除</a><?php } ?></td>
        </tr>
    <?php
		}
	?>
	</tbody>
</table>
<script type="text/javascript">
$(function(){
	$('.con-wrapper .dele_sesitive').click(function()
		{
			var id=$(this).parent().parent().find("td:eq(0)").text();
			//alert(id)
			var _this=$(this).parent().parent();
			showDialog('sensitiveword/showDelSensitiveWordPage' + '/' + id);
			$('#dialog #del_sensitive').die("click");
			$('#dialog #del_sensitive').live("click",function()
			{
				//alert(121)
				var path="sensitiveword/delSensitiveWord"+ '/' + id;
				var obj={
					"SensitiveId":id
				        };
				$.post(path,obj,function(data)
				{
					if(data.code==0)
					{
						_this.remove();
						hideDialog();
					}
					else
				{
					alert(data.prompt_text)	
				}
					
					//alert(data);
					
				},'json')
			})
		})
	});
</script>