$(document).ready(function(){

$(window).scroll(function () {
    if ($(window).scrollTop() > 200) {
        $('#content-nav').addClass('fixed');
    } else {
        $('#content-nav').removeClass('fixed');
    }
});

$("li.dropdown").mouseenter(function(){
    $(this).addClass("open");
});
$("li.dropdown").mouseleave(function(){
    $(this).removeClass("open");
});

});

