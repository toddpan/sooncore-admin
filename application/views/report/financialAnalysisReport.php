<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo base_url('public/css/jquery.jscrollpane.css');?>" />
</head>

<body>
<!--报告管理_财务分析报告.html-->
<div class="contHead" style="border-bottom: none; margin-bottom: 0">
	<span class="title01">报告管理</span>
	<ul class="nav02">
		<li class="first selected"><a >财务分析报告</a></li>
		<li><a >帐号分析报告</a></li>
		<li><a >使用行为报告</a></li>
        <li class="last"><a >生态热点报告</a></li>	
	</ul>
    <div class="report-time">
    	<span class="text01 fl">日期筛选： 从</span>
        <div class="inputBox dp fl" style="margin: 11px 5px 0">
            <a class="icon" ></a>
            <label class="label"></label>
            <input id="startTime" class="input" readonly="readonly" style="width: 100px;" />
        </div>
        <span class="text01 fl">到</span>
        <div class="inputBox dp fl" style="margin: 11px 5px 0">
            <span class="icon"></span>
            <label class="label"></label>
            <input id="endTime" class="input" readonly="readonly" style="width: 100px;" />
        </div>
        <a  class="btn yes fl"><span class="text" style="margin-right:0">&nbsp;确定&nbsp;</span><b class="bgR"></b></a>
    </div>
	
</div>
<!-- end contHead -->

<div class="cont-wrapper" style="display: block">
	<ul class="infoNav">
        <li class="last"><a  class="btn yes fr"><span class="text">导出报告</span><b class="bgR"></b></a></li>
    </ul>
  <div class="report-content">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">并发坐席费</span>
                <span class="chart-b">通信费</span>
            </div>
        </div>
        
        <div class="dataBox">
            <table>
                <tr>
                    <td class="rightLine03">
                        <span class="text01">并发坐席费</span>
                        <span class="text02">21,234.5</span>
                    </td>
                    <td class="rightLine03">
                        <span class="text01">通信费</span>
                        <span class="text02">52,667.5</span>
                    </td>
                    <td>
                        <span class="text01">总计</span>
                        <span class="text02">978,402</span>
                    </td>
                </tr>
            </table>
        </div>
        
   		 <div class="chart-box">
   	  		<img src="<?php echo base_url('public/images/chart04.jpg');?>" width="691" height="309" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>通信费(元)</th>
                    <th>并发坐席费(元)</th>
                    <th>所有费用(元)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                    <td>2234.45</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
</div>

<div class="cont-wrapper" style="display: none">
    <ul class="infoNav">
        <li class="first selected">帐号变化情况</li>
        <li>新增帐号使用情况</li>
        <li>已有帐号使用情况</li>
        <li>帐号活跃情况</li>
        <li class="last"><a  class="btn yes fr"><span class="text">导出报告</span><b class="bgR"></b></a></li>
    </ul>
	<div class="report-content" style="display: block">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">新增的帐号</span>
                <span class="chart-b">关闭的帐号</span>
            </div>
        </div>
        
   		 <div class="chart-box">
   	  		<img src="<?php echo base_url('public/images/chart05.jpg');?>" width="694" height="310" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>新增帐号</th>
                    <th>关闭帐号</th>
                    <th>总帐号</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>5</td>
                    <td>0</td>
                    <td>55</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>3</td>
                    <td>1</td>
                    <td>43</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>2</td>
                    <td>4</td>
                    <td>63</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>4</td>
                    <td>4</td>
                    <td>84</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>6</td>
                    <td>0</td>
                    <td>65</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>2</td>
                    <td>1</td>
                    <td>36</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>22</td>
                    <td>10</td>
                    <td>322</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
  	<div class="report-content" style="display: none">
        <div class="con-bar"> 
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">使用</span>
                <span class="chart-b">未使用</span>
                
                <a  class="btn" style="top: 5px;" onclick="sendAlertMsg(this)"><span class="text" style="width: 130px;">给未使用帐号发送提醒</span><b class="bgR"></b></a>
            </div>
        </div>
        
   		 <div class="chart-box">
   	  		<img src="<?php echo base_url('public/images/chart05.jpg');?>" width="694" height="310" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>使用</th>
                    <th>未使用</th>
                    <th>新增帐号总数</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>5</td>
                    <td>0</td>
                    <td>55</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>3</td>
                    <td>1</td>
                    <td>43</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>2</td>
                    <td>4</td>
                    <td>63</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>4</td>
                    <td>4</td>
                    <td>84</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>6</td>
                    <td>0</td>
                    <td>65</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>2</td>
                    <td>1</td>
                    <td>36</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>22</td>
                    <td>10</td>
                    <td>322</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
 	<div class="report-content" style="display: none">
        <div class="con-bar"> 
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">使用</span>
                <span class="chart-b">未使用</span>
                
                <a  class="btn" style="top: 5px;"  onclick="sendAlertMsg(this)"><span class="text" style="width: 130px;">给未使用帐号发送提醒</span><b class="bgR"></b></a>
            </div>
        </div>
        
   		 <div class="chart-box">
   	  		<img src="<?php echo base_url('public/images/chart05.jpg');?>" width="694" height="310" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>使用</th>
                    <th>未使用</th>
                    <th>已有帐号总数</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>5</td>
                    <td>0</td>
                    <td>55</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>3</td>
                    <td>1</td>
                    <td>43</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>2</td>
                    <td>4</td>
                    <td>63</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>4</td>
                    <td>4</td>
                    <td>84</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>6</td>
                    <td>0</td>
                    <td>65</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>2</td>
                    <td>1</td>
                    <td>36</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>22</td>
                    <td>10</td>
                    <td>322</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
  	<div class="report-content" style="display: none">
        <div class="con-bar"> 
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">活跃帐号：60 分钟（含）以上/每天</span>
                <span class="chart-b">不活跃帐号：60 分钟以下/每天</span>
                
                <a  class="btn" style="top:5px;"  onclick="sendAlertMsg(this)"><span class="text" style="width: 130px;">给不活跃帐号发送提醒</span><b class="bgR"></b></a>
            </div>
        </div>
        
   		 <div class="chart-box">
   	  		<img src="<?php echo base_url('public/images/chart05.jpg');?>" width="694" height="310" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>活跃</th>
                    <th>不活跃</th>
                    <th>总帐号</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>5</td>
                    <td>0</td>
                    <td>55</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>3</td>
                    <td>1</td>
                    <td>43</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>2</td>
                    <td>4</td>
                    <td>63</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>4</td>
                    <td>4</td>
                    <td>84</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>6</td>
                    <td>0</td>
                    <td>65</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>2</td>
                    <td>1</td>
                    <td>36</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>22</td>
                    <td>10</td>
                    <td>322</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
</div>

<div class="cont-wrapper" style="display: none">
    <ul class="infoNav">
        <li class="first selected">在线时长</li>
        <li>通信使用比例</li>
        <li>通信使用时长</li>
        <li>会议次数</li>
        <li>入会方式</li>
        <li class="last"><a  class="btn yes fr"><span class="text">导出报告</span><b class="bgR"></b></a></li>
    </ul>
	
    <div class="report-content" style="display: block">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
                <span class="sl"></span>
                <div class="select selectOffer"><span>全部职位</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">PC客户端在线时长</span>
                <span class="chart-b">移动客户端在线时长</span>
            </div>
        </div>
        
   		 <div class="chart-box">
  		   <img src="<?php echo base_url('public/images/chart06.jpg');?>" width="694" height="309" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>5月</th>
                    <th>6月</th>
                    <th>7月</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>全部职位</td>
                    <td>0</td>
                    <td>5</td>
                  <td>55</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>全部职位</td>
                    <td>1</td>
                    <td>3</td>
                    <td>43</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>全部职位</td>
                    <td>4</td>
                    <td>2</td>
                    <td>63</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>全部职位</td>
                    <td>4</td>
                    <td>4</td>
                    <td>84</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>全部职位</td>
                    <td>0</td>
                    <td>6</td>
                    <td>65</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>全部职位</td>
                    <td>1</td>
                    <td>2</td>
                    <td>36</td>
                </tr>
                <tr class="fb">
                    <td>总计</td>
                    <td>全部职位</td>
                    <td>10</td>
                    <td>22</td>
                    <td>322</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
  	<div class="report-content" style="display: none">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
                <span class="sl"></span>
                <div class="select selectOffer"><span>全部职位</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">电话</span>
                <span class="chart-b">电脑语音</span>
                <span class="chart-c">即时会议</span>
                <span class="chart-d">预约会议</span>
            </div>
        </div>
        
   		 <div class="chart-box">
  		   <img src="<?php echo base_url('public/images/chart07.jpg');?>" width="816" height="247" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>电话</th>
                    <th>电脑语音</th>
                    <th>即时会议</th>
                    <th>预约会议</th>
                    <th>总计</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>全部职位</td>
                    <td>2%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>全部职位</td>
                    <td>5%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>全部职位</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>全部职位</td>
                    <td>15%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>全部职位</td>
                    <td>13%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>全部职位</td>
                    <td>5%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                    <td>10%</td>
                </tr>
                <tr class="fb">
                    <td colspan="2">以上部门合计</td>
                    <td>50%</td>
                    <td>60%</td>
                    <td>60%</td>
                    <td>60%</td>
                    <td>60%</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
  	<div class="report-content" style="display: none">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
                <span class="sl"></span>
                <div class="select selectOffer"><span>全部职位</span></div>
                
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">2方</span>
                <span class="chart-b">3-5方</span>
                <span class="chart-c">6-10方</span>
                <span class="chart-d">11方以上</span>
            </div>
        </div>
        
   		 <div class="chart-box">
  		   <img src="<?php echo base_url('public/images/chart08.jpg');?>" width="675" height="301" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>2方</th>
                    <th>3-5方</th>
                    <th>6-10方</th>
                    <th>11方以上</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>全部职位</td>
                    <td>2</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>全部职位</td>
                    <td>5</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td>市场部</td>
                    <td>全部职位</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td>营销部</td>
                    <td>全部职位</td>
                    <td>15</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td>客服部</td>
                    <td>全部职位</td>
                    <td>13</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr>
                    <td>商务部</td>
                    <td>全部职位</td>
                    <td>5</td>
                    <td>10</td>
                    <td>10</td>
                    <td>10</td>
                </tr>
                <tr class="fb">
                    <td colspan="2">以上部门合计</td>
                    <td>50</td>
                    <td>60</td>
                    <td>60</td>
                    <td>60</td>
                </tr>
            </tbody>
        </table>
		</div>
  </div>
  	<div class="report-content" style="display: none">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
                <span class="sl"></span>
                <div class="select selectOffer"><span>全部职位</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">每人平均预约会议数</span>
                <span class="chart-e">整体用户使用日历比例</span>
            </div>
        </div>
        
   		 <div class="chart-box">
  		   <img src="<?php echo base_url('public/images/chart09.jpg');?>" width="667" height="275" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>每人平均预约会议数</th>
                    <th>整体用户使用日历比例</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>全部职位</td>
                    <td>90</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>全部职位</td>
                    <td>564</td>
                    <td>40%</td>
                </tr>
                <tr class="fb">
                    <td colspan="2">以上部门合计</td>
                    <td>654</td>
                    <td>50%</td>
                </tr>
            </tbody>
        </table>
	  </div>
  </div>
 	<div class="report-content" style="display: none">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectGroup"><span>全部组织</span></div>
                <span class="sl"></span>
                <div class="select selectOffer"><span>全部职位</span></div>
            </div>
            <div class="con-bar-right">
            	<span class="chart-a">从聊天框</span>
                <span class="chart-b">从日历</span>
                <span class="chart-c">从移动客户端</span>
            </div>
        </div>
        
   		 <div class="chart-box">
  		   <img src="<?php echo base_url('public/images/chart10.jpg');?>" width="645" height="290" alt="" /> 
        </div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>从聊天框</th>
                    <th>从日历</th>
                    <th>从移动客户端</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>总经办</td>
                    <td>全部职位</td>
                    <td>20%</td>
                    <td>8%</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>研发部</td>
                    <td>全部职位</td>
                    <td>40%</td>
                    <td>9%</td>
                    <td>13%</td>
                </tr>
                <tr class="fb">
                    <td colspan="2">以上部门合计</td>
                    <td>60%</td>
                    <td>17%</td>
                    <td>23%</td>
                </tr>
            </tbody>
        </table>
    </div>
  </div>
</div>

<div class="cont-wrapper" style="display: none">
	<ul class="infoNav">
        <li class="last"><a  class="btn yes fr"><span class="text">导出报告</span><b class="bgR"></b></a></li>
    </ul>
  <div class="report-content">
        <div class="con-bar">
            <div class="con-bar-left">
            	<div class="select selectCompany"><span>全部生态</span></div>
            </div>
           
        </div>
        
        <div class="dataBox">
            <table>
                <tr>
                    <td class="rightLine03">
                        <span class="text01">生态企业累计总数</span>
                        <span class="text02">132</span>
                    </td>
                    <td class="rightLine03">
                        <span class="text01">本月新增企业数</span>
                        <span class="text02">5</span>
                    </td>
                    <td>
                        <span class="text01">本月离开企业数</span>
                        <span class="text02">2</span>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="dataBox">
            <table>
                <tr>
                    <td class="rightLine03">
                        <span class="text01">生态用户总数</span>
                        <span class="text02">34253</span>
                    </td>
                    <td class="rightLine03">
                        <span class="text01">本月新增生态用户数</span>
                        <span class="text02">2121</span>
                    </td>
                    <td class="rightLine03">
                        <span class="text01">本月离开生态用户数</span>
                        <span class="text02">455</span>
                    </td>
                    <td>
                        <span class="text01">本月生态用户调岗数</span>
                        <span class="text02">122</span>
                    </td>
                </tr>
            </table>
        </div>
        
   		<div class="contTitle" style="margin-bottom: 10px; margin-top: 20px;"><span class="text">下级生态详情</span></div>
        
        <div class="dataTable">
        <table class="table">
            <thead>
                <tr>
                    <th>生态企业</th>
                    <th>生态用户数</th>
                    <th>本月新增用户</th>
                    <th>本月删除用户</th>
                    <th>本月调岗用户</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>北京分公司</td>
                    <td>2234</td>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                </tr>
                 <tr>
                    <td>上海分公司</td>
                    <td>2234</td>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                </tr>
                 <tr>
                    <td>成都分公司</td>
                    <td>2234</td>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                </tr>
                 <tr>
                    <td>河北分公司</td>
                    <td>2234</td>
                    <td>2</td>
                    <td>2</td>
                    <td>2</td>
                </tr>
               
            </tbody>
        </table>
		</div>
  </div>
</div>

<div class="pop-box" id="dateSelectedBox" style="display: none">
	<span class="pop-arrow"></span>
    <div class="radio-group">
    	
    	  <label class="radio">
    	    <input type="radio" name="reportTime" value="0" id="reportTime_0" />
    	    最近3个月（<span class="r_startTime">2013/06/02</span> - <span class="r_endTime">2013/09/02</span>）</label>
    	  
    	  <label class="radio checked">
    	    <input type="radio" name="reportTime" value="1" checked="checked" id="reportTime_1" />
    	    自定义时间</label>

    </div>
    <div class="datepickers">
    	
    </div>
    <div class="pop-box-footer">
    	<a  class="btn yes" onclick="closeDatepicker();"><span class="text">确定</span><b class="bgR"></b></a> &nbsp;
        <a  class="btn" onclick="closeDatepicker();"><span class="text">取消</span><b class="bgR"></b></a>
    </div>
</div>

<div class="pop-box" id="allGroup" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	
    </div>
</div>

<div class="pop-box" id="allCompany" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
    	
    </div>
</div>

<div class="pop-box" id="allOffer" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
        <dl class="offer-list">
        	<dd>全部职位</dd>
        	<dt>A</dt>
            <dd>ASP工程师</dd>
            <dd>安全专员</dd>
            <dd>安装监理</dd>
            <dt>B</dt>
            <dd>保险专员</dd>
            <dd>编导</dd>
            <dd>办公主任</dd>
            <dt>C</dt>
            <dd>财务经理</dd>
            <dd>策划</dd>
            <dd>产品专员</dd>
            <dd>采购专员</dd>
        </dl>
    </div>
</div>

<div class="pop-box" id="allNums" style="display: none">
	<span class="pop-arrow"></span>
    <div class="pop-box-content">
        <dl class="offer-list">
        	<dd>全部方数</dd>
            <dd>2方</dd>
            <dd>3-5方</dd>
            <dd>6-10方</dd>
            <dd>11方以上</dd>
        </dl>
    </div>
</div>
<script type="text/javascript" src="js/jquery.jscrollpane.min.js"> </script>

<script type="text/javascript">
	function sendAlertMsg(t) {
		if($(t).find(".text").text() == "已发送使用提醒") {
			return false;	
		}
		$(t).find(".text").text("发送中，请稍候...");
		setTimeout(function(){
			$(t).find(".text").text("已发送使用提醒");
		},2000);	
	}
	
	var treeNodeText = [
		{'text':'海尔手机电子事业部', 'children':[{'text':'研发部'},{'text':'市场部'},{'text':'营销部'}]},
		{'text':'海尔生活家电事业部'},
		{'text':'海尔电脑事业部'}
	];
	
	function createNode(){
		  var root = {
			"id" : "0",
			"text" : "海尔",
			"value" : "86",
			"showcheck" : false,
			"complete" : true,
			"isexpand" : true,
			"checkstate" : 1,
			"hasChildren" : true
		  };
		  var arr = [];
		  for(var i=0;i<treeNodeText.length; i++){
			var subarr = [];
			if(treeNodeText[i]['children']){
				for(var j=0;j<treeNodeText[i]['children'].length;j++){
				  var value = "node-" + i + "-" + j; 
				  subarr.push( {
					 "id" : value,
					 "text" : treeNodeText[i]['children'][j]['text'],
					 "value" : value,
					 "showcheck" : false,
					 complete : true,
					 "isexpand" : false,
					 "checkstate" : 1,
					 "hasChildren" : false
				  });
				}
			}
			arr.push( {
			  "id" : "node-" + i,
			  "text" : treeNodeText[i]['text'],
			  "value" : "node-" + i,
			  "showcheck" : false,
			  "complete" : true,
			  "isexpand" : true,
			  "checkstate" : 1,
			  "hasChildren" : subarr.length?true:false,
			  "ChildNodes" : subarr
			});
		  }
		  root["ChildNodes"] = arr;
		  return root; 
		}
	var treeNodeText2 = [
		{'text':'北京分公司'},
		{'text':'西安分公司'},
		{'text':'上海分公司'}
	];
	function createNode2(){
		  var root = {
			"id" : "C0",
			"text" : "北京创想空间商务通信服务有限公司",
			"value" : "86",
			"showcheck" : false,
			"complete" : true,
			"isexpand" : true,
			"checkstate" : 1,
			"hasChildren" : true
		  };
		  var arr = [];
		  for(var i=0;i<treeNodeText2.length; i++){
			var subarr = [];
			if(treeNodeText2[i]['children']){
				for(var j=0;j<treeNodeText2[i]['children'].length;j++){
				  var value = "c-node-" + i + "-" + j; 
				  subarr.push( {
					 "id" : value,
					 "text" : treeNodeText2[i]['children'][j]['text'],
					 "value" : value,
					 "showcheck" : false,
					 complete : true,
					 "isexpand" : false,
					 "checkstate" : 1,
					 "hasChildren" : false
				  });
				}
			}
			arr.push( {
			  "id" : "c-node-" + i,
			  "text" : treeNodeText2[i]['text'],
			  "value" : "node-" + i,
			  "showcheck" : false,
			  "complete" : true,
			  "isexpand" : true,
			  "checkstate" : 1,
			  "hasChildren" : subarr.length?true:false,
			  "ChildNodes" : subarr
			});
		  }
		  root["ChildNodes"] = arr;
		  return root; 
		}
		
	$(function(){
		
		var treedata = [createNode()];
		var treedata2 = [createNode2()];
				
		$("#allGroup .pop-box-content").treeview({
			showcheck:true,
			data:treedata
		});
		$("#allCompany .pop-box-content").treeview({
			showcheck:true,
			data:treedata2
		});
		
		$(".selectGroup").click(function(event){
			$(".pop-box").hide();
			$("#allGroup").toggle();
			event.stopPropagation();
		})
		
		$(".selectCompany").click(function(event){
			$(".pop-box").hide();
			$("#allCompany").toggle();
			event.stopPropagation();
		})
		
		$(".selectOffer").click(function(event){
			$(".pop-box").hide();
			$("#allOffer").toggle();
			event.stopPropagation();
		})
		
		$("#allOffer .pop-box-content").jScrollPane();
		
		$(".selectNums").click(function(event){
			$(".pop-box").hide();
			$("#allNums").toggle();
			event.stopPropagation();
		})
		
		$(document).click(function(){
			$("#allGroup").hide();
			$("#allCompany").hide();
			$("#allOffer").hide();
			$("#allNums").hide();
			//$(".datepickers").empty();	
		})
		
		
		var now = new Date();
		//$('#startTime').datepicker('setDate', date ) ;
		
		var dates = $('#startTime, #endTime').datepicker({
			dateFormat: 'yy-mm-dd',
			dayNamesMin: ['日','一','二','三','四','五','六'],
			changeMonth: false,
			maxDate: '+0d',
			numberOfMonths: 1,
			onSelect: function(selectedDate) {
				var option = this.id == "startTime" ? "minDate" : "maxDate";
				var instance = $(this).data("datepicker");
				var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
				dates.not(this).datepicker("option", option, date);
			}
		});
		$('#endTime').datepicker('setDate', '+0d' ) ;
		$('#startTime').datepicker('setDate', '-3m' ) ;
		
		
		$(".nav02 li").click(function(){
			var index = $(this).index();
			$(this).addClass("selected").siblings().removeClass("selected");
			$(".cont-wrapper").eq(index).show().siblings(".cont-wrapper").hide();	
		})
		
		$(".infoNav li").click(function(){
			
			var index = $(this).index();
			var len = $(this).parent("ul").children().length;
			
			if(index<len-1) {
			$(this).addClass("selected").siblings().removeClass("selected");
			var rc = $(this).parents(".cont-wrapper").find(".report-content");
			rc.eq(index).show().siblings(".report-content").hide();	
			}
		})
		
		/*$(".report-time").click(function(event){
			$("#allGroup").hide();
			if($("#dateSelectedBox").is(":visible")){
				$("#dateSelectedBox").hide();
				$('.datepickers').empty();
			}
			else {
				$("#dateSelectedBox").show();
				if($("input[name = 'reportTime']:checked").val()=="0"){
					$('.datepickers').empty();
				}
				else {
					$('.datepickers').DatePicker({
						flat: true,
						date: [rstartTime, rendTime],
						current: rendTime,
						format: 'Y/m/d',
						calendars: 3,
						mode: 'range',
						onRender: function(date) {
							return {
								disabled: (date.valueOf() > now.valueOf())
								//className: date.valueOf() == now2.valueOf() ? 'datepickerSpecial' : false
							}
						},
						onChange: function(formated, dates) {
						},
						starts: 0
					});	
				}
			}
			event.stopPropagation();
		})
		
		$("input[name = 'reportTime']").click(function(){
			if($(this).val()=="0"){
				$(".datepickers").empty();
			}
			else {
				$(".datepickers").empty().DatePicker({
						flat: true,
						date: [rstartTime, rendTime],
						current: rendTime,
						format: 'Y/m/d',
						calendars: 3,
						mode: 'range',
						onRender: function(date) {
							return {
								disabled: (date.valueOf() > now.valueOf())
								//className: date.valueOf() == now2.valueOf() ? 'datepickerSpecial' : false
							}
						},
						onChange: function(formated, dates) {
						},
						starts: 0
					});	
			}
			//event.stopPropagation();
		})
		*/
		$(".offer-list dd").hover(function(){
			$(this).addClass("hover");	
		},function(){
			$(this).removeClass("hover");	
		})
		
	});
</script>
</body>
</html>