
<table class="table table_org">
    <thead>
    <tr>
        <th width="6%"><label class="checkbox"><input type="checkbox" /></label></th>
        <th style="text-align: left; text-indent: 24px">姓名</th>
        <th>帐号</th>
        <th>手机</th>
        <th>上次登录</th>
        <th>帐号状态</th>
    </tr>
    </thead>
    <tbody>
    <?php
       //var_dump($user_arr);
    foreach($user_arr as $k => $v):
	$productStatus = arr_unbound_value($v,'productStatus',2,'0');
	?>
        <tr>
            <td><label class="checkbox"><input type="checkbox" value="<?php echo $v['id'];?>" /></label></td>
            <td class="tl"><a style="cursor: pointer" class="userName <?php if($v['is_org_manager'] == 1): ?> manage <?php endif;?>  ellipsis"  onclick="staff_information1(this,<?php echo $v['id'];?>)"><?php echo $v['displayName'];?></a></td>
            <td class="tl"><span class="userCount ellipsis"><?php echo $v['loginName'];?></span></td>
            <td class="telephone"><?php echo $v['mobileNumber'];?></td>
            <td class="logintime">
                <?php
                if(!bn_is_empty($v['lastlogintime'])){
                    echo dgmdate($v['lastlogintime'], 'dt');
                }else{
                	echo '未登录';
                }
                ?>
            </td>
            <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
            <td><a  class="countType <?php if($productStatus == 82): ?>  btnOn <?php else: ?> btnOff <?php endif;?>"><em class="btnFixed"></em></a></td>
            <?php }?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
