<div class="pop">
<title>变更管理员</title>
	<div class="popTitle">变更管理员<a  class="close"></a></div>
	<div class="Popbox">
    <div class="PopMain">
<!--<h4 align="left" class="shenpiTitle"  style="padding-left:50px;font-family:Arial;font-size:16px;font-weight:bold;font-style:normal;text-decoration:none;color:#333333;">变更管理员</h4>-->
<div class="setManTable" id="change_select">
	<div style="color:red;display:none" id="error_tip">
		<label >请选择一种创建方式
		</label>
	</div>
    <table class="buildNewcompany">
		<tr>
			<td class="left"  width="35%"  colspan="3">
				
					<label class="radioBox" id="first"><input type="button"/>从现有用户挑选
					</label>
			</td>			
			<td width="15%" >
				<div align="left">
					<input name="text" type="text"  class="textii" value="" id="loginName"/>
				</div>
			</td>
			<td width="63%">
				<div align="left">（输入用户账号，然后回车）</div>
			</td>
		</tr>
	</table>
	<table width="342" class="buildNewcompany">
	<tr>
		<td class="left" colspan="3" >
			<label class="radioBox" id="second"><input type="button">不存在此用户，创建一个新的管理员
			</label>
		</td>
		<!--<td class="left" width="99%"><div align="left"></div></td>-->
	</tr>
	</table>
		<table class="buildNewcompany2">
    	<tr>
        	<td class="left" width="18%">姓氏：</td>
            <td><input type="text" class="textI w183" value="请输入姓氏" id="first_name"/></td>
            <td class="left">名字：</td>
            <td><input type="text" class="textI w183" value="请输入名称"  id="last_name"/></td>
        </tr>
        <tr>
        	<td class="left">显示名称：</td>
            <td colspan="3"><input type="text" class="textI w183" id="display_name"/> <span class="hui">如果显示名称不是姓氏+名字的组合，请重新填写</span></td>
        </tr>
        <tr>
        	<td class="left">手机号：</td>
            <td colspan="3">
				<div class="select w69" id="countrycode">
					<a >国码</a>
					<ul class="w69" id="country_code">
						{foreach $country_code_arr as $country}
							<li>{$country}</li>
						{/foreach}
					</ul>
				</div>
				<input type="text" class="textI w183 marl10" id="telephone"/></td>
        </tr>
        <tr>
        	<td class="left">固定电话：</td>
            <td colspan="3">
				<div class="select w69" id="countrycode1">
					<a >国码</a>
					<ul class="w69">
						{foreach $country_code_arr as $country}
							<li>{$country}</li>
						{/foreach}
					</ul>
				</div>
				<input type="text" class="textI w46" value="区号" id="city_code"/>
				<input type="text" class="textI w95" value="电话号码" id="phone"/>
				<input type="text" class="textI w46" value="分机号" id="extersion"/></td>
        </tr>
        
        <tr>
        	<td class="left">电子邮箱：</td>
            <td colspan="3">
				<input type="text" class="textI w183" id="email"/>
			</td>
        </tr>
        <tr>
        	<td class="left">部门名称：</td>
            <td><input type="text" class="textI w183" value="请输入部门名称" id="department_name"/></td>
            <td class="left">职位名称：</td>
            <td><input type="text" class="textI w183" value="请输入职位名称"  id="position"/></td>
        </tr>
        <tr>
        	<td class="left">指定管理员帐号：</td>
            <td colspan="3" ><input type="text" class="textI w183" id="login_name"/> @ <input type="text" class="textI w183" /> .quanshi.com</td>
        </tr>
        <tr>
        	<td class="left">&nbsp;  </td>
            <td colspan="3" class="hui">管理员初始化可使用这个使用者帐号登录，之后同步或导入公司员工信息后可改用公司定义的帐号</td>
        </tr>
    </table>
	
	</div>
	</div>
</div>
<div class="popFooter"><a onclick="save_change_manager()">变更完成</a><a onclick="hideDialog()" style="cursor:pointer">取消</a></div>
</div>
<script type="text/javascript" src="public/js/filiale/change_manager.js"></script>
<script type="text/javascript">

</script>