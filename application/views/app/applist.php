<div id="appManage">
	<div class="contHead">
		<span class="title01" style="padding: 0px">应用管理</span>
		<span class="title02" style="padding: 0px"></span>
	</div>
	<div class="contTitle02" style="margin-bottom:20px;">
	    <a class="btn yes" style="float: right" id="add_app"><span class="text">新增应用</span><b class="bgR"></b></a>
	</div>
	<div class="infor_page">
		<table class="table" id="app_page">
		        <tr>
		            <th>应用名称</th>
		            <th>出版者</th>
		            <th>适用对象</th>
		            <th>状态</th>
		            <th>最后更新时间</th>
		            <th></th>
		        </tr>
		</table>
	</div>
</div>

<script type="text/javascript" src="public/js/part_js/qs.appManage.js"></script>
<script>
	$().ready(function(){
		qs.appManage.init({
			app_list: <?php echo json_encode($app_list); ?>
		});
	});
</script>