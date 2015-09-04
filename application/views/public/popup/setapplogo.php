
<dl class="dialogBox D_uploadLogo">
	<dt class="dialogHeader">
		<span class="title">应用logo设置</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<dl class="bisinessLogo">
			<dt class="title">预览</dt>
			<dd class="img"><img id="app_logo_img" src="<?php echo $logo;?>"/></dd>
			<dd class="tc">尺寸：110*110px</dd>
		</dl>
		<dl class="bisinessInfo">
			<dt class="btnBox02">
				<a class="btnGray btn" ><span class="text">选择图片</span><b class="bgR"></b><input type="file" class="inputFile" id=imgfileupId name="logo"  accept="image/gif, image/jpeg,image/jpg" onchange="upload()"/></a>
			</dt>
			<dd class="info">
				请从本地选择一张照片，支持jpg,png格式。
				<div class="clipBox"><img id="app_element_id" src="<?php echo $o_logo;?>" />
				</div>
			</dd>
		</dl>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm" id="set_app_logo"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
	<div  id="app_save_x_y" style="display:none">
	</div>
</dl>

<link href="public/css/jquery.Jcrop.css" rel="stylesheet" />
<script type="text/javascript" src="public/js/jquery.Jcrop.min.js"></script>
<script type="text/javascript" src="public/js/ajaxfileupload.js"></script>
<script type="text/javascript">
var ratio;
function AutoResizeImage(maxWidth,maxHeight,imgId){
	var img = new Image();
	var objImg=document.getElementById(imgId);
	img.src = objImg.src;
	var hRatio;
	var wRatio;
	var Ratio = 1;
	var w = img.width;
	var h = img.height;
	if(w>h || w==h)
	{
		Ratio=maxWidth/w;
	}
	else if(w<h)
	{
		Ratio=maxHeight/h;
		
	}
	if (Ratio){
	w = w * Ratio;
	h = h * Ratio;
	}
	objImg.height = h;
	objImg.width = w;
	return Ratio;
}
function showCoords(c)
 { 
	//alert(c.x); //得到选中区域左上角横坐标 
	$("#app_save_x_y").text(c.x); //得到选中区域左上角纵坐标
	$('#app_save_x_y').attr("class",c.y) ;
	$('#app_save_x_y').attr("target",c.w);
	$('#app_save_x_y').attr("name",c.h) ;
}
function  upload()
{
  	var url = 'app/logoUpload';
   	$.ajaxFileUpload(
	   { 
			url:url,            //需要链接到服务器地址 
			secureuri:false, 
			fileElementId:'imgfileupId',                        //文件选择框的id属性 
			dataType: 'JSON',                                     //服务器返回的格式，可以是json 
			success: function (data, status)            //相当于java中try语句块的用法 
			{    
				
				var data=$.parseJSON(data);
				if(data.code==0)
				{
					var timstamp = (new Date()).valueOf();
					$("#app_element_id").attr("src", data.data.src+"?t=" + timstamp);
					$('.jcrop-holder').remove();
					var api = $.Jcrop('#app_element_id',{
					onSelect:showCoords
					}
					); 
					api.setImage($('#app_element_id').attr("src"));
					api.setOptions({bgOpacity:0.5,maxSize:[110,110],allowResize:true,minSize:[1,1]});//设置相应配置 
					api.setSelect([0,0,110,110]); //设置选中区
					$('.jcrop-holder').find("img").show(); 
					if (typeof (data.error) != 'undefined')
					 {
						if (data.error != '') 
						{
							alert(data.error);
						} else {
							alert(data.msg);
						}
					}
				}
			}, 
			error: function (data, status, e)            //相当于java中catch语句块的用法 
			{
				alert("上传文件失败");
			} 
	})
}

$(function(){
			$("#app_element_id").Jcrop({
				 minSize:[1,1],
				onChange:showCoords, //当选择区域变化的时候，执行对应的回调函数 
				onSelect:showCoords ,
				aspectRatio: 1, //选中区域宽高比为1，即选中区域为正方形 
				bgColor:"#ccc", //裁剪时背景颜色设为灰色 
				bgOpacity:0.1, //透明度设为0.1 
				allowResize:true, //不允许改变选中区域的大小 
				setSelect:[0,0,110,110] //初始化选中区域 
			});
				$('.dialogBottom #set_app_logo').click(function()
				{
					if($(this).hasClass("false"))
					{
						return;
					}
					$(this).addClass("false");
					var x=$('#app_save_x_y').text();
					var y=$('#app_save_x_y').attr("class");
					var w=$('#app_save_x_y').attr("target");
					var h=$('#app_save_x_y').attr("name");
					var app_logo = $("#app_element_id").attr("src");
					if(app_logo != ''){
						app_logo = app_logo.split('?');
						app_logo = app_logo[0];
						app_logo = app_logo.split('/');
						app_logo = app_logo[app_logo.length - 1];
						app_logo = app_logo.substr(0, 31);
						if(app_logo.length < 20){
							app_logo = '';
						}
					}
					var obj={
					"x":x,
					"y":y,
					"w":w,
					"h":h,
					"app_logo":app_logo
					};
					var _this=$(this);
					var path="app/logoCrop";
					$.post(path,obj,function(data)
					{
						var timstamp = (new Date()).valueOf();
						document.getElementById('app_logo_img').src=data.data.src+"?t=" + timstamp;
						$("#appAddOrEdit .appLogo img").attr("src", data.data.src+"?t=" + timstamp);
						//$("#appAddOrEdit .appLogo img").attr("app_logo", data.data.src);
						_this.removeClass("false");
						hideDialog();
					},"json");
				})
	});
</script>
