
//检查账号是否已存在
function checkLoginName(checkStr)
{
    var checkName = $(checkStr).val();
    var status = false;
	$.ajax({
		type:'POST',
		url:'register/checkLoginName',
		data:{
                    loginName:checkName
                },//序列化表单里所有的内容
		timeout:3000,
                async:false,
		success: function(data){
                    var obj = $.parseJSON(data);
                    if(obj.code === 1){
                        //用户名已存在
                        $(".errorMsg").css("display","block").text(obj.msg);
                        borderChangeColor($(checkStr).parent(".inputBox"),1);
                        status = btnOkOrNo("#regBtn",0);
                    }else{
                        $(".errorMsg").css("display","none");
                        borderChangeColor($(checkStr).parent(".inputBox"),0);
                        status = btnOkOrNo("#regBtn",1);
                    }
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			//alert(XMLHttpRequest.status);
			if( textStatus==="timeout")
                            alert('超时了……有可能是你的网络不给力哟！');
			else
                            alert('有错误发生！请稍后再试！');
		}
	});
        return status;
}

//检查公司网址是否已存在
function checkUrl(checkStr)
{   
    //alert(checkStr);
    //var checkStr = $(checkStr).val();
    $.ajax({
            type:'POST',
            url:'register/checkUrl',
            data:{
                site_url:checkStr
            },//序列化表单里所有的内容
            timeout:3000,
            success: function(data){
                var obj = $.parseJSON(data);
                if(obj.code === 1){
                    //用户名已存在
                    $(".errorMsg").css("display","block").text(obj.msg);
                    borderChangeColor($("#site_url").parent(".inputBox"),1);
                    btnOkOrNo("#regBtn",0);
                }else{
                    $(".errorMsg").css("display","none");
                    borderChangeColor($("#site_url").parent(".inputBox"),0);
                    btnOkOrNo("#regBtn",1);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    if( textStatus==="timeout")
                        alert('超时了……有可能是你的网络不给力！请稍后再试');
                    else
                        alert('有未知错误发生！请稍后再试！');
            }
    });
}

//改变边框颜色
function borderChangeColor(domPath,type){    //domPath 为要改变的元素路径
    var path = domPath;
    if( type===1){
        path.css("border","1px solid #FF9000");
    }else{
        path.css("border","");
    }
}

//判断验证码是否正确
function checkCode(checkStr){
    if(!checkStr){
        //不存在
        $(".errorMsg").css("display","block").text('验证码不能为空');
        borderChangeColor($("#pass_word_code").parent(".inputBox"),1);
        btnOkOrNo("#regBtn",0);
    }else{
        $(".errorMsg").css("display","none");
        borderChangeColor($("#pass_word_code").parent(".inputBox"),0);
        btnOkOrNo("#regBtn",1);
    }
    /*
    $.ajax({
        type:'POST',
        url:'register/valid_code',
        data:{
            checkCode:checkStr
        },//序列化表单里所有的内容
        timeout:3000,
        //async:false,
        success: function(data){
            var obj = $.parseJSON(data);
            if(obj.code === 0){
                //验证码不正确
                $(".errorMsg").css("display","block").text(obj.msg);
                borderChangeColor($("#pass_word_code").parent(".inputBox"),1);
                btnOkOrNo("#regBtn",0);
            }else{
                $(".errorMsg").css("display","none");
                borderChangeColor($("#pass_word_code").parent(".inputBox"),0);
                btnOkOrNo("#regBtn",1);
                
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                if( textStatus==="timeout")
                    alert('超时了……有可能是你的网络不给力哟！');
                else
                    alert('有错误发生！请稍后再试！');
        }
    });
    */
}

//判断两次密码是否一样
function checkPwd(path){
    var p1 = $("#user_pwd");
    if(!p1.val() || !$(path).val()){
        $(".errorMsg").css("display","block").text("密码不能为空");
        borderChangeColor($(path).parent(),1);
        borderChangeColor($(p1).parent(),1);
        btnOkOrNo("#regBtn",0);
    }
    if(p1.val() !== $(path).val()){
        $(".errorMsg").css("display","block").text("两次输入密码不一致");
        borderChangeColor($(path).parent(),1);
        borderChangeColor($(p1).parent(),1);
        btnOkOrNo("#regBtn",0);
    }else{
        $(".errorMsg").css("display","none");
        borderChangeColor($(path).parent(),0);
        borderChangeColor($(p1).parent(),0);
        btnOkOrNo("#regBtn",1);
    }
}


//发送验证邮件
function sendMail(){
    var emailStr = $("#user_email").val();
    if(!emailStr){
        $(".errorMsg").css("display","block").text('邮箱不能为空');
        borderChangeColor($("#user_email").parent(".inputBox"),1);
        btnOkOrNo("#regBtn",0);
        return false;
    }
    var picCode = $("#pass_word_code").val();
    if(!picCode){
        $(".errorMsg").css("display","block").text('图片验证码不能为空');
        borderChangeColor($("#pass_word_code").parent(".inputBox"),1);
        btnOkOrNo("#regBtn",0);
        return false;
    }
    $.ajax({
        type:'POST',
        url:'register/sendMailCode',
        data:{
            email:emailStr,
            code:picCode
        },//序列化表单里所有的内容
        timeout:10000,
        success: function(data){
            var obj = $.parseJSON(data);
            if(obj.code === 1){
                //不正确
                $(".errorMsg").css("display","block").text(obj.msg);
                borderChangeColor($("#pass_word_code").parent(".inputBox"),1);
                btnOkOrNo("#regBtn",0);
            }else{
                $(".errorMsg").css("display","block").html(obj.msg);
                borderChangeColor($("#pass_word_code").parent(".inputBox"),0);
                btnOkOrNo("#regBtn",1);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                if( textStatus==="timeout")
                    alert('超时了……有可能是你的网络不给力哟！');
                else
                    alert('有错误发生！请稍后再试！');
        }
    });
}


//验证邮箱的验证码是否一致
function checkEmailCode(str){
    var codeStr = $(str).val();
    if(codeStr.length<1)
    {
        $(".errorMsg").css("display","block").text('邮箱验证码不能为空');
        borderChangeColor($(str).parent(".inputBox"),1);
        btnOkOrNo("#regBtn",0);
    }
    var status = false;
    $.ajax({
        type:'POST',
        url:'register/validMailCode',
        data:{
            emailCode:codeStr
        },//序列化表单里所有的内容
        timeout:4000,
        success: function(data){
            var obj = $.parseJSON(data);
            if(obj.code === 1){
                //不正确
                $(".errorMsg").css("display","block").text(obj.msg);
                borderChangeColor($(str).parent(".inputBox"),1);
                status = btnOkOrNo("#regBtn",0);
            }else{
                $(".errorMsg").css("display","none");
                borderChangeColor($(str).parent(".inputBox"),0);
                status = btnOkOrNo("#regBtn",1);
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                if( textStatus==="timeout")
                    alert('超时了……有可能是你的网络不给力哟！');
                else
                    alert('有错误发生！请稍后再试！');
        }
    });
    return status;
}



//判断登录名格式以及是否存在于数据库
function checkLoginN(path){
       var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
       if(reg.test($(path).val())){
           $(".errorMsg").css("display","none");
           borderChangeColor($(path).parent(),0);
           return checkLoginName(path);
           //return true;
       }else{
           $(".errorMsg").css("display","block").text("格式应为“xxx@abc.com”");
           borderChangeColor($(path).parent(),1);
           return btnOkOrNo("#regBtn",0);
       }
}

//按钮禁用或恢复
function btnOkOrNo(dom,type){
    if(type===1){   //启用
        $(dom).removeAttr("disabled");
        $(dom).removeClass("regBtnDisabled");
        return true;
    }else{  //禁用
        $(dom).attr("disabled","disabled");
        $(dom).addClass("regBtnDisabled");
        return false;
    }
}

//判断email格式
function checkEmail(path){
    var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
    if(reg.test($(path).val())){
        $(".errorMsg").css("display","none");
        borderChangeColor($(path).parent(),0);
        btnOkOrNo("#regBtn",1);
    }else{
        $(".errorMsg").css("display","block").text("邮箱格式应为“xxx@abc.com”");
        borderChangeColor($(path).parent(),1);
        btnOkOrNo("#regBtn",0);
    }
}


//注册提交
function submitForm(){
        var p1 = $("#login_name").val();
        var p2 = $("#user_pwd").val();
        var p3 = $("#user_cfPwd").val();
        var p4 = $("#display_name").val();
        var p5 = $("#company_name").val();
        var p6 = $("#site_url").val();
        if(p1 && p2 && p3 && p4 && p5 && p6){
            
            $.ajax({
                type:'POST',
                url:'register/regSave',
                data: $("form#submitForm").serialize(),//序列化表单里所有的内容
                timeout:20000,
                beforeSend: function(){
                    $('#msgBox').css({'display':'block'});
                },
                success: function(data){
                    var obj = $.parseJSON(data);
                    //alert(obj.code);
                    $('#msgBox').css({'display':'none'});
                    if(obj.code===0 && obj.data.length>3){
                        //alert(obj.msg+"，确定后马上跳转到登录页");
                        window.location = "register/index/3";
                    }else{
                        alert(obj.prompt_text+obj.msg);
                        return false;
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                        //alert(XMLHttpRequest.status);
                        $('#msgBox').css({'display':'none'});
                        if( textStatus==="timeout")
                            alert('超时了……有可能是你的网络不给力哦！');
                        else
                            alert('有错误发生！请稍后再试！');
                        return false;
                }
            });
            
            
        }else{
            $(".errorMsg").css("display","block").text("有必填项目未填，请耐心填写完表单");
            btnOkOrNo("#regBtn",0);
        }
}


//注册第一步验证提交
function checkStep(step){
    if(step===1){
        var p1 = $("#user_email").val();
        var p2 = $("#pass_word_code").val();
        var p3 = $("#mail_code").val();
        if(!p1 || !p2 || !p3){
            $(".errorMsg").css("display","block").text("有项目未填，请耐心填写完表单");
            btnOkOrNo("#regBtn",0);
        }
    }
}