<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>蜜蜂管理中心</title>
</head>
<body>
<!--组织与帐号_LDAP同步2.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">LDAP同步设置</span>
</div>
<div class="ldapSetBox">
	<div class="ldapSetStep">
    	<a  class="selected">1. 连接LDAP服务器<b class="arrow"></b></a>
    	<a  class="selected current">2. 选择同步的组织<b class="arrow"></b></a>
    	<a >3. 指定员工信息<b class="arrow"></b></a>
    	<a >4. 选择同步的员工信息<b class="arrow"></b></a>
    	<a >5. 设置帐号规则<b class="arrow"></b></a>
        <div class="bar">
        	<div class="innerBar" style="width:40%;">
                <b class="ibgL"></b><b class="ibgR"></b>
            </div>
            <b class="bgL"></b><b class="bgR"></b>
        </div>
    </div>
    <dl class="ldapSetCont">
	
        <dt class="setTitle">请选择要同步的组织结构：</dt>
		<dd class="error">操作超时，请稍后再试</dd>
        <dd style="padding: 10px 0;">
            <div class="treeBox">
            </div>
        </dd>
    </dl>
	<div class="toolBar2">
    	<a class="btnGray btn fl" href="javascript:loadPage('<?php echo site_url('main/mainPage');?>','main');"><span class="text" style="cursor: pointer">放弃</span><b class="bgR"></b></a>
		<a class="btnGray btn" href="javascript:loadCont('<?php echo site_url('ldap/showLdapPage');?>');"><span class="text" style="cursor: pointer">上一步</span><b class="bgR"></b></a>
		<a class="btnBlue yes"><span class="text" onclick='nextStep()' style="cursor: pointer">下一步</span><b class="bgR"></b></a>
	</div>
</div>
<div id="checking">
	<span>验证设置中，请稍候...</span>
</div>
<script type="text/javascript">
var page_ldap_arr ='<?php echo json_encode($page_ldap_arr); ?>' ;//LDAP 信息数组
var LDAP_org_arr ='<?php echo json_encode($LDAP_org_arr); ?>' ;//获得ldap 组织结构
var treeNodeLdapText = [
	{'text':'OU=Products Division', 'children':[{'text':'研发部'},{'text':'市场部'},{'text':'营销部'}]},
	{'text':'OU=Market', 'children':[{'text':'OU=beijing'},{'text':'OU=shanghai'},{'text':'OU=shenzhen'},{'text':'OU=guangzhou'},    {'text':'OU=shandong'},{'text':'OU=UE'}]},
	{'text':'OU=R & D center'}
];
function createLdapNode(){
  var root = {
	"id" : "0",
	"text" : "DC=全时，DC=com[192.168.12.1]",
	"value" : "86",
	"showcheck" : true,
	"complete" : true,
	"isexpand" : false,
	"checkstate" : 0,
	"hasChildren" : true
  };
  var arr = [];
  for(var i=0;i<treeNodeLdapText.length; i++){
	var subarr = [];
	if(treeNodeLdapText[i]['children']){
		for(var j=0;j<treeNodeLdapText[i]['children'].length;j++){
		  var value = "node-" + i + "-" + j; 
		  subarr.push( {
			 "id" : value,
			 "text" : treeNodeLdapText[i]['children'][j]['text'],
			 "value" : value,
			 "showcheck" : true,
			 "complete" : true,
			 "isexpand" : false,
			 "checkstate" : 1,
			 "hasChildren" : false
		  });
		}
	}
	arr.push( {
	  "id" : "node-" + i,
	  "text" : treeNodeLdapText[i]['text'],
	  "value" : "node-" + i,
	  "showcheck" : true,
	  "complete" : true,
	  "isexpand" : false,
	  "checkstate" : 0,
	  "hasChildren" : subarr.length?true:false,
	  "ChildNodes" : subarr
	});
  }
  root["ChildNodes"] = arr;
  return root; 
}
function nextStep() {
	var Re_context=$(".treeBox").getTSNs();//获取到选中的框的数量
	if(Re_context.length==0)
	{
	  $('dd.error').text("您必须选择一项")
	  return false;
	}
	else
	{
	   /*$("#checking").show();*/
	 var Re_con=[];
	  var i=Re_context.length-1;
	  var j=i;
	  var Re_name=[];
	  while(i>-1)
	  {
	    Re_con[j]=Re_context[i].id;//id
	    Re_name[j]=Re_context[i].text;//context
		j=j-1;
		i=i-1;
	  }
	  var Re_data='';
	  for(var i=0;i<Re_context.length;i++)
	  {
	    ///Re_data=Re_data+'{id:'+Re_con[i]+',pid:'+Re_con[i+1]+',name:'+Re_con[i+2]+',},';
		Re_data=Re_data+'{id:'+Re_con[i]+',name:'+Re_name[i]+'},';
	  }
	  Re_data=DelLastComma(Re_data);
	   var path="<?php echo site_url('ldap/loadLdap1');?>";
	   var obj={
	         "select_context":Re_data  //选中的框的内容
	   };
		$.post(path,obj,function(data){
			var json = $.parseJSON(data);
			if(json.code == 0)
			{ 
				  $("#checking").show();
				  loadCont('<?php echo site_url('ldap/loadLdap');?>');
				 /* var clr = setTimeout(function(){
				  loadCont('<?php echo site_url('ldap/isLdapLink1');?>');	
				  clearTimeout(clr);
					 },2000)*/
			}
		else
		 {
		  /* $('#'+json.error_id).parent("div").addClass("error");/*/
		   return false;
	     } 
	})
	  /*var clr = setTimeout(function(){
		loadCont('<?php echo site_url('ldap/loadLdap');?>');	
		clearTimeout(clr);
	   },2)
	  */
	}
}
	$(function(){
		var treedata = [createLdapNode()];
				
		$(".treeBox").treeview({
			showcheck:true,
			data:treedata
		});
		checkbox();
		//组织结构树		
	});
</script>
</body>
</html>