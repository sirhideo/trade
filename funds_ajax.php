<?php 
include('app_settings.php');

if(isset($_POST['create_deposit'])){

    // get current balance of investor
    $investor_id = $_POST['investor_id'];
    $getData = $connect->query("SELECT * FROM tbl_users WHERE id = '$investor_id'");
    $investor = $getData->fetch_assoc();
    $current_balance = $investor['investor_balance'];
    $deposit_amount = $_POST['txt_deposit_amount'];
    $new_balance = $current_balance + $deposit_amount;
    // update investor balance
    $update_balance = $connect->query("UPDATE tbl_users SET investor_balance = '$new_balance' WHERE id = '$investor_id'");
    // insert deposit record into balance sheet
    $query = "INSERT INTO tbl_balance_sheet(trans_date, trans_amount, trans_desc, trans_type, user_id, balance_line) 
              VALUES (NOW(), '$deposit_amount', 'Deposit Fund', 'Deposit', '$investor_id', '$new_balance')";
    $result = $connect->query($query);
    if($result && $update_balance){
        echo json_encode(['status' => 'success', 'message' => 'Deposit successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Deposit failed!']);
    }
}

if(isset($_POST['create_withdraw'])){
    // get current balance of investor
    $investor_id = $_POST['investor_id'];
    $getData = $connect->query("SELECT * FROM tbl_users WHERE id = '$investor_id'");
    $investor = $getData->fetch_assoc();
    $current_balance = $investor['investor_balance'];
    $deposit_amount = $_POST['txt_withdraw_amount'];
    $new_balance = $current_balance - $deposit_amount;
    // update investor balance
    $update_balance = $connect->query("UPDATE tbl_users SET investor_balance = '$new_balance' WHERE id = '$investor_id'");
    // insert deposit record into balance sheet
    $query = "INSERT INTO tbl_balance_sheet(trans_date, trans_amount, trans_desc, trans_type, user_id, balance_line) 
              VALUES (NOW(), '$deposit_amount', 'Withdraw Fund', 'Withdraw', '$investor_id', '$new_balance')";
    $result = $connect->query($query);
    if($result && $update_balance){
        echo json_encode(['status' => 'success', 'message' => 'Withdraw successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Withdraw failed!']);
    }
}