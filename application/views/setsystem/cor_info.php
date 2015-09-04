<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
<link rel="stylesheet" href="public/css/jquery.Jcrop.css">
</head>
<body>
<!--系统设置_企业信息设置.html-->
<div class="contHead"> <span class="title01">系统设置</span>
	<ul class="nav02">
		<li class="selected"><a >企业信息设置</a></li>
		<li class="last"><a >站点应用设置</a></li>
		<li class="last"><a >帐号与认证设置</a></li>
		<li class="last"><a >通知设置</a></li>
	</ul>
</div>
<div class="company_infor">
	<dl class="bisinessLogo">
		<dt class="title">企业LOGO</dt>
		<dd class="img"><img id="logo_finished" src="<?php echo $data['url'] ?>" /></dd>
		<dd class="tc"><a onclick="showDialog('systemset/setLogoDialog');" class="contLink">修改</a></dd>
	</dl>
	<dl class="bisinessInfo"  style="position: relative">
		<!--<dt class="toolBar2" style="position: absolute; top: -15px; right: 10px;"> <a class="btnGray btn btn_infoEdit" ><span class="text">编辑</span><b class="bgR"></b></a> <a class="btnBlue yes btn_infoSave hide" ><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn btn_infoCancel hide" ><span class="text">取消</span><b class="bgR"></b></a> </dt>-->
		<dd class="info">
			<table class="infoTable">
			<?php if(!is_empty($data['name'])){?>
				<tr>
					<td class="tr">公司名称</td>
					<td><span class="infoText"><?php echo $data['name'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['name'];?>" />
						</div></td>
				</tr>
				<?php } ?>
				<tr>
					<td class="tr">公司简称</td><!-- Jackson说：不超过六个字；永川说：当是中文时不超过四个汉字，当是英文是不超过5个单词。 -->
					<td><span class="infoText" style="margin-right:10px;"><?php echo $data['corName']; ?></span>
					<a class="contLink" onclick="update_corName();"><span class="text">编辑</span></a>
						<div class="inputBox w360 hide" style="float:left;">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['corName']; ?>" id="corName" />
						</div>
					<a class="contLink hide" onclick="submit_update_corName();" style="float:left;margin-left:10px;margin-right:10px;">保存</a>
					<a class="contLink hide" onclick="cancel_update_corName();" style="float:left;margin-left:10px;">取消</a>
					</td>
				</tr>
				<?php if(!is_empty($data['address'])){?>
				<tr>
					<td class="tr">公司地址</td>
					<td><span class="infoText"><?php echo $data['address'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['address'];?>" />
						</div></td>
				</tr>
				<?php }?>
				<?php if(!is_empty($data['country'])){?>
				<tr>
					<td class="tr">国家/城市</td>
					<td><span class="infoText"><?php echo $data['country'];?></span>
						<div class="combo selectBox w110 hide"> <a class="icon" ></a> <span class="text selected">中国</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">中国</dd>
									<dd class="option" target="1">美国</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						<span class="infoText"><?php echo $data['city'];?></span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['city'];?>" />
						</div></td>
				</tr>
				<?php } ?>
				<?php if(!is_empty($data['phone'])){?>
				<tr>
					<td class="tr">公司电话</td>
					<td><span class="infoText "><?php echo $data['phone'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59933636</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59933636" />
						</div--></td>
				</tr>
				<?php }?>
				<?php if(!is_empty($data['fax'])){?>
				<tr>
					<td class="tr">传真</td>
					<td><span class="infoText"><?php echo $data['fax'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59933555</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59933555" />
						</div--></td>
				</tr>
				<?php }?>
				<?php if(!is_empty($data['website'])){?>
				<tr>
					<td class="tr">公司网站</td>
					<td><span class="infoText"><?php echo $data['website'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['website'];?>" />
						</div></td>
				</tr>
				<?php } ?>
				<?php if(!is_empty($data['m_name'])){?>
				<tr>
					<td class="tr">主要联系人姓名</td>
					<td><span class="infoText dotEdit"><?php echo $data['m_name'];?></span> </td>
				</tr>
				<?php } ?>
				<?php if(!is_empty($data['m_tel'])){?>
				<tr>
					<td class="tr">主要联系人电话</td>
					<td><span class="infoText"><?php echo $data['m_tel'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59937423</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59937423" />
						</div--></td>
				</tr>
				<?php } ?>
				<?php if(!is_empty($data['m_email'])){?>
				<tr>
					<td class="tr">主要联系人邮箱</td>
					<td><span class="infoText"><?php echo $data['m_email'];?></span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="<?php echo $data['m_email'];?>" />
						</div></td>
				</tr>
				<?php }?>
				<?php if(!is_empty($data['f_name'])){?>
				<tr>
					<td class="tr">财务联系人姓名</td>
					<td><span class="infoText dotEdit"><?php echo $data['f_name'];?></span> </td>
				</tr>
				<?php } ?>
				<?php if(!is_empty($data['f_tel'])){?>
				<tr>
					<td class="tr">财务联系人电话</td>
					<td><span class="infoText"><?php echo $data['f_tel'];?></span>
						<!--div class="combo selectBox w60 hide"> <a class="icon" ></a> <span class="text selected">+86</span>
							<div class="optionBox">
								<dl class="optionList">
									<dd class="option selected" target="0">+86</dd>
									<dd class="option" target="1">+85</dd>
								</dl>
								<input type="hidden" class="val" value="0" />
							</div>
						</div>
						- <span class="infoText">010</span>
						<div class="inputBox w60 hide">
							<label class="label"></label>
							<input class="input" value="010" />
						</div>
						- <span class="infoText">59937424</span>
						<div class="inputBox w110 hide">
							<label class="label"></label>
							<input class="input" value="59937424" />
						</div--></td>
				</tr>
				<?php } ?>
				<!--<tr>
					<td class="tr">接收提醒邮箱</td>
					<td><span class="infoText tip_email">123@quanshi.com</span>
						<div class="inputBox w360 hide">
							<label class="label"></label>
							<input class="input" value="xiaolei.han@quanshi.com" />
						</div></td>
				</tr>
				<tr>
					<td class="tr">系统通知</td>
					<td><span class="infoText system_inform">开</span>
						<div class="radioBox hide">
							<label class="radio radio_on">
							<input type="radio" checked="checked" name="sysNotice"/>
							开
							</label>
							<label class="radio">
							<input type="radio" name="sysNotice"/>
							关
							</label>
							<!--<input name="sysNotice" type="radio" class="radio" id="sysNotice_01" checked="checked" />
							<label for="sysNotice_01">开</label>
							<input name="sysNotice" type="radio" class="radio" id="sysNotice_02" />
							<label for="sysNotice_02">关</label>
						</div></td>
				</tr>-->
			</table>
		</dd>
	</dl>
</div>
<div class="groupLimit2" style="display: none">
	<div class="toolBar2" style="position: absolute; top: -15px; right: 10px; display: none"> <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a> </div>
	<!-- end tabToolBar -->
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
	<div class="toolBar2" style="display: none; clear: both"> <a class="btnBlue yes"  onclick="saveSuccess()"><span class="text">保存</span><b class="bgR"></b></a> <a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a> </div>
</div>
<div class="styleset" style="display: none;">
	<div class="toolBar2" style=""> 
		<a class="btnBlue yes"  onclick=""><span class="text">保存</span><b class="bgR"></b></a> 
		<!-- <a class="btnGray btn"  onclick=""><span class="text">取消</span><b class="bgR"></b></a>  -->
<!-- 	<a class="btnGray btn"  onclick="$('.toolBar2').hide();"><span class="text">取消</span><b class="bgR"></b></a>  -->
	</div>
	<h3 class="setTitle">认证方式</h3>
	<dl class="radio-dl">
		<dd class="auth" id="auth">
			<label  class="radio">
				<input type="radio" name="ldap" value="0" id="auth0" />
				普通认证
			 </label>
			<label  class="radio">
				<input type="radio" name="ldap" value="1"  id="auth1" />
				LDAP认证
			</label>
<!-- 			<label  class="radio"> -->
<!-- 				<input type="radio" name="ldap" value="13" id="auth2" /> -->
<!-- 				OAuth 2认证 -->
<!-- 			</label> -->
<!-- 			<label  class="radio"> -->
<!-- 				<input type="radio" name="ldap" value="13" id="auth3" /> -->
<!-- 				SAML 2认证 -->
<!-- 			</label> -->
		</dd>
	</dl>
	<div class="ldapSetBox1" target="1" id="ldapset" style="display:none;">
		<span class="error1" style="margin-left:160px;color:#ec6764;display:none"></span>
		<table class="infoTable" style="margin:0 30px;">
		     <tbody>
			 	<tr>
					<td width="160"></td>
					<td height="10" class=""></td>
			 	</tr>
			 	<tr>
					<td width="160">服务器类型：</td>
					<td>
						<div class="combo selectBox w318" id="servertype1">
							<a class="icon"></a>
							<span title="" class="text">请选择服务类型</span>
							<div class="optionBox">
							<dl class="optionList" style="height: 130px;">
							<dd class="option" target="0">请选择服务类型</dd>
							<dd class="option" target="1">Microsoft Active Directory</dd>
							<dd class="option" target="2">OPENDIRECTORY</dd>             									                     
							</dl>
						</div>
						</div>
					</td>
				</tr>
				<tr> 
					<td width="160">连接方式：</td>
					<td>
						<div class="inputBox w318" id="protocol1">
							<label class="label">LDAP</label>
							<input title="" class="input">
						</div>
					</td>
				</tr>
				<tr> 
					<td width="160">LDAP服务器地址：</td>
					<td>
						<div id="hostname1" class="inputBox w318">
							<label class="label"></label>                  
							<input type="" value="" class="input">
						</div>
					</td>
				</tr>
				<tr> 
					<td width="160">LDAP服务器端口：</td>
					<td>
						<div id="port1" class="inputBox w318">
							<label class="label"></label>            
							<input type="" value="" class="input">
						</div>
					</td>
				</tr>
				<tr> 
				<td width="160">LDAP服务器用户名：</td>
				<td>
					<div id="admindn1" class="inputBox w318">
						<label class="label"></label>   
					   <input type="" value="" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP服务器密码：</td>
				<td>
					<div id="password1" class="inputBox w318">
						<label class="label"></label>   
						<input type="password" value="" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">Base DN：</td>
				<td>
					<div id="basedn1" class="inputBox w318">
						<label class="label"></label>             
						<input type="" value="" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP用户唯一标识：</td>
				<td>
					<div id="idAttribute1" class="inputBox w318">
						<label class="label"></label>                  
						<input type="" value="" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">LDAP用户mail属性：</td>
				<td>
					<div id="emailAttribute" class="inputBox w318">
						<label class="label"></label>                  
						<input type="" value="" class="input">
					</div>
				</td>
			</tr>
			<tr> 
				<td width="160">认证配置名称：</td>
				<td>
					<div id="confName1" class="inputBox w318">
						<label class="label"></label>             
						<input type="" value="" class="input">
					</div>
				</td>
			</tr>
			 </tbody>
		</table>
	</div>
	<h3 class="setTitle">帐号导入方式</h3>
	<dl class="radio-dl">
		<dd class="importmode" id="importmode">
			<label  class="radio">
				<input type="radio" name="import" value="excel" id="excel_sync" />
				Excel导入
			 </label>
			 <label  class="radio">
				<input type="radio" name="import" value="xml" id="xml_sync" />
				XML同步
			</label>
			<label  class="radio">
				<input type="radio" name="import" value="ldap" id="ldap_sync" />
				LDAP同步
			</label>
		</dd>
	</dl>
	 <!-- add by ge.xie 4 xmlImport setup. -->
	 <div class ="xmlImportSetBox" id="xml_sync_set" style="display:none;">
	 	<table class="infoTable" style="margin:0 30px;">
	 		<tbody>
	 			<tr>
					<td width="160"></td>
					<td height="10" class=""></td>
			 	</tr>
			 <!-- 关闭路径设置功能。仅提供上传途径添加xml和xsl。
			 	<tr>
					<td width="160">XML文件路径：</td>
					<td>
						<div class="inputBox w318" id="xmlurl">
							<label class="label" />
							<input title="" class="input" value="" />
						</div>
					</td>					
				</tr>
				<tr>
					<td width="160">XSLT文件路径：</td>
					<td>
						<div class="inputBox w318" id="formaturl">
							<label class="label" />
							<input title="" class="input" value="" />
						</div>
					</td>					
				</tr> 
			-->
				<tr>
					<td width="200px">上传公司帐号XML文件：</td>
					<td>
					<form id="xml_upload" action="">
						<div style="float:left;position:relative;width: 500px;">
							<a class="btnBlue" style="float:left;"><span class="text">选择文件</span><b class="bgR"></b></a>
							<span class="error1" id="upfile" style="margin-left:20px;color:#ec6764;">仅允许上传扩展名为 xml 和 xsl 的文件.</span>
							<input type="file" name="userfile" id="xml_file" onchange="uploadxml();" style="position:absolute;left:0px;top:0px;width:72px;height:28px;filter:alpha(opacity=0);-moz-opacity:0;opacity:0;" />
						</div>
					</form>
					</td>
					<td></td>
				</tr>
				<tr>
					<td width="160">无效用户默认处理方式：</td>
					<td>						
						<dl class="radio-dl">
							<dd class="deleablemode" id="deleablemode">
								<label class="radio">
									<input type="radio" name="deleable" value="delete" id="delete" />
									删除
								</label>
								<label class="radio">
									<input type="radio" name="deleable" value="disable" id="disable" />
									关闭
								</label>
							</dd>
						</dl>
					</td>					
				</tr>
	 		</tbody>
	 	</table>
	 </div>
	 
</div>

<div class="js-notice-set" style="display: none;">
	<div class="toolBar2" style=""> 
		<a class="btnBlue yes"  onclick=""><span class="text">保存</span><b class="bgR"></b></a> 
	</div>
	<h3 class="setTitle">开通帐号通知设置</h3>
	<label class="checkbox checked" style="width: 150px;" id="accountNotifyEmail">
		<input type="checkbox" checked="checked" />发送邮件
	</label>
	<label class="checkbox checked" style="width: 260px;" id="accountNotifySMS">
		<input type="checkbox" checked="checked" />发送短信
	</label>
	<br />
 	<label class="label" style="font-size: 14px;display: block; margin-top: 15px;">
 		开通帐号时，如果已有密码，提示信息：
		<input id="passwordNotifyWord" name="passwordNotifyWord" class="form-text infoInput"  style="width: 200px;" value="" placeholder="" type="text"/>
		<span id="err_pwd_msg" style="display: none;color: red;">您输入的提示信息太长啦！</span>
	</label>
	<label class="label" style="font-size: 14px;display: block;margin-top: 15px;">
 		开通帐号时的默认密码：
		<input id="accountDefaultPassword" name="accountDefaultPassword" class="form-text infoInput"  style="width: 200px;" value="" placeholder="" type="text"/>
		<span id="err_acc_msg" style="display: none;color: red;">请输入8-20位密码</span>
	</label>
	<h3 class="setTitle" style="padding-top: 15px;padding-bottom: 10px;">安排会议通知设置</h3>
	<label class="checkbox  checked" style="width: 150px;" id="meetingNotifyEmail">
		<input type="checkbox" checked="checked" />发送邮件
	</label>
	<h3 class="setTitle" style="padding-top: 20px;">密码设置</h3>
	<label class="checkbox  checked" style="width: 300px;" id="siteAllowChangePassword">
		<input type="checkbox" checked="checked" />允许在客户端进行重置密码和忘记密码操作
	</label>
</div>


<script type="text/javascript" src="public/js/self_common.js"></script>
<script type="text/javascript" src="public/js/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="public/js/ajaxfileupload.js"></script>
<script type="text/javascript">

// 初始化页面时的认证方式
var default_isldap;

// 初始化页面时的密码提示信息
var default_passwordNotifyWord;

//初始化页面时的开通账号时的默认密码
var default_accountDefaultPassword;


$('.system').removeClass("false");
$(function(){
	$('.infoTable .selectBox').combo({
		cont:'>.text',
		listCont:'>.optionBox',
		list:'>.optionList',
		listItem:' .option'
	});
	
	$('.infoTable .radioBox label.radio').click(function(){
		$('.infoTable .radioBox label.radio_on').removeClass("radio_on");
		$(this).addClass("radio_on");	
	});
	
	$('.btn_infoEdit').click(function(){
		$(this).addClass('hide').siblings('.btn_infoSave, .btn_infoCancel').removeClass('hide');
		$('.infoTable .infoText').not('.dotEdit').each(function(){
		if($(this).hasClass("tip_email") || $(this).hasClass("system_inform")){
			$(this).hide().next().removeClass('hide');
		}
		});
	});
	
	$('.btn_infoCancel').click(function(){
		$('.infoTable .tip_email').show().next().addClass('hide');
		$('.infoTable .system_inform').show().next().addClass('hide');
		$(this).addClass('hide').siblings('.btn_infoSave').addClass('hide').siblings('.btn_infoEdit').removeClass('hide');
	});
	
	$('.btn_infoSave').click(function(){
		var email=$('.tip_email').next().find('input').val();
		var system_inform=$('.system_inform').next().find("label.radio_on").text();
		var sys;
		if(system_inform == "开"){
			sys=1;
		}else{
			sys=0;
		}
		$(this).addClass('hide').siblings('.btn_infoCancel').addClass('hide').siblings('.btn_infoEdit').removeClass('hide');
		$('.infoTable .tip_email').text(email);
		$('.infoTable .tip_email').show().next().addClass('hide');
		$('.infoTable .system_inform').text(system_inform);
		$('.infoTable .system_inform').show().next().addClass('hide');
	});

	$('.groupLimit2 label.checkbox').toggle(function(e){
		var t=$(e.target);
		if(!t.hasClass('form-text')){
			if($(this).hasClass('checked')){
				$(this).removeClass('checked');
			}else{
				if(!$(this).hasClass('checked')){
					$(this).addClass('checked');
					}
				}
			}
		var count=0;
		save_show(va_post,count);
		}, function(e){
            var t=$(e.target)
            if(!t.hasClass('form-text')){
                if(!$(this).hasClass('checked')){
                    $(this).addClass('checked');
                }else{
                    if($(this).hasClass('checked')){
                        $(this).removeClass('checked');
                    }
                }
            }
            var count=0;
            save_show(va_post,count);
  	});
    
	$('.groupLimit2 dl.radio-dl label.radio').live('click',function(){
		$(this).parent().find('label.radio_on').removeClass('radio_on');
		if(!$(this).hasClass('radio_on')){
			$(this).addClass('radio_on');
		}
		var count=0;
		save_show(va_post,count);
	});

	$('.input_right').keyup(function(event){
		if($(this).val()!=''){
			$(this).parent().addClass('checked');
			$(this).prev().attr('checked','checked');
			var count=0;
			save_show(va_post,count);
			event.stopPropagation();
			} 
	}).blur(function(event){
		if($(this).val()!=''){
			$(this).parent().addClass('checked');
			$(this).prev().attr('checked','checked');
			var count=0;
			save_show(va_post,count);
			event.stopPropagation();
			}
		})
	});

	// 保存站点权限
    function saveSuccess() {
        var obj=right_save('.groupLimit2 ');
		var value={
			"power_json":obj
		};
		
        var path = "setsystem/save_sys_power";
        $.post(path,value,function(data){
            var json=$.parseJSON(data);
            if(json.code==0){
                $(".rightCont").append('<div class="successMsg">保存成功</div>');
                setTimeout(function(){
                    $(".successMsg").remove();
                    $(".toolBar2").hide();
                },2000)
            }else{
				alert(json.prompt_text)	
			}
        });
    }

    // 修改公司简称
    function update_corName(){
        $('.infoTable tr:eq(1) .infoText').addClass('hide');
        $('.infoTable tr:eq(1) .contLink').addClass('hide');
        $('.infoTable tr:eq(1) .inputBox').removeClass('hide');
        $('.infoTable tr:eq(1) a:eq(1)').removeClass('hide');
        $('.infoTable tr:eq(1) a:eq(2)').removeClass('hide');
   }
    
    // 取消修改公司简称
    function cancel_update_corName(){
        $('.infoTable tr:eq(1) .inputBox').addClass('hide');
        $('.infoTable tr:eq(1) .contLink').removeClass('hide');
        $('.infoTable tr:eq(1) .infoText').removeClass('hide');
        $('.infoTable tr:eq(1) a:eq(1)').addClass('hide');
        $('.infoTable tr:eq(1) a:eq(2)').addClass('hide');
    }
    
    // 向PHP提交修改公司简称的数据并返回处理结果
    function submit_update_corName(){
        var corName = $('#corName').val(); // 公司新的简称
        var old_corName = $('.infoTable tr:eq(1) .infoText').text();// 公司旧的简称

        // 去掉空格
        var reg=/\s/g;
        corName=corName.replace(reg,'');

        // 判断公司新的简称的长度：Jackson说：不超过六个字；永川说：当是中文时不超过四个汉字，当是英文是不超过5个单词。
        if(corName.length <= 0 ){
            alert('请输入公司简称');
            $('#corName').val(corName);
            return false;
        }else{
            if(corName.length > 6){
            	alert('公司简称最多不超过6个字');
            	return false;
            }
        }

        if(corName == old_corName){
        	cancel_update_corName();
        	return false;
        }

        var path = 'systemset/update_cor_name'; // 向PHP抛数据的路径
        var obj = {
				"corName":corName  // 向PHP抛的数据
            };
        $.post(path,obj,function(data)
        {
			var json=$.parseJSON(data);
            if(json.code==0)
            {
               cancel_update_corName();
               $('.infoTable tr:eq(1) .infoText').text(corName)
               $('#carName b').text(corName);
            }
    		else
    		{
    			alert(json.msg);
    		}
        });
    }

    // 认证方式选择
    $('#auth0,#auth1').click(function(e){

    	// 普通认证
    	if($(this).val() == 0){ 
    		$('#auth1').parent().removeClass('radio_on');
    		$('#auth0').attr("checked", true);
    		$('#auth0').parent().addClass('radio_on');
    		$('#ldapset').hide();
    	}

    	// LDAP认证
    	if($(this).val() == 1){ 
    		$('#auth0').parent().removeClass('radio_on');
    		$('#auth1').attr("checked", true);
    		$('#auth1').parent().addClass('radio_on');
    		$('#ldapset').show();
    	}

    	$('#ldapset #servertype1 span').show();

    	// 如果认证方式发生变化
//     	if($(this).val() == default_isldap){
//         	$('.styleset .toolBar2').hide();
//     	}else{
//     		$('.styleset .toolBar2').show();
//     	}
    });
    
 	// 账号保存方式选择 
	$('#excel_sync').click(function(e) {
		$('#xml_sync').parent().removeClass('radio_on');
		$('#ldap_sync').parent().removeClass('radio_on');
		$('#disable').parent().removeClass('radio_on');
		$('#delete').parent().removeClass('radio_on');
   		$('#excel_sync').attr("checked", true);
    	$('#excel_sync').parent().addClass('radio_on');
    	$('#excel_sync_set').show();
    	$('#xml_sync_set').hide();
    	$('#ldap_sync_set').hide();
	});
	$('#xml_sync').click(function(e) {
    	$('#excel_sync').parent().removeClass('radio_on');
   		$('#ldap_sync').parent().removeClass('radio_on');
   		$('#xml_sync').attr("checked", true);
   		$('#xml_sync').parent().addClass('radio_on');
   		// deleable 的默认值
   		if ($('#deleablemode').find("label.radio_on input").val() == null) {
   			$('#delete').parent().removeClass('radio_on');
   			$('#disable').parent().addClass('radio_on');
       	}
    		
   		$('#xml_sync_set').show();
   		$('#excel_sync_set').hide();
   		$('#ldap_sync_set').hide();
   	});
	$('#ldap_sync').click(function(e) {	 
    	$('#excel_sync').parent().removeClass('radio_on');
   		$('#xml_sync').parent().removeClass('radio_on');
    	$('#disable').parent().removeClass('radio_on');
		$('#delete').parent().removeClass('radio_on');
   		$('#ldap_sync').attr("checked", true);
   		$('#ldap_sync').parent().addClass('radio_on');
   		$('#ldap_sync_set').show();
   		$('#excel_sync_set').hide();
      	$('#xml_sync_set').hide();
	});
	
	$('#delete').click(function(e) {
		$('#disable').parent().removeClass('radio_on');
    	$('#delete').attr("checked", true);
    	$('#delete').parent().addClass('radio_on');
	});
	$('#disable').click(function(e) {
		$('#delete').parent().removeClass('radio_on');
		$('#disable').attr("checked", true);
		$('#disable').parent().addClass('radio_on');
	});

	// 上传xml导入文件
// 	$('#xml_file').change(function(e) {
	function uploadxml() {
		$('#upfile').text($('#xml_file').attr('value'));
		var type = (/\.[a-zA-Z]+$/).exec($('#xml_file').attr('value')).toString().substr(1);
// 		alert(/\.[a-zA-Z]+$/.exec($('#xml_file').attr('value')).toString().substr(1));
		$.ajaxFileUpload({
 			url            : 'interface/xmlimport/uploaduc',
			type           : 'post',
			secureuri      : false,
			fileElementId  : 'xml_file',
			dataType       : 'json',
			data           : { type: type },
			success        : function(data, status) {
					if (data.code.result == true) {
						$('#upfile').text('文件上传成功.');
					} else {
						$('#upfile').text('文件上传失败.');
					}
				},
			error          : function (data, status, e) {
					$('#upfile').text('抱歉，网络出错啦！');
				}
		});
	};
	
    // 保存认证方式 by xue.bai
    // 保存账号导入方式 by ge.xie
    $('.styleset .toolBar2 .yes').click(function(){
        // 隐藏提示信息
    	$('.styleset .ldapSetBox1 span').hide();
//     	$('.styleset .xmlImportSetBox span').hide();

    	// 获得认证方式
        var isldap = $('#auth').find(".radio_on input").val();

        // 获得账号导入方式

        var importmode = $('#importmode').find(".radio_on input").val();

     	// // 需要抛给PHP的数据
      //   var obj = {
      //           "is_ldap":isldap
      //   }; 

        // 如果是LDAP认证
        if(isldap == 1){
        	$('#ldapset #servertype1 span').show();
            var servertype 		= $('.styleset #servertype1 span').text(); 		// 服务器类型
            var protocol 		= $('.styleset #protocol1 input').val();		// 连接方式
            var hostname 		= $('.styleset #hostname1 input').val(); 		// 服务器地址
            var port 			= $('.styleset #port1 input').val(); 			// 端口号
            var admindn 		= $('.styleset #admindn1 input').val(); 		// 服务器用户名
            var password 		= $('.styleset #password1 input').val(); 		// 密码
            var basedn 			= $('.styleset #basedn1 input').val(); 			// Base DN
            var idAttribute 	= $('.styleset #idAttribute1 input').val();		// LDAP用户唯一标识
            var emailAttribute 	= $('.styleset #emailAttribute input').val(); 	// 邮箱属性
            var confName 		= $('.styleset #confName1 input').val(); 		// LDAP名字

            if(servertype == '请选择服务类型'){
            	$('.styleset .ldapSetBox1 .error1').text('请选择服务类型').show();
                return false;
            }
            if(protocol == '' || protocol.toUpperCase() == 'LDAP'){
            	protocol = 'LDAP';
            }else{
            	$('.styleset .ldapSetBox1 .error1').text('服务器的连接方式不能为空或者不正确格式，目前只支持“LDAP”连接方式,请正确填写').show();
                return false;
            }

            if(!valitateLdpAddress(hostname)){
                $('.styleset .ldapSetBox1 .error1').text('请输入正确的LDAP服务器地址').show();
                return false;
            }
            if(!valitateLdpPort(port)){
                $('.styleset .ldapSetBox1 .error1').text('请输入正确的LDAP服务器端口').show();
                return false;
            }
            if(!valitateLdpUserName(admindn)){
                $('.styleset .ldapSetBox1 .error1').text('请输入正确的LDAP服务器用户名').show();
                return false;
            }
            if(password == ''){
                $('.styleset .ldapSetBox1 .error1').text('请输入LDAP服务器密码').show();
                return false;
            }
            if(basedn == ''){
                $('.styleset .ldapSetBox1 .error1').text('请输入Base DN').show();
                return false;
            }
            if(idAttribute == ''){
                $('.styleset .ldapSetBox1 .error1').text('请输入LDAP用户唯一标识').show();
                return false;
            }
            if(emailAttribute.length > 50){
                $('.styleset .ldapSetBox1 .error1').text('LDAP用户mail属性太长了哦').show();
                return false;
            }
            if(confName == ''){
                $('.styleset .ldapSetBox1 .error1').text('请输入认证配置名称').show();
                return false;
            }

            var server_info ={
                    "servertype": servertype,
                    "protocol": protocol,
                    "hostname": hostname,
                    "port": port,
                    "admindn": admindn,
                    "password": password,
                    "basedn": basedn,
                    "idAttribute":idAttribute,
                    "confName": confName,
                    "emailAttribute":emailAttribute
            };
        }

		// xml导入。当前需求改为仅通过上传方式，因此以下关于import表单内容被注释，添加新的内容
		if (importmode == 'xml') {
// 			var xmlurl 	= $('.xmlImportSetBox #xmlurl input').val(); // xml地址
// 			var formaturl 	= $('.xmlImportSetBox #formaturl input').val(); // xslt地址
			var deleable = $('#deleablemode').find("label.radio_on input").val(); // delete or disable
			if (typeof(deleable) == "undefined") {
				deleable = 'delete';
			}				
// 			if (xmlurl == '') {
// 				$('.styleset .xmlImportSetBox span').text('对不起,XML地址不能为空.').show();
//                 return false;
// 			}
// 			if (formaturl == '') {
// 				$('.styleset .xmlImportSetBox span').text('对不起,XSLT地址不能为空.').show();
//                 return false;
// 			}
			var import_info = {
				"DATA_IMPORT_TYPE": importmode,
// 				"xmlurl": xmlurl,
// 				"formaturl": formaturl,
				"handle_invalidation_user_type": deleable
			};
		}

		
		
		if (importmode == 'ldap') {
			var import_info = {
				"DATA_IMPORT_TYPE": importmode
			};
		}
		if (importmode == 'excel') {	
			var import_info = {
				"DATA_IMPORT_TYPE": importmode
			};
		}
		
		server_info = typeof(server_info) == "undefined" ? null : server_info;
		import_info = typeof(import_info) == "undefined" ? null : import_info;

		// post的数据
		obj = {
            "is_ldap":isldap,
            "server_info":server_info,
            "import_info":import_info
        };

        var path = 'systemset/save_ldap';
		$.post(path, obj, function(data)
		{	
			if(data.code == 0){
				$('.styleset .ldapSetBox1 span').hide();
				$(".rightCont").append('<div class="successMsg">保存成功</div>');
                setTimeout(function() {
                    $(".successMsg").remove();
                },
                2000);
				default_isldap = isldap;
				location.reload(true);
			}
			else{
				$('.styleset .ldapSetBox1 span').hide();
				$(".rightCont").append('<div class="successMsg">保存失败</div>');
                setTimeout(function() {
                    $(".successMsg").remove();
                },
                2000);
			}
		},'json');
    });

    $('#accountNotifyEmail').click(function(){
        if($(this).hasClass('checked')){
        	$(this).removeClass('checked');
        }else{
        	$(this).addClass('checked');
        }
    });

    $('#accountNotifySMS').click(function(){
        if($(this).hasClass('checked')){
        	$(this).removeClass('checked');
        }else{
        	$(this).addClass('checked');
        }
    });

    $('#meetingNotifyEmail').click(function(){
        if($(this).hasClass('checked')){
        	$(this).removeClass('checked');
        }else{
        	$(this).addClass('checked');
        }
    });

    $('#passwordNotifyWord').focus(function(){
        if($(this).val() == default_passwordNotifyWord){
        	$(this).val('');
        }
    });
    
    $('#passwordNotifyWord').blur(function(){
        if($(this).val() == ''){
        	$(this).val(default_passwordNotifyWord); 
        }
    });

//     $('#accountDefaultPassword').focus(function(){
//         if($(this).val() == default_accountDefaultPassword){
//         	$(this).val('');
//         }
//     });
    
//     $('#accountDefaultPassword').blur(function(){
//         if($(this).val() == ''){
//         	$(this).val(default_accountDefaultPassword); 
//         }
//     });

    $('#siteAllowChangePassword').click(function(){
        if($(this).hasClass('checked')){
        	$(this).removeClass('checked');
        }else{
        	$(this).addClass('checked');
        }
    });

    // 保存通知设置
    $('.js-notice-set .toolBar2 .yes').click(function(){

        $('#err_pwd_msg').hide();
        $('#err_acc_msg').hide();
        
        var accountNotifyEmail 		= ($('#accountNotifyEmail').hasClass('checked')) ? 1 : 0;
        var accountNotifySMS 		= ($('#accountNotifySMS').hasClass('checked')) ? 1 : 0;
        var meetingNotifyEmail 		= ($('#meetingNotifyEmail').hasClass('checked')) ? 1 : 0;
        var passwordNotifyWord 		= $('#passwordNotifyWord').val();
        var accountDefaultPassword 	= $('#accountDefaultPassword').val();
        var siteAllowChangePassword = ($('#siteAllowChangePassword').hasClass('checked')) ? 1 : 0;

        if(passwordNotifyWord.length > 200){
        	$('#err_pwd_msg').show();
        	return false;
        }

        if((accountDefaultPassword != '') && (accountDefaultPassword.length > 20 || accountDefaultPassword.length < 8)){
        	$('#err_acc_msg').show();
        	return false;
        }

		obj = {
            "accountNotifyEmail":accountNotifyEmail,
            "accountNotifySMS":accountNotifySMS,
            "meetingNotifyEmail":meetingNotifyEmail,
            "passwordNotifyWord":passwordNotifyWord,
            "accountDefaultPassword":accountDefaultPassword,
            "siteAllowChangePassword":siteAllowChangePassword
        };

        var path = 'systemset/save_notice_set';
		$.post(path, obj, function(data)
		{	
			if(data.code == 0){
				default_passwordNotifyWord = passwordNotifyWord;
				default_accountDefaultPassword = accountDefaultPassword;
				$(".rightCont").append('<div class="successMsg">保存成功</div>');
                setTimeout(function() {
                    $(".successMsg").remove();
                },
                2000);
			}
			else{
				$(".rightCont").append('<div class="successMsg">保存失败</div>');
                setTimeout(function() {
                    $(".successMsg").remove();
                },
                2000);
			}
		},'json');
    });
    
</script>
</body>
</html>
