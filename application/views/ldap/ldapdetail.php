<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sooncore平台管理中心</title>
</head>
<body>
<!--ldap详细信息.html-->
<div class="contHead">
	<span class="title01 rightLine">组织管理</span><span class="title03">查看LDAP详情</span>
	<div class="contHead-right"><div class="fr rightLine"><a class="btnSet" onclick="toggleMenu('menu1',event)" ></a></div>
	
	<div class="headSearch rightLine">
		<div class="combo searchBox">
			<b class="bgR"></b>
			<a class="icon" ></a>
			<label class="label">请输入查询条件</label>
			<input class="input">
		</div>
	</div>
    <ul class="menu" id="menu1">
        <li><a  onclick="loadCont('组织与员工_批量导入.html');">员工标签管理</a></li>
        <li><a href="index.html">管理员设置</a></li>
        <li><a  onclick="loadCont('ldap列表.html');">LDAP设置</a></li>
    </ul>
    </div>
</div>

<!-- end contHead -->
<div class="toolBar2" style="margin-top: -20px; margin-bottom: 20px;">
	<a class="back fl" onclick="loadCont('ldap/getLdapList');" title="返回">&nbsp;</a>
    <div class="infoTitle fl"><span class="personName">北京分公司LDAP设置</span></div>
	<a ldap_id="<?php echo $ldap_id;?>" class="btnGray fr"  onclick="detail_ldapdel(this);"><span class="text">删除</span><b class="bgR"></b></a>
    <a class="btnBlue fr" onclick="loadCont('<?php echo site_url("ldap/showLdapPage").'?ldap_id='.$ldap_id;?>');"><span class="text">编辑</span><b class="bgR"></b></a>
    
		
	</div>
<dl class="ldapSetCont">
        <dt class="setTitle" style="margin-bottom:5px;">链接LDAP设置</dt>
        <dd>
            <table class="infoTable">
                <tr>
                    <td width="148">服务器类型：</td>
                    <td><?php echo $server_info['servertype'];?></td>
                </tr>
                <tr>
                    <td>连接方式：</td>
                    <td><?php echo $server_info['authtype'];?></td>
                </tr>
                <tr>
                    <td>LDAP服务器地址：</td>
                    <td><?php echo $server_info['hostname'];?></td>
                </tr>
                <tr>
                    <td>LDAP服务器端口：</td>
                    <td>
                        <?php echo $server_info['port'];?>
                    </td>
                </tr>
                <tr>
                    <td>LDAP服务器用户名：</td>
                    <td><?php echo $server_info['admindn'];?></td>
                </tr>
                <tr>
                    <td>LDAP服务器密码：</td>
                    <td><?php echo $server_info['adminpassword'];?></td>
                </tr>
            </table>
        </dd>
        <dt class="setTitle" style="margin:15px 0 5px;">导入的组织列表</dt>
        <dd style="padding: 10px">
            <div class="treeBox">
                <ul style="display:block;">
                    <li>
                        <a class="treeNode" >
                            <b class="treeNodeArrow open"></b>
                          
                            <span class="treeNodeName">DC=全时，DC=com[192.168.12.1]</span>
                        </a>
                        <ul class="subTree" style="display:block;">
                            <li>
                                <a class="treeNode" >
                                    <b class="treeNodeArrow close"></b>
                                  
                                    <span class="treeNodeName">OU=Products Division</span>
                                </a>
                                <ul class="subTree">
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                          
                                            <span class="treeNodeName">研发部</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">市场部</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">营销部</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="treeNode" >
                                    <b class="treeNodeArrow open"></b>
                                   
                                    <span class="treeNodeName">OU=Market</span>
                                </a>
                                <ul class="subTree" style="display:block;">
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                         
                                            <span class="treeNodeName">OU=beijing</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">OU=shanghai</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">OU=shenzhen</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">OU=guangzhou</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                          
                                            <span class="treeNodeName">OU=shandong</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="treeNode" >
                                            <b class="treeNodeArrow"></b>
                                           
                                            <span class="treeNodeName">OU=UE</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="treeNode" >
                                    <b class="treeNodeArrow"></b>
                                 
                                    <span class="treeNodeName">OU=R & D center</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- end tree -->
            </div>
        </dd>
        <dt class="setTitle" style="margin:15px 0 5px;">选择的员工标签</dt>
        <dd>
        	<table class="infoTable">
				<?php foreach($classes as $c):?>
					<tr>
						<td width="148"><?php echo $c;?></td>
						<td>&nbsp;</td>
					</tr>
				<?php endforeach;?>
             </table>
           
        </dd>
       <dt class="setTitle" style="margin:15px 0 5px;">请为您企业选择统一的sooncore平台帐号前缀</dt>
        <dd>
        	<table class="infoTable">
                <tr>
                    <td ><?php echo $account;?></td>
                </tr>
             </table>
           
        </dd>
        <dt class="setTitle" style="margin:15px 0 5px;">不开通全时sooncore平台的例外规则</dt>
        <dd>
        	<table class="infoTable">
				<?php foreach($filter_rule as $f):?>
					<tr>
						<td ><?php echo $f;?></td>
					</tr>
				<?php endforeach;?>
             </table>
        </dd>
    </dl>
    


</body>
</html>
<script type="text/javascript">
function detail_ldapdel(t)
{
	var _this=$(t);
	showDialog('<?php echo site_url("ldap/showDeleteLdapPage2").'?ldap_id='.$ldap_id;?>');
	$("#dialog .btn_confirm").live("click",function()
	{
		var ldap_id=_this.attr("ldap_id");
		var path= "ldap/deleteLdap";
		$.post(path,{ldap_ids:ldap_id},function(data)
		{
			if(data.code==0)
			{
				 loadPage('ldap/getLdapList','group');
				 hideDialog();
			}
			else
				   {
				   		alert(data.prompt_text);
				   }
		},"json")
		
	})
}

</script>









