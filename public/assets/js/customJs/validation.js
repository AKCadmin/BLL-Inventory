$('input[type="text"]').on('input', function() {
    if ($(this).attr('id') === 'admin_username') {
        return;
    }
    const textInput = $(this).val();
    const errorElement = $('#global_error');

    if (!/^[a-zA-Z\s'-]*$/.test(textInput)) {
        errorElement.show();
        $(this).val(textInput.replace(/[^a-zA-Z\s'-]/g, ''));
    } else {
        errorElement.hide();
    }
});