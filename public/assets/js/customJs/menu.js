$(document).ready(function() {
    var appUrl = $('#appUrl').val();

    $('#addMenuForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: appUrl + '/api/add-new-menu',
            type: "POST",
            data: $(this).serialize(),
            success: function (response) {
                if (response.success) {
                    toastr.success('Menu add successfully')
                    $('#addMenuForm')[0].reset();  
                    location.reload();
                } else {

                    alert('Failed to update status');
                }
            },
            error: function (xhr) {
                const errors = xhr.responseJSON.errors;
                let errorHtml = '<div class="alert alert-danger">';
                $.each(errors, function (key, value) {
                    errorHtml += value[0] + '<br>';
                });
                errorHtml += '</div>';
                $('#errorAlert').html(errorHtml);
            }
        });
    });
    $(document).on('change', '.toggle-status', function() {
        // Get the menu ID from the data-id attribute of the changed element
        var menuId = $(this).data('id');
        // Determine the status based on whether the checkbox is checked or not
        var status = $(this).prop('checked') ? '1' : '0';
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        // Send an AJAX request to update the menu status on the server
        $.ajax({
            // The URL to send the request to, generated using a Laravel route helper
            url: appUrl + '/api/menus/toggle-status',
            // The HTTP method to use for the request
            type: 'POST',
            // The data to send with the request
            data: {
                // CSRF token for security, required by Laravel to validate the request
                // The menu ID
                id: menuId,
                // The new status
                status: status
            },
            // Function to call if the request succeeds
            success: function(response) {
                // If the server response indicates success
                if (response.success) {
                    // Show a success message to the user
                    toastr.success('Status updated successfully')
                  
                } else {
                    // Show an error message if the status update failed
                    toastr.alert('Failed to update status');
                }
            },
            // Function to call if the request fails
            error: function() {
                // Show a generic error message
                alert('Error updating status');
            }
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






// function toggleParentIdField() {
//     var selectBox = document.getElementById("is_parent_input");
//     var parentIdField = document.getElementById("parentIdField");

//     if (selectBox.value == "0") {
//         parentIdField.style.display = "block";
//         document.getElementById("parentIdField").value = '';

//     } else {
//         parentIdField.style.display = "none";
//         document.getElementById("parentIdField").value = '';

//     }
// }

// Call the function on page load to ensure the field is shown/hidden based on the old value
// window.onload = function() {
//     toggleParentIdField();
// };

});
var appUrl = $('#appUrl').val();
$('.delete-menu').on('click', function() {
    let row = $(this).closest('tr');
    let menuId = row.find('.toggle-status').data('id');
    console.log(menuId,"menuId")
    if (confirm('Are you sure you want to delete this item?')) {
        $.ajax({
            url: appUrl+'/api/menus/' + menuId,
            type: 'DELETE',
            dataType: 'json', // Ensure the response is parsed as JSON

            success: function(response) {
                if (response.success) {
                    toastr.success("Menu deleted successfully!"); 
                    location.reload(); 
                } 
              
            },
            error: function(xhr) {
                alert('An error occurred. Please try again.');
            }
        });
    }
});
