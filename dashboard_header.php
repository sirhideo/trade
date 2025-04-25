<div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="sidebar-collapse-btn" id="sidebarCollapse">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle text-capitalize" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                               <?php
                                $get_user = $connect->query("SELECT * FROM tbl_users WHERE id = '".$_SESSION['user_id']."'");
                               if ($get_user->num_rows > 0) {
                                   $user = $get_user->fetch_assoc();
                                   echo $user['first_name'] ."-" . ($user['is_admin'] == 1 ? 'Admin' :  $user['user_type']);
                               }
                               ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                
                            
                                <li><a class="dropdown-item" href="javascript:logout()"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>

                    </div>
                </div>