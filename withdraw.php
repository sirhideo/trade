<div class="page-header">
    <h4><i class="fa-solid fa-hand-holding-dollar me-2"></i> Investor Withdraw</h4>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="card p-1 border-dark">
    <div class="card-body">
        <form id="add_withdraw_form">
            <input type="hidden" name="create_withdraw" value="1" />
            <div class="card-header bg-light border-bottom border-top border-dark">
                Investor Withdraw Entry Form
            </div>

            <div class="row mt-4">
                <div class="col-sm-6">
                    <div class="row mb-3">
                        <div class="col-sm-4 text-end">
                            <label>Select Investor</label>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control js-example-programmatic" name="investor_id" id="investorSelect" required>
                                <option value="">Select Investor Name</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-end">
                            <label>Investor Balance</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" id="investor_balance" readonly name="txt_investor_balance" class="form-control" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-4 text-end">
                            <label>Withdraw Amount</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" id="withdraw_amount" name="txt_withdraw_amount" class="form-control" placeholder="Enter Withdraw Amount" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary mt-2" id="assignFundBtn">
                                <i class="fas fa-save"></i> Withdraw
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function withdraw_amout_check(){
        let withdraw_amount = $('#withdraw_amount').val();
        let investor_balance = $('#investor_balance').val();
        if (parseFloat(withdraw_amount) > parseFloat(investor_balance)) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Withdraw amount exceeds the available balance.'
            });
            return false;
        }
        return true;
    }
    // Save withdraw Form
    $('#add_withdraw_form').on('submit', function (e) {

        if (!withdraw_amout_check()) {
            e.preventDefault();
            return;
        }
        e.preventDefault();
        let formData = $(this).serialize();
        $.ajax({
            url: 'funds_ajax.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    $('#investorSelect').val(null).trigger('change');
                    $('#investor_balance').val('');
                    $('#withdraw_amount').val('');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX Error:', error);
                console.log('Response:', xhr.responseText);
            }
        });
    });

    function load_investor() {
        $('#investorSelect').select2({
            placeholder: "Select Investor Name",
            ajax: {
                url: 'investor_ajax.php',
                type: 'POST',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        loadinvestor: 1,
                        searchTerm: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1
        });
    }

    $(document).ready(function () {
        load_investor();

        $('#investorSelect').on('change', function () {
            let selectedId = $(this).val();
            if (selectedId) {
                $.ajax({
                    url: 'investor_ajax.php?get_investor_balance',
                    method: 'GET',
                    data: { id: selectedId },
                    dataType: 'json',
                    success: function (data) {
                        $('#investor_balance').val(data.investor_balance);
                    }
                });
            }
        });
    });
</script>
