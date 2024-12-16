$(document).ready(function() {
    var loader = $('#loader');

    function showLoader() {
        loader.show();
    }

    function hideLoader() {
        loader.hide();
    }

    // Show loader when any form is submitted
    $('form').on('submit', function() {
        showLoader();
    });

    // Hide the loader after the page is fully loaded
    $(window).on('load', function() {
        hideLoader();
    });

    // Show loader on AJAX start and hide on AJAX complete
    $(document).ajaxStart(function() {
        showLoader();
    }).ajaxStop(function() {
        hideLoader();
    });

        // Show the loader modal
        function showLoaderModal() {
            $('#loaderModal').modal('show');
        }
    
        // Hide the loader modal
        function hideLoaderModal() {
            $('#loaderModal').modal('hide');
        }
    
        // Show the loader modal on form submission
        $('form').on('submit', function() {
            showLoaderModal();
        });
    
        // Hide the loader modal when the page is fully loaded
        $(window).on('load', function() {
            hideLoaderModal();
        });
    
        // Show the loader modal on AJAX start and hide it on AJAX complete
        $(document).ajaxStart(function() {
            showLoaderModal();
        }).ajaxStop(function() {
            hideLoaderModal();
        });
});
