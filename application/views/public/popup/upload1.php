<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- Force latest IE rendering engine or ChromeFrame if installed -->
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<!-- Bootstrap styles 引导风格-->
	<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">-->
	<link rel="stylesheet" href="public/jQueryFileUpload/bootstrap.min.css">
	
	<!-- Generic page styles通用页面样式 -->
	<link rel="stylesheet" href="public/jQueryFileUpload/css/style.css">
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars CSS样式文件输入字段作为按钮并调整引导进度条-->
	<link rel="stylesheet" href="public/jQueryFileUpload/css/jquery.fileupload.css">
</head>

<body>

<!--弹窗_上传文档.html-->

<dl class="dialogBox D_uploadTable">
	<dt class="dialogHeader">
        
		<span class="title" >上传文档</span>
                
		<a  class="close" onClick="upCSVFile();"></a>
	</dt>
	<dd class="dialogBody">
    	<div class="tcText">正在上传文档0...</div>
		<div class="statusBar">
        	<div class="loading" ></div>
        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnGray btn btn_cancel" ><span class="text" onClick="hideDialog();">取消</span><b class="bgR"></b></a>
	</dd>
</dl>


<!--   onchange="submitform()"-->

<div class="container">
    <br>
    <!-- The fileinput-button span is used to style the file input field as button 中的FileInput按钮跨度用于样式文件输入字段作为按钮 -->
    <span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Select files...</span>
        <!-- The file input field used as target for the file upload widget -->
        <input id="fileupload" type="file" name="files[]" multiple>
    </span>
    <br>
    <br>
    <!-- The global progress bar 全球进度条-->
	
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <!-- The container for the uploaded files 的容器上传的文件-->
    <div id="files" class="files"></div>
	
    <br>    
</div>
<script src="public/jQueryFileUpload/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="public/jQueryFileUpload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="public/jQueryFileUpload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="public/jQueryFileUpload/js/jquery.fileupload.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!--<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>-->
<script src="public/jQueryFileUpload/bootstrap.min.js"></script>
<script>
/*jslint unparam: true */
/*global window, $ */
$(function () {
//		var i = 0;
//		var t;
//		var t2 = setTimeout(function(){
//		/*var t2 = setTimeout(function(){
//			$(".tcText").text("正在导入组织与帐号...");
//		},1500);*/
//		function statusBar(){
//			if(i<100){
//				$(".statusBar .loading").css("width",i+"%");
//				i++;
//				t = setTimeout(function(){statusBar();},30);
//			}else{
//				$(".dialogHeader .title").html("导入组织与帐号");
//				$(".tcText").html("正在导入组织与帐号...<br><span  class='green'>已经导入10个组织50个帐号</span>");
//				$(".statusBar").remove();
//				setTimeout(
//					function()
//					{
//						//location.href= '<?php echo site_url('bulkimport/importlayout');?>?#group';
//						//loadPage('<?php echo site_url('ecologycompany/staffInfoPage');?>','group');
//						//hideDialog();
//					},2000
//				);
//				
//				clearTimeout(t);
//				//clearTimeout(t2);
//			}
//		}
	//	statusBar();
	$('.D_uploadTable .close, .D_uploadTable .btn_cancel').click(function(){
		//clearTimeout(t);
		hideDialog();
	});

	//function submitform(){
	   // alert(values);
	//}
    //'use strict';
    // Change this to the location of your server-side upload handler:更改为你的服务器端上传处理程序的位置
    // var url = '<?php echo ('/ucadmin/public/jQueryFileUpload/server/php/');?>';
	 var url = '<?php echo ('/ucadmin/public/jQueryFileUpload/server/php/');?>';
	//alert(url);
    $('#fileupload').fileupload({
        url: url,
        dataType:'json',
        done: function (e, data) {
            $('.statusBar .loading').css(
                'width',
                 '100%'
            );
			var filename = '';
            $.each(data.result.files, function (index, file) {
			     filename = file.name;
                $('<p/>').text(file.name).appendTo('#files');
            });
			//alert(filename);
			var gotourl = "<?php echo site_url('bulkimport/upCSVFile').'/1/'?>" + Math.random() + "?filename=" + encodeURIComponent(filename);  
             showDialog(gotourl);   
			//location.href= '<?php echo site_url('bulkimport/importlayout');?>?#group';
			//loadPage('<?php echo site_url('ecologycompany/organizeStaff');?>','group');
			//hideDialog();  
			//跳转到解析页面[另一个一样的页面,只是是在记录解析的数量]
			//隐藏当前对话框
			//hideDialog();
        },
        progressall: function (e, data) {//进度条
            var progress = parseInt(data.loaded / data.total * 100, 10);
            //$('#progress .progress-bar').css(
            //    'width',
            //    progress + '%'
           // );
           // alert(progress);
            $('.statusBar .loading').css(
                'width',
                progress + '%'
            );
			
			
        }
    }).prop('disabled', !$.support.fileInput)
    .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>

</body>
</html>
<script type="text/javascript">
     
//	$(function(){
//	   

</script>
