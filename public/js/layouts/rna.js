$(document).ready(function(){

$("ul.left-nav li a").click(function (event) {
    var li = $(this).parent();
    if (li.hasClass('unfolded') || li.hasClass('folded')) {
        li.toggleClass('unfolded');
        li.toggleClass('folded');
    }
});

});

