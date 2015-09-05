<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE">
	<base href="<?php echo site_url();?>"/>
        <base target="_blank" />
	<title>管理中心</title>
	<link href="public/css/common.css" rel="stylesheet" />
	<!--//<link href="public/css/self_common.css" rel="stylesheet" />//-->
	<link href="public/css/datepicker.css" rel="stylesheet" />
	<link href="public/css/tree.css" rel="stylesheet" />
	<link href="public/css/ldap.css" rel="stylesheet" />
	<link href="public/css/jquery.jscrollpane.css" rel="stylesheet" />
	<link href="public/css/jquery.jqplot.css" rel="stylesheet" />
	<link type="text/css" href="public/zTreeStyle/zTreeStyle.css" rel="stylesheet" />
</head>
<body>
<div class="pageBody">
    <div class="headerBox">
        <div class="header">
  	<a class="logo" href="main/index" target="_self">
            <span class="text1">海尔</span>
            <span class="text2">管理中心</span>
  	</a>
    <!-- end logo -->
    
    <ul class="topMenu">
      <li class="selected">
	 	 <a class="main" onclick="limit_click(this);loadPage('main/mainPage','main')"><span class="icon">首页</span></a>
	  </li>
	  <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == EMPPLOYEE_MANAGER || $this->p_role_id == CHANNEL_MANAGER || $this->p_role_id == ACCOUNT_MANAGER){?>
      <li>
        <a class="group"  onclick="limit_click(this);loadPage('organize/OrgList','group')"><span class="icon">通讯录</span></a>
	  </li>
	  <?php }?>
        <!--//
        <?php if($this->p_role_id == SYSTEM_MANAGER || $this->p_role_id == ORGANIZASION_MANAGER || $this->p_role_id == ECOLOGY_MANAGER || $this->p_role_id == CHANNEL_MANAGER){?>
          <li><a class="company"  onclick="limit_click(this);loadPage('ecologycompany/ecologyPage','company')"><span class="icon">企业生态</span></a></li>
        <?php }?>
        //-->
      <?php if($this->p_role_id == SYSTEM_MANAGER){?>
      <li><a class="safe"  onclick="limit_click(this);loadPage('password/PWDManagePage','safe')"><span class="icon">安全管理</span></a> </li>
     <?php }?>
     <?php if($this->p_role_id == ORGANIZASION_MANAGER){?>
      <li><a class="safe"  onclick="limit_click(this);loadPage('log/logPage','safe')"><span class="icon">安全管理</span></a> </li>
     <?php }?>
     <?php if($this->p_role_id == EMPPLOYEE_MANAGER){?>
      <li><a class="safe"  onclick="limit_click(this);loadPage('sensitiveword/sensitiveWordPage/1','safe')"><span class="icon">安全管理</span></a> </li>
     <?php }?>
     <?php if($this->p_role_id == SYSTEM_MANAGER){?>
      <li>
	  	<a class="system"  onclick="limit_click(this);loadPage('systemset/company','system')"style="cursor: pointer" ><span class="icon">系统设置</span></a>
	 </li>
	 <?php }?>
    </ul>
    <!-- end leftMenu -->
    
    
    <ul class="headerLink">
      <li class="hlItem"> 
		 <a class="user" >
                    <span class="text" ><?php echo $displayName ; ?></span>
                    <span class="icon"style="cursor: pointer" ></span>
		</a>
		<dl class="menu" style="width: 86px; clear:both;">
		  <dt><a class="changePwd" >修改密码</a></dt>
		  <dt><a href="login/logout" target="_self">注销</a></dt>
		</dl>
      </li>
<!--       <li class="hlItem"> 
  	 	 <a class="email" > 
			 <span class="text">消息</span>
  			 <span class="icon nums"><b><?php //echo $msg_sum ; ?></b></span> 
 		 </a> 
 		 <span class="hArrow"></span> 
 	  </li>  -->
	  <?php if($this->p_role_id == SYSTEM_MANAGER){?>
		<li class="hlItem">
			<a class="admin" ><span class="text">管理员管理</span></a>
			<span class="hArrow"></span>
		</li>
	<?php }?>
<!--       <li class="hlItem"> 
		  <a class="help" ><span class="text">帮助中心</span></a>
<!-- 		  <span class="hArrow"></span> -->
<!-- 	  </li> -->
    </ul>
    <!-- end headerLink -->
  </div>
    </div>
  <!-- end header -->
  <div class="content clearfix">
      
    <div class="rightCont clearfix">
      <!-- 此处内容为ajax加载 -->
    </div>
    <!-- end rightCont -->
  </div>
  <!-- end content -->
  <div class="footer">
	  <span class="text rightLine">
	  <?php echo COMPANY_NAME;?> 
	  <?php echo COMPANY_COPR;?> 
	  <?php echo COMPANY_ICP;?>
	  </span>
	  <span class="text">24小时服务热线：<?php echo COMPANY_SERVE_TEL;?></span>
  </div>
  <!-- end footer -->
</div>
<script type="text/javascript" src="public/js/jquery.js"></script>
<script type="text/javascript" src="public/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="public/js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="public/js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="public/js/jquery.tree.js"></script>
<script type="text/javascript" src="public/js/common.js"></script>
<script type="text/javascript" src="public/js/self_common.js"></script>
<script type="text/javascript">

function limit_click(t) {
    if ($(t).hasClass("false")) {
        //alert(1)
        return;
    }
    //alert(2)
    $(t).addClass("false");
	if($('.headerLink .bg').length>0)
	{
		$('.headerLink .bg').removeClass("bg");
	}
}
$(function(){	
		var hash = location.hash.substring(1);
		
		switch(hash){
			case "":				
				loadPage('main/mainPage','main');
				break;
			case "msg3":
				loadCont("information/infoManPage");
				$('.topMenu > li').removeClass('selected');
				break;
			case "admin":
				loadPage('组织与员工-管理员.html','group');
				break;
			case "companyAdmin":
				loadCont('ecologycompany/ecologyInfoPage');
				$('.topMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
				break;
			/*case "app":
				//alert(1);
				loadCont('<?php //echo site_url('application/index');?>');
				//$('.topMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
				break;*/
			default:
				$(".topMenu a."+hash).trigger("click");
		}
	
		$('.headerLink .email').click(function(){
			loadPage("information/infoManPage","msg3");
			$('.topMenu > li').removeClass('selected');
		});		

	});
	
	$('.changePwd').click(function(){
		loadCont("mixture/resetPwd");
		$('.topMenu > li').removeClass('selected');
	});
	//帮助中心
	$('.headerLink .help').click(function(){
		loadCont("mixture/showHelpCenter");
		$('.topMenu > li').removeClass('selected');
	});
	//管理员管理
	$('.headerLink .admin').click(function(){
		loadCont("manager/listManagerPage");
		$('.topMenu > li').removeClass('selected');
	});
</script>

<div class="mask"></div>
<div id="dialog" class="dialog">
  <div class="dialogBorder"></div>
  <b class="bgTL"></b>
  <b class="bgTR"></b>
  <b class="bgBL"></b>
  <b class="bgBR"></b>
  <b class="shadow"></b>
</div>
</body>
</html>
