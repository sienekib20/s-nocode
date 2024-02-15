<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

    <!-- ! Hide app brand if navbar-full -->
    <div class="app-brand demo">
        <a href="<?= route('home') ?>" class="app-brand-link">
            <span class="app-brand-text demo menu-text fw-bold ms-2">Sílica Page</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item <?= request()->path() == '/home' ? 'active' : '' ?>">
            <a href="<?= route('home') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div class="text-truncate">Dashboards</div>
            </a>
        </li>
        
        <li class="menu-item <?= request()->path() == '/websites' ? 'active' : '' ?>">
            <a href="<?= route('websites') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div class="text-truncate">Meus websites</div>
            </a>
        </li>


        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div class="text-truncate">Layouts</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/collapsed-menu" class="menu-link">
                        <div>Collapsed menu</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/content-navbar" class="menu-link">
                        <div>Content navbar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/content-nav-sidebar" class="menu-link">
                        <div>Content nav + Sidebar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/horizontal" class="menu-link" target="_blank">
                        <div>Horizontal</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/without-menu" class="menu-link">
                        <div>Without menu</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/without-navbar" class="menu-link">
                        <div>Without navbar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/fluid" class="menu-link">
                        <div>Fluid</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/container" class="menu-link">
                        <div>Container</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/layouts/blank" class="menu-link" target="_blank">
                        <div>Blank</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div class="text-truncate">Front Pages</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/front-pages/landing" class="menu-link" target="_blank">
                        <div>Landing</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/front-pages/pricing" class="menu-link" target="_blank">
                        <div>Pricing</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/front-pages/payment" class="menu-link" target="_blank">
                        <div>Payment</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/front-pages/checkout" class="menu-link" target="_blank">
                        <div>Checkout</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/front-pages/help-center" class="menu-link" target="_blank">
                        <div>Help Center</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bxl-php"></i>
                <div class="text-truncate">Laravel Example</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/laravel/user-management" class="menu-link">
                        <div>User Management</div>
                    </a>


                </li>
            </ul>
        </li>




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps &amp; Pages</span>
        </li>









        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/email" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate">Email</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/chat" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chat"></i>
                <div class="text-truncate">Chat</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/calendar" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div class="text-truncate">Calendar</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/kanban" class="menu-link">
                <i class="menu-icon tf-icons bx bx-grid"></i>
                <div class="text-truncate">Kanban</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                <div class="text-truncate">eCommerce</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/dashboard" class="menu-link">
                        <div>Dashboard</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Products</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/product/list" class="menu-link">
                                <div>Product List</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/product/add" class="menu-link">
                                <div>Add Product</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/product/category" class="menu-link">
                                <div>Category List</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Order</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/order/list" class="menu-link">
                                <div>Order List</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/order/details" class="menu-link">
                                <div> Order Details</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Customer</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/all" class="menu-link">
                                <div>All Customers</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="javascript:void(0)" class="menu-link menu-toggle">
                                <div>Customer Details</div>
                            </a>


                            <ul class="menu-sub">



                                <li class="menu-item ">
                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/details/overview" class="menu-link">
                                        <div>Overview</div>
                                    </a>


                                </li>



                                <li class="menu-item ">
                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/details/security" class="menu-link">
                                        <div>Security</div>
                                    </a>


                                </li>



                                <li class="menu-item ">
                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/details/billing" class="menu-link">
                                        <div>Address &amp; Billing</div>
                                    </a>


                                </li>



                                <li class="menu-item ">
                                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/customer/details/notifications" class="menu-link">
                                        <div>Notifications</div>
                                    </a>


                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/manage/reviews" class="menu-link">
                        <div>Manage Reviews</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/referrals" class="menu-link">
                        <div>Referrals</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Settings</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/details" class="menu-link">
                                <div>Store details</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/payments" class="menu-link">
                                <div>Payments</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/checkout" class="menu-link">
                                <div>Checkout</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/shipping" class="menu-link">
                                <div>Shipping &amp; Delivery</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/locations" class="menu-link">
                                <div>Locations</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/ecommerce/settings/notifications" class="menu-link">
                                <div>Notifications</div>
                            </a>


                        </li>
                    </ul>
                </li>
            </ul>
        </li>








        <li class="menu-item active open">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book-open"></i>
                <div class="text-truncate">Academy</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item active">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/academy/dashboard" class="menu-link">
                        <div>Dashboard</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/academy/course" class="menu-link">
                        <div>My Course</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/academy/course-details" class="menu-link">
                        <div>Course Details</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-car"></i>
                <div class="text-truncate">Logistics</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/logistics/dashboard" class="menu-link">
                        <div>Dashboard</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/logistics/fleet" class="menu-link">
                        <div>Fleet</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div class="text-truncate">Invoice</div>
                <div class="badge bg-success rounded-pill ms-auto">4</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/invoice/list" class="menu-link">
                        <div>List</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/invoice/preview" class="menu-link">
                        <div>Preview</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/invoice/edit" class="menu-link">
                        <div>Edit</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/invoice/add" class="menu-link">
                        <div>Add</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div class="text-truncate">Users</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/list" class="menu-link">
                        <div>List</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>View</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/view/account" class="menu-link">
                                <div>Account</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/view/security" class="menu-link">
                                <div>Security</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/view/billing" class="menu-link">
                                <div>Billing &amp; Plans</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/view/notifications" class="menu-link">
                                <div>Notifications</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/user/view/connections" class="menu-link">
                                <div>Connections</div>
                            </a>


                        </li>
                    </ul>
                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-check-shield"></i>
                <div class="text-truncate">Roles &amp; Permissions</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/access-roles" class="menu-link">
                        <div>Roles</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/app/access-permission" class="menu-link">
                        <div>Permission</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div class="text-truncate">Pages</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>User Profile</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/profile-user" class="menu-link">
                                <div>Profile</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/profile-teams" class="menu-link">
                                <div>Teams</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/profile-projects" class="menu-link">
                                <div>Projects</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/profile-connections" class="menu-link">
                                <div>Connections</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Account Settings</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/account-settings-account" class="menu-link">
                                <div>Account</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/account-settings-security" class="menu-link">
                                <div>Security</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/account-settings-billing" class="menu-link">
                                <div>Billing &amp; Plans</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/account-settings-notifications" class="menu-link">
                                <div>Notifications</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/account-settings-connections" class="menu-link">
                                <div>Connections</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/faq" class="menu-link">
                        <div>FAQ</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/pricing" class="menu-link">
                        <div>Pricing</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Misc</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/misc-error" class="menu-link" target="_blank">
                                <div>Error</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/misc-under-maintenance" class="menu-link" target="_blank">
                                <div>Under Maintenance</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/misc-comingsoon" class="menu-link" target="_blank">
                                <div>Coming Soon</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/pages/misc-not-authorized" class="menu-link" target="_blank">
                                <div>Not Authorized</div>
                            </a>


                        </li>
                    </ul>
                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                <div class="text-truncate">Authentications</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Login</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/login-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/login-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Register</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/register-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/register-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/register-multisteps" class="menu-link" target="_blank">
                                <div>Multi-steps</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Verify Email</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/verify-email-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/verify-email-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Reset Password</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/reset-password-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/reset-password-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Forgot Password</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/forgot-password-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/forgot-password-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Two Steps</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/two-steps-basic" class="menu-link" target="_blank">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/auth/two-steps-cover" class="menu-link" target="_blank">
                                <div>Cover</div>
                            </a>


                        </li>
                    </ul>
                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-spreadsheet"></i>
                <div class="text-truncate">Wizard Examples</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/wizard/ex-checkout" class="menu-link">
                        <div>Checkout</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/wizard/ex-property-listing" class="menu-link">
                        <div>Property Listing</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/wizard/ex-create-deal" class="menu-link">
                        <div>Create Deal</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/modal-examples" class="menu-link">
                <i class="menu-icon tf-icons bx bx-window-open"></i>
                <div class="text-truncate">Modal Examples</div>
            </a>


        </li>




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Components</span>
        </li>









        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-collection"></i>
                <div class="text-truncate">Cards</div>
                <div class="badge bg-danger rounded-pill ms-auto">6</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/basic" class="menu-link">
                        <div>Basic</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/advance" class="menu-link">
                        <div>Advance</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/statistics" class="menu-link">
                        <div>Statistics</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/analytics" class="menu-link">
                        <div>Analytics</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/gamifications" class="menu-link">
                        <div>Gamifications</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/cards/actions" class="menu-link">
                        <div>Actions</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div class="text-truncate">User interface</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/accordion" class="menu-link">
                        <div>Accordion</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/alerts" class="menu-link">
                        <div>Alerts</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/badges" class="menu-link">
                        <div>Badges</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/buttons" class="menu-link">
                        <div>Buttons</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/carousel" class="menu-link">
                        <div>Carousel</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/collapse" class="menu-link">
                        <div>Collapse</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/dropdowns" class="menu-link">
                        <div>Dropdowns</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/footer" class="menu-link">
                        <div>Footer</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/list-groups" class="menu-link">
                        <div>List Groups</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/modals" class="menu-link">
                        <div>Modals</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/navbar" class="menu-link">
                        <div>Navbar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/offcanvas" class="menu-link">
                        <div>Offcanvas</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/pagination-breadcrumbs" class="menu-link">
                        <div>Pagination &amp; Breadcrumbs</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/progress" class="menu-link">
                        <div>Progress</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/spinners" class="menu-link">
                        <div>Spinners</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/tabs-pills" class="menu-link">
                        <div>Tabs &amp; Pills</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/toasts" class="menu-link">
                        <div>Toasts</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/tooltips-popovers" class="menu-link">
                        <div>Tooltips &amp; popovers</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/ui/typography" class="menu-link">
                        <div>Typography</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-copy"></i>
                <div class="text-truncate">Extended UI</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-avatar" class="menu-link">
                        <div>Avatar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-blockui" class="menu-link">
                        <div>BlockUI</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-drag-and-drop" class="menu-link">
                        <div>Drag &amp; Drop</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-media-player" class="menu-link">
                        <div>Media Player</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-perfect-scrollbar" class="menu-link">
                        <div>Perfect scrollbar</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-star-ratings" class="menu-link">
                        <div>Star Ratings</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-sweetalert2" class="menu-link">
                        <div>SweetAlert2</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-text-divider" class="menu-link">
                        <div>Text Divider</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <div>Timeline</div>
                    </a>


                    <ul class="menu-sub">



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-timeline-basic" class="menu-link">
                                <div>Basic</div>
                            </a>


                        </li>



                        <li class="menu-item ">
                            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-timeline-fullscreen" class="menu-link">
                                <div>Fullscreen</div>
                            </a>


                        </li>
                    </ul>
                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-tour" class="menu-link">
                        <div>Tour</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-treeview" class="menu-link">
                        <div>Treeview</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/extended/ui-misc" class="menu-link">
                        <div>Miscellaneous</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-crown"></i>
                <div class="text-truncate">Icons</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/icons/boxicons" class="menu-link">
                        <div>Boxicons</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/icons/font-awesome" class="menu-link">
                        <div>Fontawesome</div>
                    </a>


                </li>
            </ul>
        </li>




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Forms &amp; Tables</span>
        </li>









        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div class="text-truncate">Form Elements</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/basic-inputs" class="menu-link">
                        <div>Basic Inputs</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/input-groups" class="menu-link">
                        <div>Input groups</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/custom-options" class="menu-link">
                        <div>Custom Options</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/editors" class="menu-link">
                        <div>Editors</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/file-upload" class="menu-link">
                        <div>File Upload</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/pickers" class="menu-link">
                        <div>Pickers</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/selects" class="menu-link">
                        <div>Select &amp; Tags</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/sliders" class="menu-link">
                        <div>Sliders</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/switches" class="menu-link">
                        <div>Switches</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/forms/extras" class="menu-link">
                        <div>Extras</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div class="text-truncate">Form Layouts</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/layouts-vertical" class="menu-link">
                        <div>Vertical Form</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/layouts-horizontal" class="menu-link">
                        <div>Horizontal Form</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/layouts-sticky" class="menu-link">
                        <div>Sticky Actions</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-carousel"></i>
                <div class="text-truncate">Form Wizard</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/wizard-numbered" class="menu-link">
                        <div>Numbered</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/wizard-icons" class="menu-link">
                        <div>Icons</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/form/validation" class="menu-link">
                <i class="menu-icon tf-icons bx bx-list-check"></i>
                <div class="text-truncate">Form Validation</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/tables/basic" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div class="text-truncate">Tables</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-grid"></i>
                <div class="text-truncate">Datatables</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/tables/datatables-basic" class="menu-link">
                        <div>Basic</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/tables/datatables-advanced" class="menu-link">
                        <div>Advanced</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/tables/datatables-extensions" class="menu-link">
                        <div>Extensions</div>
                    </a>


                </li>
            </ul>
        </li>




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Charts &amp; Maps</span>
        </li>









        <li class="menu-item ">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div class="text-truncate">Charts</div>
            </a>


            <ul class="menu-sub">



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/charts/apex" class="menu-link">
                        <div>Apex Charts</div>
                    </a>


                </li>



                <li class="menu-item ">
                    <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/charts/chartjs" class="menu-link">
                        <div>ChartJS</div>
                    </a>


                </li>
            </ul>
        </li>








        <li class="menu-item ">
            <a href="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template/demo-1/maps/leaflet" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map-alt"></i>
                <div class="text-truncate">Leaflet Maps</div>
            </a>


        </li>




        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Misc</span>
        </li>









        <li class="menu-item ">
            <a href="https://themeselection.com/support/" class="menu-link" target="_blank">
                <i class="menu-icon tf-icons bx bx-support"></i>
                <div class="text-truncate">Support</div>
            </a>


        </li>








        <li class="menu-item ">
            <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/laravel-introduction.html" class="menu-link" target="_blank">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div class="text-truncate">Documentation</div>
            </a>


        </li>
    </ul>

</aside>