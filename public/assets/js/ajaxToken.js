$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    beforeSend: function (xhr, settings) {
        const dbName = localStorage.getItem('db_name');
        const token = localStorage.getItem('token');

        // Add Authorization header if token exists
        if (token) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + token);
        }

        // Append db_name to requests
        if (dbName) {
            if (settings.type === 'GET' || settings.type === 'DELETE') {
                // Append db_name as a query parameter
                settings.url += (settings.url.indexOf('?') === -1 ? '?' : '&') + 'db_name=' + encodeURIComponent(dbName);
            } else if (settings.data instanceof FormData) {
                // Append db_name directly to FormData
                settings.data.append('db_name', dbName);
            } else if (typeof settings.data === 'object' && settings.data !== null) {
            // If data is an object (including arrays), append db_name
            settings.data.db_name = dbName;
            } else if (settings.data) {
                // Append db_name to string-based data
                settings.data += '&db_name=' + encodeURIComponent(dbName);
            } else {
                // Create string-based data if none exists
                settings.data = 'db_name=' + encodeURIComponent(dbName);
            }
        }
    },

});
