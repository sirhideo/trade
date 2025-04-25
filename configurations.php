<div class="page-header">
                    <h4><i class="fas fa-cog me-2"></i>Configurations</h4>
                   
                </div>

                <!-- Search and Filter Section -->
                <style>
    .card {
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        padding: 0px;
    }

    .card-header {
        background-color: #fff;
        color: #000;
        padding: 10px;
        font-size: 18px;
        font-weight: bold;
        text-align: left;
        border-bottom: 1px solid #ccc;
    }

    .card-body {
        padding: 20px;
        background-color: #fff;
    }
    input[type="text"] {
    border: 1px solid #ccc;
    padding: 8px;
    border-radius: 4px;
    outline: none;
}

select {
    border: 1px solid #ccc !important;
    padding: 8px;
    border-radius: 4px;
    outline: none;
    background-color: #fff;
    color: #000;
    font-size: 14px;
    appearance: none;         /* Removes default arrow in some browsers */
    -webkit-appearance: none; /* For Safari/Chrome */
    -moz-appearance: none;    /* For Firefox */
    background-image: url('data:image/svg+xml;charset=US-ASCII,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="5"><polygon points="0,0 10,0 5,5" fill="%23000"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 10px 5px;
    padding-right: 30px; /* Space for custom arrow */
}

/* Keep border black on focus */
input[type="text"]:focus,
select:focus {
    border-color: #000;
    box-shadow: none;
    background-color: #fff;
    color: #000;
}

</style>
                <div class="row">
                        <div class="col-sm-5">
                            <div class="row">
                        <div class="card">
                            <div class="card-header">Create Investor Account</div>
                            <div class="card-body">
                                <div class="row">
                                    
                                    <form id="create_investor_account">
                                        <input type="hidden" name="create_investor" value="1">
                                        <div class="row">
                                        
                                            <label>Name</label>
                                            <input type="text" name="txt_investor" class="form-control" autocomplete="off" placeholder="Enter Investor Name" required>
                                        
                                        </div>
                                        <div class="row mt-2">
                                        
                                            <label>Investor Login ID</label>
                                            <input type="text" name="txt_investor_login_id" class="form-control" autocomplete="off" placeholder="Enter Investor Login ID" required>
                                        
                                        </div>
                                        <div class="row mt-2">
                                        
                                        <label>Investor Password</label>
                                        <input type="password" name="txt_investor_password" class="form-control" autocomplete="off" placeholder="Enter Investor Password" required>
                                    
                                    </div>
                                        <button type="submit" class="btn btn-primary mt-3 float-end"><i class="fas fa-save"></i> Save </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
                        <script>
                             $('#create_investor_account').on('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Confirmation',
                                html: '<span style="color:#3085d6;">Are you sure you want to create this investor account?</span>',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                               
                            $.ajax(
                                {
                                url: 'investor_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Investor account created successfully',
                                            icon: 'success',
                                            willClose: () => {
                                                $('#create_investor_account')[0].reset();
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in account creation. '+response.trim(),
                                                icon: 'error',
                                                confirmButtonText: 'Try Again',
                                                confirmButtonColor: "#000",
                                              
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#000",
                                            willClose: () => {
                                                body.classList.remove('blur-active');
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                        } 
                    });
                        });
                        </script>
                            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                        <!--
                            <div class="row">
                                <div class="card">
                                    <div class="card-header">Delete Investor Account</div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <form id="delete_investor_form">
                                                <input type="hidden" name="delete_investor">
                                                <div class="row">
                                                
                                                    <label>Name</label>
                                                    <select class="form-control js-example-programmatic" name="investor_delete_id" placeholder="Select Investor Name" required id="investorSelect">
                                                        <option value="">Select Investor Name</option>
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-danger mt-3 float-end"><i class="fas fa-trash"></i> Save </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    -->
                        <script>
                            function load_investor(){
                                
                                
                                $('#investorSelect').select2({
                                    placeholder: "Select Investor Name",
                                    ajax: {
                                        url: 'investor_ajax.php', // PHP file to fetch investors
                                        type: 'POST',
                                        dataType: 'json',
                                        delay: 250,
                                        data: function (params) {
                                            return {
                                                loadinvestor:1,
                                                searchTerm: params.term // search term entered by user
                                            };
                                        },
                                        processResults: function (data) {
                                            return {
                                                results: data // format should match: [{ id: 1, text: 'John Doe' }]
                                            };
                                        },
                                        cache: true
                                    },
                                    minimumInputLength: 1
                                });
                            }
                            function loader_investor_user(){
                                
                                
                                $('#investorSelect2').select2({
                                    placeholder: "Select Investor Name",
                                    ajax: {
                                        url: 'investor_ajax.php', // PHP file to fetch investors
                                        type: 'POST',
                                        dataType: 'json',
                                        delay: 250,
                                        data: function (params) {
                                            return {
                                                loadinvestor:1,
                                                searchTerm: params.term // search term entered by user
                                            };
                                        },
                                        processResults: function (data) {
                                            return {
                                                results: data // format should match: [{ id: 1, text: 'John Doe' }]
                                            };
                                        },
                                        cache: true
                                    },
                                    minimumInputLength: 1
                                });
                            }
                            $(document).ready(function() {
                                load_investor();
                                loader_investor_user();
                                load_default_url();
                            });
                            function load_default_url(){
                                $.ajax({
                                    url: 'investor_ajax.php',
                                    type: 'GET',
                                    data: {load_default_url:1},
                                    success: function(response) {
                                        var jsonString = '{"default_url":"http://localhost/trading_app/"}';

                                        // Parse the JSON
                                        var jsonObj = JSON.parse(jsonString);

                                        // Get the value
                                        var url = jsonObj.default_url;
                                        if (url != '') {
                                           
                                            $('#default_url').val(url);
                                        } else {
                                            $('#default_url').val('');
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#000",
                                            willClose: () => {
                                                body.classList.remove('blur-active');
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                            }
                            $('#delete_investor_form').on('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Confirmation',
                                html: '<span style="color:#3085d6;">Are You Sure, You want to Delete Investor Account?</span>',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                               
                            $.ajax(
                                {
                                url: 'investor_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        $('#investorSelect').val(null).trigger("change");;
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Investor account deleted successfully',
                                            icon: 'success',
                                            willClose: () => {
                                                load_investor();
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in account creation. '+response.trim(),
                                                icon: 'error',
                                                confirmButtonText: 'Try Again',
                                                confirmButtonColor: "#000",
                                                willClose: () => {
                                                load_investor();
                                            }
                                              
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#000",
                                            willClose: () => {
                                                body.classList.remove('blur-active');
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                        } 
                    });
                        });
                        </script>
                        
                        </div>
                        <div class="col-sm-1"></div>
                        <div class="col-sm-6 p-0">
                        <div class="row">
                        <div class="card">
                            <div class="card-header">Create Trading Account</div>
                            <div class="card-body">
                                <div class="row">
                                    
                                    <form id="create_trading_acct_form">
                                        <input type="hidden" name="create_trading_acct" value="1">
                                        <div class="row">
                                            <div class="col-sm-5">

                                                <label>Trading Account Number</label>
                                            </div>
                                            <div class="col-sm-7">

                                                <input type="text" name="txt_trading_acct_number" class="form-control" placeholder="Enter Account Number" required>
                                            </div>
                                        </div>
                                  
                                            <div class="row mt-2">
                                            <div class="col-sm-5">

                                                <label>Virtual Machine Name</label>
                                            </div>
                                            
                                            <div class="col-sm-7">

                                                <input type="text" name="txt_vm_name" class="form-control" placeholder="Enter Virtual Machine Name" required>
                                            </div>
                                            </div>
                                            <div class="row mt-2">
                                            <div class="col-sm-5">

                                                <label>Instance</label>
                                            </div>
                                            <div class="col-sm-7">

                                                <input type="text" name="txt_instance" class="form-control" placeholder="Enter Instance" required>
                                            </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-sm-5">

                                                    <label>Broker Server</label>
                                                </div>
                                                <div class="col-sm-7">

                                                    <input type="text" name="txt_broker_server" class="form-control" placeholder="Enter Broker Server" required>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-sm-5">

                                                    <label>Investor Password</label>
                                                </div>
                                                <div class="col-sm-7">

                                                    <input type="text" name="txt_investor_password" class="form-control" placeholder="Enter Investor Password" required>
                                                </div>
                                            </div>
                                        
                                        <button type="submit"  class="btn btn-primary mt-3 float-end"><i class="fas fa-save"></i> Save </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                             $('#create_trading_acct_form').on('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Confirmation',
                                html: '<span style="color:#3085d6;">Are You Sure, You want to Create Trading Account?</span>',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                               
                            $.ajax(
                                {
                                url: 'trading_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Trading account created successfully',
                                            icon: 'success',
                                            willClose: () => {
                                                $('#create_trading_acct_form')[0].reset();
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in account creation. '+response.trim(),
                                                icon: 'error',
                                                confirmButtonText: 'Try Again',
                                                confirmButtonColor: "#000",
                                              
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#000",
                                            willClose: () => {
                                                body.classList.remove('blur-active');
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                        } 
                    });
                        });
                        </script>
                        <div class="row">
                                <div class="card">
                                    <div class="card-header">General Configuration</div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            <form id="general_config_form">
                                                <input type="hidden" name="general_config" value="1">
                                                <div class="row">
                                                
                                                    <label>Default Website URL</label>
                                                    <input type="text" class="form-control" placeholder="Enter Website URL" name="default_url" id="default_url" required>
                                                </div>
                                                <div class="row mt-2">
                                                
                                                <label>Assign Admin user </label>
                                                <select class="form-control" placeholder="Select Investor" id="investorSelect2" name="investorSelect2">
                                                    <option value="">Select Admin User</option>
                                                    
                                                </select>
                                            </div>
                                                <button type="submit" class="btn btn-primary mt-3 float-end"><i class="fas fa-save"></i> Save </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <script>
                             $('#general_config_form').on('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Confirmation',
                                text: 'Are You Sure, You want to Update.',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#000",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                               
                            $.ajax(
                                {
                                url: 'investor_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        $('#investorSelect').val(null).trigger("change");;
                                        Swal.fire({
                                            title: 'Success',
                                            text: 'Configuration completed successfully',
                                            icon: 'success',
                                            willClose: () => {
                                                load_investor();
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in configuration. '+response.trim(),
                                                icon: 'error',
                                                confirmButtonText: 'Try Again',
                                                confirmButtonColor: "#000",
                                                willClose: () => {
                                                load_investor();
                                            }
                                              
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#000",
                                            willClose: () => {
                                                body.classList.remove('blur-active');
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                        } 
                    });
                        });
                        </script>

                        </div>
                </div>
                

               
                    
                    
                </div>
            </div>
        </div>
    </div>

    