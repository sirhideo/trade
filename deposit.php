<div class="page-header">
    <h4><i class="fa-solid fa-money-bill-transfer me-2"></i> Investor Deposit</h4>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="card p-1 border-dark">
    <div class="card-body">
        <form id="add_deposit_form">
            <input type="hidden" name="create_deposit" value="1" />
            <div class="card-header bg-light border-bottom border-top border-dark">
                Investor Deposit Entry Form
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
                            <label>Deposit Amount</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" id="deposit_amount" name="txt_deposit_amount" class="form-control" placeholder="Enter Deposit Amount" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary mt-2" id="assignFundBtn">
                                <i class="fas fa-save"></i> Deposit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Save Deposit Form
    $('#add_deposit_form').on('submit', function (e) {
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
                    $('#deposit_amount').val('');
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
