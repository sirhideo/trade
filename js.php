<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                // Sidebar toggle functionality
                
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebar = document.getElementById('sidebar');
                    const mainContent = document.getElementById('mainContent');
                    const sidebarCollapseBtn = document.getElementById('sidebarCollapse');

                    // Toggle sidebar on button click
                    sidebarCollapseBtn.addEventListener('click', function() {
                        sidebar.classList.toggle('active');
                        mainContent.classList.toggle('active');
                    });

                    // Close sidebar when clicking outside on mobile
                    document.addEventListener('click', function(event) {
                        const isClickInsideSidebar = sidebar.contains(event.target);
                        const isClickOnToggleBtn = event.target === sidebarCollapseBtn ||
                            sidebarCollapseBtn.contains(event.target);
                            const isNavLinkClick = event.target.closest('.nav-link');

                        if (window.innerWidth <= 992 &&
                            !isClickInsideSidebar &&
                            !isClickOnToggleBtn && 
                            sidebar.classList.contains('active') || isNavLinkClick) {
                            sidebar.classList.remove('active');
                            mainContent.classList.remove('active');
                        }
                    });

                    // Initialize DataTable
                    $('#accountsTable').DataTable({
                        responsive: true,
                        dom: '<"top"f>rt<"bottom"lip><"clear">',
                        language: {
                            search: "_INPUT_",
                            searchPlaceholder: "Search...",
                        }
                    });
                });

                // Show create account modal when button is clicked
                document.querySelector('.btn-primary').addEventListener('click', function() {
                    var modal = new bootstrap.Modal(document.getElementById('createAccountModal'));
                    modal.show();
                });

                function show_loader() {
                    $('#contentArea').html('<div class="loader-spinner" id="loader" style="display: none;"> <div class="spinner"> </div>');
                }

                function hide_loader() {
                    document.getElementById('loader').style.display = 'none';
                }

                function loadPage(page, element) {
                    $('#contentArea').html(`
        <div class="d-flex justify-content-center align-items-center" style="height: 200px;">
            <div class="spinner-border text-black" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `);
                    $.ajax({
                        url: page,
                        type: 'GET',
                        success: function(response) {
                            if (response === 'unauthorized') {
                                location.reload();
                            } else {
                                $('#contentArea').html(response);

                                $('.nav-link').removeClass('active');

                                $(element).addClass('active');
                            }
                        },
                        error: function() {
                            alert('Failed to load page.');
                        }
                    });
                }
                function logout(){
                    Swal.fire({
                                html: '<span style="color:#3085d6;">Are you sure you want to logout?</span>',
                                confirmButtonText: 'Continue',
                                showCancelButton: true,
                                allowOutsideClick: false,
                                confirmButtonColor: "#3085d6",
                                cancelButtonColor: "#ccc",
                            }).then((result) => {
                                /* Read more about isConfirmed, isDenied below */
                                if (result.isConfirmed) {
                                    window.location.href = 'logout.php';
                                } 
                                });

                }

               
    document.addEventListener("DOMContentLoaded", function () {
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');
        const navLinks = document.querySelectorAll('.nav-link');

        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    sidebar.classList.remove('active');
                    mainContent.classList.remove('active');
                }
            });
        });
    });


            </script>