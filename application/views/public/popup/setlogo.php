<!--弹窗_企业logo设置.html-->
<dl class="dialogBox D_uploadLogo">
	<dt class="dialogHeader">
		<span class="title">企业logo设置</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<dl class="bisinessLogo">
			<dt class="title">预览</dt>
			<dd class="img"><img id="logo_img" src="<?php echo $logo;?>"/></dd>
			<dd class="tc">尺寸：110*110px</dd>
		</dl>
		<dl class="bisinessInfo">
			<dt class="btnBox02">
				<a class="btnGray btn" ><span class="text">选择图片</span><b class="bgR"></b><input type="file" class="inputFile" id="fileupId" name="logo"  accept="image/gif, image/jpeg,image/jpg" onchange="upload()"/></a>
			</dt>
			<dd class="info">
				请从本地选择一张照片，支持jpg,png格式。
				<div class="clipBox"><img id="element_id" src="<?php echo $o_logo;?>" />
				<!--<img id="element_id1" src="<?php echo $o_logo;?>" />-->
				</div>
			</dd>
		</dl>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm" id="set_logo"><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel" onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
	<div  id="save_x_y" style="display:none">
	</div>
</dl>

<script type="text/javascript">
var ratio;
function AutoResizeImage(maxWidth,maxHeight,imgId){
	var img = new Image();
	var objImg=document.getElementById(imgId);
	//img.src =document.getElementById(imgId).src;
	img.src = objImg.src;
	var hRatio;
	var wRatio;
	var Ratio = 1;
	var w = img.width;
	var h = img.height;
	if(w>h || w==h)
	{
		Ratio=maxWidth/w;
		//alert(Radio)
	}
	else if(w<h)
	{
		Ratio=maxHeight/h;
		//alert(Radio)
		
	}
	//alert(Ratio)
	/*wRatio = maxWidth / w;
	hRatio = maxHeight / h;
	if (maxWidth ==0 && maxHeight==0){
	Ratio = 1;
	}else if (maxWidth==0){//
	if (hRatio<1) Ratio = hRatio;
	}else if (maxHeight==0){
	if (wRatio<1) Ratio = wRatio;
	}else if (wRatio<1 || hRatio<1){
	Ratio = (wRatio<=hRatio?wRatio:hRatio);
	}*/
	if (Ratio){
	w = w * Ratio;
	h = h * Ratio;
	}
	objImg.height = h;
	objImg.width = w;
	//alert(h)
	//alert(w)
	return Ratio;
}
function showCoords(c)
 { 
	//alert(c.x); //得到选中区域左上角横坐标 
	$("#save_x_y").text(c.x); //得到选中区域左上角纵坐标
	$('#save_x_y').attr("class",c.y) ;
	$('#save_x_y').attr("target",c.w);
	$('#save_x_y').attr("name",c.h) ;
}
function  upload()
{
  	var url = 'systemset/logoUpload';
   	$.ajaxFileUpload(
	   { 
			url:url,            //需要链接到服务器地址 
			secureuri:false, 
			fileElementId:'fileupId',                        //文件选择框的id属性 
			dataType: 'JSON',                                     //服务器返回的格式，可以是json 
			success: function (data, status)            //相当于java中try语句块的用法 
			{    
				//$('#result').html('添加成功');
				var data=$.parseJSON(data);
				//alert(data)
				//alert(data.code)
				if(data.code==0)
				{
					//alert(22)
					//$('#element_id').remove();
					var timstamp = (new Date()).valueOf();
					$("#element_id").attr("src", data.data.src+"?t=" + timstamp);
					$('.jcrop-holder').remove();
					//alert(111)
					var api = $.Jcrop('#element_id',{
					onSelect:showCoords
					}
					); 
					api.setImage($('#element_id').attr("src"));
					api.setOptions({bgOpacity:0.5,maxSize:[110,110],allowResize:true,minSize:[1,1]});//设置相应配置 
					api.setSelect([0,0,110,110]); //设置选中区
					//api.onSelect(showCoords);
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
				//alert(data)
				//$('#result').html('添加失败');
				//alert(data.name)
				//alert(status) 
				alert("上传文件失败");
			} 
	})
}

$(function(){
			$("#element_id").Jcrop({
				 minSize:[1,1],
				// maxSize:[110,110],
				onChange:showCoords, //当选择区域变化的时候，执行对应的回调函数 
				onSelect:showCoords ,
				aspectRatio: 1, //选中区域宽高比为1，即选中区域为正方形 
				bgColor:"#ccc", //裁剪时背景颜色设为灰色 
				bgOpacity:0.1, //透明度设为0.1 
				allowResize:true, //不允许改变选中区域的大小 
				setSelect:[0,0,110,110] //初始化选中区域 
				//onChange:showPreview
			});
				$('.dialogBottom #set_logo').click(function()
				{
					if($(this).hasClass("false"))
					{
						return;
					}
					$(this).addClass("false");
					var x=$('#save_x_y').text();
					var y=$('#save_x_y').attr("class");
					var w=$('#save_x_y').attr("target");
					var h=$('#save_x_y').attr("name");
					//alert(x)
					//alert(y)
					var obj={
					"x":x,
					"y":y,
					"w":w,
					"h":h
					};
					var _this=$(this);
					var path="systemset/logoCrop";
					$.post(path,obj,function(data)
					{
						var timstamp = (new Date()).valueOf();
						document.getElementById('logo_img').src=data.data.src+"?t=" + timstamp;
						$('#logo_finished').attr("src",data.data.src+"?t=" + timstamp);
						_this.removeClass("false");
						hideDialog();
						//$('#logo_img').attr("src",data.data.src);
					},"json");
					/* var path;
					$.post(path,obj,function(data)
					{
						
					},"json");*/
					//$.POST('',obj,,'json')
					
				})
			//var api = $.Jcrop('#element_id',{
                  //allowResize:false,
				  //aspectRatio:1,
                 // dragEdges:true
                 /* minSize:[1,1],
				  */
          // });
			/*var a=[1,1,1,1];
			api.setSelect(a);*/
	});
</script>
