<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include('app_settings.php');
    
    if(isset($_POST['create_investor'])){
        
        $txt_investor = $_POST['txt_investor'];
        $txt_investor_login_id = $_POST['txt_investor_login_id'];
        $txt_investor_password = $_POST['txt_investor_password'];

        $verify_query = "SELECT * FROM tbl_users WHERE login_id='$txt_investor_login_id' and password='$txt_investor_password' and user_type='investor'";
        $verify_result = mysqli_query($connect, $verify_query);
        if(mysqli_num_rows($verify_result) > 0){

            echo "Investor with login ID $txt_investor_login_id already exists.";
        }
        else
        {
            $insert_query = "INSERT INTO tbl_users (user_type, login_id, password, first_name, investor_balance ) VALUES ('investor', '$txt_investor_login_id', '$txt_investor_password', '$txt_investor', 0)";
            $insert_result = mysqli_query($connect, $insert_query);
            
            if($insert_result){
                echo "success";
            } else {
                echo "Error creating investor: " . mysqli_error($connect);
            }
        }
        
    }
    if(isset($_REQUEST['delete_investor'])){
        $query = "DELETE FROM tbl_users WHERE user_type='Investor' AND  id=" . $_REQUEST['investor_delete_id'];
        if(mysqli_query($connect, $query))
        {
            echo "success";
        }
        else
        {
            echo "Error in deleting investor";
        }
    }
if(isset($_POST['loadinvestor'])){
    $searchTerm = $_POST['searchTerm'];

    $query = "SELECT id, first_name FROM tbl_users WHERE user_type = 'investor' and first_name LIKE ? LIMIT 10";
    $stmt = $connect->prepare($query);
    $term = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = ["id" => $row['id'], "text" => $row['first_name']];
    }
    
    echo json_encode($data);
}
if(isset($_REQUEST['get_investor_balance'])){
    $investor_id = $_REQUEST['id'];
    $query = "SELECT investor_balance FROM tbl_users WHERE id='$investor_id'";
    $result = mysqli_query($connect, $query);
    
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Investor not found"]);
    }
}
    
if(isset($_REQUEST['general_config'])){ 
   

    mysqli_query($connect, "UPDATE tbl_settings SET default_url = '".$_POST['default_url']."'");
   
    $investor_id = $_POST['investorSelect2'];
    if(mysqli_query($connect, "UPDATE tbl_users SET is_admin = 1 WHERE id='$investor_id'")){
        echo "success";
    } else {
        echo "Error in updating investor: " . mysqli_error($connect);
    }
}
if(isset($_REQUEST['load_default_url'])){
    $query = "SELECT default_url FROM tbl_settings";
    $result = mysqli_query($connect, $query);
    
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Default URL not found"]);
    }
}
?>