$(document).ready(function() {

$('.collecting-form').hide();

// Handle form selection when multiple forms are within one block.
$('#content').on('change', '.collecting-form-select', function(e) {
    var thisSelect = $(this);
    thisSelect.siblings('.collecting-form').hide();
    thisSelect.siblings('.collecting-form-' + thisSelect.val()).show();
});

});
