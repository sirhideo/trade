<?php 
    ob_start();
?>
<div class="page-header">
    <h4><i class="fas fa-tachometer-alt me-2"></i> Balance Sheet</h4>

</div>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Optional: Export Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- Accounts Table -->
<div class="card">

 <?php 
    session_start();
    include('./app_settings.php');
   
 ?>
    <div class="card-body">
     <?php 
      if($_SESSION['user_type'] == 'Admin' || is_admin($connect))
      {
        ?>
         <select class="form-control js-example-programmatic" name="investor_delete_id" placeholder="Select Investor Name" required id="investorSelect">
            <option value="">Select Investor Name</option>
        </select>
        <?php 
      }
      ?>

        <div style="width: 100%; max-width: 700px;" class="mt-3"> 
            <h5 id="investor_balance_line"></h5>
           
            <h5 title="Growth Rate" id="growth_line" style="display: none;"> <i class="fa fa-bar-chart" aria-hidden="true"></i><span title="Growth Rate" id="grate"  style="padding: 3px 12px; border-radius: 12px; font-weight: bold;font-size: 11px; margin-left: 10px;" >
                                    0.00%
                                </span>
                            </h5>
            <canvas id="equityChart" style="height: 180px;"></canvas>
        </div>
            <div class="row mt-4" id="tai_title" style="display: none;">
                <h5 >Trading Account information</h5>
                   
                   <div id="accountsContainer" class="row"></div>
                </div>
        <div class="row mt-4" id="balance_sheet_container" style="display: none;">
            <h5 class="mb-3 bg-black text-white p-2">Investor Balance Sheet</h5>
            <table id="balanceTable_admin" class="display table table-bordered table-striped border-black border-.5" style="width:100%; font-size: 14px;">
        <thead >
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="balance_detail_admin"></tbody>
    </table>
        </div>
        
            
            <style>
                .badge-green {
                    background-color: #d4edda;
                    color: #155724;
                    border: 1px solid #c3e6cb;
                }

                .badge-red {
                    background-color: #f8d7da;
                    color: #721c24;
                    border: 1px solid #f5c6cb;
                }

                .fade-in {
                    opacity: 0;
                    transition: opacity 0.6s ease-in-out;
                }

                .fade-in.show {
                    opacity: 1;
                }
            </style>

            
        </div>
        



        <script>
 function load_investor_trading_accounts(id){
    document.getElementById('tai_title').style.display = '';
    $.ajax({
        url: "balancesheet_ajax.php?load_trading_accounts_admin&id="+id+"&t=" + new Date().getTime(),
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("Accounts response:", response);

            $('#accountsContainer').empty();
            if(response.length > 0) {
                response.forEach(function(account) {
                    $('#accountsContainer').append(`
                        <div class="col-sm-4">
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Server</label></div>
                                        <div class="col-sm-7">${account.broker_server}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Account Number</label></div>
                                        <div class="col-sm-7">${account.trading_acct_number}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Investor Password</label></div>
                                        <div class="col-sm-7">${account.investor_password}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    `);
                });
            } else {
                $('#accountsContainer').html('<p>No trading accounts found.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error loading accounts:", error);
            $('#accountsContainer').html('<p>Error loading accounts.</p>');
        }
    });
}
function loadBalanceSheetAdmin(id) {
    var investorId = id;
document.getElementById('balance_sheet_container').style.display = '';
    // Destroy existing DataTable FIRST
    if ($.fn.DataTable.isDataTable("#balanceTable_admin")) {
        $('#balanceTable_admin').DataTable().destroy();
    }

    fetch("balancesheet_ajax.php?get_balance_sheet&investor_id=" + investorId)
    .then(res => res.json())
    .then(data => {
        let tbody = document.getElementById("balance_detail_admin");
        tbody.innerHTML = "";

        data.forEach((item, index) => {
            const formattedAmount = parseFloat(item.trans_amount).toLocaleString('en-US', {
                style: 'currency',
                currency: 'USD'
            });

            const formattedDate = new Date(item.trans_date).toLocaleDateString();
            const rowClass = item.trans_amount >= 0 ? '' : 'table-danger border-1 border-black';
            let row = `
                <tr class="${rowClass}">
                    <td>${formattedDate}</td>
                    <td>${formattedAmount}</td>
                    <td>${item.trans_desc}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        // Reinitialize DataTable
        dataTable = $('#balanceTable_admin').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard"></i> Copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    filename: 'Fund Management',
                    title: 'Fund Management'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    filename: 'Fund Management',
                    title: 'Fund Management'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-primary btn-sm'
                }
            ],
            responsive: true
        });
    });
}

            function load_investor() {


                $('#investorSelect').select2({
                    placeholder: "Select Investor Name",
                    ajax: {
                        url: 'investor_ajax.php', // PHP file to fetch investors
                        type: 'POST',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                loadinvestor: 1,
                                searchTerm: params.term // search term entered by user
                            };
                        },
                        processResults: function(data) {
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
                $('#resetPasswordForm').on('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const newPassword = $('#new_password').val();
                    const confirmPassword = $('#confirm_password').val();
                    const investor_id = $('#investorSelect').val();
                    $('#reset_investor_id').val(investor_id);
                    if (newPassword === '' || confirmPassword === '' || investor_id === '') {
                        Swal.fire({
                            title: 'Validation Error',
                            text: 'New Password, Confirm Password and investor are mandatory.',
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
                                            willClose: () => {

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
                load_investor(); // Load investors on page load
                


                $('#investorSelect').on('change', function() {
                    const investorId = $(this).val();
                    if (investorId) 
                    {
                        $('#investor_data_row').show();
                        $('#deposit_withdraw_row').show();
                        $('#reset_password_row').show();
                        load_chart(investorId); // Load chart data
                    } else {
                        $('#investor_data_row').hide();
                        $('#deposit_withdraw_row').hide();
                        $('#reset_password_row').hide();
                        if (chart) chart.destroy();
                        $('#equityChart').remove(); // Remove the canvas element
                        $('#investorSelect').after('<canvas id="equityChart" width="600" height="300"></canvas>'); // Recreate it
                    }
                });


            });

            function load_chart(investorId){
                let chart;
                document.getElementById('growth_line').style.display = '';
                $.ajax({
                            url: 'balancesheet_ajax.php',
                            type: 'POST',
                            data: {
                                investor_id: investorId,
                                get_investor_data: 1
                            },
                            dataType: 'json',
                            success: function(data) {
                               
                                const labels = data.map(item => item.trans_date);
                                const values = data.map(item => item.balance_line);

                                const growthRates = [];

                                // Calculate growth % from previous
                                for (let i = 0; i < values.length; i++) {
                                    if (i === 0) {
                                        growthRates.push(0);
                                    } else {
                                        const prev = values[i - 1];
                                        const curr = values[i];
                                        const growth = ((curr - prev) / prev) * 100;
                                        growthRates.push(growth.toFixed(2));
                                    }
                                }

                                if (chart) chart.destroy();

                                chart = new Chart(document.getElementById('equityChart'), {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Equity Growth',
                                            data: values,
                                            borderColor: 'black',
                                            fill: false,
                                            tension: 0.1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        plugins: {
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        const index = context.dataIndex;
                                                        const item = data[index];
                                                        const growth = growthRates[index];
                                                        return [
                                                            `Balance: $${item.balance_line}`,
                                                            `Type: ${item.trans_type}`,
                                                            `Amount: ${item.trans_amount}`,
                                                            `Detail: ${item.trans_desc}`,
                                                            `Growth Rate: ${growth}%`
                                                        ];
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Amount ($)'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Date'
                                                }
                                            }
                                        }
                                    }
                                });
                                const investorName = $('#investorSelect option:selected').text();
                                $('#investor_name').text(investorName);
                                const $grate = $('#grate');

                                if (data.length >= 2) {
                                    const first = parseFloat(data[0].balance_line);
                                    const last = parseFloat(data[data.length - 1].balance_line);

                                    let overallGrowthRate = 0;

                                    if (first > 0 && !isNaN(first) && !isNaN(last)) {
                                        overallGrowthRate = ((last - first) / first) * 100;
                                    }
                                   
                                    
                                    const rateText = overallGrowthRate.toFixed(2) + '%';
                                    const isPositive = overallGrowthRate >= 0;

                                    // Reset styles
                                    $grate.removeClass('badge-green badge-red fade-in show');

                                    // Set new value and style
                                    $grate
                                        .text(rateText)
                                        .addClass(isPositive ? 'badge-green' : 'badge-red')
                                        .addClass('fade-in');

                                    // Trigger fade-in
                                    setTimeout(() => $grate.addClass('show'), 50);
                                } else {
                                    $grate
                                        .removeClass('badge-green badge-red fade-in show')
                                        .text('0.00%');
                                }
                            }
                        });
            }
            function loadDepositWithdraw(investorId) {
                $.ajax({
                    url: 'balancesheet_ajax.php',
                    type: 'POST',
                    data: {
                        investor_id: investorId,
                        get_deposit_withdraw: 1
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#deposit_line').text("$"+data[0].total_deposit);
                        $('#withdraw_line').text("$"+data[0].total_withdraw);
                        $('#investor_balance_line').text('Balance: $'+data[0].investor_balance);
                    }
                });
            }
            $('#investorSelect').on('change', function() {
                const investorId = $(this).val();
                if (investorId) {
                    loadDepositWithdraw(investorId); // Load deposit and withdraw data
                    loadBalanceSheetAdmin(investorId); // Load deposit and withdraw data
                    load_investor_trading_accounts(investorId); // Load trading accounts
                }
            });
            <?php 
            if($_SESSION['user_type'] != 'Admin')
            {
            ?>
            $(document).ready(function() {
                loadDepositWithdraw(<?=$_SESSION['user_id'];?>); // Load deposit and withdraw data
                    loadBalanceSheetAdmin(<?=$_SESSION['user_id'];?>); // Load deposit and withdraw data
                    load_investor_trading_accounts(<?=$_SESSION['user_id'];?>); // Load trading accounts
                    load_chart(<?=$_SESSION['user_id'];?>); // Load chart data

            });
                 <?php }
                 ?>  
        </script>
    </div>
    <?php
    $test = 0;
   if($test== 1)
    {
        ?>
        <div class="card-body">
        <div class="row">
            <div class="col-sm-12">
            <h5 id="investor_balance_line_single"></h5>
                <div style="width: 100%; max-width: 700px;" class="mt-3">
                    <canvas id="equityChartsingle" style="height: 180px;"></canvas>
                </div>
                <div class="row mt-4">
                <h5>Trading Account information</h5>
                   
                   <div id="accountsContainer" class="row"></div>
                </div>
                <div class="row">
                <div class="container mt-4">
    <h5 class="mb-3 bg-black text-white p-2">Investor Balance Sheet</h5>



    <table id="balanceTable" class="display table table-bordered table-striped border-black border-.5" style="width:100%; font-size: 14px;">
        <thead >
            <tr>
                
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody id="balance_detail"></tbody>
    </table>
</div>
                </div>
            </div>
           
        </div>
        
        <script>
            $(document).ready(function() {
                loadinvestorbalance(<?=$_SESSION['user_id'];?>); // Load investor balance on page load
                get_chart_data(<?=$_SESSION['user_id'];?>); // Load chart data on page load
                loadBalanceSheet();
                load_investor_trading_accounts(<?=$_SESSION['user_id'];?>);
               
            });

            function loadinvestorbalance(investorId) {
                $.ajax({
                    url: 'balancesheet_ajax.php',
                    type: 'POST',
                    data: {
                        investor_id: investorId,
                        get_deposit_withdraw: 1
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log("data:", data);
                      
                        $('#investor_balance_line_single').text('Balance: $'+data[0].investor_balance);
                    }
                });
            }
            function load_investor_trading_accounts(){
    $.ajax({
        url: "balancesheet_ajax.php?load_trading_accounts&t=" + new Date().getTime(),
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("Accounts response:", response);
            $('#accountsContainer').empty();
            if(response.length > 0) {
                response.forEach(function(account) {
                    $('#accountsContainer').append(`
                        <div class="col-sm-4">
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Server</label></div>
                                        <div class="col-sm-7">${account.broker_server}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Account Number</label></div>
                                        <div class="col-sm-7">${account.trading_acct_number}</div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-5"><label>Investor Password</label></div>
                                        <div class="col-sm-7">${account.investor_password}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    `);
                });
            } else {
                $('#accountsContainer').html('<p>No trading accounts found.</p>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error loading accounts:", error);
            $('#accountsContainer').html('<p>Error loading accounts.</p>');
        }
    });
}


            let userChart; // Use camelCase for variable naming

function get_chart_data(investor_id) {
    if (investor_id) {
        $.ajax({
            url: 'balancesheet_ajax.php',
            type: 'POST',
            data: {
                investor_id: investor_id,
                get_investor_data: 1
            },
            dataType: 'json',
            success: function(data) {
                loadBalanceSheet(); // Load balance sheet data on chart data success
                // Ensure canvas exists
                if (!$('#equityChartsingle').length) {
                    $('<canvas id="equityChartsingle" style="height: 180px;"></canvas>')
                        .appendTo('#chart-container'); // Make sure you have a container div
                }

                const labels = data.map(item => item.trans_date);
                const values = data.map(item => item.trans_amount);

                const growthRates = [];

                // Calculate growth % from previous
                for (let i = 0; i < values.length; i++) {
                    if (i === 0) {
                        growthRates.push(0);
                    } else {
                        const prev = values[i - 1];
                        const curr = values[i];
                        const growth = ((curr - prev) / prev) * 100;
                        growthRates.push(growth.toFixed(2));
                    }
                }

                if (userChart) userChart.destroy();

                userChart = new Chart(document.getElementById('equityChartsingle'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Equity Growth',
                            data: values,
                            borderColor: 'black',
                            fill: false,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const index = context.dataIndex;
                                        const item = data[index];
                                        const growth = growthRates[index];
                                        return [
                                            `Amount: $${item.trans_amount}`,
                                            `Type: ${item.trans_type}`,
                                            `Detail: ${item.trans_desc}`,
                                            `Growth Rate: ${growth}%`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Amount ($)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching chart data:", error);
                // Optionally show an error message to the user
            }
        });
    } else {
        if (userChart) {
            userChart.destroy();
            userChart = null;
        }
        $('#equityChartsingle').remove();
    }
}
           
// Filter table rows as you type
document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#balance_detail tr");

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
    });
});


     
function loadBalanceSheet() {
    var investorId = <?=$_SESSION['user_id'];?>;

    fetch("balancesheet_ajax.php?get_balance_sheet&investor_id=" + investorId)
    .then(res => res.json())
        .then(data => {
            let tbody = document.getElementById("balance_detail");
            tbody.innerHTML = "";

            data.forEach((item, index) => {
                const formattedAmount = parseFloat(item.trans_amount).toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD'
                });

                const formattedDate = new Date(item.trans_date).toLocaleDateString();
                const rowClass = item.trans_amount >= 0 ? '' : 'table-danger border-1 border-black';
                let row = `
                    <tr class="${rowClass}">
                        <td>${formattedDate}</td>
                        <td>${formattedAmount}</td>
                        <td>${item.trans_desc}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // Destroy old DataTable if it exists
            if ($.fn.DataTable.isDataTable("#balanceTable")) {
                dataTable.destroy();
            }

            // Reinitialize DataTable
            dataTable = $('#balanceTable').DataTable({
                dom: 'Bfrtip',
    buttons: [
        {
            extend: 'copy',
            text: '<i class="bi bi-clipboard"></i> Copy',
            className: 'btn btn-secondary btn-sm'
        },
        {
            extend: 'excel',
            text: '<i class="bi bi-file-earmark-excel"></i> Excel',
            className: 'btn btn-success btn-sm'
        },
        {
            extend: 'pdf',
            text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
            className: 'btn btn-danger btn-sm'
        },
        {
            extend: 'print',
            text: '<i class="bi bi-printer"></i> Print',
            className: 'btn btn-primary btn-sm'
        }
    ],
    responsive: true
            });
        });
}

           
        </script>
        <?php
    }
    ?>

</div>
</div>
</div>
</div>