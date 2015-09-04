<div id="part1" class="information_part">
	<div class="infoTitle"> 
		<a class="pageGoBack"></a> 
		<span class="personName"><?php echo $user_info_arr['displayName'];?></span>
		<div class="fr">
		<?php if($this->functions['AccountOperation']){?>
		 	<a class="btn btn0ff" id="count_handle"  onClick="toggleAccount(this)">
				<span class="text" style="text-decoration: none">
					<?php if($user_info_arr['productStatus'] == 82){ ?>关闭帐号<?php }else{?>开启账号<?php }?>
				</span>
				<b class="bgR"></b>
			</a>
			<?php }?>
			&nbsp; 
			<?php if($this->functions['changePassword']){?>
			<a class="btn"  onClick="showDialog('password/showTempPWD')">
				<span class="text"style="text-decoration: none">重置密码</span>
				<b class="bgR"></b>
			</a> 
			<?php }?>
		</div>
	</div>
	<div class="cont-wrapper">
		<ul class="infoNav" id="staff_info">
			<li class="selected">员工信息</li>
			<?php if($this->functions['employeeAuthority']){?>
			<li>员工权限</li>
			<?php 
			$org_json_arr = json_decode($org_json,true);
			if(!empty($org_json_arr['admin_arr'])){	//该员工是管理员才进行展示  ?>
			<li>管理员权限</li>
				<?php }?>
			<?php }?>
		</ul>
		<dl class="infoCont">
			<dd id="dd1">
			<?php if($this->functions['employeeEdit']){?>
				<div class="toolBar2">
					 <a class="btnGray btn btn_infoEdit" >
					 	<span class="text">编辑信息</span>
						<b class="bgR"></b>
					</a> 
					<a class="btnBlue yes btn_infoSave hide" >
						<span class="text">保存</span>
						<b class="bgR"></b>
					</a> 
					<a class="btnGray btn btn_infoCancel hide" >
						<span class="text">取消</span>
						<b class="bgR"></b>
					</a>
			 	</div>
			<?php }?>
			<table class="infoTable">
				<?php //必选员工标签 ?>
				<?php 
					   foreach($system_must_tag_arr as $k => $v):
// 					   		$field = arr_unbound_value($v,'field',2,'');
							$umsapifield = arr_unbound_value($v,'umsapifield',2,'');
							$title = arr_unbound_value($v,'title',2,'');
							$regex = arr_unbound_value($v,'regex',2,'');
							$tag_value = arr_unbound_value($v,'tag_value',2,'');
// 							echo $umsapifield;
							?>
			<?php if ($umsapifield == 'lastName')://姓名?>
			<tr>
				<td class="tr">姓名：</td>
				<td>
					<span class="infoText userName"><?php echo $tag_value;?></span>
					<div class="inputBox w360 hide add_css">
						<input id="nameInput" class="input" maxlength="100" id="<?php echo $umsapifield ;?>" value="<?php echo $tag_value ;?>"/>
					</div>
				</td>
			</tr>
			<?php
				continue;
				 endif;?>
			<?php if ($umsapifield == 'loginName')://帐号?>
			<tr>
				<td class="tr">帐号：</td>
				<td>
					<span class="infoText userCount"><?php echo $tag_value;?></span>
					<div class="inputBox w360 hide add_css">
						<input class="input account_input" maxlength="100" id="<?php echo $umsapifield ;?>" value="<?php echo $tag_value;?>" />
					</div>
				</td>
			</tr>
			<?php
							continue;
							 endif;?>
			<?php if ($umsapifield == 'accountId')://账户?>
			<tr>
				<td class="tr">账户：</td>
				<td>
					<span class="infoText userAccount" value="<?php echo $account_id;?>"><?php echo $account_name;?></span>
					<?php if($user_info_arr['productStatus'] == 82){ //账号只有开通后才能进行修改 ?>
					<div class="combo selectBox hide add_css"  style="width:204px;word-break:keep-all;"> 
						<a class="icon" ></a> 
						<span class="text" value="<?php echo $account_id;?>" style="width: 175px" readonly="readonly" onfocus="$(this).blur();"><?php echo $account_name;?></span>
						<div class="optionBox" target='0' style="width: 206px; display: none;*position:relative;*margin-top:-27px" id="<?php echo $umsapifield ;?>">
							<dl class="optionList" style="height: 95px;" id="staff_userCount">
								<?php foreach($account_names as $item): ?>
									<dd class="option" target="0" style="" account_id="<?php echo $item['accountId'] ?>"><?php echo $item['name'];?></dd>
								<?php endforeach; ?>
							</dl>
						</div>
					</div>
					<?php }?>
				</td>
			</tr>
			<?php 
							continue;
							endif;?>
			<?php if ($umsapifield == 'organizationName')://部门?>
			<tr>
				<td class="tr">部门：</td>
				<td>
					<span class="infoText" id="depart_span" readonly="readonly"><?php echo $tag_value;?></span>
					<div class="select-box w210 hide add_css">
						<input  readonly="readonly" type="text" class="text" value="" onClick="showMenu(this);" id="departmentSel2" placeholder="请选择管理的部门"  id="<?php echo $umsapifield ;?>" />
						<a class="icon" onClick="showMenu(this); return false;"></a>
						<div class="selectOptionBox "   target='0'  style="display: none;z-index:99;width: 208px;*position:relative;*top:1px">
							<ul class="ztree" id="ztree4">
							</ul>
						</div>
					</div>
				</td>
			</tr>
			<?php 
							continue;
							endif;?>
			<?php if ($umsapifield == 'position')://职位?>
			<tr>
				<td class="tr">职位：</td>
				<td>
					<span class="infoText position"><?php echo $tag_value ;?></span>
					<div class="inputBox w360 hide add_css" > 
						<b class="bgR"></b>
						<label class="label"></label>
						<input id="<?php echo $umsapifield ;?>" maxlength="100" class="input" value="<?php echo  $tag_value ;?>"/>
					</div>
				</td>
			</tr>
			<?php 
							continue;
							endif;?>
			<?php if ($umsapifield == 'mobileNumber')://手机?>
			<tr>
				<td class="tr">手机：</td>
				<td>
					<span class="infoText telephone1"><?php echo $country_code ?></span>
					<div class="combo selectBox w60 hide add_css"> 
						<a class="icon" ></a> 
						<span class="text selected" ><?php echo $country_code ?></span>
						<div class="optionBox"  target='0' style="display: none;*position:relative">
							<dl class="optionList" style="height: 52px;">
							<!--<dd class="option selected" target="0" style="">+86</dd>
													<dd class="option" target="1">+85</dd>-->
							<?php 
							$c_i = 0;
							foreach($country_arr as $c_k => $c_v): 
								$country_code = arr_unbound_value($c_v,'country_code',2,'');
								$is_selected = arr_unbound_value($c_v,'is_selected',2,0);											
								?>
							<dd onclick="change_value(this)" class="option <?php if($is_selected == 1): ?> selected <?php endif ; ?>" target="<?php echo $c_i ;?>"><?php echo $country_code ;?></dd>
							<?php 
								$c_i += 1;
								endforeach; ?>
							</dl>
						</div>
					</div>
					- <span class="infoText telephone2"><?php echo $country_mobile ;?></span>
					<div class="inputBox w130 hide add_css" style="*margin-left:10px;*margin-top:-26px"> 
						<b class="bgR"></b>
						<label class="label"></label>
						<input id="<?php echo $umsapifield ;?>" maxlength="100" class="input" value="<?php echo $country_mobile ;?>" />
					</div>
				</td>
			</tr>
			<?php 
							continue;
							endif;?>
			<?php endforeach;?>
			<?php //可选员工标签 ?>
			<?php foreach($seled_not_must_tag_arr as $k => $v):
// 					   		$field = arr_unbound_value($v,'field',2,'');
							$umsapifield = arr_unbound_value($v,'umsapifield',2,'');
							$title = arr_unbound_value($v,'title',2,'');
							$regex = arr_unbound_value($v,'regex',2,'');
							$tag_value = arr_unbound_value($v,'tag_value',2,'');
							 ?>
			<tr>
				<td class="tr"><?php echo $title;?>：</td>
				<td>
					<span class="infoText">
					<?php  echo $tag_value;?>
					</span>
					<div class="inputBox w360 hide add_css" style="*z-index:1"> <b class="bgR"></b>
						<label class="label"></label>
						<input class="input" id="<?php echo $umsapifield ;?>" maxlength="100" value="<?php  echo $tag_value;?>" />
					</div>
				</td>
			</tr>
			<?php endforeach;?>
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
				<td>
					<span class="infoText">
					<?php  echo $tag_value;?>
					</span>
					<div class="inputBox w360 hide" style="_z-index:-1"> <b class="bgR"></b>
						<label class="label"></label>
						<input class="input" id="user_tag<?php  echo $tag_id;?>" maxlength="100" value="<?php  echo $tag_value;?>" />
					</div>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</dd>
	<dd style="display:none;" id="dd2" class="staff_right">
		<div class="toolBar2" style="display:none;"> 
			<a class="btnBlue yes">
				<span class="text">保存</span>
				<b class="bgR"></b>
			</a>
			<a class="btnGray btn" >
				<span class="text">还原设置</span>
				<b class="bgR"></b>
			</a>
		</div>
		<!-- 	<h3 class="setTitle">IM设置</h3> -->
	<!-- 	<label class="checkbox im_file checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	可使用全时蜜蜂 IM 互传文档</label> -->
	<!-- 	<label class="checkbox add_link checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的联系人添加到常用联系人列表</label> -->
	<!-- 	<label class="checkbox add_success checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的讨论组添加到讨论组列表</label> -->
	<h3 class="setTitle">通话设置</h3>
		<label class="checkbox accept_call checked" style="width: 155px;">
			<input type="checkbox" checked="checked" />
				允许用户设置接听策略
		</label>
		<label class="checkbox set_area checkbox2" style="width: 240px;">
			<input type="checkbox" checked="checked" />
				用户可设定接听策略到海外直线电话
		</label>
		<label class="checkbox accept_cloud checked" style="width: 155px;">
			<input type="checkbox" checked="checked">
				允许使用蜜蜂拨打电话
		</label>
		<label class="checkbox accept_areaPhone checkbox2 checked" style="width: 130px;">
			<input type="checkbox" checked="checked" />
				允许拨打海外电话
		</label>
	<h3 class="setTitle">会议设置</h3>
		<dl class="radio-dl">
			<dt>允许用户使用语音接入方式</dt>
			<dd class="pc_warning">
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="0" checked="checked" id="xb_1" />
					电话+VoIP
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					电话
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="2" id="xb_1" />
					VoIP
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="3" id="xb_1" />
					VoIP+国内本地接入
				</label>
			</dd>
		</dl>
		<label class="checkbox allow_attendee_call checked" style="width: 150px;">
			<input type="checkbox" checked="checked" />
				允许参会人自我外呼
		</label>
		<label class="checkbox record_name checked" style="width: 260px;">
			<input type="checkbox" checked="checked" />
				所有参会者在加入会议时，允许录制姓名
		</label>
		<dl class="radio-dl">
			<dt>主持人加入会议语音提示</dt>
			<dd class="add_warning">
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrJrTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>主持人退出会议语音提示</dt>
			<dd class="present_exit">
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>主持人未入会，参会人入会时的初始状态</dt>
			<dd class="initial_state">
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="T" checked="checked" id="xb_0" />
					可听可讲
				</label>
				<label  class="radio">
					<input type="radio" name="zcrTcTs" value="M" id="xb_1" />
					静音
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>参会人加入会议语音提示</dt>
			<dd class="warning_radio">
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="chrJrTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
			<dt>参会人退出会议语音提示</dt>
			<dd class="exit_warning">
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="0" checked="checked" id="xb_0" />
					无提示音
				</label>
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="1" id="xb_1" />
					蜂音
				</label>
				<label  class="radio">
					<input type="radio" name="chrTcTs" value="1" id="xb_1" />
					语音报名
				</label>
			</dd>
		</dl>
		<label class="checkbox report_num checked" style="width: 220px;">
			<input type="checkbox" checked="checked" />
			参会人加入会议，告知参会者人数
		</label>
		<label class="checkbox warning_information checked" style="width: 325px;">
			<input type="checkbox" checked="checked" />
			第一方与会者是否听到“您是第一个入会者”的提示
		</label>
		<dl class="radio-dl">
		<dt>主持人离开会议时，何时结束会议</dt>
			<dd class="meeting_leave">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="0" checked="checked" id="zcrTh_0" />
					是，立即结束
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="1" id="zcrTh_1" />
					否，会议继续进行
				</label>
			</dd>
		</dl>
		<dl class="radio-dl">
		<dt>主持人退出会议时，会议是否自动终止</dt>
			<dd class="meeting_end">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="0" checked="checked" id="zcrTh_0" />
					是，立即结束
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="1" id="zcrTh_1" />
					否，会议继续进行
				</label>
			</dd>
		</dl>
		<label class="checkbox accept_95 checked" style="width: 240px;">
			<input type="checkbox" checked="checked" />
			数据会议结束后，立即结束电话会议
		</label>
		<dl class="radio-dl">
		<dt>VoIP 音频质量</dt>
			<dd class="voip_quality">
				<label  class="radio">
					<input type="radio" name="zcrTh" value="11" checked="checked" id="zcrTh_0" />
					高保真音质
				 </label>
				<label  class="radio">
					<input type="radio" name="zcrTh" value="13" id="zcrTh_1" />
					标准音质
				</label>
			</dd>
		</dl>
		<label class="accept_max">
			会议允许最大方数
			<input id="accept_max_input" name="accept_max_input" class="form-text input_right"  style="width: 60px;" value="" placeholder="" type="text"/>方
			<span class="gray-style">(只限数字，最大2000方，最小2方)</span>
		</label>
		<label class="checkbox accept_inner_local" style="width: 130px;">
			<input type="checkbox" checked="checked" />
			允许国内本地接入
		</label>
		<label class="checkbox accept_40" style="width: 135px;">
			<input type="checkbox" checked="checked" />
			允许国内 400 接入
		</label>
		<label class="checkbox accept_80" style="width: 135px;">
			<input type="checkbox" checked="checked" />
			允许国内 800 接入
		</label>
		<label class="checkbox accept_hk_local" style="width: 140px;">
			<input type="checkbox" checked="checked" />
			允许香港 local 接入
		</label>
		<label class="checkbox accept_toll_free" style="width: 160px;">
			<input type="checkbox" checked="checked" />
			允许国际 toll free 接入
		</label>
		<label class="checkbox accept_local_toll" style="width: 165px;">
			<input type="checkbox" checked="checked" />
			允许国际 local toll 接入
		</label>
	</dd>
	</div>
</div>
<script type="text/javascript">
//var  zNodes = <?php echo $org_list_json ;?>;//初始化的组织结构
//var path="<?php echo site_url('organize/get_next_OrgList');?>";//要加载的每个组织结构
//alert(<?php echo $user_info_arr['displayName'];?>)
count_name=$('#part1 .infoTitle .personName').text();
var user_id=<?php echo $user_id;?>;
var  staff_account={
	//"parent_orgid":parent_orgid,
	//"orgid":org_ID,
	 "type_id":2,
	"user_id":user_id
 };
function toggleAccount(t)
{
			
	if($(t).find("span.text").text()=="开启帐号")
	{
		showDialog('<?php echo site_url('staff/closeAccount'); ?>');
		var _this = $(t);
		
		 $("#dialog .dialogBottom #closeAccount").die("click");
		 $("#dialog .dialogBottom #closeAccount").live("click",function()
		 {
			var path_close_account = '<?php echo site_url('staff/close_user'); ?>';
											// alert( staff_account.user_id)
			$.post(path_close_account,staff_account,function(data)
				{
					//alert(data);                                    
					 var json=$.parseJSON(data);
					 
					 if(json.code==0)
						{
							_this.find("span.text").text("关闭帐号");
							hideDialog();
						 }else
							{
								alert(json.prompt_text);
							}
				});
		});
	}
else {
		var path_Open = '<?php echo site_url('staff/open_user')?>';
			// alert(111);
			$.post(path_Open,staff_account,function(data)
			{
			  //alert(data);
			   var json=$.parseJSON(data);
			   if(json.code==0)
			   {
				  //alert(444);
				  $(t).find("span.text").text("开启帐号");
			   }
			   else
				{
					alert(json.prompt_text);	
				}
			});
	
	}
}
function create_node(Nodes)
{
	var leng=Nodes.length;
	//alert(Len)
	// alert(leng)
	
	for(var i=0; i<leng;i++)
	{
	  if(Nodes[i].isParent>0 )
	  { 
	  	Nodes[i].nocheck=true;
	 }
	 else
	 {
		
		 Nodes[i].nocheck=false;
	
	 }
	}
}
function disable_select()
{
			//alert(1);
	var zTree = $.fn.zTree.getZTreeObj("ztree4");
	var treeNode=zTree.getSelectedNodes();
	if(treeNode[0]!=null)
	{
	 
	   if(treeNode[0].isDisabled==true)
		{
		  //alert(21);
		  zTree.cancelSelectedNode(treeNode[0]);
		}
	}
}

//修改员工信息
function change_value()
{
	$("#part1").removeClass("value_change");
	$("#part1").addClass("value_change");
}
$(function(){
	//alert(3333)
	//为INPUT绑定ONCHANG事件\
	$('#part1 input').bind('input propertychange', function() {
    	change_value();
	});
	//员工信息修改的取消按钮
	$('.btn_infoCancel').click(function()
	{
		$("#part1").removeClass("value_change");
	});
	
	$('#part1 label.checkbox').click(function()
	{
		change_value();
	})
	var default_user_org_json='';
	default_user_org_json ='<?php echo $org_json;?>';//默认的当前的用户部门串
	default_user_org_json=$.parseJSON(default_user_org_json);
	var depart_input='';
	if(default_user_org_json!='')
	{
		depart_input=default_user_org_json[0].value;
	}
	var len=default_user_org_json.length;
	if(len>0)
	{
		for(var i=1;i<len;i++)
		{
			depart_input=depart_input+"-"+default_user_org_json[i].value;
		}	
	$('#depart_span').text(depart_input);
	$('#depart_span').next().find('input').val(default_user_org_json[len-1].value);
	}
   
 	$('#ztree4 a').live("click",function()
	  {
		  
		  disable_select();
		 

  	 })
	// if(clear_null==0)
	// {
	create_node(zNodes);
	//$.fn.zTree.init($("#ztree"), setting, zNodes);
	$.fn.zTree.init($("#ztree4"), radioSetting, zNodes);
	$.fn.zTree.init($("#selectTree"), selectSetting, zNodes);
	$('.radioBox label.radio').click(function()
	{ 
 	 $(this).siblings().removeClass("radio_on");
 	 $(this).addClass("radio_on");
 
	});
	//点击员工信息
	 $('#staff_info li:eq(0)').click(function()
 	{
	 $(this).addClass('selected').siblings().removeClass('selected');
	 $('#dd1').show();
	 //$('.toolBar2:eq(0)').show();
	 $('#dd2').hide();
	 });
//点击员工权限
	var staff_right;
	$('#staff_info li:eq(1)').die('click');
	$('#staff_info li:eq(1)').live('click',function()
	{
	//$('.infoCont .infoTable').hide();
		$('#dd1').hide();
		$(this).addClass('selected').siblings().removeClass('selected');
		if(!$('.groupLimit h3').hasClass("setTitle"))
		{
			var obj={
				"org_code":<?php echo $org_json;?>,
				"user_id":user_id
			};
			//alert(obj)
			var path_power= "<?php echo site_url('staff/get_user_power')?>";
			$.post(path_power,obj,function(data)
				{
				 //alert(data);
					
					var value= $.parseJSON(data);
					staff_right=value.other_msg.power;					
					org_user_right(value.other_msg.power);
				});
		}
		$('#dd2').show();
	});
	$('#dd2 .toolBar2 a:eq(0)').click(function()
	{
		var obj=right_save('.staff_right ');
		 var value={
			"power_json":obj,
			"org_code":<?php echo $org_json;?>,
			"user_id":user_id
		};
		var path = "<?php echo site_url('staff/save_user_power');?>";
		//alert(111)
		var _this=$(this);
		$.post(path,value,function(data){
			//alert(data);
			$("#part1").removeClass("value_change");
			var json=$.parseJSON(data);
			if(json.code==0)
			{
				$('#dd2 .toolBar2').hide();
				$('.groupLimit').hide();
			}
			else
			{
				alert(json.prompt_text)
			}
		})
	});
	$('#dd2 .toolBar2 a:eq(1)').click(function()
	{
		$("#part1").removeClass("value_change");
		$('.infoNav li:eq(1)').trigger("click");
		$('#dd2 .toolBar2').hide();
	})
	//$('#ztree4 a').click(function()
	//{
	// /* $('#part02').find(".bread").children().remove();*/
	 //alert(111)
	// var  value=$(this).attr("title");
	 
	// //$('#departmentSel2').val(value);
	// alert($('#departmentSel2').val())
	 //alert(111)
	 /* var select_spend='';
	  select_spend='<span>成本中心</span>&nbsp;&gt;&nbsp;<span>'+value+'</span>';
	  $('#part02').find('.bread').append(select_spend);*/
	//})
	$('#centerTree li').click(function()
	{
	 /* $('#part02').find(".bread").children().remove();*/
	 //alert(111)
	var  value=$(this).find('span').text();
	$('#part02').find("span").eq(1).text(value);
	 
	 /* var select_spend='';
	  select_spend='<span>成本中心</span>&nbsp;&gt;&nbsp;<span>'+value+'</span>';
	  $('#part02').find('.bread').append(select_spend);*/
	})
/*$('.infoNav li').click(function(){
		
		var ind = $(this).index();
		//var len = $(this).parent("ul").children().length;
		
		//if(ind<len-1) {
		$(this).addClass('selected').siblings().removeClass('selected');
		$('.infoCont > dd').eq(ind).show().siblings().hide();
		//}
	});*/
	//如果是从搜索页过来的请求，返回的点击事件执行如下
	<?php 
	$org_arr = json_decode($org_json, true);
	$search_flag = empty($org_arr[0]['flag']) ? "not_search" : $org_arr[0]['flag'];
	?>
	var search_flag = '<?php echo $search_flag; ?>';
	if(search_flag == 'search'){
		$('.pageGoBack').click(function(){
			$('#part1').hide();
			$('.table').show();
			$('.tabToolBar').show();
		});
	}
	
	$('.pageGoBack').click(function()
	{
		if($('.conTabsHead').find("li:eq(0)").hasClass("selected"))
		{
			$('#part01 .part01_1').show();
			$('#part01 .tabToolBar').show();
			$('#part01 div.bread').siblings().show();
			$('#part01 div.bread').show();
			$('#part01 #test').show();
			$('#part01 table.table tbody tr').each(function()
			{
				
				if(user_id==$(this).find("input").val() || user_id==$(this).find("a.userName").attr("name"))
				{
					
					$(this).find('.userName').text($('#part01 #part1 table.infoTable tr .userName').text());
					
					$(this).find('.userCount').text($('#part01 #part1 table.infoTable tr .userCount').text());
					var telephone='';
					telephone=$('#part01 #part1 table.infoTable tr .telephone1').text()+$('#part01 #part1 table.infoTable tr .telephone2').text();
					$(this).find('.telephone').text(telephone);
					var logintime=$(this).find('.logintime').text();
					$(this).find('.logintime').text(logintime);
					//alert($('#part1 #count_handle').find("span").text())
					if($('#part01 #part1 #count_handle').find("span").text()==="关闭帐号")
					{
						//alert($(this).find('.countType').hasClass("btnOn"))
						if(!$(this).find('.countType').hasClass("btnOn"))
						{
							//alert(11)
							$(this).find('.countType').removeClass("btnOff").addClass("btnOn");
						}
					}
					else if($('#part01 #part1 #count_handle').find("span").text()==='开启帐号')
					{
						//alert(111)
						if($(this).find('.countType').hasClass("btnOn"))
						{
							$(this).find('.countType').removeClass("btnOn").addClass("btnOff");
						}
					}
				}
			})
			$("#part01 #part1").remove();
		}
		else
		{
			$('#part02 .tabToolBar').show();
			$('#part02 div.bread').siblings().show();
			$('#part02 div.bread').show();
			$('#part02 #test').show();
			$('#part02 table.table tbody tr').each(function()
			{
				if(user_id==$(this).find("input").val())
				{
					$(this).find('.userName').text($('#part02 #part1 table.infoTable tr .userName').text());
					$(this).find('.userCount').text($('#part02 #part1 table.infoTable tr .userCount').text());
					var telephone='';
					telephone=$('#part02 #part1 table.infoTable tr .telephone1').text()+$('#part02 #part1 table.infoTable tr .telephone2').text();
					$(this).find('.telephone').text(telephone);
					var logintime=$(this).find('.logintime').text();
					$(this).find('.logintime').text(logintime);
					//alert($('#part1 #count_handle').find("span").text())
					if($('#part02 #part1 #count_handle').find("span").text()==="关闭帐号")
					{
						//alert($(this).find('.countType').hasClass("btnOn"))
						if(!$(this).find('.countType').hasClass("btnOn"))
						{
							//alert(11)
							$(this).find('.countType').removeClass("btnOff").addClass("btnOn");
						}
					}
					else if($('#part02 #part1 #count_handle').find("span").text()==='开启帐号')
					{
						//alert(111)
						if($(this).find('.countType').hasClass("btnOn"))
						{
							$(this).find('.countType').removeClass("btnOn").addClass("btnOff");
						}
					}
				}
			})
			$("#part02 #part1").remove();
		}
	})
  // alert(2);
  // $('#part01').toggle();
   //var target=1;
   //setCookie('target',target,30);
 
	//点击编辑按钮
	$('.btn_infoEdit').click(function(){
		$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
		$('.infoTable .infoText').not('.dotEdit').each(function(){
			if($(this).next().find("input").attr("id")!="loginName" 
				&& $(this).next().find("input").attr("id")!="nameInput"
				&& $(this).next().find("input").attr("id")!="departmentSel2")
			{
				$(this).hide().next().removeClass('hide');
			}
			if($(this).next().hasClass("radioBox"))
			{
				$(this).next().find("label.radio").removeClass("radio_on");
				if($(this).text()=="女")
				{
					$(this).next().find("label:eq(1)").addClass("radio_on");
				}
				else
				{
					$(this).next().find("label:eq(0)").addClass("radio_on");
				}
			}
		});
	});
	$('.btn_infoCancel').click(function(){
		$(this).addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoSave').addClass('hide');
		$('.infoTable .infoText').not('.dotEdit').each(function(){
			$(this).show().next().addClass('hide');
		});
	});
	//点击保存员工信息
	$('.btn_infoSave').click(function(){
		//如果账户进行了修改
		post_value = '';
		original_account_value	= $('.userAccount').attr('value');
		update_account_value	= $('#accountId').prev().val();
		if(original_account_value != update_account_value){
			change_value();
			post_value += ',"accountId":"' + update_account_value + '"';
		}
		//如果职位进行了修改
		original_position_value = $('.position').text();
		update_position_value	= $('#position').val();
		if(original_position_value != update_position_value){
			change_value();
			post_value += ',"position":"' + update_position_value + '"';
		}
		//如果手机进行了修改
		original_mobile_value	= $('.telephone2').text();
		update_mobile_value	= $('#mobileNumber').val();
		if(original_mobile_value != update_mobile_value){
			change_value();
			post_value += ',"mobileNumber":"' + update_mobile_value + '"';
		}
			<?php foreach($seled_not_must_tag_arr as $k => $v)://循环
					$umsapifield = arr_unbound_value($v,'umsapifield',2,'');//ums字段名称
					$title = arr_unbound_value($v,'title',2,'');//名称
					$regex = arr_unbound_value($v,'regex',2,'');//正则
					$tag_value = arr_unbound_value($v,'tag_value',2,'');
				   ?>
				   umsapifield = "<?php echo $umsapifield ;?>"; 
				   _this = $("#" + umsapifield);
					original_value	= _this.parent().prev().text().replace(/^\s+|\s+$/g,"");
					update_value	= _this.val().replace(/^\s+|\s+$/g,"");//拿到值
					if(original_value != update_value){
						change_value();
						post_value += ',"' + umsapifield + '":"' + update_value + '"';
					}
					
			<?php endforeach;?> 
			
			<?php //自定义员工标签 ?>				
			<?php foreach($user_defined_tag_arr as $k => $v)://循环
					$tag_name = arr_unbound_value($v,'tag_name',2,'');//自定义标签名称
					$tag_id = arr_unbound_value($v,'id',2,'');//自定义标签id
					$regex = arr_unbound_value($v,'regex',2,'');//自定义标签正则
					?>
					//alert('<?php echo  $tag_id;?>')
					_define_this = $('#user_tag<?php echo  $tag_id;?>');
					original_define_value	= _this.parent().prev().text();
					update_define_value		= _define_this.val();//拿到值user_tag+标签id
					if(original_define_value != update_define_value){
						change_value();
						post_value += ',"' + <?php echo $umsapifield?> + '":"' + update_value + '"';
					}
			<?php endforeach;?>
// 			post_json = '{"sys_tag":[' + sys_tag_value + '],"user_tag":[' + user_tag_value + '],"org_tag":' + org_tag_value + '}';//组织
			post_json = '{"user_id":"' + user_id + '"' + post_value + '}';
			var path_change_staffInfor = '<?php echo site_url('staff/updateUserInfo'); ?>';
		   	var change_staff={
				"post_json":post_json
				};
		   $.post(path_change_staffInfor,change_staff,function(data)
		   {
			 //alert(data);
			  var json=$.parseJSON(data);
			  if(json.code==0)
			  {
				  $('#part1').removeClass("value_change");
				  $('.btn_infoSave').addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
				  $('.infoTable .infoText').not('.dotEdit').each(function(){
						$(this).show().next().addClass('hide');
						if($(this).attr("id")!="depart_span")
						{ 
						  var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : $(this).next().hasClass('radioBox')?$(this).next().find(":checked").val():$(this).next().hasClass('select-box')?$(this).next().find("input").val():"";
						 
						  $(this).text(text);
						 }
						 else
						 {
							 $(this).text(depart_input);
						 }
				  });
			  }
			  else
			  {
			  	alert(json.prompt_text);
			  }
				
			 
		   });
	});
	//点击事件获取当前选中的账户值
	$("#staff_userCount dd").click(function(e)
	{
		
		$(this).parent().find("dd.selected").removeClass("selected");
		$(this).addClass("selected");
		$(this).parent().parent().prev().text($(this).text()).css("color","#4f4f4f");
		$(this).parent().parent().prev().attr('value', $(this).attr('account_id'));
		$(this).parent().parent().hide();
		return false;		
	})
	$('.infoTable .select-box').toggle(function(e)
	{
		
		var t=$(e.target);
		if(t.hasClass('icon'))
		{
			if($(this).find('.selectOptionBox').attr('target')=='0')
			{
				 //alert(1)
				//$('#treeOption').hide();
				$('.infoTable tbody .combo ').find('.optionBox').hide();
				$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
				$(this).find('.selectOptionBox').show();
				$(this).find('.selectOptionBox').attr('target','1');
			}else
			{
				//alert(2)
				$(this).find('.selectOptionBox').hide();
				$(this).find('.selectOptionBox').attr('target','0');
			}
		}

	},function(e)
	{
		var t=$(e.target);
		if(t.hasClass('icon'))
		{
			if($(this).find('.selectOptionBox').attr('target')=='0')
			{
				//alert(3)
				//$('#treeOption').hide();
				$('.infoTable tbody .combo ').find('.optionBox').hide();
				$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
				$(this).find('.selectOptionBox').show();
				$(this).find('.selectOptionBox').attr('target','1');
			}else
			{
				//alert(4)
				$(this).find('.selectOptionBox').hide();
				$(this).find('.selectOptionBox').attr('target','0');
			}
		}

	});
	$('.infoTable tbody .combo').toggle(function()
	{

		if($(this).find('.optionBox').attr('target')=='0')
		{
			
			//$('#treeOption').hide();
			$('.infoTable tbody .combo ').find('.optionBox').hide();
			$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
			$('.infoTable .select-box ').find('.selectOptionBox').hide();
			$('.infoTable .select-box ').find('.selectOptionBox').attr('target','0');
			$(this).find('.optionBox').show();
			$(this).find('.optionBox').attr('target','1');
		}else
		{
			 
			$(this).find('.optionBox').hide();
			$(this).find('.optionBox').attr('target','0');
		}


	},function()
	{

		if($(this).find('.optionBox').attr('target')=='0')
		{
			
			$('#treeOption').hide();
			$('.infoTable tbody .combo ').find('.optionBox').hide();
			$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
			$('.infoTable .select-box ').find('.selectOptionBox').hide();
			$('.infoTable .select-box ').find('.selectOptionBox').attr('target','0');
			$(this).find('.optionBox').show();
			$(this).find('.optionBox').attr('target','1');
		}else
		{
			 
			$(this).find('.optionBox').hide();
			$(this).find('.optionBox').attr('target','0');
		}
		//$(this).siblings('.optionBox').hide();
	});
	/*$(".selectOptionBox").click(function()
	{
		//alert(212324)
	})*/
	$(document).click(function(e)
	{
		var t=$(e.target)
		if(!t.hasClass('combo') )
		{
			$('.infoTable tbody .combo ').find('.optionBox').hide();
			$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
		}
	});
	$('.optionBox dd').click(function()
	{

		$('.infoTable tbody .combo ').find('.optionBox').hide();
		$('.infoTable tbody .combo ').find('.optionBox').attr('target','0');
	});
	$('#dd1 label.radio').click(function()
	{
		$('#dd1 label.radio').removeClass("radio_on");
		$('#dd1 label.radio').removeClass("checked");
		$(this).addClass("radio_on");
		
	});
	$('#dd1 label.checkbox').click(function()
	{
	   // alert(111)
		if($(this).hasClass('checked'))
		{
			$(this).removeClass('checked');

		}
		else
		{
			$(this).addClass('checked');
		}
	})
	$('#dd2 label.checkbox').toggle(function(e)
	{
		$("#dd2 .toolBar2").show();
		var t=$(e.target);
		if(!t.hasClass('form-text'))
		{
// 			alert(333);
			if($(this).hasClass('checked'))
			{
// 				alert(111);
				$(this).removeClass('checked');
				return false;
			}
			else
			{
// 				alert(444);
				if(!$(this).hasClass('checked'))
				{
// 					alert(555);
					$(this).addClass('checked');
					return false;
				}
			}

		}
	},function(e)
	{
		 $("#dd2 .toolBar2").show();
		var t=$(e.target);
		if(!t.hasClass('form-text'))
		{
// 			alert(222);
			if(!$(this).hasClass('checked'))
			{
// 				alert(666);
				$(this).addClass('checked');
				return false;
			}
			else
			{
// 				alert(777);
				if($(this).hasClass('checked'))
				{
// 					alert(888);
					$(this).removeClass('checked');
					return false;
				}
			}
		//return false;
		}

	});
	$('#dd2 dl.radio-dl label.radio').live('click',function()
	{
		$(this).parent().find('label.radio_on').removeClass('radio_on');
		if(!$(this).hasClass('radio_on'))
		{
			$(this).addClass('radio_on');
		}
		 $("#dd2 .toolBar2").show();
	});

	$('.input_right').keyup(function(event)
	        {
	            if($(this).val()!='')
	            {
	            	$("#dd2 .toolBar2").show();
	            }
	        });

	
	//$(".account_input").addClass("disabled");
	$('#loginName').click(function()
		{
			//alert(11)
			 if($(this).hasClass('account_input'))
				{
					 $(this).addClass('disabled');
					 $(this).blur();
						//return;
				}
			}) 
	$('#loginName').mousedown(function(e)
		{
				//alert(11)
				//var e=window.event;
				//alert(e.button)
				if(e.button=="2")
				{
					//alert(111)
					$('body').attr("window.event.returnValue",false);
					$(this).blur();
					return;
				}
		})
	$(document).click(function(e)
	{
		if(!$(e.target).hasClass("disabled"))
		{
			$('body').attr("window.event.returnValue",'');
		}
	})

	});
</script>
