$(document).ready(function(){

var mode = "single";

function set_ss(ss) {
    ss = ss.replace(/\s+/g, '');
    if (ss == '') {
        $('#pred3d').hide();
    } else {
        $('#pred3d').show();
    }
    $('[name="ss"]').val(ss);
}

$('#example1').click(function(){
    $('[name="seq"]').val('AAAACCCCTTTT');
    set_ss('.((......)).');
    $('[name="num"]').val(5);
    $('[name="pred_type"]').val("duplex");
});

$('#example2').click(function(){
    $('[name="seq"]').val('TTTTCCCCAAAACCCCTTTT');
    set_ss('33330000111100002222');
    $('[name="num"]').val(5);
    $('[name="pred_type"]').val("triplex");
});

$("#advanced-options").hide();
$("#a-advanced-options").click(function(){ $("#advanced-options").toggle(); });

$("#radio-compute_rmsd-yes").click(function(){ 
    if ($(this).val() == "yes" && $("#input-file-native_structure").val() == "") alert("Please upload the natvie structure!"); 
});

$('[name="seq"]').attr("placeholder", "Please input the sequence here, and only the 4 character 'A', 'T', 'G', 'C' are accepted.");
$('[name="seq"]').change(function(){
    $(this).val($(this).val().replace(/\s+/g, '').replace(/U/g, 'T').toUpperCase());
});

$('#pred3d').hide();
$('[name="ss"]').change(function(){
    set_ss($(this).val());
});

$('[name="form1"]').submit(function(){
    ss_e = $('[name="ss"]');
    seq_e = $('[name="seq"]');
    ss = ss_e.val();
    seq = seq_e.val();

    if (seq == '') {
        alert("Please input the sequence.");
        return false;
    } else if (ss == '') {
        alert("Please input the 2D structure.");
        return false;
    } else {
        return true;
    }
});

$('#pred2d').click(function(){
    ss_e = $('[name="ss"]');
    seq_e = $('[name="seq"]');
    type_e = $('[name="pred_type"]');
    ss = ss_e.val();
    seq = seq_e.val();
    type = type_e.val();

    ss_e.attr("placeholder", "The server is predicting the 2D structure...");
    $.get("/" + type +"_2d/" + seq, function(data, status){
        ss_e.attr("placeholder", '');
        set_ss(data);
        $('#pred3d').show();
    });
});

});

