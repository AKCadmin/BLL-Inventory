window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Immediately redirect the user to the logout URL
        window.location.href = '/logout';
    }
});
