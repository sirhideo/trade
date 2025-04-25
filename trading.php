<div class="page-header">
                    <h4><i class="fa-solid fa-chart-simple me-2"></i> Trading</h4>
                </div>
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
               
                <div class="card p-1 border-dark">
                            <div class="card-header bg-light border-bottom border-dark">General Information</div>
                            <div class="card-body">
                                
                                <form id="add_investment_form">
                                                <input type="hidden" name="create_investment" value="1">
                                                <div class="row col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Trading Account Number</label>
                                                    </div>
                                                    <div class="col-sm-8">

                                                        <select id="tradingAccountSelect" name="txt_trading_acct_number" class="form-control" placeholder="Enter Account Number" required>
                                                            <option value="">Search Trading Account number</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Server</label>
                                                    </div>
                                                    <div class="col-sm-8">

                                                        <input type="text" id="broker_server" readonly name="txt_broker_server" class="form-control" placeholder="Enter Broker Server" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Instance</label>
                                                    </div>
                                                    <div class="col-sm-8">

                                                        <input type="text" id="instance" readonly name="txt_instance" class="form-control" placeholder="Enter Instance" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Virtual Machine Name</label>
                                                    </div>
                                                    
                                                    <div class="col-sm-8">

                                                        <input type="text" id="vm_name" readonly name="txt_vm_name" class="form-control" placeholder="Enter Virtual Machine Name" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Target Investment</label>
                                                    </div>
                                                    
                                                    <div class="col-sm-8">

                                                        <input type="text" id="target_investment" name="txt_target_investment" class="form-control" placeholder="Enter Target Investment" required>
                                                    </div>
                                                </div>
                                                <div class="row mt-2 col-sm-6">
                                                    <div class="col-sm-4 text-end">

                                                        <label>Initial Deposit</label>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <input type="hidden" id="hidden_initial_deposit" name="hidden_initial_deposit" class="form-control" required>
                                                        <input type="text" id="initial_deposit" readonly name="txt_initial_deposit" class="form-control" required>
                                                    </div>
                                                </div>
                                            
                                  
                            <div class="row mt-3">
                                <div class="card-header bg-light border-bottom border-top border-dark">Investor</div>
                            <div class="col-sm-6 mt-4">
                            <div class="row" id="investor_select_row">
                                <div class="col-sm-4 text-end">
                                    <label>Select Investor</label>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control js-example-programmatic" name="investor_delete_id" placeholder="Select Investor Name" required id="investorSelect">
                                            <option value="">Select Investor Name</option>
                                    </select>
                                </div>           
                                                    
                           </div>

                            <div class="row mt-2" id="investor_balance_row">
                                <div class="col-sm-4 text-end">
                                    <label>Investor Balance</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" id="investor_balance" readonly name="txt_investor_balance" class="form-control" placeholder="Enter Investor Balance" required>
                                <input type="hidden" id="hidden_investor_balance" readonly name="hidden_investor_balance" class="form-control" placeholder="Enter Investor Balance" required>
                                </div>
                        </div>
                        <div class="row mt-2" id="assign_fund_row">
                                <div class="col-sm-4 text-end">
                                    <label>Assign Fund</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number"  id="assign_fund"  name="txt_assign_fund" class="form-control" placeholder="Enter Investor Balance" required>
                                    <button type="submit" class="btn btn-primary mt-2" id="assignFundBtn">Add</button>
                                </div>
                        </div>
                            </div>
                            </form>
                           
                            <div class="col-sm-5 mt-4  border border-1 border-dark bg-black text-white mt-3 text-center float-end">
                                <div class="card-header bg-black text-warning border-bottom border-1 border-secondary">Investment Ratio</div>
                                    <div class="row border-bottom border-1 border-secondary  p-2  ">
                                    <div id="investmentRatioContainer"></div>   
                                    
                                    
                                </div>
                                
                            </div>
                            <div class="col-sm-12 text-center mt-4" id="commit_row">
                                <button onclick="commit_trade()" type="button" class="btn btn-success"> Commit Trade </button>
                            </div>
                            
                            
                        
                </div>
                <div class="row mt-4" id="settlement_row" style="display: none;">
                    <div class="card-header bg-light border-bottom border-top border-dark">Settlement:</div>
                    <form id="settle_account_form">
                        <input type="hidden" name="settle_account" value="1">
                        <input type="hidden" name="trading_account_id" id="trading_account_id" value="">
                       
                    <div class="row mt-4 col-sm-6">
                                <div class="col-sm-4 text-end">
                                    <label>Profit / Loss</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" id="settlement_amount"  name="txt_settlement_amount" class="form-control" placeholder="Enter Profit or Loss" required>
                                    <button type="submit" class="btn btn-primary mt-2" id="settle_account">Settle Trade</button>
                                </div>
                        </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <script>
function commit_trade(){
    let trade_account_id = $('#tradingAccountSelect').val();
    Swal.fire({
                                title: 'Confirmation',
                                html: '<span style="color:#3085d6;">Start the Trade?</span>',
                                confirmButtonText: 'Start',
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
                                data: {
                                    commit_trade:1,
                                    txt_trading_acct_number:trade_account_id
                                },
                                success: function(response) {
                                    fetchAllInvestments();
                                   
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            html: '<span style="color:#3085d6;">Trade Commit and Start successfully.</span>',
                                            icon: 'success',
                                            confirmButtonColor: "#3085d6",
                                            willClose: () => {
                                                loadPage('trading.php', this);
                                               
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in Trade Starting. '+response.trim(),
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
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                                }
                            }
                        )
}
 $('#settle_account_form').on('submit', function(e) {
    let selelect_trade = $('#tradingAccountSelect').val();
    if(selelect_trade == "" || 0){
        Swal.fire({
            title: 'Error',
            text: 'Please select trading account.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: "#000",
        });
        return;
    }
    else
    {
    $('#trading_account_id').val(selelect_trade);
                            e.preventDefault();
                            Swal.fire({
                                title: 'Confirmation',
                                html: '<span style="color:#3085d6;">Settle this trade?</span>',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                              
                               
                                
                                // Perform the AJAX request to save the investment
                            $.ajax(
                                {
                                url: 'trading_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    fetchAllInvestments();
                                   
                                    console.log(response);
                                    if (response.trim() === 'success') {
                                        Swal.fire({
                                            title: 'Success',
                                            html: '<span style="color:#3085d6;">Trade has been settled</span>',
                                            icon: 'success',
                                            confirmButtonColor: "#3085d6",
                                            willClose: () => {
                                                loadPage('trading.php', this);
                                               
                                            }
                                        })
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in settlement. '+response.trim(),
                                                icon: 'error',
                                                confirmButtonText: 'Try Again',
                                                confirmButtonColor: "#3085d6",
                                              
                                            });
                                        }
                                    },
                                    error: function () {
                                        Swal.fire({
                                            title: 'Server Error',
                                            text: 'Could not connect to the server. Please try again later.',
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            confirmButtonColor: "#3085d6",
                                            willClose: () => {
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                        } 
                    });
                }
                        });

         function get_trading_account_data(selectedId){
        $.ajax({
                url: 'trading_ajax.php?get_single_trading_account',
                method: 'GET',
                data: { id: selectedId },
                dataType: 'json',
                success: function(data) {
                    $('#vm_name').val(data.vm_name);
                    $('#instance').val(data.instance);
                    $('#broker_server').val(data.broker_server);
                    $('#initial_deposit').val(data.total_initial_deposit);
                    $('#hidden_initial_deposit').val(data.total_initial_deposit);
                    if(data.trading_status == 'Yes'){
                        $('#commit_row').hide();
                        $('#assign_fund_row').hide();
                        $('#investor_balance_row').hide();
                        $('#investor_select_row').hide();
                        $('#settlement_row').show();
                    }
                    else{
                        $('#commit_row').show();
                        $('#assign_fund_row').show();
                        $('#investor_balance_row').show();
                        $('#investor_select_row').show();
                        $('#settlement_row').hide();
                    }
                    
                   
                }
            });
    }
        function deleteInvestorFromDB(entry_id) {
          
                                    $.ajax({
        url: 'trading_ajax.php',
        type: 'POST',
        data: {
            delete_investment:1,
            entry_id: entry_id
        },
        success: function(response) {
            if (response.trim() === 'success') {
                get_trading_account_data($('#tradingAccountSelect').val());
                fetchAllInvestments(); 
                                        
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in investment deleting. '+response.trim(),
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
                                           
                                        });
        }
    });
    

                                }
                        
    
        function fetchAllInvestments() {
            let trade_account_id = $('#tradingAccountSelect').val();
    $.ajax({
        url: 'trading_ajax.php',
        type: 'POST',
        data: {
            get_all_investments:1,
            trade_account_id: trade_account_id
        },
        success: function(response) {
            const res = JSON.parse(response);
            if (res.success) {
                renderInvestments(res.data);
            } else {
                alert('Failed to fetch investments');
            }
        }
    });
}
function renderInvestments(data){
    const container = document.getElementById('investmentRatioContainer');
    container.innerHTML = ''; // Clear previous rows
    let total_investment = 0;
    data.forEach(entry => {
        total_investment = parseInt(total_investment) + parseInt(entry.invested_amount);
        const row = document.createElement('div');
        row.className = 'row border-bottom border-1 border-secondary py-2 align-items-center';
        row.id = 'row_' + entry.entry_id;
        row.innerHTML = `
            <div class="col-sm-4 text-center">
                <label>${entry.first_name}</label>
            </div>
            <div class="col-sm-4 text-center">
                $${entry.invested_amount}
            </div>
            <div class="col-sm-2 text-center">
                ${entry.invested_percentage}%
            </div>

            <div class="col-sm-2 text-center">
    ${entry.investment_status == 'pending' ? `<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInvestorFromDB(${entry.entry_id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>` : ''}    
            
        `;
        
        container.appendChild(row);
        console.log(total_investment);
        document.getElementById('initial_deposit').value = total_investment
    });
}
                             $('#add_investment_form').on('submit', function(e) {
                            e.preventDefault();
                           
                            $.ajax(
                                {
                                url: 'trading_ajax.php',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: function(response) {
                                    fetchAllInvestments();
                                   
                                    if (response.trim() === 'success') {
                                        get_trading_account_data($('#tradingAccountSelect').val());
                                       
                                                $('#investorSelect').val(null).trigger('change');
                                                $('#investor_balance').val('');
                                                $('#assign_fund').val('');
                                       
                                        } else {
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error in investment saving. '+response.trim(),
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
                                                submitButton.innerHTML = originalText;
                                                submitButton.disabled = false;
                                            }
                                        });
                                    
                                }
                            });
                      
                        });
                    
    

    function make_calculation() {
      
     var assign_fund = parseInt(document.getElementById('assign_fund').value) || 0;
     var investor_balance = parseInt(document.getElementById('hidden_investor_balance').value);
     var initial_deposit = parseInt(document.getElementById('hidden_initial_deposit').value);
     if(isNaN(assign_fund)) {
        assign_fund = 0; // Default to 0 if not a number
     }
     if(isNaN(investor_balance)) {
        investor_balance = 0; // Default to 0 if not a number
     }
        if(isNaN(initial_deposit)) {
            initial_deposit = 0; // Default to 0 if not a number
        }
        // Calculate updated values
        if(investor_balance < assign_fund) {
            alert("Assign fund cannot be greater than investor balance.");
            return;
        }
        if(assign_fund < 0) {
            alert("Assign fund cannot be negative.");
            return;
        }
     var updated_investor_balance = investor_balance - assign_fund;
     var updated_initial_deposit = initial_deposit + assign_fund;
     //document.getElementById('investor_balance').value = updated_investor_balance + 0;
    //document.getElementById('initial_deposit').value = updated_initial_deposit + 0;    
}

  
   
  
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
$(document).ready(function() {
    // Initialize Select2
    

    $.getJSON('trading_ajax.php?create_trading_acct_drop_down', function(data) {
    $('#tradingAccountSelect').select2({
        data: data,
        placeholder: "Search Trading Account",
        allowClear: true
    });
   load_investor();
   
});

    // When account is selected, load details
    $('#tradingAccountSelect').on('change', function() 
    {
        let selectedId = $(this).val();
        if (selectedId) {
            get_trading_account_data(selectedId);
            fetchAllInvestments();
        }
    });

   

    $('#investorSelect').on('change', function() {
        let selectedId = $(this).val();
       
        if (selectedId) {
            $.ajax({
                url: 'investor_ajax.php?get_investor_balance',
                method: 'GET',
                data: { id: selectedId },
                dataType: 'json',
                success: function(data) {
                    $('#investor_balance').val(data.investor_balance);
                    $('#hidden_investor_balance').val(data.investor_balance);
                  
                }
            });
        }
    });
});
</script>
