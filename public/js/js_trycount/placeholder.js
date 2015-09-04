// 兼容其他不支持placeholder的浏览器
var PlaceHolder = { 
    _support: (function() { 
        return 'placeholder' in document.createElement('input'); 
    })(), 
  
    //提示文字的样式，需要在页面中其他位置定义 
    className: 'placeholder', 
  
    init: function() { 
        if (!PlaceHolder._support) { 
            //未对textarea处理，需要的自己加上 
            $('input[placeholder]').each(function(){
				if($(this).val()==""){
					var placeholder = $(this).attr('placeholder');
					$(this).val(placeholder).addClass('placeholder');
					$(this).focus(function(){
						if($(this).val() == placeholder){
							 $(this).val('');
							 $(this).removeClass(PlaceHolder.className);
						}
					}).blur(function(){
						if($(this).val() ==''){
							$(this).val(placeholder);
							$(this).addClass(PlaceHolder.className);	
						}
					})
				}
			}) 
            
        } 
    }
};