<script type="text/javascript">
<!--
confName = $('#updateLdapName').val();
if(confName != ''){
	$('.ldapName').attr('value', confName);
}
//-->
</script>
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">指定名称</span>
		<a class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<div class="form-item">
        <label>为此次LDAP设置指定名称:</label>
        <div class="inputBox"><input class="ldapName" type="text" style="width: 428px;" value="LDAP设置" class="input" /></div>
        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes" id="ldap_name_dialog"><span class="text" style="cursor: pointer" >确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text" style="cursor: pointer">取消</span><b class="bgR"></b></a>
		<!-- 	<a class="btnBlue btn_confirm"  onclick="locatioonclick=" Ldap_name();"n.href='ldap-layout.html#ldapList'; loadCont('ldap列表.html');hideDialog();"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn_cancel"  o><span class="text">取消</span><b class="bgR"></b></a> -->
		<!--  <a class="btnBlue btn_confirm"  ><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a> -->
	</dd>
</dl>
<script type="text/javascript">
$(function()
{
     $('#dialog #ldap_name_dialog').on('click',
     function() {
     	$("#checking").show();
         ldap_name = $("#dialog .dialogBox .dialogBody .inputBox").find("input").val();
         if(/^\s*$/.test(ldap_name)){
			alert('LDAP名称不能为空！');
			$("#checking").hide();
         }else{
	         obj.ldap_name = ldap_name;
	         var ldap_id = $(".ldapSetBox5 #Select_div").attr("name");
	         var path_ldap;
	         if (ldap_id > 0) {
	              path_ldap = "ldap/updateLdap";
	             obj.ldap_id = ldap_id;
	         } else {
	             path_ldap = "ldap/createLdap";
	         }		
	         $.ajax({
	             url: path_ldap,
	             timeout: 6000,
	             type: "POST",
	             data: obj,
	             cache: false,
	             success: function(data) {
	             	$("#checking").hide();
	             	var json = $.parseJSON(data);
	                 if (json.code == 0) {
	                 	$("#checking").show();
// 						$('#ri_group').load('ldap/getLdapList',function(data)
// 						 {
// 							 $("#checking").hide();
// 						 });
	                     //location.href = "ldap/ldaplayout"+"#ldapList";
	                    $("#checking").hide();
	                 	hideDialog();
	                 	loadCont('ldap/getLdapList');
	                 } else {
	                     $('#' + json.error_id).parent("div").addClass("error");
	                     return false;
	                 }
	             },
	             error:function()
	             {
	             	$("#checking").hide();
	                 $('.ldapSetBox5 .error5').show();
	 				$('.ldapSetBox5 dd.error5').text("操作超时，请稍后再试");
	 				hideDialog();
	             }
	     	});
     	}
     });
});
</script>