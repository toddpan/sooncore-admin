<div class="contHead"> 
	<span class="title01 rightLine">企业生态</span>
	<span class="title03">创建生态企业</span>
</div>
<div class="ldapSetBox" id="head_style">
		<div class="ldapSetStep qystStep"> 
			<a  class="selected current">1. 填写生态企业信息
				<b class="arrow"></b>
			</a> 
			<a >2. 设置生态企业权限
				<b class="arrow"></b>
			</a>
			<a >3. 设置该企业管理员
				<b class="arrow"></b>
			</a> 
			<a >4. 设置本方参与的用户
				<b class="arrow"></b>
			</a>
			<div class="bar">
				<div class="innerBar" style="width:25%;">
					 <b class="ibgL"></b>
					 <b class="ibgR"></b>
				</div>
					<b class="bgL"></b>
					<b class="bgR"></b> 
			</div>
		</div>
</div>
<div class="new_ecology ldapSetBox" id='creater_one' target="1">
		<table class="infoTable">
			<tr>
				<td width="100">上级企业：</td>
				<td id="pre_name">
				</td>
        	</tr>
			<tr>
				<td>企业名称：</td>
				<td><div class="inputBox"> <b class="bgR"></b>
						<label class="label" ></label>
						<input class="input" style="width: 454px;" id="create_company_name" value="" />
					</div></td>
			</tr>
			<tr>
				<td>中文简称：</td>
				<td><div class="inputBox"> <b class="bgR"></b>
						<label class="label"></label>
						<input class="input" style="width: 454px;"  id="create_company_chinese" value="" />
					</div></td>
			</tr>
			<tr>
				<td>联系电话：</td>
				<td>
					<div class="combo selectBox" style="width:100px;"> 
						<a class="icon"></a> 
						<span class="text" id="create_add_num">{$telephone}</span>
						<div class="optionBox" >
							<dl class="optionList" style="height:160px;overflow:scroll">
								{$j=0}
								{foreach $country_code as $code}
								<dd class="option {if $telephone== $code}
											selected
										{/if}" target="{$j}">{$code}</dd>
								{$j=$j+1}
								{/foreach}
							</dl>
						</div>
					</div>
					<div class="inputBox"> 
						<b class="bgR"></b>
						<label class="label" for="quhao">区号</label>
						<input class="input" id="create_area_code" value=""  style="width: 72px;" />
					</div>
					<div class="inputBox"> <b class="bgR"></b>
						<label class="label" for="phoneNum">电话号码</label>
						<input class="input" id="create_phoneNum_1" value=""  style="width: 262px;" />
					</div>
				</td>
			</tr>
			<tr>
				<td>国家地区：</td>
				<td><div class="inputBox"> <b class="bgR"></b>
						<label class="label" ></label>
						<input class="input" style="width: 454px;" id="create_country_area" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top">公司介绍：</td>
				<td><div style="width: 464px; height: 90px;">
						<textarea id="textarea" name="" cols="" rows="" class="textarea" style="width: 454px; height: 80px;"></textarea>
					</div></td>
			</tr>
		</table>
</div>
<div class="toolBar2" id="prev_next"> 
	<a class="btnGray btn fl" onclick="loadPage('ecologycompany/ecologyPage','main');">
		<span class="text" style="cursor: pointer">放弃</span>
		<b class="bgR"></b>
	</a> 
	<a class="btnGray btn" style="display:none" onclick="back_step(this);">
		<span class="text" style="cursor: pointer">上一步</span>
		<b class="bgR"></b>
	</a> 
	<a class="btnBlue yes ldapStepNext" id="new_stqy_one">
		<span class="text" onclick="nextStep(this,event)" style="cursor: pointer" >下一步</span>
		<b class="bgR"></b>
	</a> 
</div>
<div id="checking" style="display:none"> 
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript" src="public/js/part_js/createEco1.js"></script>
<script type="text/javascript">
var company_ecol_id = '{$org_id}';
if(operate_type==0)
{
	var zTree = $.fn.zTree.getZTreeObj('stqyTree');
	var treeNode_pre=zTree.getSelectedNodes();
	$("#pre_name").text(treeNode_pre[0].name);
}
else
{
	
}
function nextStep(t,e)
{

	var pre=$(t).parent().parent().prev().attr("target");
	var ind=pre;
	var num='nextStep'+ind;
	eval(num+"(e)");
}
function back_step(t)
{
	if($(t).parent().prev().attr("target")==2)
	{
		var prev_page=$(t).parent().prev().prev().prev();
	}
	else
	{
		var prev_page=$(t).parent().prev().prev();
	}
	var current_page=$(t).parent().prev();
	var head=$('#head_style');
	var tom=$(t).parent();
	current_page.hide();
	prev_page.show();
	var ind=prev_page.attr("target");
	ind=ind-1;
	var len=25+ind*25;
	head.find(".innerBar").css('width',len+'%');
	head.find("a").removeClass("selected");
	head.find("a").removeClass("current");
	head.find('a:eq('+ind+')').addClass("selected");
	head.find('a:eq('+ind+')').addClass("current");
	var back_next=tom;
	tom.remove();
	prev_page.after(back_next);
	if(ind==0)
	{
		tom.find("a:eq(1)").hide();
	}
	
}
	//var cost_get_staff = 'organize/get_next_orguser_list'; //组织结构和成本中心部分的调入员工
//从页面1到页面3
	var obj1_json = '';
	var obj1 = {};
</script>