
var PageName = '变更管理员';
var PageId = '71e3c60b27f64e8a8099b1bb22a116fd'
var PageUrl = '变更管理员.html'
document.title = '变更管理员';
var PageNotes = 
{
"pageName":"变更管理员",
"showNotesNames":"False"}
var $OnLoadVariable = '';

var $CSUM;

var hasQuery = false;
var query = window.location.hash.substring(1);
if (query.length > 0) hasQuery = true;
var vars = query.split("&");
for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    if (pair[0].length > 0) eval("$" + pair[0] + " = decodeURIComponent(pair[1]);");
} 

if (hasQuery && $CSUM != 1) {
alert('Prototype Warning: The variable values were too long to pass to this page.\nIf you are using IE, using Firefox will support more data.');
}

function GetQuerystring() {
    return '#OnLoadVariable=' + encodeURIComponent($OnLoadVariable) + '&CSUM=1';
}

function PopulateVariables(value) {
    var d = new Date();
  value = value.replace(/\[\[OnLoadVariable\]\]/g, $OnLoadVariable);
  value = value.replace(/\[\[PageName\]\]/g, PageName);
  value = value.replace(/\[\[GenDay\]\]/g, '26');
  value = value.replace(/\[\[GenMonth\]\]/g, '7');
  value = value.replace(/\[\[GenMonthName\]\]/g, '七月');
  value = value.replace(/\[\[GenDayOfWeek\]\]/g, '星期六');
  value = value.replace(/\[\[GenYear\]\]/g, '2014');
  value = value.replace(/\[\[Day\]\]/g, d.getDate());
  value = value.replace(/\[\[Month\]\]/g, d.getMonth() + 1);
  value = value.replace(/\[\[MonthName\]\]/g, GetMonthString(d.getMonth()));
  value = value.replace(/\[\[DayOfWeek\]\]/g, GetDayString(d.getDay()));
  value = value.replace(/\[\[Year\]\]/g, d.getFullYear());
  return value;
}

function OnLoad(e) {

}

var u45 = document.getElementById('u45');
gv_vAlignTable['u45'] = 'top';
var u27 = document.getElementById('u27');

var u16 = document.getElementById('u16');
gv_vAlignTable['u16'] = 'top';
var u17 = document.getElementById('u17');

var u28 = document.getElementById('u28');
gv_vAlignTable['u28'] = 'top';
var u42 = document.getElementById('u42');

var u29 = document.getElementById('u29');
gv_vAlignTable['u29'] = 'top';
var u8 = document.getElementById('u8');

var u30 = document.getElementById('u30');

var u6 = document.getElementById('u6');
gv_vAlignTable['u6'] = 'top';
var u32 = document.getElementById('u32');
gv_vAlignTable['u32'] = 'top';
var u35 = document.getElementById('u35');

var u13 = document.getElementById('u13');
gv_vAlignTable['u13'] = 'top';
var u14 = document.getElementById('u14');

var u15 = document.getElementById('u15');

var u43 = document.getElementById('u43');
gv_vAlignTable['u43'] = 'top';
var u41 = document.getElementById('u41');
gv_vAlignTable['u41'] = 'top';
var u44 = document.getElementById('u44');

var u4 = document.getElementById('u4');

var u1 = document.getElementById('u1');
gv_vAlignTable['u1'] = 'center';
var u10 = document.getElementById('u10');
gv_vAlignTable['u10'] = 'top';
var u39 = document.getElementById('u39');
gv_vAlignTable['u39'] = 'top';
var u11 = document.getElementById('u11');

var u38 = document.getElementById('u38');

var u12 = document.getElementById('u12');
gv_vAlignTable['u12'] = 'top';
var u26 = document.getElementById('u26');

var u9 = document.getElementById('u9');
gv_vAlignTable['u9'] = 'top';
var u40 = document.getElementById('u40');

var u7 = document.getElementById('u7');
gv_vAlignTable['u7'] = 'top';
var u3 = document.getElementById('u3');

var u23 = document.getElementById('u23');
gv_vAlignTable['u23'] = 'top';
var u24 = document.getElementById('u24');

var u25 = document.getElementById('u25');
gv_vAlignTable['u25'] = 'top';
var u47 = document.getElementById('u47');

var u2 = document.getElementById('u2');
gv_vAlignTable['u2'] = 'top';
var u18 = document.getElementById('u18');

var u19 = document.getElementById('u19');

var u20 = document.getElementById('u20');
gv_vAlignTable['u20'] = 'top';
var u36 = document.getElementById('u36');
gv_vAlignTable['u36'] = 'top';
var u5 = document.getElementById('u5');

var u48 = document.getElementById('u48');

var u22 = document.getElementById('u22');

var u37 = document.getElementById('u37');
gv_vAlignTable['u37'] = 'top';
var u21 = document.getElementById('u21');

var u46 = document.getElementById('u46');
gv_vAlignTable['u46'] = 'top';
var u33 = document.getElementById('u33');
gv_vAlignTable['u33'] = 'top';
var u31 = document.getElementById('u31');
gv_vAlignTable['u31'] = 'top';
var u34 = document.getElementById('u34');

var u0 = document.getElementById('u0');

if (window.OnLoad) OnLoad();
