<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<base href="<?php echo site_url();?>"/>
    <base target="_blank" />
	<title>蜜蜂管理中心</title>
	<link href="public/css/common.css" rel="stylesheet" />
	<link href="public/css/self_common.css" rel="stylesheet" />
	<link href="public/css/datepicker.css" rel="stylesheet" />
	<link href="public/css/tree.css" rel="stylesheet" />
	<link href="public/css/ldap.css" rel="stylesheet" />
	<link href="public/css/jquery.jscrollpane.css" rel="stylesheet" />
	<link href="public/css/jquery.jqplot.css" rel="stylesheet" />
	<link href="public/zTreeStyle/zTreeStyle.css" rel="stylesheet" />
	<link href="public/css/base.css" rel="stylesheet" />
	
	<script type="text/javascript" src="public/js/jquery.js"></script>
	<script type="text/javascript" src="public/js/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="public/js/jquery.tree.js"></script>
	<script type="text/javascript" src="public/js/json2.js"></script>
	<script type="text/javascript" src="public/js/common.js"></script>
	<script type="text/javascript" src="public/js/self_common.js"></script>
	<script type="text/javascript" src="public/js/qs.core.js"></script>
	<script type="text/javascript" src="public/js/qs.controls.js"></script>
	<script type="text/javascript">
		window.baseUrl = "<?php echo site_url();?>";
	</script>
</head>
<body>
<div class="pageBody">
  <div class="header">
  	<a class="logo" href="main/index" target="_self" style="text-decoration:none;line-height:5em;">
<!--   	<img src="public/images/logo.png" /> -->
  	<span style="font-size:30px;position:positive;margin-left:20px;color:#E8EDEA;" id="carName"><b><?php echo $corName; ?></b>
  	</span><span style="font-size:18px;position:positive;color:#A5ACAC;">管理中心</span>
  	</a>
    <!-- end logo -->
    <ul class="headerLink">
      <li class="hlItem"> 
		 <a class="user" >
			  <span class="text" style="cursor: pointer;height: 18px;line-height: 18px;" ><?php echo $displayName ; ?></span>
			  <span class="icon"style="cursor: pointer" ></span>
		</a>
		<dl class="menu" style="width: 86px; clear:both;">
			<?php if($this->functions['ChangePassword']){ ?>
		  	<dt><a class="changePwd"  style="cursor: pointer">修改密码</a></dt>
		  	<?php }?>
		  	<dt><a href="login/logout" style="cursor: pointer" target="_self">注销</a></dt>
		</dl>
      </li>
<!--       <li class="hlItem"> -->
<!--  	 	 <a class="email" > -->
<!--			 <span class="text" style="cursor: pointer">消息</span>-->
<!--  			 <span class="icon nums"><b style="cursor: pointer"><?php //echo $msg_sum ; ?></b></span> -->
<!-- 		 </a> -->
<!-- 		 <span class="hArrow"></span> -->
<!-- 	  </li>  -->
	  	<?php if($this->functions['ManagerManage']){?>
		<li class="hlItem">
			<a class="admin" ><span class="text">管理员管理</span></a>
			<span class="hArrow"></span>
		</li>
		<?php }?>
<!--       <li class="hlItem"> 
		  <a class="help" ><span class="text" style="cursor: pointer">帮助中心</span></a>
<!-- 		  <span class="hArrow"></span> -->
<!-- 	  </li> -->
    </ul>
    <!-- end headerLink -->
  </div>
  <!-- end header -->
  <div class="content clearfix">
    <ul class="leftMenu">
      <li class="selected"><a class="main" onclick="limit_click(this,'main/mainPage');"><span class="icon" style="cursor: pointer">首页</span></a></li>
	  <?php if($this->functions['OrgManage']){?>
      <li><a class="group"  onclick="limit_click(this,'organize/OrgList');" style="cursor: pointer"><span class="icon">组织管理</span></a></li>
	  <?php }?>
	  <?php if($this->functions['EcologyCompany']){?>
      <!--  <li><a class="company"  onclick="limit_click(this,'ecologycompany/ecologyPage')" style="cursor: pointer"><span class="icon">企业生态</span></a></li> -->
      <?php }?>
      <li><a class="app"  onclick="limit_click(this,'app/app_list');" style="cursor: pointer"><span class="icon">应用管理</span></a> </li>
     <?php if($this->functions['SecurityManage']){?>
      <li><a class="safe"  onclick="limit_click(this,'password/PWDManagePage');" style="cursor: pointer"><span class="icon">安全管理</span></a> </li>
     <?php }?>
     <?php if($this->functions['SystemSetting']){?>
      <li><a class="system"  onclick="limit_click(this,'systemset/company');" style="cursor: pointer" ><span class="icon">系统设置</span></a></li>
	 <?php }?>
    </ul>
    <!-- end leftMenu -->
    <div id="ri_main" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
    <div id="ri_group" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
    <!-- 
    <div id="ri_company" class="rightCont clearfix hide">
    </div>
     -->
     <div id="ri_app" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
    <div id="ri_safe" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
    <div id="ri_system" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
    <div id="ri_admin" class="rightCont clearfix hide">
      <!-- 此处内容为ajax加载 -->
    </div>
  </div>
  <!-- end content -->
  <div class="footer">
	  <span class="text rightLine">
	  <?php echo COMPANY_NAME;?> 
	  <?php echo COMPANY_COPR;?> 
	  <?php echo COMPANY_ENG_NAME;?>
	  <?php echo COMPANY_ICP;?>
	  </span>
	  <span class="text">24小时服务热线：<?php echo COMPANY_SERVE_TEL;?></span>
  </div>
  <!-- end footer -->
</div>
<div class="mask"></div>
<div id="dialog" class="dialog">
  <div class="dialogBorder"></div>
  <!--
  <b class="bgTL"></b>
  <b class="bgTR"></b>
  <b class="bgBL"></b>
  <b class="bgBR"></b>
  -->
  <b class="shadow"></b>
</div>
<script type="text/javascript">
function limit_click(t, url) {
	var className = $(t).attr('class');	//表示当前执行点击事件的页签类
	var hash = location.hash.substring(1);	//上一个hash页签的值
	//如果当前标签不是已经选中的标签页或者是刷新页面，则执行操作，否则不执行操作
	if(className != hash || $(t).parent().attr('id') != 'has_click' ) {
		loadPage(url, className);	//根据当前点击的页签，通过ajax加载相应的页面
		var parentId = $(t).parent().attr('id');
		(parentId == undefined) ? $(t).parent().attr('id', 'has_click') : '';
	    
		if($('.headerLink .bg').length>0)
		{
			$('.headerLink .bg').removeClass("bg");
		}
	}
	return ;
}

$(function(){	
		var hash = location.hash.substring(1);
		
		switch(hash){
			case "":				
				loadPage('main/mainPage','main');
				break;
			case "msg3":
				loadCont("information/infoManPage");
				$('.leftMenu > li').removeClass('selected');
				break;
			case "admin":
				loadPage('组织与员工-管理员.html','group');
				break;
			case "companyAdmin":
				loadCont('ecologycompany/ecologyInfoPage');
				$('.leftMenu  li').eq(2).addClass('selected').siblings().removeClass('selected');
				break;
			case "app":
				loadPage('app/app_list', 'app');
				break;
			default:
				$(".leftMenu a."+hash).trigger("click");
		}
	
		$('.headerLink .email').click(function(){
			$('.leftMenu > li').removeClass('selected');
			loadPage("information/infoManPage","msg3");
		});		

	});
	
	$('.changePwd').click(function(){
		$('.leftMenu > li').removeClass('selected');
		loadCont("mixture/resetPwd");
	});
	//帮助中心
	$('.headerLink .help').click(function(){
		$('.leftMenu > li').removeClass('selected');
		loadCont("mixture/showHelpCenter");
	});
	//管理员管理
	$('.headerLink .admin').click(function(){
		$('.leftMenu > li').removeClass('selected');
		loadCont("manager/listManagerPage");
	});


	// 管理员列表中的选择框
	$('#self_staff thead span.checkbox').die().live('click',
		    function() {
				//alert(12)
		        if ($(this).hasClass("checked")) {
		            $(this).removeClass("checked");
		            $(' #self_staff tbody span.checkbox').removeClass("checked");
		            $(" #dete_btn_admin").hide();
		        } else {
		            $(this).addClass("checked");
		            $(' #self_staff tbody span.checkbox').addClass("checked");
		            $(" #dete_btn_admin").show();
		        }
		    }) 
			$('#self_staff tbody span.checkbox').die().live('click',
		    function() {
		        //alert(1)
		        if ($(this).hasClass("checked")) //选中的则去除
		        {
		            $(this).removeClass("checked");
		            $('#self_staff thead span.checkbox').removeClass("checked");
		            //alert($(' #self_staff tbody tr td span.checked').length);
		            if ($('#self_staff tbody tr td span.checked').length == 0) {

		                $(" #dete_btn_admin").hide();
		                //alert(2)
		            } else {
		                $(" #dete_btn_admin").show();
		                //alert(3)
		                //alert(222)
		                //return false;
		            }
		            //$(' #self_staff tbody label.checkbox').removeClass("checked");
		        } else //去除的，则变为选中
		        {
		            $(this).addClass("checked");
		            $(" #dete_btn_admin").show();
		            //alert(4)
		            // alert($(' #self_staff tbody tr td span.checked').length);
		            // alert($(' #self_staff tbody tr td span.checkbox').length);
		            if ($(' #self_staff tbody tr td span.checked').length == $(' #self_staff tbody tr td span.checkbox').length) {
		                $(' #self_staff thead span.checkbox').addClass("checked");
		                //alert(5)
		            } else {
		                $(' #self_staff thead span.checkbox').removeClass("checked");
		                //alert(6)
		                ///return false;
		            }
		            //$(' #self_staff tbody label.checkbox').addClass("checked");
		        }
		    })
		    
// 		    $('#menu_manager li:eq(0) a').click(function(){
// 		    	loadCont('tag/addTagPage/0');
// 		    });
	
// 	$( ".pageBody .content" ).on( "click", "#ri_system .nav02 li:eq(0)", function() {
// 		 $(".nav02 li").removeClass("selected");
//         $(this).addClass("selected");
//         $('.company_infor').show();

//         $('.groupLimit2').hide();
// 	});

$( ".pageBody .content" ).on( "click", "#ri_system .nav02 li:eq(0)", function() {
 			$(".nav02 li").removeClass("selected");
            $(this).addClass("selected");
            $('.groupLimit2').hide();
            $('.styleset').hide();
            $('.js-notice-set').hide();
            $('.company_infor').show();
});

$(".pageBody .content" ).on( "click", "#ri_system .nav02 li:eq(1)", function() {
	$(".nav02 li").removeClass("selected");
    $(this).addClass("selected");
    $('.company_infor').hide();
    $('.styleset').hide();
    $('.js-notice-set').hide();

    var obj=[];
    var path_power= "setsystem/get_sys_power";
    $.post(path_power,obj,function(data)
    {
        var value= $.parseJSON(data);
        value=value.other_msg.power;
        va_post=value;
        org_user_right(value);
        //save_show(va_post,count);
    });
    $('.groupLimit2').show();
});

$( ".pageBody .content" ).on( "click", "#ri_system .nav02 li:eq(2)", function() {
    $(".nav02 li").removeClass("selected");
    $(this).addClass("selected");
    $('.company_infor').hide();
    $('.groupLimit2').hide();
    $('.js-notice-set').hide();
    var obj=[];
    var path_power= "systemset/get_isldap";
    $.post(path_power,obj,function(data)
    {
		//alert(data);
        var value = $.parseJSON(data);
        var isldap=value.other_msg.isLDAP;
        default_isldap = isldap;
        var server_info=value.other_msg.server_info;
        var import_info=value.other_msg.import_info;
        //alert(isldap);
        getLdap(isldap, server_info);
        getImport(import_info);
    });
    $('.styleset').show();
});

//通知设置
$( ".pageBody .content" ).on( "click", "#ri_system .nav02 li:eq(3)", function() {
    $(".nav02 li").removeClass("selected");
    $(this).addClass("selected");

    $('.company_infor').hide();
    $('.groupLimit2').hide();
    $('.styleset').hide();

    var obj=[];
    var path_power= "systemset/get_notice_set";
    $.post(path_power,obj,function(data)
    {
    	data=eval('('+data+')');
        //alert(data.data.accountNotifyEmail);
    	get_inform_set(data);
    	default_passwordNotifyWord = data.data.password_existing_prompt;
    	default_accountDefaultPassword = data.data.accountDefaultPassword;
    	//alert(default_passwordNotifyWord);
    });
    $('.js-notice-set').show();
});
</script>
</body>
</html>
