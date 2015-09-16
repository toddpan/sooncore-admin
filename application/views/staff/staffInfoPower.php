<div id="part1" class="information_part">
	<div class="infoTitle"> 
		<a class="pageGoBack"></a> 
		<span class="personName"><?php echo $user_info_arr['displayName'];?></span>
		<div class="fr">
		<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
		 	<a class="btn btn0ff" id="count_handle"  onClick="toggleAccount(this)">
				<span class="text" style="text-decoration: none">
					<?php if($user_info_arr['productStatus'] == 82){ ?>关闭帐号<?php }else{?>开启账号<?php }?>
				</span>
				<b class="bgR"></b>
			</a>
			<?php }?>
			&nbsp; 
			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
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
			<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
			<li>员工权限</li>
			<?php }?>
		</ul>
		<dl class="infoCont">
			<dd id="dd1">
				<div class="toolBar2">
				<?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER){?>
					 <a class="btnGray btn btn_infoEdit" >
					 	<span class="text">编辑信息</span>
						<b class="bgR"></b>
					</a> 
				<?php }?>
					<a class="btnBlue yes btn_infoSave hide" >
						<span class="text">保存</span>
						<b class="bgR"></b>
					</a> 
					<a class="btnGray btn btn_infoCancel hide" >
						<span class="text">取消</span>
						<b class="bgR"></b>
					</a>
			 </div>
			<table class="infoTable">
				<?php //必选员工标签 ?>
				<?php 
					   foreach($system_must_tag_arr as $k => $v):
					   		$field = arr_unbound_value($v,'field',2,'');
							$umsapifield = arr_unbound_value($v,'umsapifield',2,'');
							$title = arr_unbound_value($v,'title',2,'');
							$regex = arr_unbound_value($v,'regex',2,'');
							$tag_value = arr_unbound_value($v,'tag_value',2,'');
// 							echo $umsapifield;
							//echo '  ';
							?>
			<?php if ($umsapifield == 'lastName')://姓名?>
			<tr>
				<td class="tr">姓名：</td>
				<td>
					<span class="infoText userName"><?php echo $tag_value;?></span>
					<div class="inputBox w360 hide add_css">
						<b class="bgR"></b>
						<label class="label"></label>
						<input class="input" id="<?php echo $umsapifield ;?>" value="<?php echo $tag_value ;?>"/>
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
						<b class="bgR"></b>
						<label class="label"></label>
						<input class="input account_input"  id="<?php echo $umsapifield ;?>" value="<?php echo $tag_value;?>" />
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
					<span class="infoText"><?php echo $account_name;?></span>
					<div class="combo selectBox hide add_css"  style="width:204px;word-break:keep-all;"> 
						<a class="icon" ></a> 
						<span class="text" value="<?php echo $account_name;?>" style="width: 175px" readonly="readonly" onfocus="$(this).blur();"></span>
						<div class="optionBox" target='0' style="width: 206px; display: none;*position:relative;*margin-top:-27px" id="<?php echo $umsapifield ;?>">
							<dl class="optionList" style="height: 95px;" id="staff_userCount">
								<?php foreach($account_names as $item): ?>
									<dd class="option" target="0" style="" account_id="<?php echo $item['accountId'] ?>"><?php echo $item['name'];?></dd>
								<?php endforeach; ?>
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<?php 
							continue;
							endif;?>
			<?php if ($umsapifield == 'sex')://性别//0未知1男2女?>
			<tr>
				<td class="tr">性别：</td>
				<td>
					<span class="infoText"><?php if($tag_value == 1){echo "男";}else if($tag_value == 2){ echo "女";}?></span>
					<div class="radioBox hide">
						<label class="radio radio_on">
							<input name="xb " onchange='change_value(this)'type="radio" value="男" <?php if($tag_value == 1){?> checked="checked" <?php } ?> />男					
						</label>
						<label class="radio">
							<input name="xb " onchange='change_value(this)' type="radio" value="女" <?php if($tag_value == 2){?> checked="checked" <?php } ?> />
					女</label>
					</div>
				</td>
			</tr>
			<?php
							continue;
							 endif;?>
			<?php if ($umsapifield == 'organizationId')://部门?>
			<tr>
				<td class="tr">部门：</td>
				<td>
					<span class="infoText" id="depart_span" readonly="readonly"></span>
					<div class="select-box w210 hide add_css">
						<input  type="text" class="text" value="" onClick="showMenu(this);" id="departmentSel2" placeholder="请选择管理的部门"  id="<?php echo $umsapifield ;?>" />
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
					<span class="infoText"><?php echo $tag_value ;?></span>
					<div class="inputBox w360 hide add_css" > 
						<b class="bgR"></b>
						<label class="label"></label>
						<input id="<?php echo $umsapifield ;?>" class="input" value="<?php echo  $tag_value ;?>"/>
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
						<input id="<?php echo $umsapifield ;?>" class="input" value="<?php echo $country_mobile ;?>" />
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
					<span class="infoText "><?php echo $address;?></span>
					<div  class="combo selectBox hide add_css" style="width: 250px; height:28px;">
					 	<a class="icon" ></a>
					 	<span class="text selected">北京朝阳区酒仙桥北路甲10号院</span>
						<div class="optionBox" id="<?php echo $umsapifield ;?>" target='0' style="height:100%;*position:relative">
						 	<dl class="optionList">
								<dd class="option selected" onclick="change_value(this)" target="0" style=""><?php echo $address;?></dd>
							</dl>
						</div>
					</div>
				</td>
			</tr>
			<?php 
						   continue;
						   endif;?>
			<?php endforeach;?>
			<?php //可选员工标签 ?>
			<?php foreach($seled_not_must_tag_arr as $k => $v):
					   		$field = arr_unbound_value($v,'field',2,'');
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
						<input class="input" id="<?php echo $umsapifield ;?>"  value="<?php  echo $tag_value;?>" />
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
						<input class="input" id="user_tag<?php  echo $tag_id;?>" value="<?php  echo $tag_value;?>" />
					</div>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	</dd>
	<dd style="display:none;" id="dd2">
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
	<!-- 	可使用全时sooncore平台 IM 互传文档</label> -->
	<!-- 	<label class="checkbox add_link checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的联系人添加到常用联系人列表</label> -->
	<!-- 	<label class="checkbox add_success checked"> -->
	<!-- 	<input type="checkbox" checked="checked"> -->
	<!-- 	自动将联系过的讨论组添加到讨论组列表</label> -->
	<h3 class="setTitle">通话设置</h3>
		<label class="checkbox accept_call checked">
			<input type="checkbox" checked="checked" />
				允许用户设置接听策略
		</label>
		<label class="checkbox set_area checkbox2">
			<input type="checkbox" checked="checked" />
				用户可设定接听策略到海外直线电话
		</label>
		<label class="checkbox accept_cloud checked">
			<input type="checkbox" checked="checked">
				允许使用sooncore平台拨打电话
		</label>
		<label class="checkbox accept_areaPhone checkbox2 checked">
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
		<label class="checkbox allow_attendee_call checked">
			<input type="checkbox" checked="checked" />
				允许参会人自我外呼
		</label>
		<label class="checkbox record_name checked">
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
		<label class="checkbox report_num checked">
			<input type="checkbox" checked="checked" />
			参会人加入会议，告知参会者人数
		</label>
		<label class="checkbox warning_information checked">
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
		<label class="checkbox accept_95 checked">
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
		<label class="checkbox accept_inner_local">
			<input type="checkbox" checked="checked" />
			允许国内本地接入
		</label>
		<label class="checkbox accept_40">
			<input type="checkbox" checked="checked" />
			允许国内 400 接入
		</label>
		<label class="checkbox accept_80">
			<input type="checkbox" checked="checked" />
			允许国内 800 接入
		</label>
		<label class="checkbox accept_hk_local">
			<input type="checkbox" checked="checked" />
			允许香港 local 接入
		</label>
		<label class="checkbox accept_toll_free">
			<input type="checkbox" checked="checked" />
			允许国际 toll free 接入
		</label>
		<label class="checkbox accept_local_toll">
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
								alert(json.prompt_text)	
							}
				}) 
										//_this.removeClass('btnOn').addClass('btnOff');
										//hideDialog();
		})
				
					
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
					alert(json.prompt_text)	
				}
			})	
	
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
	//alert(1)
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
	//$.fn.zTree.init($("#ztree"), setting, zNodes);
	//create_node(zNodes);
	//$.fn.zTree.init($("#ztree4"), radioSetting, zNodes);
	//$.fn.zTree.init($("#selectTree"), selectSetting, zNodes);
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
		var obj=right_save();
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
	$search_flag = empty($org_arr['flag']) ? "not_search" : $org_arr['flag'];
	?>
	var search_flag = '<?php echo $search_flag; ?>';
	if(search_flag == 'search'){
		$('.pageGoBack').click(function(){
			$('#part1').hide();
			$('.table').show();
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
			if($(this).next().find("input").attr("id")!="loginName")
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
		  <?php //必选员工标签 ?>
		  var sys_tag_value;//系统及可选员工标签
		  sys_tag_value="";
		  var user_tag_value;//用户自定义员工标签
		  user_tag_value="";
		  var org_tag_value;//组织
		  org_tag_value="";
		  var ns_value ;//临时的值
		  ns_value="";
		  var ns_regex;//临时的正则
		  ns_regex="";  
		  var default_user_org_json;
		  default_user_org_json = '<?php echo $org_json;?>';//默认的当前的用户部门串
		  var count=0;
		  //{"name": "姓名","value": "开发测试","umsapifield": "lastName"}
		   <?php 
		  // print_r($system_must_tag_arr);
		   foreach($system_must_tag_arr as $k => $v):
				$field = arr_unbound_value($v,'field',2,'');//字段名称
				$umsapifield = arr_unbound_value($v,'umsapifield',2,'');//ums字段名称
				$title = arr_unbound_value($v,'title',2,'');//名称
				$regex = arr_unbound_value($v,'regex',2,'');//正则
				$tag_value = arr_unbound_value($v,'tag_value',2,'');
				//echo $umsapifield;
				//echo '  ';
				?>
				<?php if ($umsapifield == 'lastName')://姓名?> 
					var ns_value =$("#<?php echo $umsapifield ;?>").val();//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					 ns_regex ="<?php echo $regex;?>" ; //alert(ns_regex)
					if(ns_regex !=''){//有正则，才去做判断
					   ns_regex=<?php echo $regex;?>;
					   if(!ns_regex.test(ns_value))
					   {
						   $("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
						  count++;

					   }
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
				<?php 
				continue;
				endif;?> 
				<?php if ($umsapifield == 'loginName')://帐号?> 
					ns_value = $("#<?php echo $umsapifield ;?>").val();//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
					 
				<?php
				continue;
				 endif;?> 
				<?php if ($umsapifield == 'accountId')://账户?> 
				    ns_value =$("#<?php echo $umsapifield ;?>").find('dd.selected').attr("account_id");//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
					 	sys_tag_value = sys_tag_value + ',';
					}
					if(ns_value=="")
					{
						$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
						count++;
					}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
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
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'sex')://性别//0未知1男2女?> 
				   ns_value = $('.radioBox label.radio_on').find('input').val();//拿到值
					if(ns_value=="男")
					{
						ns_value=1;
					}else if(ns_value=="女")
					{
						ns_value=2;
					}else
					{
						ns_value=0;
					}
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					//ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
				
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'organizationId')://部门?>
				var zTree = $.fn.zTree.getZTreeObj("ztree4");
				var nodes = zTree.getCheckedNodes(true);
				var treeNode = nodes[0];				
				//alert(treeNode)	
				if (treeNode==null && $("#departmentSel2").val()=="") {
					
					   $("#departmentSel2").parent('div').addClass('error');
					   //alert("<?php echo $umsapifield ;?>");
							count++;
					}
					else
					{
						if(treeNode==null && $("#departmentSel2").val()!="")
						{
							org_tag_value='<?php echo $org_json;?>';
						}
						else if(treeNode!=null && $("#departmentSel2").val()!="")
						{
							//alert(111)
							var id_2 = treeNode.pId;
							var node;
							depart_input=treeNode.name;
							//alert(treeNode.name)
							org_tag_value = org_tag_value + '{"id":"' + treeNode.id + '","value": "' + treeNode.name + '"},';
							while (zTree.getNodesByParam('id', id_2, null)[0] != null) {
								node = zTree.getNodesByParam('id', id_2, null)[0];
								id_2 = node.pId;
								org_tag_value = '{"id":"' + node.id + '","value": "' + node.name + '"},' + org_tag_value;
								depart_input= node.name+'-'+depart_input;
							}
							var staff_tag_post = org_tag_value;
							var lastIndex = staff_tag_post.lastIndexOf(',');
							if (lastIndex > -1) {
							org_tag_value = staff_tag_post.substring(0, lastIndex) + staff_tag_post.substring(lastIndex + 1, staff_tag_post.length);
							} 
							ns_regex ="<?php echo $regex;?>" ;
							if(ns_regex != ''){//有正则，才去做判断
								ns_regex=<?php echo $regex;?>;
								if(!ns_regex.test(treeNode.name))
								{
									$("#departmentSel2").parent('div').addClass('error');
									count++;
								}
							}  
							//sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
						}
					}
				   // sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
					
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'position')://职位?>
					ns_value =  $("#<?php echo $umsapifield ;?>").val();;//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
				
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'mobileNumber')://手机?>
					var pre=$("#<?php echo $umsapifield ;?>").parent().siblings('.selectBox').find('dd.selected').text();
					ns_value = $("#<?php echo $umsapifield ;?>").val();;//拿到值
					ns_value=''+pre+''+ns_value+'';
					//alert(ns_value)
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
				sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield":'+
				'"<?php echo  $umsapifield ;?>"}';
				<?php 
				continue;
				endif;?>
				<?php if ($umsapifield == 'mobileNumber')://办公地址?>
					ns_value = 11;//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
				
				<?php 
				continue;
				endif;?>
			<?php endforeach;?> 
			
			<?php //可选员工标签,放系统标签里面 ?>				
			<?php foreach($seled_not_must_tag_arr as $k => $v)://循环
					$field = arr_unbound_value($v,'field',2,'');//字段名称
					$umsapifield = arr_unbound_value($v,'umsapifield',2,'');//ums字段名称
					$title = arr_unbound_value($v,'title',2,'');//名称
					$regex = arr_unbound_value($v,'regex',2,'');//正则
					$tag_value = arr_unbound_value($v,'tag_value',2,'');
				   ?>
					ns_value =  $("#<?php echo $umsapifield ;?>").val();;//拿到值
					if(sys_tag_value != ''){//有值则加一个逗号
						sys_tag_value = sys_tag_value + ',';
					}
					if(ns_value=="")
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$("#<?php echo $umsapifield ;?>").parent('div').addClass('error');
							count++;
						}
					}
					sys_tag_value = sys_tag_value + '{"name": "<?php echo  $title ;?>","value": "' + ns_value + '","umsapifield": "<?php echo  $umsapifield ;?>"}';
			
			
			<?php endforeach;?> 
			
			<?php //自定义员工标签 ?>				
			<?php foreach($user_defined_tag_arr as $k => $v)://循环
					$tag_name = arr_unbound_value($v,'tag_name',2,'');//自定义标签名称
					$tag_id = arr_unbound_value($v,'id',2,'');//自定义标签id
					$regex = arr_unbound_value($v,'regex',2,'');//自定义标签正则
					?>
					//alert('<?php echo  $tag_id;?>')
					ns_value = $('#user_tag<?php echo  $tag_id;?>').val();//拿到值user_tag+标签id
					if(user_tag_value != ''){//有值则加一个逗号
						user_tag_value = user_tag_value + ',';
					}
					if(ns_value=="")
						{
							$('#user_tag<?php echo  $tag_id;?>').parent('div').addClass('error');
							count++;
						}
					//对值进行正则判断
					ns_regex ="<?php echo $regex;?>";
					if(ns_regex != ''){//有正则，才去做判断
						//ns_regex=<?php echo $regex;?>;
						if(!ns_regex.test(ns_value))
						{
							$('#user_tag<?php echo  $tag_id;?>').parent('div').addClass('error');
							count++;
						}
					}
					//{"tag_name": "birthday","tag_id": "1", "value": 19840229}
					user_tag_value = user_tag_value + '{"tag_name": "<?php echo  $tag_name ;?>","value": "' + ns_value + '","tag_id": "<?php echo  $tag_id;?>"}';
			
			<?php endforeach;?>
			if(count!=0)
			{

				return false;
			}
			var post_json ;
			post_json = '{"sys_tag":[' + sys_tag_value + '],"user_tag":[' + user_tag_value + '],"org_tag":[' + org_tag_value + ']}';//组织
			//alert(post_json)
			//alert(sys_tag_value)
			//alert(user_tag_value)
			//alert(org_tag_value)
			var path_change_staffInfor = '<?php echo site_url('staff/save_staff'); ?>';
		   	var change_staff={
				"user_json":post_json,
				"user_id":user_id};
		   $.post(path_change_staffInfor,change_staff,function(data)
		   {
			 //alert(data);
			  var json=$.parseJSON(data);
			  if(json.code==0)
			  {
				  //alert(32)
				  $('#part1').removeClass("value_change");
				  $('.btn_infoSave').addClass('hide').siblings('.btn_infoEdit').removeClass('hide').siblings('.btn_infoCancel').addClass('hide');
				  $('.infoTable .infoText').not('.dotEdit').each(function(){
						$(this).show().next().addClass('hide');
						if($(this).next().find("label:eq(1)").hasClass("radio_on"))
						{
							$(this).text("女");
						}
						else
						{
							$(this).text("男");
						}
						if($(this).attr("id")!="depart_span")
						{ 
						  var text = $(this).next().hasClass('inputBox') ? $(this).next().find('.input').val() : $(this).next().hasClass('selectBox') ? $(this).next().find('.text').text() : $(this).next().hasClass('radioBox')?$(this).next().find(":checked").val():$(this).next().hasClass('select-box')?$(this).next().find("input").val():"";
						 
						  $(this).text(text);
						 }
						 else
						 {
							//alert(depart_input)
							 $(this).text(depart_input);
						 }
				  });
			  }
			  else
			  {
			  	alert(json.prompt_text)	
			  }
				
			 
		   });
	});
	/*$('.selectBox').combo({
		cont:'>.text',
		listCont:'>.optionBox',
		list:'>.optionList',
		listItem:' .option'
	});*/
	$("#staff_userCount dd").click(function(e)
	{
		
		$(this).parent().find("dd.selected").removeClass("selected");
		$(this).addClass("selected");
		$(this).parent().parent().prev().text($(this).text()).css("color","#4f4f4f");
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
	$(".account_input").addClass("disabled");
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
