
<dl class="dialogBox D_confirm">
	<dt class="dialogHeader">
		<span class="title">新建敏感词</span>
		<a  class="close" onclick="hideDialog();"></a>
	</dt>
	<dd class="dialogBody">
		<div class="form-item">
        <label>请输入敏感词</label> <br />
            <div class='text_box' style="width: 428px; height: 80px; cursor: text">
                <label style="cursor:text">您可以输入多个敏感词，例如：无党，刺杀，暴力，每个敏感词之间用逗号隔开。</label>
                <textarea  class='textarea' style="width: 428px; height: 80px;" />
            </div>
            <!--<div  class="textarea">
              <label>您可以输入多个敏感词，例如：无党，刺杀，暴力，每个敏感词之间用逗号隔开。</label>
             <textarea style="width: 428px; height: 80px; color:#999" class="textarea"></textarea>

            </div>-->

        </div>
	</dd>
	<dd class="dialogBottom">
		<a class="btnBlue yes btn_confirm" ><span class="text">确定</span><b class="bgR"></b></a>
		<a class="btnGray btn btn_cancel"  onclick="hideDialog();"><span class="text">取消</span><b class="bgR"></b></a>
	</dd>
</dl>
<script type="text/javascript">
	function addNewWord(){
	    var text=$('textarea').val();
	    var path="SensitiveWord/saveSensitiveWord";
	    var obj={
				"word":text
				};	
	     //alert(text);	
		$.post(path,obj,function(data)
	        {
			 // alert(data)
			   var json = $.parseJSON(data);
			   if(json.code==0)
			   {
			     
			      var _this = $('.con-wrapper').eq(1);
		          _this.show().siblings('.con-wrapper').hide();
				  
		          hideDialog();
			   }
			  else
				{
					alert(json.prompt_text)
					return false;	
				}
			});
		
	}
    $(function()
    {
        $('.text_box').click(function()
        {
            $(this).find('textarea').focus();
        });
        $('textarea').keydown(function()
        {

            $(this).prev().hide();
        });
        $('textarea').keyup(function()
        {
            if($(this).val()=='')
            {
                $(this).prev().show();
            }
        })
       /* $('.text_area').click(function()
        {
            $(this).focus();
        });
        var context= $('textarea.textarea').text();
        $('textarea.textarea').focus(function()
        {
            if($(this).val()==context)
            {
                $(this).val('');
            }


        }).blur(function()
            {
                if($(this).val()=='')
                {

                    $(this).val(context);
                    //alert($(this).text());
                }
            });*/
    })
</script>
