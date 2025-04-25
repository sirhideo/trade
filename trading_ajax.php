<?php 
include('app_settings.php');

if(isset($_POST['create_trading_acct'])){
    $txt_trading_acct_number = $_POST['txt_trading_acct_number'];
    $txt_vm_name = $_POST['txt_vm_name'];
    $txt_instance = $_POST['txt_instance'];
    $txt_broker_server = $_POST['txt_broker_server'];
    $txt_investor_password = $_POST['txt_investor_password'];
    
    $query = "INSERT INTO tbl_trading_accounts (trading_acct_number, vm_name, instance, broker_server, investor_password) 
    VALUES ('$txt_trading_acct_number', '$txt_vm_name', '$txt_instance', '$txt_broker_server', '$txt_investor_password')";
    if(mysqli_query($connect, $query)){
        echo "success";
    } else {
        echo "Error creating trading account: " . mysqli_error($connect);
    }
}

if(isset($_REQUEST['get_trading_accounts'])){
    $sql_accounts = "SELECT * FROM tbl_trading_accounts";
    $result_accounts = $connect->query($sql_accounts);

    $accounts = [];

    while ($account = $result_accounts->fetch_assoc()) {
      
        $accounts[] = $account;
    }

    echo json_encode($accounts);
    exit;
}

if(isset($_REQUEST['create_trading_acct_drop_down'])){
    $sql = "SELECT id, trading_acct_number FROM tbl_trading_accounts";
$result = $connect->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id" => $row['id'],
        "text" => $row['trading_acct_number']
    ];
}
echo json_encode($data);
}
if(isset($_REQUEST['get_single_trading_account'])){
    $id = intval($_GET['id']);
$sql = "SELECT * FROM tbl_trading_accounts WHERE id = $id";
$result1 = $connect->query($sql);
//check if trading_status is Yes or No
$trading_status = mysqli_fetch_array(mysqli_query($connect, 
"SELECT trading_status FROM tbl_trading_accounts WHERE id = '$id'"));
// if tradin_status yes get sum of tbl_investment of active esle sum of pending
if($trading_status['trading_status'] == 'Yes'){
    $result = mysqli_query($connect, "SELECT SUM(invested_amount) as total_investment FROM tbl_investment WHERE trading_acct_id = '$id' AND investment_status = 'active'");
}
else{
    $result = mysqli_query($connect, "SELECT SUM(invested_amount) as total_investment FROM tbl_investment WHERE trading_acct_id = '$id' AND investment_status = 'pending'");
}
$total_investment_data = mysqli_fetch_array($result);
$total_investment = $total_investment_data['total_investment'] ?? 0;

if ($row = $result1->fetch_assoc()) {
    $row['total_initial_deposit'] = $total_investment; 
    echo json_encode($row);
} else {
    echo json_encode([]);
}
}
if(isset($_REQUEST['create_investment'])){
    $txt_trading_acct_number = $_POST['txt_trading_acct_number'];
    $investor_id = $_POST['investor_delete_id'];
    $assinged_fund = $_POST['txt_assign_fund'];
    $txt_investment_target = $_POST['txt_target_investment'];
 
    // get sum of invested_amount from tbl_investment where invesment_status = active
    
    
    $total_investment = get_total_investment($connect, $txt_trading_acct_number);
    if($total_investment == 0){
        $investment_percentage = 100;
    }
    else
    {

        $investment_percentage = ($assinged_fund / $total_investment) * 100;
    }
    
    $query = "INSERT INTO tbl_investment (trading_acct_id, investor_id, invested_amount, investment_target, invested_percentage)
    VALUES ('$txt_trading_acct_number', '$investor_id', '$assinged_fund', '$txt_investment_target', '$investment_percentage')";
    if(mysqli_query($connect, $query)){
        update_investment($connect, $txt_trading_acct_number);
        echo "success";
    } else {
        echo "Error creating investment: " . mysqli_error($connect);
    }
}

function get_total_investment($connect, $trade_acct_id){
    $total_investmentData = mysqli_fetch_array(mysqli_query($connect, 
    "SELECT SUM(invested_amount) as total_investment 
    FROM tbl_investment WHERE trading_acct_id = '$trade_acct_id' AND investment_status = 'pending'"));
    return $total_investmentData['total_investment'] ?? 0;
}
function update_investment($connect, $txt_trading_acct_number){
    $total_trading = mysqli_query($connect,"SELECT * FROM tbl_investment 
    WHERE trading_acct_id = '$txt_trading_acct_number' and investment_status = 'pending'");
    $total_investment = get_total_investment($connect, $txt_trading_acct_number);
    if ($total_investment > 0) {
        while ($obj = mysqli_fetch_array($total_trading)) {
            $updated_investment_percentage = round(($obj['invested_amount'] / $total_investment) * 100);
            mysqli_query($connect, "UPDATE tbl_investment SET invested_percentage = '$updated_investment_percentage' 
        WHERE entry_id = '" . $obj['entry_id'] . "'");
        }
    }
}
if(isset($_POST['get_all_investments'])){
    $sql = "SELECT tbl_investment.*, tbl_users.first_name, tbl_trading_accounts.trading_acct_number FROM tbl_investment 
    JOIN tbl_users ON tbl_investment.investor_id = tbl_users.id 
    JOIN tbl_trading_accounts ON tbl_investment.trading_acct_id = tbl_trading_accounts.id
    WHERE tbl_investment.trading_acct_id = '".$_POST['trade_account_id']."' AND tbl_investment.investment_status != 'closed'";
    $result = $connect->query($sql);
    $investments = [];
    while ($row = $result->fetch_assoc()) {
        $investments[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $investments]);
}
if(isset($_POST['delete_investment'])){
    $investment_id = $_POST['entry_id'];
    $details = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM tbl_investment 
    WHERE entry_id = '$investment_id'"));
    $sql = "DELETE FROM tbl_investment WHERE entry_id = '$investment_id'";
    if(mysqli_query($connect, $sql)){
 
      
        
            update_investment($connect, $details['trading_acct_id']);
        
        echo "success";
    } else {
        echo "Error deleting investment: " . mysqli_error($connect);
    }
}
if(isset($_POST['commit_trade'])){
    $txt_trading_acct_number = $_POST['txt_trading_acct_number'];
   // get all active investments of trading account
    $get_investments = mysqli_query($connect, "SELECT * FROM tbl_investment 
    WHERE trading_acct_id = '$txt_trading_acct_number' AND investment_status = 'pending'");
    if(mysqli_num_rows($get_investments) == 0){
        echo "No investments found for this trading account.";
        exit;
    }
    while($obj = mysqli_fetch_array($get_investments)){
        calculate_earning($connect, $obj['entry_id'], '', $obj['investor_id'], $obj['invested_amount'],$txt_trading_acct_number,'yes','no');
       
    }
    mysqli_query($connect, "UPDATE tbl_investment SET investment_status = 'active' 
    WHERE investment_status = 'pending' AND trading_acct_id = '$txt_trading_acct_number'");
    // total investoment
    $total_investment = get_total_investment($connect, $txt_trading_acct_number);
    // initial deposit of trading account
    $post_trading = 
    "UPDATE tbl_trading_accounts SET trading_status = 'Yes', 
    balance = '$total_investment' WHERE id = '$txt_trading_acct_number'";
   
    if(mysqli_query($connect, $post_trading)){
        echo "success";
    } else {
        echo "Error committing trade: " . mysqli_error($connect);
    }
    

}
if(isset($_POST['settle_account'])){
    $trading_acct_id = $_POST['trading_account_id'];
    $settle_amount = $_POST['txt_settlement_amount'];
    
    // get investments
    $get_investments = mysqli_query($connect, "SELECT * FROM tbl_investment 
    WHERE trading_acct_id = '$trading_acct_id' AND investment_status = 'active'");
    if(mysqli_num_rows($get_investments) == 0){
        echo "No investments found for this trading account.";
        exit;
    }
    while($obj = mysqli_fetch_array($get_investments)){
        if($settle_amount < 0){
            $trans_type = "Loss";
        }
        if($settle_amount > 0){
            $trans_type = "Profit";
        }
        calculate_earning($connect, $obj['entry_id'], $trans_type, $obj['investor_id'], $settle_amount,$trading_acct_id,'no','yes');
    }
   
    //update the initial deposit amount
    $query = "UPDATE tbl_trading_accounts SET balance = 0, trading_status = 'No' WHERE id = '$trading_acct_id'";
    mysqli_query($connect, $query);


    if(mysqli_query($connect, $query)){
        echo "success";
    } else {
        echo "Error settling account: " . mysqli_error($connect);
    }
}
function calculate_earning($connect,$entry_id,$trans_type,$investor_id, $settle_amount, $trade_acct_id, $commit_trade,$settle_trade){
    
    $trading_account = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM tbl_trading_accounts WHERE id = '$trade_acct_id'"));
    // return investor_amount to investor account
    $get_investor_balance = mysqli_fetch_array(mysqli_query($connect, "SELECT investor_balance 
   
    FROM tbl_users WHERE id = '$investor_id' "));
   
   $investor_current_balance = $get_investor_balance['investor_balance'];
   
    $get_investment = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM tbl_investment WHERE entry_id = '$entry_id'")); 
   
    $invested_amount = $get_investment['invested_amount'];

   // $new_balance = $investor_current_balance - $invested_amount;
    $total_investment = get_total_investment($connect, $trade_acct_id);

    if($commit_trade == 'yes'){
        
        //$query = "UPDATE tbl_users SET investor_balance = '$new_balance' WHERE id = '$investor_id' ";
       // mysqli_query($connect, $query);
        make_transaction($connect, $invested_amount, "Investment on Product: ".$get_investment['investment_target']." (".$get_investment['invested_percentage']."% of Total initial deposit: $".$total_investment.")", "Investment", $investor_id);
    }
   
   
   // $updated_investor_balance = $investor_current_balance + $invested_amount;
   if($settle_trade == 'yes'){
       
   
   // $query = "UPDATE tbl_users SET investor_balance = '$updated_investor_balance' WHERE id = '$investor_id' ";
   
   // mysqli_query($connect, $query);
   
    make_transaction($connect, $invested_amount, "Return of Investment Fund", "Return Investment", $investor_id);
   
    if($trans_type == "Profit"){
    
        $amount = $settle_amount * ($get_investment['invested_percentage'] / 100);
    
        make_transaction($connect, $amount, "Profit", $trans_type, $investor_id);
   }
    if($trans_type == "Loss"){
    
        $amount =  "-".(abs($settle_amount) * ($get_investment['invested_percentage'] / 100));
    
        make_transaction($connect, $amount, "Loss", $trans_type, $investor_id);
    }
     // update investment status
     $query = "UPDATE tbl_investment SET investment_status = 'closed', trading_earning = '$amount' WHERE entry_id = '$entry_id'";
     mysqli_query($connect, $query);
    }
     // make transaction

}
function make_transaction($connect, $trans_amount, $trans_detail, $trans_type, $user_id){


        $get_investor_balance = mysqli_fetch_array(mysqli_query($connect, 
        "SELECT investor_balance FROM tbl_users WHERE id = '$user_id' "));
        $investor_current_balance = $get_investor_balance['investor_balance'];
        if($trans_type == "Profit" || $trans_type == "Return Investment"){

            $updated_investor_balance = $investor_current_balance + $trans_amount;
        }
        if($trans_type == "Loss" || $trans_type == "Investment"){
            $updated_investor_balance = $investor_current_balance - abs($trans_amount);
        }
        $query = "UPDATE tbl_users SET investor_balance = '$updated_investor_balance' WHERE id = '$user_id' ";
        mysqli_query($connect, $query);

        
    $query = "INSERT INTO tbl_balance_sheet(trans_date, trans_amount, trans_desc, trans_type, user_id, balance_line)VALUES(
        '".date('m/d/Y')."', '$trans_amount', '$trans_detail', '$trans_type', '$user_id', '$updated_investor_balance')";
        mysqli_query($connect, $query);

}
if(isset($_REQUEST['delete_trading_account'])) {
    // Validate and sanitize the input
    $trading_acct_id = intval($_REQUEST['id']);
    
    // Prepare response array
    $response = ['success' => false, 'message' => ''];
    
    // Validate the ID
    if($trading_acct_id <= 0) {
        $response['message'] = 'Invalid account ID';
        echo json_encode($response);
        exit;
    }
    mysqli_query($connect, "DELETE FROM tbl_investment WHERE trading_acct_id = '$trading_acct_id'");
    // Use prepared statement to prevent SQL injection
    $query = "DELETE FROM tbl_trading_accounts WHERE id = ?";
    $stmt = mysqli_prepare($connect, $query);
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $trading_acct_id);
        
        if(mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Trading account deleted successfully';
        } else {
            $response['message'] = 'Error deleting trading account: ' . mysqli_error($connect);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Database error: ' . mysqli_error($connect);
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

if(isset($_REQUEST['get_users'])){
    $sql = "SELECT * FROM tbl_users WHERE user_type = 'investor'";
    $result = $connect->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            "id" => $row['id'],
            "first_name" => $row['first_name'],
            "login_id" => $row['login_id'],
            "user_type" => $row['user_type'],
            "is_admin" => $row['is_admin']

        ];
    }
    echo json_encode($data);
}
if(isset($_REQUEST['reset_to_account'])){
    $account_id = $_POST['id'];
    $action = $_POST['action'];
    if($action ==  'Delete'){
        $query = "DELETE FROM tbl_users WHERE id=".$account_id;
    }
    else{
                // check user is admin or not
                $getdata = mysqli_fetch_array(mysqli_query($connect,"SELECT is_admin FROM tbl_users WHERE id = '$account_id'"));
                if($getdata['is_admin'] == 1){

                    $query = "UPDATE tbl_users SET is_admin = 0 WHERE id = '$account_id'";
                }
                else
                {
                    $query = "UPDATE tbl_users SET is_admin = 1 WHERE id = '$account_id'";

                }
    }
   
    if(mysqli_query($connect, $query)){
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => mysqli_error($connect)]);
    }
}
