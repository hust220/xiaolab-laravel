function change_page(table, n, i) {
    $(table).find("tbody tr").slice(0, i*n).hide();
    $(table).find("tbody tr").slice(i*n, i*n+n).show();
    $(table).find("tbody tr").slice(i*n+n).hide();
}

function set_page_nums() {
    $("ul.page-nums").empty();
    $("ul.page-nums").append("<li class='prev-page'><a>Prev</a></li>");
    if ($num_pages <= 7) {
        for (i = 0; i < $num_pages - 1; i++) {
            $("ul.page-nums").append("<li><a>" + String(i+1) + "</a></li>");
        }   
    } else {
        $("ul.page-nums").append("<li><a>1</a></li>");
        if ($num_page > 3) $("ul.page-nums").append("<li class='ellipsis'>...</li>");
        for (i = 0; i < 5; i++) {
            $num_init = $num_page - 1;
            if ($num_page < 3) $num_init = 2; else if ($num_page > $num_pages - 4) $num_init = $num_pages-5;
            $("ul.page-nums").append("<li><a>" + String($num_init+i) + "</a></li>");
        }
        if ($num_page < $num_pages - 4) $("ul.page-nums").append("<li class='ellipsis'>...</li>");
    }
    $("ul.page-nums").append("<li class='final-page'><a>" + String($num_pages) + "</a></li>");
    $("ul.page-nums").append("<li class='next-page'><a>Next</a></li>");
    $("ul.page-nums").find("li").each(function (index) {
        if (parseInt($(this).text()) == $num_page + 1) $(this).addClass("selected");
    });
}

$(document).ready(function () {

$rows_page = 15;
$num_page = 0;
$rows_table = $(".pageable").find("tbody tr").length;
$num_pages = Math.floor($rows_table/$rows_page) + 1;

change_page($(".pageable"), $rows_page, $num_page);

$(".pageable").after("<div class='table-page-control clearfix' style='-moz-user-select:none;' onselectstart='return false;'>" +
                       "<ul class='page-nums'></ul></div>");

set_page_nums();

$("ul.page-nums").delegate("li", "click", function (event) {
    if ($(this).hasClass("prev-page")) {
        if ($num_page > 0) $num_page = $num_page - 1;
    } else if ($(this).hasClass("next-page")) {
        if ($num_page < $num_pages - 1) $num_page = $num_page + 1;
    } else if ($(this).hasClass("ellipsis")) {
        return;
    } else {
        $num_page = parseInt($(this).text()) - 1;
    }
    change_page($(".pageable"), $rows_page, $num_page);
    set_page_nums();
    $(window).scrollTop(190);
});

});




