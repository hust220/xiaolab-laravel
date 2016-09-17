$(document).ready(function(){

$("#seq").attr('placeholder', "Example: GGCGUAAGGAUUACCUAUGCC");

$("#seq").change(function() {
    $(this).val($(this).val().replace(/\s+/g, '').toUpperCase());
});

});

