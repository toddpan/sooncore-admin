<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height:100%;">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>sooncore平台管理中心</title>
</head>
<body>
	<!-- 组织与员工_批量导入_下载模板上传文档.html -->
	<div class="contHead">
		<span class="title01 rightLine">组织管理</span><span class="title03">批量导入</span>	
	</div>
	<!-- end contHead -->
	<div class="contTitle"><span class="text">批量导入组织与员工</span><span class="tips">您已定义的员工标签如下：</span></div>
	<div class="userTagBox">    
		{foreach $tags as $tag}
			<span class="tag">{$tag}</span>
		{/foreach}
		<a class="link" onclick="loadCont('{site_url('tag/addTagPage')}');">修改</a>
	</div>
	<a class="downLoadTemp" href="{site_url('batchimport/downloadTemplate')}" target="_self">
		<span class="btn_downLoad">下载模板</span>
		<span class="text01">请先下载模板，根据已定义的员工标签填写内容， 模板中的员工标签不可编辑，否则无法导入。</span>
	</a>

	<a class="downLoadTemp upTEMP" >
		<span class="btn_upLoad" >上传文档</span>
		<span class="text01" >填写完成模板内容后，上传文档导入组织与员工。</span>
	</a>	
	<form style="display: none">
		<input class="inputFile1" id="fileupload" type="file"  title=""   name="batchfile"/>
		<button type="submit" class="start" id="start1">upload</button>  
	</form>
	<div id="dialog" class="dialog">
		<div class="dialogBorder">
		</div>
	 </div>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<!--<script src="public/js/jquery-1.10.2.js"></script>-->
<script src="public/js/ajaxfileupload.js"></script>
<script src="public/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="public/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="public/jQueryFileUpload/js/jquery.fileupload.js"></script>
<script src="public/jQueryFileUpload/js/jquery.fileupload-process.js"></script>
<script src="public/jQueryFileUpload/js/jquery.fileupload-validate.js"></script>
<script src="public/jQueryFileUpload/js/cors/jquery.xdr-transport.js"></script>
<script src="public/jQueryFileUpload/bootstrap.min.js"></script>
<script type="text/javascript">
var iframe = false; 
if($.support.msie  && $.support.version < 10){  
	iframe = true; 
}
iframe=true;

$(function(){
			$('.upTEMP ').click(function(){
				$('input[class=inputFile1]').trigger('click');
			});
			$('.inputFile1').change(function()
			{
				var dialog='<dl class="dialogBox D_uploadTable load_file">'+
				'<dt class="dialogHeader">'+
				'<span class="title">上传文档</span>'+
				'<a class="close" onClick="hideDialog();"></a>'+
				'</dt>'+
				'<dd class="dialogBody">'+
				'<div class="tcText">正在上传文档...</div>'+
				'<div class="statusBar">'+
				'<div class="loading" id="down_progress" style="width:0%"></div>'+
				'</div>'+
				'</dd>'+
				'<dd class="dialogBottom">'+
				'<a class="btnGray btn_cancel"><span class="text" onClick="hideDialog();">取消</span><b class="bgR"></b></a>'+
				'</dd>'+
			   '</dl>';
			  	var _mask = $('.mask').show();
				var _dialog = $('.dialog').show();	
				 _dialog.find('.dialogBorder').append(dialog);
				var w = _dialog.width();
				var h = _dialog.height();
				 _dialog.css({
							'margin-top': -h/2+'px',
							'margin-left': -w/2+'px'
						});
						
				//ajaxFileUpload($(this).val());
			});
			$('.D_uploadTable .close, .D_uploadTable .btn_cancel').click(function(){
				//clearTimeout(t);
				hideDialog();
			});

			var url = "{site_url('batchimport/upload')}";

		    $('#fileupload').fileupload({
		        url: url,
				type:"POST",
				iframe:true,
		        dataType:'json',
				acceptFileTypes:/(\.|\/)(xlsx|xls|csv)$/i,
				autoUpload:true,
           		maxFileSize: 10000000, // 10 MB
				minFileSize:0,
            	previewMaxWidth: 100,
           		previewMaxHeight: 100,
            	previewCrop: true,
				add: function(e, data) {
					var uploadErrors = [];									
					if(data.originalFiles[0]['type'] && data.originalFiles[0]['type']!="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" && data.originalFiles[0]['type']!="application/vnd.ms-excel") {
					 uploadErrors.push('文件类型不符合要求');
					 hideDialog();
					}
					if(data.originalFiles[0]['size'] && data.originalFiles[0]['size'] > 10000000) {
					 uploadErrors.push('文件大小不能超过10M');
					 hideDialog();
					}
					//alert(data.originalFiles[0]['size'])
					if(data.originalFiles[0]['size']==0) {
					 uploadErrors.push('文件大小不能为空');
					 hideDialog();
					}
					if(uploadErrors.length > 0) {
					 alert(uploadErrors.join("\n"));			
					 		} else {
															
				 		data.submit();	
											
					}

			  	},
		        done: function (e, data) {
				
					var result = data.result;					
					if(result.code == 0){
						hideDialog();
						loadCont(result.data.url);
					}else{
						hideDialog();
						$('.rightCont').html(result.data.html);
					}
					return;
					
		        },
				fail:function(e,data)
				{
				
					if((typeof data.result) == "undefined"){
						alert("可能您传输的文件格式不正确!!!"); 
					}else{
						$.each(data.result.files, function (index, file) {
						alert(file.error)
							var error = $('<span/>').text(file.error);
							$(data.context.children()[index])
								.append('<br>')
								.append(error);
						});
					}
					 hideDialog();
				},
				 progressall: function (e, data) {					
		            var progress = parseInt(data.loaded/data.total * 100, 10);
					 //$('.dialog .statusBar #down_progress').css("width",'100%')
					 //alert($('#down_progress').attr("class"))
		            $('#down_progress').css(
		                'width',
		                progress+'%'
		            );
					//alert( $('.dialog .statusBar #down_progress').css("width"))
		        }
		    }).prop('disabled', !$.support.fileInput)
		    .parent().addClass($.support.fileInput ? undefined : 'disabled')
		})      
</script>
</body>
</html>