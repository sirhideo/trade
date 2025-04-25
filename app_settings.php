<?php 


    // Use service name 'db' defined in docker-compose.yml
    $connect = mysqli_connect("db", "trader", "tradepass", "tradingdb");

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    function is_admin($connect) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $user_id = mysqli_real_escape_string($connect, $_SESSION['user_id']);
        $query = "SELECT is_admin FROM tbl_users WHERE id = '$user_id'";
        $result = mysqli_query($connect, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['is_admin'] == 1;
        } else {
            return false;
        }
    }


