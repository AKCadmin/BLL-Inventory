function ajaxRequest(url, type = 'GET', data = {}, successCallback, errorCallback) {
    $.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'json',
        // beforeSend: function(xhr) {
        //     const token = localStorage.getItem('token');
        //     if (token) {
        //         xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        //     }
        // },
        success: function(response) {
            if (successCallback) successCallback(response);
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, error) {
                    errorHtml += `<li>${error[0]}</li>`;
                });
                errorHtml += '</ul></div>';
                $('#errorAlert').html(errorHtml);
            } else {
                $('#errorAlert').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
            }
            if (errorCallback) errorCallback(xhr);
        }
    });
}




