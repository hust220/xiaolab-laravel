$(document).ready(function() {

$("ul.tab").each(function() {
    $(this).find("li.tab-default").css({"background-color": "rgb(107, 152, 191", "color": "white"});
    $(this).find("li").click(function(){
        $(this).parent().find("li").css({"background-color": "white", "color": "black"});
        $(this).css({"background-color": "rgb(107, 152, 191", "color": "white"});
    });

});





});





