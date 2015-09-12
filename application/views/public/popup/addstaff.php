<dl class="dialogBox D_addAccounts">
	<dt class="dialogHeader">
		<span class="title">添加员工</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
           
	<dd class="dialogBody" style="overflow: inherit">
		<table class="infoTable">
		   <?php 
		   //print_r($system_must_tag_arr);
		  //必选员工标签
		   foreach($system_must_tag_arr as $k => $v):
				$field = arr_unbound_value($v,'field',2,'');
				$umsapifield = arr_unbound_value($v,'umsapifield',2,'');
				$title = arr_unbound_value($v,'title',2,'');
				$regex = arr_unbound_value($v,'regex',2,'');
				$tag_value = arr_unbound_value($v,'tag_value',2,'');
				?>
				<?php if ($umsapifield == 'lastName')://姓名?> 
					<tr>
						<td class="tr">姓名：</td>
						<td colspan="3">
							<div class="inputBox">
								<label class="label"></label>
								<input class="input" id="<?php echo $umsapifield ;?>"  value="<?php echo $tag_value ;?>"style="width: 200px" />
							</div>
						</td>
					</tr>
				<?php 
				continue;
				endif;?> 
				<?php if ($umsapifield == 'sex')://性别//0未知1男2女?> 
					<tr>
					  <td class="tr">性别：</td>
					  <td colspan="3" id="<?php echo $umsapifield;?>">
					  		<label id="addstaff_man" class="radio<?php if( $tag_value == 1):?>radio_on<?php endif; ?>" for="sex_01"><input name="sex" type="radio" id="sex_01"  />男</label>
							<label id="addstaff_women" for="sex_02" class="radio <?php if( $tag_value == 2):?>radio_on<?php endif; ?>"><input name="sex" type="radio" id="sex_02" />女</label>
					</td>
				  </tr>
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'position')://职位?>
					<tr>
					  <td class="tr">职位：</td>
					  <td colspan="3"><div class="inputBox">
						<label class="label"></label>
						<input class="input" id="<?php echo $umsapifield ;?>" value="<?php echo  $tag_value ;?>" style="width: 200px" />
						</div></td>
				  </tr>
				 <?php 
				 continue;
				 endif;?> 
				 <?php if ($umsapifield == 'loginName')://帐号?> 
					<tr>
						<td class="tr">帐号：</td>
						<td colspan="3" id="account">
							<div class="inputBox">
								<label class="label"></label>
								<input class="input" id="<?php echo $umsapifield ;?>"  style="width: 200px" />
							</div> &nbsp;&nbsp;
						<?php // if ($umsapifield == 'isopen')://帐号?> 
							<label id="addstaff_count_open" class="radio radio_on"  for="openAccounts_01"><input name="openAccounts" type="radio" id="openAccounts_01"/>开启</label>
							<label id="addstaff_count_close" for="openAccounts_02" class="radio" style="margin-right: 0;"><input name="openAccounts" type="radio" id="openAccounts_02" />关闭</label>
						<?php // endif;?> 
						</td>
					</tr>
				<?php 
				continue;
				endif;?> 
				<?php if ($umsapifield == 'accountId')://账户?> 
					<tr>
						<td class="tr">账户：</td>
						<td>
							<div class="combo selectBox" style="width:212px;">
								<a class="icon" ></a>
								<input class="text" value="<?php echo $account_names[0]['name'];?>" style="width: 175px" readonly="readonly" onfocus="$(this).blur();"/>
								<div class="optionBox" target="0" id="<?php echo $umsapifield ;?>">
									<dl class="optionList" id="acount_user">
									<?php foreach($account_names as $k=>$item): ?>
										<dd class="option <?php echo $k==0 ? 'selected' : '';?>" target="0" style="" account_id="<?php echo $item['accountId'] ?>"><?php echo $item['name'];?></dd>
									<?php endforeach; ?>
									</dl>
								</div>
								
							</div>
						</td>
						<!-- <td class="tr">&nbsp;</td>
						<td>&nbsp;</td>
						 -->
					</tr>
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'mobileNumber')://手机?>
					<tr>
						<td class="tr">手机：</td>
						<td colspan="3">
							<div class="combo selectBox w60" style="z-index: 1;">
								<a class="icon" ></a>
								<span class="text selected"><?php echo $country_code ?></span>
								<div class="optionBox" target="0">
									<dl class="optionList" style="height: 52px;">
									   <!--
										<dd class="option selected" target="0" style="">+86</dd>
										<dd class="option" target="1">+85</dd>-->
										<?php 
										$c_i = 0;
										foreach($country_arr as $c_k => $c_v): 
										    $country_code = arr_unbound_value($c_v,'country_code',2,'');
											$is_selected = arr_unbound_value($c_v,'is_selected',2,0);											
											?>
											<dd class="option <?php if($is_selected == 1): ?> selected <?php endif ; ?>" target="<?php echo $c_i ;?>"><?php echo $country_code ;?></dd>
										<?php 
										$c_i += 1;
										endforeach; ?>
									</dl>
									<input type="hidden" class="val" value="0">
								</div>
							</div>
							<div class="inputBox">
								<label class="label"><?php echo $country_mobile ;?></label>
								<input class="input" id="<?php echo $umsapifield ;?>"  value="<?php echo $country_mobile ;?>" style="width: 134px;">
							</div>
						</td>
					</tr>
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'officeaddress')://办公地址?>
					<tr>
						<td class="tr">办公地址：</td>
						<td>
							<div class="inputBox" style="width: 212px;">
								<label class="label"></label>
								<input class="input" id="<?php echo $umsapifield ;?>" value="<?php echo $address;?>" style="width: 200px;" />
							</div>
						</td>
						<td class="tr">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
				<?php 
				continue;
				endif;?>	
				<?php if ($umsapifield == 'organizationId')://部门?>
					<tr>
						<td class="tr">选择部门：</td>
						<td>
							<div class="inputBox selectInput" id="inputVal2" style="z-index:2">
								<a class="icon"  onclick="showTreeList(event)"></a>
								<label class="label"></label>
								<input class="input" id="<?php echo $umsapifield ;?>" value="" style="width: 180px" readonly="readonly"   onclick="showTreeList(event)"/>
                                                                <div id="treeOption" style="z-index:9">
                                                                    <ul class="ztree" id="departmentTree"></ul>
                                                                </div>
							</div>
						</td>						
					</tr>
				<?php
				continue;
				 endif;?>
			<?php endforeach;?> 
			<?php //可选员工标签 ?>
			<?php foreach($seled_not_must_tag_arr as $k => $v){
				$field = arr_unbound_value($v,'field',2,'');
				$umsapifield = arr_unbound_value($v,'umsapifield',2,'');
				$title = arr_unbound_value($v,'title',2,'');
				$regex = arr_unbound_value($v,'regex',2,'');
				$tag_value = arr_unbound_value($v,'tag_value',2,'');
				 ?>
					<tr>
					  <td class="tr"><?php echo $title;?>：</td>
					  <td colspan="3"><div class="inputBox">
						<label class="label"></label>
						<input class="input" id="<?php echo $umsapifield;?>"  value="<?php  echo $tag_value; ?>" style="width: 200px" />
						</div></td>
				  </tr>
			<?php }?> 
			<?php //自定义员工标签 ?>
			<?php 
			//print_r($user_defined_tag_arr);
			foreach($user_defined_tag_arr as $k => $v):
				$tag_name = arr_unbound_value($v,'tag_name',2,'');//自定义标签名称
				$tag_id = arr_unbound_value($v,'id',2,'');//自定义标签id
				$regex = arr_unbound_value($v,'regex',2,'');//自定义标签正则[以后传]
				$tag_value = arr_unbound_value($v,'tag_value',2,'');//自定义标签值
				?>
					<tr>
					  <td class="tr"><?php echo $tag_name;?>：</td>
					  <td colspan="3"><div class="inputBox">
						<label class="label"></label>
						<input class="input" id="user_tag<?php  echo $tag_id;?>"  value="<?php  echo $tag_value;?>"  style="width: 200px" />
						</div></td>
				  </tr>
			<?php endforeach;?> 
		</table>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes"  onclick="add_staff(event,this)"><span class="text">添加</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">

if(!$('#sex .radio').hasClass("radio_on")){
    $('#sex .radio:eq(0)').addClass("radio_on");
}
var on_off = 0; //0关闭1开启
var path1 = "organize/get_next_OrgList"; //要加载的每个组织结构
        
var page_type_json ='<?php echo $page_type_json; ?>';
//alert(page_type_json)
var add_json = $.parseJSON(page_type_json);
var add_type = add_json.type;

<?php
    foreach($system_must_tag_arr as $k => $v){
        $field = arr_unbound_value($v, 'field', 2, ''); //字段名称
        $umsapifield = arr_unbound_value($v, 'umsapifield', 2, ''); //ums字段名称
        $title = arr_unbound_value($v, 'title', 2, ''); //名称
        $regex = arr_unbound_value($v, 'regex', 2, ''); //正则
        $tag_value = arr_unbound_value($v, 'tag_value', 2, 1);
         if ($umsapifield == 'isopen'){ 
                $on_off = $tag_value; //0关闭1开启
                break;
         }else{
                $on_off = '';
         }
    }
?>
on_off = <?php echo $on_off;?>;
if(on_off!="")
{
	on_off=<?php echo $on_off;?>;
	if (on_off == 0) {
		$('#openAccounts_01').parent().removeClass("radio_on");
		$('#openAccounts_02').parent().removeClass("radio_on");
		$('#openAccounts_02').parent().addClass("radio_on");
	} else {
		$('#openAccounts_01').parent().removeClass("radio_on");
		$('#openAccounts_02').parent().removeClass("radio_on");
		$('#openAccounts_01').parent().addClass("radio_on");
	}
}
	
var default_user_org_json = '<?php echo $org_json;?>'; //默认的当前的用户部门串;
if (add_type!=3)
{
	var obj = getSelectNode();
        var nodeName = obj.name;
	if (obj.oid != null && obj.pid != "0") {
		//var value = [];
            $('#inputVal2').find("input").val(nodeName);//'{"id":"' + treeNode.id + '","value": "' + treeNode.name + '"},'
            default_user_org_json='{"id":"'+obj.oid+'","value":"'+nodeName+'"}';
	}
}
if(add_type==3)
{
	var text;
	var str='';
	if(default_user_org_json)
	{
		text=$.parseJSON(default_user_org_json);
		for(var i=0;i<text.length;i++)
		{
			str=str+'-'+text[i].value;
		}
	}
	
	$('#lastName').parent().removeClass("inputBox");
	$('#lastName').prev().text($('#lastName').val());
	$('#organizationId').parent().removeClass("inputBox");
	$('#organizationId').prev().text($('#organizationId').val());
	$('#position').parent().removeClass("inputBox");
	$('#position').prev().text($('#position').val());
	$('#mobileNumber').parent().removeClass("inputBox");
	$('#mobileNumber').prev().text($('#mobileNumber').val());
	var tele=$('#mobileNumber').parent().prev().find('span.text');
	$('#mobileNumber').parent().prev().removeClass("selectBox combo");
	$('#mobileNumber').parent().prev().text(tele.text());
	$('#lastName').remove();
	$('#inputVal2').find('a.icon').remove();	
	$('#inputVal2 input').remove();
	$('#inputVal2 #treeOption').remove();
	$('#inputVal2 label').text(str);
	$('#inputVal2').css("width",400);
	$('#position').remove();
	$('#mobileNumber').remove();
	
}
//提交添加员工操作
function add_staff(e,t) {
    if($(t).hasClass("false"))
    {
            return;
    }
    $(t).addClass("false");
    var _t=$(t);
    //必选员工标签
    var sys_tag_value; //系统及可选员工标签
    sys_tag_value = "";
    var user_tag_value; //用户自定义员工标签
    user_tag_value = "";
    var org_tag_value; //组织
    org_tag_value = "";
    var ns_value; //临时的值
    ns_value = "";
    var ns_regex; //临时的正则
    ns_regex = "";
        var count=0;
    //{"name": "姓名","value": "开发测试","umsapifield": "lastName"}

<?php
// print_r($system_must_tag_arr);
    foreach($system_must_tag_arr as $k => $v) {
    $field = arr_unbound_value($v, 'field', 2, ''); //字段名称
    $umsapifield = arr_unbound_value($v, 'umsapifield', 2, ''); //ums字段名称
    $title = arr_unbound_value($v, 'title', 2, ''); //名称
    $regex = arr_unbound_value($v, 'regex', 2, ''); //正则
    $tag_value = arr_unbound_value($v, 'tag_value', 2, '');
    //echo $umsapifield;
    //echo '  ';
    
    if ($umsapifield == 'lastName') { //姓名
    ?> 
        var ns_value = $("#<?php echo $umsapifield;?>").val(); //拿到值
        if (sys_tag_value != '') { //有值则加一个逗号
            sys_tag_value = sys_tag_value + ',';
        }
        //对值进行正则判断
        ns_regex = "<?php echo $regex;?>";
        if (ns_regex != '') { //有正则，才去做判断
                    ns_regex=<?php echo $regex;?>;
                    if(!ns_regex.test(ns_value))
                       {
                            $("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
                            count++;

                       }
                     else
                     {
                             sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}'; 
                     }
        }
   
    <?php
    continue;
    }
    
    if ($umsapifield == 'sex'){ //性别//0未知1男2女?> 
            
        ns_value = $('#<?php echo $umsapifield;?>').find('label.radio_on').text(); //拿到值
        if (ns_value == "男") {
            ns_value = 1;
        } else if (ns_value == "女") {
            ns_value = 2;
        } else {
            ns_value = 0;
        }
        //alert(ns_value)
        if (sys_tag_value != '') { //有值则加一个逗号
            sys_tag_value = sys_tag_value + ',';
        }
        //对值进行正则判断
        ns_regex = "<?php echo $regex;?>";
        if (ns_regex != '') { //有正则，才去做判断
                    ns_regex=<?php echo $regex;?>;
                    if(!ns_regex.test(ns_value))
                    {
                            $("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
                            count++;
                    }
                    else
                    {
                             sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}';
                    }
        }
   
    <?php
    continue;
    }
    if ($umsapifield == 'position') { //职位?>
    ns_value = $("#<?php echo $umsapifield;?>").val();; //拿到值
    if (sys_tag_value != '') { //有值则加一个逗号
        sys_tag_value = sys_tag_value + ',';
    }
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
    if (ns_regex != '') { //有正则，才去做判断
		ns_regex=<?php echo $regex;?>;
		if(!ns_regex.test(ns_value))
		{
			$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
			count++;
		}
		else
		{
			 sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}';
		}
    }
   

    <?php
    continue;
    } ?>
    <?php
    if ($umsapifield == 'loginName') : //帐号?> 
    ns_value = $("#<?php echo $umsapifield;?>").val(); //拿到值
    if (sys_tag_value != '') { //有值则加一个逗号
        sys_tag_value = sys_tag_value + ',';
    }
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
	//alert(ns_regex)
	//验证账号长度不能长于128字符
	if(ns_value.length >= 128){
		$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
		count++;
	}
    if (ns_regex != '') { //有正则，才去做判断
		ns_regex=<?php echo $regex;?>;
		if(!ns_regex.test(ns_value))
		{
			//alert(1)
			$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
			count++;
		}
		else
		{
			sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}';
		}
    }
    
    ns_value = $("#<?php echo $umsapifield ;?>").parent().siblings('label.radio_on').text();
    //alert(ns_value)
    ns_value = (ns_value == '开启') ? 1 : 0; //1on,0off
    if (sys_tag_value != '') { //有值则加一个逗号
        sys_tag_value = sys_tag_value + ',';
    }
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
    if (ns_regex != '') { //有正则，才去做判断
    }
    sys_tag_value = sys_tag_value + '{"name": "开启帐号","value": "' + ns_value + '","umsapifield": "isopen"}'; 
    <?php
    continue;
    endif; ?>

    <?php
    if ($umsapifield == 'accountId') : //账户?> 
     ns_value =$("#<?php echo $umsapifield;?>").find('dd.selected').attr("account_id");//拿到值   
   	if(sys_tag_value != ''){//有值则加一个逗号
    	sys_tag_value = sys_tag_value + ',';
    }
	if(ns_value=='')
	{
		$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
		count++;
	}
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
    if (ns_regex != '') { //有正则，才去做判断
		ns_regex=<?php echo $regex;?>;
		if(!ns_regex.test(ns_value))
		{
			$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
			count++;
		}
		else
		{
			
			sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}';
		}
    }
    //
    <?php
    continue;
    endif; ?>
        
    <?php
    if ($umsapifield == 'mobileNumber') : //手机?>
    var pre = $("#<?php echo $umsapifield ;?>").parent().parent().find("span").text();
	ns_value = $("#<?php echo $umsapifield ;?>").val();; //拿到值
	if (sys_tag_value != '') { //有值则加一个逗号
			sys_tag_value = sys_tag_value + ',';
		}
	if(add_type!=3)
	{
		
		ns_value = '' + pre + '' + ns_value + '';
		//对值进行正则判断
		ns_regex = "<?php echo $regex;?>";
		//alert(ns_regex)
		//alert(ns_value)
		if (ns_regex != '') { //有正则，才去做判断
			ns_regex=<?php echo $regex;?>;
			if(!ns_regex.test(ns_value))
			{
				$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
				count++;
			}
			else
			{
				sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
			}
   		 }
	}
   else
   {
   		sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
   }
	
    <?php
    continue;
    endif; ?><?php
    if ($umsapifield == 'officeaddress') : //办公地址?>
	//alert(1)
    ns_value = $("#<?php echo $umsapifield;?>").val();; //拿到值
    if (sys_tag_value != '') { //有值则加一个逗号
        sys_tag_value = sys_tag_value + ',';
    }
	//alert(ns_value)
    //对值进行正则判断
	if(ns_value=="")
	{		
		$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
		count++;
	}
	else
	{
		ns_regex = "<?php echo $regex;?>";
		if (ns_regex != '') { //有正则，才去做判断
			ns_regex=<?php echo $regex;?>;
			if(!ns_regex.test(ns_value))
			{
				$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
				count++;
			}
			else
			{
				sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
	
			}
		}
	}
    
    <?php
    continue;
    endif; ?>

    <?php
    if ($umsapifield == 'organizationId') { //部门
    ?>
	if(add_type!=3)
	{
                var treeNode = getSelectNode("#departmentTree");	
                //alert(treeNode.name);
                if(treeNode.pid==="0"){//如果部门PID（父类ID）等于0 那么说明是根组织
                    $("#organizationId").parent('div').addClass('error');
                    alert("根组织禁止添加员工！");
                    return;
                }
                if(treeNode.oid && $("#organizationId").val())
		{
                        $("#organizationId").parent('div').removeClass('error');
                        org_tag_value = org_tag_value + '{"id":"' + treeNode.oid + '","value": "' + treeNode.name + '"},';
                        var pid = treeNode.pid;
                        //var nodeCode = treeNode.nodeCode;
                        if (pid != null) {
                                var pNode = $("#ztree [org_id='"+pid+"']");
                                var nodeId = pid;
                                var nodeName = $.trim(pNode.text());
                                org_tag_value = '{"id":"' + nodeId + '","value": "' + nodeName + '"},' + org_tag_value;
                        }
                        var staff_tag_post = org_tag_value;
                        var lastIndex = staff_tag_post.lastIndexOf(',');
                        if (lastIndex > -1) {
                        org_tag_value = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
                        } 
                        //alert(org_tag_value)
                        ns_regex ="<?php echo $regex;?>" ;
                        if(ns_regex != ''){//有正则，才去做判断
                                ns_regex=<?php echo $regex;?>;
                                if(!ns_regex.test(treeNode.name))
                                {
                                        $("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
                                        count++;
                                }
                        }  
                        //sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
                }
                else
                {
                    
		   $("#organizationId").parent('div').addClass('error');
		    //alert("organizationId");
                    org_tag_value = default_user_org_json;
                    //alert(org_tag_value)
                    count++;
		}
		 
	}
	else
	{
		org_tag_value =	default_user_org_json;
	}
        
    <?php
    continue;
        }
    }
    ?>
            
    <?php 
    //可选员工标签,放系统标签里面
    foreach($seled_not_must_tag_arr as $k =>$v) { //循环
        $field = arr_unbound_value($v, 'field', 2, ''); //字段名称
        $umsapifield = arr_unbound_value($v, 'umsapifield', 2, ''); //ums字段名称
        $title = arr_unbound_value($v, 'title', 2, ''); //名称
        $regex = arr_unbound_value($v, 'regex', 2, ''); //正则
        $tag_value = arr_unbound_value($v, 'tag_value', 2, ''); 
    ?>
    
    ns_value = $("#<?php echo $umsapifield;?>").val(); //拿到值
    if (sys_tag_value != '') { //有值则加一个逗号
        sys_tag_value = sys_tag_value + ',';
    }
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
    if (ns_regex != '') { //有正则，才去做判断
		ns_regex=<?php echo $regex;?>;
		if(!ns_regex.test(ns_value))
		{
			//alert(ns_value)
			$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
			count++;
		}
		else
		{
			 sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield;?>"}';
		}
    }
   
    <?php 
    }
    ?>
            
    //自定义员工标签 
    <?php
    foreach($user_defined_tag_arr as $k =>$v) { //循环
    $tag_name = arr_unbound_value($v, 'tag_name', 2, ''); //自定义标签名称
    $tag_id = arr_unbound_value($v, 'id', 2, ''); //自定义标签id
    $regex = arr_unbound_value($v, 'regex', 2, ''); //自定义标签正则
    ?>
    //alert('<?php echo  $tag_id;?>')
    ns_value = $('#user_tag<?php echo  $tag_id;?>').val(); //拿到值user_tag+标签id
    if (user_tag_value != '') { //有值则加一个逗号
        user_tag_value = user_tag_value + ',';
    }
	if(ns_value=="")
	{
		$('#user_tag<?php echo  $tag_id;?>').parent('div').addClass('error');
		count++;
	}
    //对值进行正则判断
    ns_regex = "<?php echo $regex;?>";
    if (ns_regex != '') { //有正则，才去做判断
		// ns_regex = <?php echo $regex;?>;
		if(!ns_regex.test(ns_value))
			{
				//alert(ns_value)
				$("#user_tag<?php echo  $tag_id;?>").parent('div').addClass('error');
				count++;
			}
		else
		{
			user_tag_value = user_tag_value + '{"tag_name": "<?php echo  $tag_name;?>","value": "' + ns_value + '","tag_id": "<?php echo  $tag_id;?>"}';
		}
    }
	else
	{
		//alert(1)
	}
    //{"tag_name": "birthday","tag_id": "1", "value": 19840229}
    

    <?php 
    
    }
    ?>
    
    if(count!=0)
	{
            $(t).removeClass("false");
            return false;
	}
	//alert(2)
    var post_json;
    post_json = '{"sys_tag":[' + sys_tag_value + '],"user_tag":[' + user_tag_value + '],"org_tag":[' + org_tag_value + ']}'; //组织
    //alert(post_json);
    var change_staff = {
        "user_json": post_json,
        "user_id": 0,
        "add_type": add_type,
        "page_type_json": page_type_json
    };
    
    var nowTreeHtml = $("#departmentTree").html();//取出添加员工页面中已经展开的目录树以方便后面完成后调用
    
    $.ajax({
                url: "staff/add_staff_open_product",
                async: false,
                type: "POST",
                data: change_staff,
                success: function(data) {
                //alert(data)
                var json = $.parseJSON(data);
                if (json.code == 0) {
                        //alert(json.prompt_text);
                        if (add_type == 3) {
                                $('#addstaff' + add_json.type_arr.task_id + '').parents("li").removeClass("new");
                                $('#addstaff' + add_json.type_arr.task_id + '').parents(".li-ml").html("已处理");
                        } else {
                                var newOrgId = json.other_msg;
                                var node = $("#departmentTree a[org_id='"+newOrgId+"']");
                                var parent_id = node.attr("parent_id");
                                var title = node.attr("title");
                                var nodeCode = node.attr("node_code");
                                var obj = {
                                    oid : newOrgId,
                                    pid : parent_id,
                                    title : title
                                };
                                
                                $("#ztree").html(nowTreeHtml);//把现有展开的组织结构复制到左边原组织树 以减少调用下级部门的请求
                                
                                setTimeout(function()
                                {
                                        //alert(111);
                                        showValue(obj);//显示数据
                                },1000);

                        }
                        hideDialog();
                        $(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
                        $('#part1 .infoTitle .personName').text($('.infoTable .infoText').next().find("input").val());
                        _t.removeClass("false");
                }
                else
                {
                        alert("操作失败："+json.prompt_text);
                        hideDialog();
                        _t.removeClass("false");
                }

            }
    });
    //hideDialog();
}


function showTreeList(event) {
    if(!$("#departmentTree").text()){
        var oldTreeDOM = $("#ztree").html();
        $("#departmentTree").html(oldTreeDOM);
        //$("#departmentTree a.nodeBtn").removeClass("curSelectedNode");
    }
    $('#treeOption').toggle();
    if ($('.optionBox').attr('target') == '1') {
        $('.optionBox').attr('target', '0');
    }
    $("body").bind("mousedown", onTreeListDown);
}
function hideTreeList() {
    $("#treeOption").fadeOut("fast");
    $("body").unbind("mousedown", onTreeListDown);
}
function onTreeListDown(event) {
    if (! (event.target.className == "icon" || event.target.className == "text" || event.target.id == "treeOption" || $(event.target).parents("#treeOption").length > 0)) {
        hideTreeList();
    }
}

function disable_select(t) {
    var _t = t;
    var pid = _t.attr("parent_id");
    //alert(pid);
    if(pid==="0"){
        _t.removeClass("curSelectedNode");
        alert("根组织不允许添加员工！");
        _t.next("ul").find("a.nodeBtn:first").click();//模拟点击下一级第一个节点以防空值
    }
    
    exit();
}

    //点击选择账户
    $('#acount_user dd').click(function(){       
            $(this).parent().find("dd.selected").removeClass("selected");
            $(this).addClass("selected");
            $(this).parent().parent().prev().css("color","#4f4f4f");
    });

    $('#treeOption a').live("click",function() {
            disable_select($(this));
    });


    //账户和部门的浮出菜单隐藏操作
    $('.infoTable tbody .combo').toggle(function() {
            if ($(this).find('.optionBox').attr('target') == '0') {
                // alert(1)
                $('#treeOption').hide();
                $('.infoTable tbody .combo ').find('.optionBox').hide();
                $('.infoTable tbody .combo ').find('.optionBox').attr('target', '0');
                $(this).find('.optionBox').show();
                $(this).find('.optionBox').attr('target', '1');
            } else {
                // alert(2)
                $(this).find('.optionBox').hide();
                $(this).find('.optionBox').attr('target', '0');
            }

        },
        function() {
            if ($(this).find('.optionBox').attr('target') == '0') {
                // alert(1)
                $('#treeOption').hide();
                $('.infoTable tbody .combo ').find('.optionBox').hide();
                $('.infoTable tbody .combo ').find('.optionBox').attr('target', '0');
                $(this).find('.optionBox').show();
                $(this).find('.optionBox').attr('target', '1');
            } else {
                // alert(2)
                $(this).find('.optionBox').hide();
                $(this).find('.optionBox').attr('target', '0');
            }
            //$(this).siblings('.optionBox').hide();
        }
    );
    
    
    //帐户开通选择切换
    $('#addstaff_count_close,#addstaff_count_open').click(function() {
        $(this).removeClass("checked");
        $(this).siblings().removeClass("checked");		
        $(this).siblings().removeClass("radio_on");
        $(this).addClass("radio_on");
    });
    
    //性别选择切换
    $('#addstaff_man,#addstaff_women').click(function() {
        $(this).removeClass("checked");
        $(this).siblings().removeClass("checked");	
        $(this).siblings().removeClass("radio_on");
        $(this).addClass("radio_on");

    });

</script>