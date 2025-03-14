            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="h-100" id="left-side-menu-container" data-simplebar>

                    <!-- LOGO -->
                    <a href="/en" target="_site" class="logo text-center">
                        <span class="logo-lg">
                            <img src="/img/myavana-logo.png" alt="" height="28" id="side-main-logo">
                        </span>
                        <span class="logo-sm">
                            <img src="/img/myavana-logo.png" alt="" height="28" id="side-sm-main-logo">
                        </span>
                    </a>

                    <!--- Sidemenu -->
                    <ul class="metismenu side-nav">

                        <li class="side-nav-title side-nav-item">Reports</li>

                        <li class="side-nav-item"><a class="side-nav-link" href="/en/admin/report/100"><i class="uil-tachometer-fast"></i><span>Report</span></a></li>

                        <li>&nbsp;</li>

                        <li class="side-nav-title side-nav-item">Database Objects</li>

                        <?php
                        if(is_admin()){
                          $html .= "<li class=\"side-nav-item\"><a class=\"side-nav-link\" href=\"/en/admin/user\"><i class=\"uil-database\"></i><span>User</span></a></li>";
                          $html .= "<li class=\"side-nav-item\"><a class=\"side-nav-link\" href=\"/en/admin/cms\"><i class=\"uil-database\"></i><span>Cms</span></a></li>";
                          $html .= "<li class=\"side-nav-item\"><a class=\"side-nav-link\" href=\"/en/admin/meta\"><i class=\"uil-database\"></i><span>Meta</span></a></li>";
                          echo $html."\n";
                        }
                        ?>


                    </ul>

                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->
