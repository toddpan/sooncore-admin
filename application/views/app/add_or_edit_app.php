<div id="appAddOrEdit">
	<div class="contHead">
		<span class="title01" style="padding: 0px">应用管理</span>
	</div>
	<div>
		<div class="appLogo">
			<dl class="bisinessLogo">
				<dt class="title">应用LOGO</dt>
				<dd class="img"><img src="<?php echo base_url('public/images/bisinessLogo.jpg'); ?>" /></dd>
				<dd class="tc"><a class="contLink">修改</a></dd>
			</dl>
		</div>
		<span class="error1" style="margin-left:160px;color:#ec6764;display:none"></span>
		<table class="infoTable" style="float:50px;width:50%">
			<tr>
				<td class="tr">应用标题：</td>
				<td>
					<span class="infoText"></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="app_title" class="input" maxlength="100"  value=""/>
					</div>
				</td>
			</tr>
			<tr>
				<td class="tr">应用描述：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="app_desc" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<td class="tr">出版者：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="author" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
<!-- 			<tr> -->
<!-- 				<td class="tr">应用账号：</td> -->
<!-- 				<td>
					<dl class="radio-dl" style="padding: 0px;">
						<dd id="app_acount" style="padding: 0px;">
<!-- 							<label  class="radio radio_on"> -->
<!-- 								<input type="radio" name="app_acount" value="1" /> -->
<!-- 								同蜜蜂账号 -->
<!-- 							 </label> -->
<!-- 							 <label  class="radio"> -->
<!-- 								<input type="radio" name="app_acount" value="0" /> -->
<!-- 								使用其他账号 -->
<!-- 							</label> -->
<!-- 						</dd> -->
<!-- 					</dl> -->
<!-- 				</td> -->
<!-- 			</tr> -->
			<tr>
				<td class="tr" colspan="2" style="padding-left: 80px;text-align: left;">平台URL</td>
			</tr>
			<tr>
				<td class="tr">PC客户端：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="pc_url" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<td class="tr">IOS客户端：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="ios_url" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<td class="tr">Android客户端：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="android_url" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
			<tr>
				<td class="tr">启动代理：</td>
				<td>
					<dl class="radio-dl" style="padding: 0px;">
						<dd id="use_agent" style="padding: 0px;">
							<label  class="radio radio_on">
								<input type="radio" name="use_agent" value="1" />
								是
							 </label>
							 <label  class="radio">
								<input type="radio" name="use_agent" value="0" />
								否
							</label>
						</dd>
					</dl>
				</td>
			</tr>
<!-- 			<tr>
				<td class="tr">应用账号属性对应关系：</td>
				<td>
					<span class="infoText "></span>
					<div class="inputInfoBox w360 hide add_css">
						<input id="relative" class="input"  maxlength="100" value="" />
					</div>
				</td>
			</tr>
			<tr> -->
<!-- 				<td class="tr">适用对象：</td> -->
<!-- 				<td> 
					<dl class="radio-dl" style="padding: 0px;">
						<dd id="oriented_obj" style="padding: 0px;">
<!-- 							<label  class="radio radio_on"> -->
<!-- 								<input type="radio" name="oriented_obj" value="1" /> -->
<!-- 								全体员工 -->
<!-- 							 </label> -->
<!-- 							 <label  class="radio"> -->
<!-- 								<input type="radio" name="oriented_obj" value="2" /> -->
<!-- 								部分员工 -->
<!-- 							</label> -->
<!-- 						</dd> -->
<!-- 					</dl> -->
<!-- 				</td> -->
<!-- 			</tr> -->
			<tr>
				<td class="tr">立即启用：</td>
				<td>
					<dl class="radio-dl" style="padding: 0px;">
						<dd id="status" style="padding: 0px;">
							<label  class="radio radio_on">
								<input type="radio" name="status" value="1" />
								是
							 </label>
							 <label  class="radio">
								<input type="radio" name="status" value="0" />
								否
							</label>
						</dd>
					</dl>
				</td>
			</tr>
		</table>
		<div class="toolBar2" style="float: left;padding: 0 400px;">
			<a class="btnBlue yes">
				<span class="text">保存</span>
				<b class="bgR"></b>
			</a>
			<a class="btnGray btn">
				<span class="text">取消</span>
				<b class="bgR"></b>
			</a>
		</div>
	</div>
</div>

<script type="text/javascript" src="public/js/part_js/qs.appAddOrEdit.js"></script>
<script>
	$().ready(function(){
		qs.appAddOrEdit.init({
			app_info: <?php echo json_encode($app_info, true); ?>
		});
	});
</script>