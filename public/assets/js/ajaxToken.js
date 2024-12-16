$.ajaxSetup({

    beforeSend: function(xhr,settings) {
        const dbName = localStorage.getItem('db_name');
        const token = localStorage.getItem('token');
        const User_role = localStorage.getItem('User_role');
        if (token) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        }

        if (dbName) {
            if (settings.type === 'GET' || settings.type === 'DELETE') {
                settings.url += (settings.url.indexOf('?') === -1 ? '?' : '&') + 'db_name=' + encodeURIComponent(dbName);
            } else if (settings.data) {
                settings.data += '&db_name=' + encodeURIComponent(dbName);
            } else {
                settings.data = 'db_name=' + encodeURIComponent(dbName);
            }
        }else{
            settings.data += '&User_role=' + encodeURIComponent(User_role);
        }
    },

});