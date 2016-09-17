function init_sortable() {
    $('.sortable thead th.sort-button').each(function(col){
        var text = $(this).text();
        $(this).empty();
        $(this).append("<a>" + text + "</a>");
        $(this).append("<div class='sort-direct'><div class='up-direct'></div><div class='down-direct'></div></div>");
    });

    var sort_direct = true;

    $('.sortable thead tr th').each(function(col){
        if ($(this).hasClass("sort-button")) {
            $(this).find("a").click(function(){
                var is_number = $(this).parent().hasClass("sort-num");
                var result = $(this).parents("table.sortable").find("tbody tr").sort(
                    is_number ?  function(a, b) {
                        return ((Number($(a).find("td").eq(col).text()) > Number($(b).find("td").eq(col).text())) ^ sort_direct) ? 1 : -1;
                    } : function(a, b) {
                        return (($(a).find("td").eq(col).text() > $(b).find("td").eq(col).text()) ^ sort_direct) ? 1 : -1;
                });
                sort_direct = !sort_direct;
                $(this).parents("table.sortable").find("tbody").empty().append(result);
                change_page($(this).parents("table.sortable"), $rows_page, $num_page);
//                if (sort_direct) {
//                    sort_direct = false;
//                    $(this).parent().find(".sort-direct .up-direct").css("border-bottom-color", "#D0D0D0");
//                    $(this).parent().find(".sort-direct .down-direct").css("border-top-color", "transparent");
//                } else {
//                    sort_direct = true;
//                    $(this).parent().find(".sort-direct .up-direct").css("border-bottom-color", "transparent");
//                    $(this).parent().find(".sort-direct .down-direct").css("border-top-color", "#D0D0D0");
//                }
            });
        }
    });


    $(".select-all").click(function(){
        var temp = $(this).prop("checked");
        $(".select-item").each(function(){
            $(this).prop("checked", temp);
        });
    });

}

function get_table_text() {
    var table_contents = [];

    $('.sortable tbody tr').each(function(row){
        table_contents.push([]);
        var line = $(this).find("td");
        line.each(function(col) {
            table_contents[row].push($(this).text());
        });
    });

    return table_contents;
};

function get_table_html() {
    var table_contents = [];

    $('.sortable tbody tr').each(function(row){
        table_contents.push([]);
        var line = $(this).find("td");
        line.each(function(col) {
            table_contents[row].push($(this).html());
        });
    });

    return table_contents;
};


