<script type="text/javascript">
<!--
//批量导入提示气泡
var login = <?php echo $this->p_has_login ?>;

if (login) {
    $('.poptip').hide();
} else {
    $('.poptip').show();
}
$('.poptip .btn_iKnow').click(function() {
    $('.poptip').animate({
       'opacity': 0
    }, 300, function() {
        $('.poptip').hide();
        $('.poptip1').show();
    });
    login = 1;
	
});
$('.poptip1 .btn_iKnow').click(function() {
    $('.poptip1').animate({
       'opacity': 0
    }, 300, function() {
        $('.poptip1').hide();
        $('.poptip2').show();
    });
    login = 1;
});
$('.poptip2 .btn_iKnow').click(function() {
    $('.poptip2').animate({
       'opacity': 0
    }, 300, function() {
        $('.poptip2').hide();
    });
    login = 1;
});
$('.poptip3 .btn_iKnow').click(function() {
    $('.poptip3').fadeOut();
    dG = 1;
	$('#deleteZuzhi').removeClass("false");

});
//-->
</script>
<div class="contHead">
        <div class="contHead-left">
            <div class="name">组织架构</div>
            
            <div class="toolBar">
                    <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
                <a class="addFunction" id="addFunction" onclick="addFunction();" title="添加功能"></a>
                    <?php }?>
            </div>
        </div>
    <div class="orgNode" id="orgNode" style="float: left;">
                <div class="bread">
                    <span><?php echo $org_json[0]['name'];?></span>
                </div>
        </div>
	<div class="contHead-right">
		<div class="fr rightLine">
			<a class="btnSet" onclick="toggleMenu('menu1',event)"></a>
		</div>
		<div class="headSearch rightLine">
			<div class="combo searchBox">
				<b class="bgR"></b>
				<a class="icon js-search" ></a>
				<label class="label">请输入查询条件</label>
				<input class="input" type="text" name="keyword" id="search_staff"/>
			</div>
		</div>
		<ul class="menu" id="menu1" style="*height:60px">
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER){?>
			<li>
				<a onclick="loadCont('tag/addTagPage/0','group')">员工标签管理</a>
			</li>
		<?php }?>
                <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
                        <li>
                        <a class="link_limitSet"  onclick="toggleGroupLimit(this,event)" title="部门权限">部门权限</a>
                        </li>
                <?php }?>
		</ul>
 	</div>
</div>
<div class="poptip" style="display: none">
    您可以指定管理员帮您管理
    <a class="btnRed btn_iKnow"><span class="text">知道了</span><b class="bgR"></b></a>
</div>

<div class="poptip1" style="display: none">
    需要大量导入新增员工或者调岗员工，可采用批量导 入的方式更新信息。 
    <a class="btnRed btn_iKnow" ><span class="text">知道了</span><b class="bgR"></b></a>
</div>

<div class="poptip2" style="display:none;z-index:99">
    可以勾选员工对其进行调岗、删除或者指定为部门管理者。 
    <a class="btnRed btn_iKnow" ><span class="text">知道了</span><b class="bgR"></b></a>
</div>
<div class="poptip3" style="display:none;z-index:99">
    请先勾选员工进行调岗或者删除，再删除部门。 
    <a class="btnRed btn_iKnow" style="float: right;*margin-top:-28px" ><span class="text">知道了</span><b class="bgR"></b></a>
</div>
<div class="pop-box " id="allGroup2" style="display: none;z-index:99;">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	<ul class="ztree" id="ztree2"></ul>
    </div>
</div>
