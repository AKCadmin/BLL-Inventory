$(document).ready(function () {
    var appUrl = $("#appUrl").val();

    $('#phone_number').on('input', function () {
        let phone = $(this).val();
        if (phone.length > 10) {
            $('#phone-error').text('Phone number must be 10 digits.');
            $(this).val(phone.substring(0, 10)); 
        } else if (!/^\d*$/.test(phone)) {
            $(this).val(phone.replace(/\D/g, '')); 
            $('#phone-error').text('');
        }
    });
    
    $('#email').on('input', function () {
        let email = $(this).val();
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        
        if (!emailPattern.test(email)) {
            $('#email-error').text('Please enter a valid email address.');
        } else {
            $('#email-error').text(''); 
        }
    });
    
    $("#userForm").on("submit", function (e) {
        e.preventDefault();
    
        let hasErrors = false;
        $('#email-error').text(''); 
        $('#phone-error').text(''); 
    
        // Check phone number validity
        let phone = $("#phone_number").val();
        if (phone.length !== 10 || !/^\d{10}$/.test(phone)) {
            $('#phone-error').text('Phone number must be 10 digits.');
            hasErrors = true;
        }
    
        // Check email validity
        let email = $("#email").val();
        let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            $('#email-error').text('Please enter a valid email address.');
            hasErrors = true;
        }
    
        if (hasErrors) {
            return; 
        }
    
        let id = $("#user_id").val();
        var formData = $(this).serialize();
    
        function handleError(xhr) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
                $.each(errors, function (key, value) {
                    toastr.error(value[0]);
                });
            }
        }
    
        if (id) {
            $.ajax({
                url: "/api/user/update",
                type: "POST",
                data: formData,
            })
            .done(function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $("#global-loader").fadeOut();
                    $("#myModal").hide();
                    $("#userForm")[0].reset();
                    location.reload();
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                var errorMessage =
                    jqXHR.responseJSON?.message ||
                    "An unexpected error occurred. Please try again.";
                toastr.error(errorMessage);
                $("#global-loader").fadeOut();
            });
        } else {
            $.ajax({
                url: "/api/user/create",
                type: "POST",
                data: formData,
            })
            .done(function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    location.reload();
                    $("#global-loader").fadeOut();
                    $("#myModal").hide();
                    $("#userForm")[0].reset();
                } else {
                    toastr.error(response.message);
                }
            })
            .fail(handleError);
        }
    });
    
    
    
    $("#organization-filter").change(function (e) {
        e.preventDefault();
        let companyName = $(this).val();
        let formattedName = companyName.toLowerCase().replace(/\s+/g, "_");
        fetchUserList(formattedName);
    });

    function fetchUserList(companyName) {
        const route = "/user-list";
        $.ajax({
            url: route,
            method: "GET",
            data: {
                company: companyName,
            },
            success: function (response) {
                const data = response.data;
                const tbody = $("#user-list");
                tbody.empty(); // Clear any existing rows

                data.forEach((user) => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.username}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.roles.role_name}</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="toggle-status"
                                           data-id="${
                                               user.id
                                           }" data-toggle="toggle"
                                           data-on="Activated" data-off="Deactivated"
                                           ${
                                               user.is_verified === 1
                                                   ? "checked"
                                                   : ""
                                           }>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-user-btn" data-id="${
                                    user.id
                                }">Edit</button>
                                <button class="btn btn-sm btn-danger delete-user-btn" data-id="${
                                    user.id
                                }">Delete</button>
                            </td>
                        </tr>
                    `;
                    tbody.append(row);
                });
            },
            error: function () {
                alert("Error fetching user data.");
            },
        });
    }

    // $("#userForm").on("submit", function (e) {
    //     e.preventDefault();
    //     var formData = $(this).serialize();

    //     function handleError(xhr) {
    //         var errors = xhr.responseJSON.errors;
    //         if (errors) {
    //             $.each(errors, function (key, value) {
    //                 toastr.error(value[0]);
    //                 alert(value[0]);
    //             });
    //         }
    //     }
    //     let userId = $('#user_id').val();
    //     console.log(userId,"userId");
    //     let route =  userId == '' || userId == null ? appUrl + "/api/user/create" : appUrl + "/api/user/"+userId+"/update"
    //     $.ajax({
    //         url: route,
    //         type: "POST",
    //         data: formData,
    //     })
    //         .done(function (response) {
    //             if (response.success) {
    //                 toastr.success(response.message);
    //                 $('#myModal').hide();
    //                 location.reload();
    //             } else {
    //                 toastr.error(response.message);
    //             }
    //         })
    //         .fail(handleError);
    // });

    // $("#userForm").on("submit", function (e) {
    //     e.preventDefault();
    //     let id = $("#user_id").val();
    //     var formData = $(this).serialize();

    //     function handleError(xhr) {
    //         var errors = xhr.responseJSON.errors;
    //         if (errors) {
    //             $.each(errors, function (key, value) {
    //                 toastr.error(value[0]);
    //             });
    //         }
    //     }

    //     if (id) {
    //         $.ajax({
    //             url: appUrl + "/api/user/update",
    //             type: "POST",
    //             data: formData,
    //         })
    //             .done(function (response) {
    //                 if (response.success) {
    //                     toastr.success(response.message);
    //                     $("#global-loader").fadeOut();
    //                     $("#myModal").hide();
    //                     $("#userForm")[0].reset();
    //                     location.reload();
    //                 }
    //             })
    //             .fail(function (jqXHR, textStatus, errorThrown) {
    //                 var errorMessage =
    //                     jqXHR.responseJSON?.message ||
    //                     "An unexpected error occurred. Please try again.";
    //                 toastr.error(errorMessage);
    //                 $("#global-loader").fadeOut();
    //             });
    //     } else {
    //         $.ajax({
    //             url: appUrl + "/api/user/create",
    //             type: "POST",
    //             data: formData,
    //         })
    //             .done(function (response) {
    //                 if (response.success) {
    //                     // if(response.note){
    //                     toastr.success(response.message);
    //                     location.reload();
    //                     $("#global-loader").fadeOut();
    //                     $("#myModal").hide();
    //                     $("#userForm")[0].reset();
    //                     // }

    //                     // Make second API call only if the first one was successful
    //                     // $.ajax({
    //                     //     url: appUrl + "/api/user/migration",
    //                     //     type: "POST",
    //                     //     data: {
    //                     //         db_name: response.db_name,
    //                     //         user: response.user,
    //                     //     },
    //                     // })
    //                     //     .done(function (response) {
    //                     //         if (response.success) {
    //                     //             toastr.success(response.message);
    //                     //             $("#global-loader").fadeOut();
    //                     //             $("#myModal").hide();
    //                     //             $("#userForm")[0].reset();
    //                     //             location.reload();
    //                     //         } else {
    //                     //             toastr.error("Database migration failed.");
    //                     //         }
    //                     //     })
    //                     //     .fail(handleError);
    //                 } else {
    //                     toastr.error(response.message);
    //                 }
    //             })
    //             .fail(handleError);
    //     }
    // });

    $(document).on("change", ".toggle-status", function () {
        var permissionId = $(this).data("id");
        var status = $(this).prop("checked") ? "1" : "0";
        $.ajax({
            url: appUrl + "/api/user/status",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: permissionId,
                status: status,
            },

            success: function (response) {
                if (response.success) {
                    toastr.success("Status updated successfully");
                } else {
                    alert("Failed to update status");
                }
            },
            error: function () {
                alert("Error updating status");
            },
        });
    });

    $(document).on("click", ".edit-user-btn", function (e) {
        e.preventDefault();
        const userId = $(this).data("id");
        //var appUrl = $("#appUrl").val();
        let route = "/api/user/" + userId + "/edit";
        $.ajax({
            url: route,
            type: "GET",
            dataType: "json",
            success: function (response) {
                console.log(response, "response");
                if (response.success) {
                    const user = response.user;
                    $("#user_id").val(user.id);
                    $("#module_name").val(user.module_name);
                    $("#organizationId").val(user.organization_id);
                    $(".role").val(user.role);
                    $("#admin_username").val(user.username);
                    const [firstName, lastName] = user.name.split(" ", 2);
                    $("#admin_firstname").val(firstName);
                    $("#admin_lastname").val(lastName);
                    $("#phone_number").val(user.phone);
                    $("#email").val(user.email);
                    $("#current_status").val(user.is_activated);
                    $("#submit").html("Update");
                    $("#modal_header").html("Update a user");

                    // Show the modal
                    $("#myModal").show();
                }
            },
            error: function (xhr) {
                console.error("Error fetching user data:", xhr.responseText);
                alert("Failed to fetch user details.");
            },
        });
    });

    $(document).on("click", ".delete-user-btn", function (e) {
        e.preventDefault();

        const userId = $(this).data("id");
        const token = $('meta[name="csrf-token"]').attr("content");
        const route = "/api/users/" + userId;

        if (confirm("Are you sure you want to delete this user?")) {
            $.ajax({
                url: route,
                type: "DELETE",
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $(`button[data-id="${userId}"]`).closest("tr").remove();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    alert("An error occurred while trying to delete the user.");
                    console.error(xhr.responseText);
                },
            });
        }
    });

    ajaxRequest(
        appUrl + "/company/data",
        "GET",
        null,
        function (data) {
            console.log(data, "data");
            $("#companyId")
                .empty()
                .append('<option value="">Select a company</option>');
            $.each(data.companies, function (index, company) {
                $("#companyId").append(
                    '<option value="' +
                        company.id +
                        '">' +
                        company.name +
                        "</option>"
                );
            });
        },
        function (error) {
            console.log("Error fetching categories:", error);
        }
    );



    var modal = $("#myModal");

    $("#openModalBtn").click(function () {
        $("#submit").html("Create");
        $("#modal_header").html("Create a user");
        $("#userForm")[0].reset();
        modal.show();
    });

    $(".close").click(function () {
        modal.hide();
        clearAlerts();
    });

    $(window).click(function (event) {
        if (event.target === modal[0]) {
            modal.hide();
            clearAlerts();
        }
    });

    function clearAlerts() {
        $("#errorAlert").html("");
        $("#successAlert").html("");
    }
});
