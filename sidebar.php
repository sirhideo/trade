<div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3 class="text-center">Investment Fund Management</h3>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#" onclick="loadPage('dashboard_contents.php', this); get_chart_data(<?=$_SESSION['user_id'];?>);load_investor_trading_accounts();loadinvestorbalance(<?=$_SESSION['user_id'];?>);">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Balance Sheet</span>
                    </a>
                </li>
                <?php 
                include('./app_settings.php');
                if($_SESSION['user_type'] == 'Admin' || is_admin($connect))
                {
                    ?>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#dashboardSubmenu" role="button">
                    <i class="fa-solid fa-list-check"></i>
                        <span>Management</span>
                        <i class="fas fa-angle-down ms-auto"></i>
                    </a>
                    <div class="collapse show" id="dashboardSubmenu">
                        <ul class="nav flex-column sub-menu">
                           
                            <li class="nav-item">
                                <a class="nav-link active" href="#" onclick="loadPage('trading_acct_mgt.php', this)">
                                    <i class="fas fa-chart-line"></i>
                                    <span>Trading Account Management</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="loadPage('configurations.php', this)">
                                    <i class="fas fa-cog"></i>
                                    <span>Configuration</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="loadPage('deposit.php', this)">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                                    <span>Deposit</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" onclick="loadPage('withdraw.php', this)">
                                <i class="fa-solid fa-hand-holding-dollar"></i>
                                    <span>Withdraw</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadPage('trading.php', this)">
                    <i class="fa-solid fa-chart-simple"></i>
                        <span>Trading</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadPage('adminusers.php', this)">
                        <i class="fas fa-users-cog"></i>
                        <span>Users</span>
                    </a>
                </li>
                <?php 
                }
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="loadPage('changepassword.php', this)">
                        <i class="fas fa-key"></i>
                        <span>Change Password</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:logout()">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </div>