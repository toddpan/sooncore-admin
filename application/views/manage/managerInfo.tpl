<div id="manger_detail_page">
        <div class="bread"><span>管理员管理</span>&nbsp;&gt;&nbsp;<span>{$role_name}</span>&nbsp;&gt;&nbsp;<span>{$must_tag['last_name']}</span></div>
        <!-- end bread -->
        <div class="infoTitle">
            <a class="pageGoBack" id="admin_goback"></a>
            <span class="personName" user="{$must_tag['user_id']}" role_id="{$id}">{$must_tag['last_name']}</span>
            <div class="fr">
                <a class="btn" onclick="toggleAccount(this)"><span class="text">关闭帐号</span><b class="bgR"></b></a>&nbsp;              
                <a class="btn" onclick="alert_reset_pwd({$must_tag['user_id']})"><span class="text">重置密码</span><b class="bgR"></b></a>
            </div>
        </div>
        <div class="cont-wrapper">
        <ul class="infoNav">
            <li>员工信息</li>
            <li>员工权限</li>
            <li class="selected">管理员权限</li>
        </ul>
        <dl class="infoCont">
            <dd style="display:none;" id="dd1">
                <div class="toolBar2">
                    <a class="btnGray btn_infoEdit"><span class="text">编辑信息</span><b class="bgR"></b></a>
                    <a class="btnBlue btn_infoSave hide" onclick="staff_save_infor(this,{$must_tag['user_id']},{$id})"><span class="text">保存</span><b class="bgR"></b></a>
                    <a class="btnGray btn_infoCancel hide"><span class="text">取消</span><b class="bgR"></b></a>
                </div>
                <table class="infoTable">
                
                    <tr>
                        <td class="tr" style="width:110px;">姓名：</td>
                        <td>
                            <span class="infoText">{$must_tag['last_name']}</span>
                            <div class="inputBox w110 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="{$must_tag['last_name']}" id="chinese_name"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
						<td class="tr">帐号：</td>
						<td><span class="infoText userCount">{$must_tag['login_name']}</span>
							<div class="inputBox w360 hide"> <b class="bgR"></b>
								<label class="label"></label>
								<input class="input account_input" value="{$must_tag['login_name']}" id="usercount" />
							</div></td>
					</tr>
                    <tr>
						<td class="tr">性别：</td>
						<td><span class="infoText"> {if $must_tag['sex'] == 1}男{/if} {if $must_tag['sex'] == 2}女{/if}</span>
						<div class="radioBox hide" id="sex">
							<label class="radio radio_on" onclick="sex_select(this)" target="1">
							<input onchange='change_value(this)' type="radio" value="1" {if $must_tag['sex'] == 1} checked {/if} />
								男</label>
								<label class="radio" onclick="sex_select(this)" target="2">
								<input name="xb " onchange='change_value(this)' type="radio" value="2" {if $must_tag['sex'] == 2} checked {/if} />
								女</label>
						</div>
						</td>
					</tr>
                    <tr>
                        <td class="tr">职位：</td>
                        <td>
                            <span class="infoText">{$must_tag['position']}</span>
                            <div class="inputBox w360 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="{$must_tag['position']}" id="position"/>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="tr">手机：</td>
                        <td>						
                            <span class="infoText ">{$must_tag['country_code']}</span>
                            <div class="combo selectBox w60 hide">
                                <a class="icon" ></a>
                                <span class="text selected" id="add_num_1">{$must_tag['country_code']}</span>
                                <div class="optionBox">
                                    <dl class="optionList">
                                       {foreach $must_tag['country_arr'] as $country_codes}
										<dd target="1" class="option 
										{if $country_codes['country_code']==$must_tag['country_code']}selected{/if}" style="">{$country_codes['country_code']}</dd>
										{/foreach}
                                    </dl>
                                </div>
                            </div>
							<span>-</span>
                            <span class="infoText">{$must_tag['country_mobile']}</span>
                            <div class="inputBox w130 hide" style="*margin-left:10px;*margin-top:-26px">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="{$must_tag['country_mobile']}" id="phoneNum"/>
                            </div>						
                        </td>
                    </tr>
                    <!--
                    <tr>
                        <td class="tr">办公地址：</td>
                        <td>
							<span class="infoText ">{$must_tag['address']}</span>
							<div class="inputBox w360 hide">
                                <b class="bgR"></b>
                                <label class="label"></label>
                                <input class="input" value="{$must_tag['address']}" id="location"/>
                            </div>
                        </td>
                    </tr>
                    -->
                    <tr>
						<td class="tr">部门：</td>
						<td>
							<span class="infoText" id="depart_span" readonly="readonly">{$must_tag['org_json']['value']}</span>
							<div class="select-box w210 hide">
								<input  cl_id="part1"  type="text" class="text" value="{$must_tag['org_json']['value']}" onClick="showMenu_staff_info(this);" id="departmentSel2" placeholder="请选择管理的部门" />
								<a class="icon" onClick="showMenu_staff_info(this); return false;"></a>
								<div class="selectOptionBox"   target='0'  style="display: none;z-index:9;width: 210px;">
									<ul class="ztree" id="ztree_admin" ids="{$must_tag['org_json']['id']}">
									</ul>
								</div>
							</div></td>
					</tr>
					<div id="self_label">
					{foreach $other_tag_arr as $other_tag}
					<tr>
						<td class="tr">{$other_tag['tag_name']}：</td>
						<td target_name="{$other_tag['tag_key']}">
							<span class="infoText ">{$other_tag['tag_value']}</span>
							<div class="inputBox w360 hide"> 
								<b class="bgR"></b>
								<label class="label">
								{if $other_tag['tag_key']=="email"}
									
								{/if}
								</label>
								<input class="input" value="{$other_tag['tag_value']}" />
							</div>
						</td>
					</tr>
					{/foreach}
					</div>
                </table>
            </dd>
            <dd style="display:none;" id="dd2">
		<div class="toolBar2" style="display:none;"> 
			<a class="btnBlue yes">
				<span class="text" onclick="save_admin_right({$must_tag['user_id']})">保存</span>
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
	<!-- 	可使用全时云企 IM 互传文档</label> -->
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
				允许使用云企拨打电话
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
            <dd>
                <div class="toolBar2">
                    <a class="btnGray btn_infoEdit2" id="detail_3"><span class="text">编辑信息</span><b class="bgR"></b></a>
                    <a class="btnBlue btn_infoSave2 hide" onclick="save_manger_detail({$must_tag['user_id']})"><span class="text">保存</span><b class="bgR"></b></a>
                    <a class="btnGray btn_infoCancel2 hide"><span class="text">取消</span><b class="bgR"></b></a>
                </div>
                <div id="infoBox03" style="display: block">
				{$j=0}
				{foreach $admin_role_arr as $role}
                    <table class="infoTable">
                        <tr>
                            <td class="tr" style="width:92px;">管理员角色：</td>
                            <td>
                                <span class="infoText">{$role['role']}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">组织管理范围：</td>
                            <td>
                                <span class="infoText">第一个管理维度：
                                {if $role['scope_level_1'] == 'department'}{$first_weidu[$j]="部门"}部门{/if}
                                {if $role['scope_level_1'] == 'region'}{$first_weidu[$j]="地区"}地区{/if}
                                {if $role['scope_level_1'] == 'costcenter'}{$first_weidu[$j]="成本中心"}成本中心{/if}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                            	{if $role['scope_level_1'] == 'department'}
                            	   <span class="infoText">{$role['org_name']}</span>
                            	{/if} 
                            	
                            	{if $role['scope_level_1'] != 'department'}
                            		{foreach  $role['scope_level_1_value'] as $first}
                                	<span class="infoText">{$first}</span>
								{/foreach}
                            	{/if}
                            </td>
                        </tr>
						{if $role['scope_level_2']!=''}
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
                                <span class="infoText">第二个管理维度：
                                {if $role['scope_level_2'] == 'department'}{$second_weidu[$j]="部门"}部门{/if}
                                {if $role['scope_level_2'] == 'region'}{$second_weidu[$j]="地区"}地区{/if}
                                {if $role['scope_level_2'] == 'costcenter'}{$second_weidu[$j]="成本中心"}成本中心{/if}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="tr">&nbsp;</td>
                            <td>
								{if $role['scope_level_2'] == 'department'}
                                	<span class="infoText">{$role['org_name']}</span>
								{/if}
								{if $role['scope_level_2'] != 'department'}
									<span class="infoText">{$role['scope_level_2_value']}</span>
								{/if}
                                
                            </td>
                        </tr>
						{/if}
						{$j=$j+1}
                 </table>
			{/foreach}
                 <div class="setTitle"></div>
            </div>
            <div id="editBox03" style="display:none;">
			{$i=0}
			{$j=0}
			{foreach $admin_role_arr as $role}
                    <table class="infoTable">
                        <tr>
                            <td width="220">管理员角色：</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>
                                <div id="juese" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">{$role['role']}</span>
                                    <div class="optionBox">
                                        <dl class="optionList" >
                                            <dd class="option" target="1" onclick="reselect_role(this)">请选择管理员角色</dd>
                                            <dd class="option {if $role['role']=="员工管理员"}selected{/if}" target="2" onclick="reselect_role(this)">员工管理员</dd>
                                            <dd class="option {if $role['role']=="帐号管理员"}selected{/if}" target="3" onclick="reselect_role(this)">帐号管理员</dd>
                                            <dd class="option {if $role['role']=="生态管理员"}selected{/if}" target="4" onclick="reselect_role(this)">生态管理员</dd>
                                        </dl>
                                    </div>
                                </div>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
						
                        <tr class="hideBar01 {if $role['role']=="生态管理员"}
						hide
						{/if}">
                            <td>组织管理范围：</td>
                            <td>&nbsp;</td>
                        </tr>
						{$i=$i+1}
                        <tr id="admin_weidu" class="hideBar01 {if $role['role']=="生态管理员"}
						hide
						{/if}">
                            <td>
                                <div id="weidu01{$i}" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">{$first_weidu}</span>
                                    <div class="optionBox">
                                        <dl class="optionList" target="2">
                                            <dd class="option" target="1" onclick="reselect_weidu(2,this,'detail_ztree{$i}','first_level{$i}','weidu01{$i}')">请选择第一个管理维度</dd>
                                            <dd class="option {if $first_weidu[$j]=="部门"}selected{/if}" target="2" onclick="reselect_weidu(2,this,'detail_ztree{$i}','first_level{$i}','weidu01{$i}')">部门</dd>
                                            <dd class="option {if $first_weidu[$j]=="地区"}selected{/if}" target="3" onclick="reselect_weidu(3,this,'detail_ztree{$i}','first_level{$i}','weidu01{$i}')">地区</dd>
                                            <dd class="option {if $first_weidu[$j]=="成本中心"}selected{/if}" target="4" onclick="reselect_weidu(4,this,'detail_ztree{$i}','first_level{$i}','weidu01{$i}')">成本中心</dd>
                                        </dl>
                                       
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="select-box w210" id="first_level{$i}">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel" value="{$role.first_detail}"  cl_id="part1" placeholder="请选择管理的部门" />
                                    <a class="icon"  id="menuBtn" onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" id="selectOption1" style="display: none; width: 210px;">
									
                                        <ul class="ztree" id="detail_ztree{$i}" ids="{$role.ids}"></ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
						{$i=$i+1}
                        <tr id="admin_weidu_1" class="hideBar02 {if $role['scope_level_2']==''}hide{/if} {if $role['role']=="生态管理员"}
						hide
						{/if}">
                            <td>
                                <div id="weidu02" class="combo selectBox w210">
                                    <a class="icon" ></a>
                                    <span class="text selected">{$second_weidu}</span>
                                    <div class="optionBox">
                                        <dl class="optionList">
                                            <dd class="option" target="1">请选择第二个管理维度</dd>
                                            <dd class="option {if $first_weidu[$j]=="部门"}hide{/if} {if $second_weidu[$j]=="部门"}selected{/if}" target="2" onclick="reselect_weidu_1(2,this,'detail_ztree{$i}','first_level{$i}','weidu02',event)">部门</dd>
                                            <dd class="option {if $first_weidu[$j]=="地区"}hide{/if} {if $second_weidu[$j]=="地区"}selected{/if}" target="3" onclick="reselect_weidu_1(3,this,'detail_ztree{$i}','first_level{$i}','weidu02{$i}',event)">地区</dd>
                                            <dd class="option {if $first_weidu[$j]=="成本中心"}hide{/if} {if $second_weidu[$j]=="成本中心"}selected{/if}" target="4" onclick="reselect_weidu_1(4,this,'detail_ztree{$i}','first_level{$i}','weidu02',event)">成本中心</dd>
                                        </dl>
                                    </div>
                                </div>
                            </td>
                            <td>
                               <div class="select-box w210 {if $role['scope_level_2']==''}hide{/if}" id="first_level{$i}">
                                    <input type="text" class="text" onclick="showMenu(this);" id="departmentSel2" placeholder="请选择管理的部门" value="{$role.second_detail}" cl_id="part1"  ids="{$role.second_id}"/>
                                    <a class="icon"  onclick="showMenu(this); return false;"></a>
                                    <div class="selectOptionBox" style="display: none; width: 210px;">
										
                                        <ul class="ztree" id="detail_ztree{$i}"></ul>
                                    </div>
                                </div>
                               
                            </td>
                        </tr>
						
                    </table>
					{$j=$j+1}
                    <div class="setTitle"></div>
                    {/foreach}
                </div>
            </dd>
        </dl>
        </div>
</div>
<script type="text/javascript" src="public/js/jquery.ztree.all-3.5.min.js"></script>
<script type="text/javascript" src="public/js/self_tree.js"></script>
<script type="text/javascript" src="public/js/part_js/addmanger_tree.js"></script>
<script type="text/javascript" src="public/js/part_js/managerInfo.js"></script>
<script type="text/javascript" src="public/js/part_js/input_radio_tree.js"></script>
<script type="text/javascript" src="public/js/self_common.js"></script>
<script type="text/javascript">
	var path="organize/get_next_OrgList"; //要加载的每个组织结构
</script>
<script type="text/javascript">
	function alert_reset_pwd(user_id){
		showDialog('manager/alert_reset_pwd/' + user_id);
	}
</script>
