<div class="infor_page">
<table class="table" id="self_staff">
    <thead>
        <tr>
            <th width="6%">{if !is_string($data)}<span class="checkbox"><input type="checkbox" /></span>{/if}</th>
            <th width="10%" style="text-align: left; text-indent: 24px">姓名</th>
            <th>角色</th>
            <th>蜜蜂帐号</th>
            <th>手机</th>
            <th>上次登录时间</th>
        </tr>
    </thead>
    <tbody>
    	{if is_string($data)}
    		<tr>
    			<td></td>
    			<td>{$data}</td>
    			<td></td>
    			<td></td>
    			<td></td>
    			<td></td>
    		</tr>
    	{else}
        {foreach $data as $v}
            <tr>
                <td><span class="checkbox"><input type="checkbox" /></span></td>
                <td class="tl"><a target="{$v['id']}" class="userName manage ellipsis" onclick="adminstaff_infor(this,{$v['user_id']},{$v['id']})">{$v['display_name']}</a></td>
                <td>{$v['role']}</td>
                <td class="tl"><span class="ellipsis">{$v['login_name']}</span></td>
                <td>{$v['mobile_number']}</td>
                <td>{$v['last_login_time']}</td>
            </tr>
        {/foreach}
        {/if}
    </tbody>
</table>
<div class="page" {if $page_text==''}style="display:none"{/if}>
    {$page_text}
</div>
</div>

<script type="text/javascript">

function adminstaff_infor(t,user_id,id)
    {
		if($(t).hasClass("false"))
		{
			return;
		}
		$(t).addClass("false");
		var _this=$(t);
		$('.contTitle02').hide();
		$('.infor_page').hide();
        var path_staff_information='manager/managerInfoPage';
        var obj={
			"user_id":user_id,
			"id":id
        }
        $.post(path_staff_information,obj,function(data)
        {
			$('#ri_admin .contHead').after(data);
			_this.removeClass("false");
		});
    }
</script>
