$(document).ready(function(){

// set onclick function of query button
$("#submit").click(function(){
    var content = {};
    content[$("select").prop("name")] = $("select").prop("value");
    $(":text").each(function(){
        content[$(this).prop("name")] = $(this).prop("value");
    });
    $.post("CADNAS/submit", content, function(data){
        $("#result-panel").empty();
        $("#result-panel").html(data);
        init_sortable();
    });
});

$("#result-panel").delegate("#select-all", "click", function(){
    var temp = $(this).prop("checked");
    $(".select-td").each(function(){
        $(this).prop("checked", temp);
    });
});

});


