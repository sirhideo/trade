<?php
ob_start();
    include('app_settings.php');
    
    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM tbl_users WHERE login_id='$username' AND password='$password'";
        $result = mysqli_query($connect, $query);
        if(mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_array($result);
        session_start();
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['user_type'] = $data['user_type'];
            echo "success";
        } else {
            echo "error";
        }
    }