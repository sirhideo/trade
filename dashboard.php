<?php
session_start();
if($_SESSION['user_id'])
{
    


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Investment Fund Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <?php include('./sidebar.php');?>

        <!-- Main Content -->
        <div class="main-content" id="mainContent">

            <div class="container-fluid">
                <!-- Header with Toggle Button -->
                <?php include('./dashboard_header.php');?>

                <!-- Page Loading Contents Area-->

                <div id="contentArea">

                </div>

            </div>



          <?php include('./js.php');?>
</body>
<script>
    $(document).ready(function() {
        // Load the initial page content
        loadPage('dashboard_contents.php', this); 
        get_chart_data(<?=$_SESSION['user_id'];?>);
        load_investor_trading_accounts();
        

    });
</script>
</html>
<?php
}
else
{
    header("location:index.php");
    exit();
}
?>