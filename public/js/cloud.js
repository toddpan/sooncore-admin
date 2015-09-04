// Cloud Float...
var offset1 = 450;
var offset2 = 0;
var offsetbg = 0;
var $body = $("body");
var $main = $("#mainBody");
var $cloud1 = $("#cloud1");
var $cloud2 = $("#cloud2");
var mainwidth = $main.outerWidth();  

function flutter() {
	if (offset1 >= mainwidth) {
		offset1 =  -580;
	}

	if (offset2 >= mainwidth) {
		 offset2 =  -580;
	}
	offset1 += 1.1;
	offset2 += 1;
	$cloud1.css("background-position", offset1 + "px 140px");
	
	$cloud2.css("background-position", offset2 + "px 360px");
}


/// 飘动
setInterval(flutter, 70);

//背景飘动
/*setInterval(function bg() {
	if (offsetbg >= mainwidth) {
		offsetbg =  -580;
	}
	offsetbg += 0.6;
	$body.css("background-position", -offsetbg + "px 0")
}, 90 );*/
