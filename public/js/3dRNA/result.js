$(document).ready(function(){
    init_sortable();

    $("#a-redo").click(function(event){
        return confirm("Are you sure redo this job?");
    });
});

