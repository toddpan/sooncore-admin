
<div class="ldapSetBox" style="display:none" target="4" id="creater_four">
		<dl class="ldapSetCont">
			<dd style="padding: 10px;">
				<table width="100%" class="table1" style="width: 680px;">
					<tr>
						<td scope="col" width="194">选择员工</td>
						<td scope="col" width="67">&nbsp;</td>
						<td scope="col">已指定员工</td>
					</tr>
					<tr>
						<td>
							<div class="combo searchBox" style="margin-bottom: 10px;"> 
								<b class="bgR"></b> 
								<a class="icon"></a>
								<label class="label">通过关键字查找</label>
								<input class="input" style="width: 274px;" />
							</div>
							<div class="treeLeft" style="width:312px">
								<ul class="ztree" id="org_treeLeft">
								</ul>
							</div>
						</td>
						<td>
							<a  onclick="addToRight(event)" class="btn btn1">
								<span class="text">添加 ></span>
								<b class="bgR"></b>
							</a> 
							<br />
							<br />
							<a  onclick="deleteToLeft()" class="btn btn1">
								<span class="text">< 删除</span>
								<b class="bgR"></b>
							</a>
						</td>
						<td>
							<div class="treeRight" style="width: 240px"> </div>
						</td>
					</tr>
				</table>
			</dd>
		</dl>
</div>
<script type="text/javascript" src="public/js/part_js/createEco4.js"></script>
<script type="text/javascript">
//var company_ecol_id = '<?php echo $org_id ; ?>';
//var cost_get_staff = '<?php echo site_url('organize/get_next_orguser_list '); ?>'; //组织结构和成本中心部分的调入员工
var obj2_json = '';
var obj2 = {};
</script>