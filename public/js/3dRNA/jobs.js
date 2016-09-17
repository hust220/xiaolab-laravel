$(document).ready(function(){

init_sortable();

$('[type="time"]').each(function(){
    s = $(this).text();
    s = new Date(parseInt(s) * 1000).toLocaleString();
    $(this).text(s);
});

});

