<div class="page-header">
    <h4><i class="fas fa-key me-2"></i> Change Password</h4>

</div>

<div class="card">

    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <form id="resetPasswordForm" method="POST">
                    <input type="hidden" name="user_reset_password" id="user_reset_password" value="1">

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
                        html: '<span style="color:#3085d6;">Are you sure you want to reset your password?</span>',
                        confirmButtonText: 'Continue',
                        showCancelButton: true,
                        allowOutsideClick: false,
                        confirmButtonColor: "#3085d6",
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
                                            html: '<span style="color:#3085d6;">Password reset successfully</span>',
                                            icon: 'success',
                                            confirmButtonColor: "#3085d6",
                                            willClose: () => {
                                                // reset the form fields
                                                $('#resetPasswordForm')[0].reset(); // Reset the form fields
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
            });

</script>