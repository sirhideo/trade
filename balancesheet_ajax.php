<?php 
include('app_settings.php');
session_start();
if(isset($_POST['get_investor_data'])){
    $investor_id = intval($_POST['investor_id']);

   
    $sql = "SELECT * FROM tbl_balance_sheet
            WHERE user_id = '$investor_id' ORDER BY trans_id ASC";
         
    
    $result = $connect->query($sql);
    
    $data = [];
    while($row = $result->fetch_assoc()) {
        $data[] = [
            'trans_date' => $row['trans_date'],
            'trans_amount' => $row['trans_amount'],
            'trans_desc' => $row['trans_desc'],
            'trans_type' => $row['trans_type'],
            'balance_line' => $row['balance_line']
        ];
    }
    

    echo json_encode($data);
}


if(isset($_REQUEST['get_all_investors'])){
    $sql = "SELECT id, first_name FROM tbl_users WHERE user_type='investor' ORDER BY first_name";
    $result = $connect->query($sql);
    
    $investors = [];
    while($row = $result->fetch_assoc()) {
        $investors[] = $row;
    }
    
    echo json_encode($investors);
}

if(isset($_REQUEST['reset_investor_id'])){

    $password = $_POST['new_password'];
    $investor_id = $_POST['reset_investor_id'];
   $query =  "UPDATE tbl_users SET password = '$password' WHERE id = '$investor_id'";
    $result = $connect->query($query);
    if($result){
        echo "success";
    } else {
        echo "error";
    }
    

}
if(isset($_REQUEST['get_balance_sheet'])){
    $investor_id = $_GET['investor_id'] ?? null;

if ($investor_id) {
   
    $sql = "SELECT * FROM tbl_balance_sheet WHERE user_id = ? ORDER BY trans_id ASC";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $investor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
} else {
    echo json_encode(["error" => "No investor ID provided"]);
}
} 

if(isset($_REQUEST['load_trading_accounts'])){
    
    $investor_id = $_SESSION['user_id'] ?? null;
if (!$investor_id) {
    echo json_encode([]);
    exit;
}

// Step 1: Get distinct server_ids from investments
$sql1 = "SELECT DISTINCT trading_acct_id 
         FROM tbl_investment
         WHERE investor_id = ?";
$stmt1 = $connect->prepare($sql1);
$stmt1->bind_param("i", $investor_id);
$stmt1->execute();
$result1 = $stmt1->get_result();

$server_ids = [];
while ($row = $result1->fetch_assoc()) {
    $server_ids[] = $row['trading_acct_id'];
}

if (empty($server_ids)) {
    echo json_encode([]);
    exit;
}

// Step 2: Get server data from trading_accounts
$placeholders = implode(',', array_fill(0, count($server_ids), '?'));
$sql2 = "SELECT * FROM tbl_trading_accounts WHERE id IN ($placeholders) AND trading_status = 'Yes'";
$stmt2 = $connect->prepare($sql2);


// Bind params dynamically
$types = str_repeat('i', count($server_ids));
$stmt2->bind_param($types, ...$server_ids);
$stmt2->execute();
$result2 = $stmt2->get_result();

$servers = [];
while ($row = $result2->fetch_assoc()) {
    $servers[] = $row;
}

echo json_encode($servers);
}

if(isset($_POST['get_deposit_withdraw'])){

    // get sum of deposits and withdrawals for the investor from balance sheet
    $investor_id = $_POST['investor_id'];
    $getDeposit = mysqli_fetch_array(mysqli_query($connect,"SELECT SUM(trans_amount) as TOTAL_DEPOSIT FROM tbl_balance_sheet WHERE trans_type = 'Deposit' AND user_id = '$investor_id'"));
    $getWithdraw = mysqli_fetch_array(mysqli_query($connect,"SELECT SUM(trans_amount) as TOTAL_WITHDRAW FROM tbl_balance_sheet WHERE trans_type = 'Withdraw' AND user_id = '$investor_id'"));
    $get_balance = mysqli_fetch_array(mysqli_query($connect,"SELECT investor_balance FROM tbl_users WHERE id = '$investor_id'"));
    $get_balance = $get_balance['investor_balance'];
    $data[] = [
        'total_deposit' => $getDeposit['TOTAL_DEPOSIT'] ?? 0,
        'total_withdraw' => $getWithdraw['TOTAL_WITHDRAW'] ?? 0,
        'investor_balance' => $get_balance ?? 0
    ];
    echo json_encode($data);
}
if(isset($_POST['user_reset_password'])){
    $user_id = $_SESSION['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if($new_password == $confirm_password){
        $query = "UPDATE tbl_users SET password = '$new_password' WHERE id = '$user_id'";
        if($connect->query($query)){
            echo "success";
        }else{
            echo "error";
        }
    }else{
        echo "password_mismatch";
    }
}

if(isset($_POST['user_reset_password_by_admin'])){
    $user_id = $_POST['user_id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    if($new_password == $confirm_password){
        $query = "UPDATE tbl_users SET password = '$new_password' WHERE id = '$user_id'";
        if($connect->query($query)){
            echo "success";
        }else{
            echo "error";
        }
    }else{
        echo "password_mismatch";
    }
}

if(isset($_REQUEST['load_trading_accounts_admin'])){
    
    $investor_id = $_REQUEST['id'] ?? null;
if (!$investor_id) {
    echo json_encode([]);
    exit;
}

// Step 1: Get distinct server_ids from investments
$sql1 = "SELECT DISTINCT trading_acct_id 
         FROM tbl_investment
         WHERE investor_id = ?";
$stmt1 = $connect->prepare($sql1);
$stmt1->bind_param("i", $investor_id);
$stmt1->execute();
$result1 = $stmt1->get_result();

$server_ids = [];
while ($row = $result1->fetch_assoc()) {
    $server_ids[] = $row['trading_acct_id'];
}

if (empty($server_ids)) {
    echo json_encode([]);
    exit;
}

// Step 2: Get server data from trading_accounts
$placeholders = implode(',', array_fill(0, count($server_ids), '?'));
$sql2 = "SELECT * FROM tbl_trading_accounts WHERE id IN ($placeholders) AND trading_status= 'Yes'";
$stmt2 = $connect->prepare($sql2);



// Bind params dynamically
$types = str_repeat('i', count($server_ids));
$stmt2->bind_param($types, ...$server_ids);
$stmt2->execute();
$result2 = $stmt2->get_result();

$servers = [];
while ($row = $result2->fetch_assoc()) {
    $servers[] = $row;
}

echo json_encode($servers); 
}
?>