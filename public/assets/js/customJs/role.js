$(document).ready(function() {
    var appUrl = $('#appUrl').val();
    $('#roleForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize(); 
        let route = appUrl+"/add-new-role"
        $.ajax({
            type: 'POST',
            url: route, 
            data: formData,
            success: function(response) {
                if(response.status == 200){
                    toastr.success(response.message)
                    $('#myModal').hide();
                    $('#roleForm')[0].reset(); 
                    location.reload();
                   
                }
            },
            error: function(xhr, status, error) {
                
                let response = JSON.parse(xhr.responseText);
                if (response.message) {
                    toastr.error(response.message);
                    
                } else {
                    toastr.error('An unknown error occurred.');
                }
            }
        });
    });


// Use event delegation to handle events for dynamically added elements
// This listens for a change event on any element with the class 'toggle-status'
$(document).on('change', '.toggle-status', function() {
    // Get the role ID from the data-id attribute of the changed element
    var roleId = $(this).data('id');
    // Determine the status based on whether the checkbox is checked or not
    var status = $(this).prop('checked') ? '1' : '0';
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let route = appUrl+"/api/roles/toggle-status"
    // Send an AJAX request to update the role status on the server
    $.ajax({
        // The URL to send the request to, generated using a Laravel route helper
        url: route,
        // The HTTP method to use for the request
        type: 'POST',
        // The data to send with the request
        data: {
            // CSRF token for security, required by Laravel to validate the request
        
            // The role ID
            id: roleId,
            // The new status
            status: status
        },
        success: function(response) {
            if (response.success) {
                toastr.success('Status updated successfully');
            } else {
                toastr.error('Failed to update status');
            }
        },
        error: function() {
            toastr.error('Error updating status');
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
