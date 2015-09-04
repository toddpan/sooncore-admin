<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<base href="{$tag_base_url}"/>
		<base target="_blank" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<!-- 管理员管理 -->
<div class="contHead" style="margin-bottom:20px;">
	<span class="title01">管理员管理</span>
	<div class="contHead-right"><div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu2',event)"></a></div>
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" id="search_admin"></a>
			<label class="label">请输入查询条件</label>
			<input class="input" id="search" />
		</div>
	</div>
    
    <ul class="menu" id="menu2">
            <li style="*margin-top:-10px;padding:0;"><a onclick="loadCont('tag/manageTag' + '/0')" style="padding:0;">员工标签管理</a></li>
          <!--  <li style="*margin-top:-25px"><a onclick="loadCont('ldap/showLdapPage')">LDAP设置</a></li> -->
        </ul>
        
    </div>
</div>
<div class="contTitle02" style="margin-bottom:20px;">
    <a class="btn yes fl btnAddAdmin"><span class="text">新增管理员</span><b class="bgR"></b></a>
    <div class="editBtnBox fl" style="display:none;" id="dete_btn_admin">
        <a class="btnGray btn btnDeleAdmin" ><span class="text">移除管理员</span><b class="bgR"></b></a>
    </div>
    <div class="combo selectBox w130" style="float:right">
        <a class="icon" ></a>
        <span class="text selected">全部管理员</span>
        <div class="optionBox hide">
            <dl class="optionList" >
                <dd id="all_manger" class="option selected" target="0" onclick="select_manger_type(this)">全部管理员</dd>
                <dd class="option" target="1" onclick="select_manger_type(this)">系统管理员</dd>
                <dd class="option" target="2" onclick="select_manger_type(this)">组织管理员</dd>
                <dd class="option" target="3" onclick="select_manger_type(this)">员工管理员</dd>
                <dd class="option" target="4" onclick="select_manger_type(this)">帐号管理员</dd>
                
         <!--       <dd class="option" target="5" onclick="select_manger_type(this)">生态管理员</dd>
				<dd class="option" target="6" onclick="select_manger_type(this)">渠道管理员</dd> -->
            </dl>
        </div>
    </div>
</div>

<script type="text/javascript" src="public/js/part_js/manger.js"></script>
<script type="text/javascript" src="public/js/self_common.js"></script>
</body>
</html>
