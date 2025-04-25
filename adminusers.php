<div class="page-header">
                    <h4><i class="fas fa-users me-2"></i>Users</h4>
                    
                </div>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

            
                

                <!-- Accounts Table -->
                <div class="card">
                    
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" id="accountsTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Type</th>
           
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        
    </tbody>
</table>


                        </div>

                        <div class="row col-sm-4 border border-1 border-black p-3 mt-4" id="reset_box" style="display:none">
                            <h5>Reset Password for <span id="user_name"></span></h5>
                        <form id="resetPasswordForm" method="POST">
                    <input type="hidden" name="user_reset_password_by_admin" id="user_reset_password_by_admin" value="1">
                    <input type="hidden" name="user_id" id="user_id" value="0">

                    <div class="mb-3 mt-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary float-end">Reset Password</button>
                </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
$(document).ready(function() {

    $('#resetPasswordForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const newPassword = $('#new_password').val();
                    const confirmPassword = $('#confirm_password').val();
                    if (newPassword === '' || confirmPassword === '') {
                        Swal.fire({
                            title: 'Validation Error',
                            text: 'New Password, Confirm Password are mandatory.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: "#000",
                            willClose: () => {

                            }
                        });

                        return;
                    } else if (newPassword !== confirmPassword) {
                        Swal.fire({
                            title: 'Validation Error',
                            text: 'New Password and Confirm Password are mistmatch.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: "#000",
                            willClose: () => {

                            }
                        });

                        return;
                    }
                    Swal.fire({
                        title: 'Confirmation',
                        text: 'Are You Sure, You want to Reset Password.',
                        confirmButtonText: 'Continue',
                        showCancelButton: true,
                        allowOutsideClick: false,
                        confirmButtonColor: "#000",
                        cancelButtonColor: "#ccc",
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            $.ajax({
                                url: 'balancesheet_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    if (response.trim() === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Password reset successfully',
                                            icon: 'success',
                                            successButtonColor: "#000",
                                            willClose: () => {
                                                // reset the form fields
                                                $('#resetPasswordForm')[0].reset(); // Reset the form fields
                                                loadPage('adminusers.php', this);
                                            }
                                        })
                                    } else {
                                        Swal.fire({
                                            title: 'Error',
                                            text: 'Error in Password reset. ' + response.trim(),
                                            icon: 'error',
                                            confirmButtonText: 'Try Again',
                                            confirmButtonColor: "#000",

                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Server Error',
                                        text: 'Could not connect to the server. Please try again later.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: "#000",
                                        willClose: () => {

                                        }
                                    });

                                }
                            });
                        }
                    });
                });
    // Load trading accounts
    load_users();
    
    // Delete button click handler using event delegation
    $(document).on('click', '.delete-btn', function() {
        const accountId = $(this).data('id');
        const title = $(this).text().trim();
        ResetAccount(accountId,title);
       
    });
});
function reset_form(user_name,id){
    $('#user_name').text(user_name);
    $('#user_id').val(id);
    document.getElementById('reset_box').style.display="";
   document.getElementById('new_password').focus();
}
function load_users() {
    $.ajax({
        url: 'trading_ajax.php?get_users',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let rows = '';
            response.forEach(account => {
                rows += `
                    <tr>
                        <td data-title="Name"><a href="javascript:reset_form('${account.first_name}',${account.id})" style="text-decoration:none">${account.first_name}</a></td>
                        <td data-title="Username">${account.login_id}</td>
                        <td data-title="Type" style="text-transform: capitalize;">${account.user_type} ${account.is_admin == 1 ? ', Admin' : ''}</td>
                        
                        <td>
                            ${account.is_admin == 1 ? `
                                <button class="btn btn-sm btn-primary delete-btn" 
                                        title="Reset to Default" 
                                        data-id="${account.id}" style="background-color:#3085d6; border-color:#3085d6;">
                                   Assign Investor
                                </button>
                            ` : `<button class="btn btn-sm btn-outline-primary delete-btn" 
                                        title="Reset to Default" 
                                        data-id="${account.id}">
                                    Assign Administrator
                                </button>`}
                                <button class="btn btn-sm btn-outline-danger delete-btn" 
                                        title="Delete" 
                                        data-id="${account.id}">
                                    Delete
                                </button>
                        </td>
                    </tr>
                `;
            });

            // Update table body
            $('#accountsTable tbody').html(rows);

            // Destroy previous instance if exists
            if ($.fn.DataTable.isDataTable('#accountsTable')) {
                $('#accountsTable').DataTable().destroy();
            }

            // Reinitialize DataTable
            $('#accountsTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true,
                language: {
                    search: "Search Users:",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No matching users found",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "No entries available",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                }
            });
        },
        error: function() {
            alert('No user found..');
        }
    });
}


function ResetAccount(accountId,action) {
    var message = '';
    var returnmsg = '';
    if(action == 'Delete'){
        message = 'Are you sure you want to delete this account?';
        returnmsg = 'Account deleted successfully'
    }
    else{
        message = 'Are you sure you want to change this account?';
        returnmsg = 'Account type has changed successfully.'
    }
    Swal.fire({
        title: 'Confirmation',
        html: '<span style="color:#3085d6;">'+message+'</span>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Continue',
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'trading_ajax.php?reset_to_account',
                method: 'POST',
                data: { id: accountId, action: action },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        
                        Swal.fire({
                            title: 'Success',
                            html: '<span style="color:#3085d6;">'+returnmsg+'</span>',
                            icon: 'success',
                            confirmButtonColor: "#3085d6",
                        }).then(() => {
                            // Reload the table after successful deletion
                            loadPage('adminusers.php', this);
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error resetting  account: ' + data.message,
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to connect to server',
                        icon: 'error'
                    });
                }
            });
        }
    });
}
</script>










