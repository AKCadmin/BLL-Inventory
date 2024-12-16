$(document).ready(function () {
    var appUrl = $("#appUrl").val();

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

    $("#userForm").on("submit", function (e) {
        e.preventDefault();
        let id = $("#user_id").val();
        var formData = $(this).serialize();

        function handleError(xhr) {
            var errors = xhr.responseJSON.errors;
            if (errors) {
                $.each(errors, function (key, value) {
                    toastr.error(value[0]);
                    alert(value[0]);
                });
            }
        }

        if (id) {
            $.ajax({
                url: appUrl + "/api/user/update",
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
                url: appUrl + "/api/user/create",
                type: "POST",
                data: formData,
            })
                .done(function (response) {
                    if (response.success) {
                        // Make second API call only if the first one was successful
                        $.ajax({
                            url: appUrl + "/api/user/migration",
                            type: "POST",
                            data: {
                                db_name: response.db_name,
                                user: response.user,
                            },
                        })
                            .done(function (response) {
                                if (response.success) {
                                    toastr.success(response.message);
                                    $("#global-loader").fadeOut();
                                    $("#myModal").hide();
                                    $("#userForm")[0].reset();
                                    loaction.reload();
                                } else {
                                    toastr.error("Database migration failed.");
                                }
                            })
                            .fail(handleError);
                    } else {
                        toastr.error(response.message);
                    }
                })
                .fail(handleError);
        }
    });

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

    $(".edit-user-btn").on("click", function (e) {
        e.preventDefault();
        const userId = $(this).data("id");
        var appUrl = $("#appUrl").val();
        let route = appUrl + "/api/user/" + userId + "/edit";
        $.ajax({
            url: route,
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    const user = response.user;
                    $("#user_id").val(user.id);
                    $("#module_name").val(user.module_name);
                    $("#companyId").val(user.company_id);
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
                        alert(response.message);
                        $(`button[data-id="${userId}"]`).closest("tr").remove();
                    } else {
                        alert(response.message);
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
