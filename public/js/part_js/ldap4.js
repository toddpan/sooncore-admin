//LDAP4
var property_info = {};
function nextStep4() {
    var ldap4_value = [];
    var ldap4_id = [];
    var i = 0;
    var count = 0;
    $('.ldapSetBox4 .selectBox span').each(function() {
        $(this).parent("div").removeClass("error");
        if ($(this).text() == "请选择对应的LDAP信息" ||$(this).text()=="") {
            $(this).parent("div").addClass("error");
            count = count + 1;
        }
        ldap4_id[i] = $(this).attr("id");
        ldap4_value[i] = $(this).text();
        i++;
    }) 
	if (count != 0) {
		$('.error4').hide();
        $('.error4').text("请为员工信息指定对应标签");
        return false;
    } else {
		$('.error4').hide();
        $('.ldapSetBox4 .ldapSetCont table.infoTable tr').each(function() {
            property_info[$(this).find('td:first').attr("name")] = $(this).find("td dd.selected").text();
        });
        $('.ldapSetBox4').hide();
        $('.ldapSetBox5').show();
		$('#idd').text("完成设置");
		$('#head_label a').removeClass("selected");
		$('#head_label a').removeClass("current");
		$('#head_label').find('a:eq(4)').addClass("selected");
		$('#head_label').find('a:eq(4)').addClass("current");
		$('#head_label .innerBar').css("width","100%");
		var back_next=$('#back_next');
		$('#back_next').remove();
		$('.ldapSetBox5 .optionList').css("height","130px");
		$('.ldapSetBox5').after(back_next);
	}
}
$(function() {
	$('.ldapSetBox4 .infoTable .selectBox').combo({
        cont: '>.text',
        listCont: '>.optionBox',
        list: '>.optionList',
        listItem: '.option'
    });
	$('.ldapSetBox4 .selectBox').die('click');
	$('.ldapSetBox4 .selectBox').live('click',function()
	{
		$(this).find(".optionBox").show();
	})
	$('.ldapSetBox4 .optionList dd').die('mouseover');
  	$('.ldapSetBox4 .optionList dd').live('mouseover',function()
	{
		$(this).addClass("hover");
	})
	$('.ldapSetBox4 .optionList dd').die('mouseout');
	$('.ldapSetBox4 .optionList dd').live('mouseout',function()
	{
		$(this).removeClass("hover");
	});
	
    $(".btn_addTag").click(function() {
        $(this).parents("tr").prev().show();
        $(this).parents("tr").prev().find(".input").val("");
        $(this).parents("tr").prev().find(".label").show();
        $(this).parents("tr").hide();
    })

    $("#otherLdap .checkbox").live("click",
    function() {
        if ($(this).hasClass("checked")) {
            $(this).parents("td").next().find(".combo").show();
        } else {
            $(this).parents("td").next().find(".combo").hide();
        }
    }) 
	$('.selectBox dd').click(function() {
        if ($(this).attr("target") != "0") {
            if ($(this).parents("div").hasClass("error")) {
                $(this).parents("div").removeClass("error");
            }
        }
    })
});