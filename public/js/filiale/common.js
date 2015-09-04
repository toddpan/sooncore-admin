function showDialog(url){
	hideDialog();
	//var url = encodeURIComponent(url);
	var _dialog = $('#dialog').show();
	var _mask = $('.mask').height($('body').height()).show();
	if(url!='' && typeof(url)=='string'){
		_dialog.find('.dialogBorder').load(url,function(){
			//var maxW=$('body').width();
			//var maxH=$('body').height();
			var w = _dialog.width();
			var h = _dialog.height();
			_dialog.css({
				'margin-top': -h/2+'px',
				'margin-left': -w/2+'px'
			});	
		});
	}
}
function hideDialog(){
	
	$('#dialog').hide();
	$('.mask').hide();
	$('#dialog').find('.dialogBorder').empty();
}
function valitateStaffAccount(value) {
    var half = /^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$/;
    if (half.test(value)) return true
    else return false;
}
function valitateAreaCode(value) {
    var login = /^[0-9]+$/;
    var count = 0;
    if (0 < value.length <= 20 && login.test(value)) {
        if (count == value.length) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
function valitateTelephonNum(value) {
    var login = /^[0-9]+$/;
    var count = 0;
    if (0 < value.length <= 20 && login.test(value)) {
        if (value.charAt(0) == 0) {
            return false;
        }
        for (var i = 0; i < value.length; i++) {
            if (value.charAt(i) == 0) {
                count++;
            }
        }
        if (count == value.length) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}