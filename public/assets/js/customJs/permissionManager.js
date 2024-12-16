$(document).ready(function() {

    var appUrl = $('#appUrl').val();
    $('#permissionForm').submit(function(e) {
        e.preventDefault(); 

        var formData = $(this).serialize(); 

        $.ajax({
            type: 'POST',
            url: appUrl + "/api/add-new-permission", 
            data: formData,
            success: function(response) {
                
                if(response.status === 200){
                    toastr.success(response.message)
                    $('#myModal').hide();
                    $('#permissionForm')[0].reset();
                    loaction.reload();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                // Handle the error response here
                var errorMessage = xhr.responseJSON.message || 'An error occurred';
                alert('Error: ' + errorMessage);
            }
        });
    });


    // Use event delegation to handle events for dynamically added elements
    // This listens for a change event on any element with the class 'toggle-status'
    $(document).on('change', '.toggle-status', function() {
        // Get the permission ID from the data-id attribute of the changed element
        var permissionId = $(this).data('id');
        // Determine the status based on whether the checkbox is checked or not
        var status = $(this).prop('checked') ? '1' : '0';
        
        $.ajax({
            // The URL to send the request to, generated using a Laravel route helper
            url: appUrl + '/api/permissions/toggle-status',
            // The HTTP method to use for the request
            type: 'POST',
            // The data to send with the request
            data: {
                // The permission ID
                id: permissionId,
                // The new status
                status: status
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Status updated successfully')
                    loaction.reload();
                } else {
                    toastr.success('Failed to update status')
                }
            },
            error: function() {
                toastr.success('Error updating status')
            }
        });
    });
});


    // Get the modal
    var modal = document.getElementById("myModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openModalBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal and clear alerts
    span.onclick = function() {
        modal.style.display = "none";
        clearAlerts();
    }

    // When the user clicks anywhere outside of the modal, close it and clear alerts
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            clearAlerts();
        }
    }

    // Function to clear alert messages
    function clearAlerts() {
        document.getElementById("errorAlert").innerHTML = '';
        document.getElementById("successAlert").innerHTML = '';
    }
