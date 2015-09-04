<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>管理员帐号开通</title>
</head>

<body>
<table style=" margin:0; padding:0; width:100%; height:100%; background:#f4f4f4;" cellpadding="0" cellspacing="0">
	<tr>
    	<td>
        	<table style="margin:0 auto; width:600px;" align="center">
            	<tr>
                	<td style="font:500 26px/45px \5FAE\8F6F\96C5\9ED1; color:#333;"><img src="<?php echo $blueline;?>" width="9" height="24" style="vertical-align:middle"/> <?php echo $cor_name; // 企业简称?>蜜蜂</td>
                </tr>
            </table>
        	<table cellpadding="0" cellspacing="0" style="margin:0 auto; width:600px;" align="center">
               <tr>
                <td><img src="<?php echo $toparrow; ?>" width="600" height="5" /></td>
               </tr>
            </table>
        	<table cellpadding="0" cellspacing="0" style="margin:0 auto; width:600px;background:#fff; border-left:1px solid #cdcdcd;border-right:1px solid #cdcdcd;" align="center">
                <tr>
                	<td style=" padding:30px;" >
                    	<table cellpadding="0" cellspacing="0" >
                        	<tr>
                            	<td style="font:20px/30px \5FAE\8F6F\96C5\9ED1; color:#24afb2;"><?php echo $user_name; // 收件人姓名?>，您好：</td>
                            </tr>
                            <tr>
                            	<td style=" height:30px;"></td>
                            </tr>
                            <tr>
                            	<td style="font:14px/24px \5FAE\8F6F\96C5\9ED1;">全时已经为您的公司创建了<?php echo $cor_name; // 企业简称 ?>蜜蜂。您的公司即将展开一场连接互联网的旅程。您的管理员帐号已经开通，请您立即登录启用。</td>
                            </tr>
                            <tr>
                            	<td style=" height:20px;"></td>
                            </tr>
                            <tr>
                            	<td style=" padding:25px 0 25px 20px; background:#f4f4f4">
                                	<table cellpadding="0" cellspacing="0" >
                                    	<tr>
                                        	<td style="font:15px/28px \5FAE\8F6F\96C5\9ED1; color:#666">用户姓名：</td>
                                            <td style="font:15px/28px \5FAE\8F6F\96C5\9ED1;"><?php echo $user_name;?></td>
                                        </tr>
                                        <tr>
                                        	<td style="font:15px/28px \5FAE\8F6F\96C5\9ED1; color:#666">用户帐号：</td>
                                            <td style="font:15px/28px \5FAE\8F6F\96C5\9ED1;"><?php echo $login_name; ?></td>
                                        </tr>
                                        <?php if(!empty($password)){?>
                                        <tr>
                                        	<td style="font:15px/28px \5FAE\8F6F\96C5\9ED1; color:#666">初始密码：</td>
                                            <td style="font:15px/28px \5FAE\8F6F\96C5\9ED1;"><?php echo $password; ?></td>
                                        </tr>
                                        <?php }?>
                                        <tr>
                                        	<td colspan="2" height="8">&nbsp;</td>
                                        </tr>
                                        <tr>
                                        	<td colspan="2"><a href="<?php echo $login_link;?>"><img src="<?php echo $loginsub; ?>" width="198" height="50" /></a></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                            	<td style=" height:20px;"></td>
                            </tr>
                            <tr>
                            	<td style="font:13px/24px \5FAE\8F6F\96C5\9ED1; color:#333;">您的行动至关重要！通过您的管理，您公司内的所有同事将亲身见证互联网如何改变我们的工作方式。现在，请您立即行动！</td>
                            </tr>
                            <tr>
                            	<td style=" height:20px;"></td>
                            </tr>
                            <tr>
                            	<td style="font:13px/24px \5FAE\8F6F\96C5\9ED1; color:#333;">顺颂时祺</td>
                            </tr>
                            <tr>
                            	<td style="font:bold 13px/24px \5FAE\8F6F\96C5\9ED1; color:#333;">全时蜜蜂小组</td>
                            </tr>
                        </table>
                    
                    </td>
                </tr>
            </table>
        	<table cellpadding="0" cellspacing="0" style="margin:0 auto; width:600px;" align="center">
               <tr>
                <td><img src="<?php echo $botarrow; ?>" width="600" height="15" /></td>
               </tr>
            </table>
        	<table cellpadding="0" cellspacing="0" style="margin:0 auto; width:600px;" align="center">
               <tr>
               		<td><img src="<?php echo $powerby; ?>" width="125" height="20" /></td>
                    <td style="font:12px/24px \5FAE\8F6F\96C5\9ED1; color:#333; text-align:right;"><img src="<?php echo $tel; ?>" width="14" height="14" style="vertical-align:middle;" /> 400-8103657 (24小时服务)</td>
               </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
