<div class="page-header">
    <h4><i class="fas fa-chart-line me-2"></i> Trading Account Management</h4>

</div>

<div class="card">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover" id="accountsTable">
                <thead>
                    <tr>
                        <th>Trading Account Number</th>
                        <th>Virtual Machine Name</th>
                        <th>Instance</th>
                        <th>Broker Server</th>
                        <th>Trade Running</th>
                        <th>Investor Password</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>


        </div>
    </div>

</div>
</div>
</div>
</div>

<script>
    $(document).ready(function() {
        // Load trading accounts
        loadTradingAccounts();

        // Delete button click handler using event delegation
        $(document).on('click', '.delete-btn', function() {
            const accountId = $(this).data('id');
            deleteTradingAccount(accountId);
        });
    });

    function loadTradingAccounts() {
    $.ajax({
        url: 'trading_ajax.php?get_trading_accounts',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let rows = '';
            response.forEach(account => {
               

                rows += `
                <tr>
                    <td data-title="Account">${account.trading_acct_number}</td>
                    <td data-title="VM">${account.vm_name}</td>
                    <td data-title="Instance">${account.instance}</td>
                    <td data-title="Server">${account.broker_server}</td>
                    <td data-title="Trading">${account.trading_status}</td>
                    <td data-title="Investor Password">${account.investor_password}</td>
                    <td data-title="Date">${account.created_date}</td>
                    <td>
            ${account.trading_status == 'No' ? `<button class="btn btn-sm btn-outline-danger delete-btn" 
                                title="Delete" 
                                data-id="${account.id}">
                            <i class="fas fa-trash"></i> Remove
                        </button>` : ''}
                        
                    </td>
                </tr>
                `;
            });
            $('#accountsTable tbody').html(rows);
        },
        error: function() {
            alert('No Account found..');
        }
    });
}


    function deleteTradingAccount(accountId) {
        Swal.fire({
            title: 'Confirmation',
            text: 'Are you sure you want to delete this trading account?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'trading_ajax.php?delete_trading_account',
                    method: 'POST',
                    data: {
                        id: accountId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success',
                                text: 'Trading account deleted successfully',
                                icon: 'success'
                            }).then(() => {
                                // Reload the table after successful deletion
                                loadTradingAccounts();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: 'Error deleting account: ' + data.message,
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