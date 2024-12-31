$('input[type="text"]').on('input', function() {
    const textInput = $(this).val();
    const errorElement = $('#global_error');

    if (!/^[a-zA-Z\s'-]*$/.test(textInput)) {
        errorElement.show();
        $(this).val(textInput.replace(/[^a-zA-Z\s'-]/g, ''));
    } else {
        errorElement.hide();
    }
});