$(document).ready(function () {
    var appUrl = $("#appUrl").val();
    $("#permissionForm").submit(function (e) {
        e.preventDefault();

        let menuId = $("#menuId").val();
        let url = menuId
            ? appUrl + "/api/update-permission/" + menuId
            : appUrl + "/api/add-new-permission"; // Update or create based on menuId
        let type = menuId ? "PUT" : "POST"; // Use PUT if updating, POST if creating

        var formData = $(this).serialize();

        $.ajax({
            type: type, // Set the request type dynamically
            url: url, // Set the URL dynamically based on the presence of menuId
            data: formData,
            success: function (response) {
                if (response.status === 200) {
                    toastr.success(response.message);
                    $("#myModal").hide();
                    location.reload(); // Reload the page
                    $("#permissionForm")[0].reset(); // Reset the form
                } else {
                    toastr.error("Something went wrong. Please try again.");
                }
            },
            error: function (xhr, status, error) {
                // Handle the error response here
                var errorMessage =
                    xhr.responseJSON.message || "An error occurred";
                    toastr.error(errorMessage);
            },
        });
    });

    $(document).on("click", ".edit-permission", function () {
        const id = $(this).data("id");
        const role = $(this).data("role");
        const menusList = JSON.stringify($(this).data("menus"));
        const menus = JSON.parse(menusList);

        $('input[name="menu_options[]"]').prop("checked", false);

        menus.forEach(function (menu) {
            $(`input[name="menu_options[]"][value="${menu}"]`).prop(
                "checked",
                true
            );
        });
        loadAvailableRoles(role)
        $("#permissionId").val(id);
        $("#menuId").val(id);
        $("#autoSizingSelect").val(role);
        $(".menuStatus").val($(this).data("status"));
        $("#menus").val(menus.join(", "));
        $("#myModal").show();
    });

    function loadAvailableRoles(role) {
        $.ajax({
            url: `/api/available-roles/${role}`, 
            type: 'GET',
            success: function(response) {
                var select = $('#autoSizingSelect');
                select.empty();
                select.append('<option value="">Select Role &ensp;</option>'); 
      
                $.each(response, function(index, availableRole) {
                   
                    var selected = (availableRole.id == role) ? 'selected' : '';
                    
                    select.append('<option value="' + availableRole.id + '" ' + selected + '>' + availableRole.role_name + '</option>');
                });
            },
            error: function(xhr, status, error) {
                // Handle the error here
                alert('Error: ' + error);
            }
        });
    }
    
    loadAvailableRoles(null);

    // Use event delegation to handle events for dynamically added elements
    // This listens for a change event on any element with the class 'toggle-status'
    $(document).on("change", ".toggle-status", function () {
        var permissionId = $(this).data("id");
        var status = $(this).prop("checked") ? "1" : "0";

        $.ajax({
           
            url: appUrl + "/api/permissions/toggle-status",
          
            type: "POST",
           
            data: {
               
                id: permissionId,
                status: status,
            },
            success: function (response) {
                if (response.success) {
                    toastr.success("Status updated successfully");
                    loaction.reload();
                } else {
                    toastr.error("Failed to update status");
                }
            },
            error: function () {
                toastr.error("Error updating status");
            },
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
btn.onclick = function () {
    modal.style.display = "block";
};

// When the user clicks on <span> (x), close the modal and clear alerts
span.onclick = function () {
    modal.style.display = "none";
    $("#permissionForm input:checkbox, #permissionForm input:radio").prop('checked', false);
    $("#autoSizingSelect").prop('selectedIndex', 0);
    $("#permissionForm")[0].reset();
    clearAlerts();
};

// When the user clicks anywhere outside of the modal, close it and clear alerts
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
        clearAlerts();
    }
};

// Function to clear alert messages
function clearAlerts() {
    document.getElementById("errorAlert").innerHTML = "";
    document.getElementById("successAlert").innerHTML = "";
}
