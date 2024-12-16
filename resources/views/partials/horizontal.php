<?php
    if (!empty($_SESSION['lang'])) {
        $sessionLang = $_SESSION['lang'];
        require_once ('assets/lang-php/'.$sessionLang.'.php');
    } else {
        require_once ('assets/lang-php/en.php');
    }
?>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.php" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>

                <a href="index.php" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-light.svg" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="19">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <form class="app-search d-none d-lg-block">
                <div class="position-relative">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="bx bx-search-alt"></span>
                </div>
            </form>

            <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                    <span key="t-megamenu">Mega Menu</span>
                    <i class="mdi mdi-chevron-down"></i> 
                </button>
                <div class="dropdown-menu dropdown-megamenu">
                    <div class="row">
                        <div class="col-sm-8">
    
                            <div class="row">
                                <div class="col-md-4">
                                    <h5 class="font-size-14" key="t-ui-components">UI Components</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-lightbox">Lightbox</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-range-slider">Range Slider</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-sweet-alert">Sweet Alert</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-rating">Rating</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-forms">Forms</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tables">Tables</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-charts">Charts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14" key="t-applications">Applications</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-ecommerce">Ecommerce</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-calendar">Calendar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-email">Email</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-projects">Projects</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tasks">Tasks</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-contacts">Contacts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-md-4">
                                    <h5 class="font-size-14" key="t-extra-pages">Extra Pages</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-light-sidebar">Light Sidebar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-compact-sidebar">Compact Sidebar</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-horizontal">Horizontal layout</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-maintenance">Maintenance</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-coming-soon">Coming Soon</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-timeline">Timeline</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-faqs">FAQs</a>
                                        </li>
                            
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="font-size-14" key="t-ui-components">UI Components</h5>
                                    <ul class="list-unstyled megamenu-list">
                                        <li>
                                            <a href="javascript:void(0);" key="t-lightbox">Lightbox</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-range-slider">Range Slider</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-sweet-alert">Sweet Alert</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-rating">Rating</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-forms">Forms</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-tables">Tables</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" key="t-charts">Charts</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-sm-5">
                                    <div>
                                        <img src="assets/images/megamenu-img.png" alt="" class="img-fluid mx-auto d-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
                    
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..." aria-label="Search input">
                                
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>s
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                        if (!empty($_SESSION['lang'])) {
                            $language = $_SESSION['lang'];
                            if ($language == "en") {
                                $imgPath = 'assets/images/flags/us.jpg';
                            } elseif ($language == "sp") {
                                $imgPath = 'assets/images/flags/spain.jpg';
                            } elseif ($language == "gr") {
                                $imgPath = 'assets/images/flags/germany.jpg';
                            } elseif ($language == "it") {
                                $imgPath = 'assets/images/flags/italy.jpg';
                            } elseif ($language == "ru") {
                                $imgPath = 'assets/images/flags/russia.jpg';
                            } else {
                                $imgPath = 'assets/images/flags/us.jpg';
                            }
                        } else {
                            $imgPath = 'assets/images/flags/us.jpg';
                        }
                    ?>
                    <img id="header-lang-img" src="<?php echo $imgPath; ?>" alt="Header Language" height="16">
                </button>
                <div class="dropdown-menu dropdown-menu-end">

                    <!-- item-->
                    <a href="?lang=en" class="dropdown-item notify-item language" data-lang="eng">
                        <img src="assets/images/flags/us.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">English</span>
                    </a>
                    <!-- item-->
                    <a href="?lang=sp" class="dropdown-item notify-item language" data-lang="sp">
                        <img src="assets/images/flags/spain.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="?lang=gr" class="dropdown-item notify-item language" data-lang="gr">
                        <img src="assets/images/flags/germany.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="?lang=it" class="dropdown-item notify-item language" data-lang="it">
                        <img src="assets/images/flags/italy.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="?lang=ru" class="dropdown-item notify-item language" data-lang="ru">
                        <img src="assets/images/flags/russia.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                    </a>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-customize"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <div class="px-lg-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/github.png" alt="Github">
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/bitbucket.png" alt="bitbucket">
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/dribbble.png" alt="dribbble">
                                    <span>Dribbble</span>
                                </a>
                            </div>
                        </div>

                        <div class="row no-gutters">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/dropbox.png" alt="dropbox">
                                    <span>Dropbox</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/mail_chimp.png" alt="mail_chimp">
                                    <span>Mail Chimp</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="assets/images/brands/slack.png" alt="slack">
                                    <span>Slack</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="bx bx-fullscreen"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-bell bx-tada"></i>
                    <span class="badge bg-danger rounded-pill">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small" key="t-view-all"> View All</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-primary rounded-circle font-size-16">
                                        <i class="bx bx-cart"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" key="t-your-order">Your order is placed</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 min ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="assets/images/users/avatar-3.jpg"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">James Lemire</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-simplified">It will seem like simplified English.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">1 hours ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="bx bx-badge-check"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" key="t-shipped">Your item is shipped</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 min ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <a href="javascript: void(0);" class="text-reset notification-item">
                            <div class="d-flex">
                                <img src="assets/images/users/avatar-4.jpg"
                                    class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Salena Layfield</h6>
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1" key="t-occidental">As a skeptical Cambridge friend of mine occidental.</p>
                                        <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">1 hours ago</span></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span> 
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="assets/images/users/avatar-1.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">Henry</span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="#"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">Profile</span></a>
                    <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">My Wallet</span></a>
                    <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">Settings</span></a>
                    <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">Lock screen</span></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>
            
        </div>
    </div>
</header>
    
<div class="topnav">
    <div class="container-fluid">
        <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

            <div class="collapse navbar-collapse" id="topnav-menu-content">
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-dashboard" role="button"
                        >
                            <i class="bx bx-home-circle me-2"></i><span key="t-dashboards"><?php echo $lang["Dashboard"]; ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-dashboard">

                            <a href="index.php" class="dropdown-item" key="t-default"><?php echo $lang["Default"]; ?></a>
                            <a href="dashboard-saas.php" class="dropdown-item" key="t-saas"><?php echo $lang["Saas"]; ?></a>
                            <a href="dashboard-crypto.php" class="dropdown-item" key="t-crypto"><?php echo $lang["Crypto"]; ?></a>
                            <a href="dashboard-blog.php" class="dropdown-item" key="t-blog"><?php echo $lang["Blog"]; ?></a>
                            <a href="dashboard-job.php" class="dropdown-item" key="t-Jobs"><?php echo $lang["Jobs"]; ?></a>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-uielement" role="button"
                        >
                            <i class="bx bx-tone me-2"></i>
                            <span key="t-ui-elements"> <?php echo $lang["UI_Elements"]; ?></span> 
                            <div class="arrow-down"></div>
                        </a>

                        <div class="dropdown-menu mega-dropdown-menu px-2 dropdown-mega-menu-xl"
                            aria-labelledby="topnav-uielement">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-alerts.php" class="dropdown-item" key="t-alerts"><?php echo $lang["Alerts"]; ?></a>
                                        <a href="ui-buttons.php" class="dropdown-item" key="t-buttons"><?php echo $lang["Buttons"]; ?></a>
                                        <a href="ui-cards.php" class="dropdown-item" key="t-cards"><?php echo $lang["Cards"]; ?></a>
                                        <a href="ui-carousel.php" class="dropdown-item" key="t-carousel"><?php echo $lang["Carousel"]; ?></a>
                                        <a href="ui-dropdowns.php" class="dropdown-item" key="t-dropdowns"><?php echo $lang["Dropdowns"]; ?></a>
                                        <a href="ui-grid.php" class="dropdown-item" key="t-grid"><?php echo $lang["Grid"]; ?></a>
                                        <a href="ui-images.php" class="dropdown-item" key="t-images"><?php echo $lang["Images"]; ?></a>
                                        <a href="ui-lightbox.php" class="dropdown-item" key="t-lightbox"><?php echo $lang["Lightbox"]; ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-modals.php" class="dropdown-item" key="t-modals"><?php echo $lang["Modals"]; ?></a>
                                        <a href="ui-offcanvas.php" class="dropdown-item" key="t-offcanvas"><?php echo $lang["Offcanvas"]; ?></a>
                                        <a href="ui-rangeslider.php" class="dropdown-item" key="t-range-slider"><?php echo $lang["Range_Slider"]; ?></a>
                                        <a href="ui-session-timeout.php" class="dropdown-item" key="t-session-timeout"><?php echo $lang["Session_Timeout"]; ?></a>
                                        <a href="ui-progressbars.php" class="dropdown-item" key="t-progress-bars"><?php echo $lang["Progress_Bars"]; ?></a>
                                        <a href="ui-placeholders.php" class="dropdown-item" key="t-placeholders"><?php echo $lang["Placeholders"]; ?></a>
                                        <a href="ui-sweet-alert.php" class="dropdown-item" key="t-sweet-alert"><?php echo $lang["Sweet_Alert"]; ?></a>
                                        <a href="ui-tabs-accordions.php" class="dropdown-item" key="t-tabs-accordions"><?php echo $lang["Tabs_&_Accordions"]; ?></a>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div>
                                        <a href="ui-typography.php" class="dropdown-item" key="t-typography"><?php echo $lang["Typography"]; ?></a>
                                        <a href="ui-toasts.php" class="dropdown-item" key="t-toasts"><?php echo $lang["Toasts"]; ?></a>
                                        <a href="ui-video.php" class="dropdown-item" key="t-video"><?php echo $lang["Video"]; ?></a>
                                        <a href="ui-general.php" class="dropdown-item" key="t-general"><?php echo $lang["General"]; ?></a>
                                        <a href="ui-colors.php" class="dropdown-item" key="t-colors"><?php echo $lang["Colors"]; ?></a>
                                        <a href="ui-rating.php" class="dropdown-item" key="t-rating"><?php echo $lang["Rating"]; ?></a>
                                        <a href="ui-notifications.php" class="dropdown-item" key="t-notifications"><?php echo $lang["Notifications"]; ?></a>
                                        <a href="ui-utilities.php" class="dropdown-item" key="t-utilities"><?php echo $lang["Utilities"]; ?></a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-pages" role="button"
                        >
                            <i class="bx bx-customize me-2"></i><span key="t-apps"><?php echo $lang["Apps"]; ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-pages">

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    <span key="t-calendar"><?php echo $lang["Calendars"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="calendar.php" class="dropdown-item" key="t-tui-calendar"><?php echo $lang["TUI_Calendar"]; ?></a>
                                    <a href="calendar-full.php" class="dropdown-item" key="t-full-calender"><?php echo $lang["Full_Calendar"]; ?></a>
                                </div>
                            </div>

                            <a href="chat.php" class="dropdown-item" key="t-chat"><?php echo $lang["Chat"]; ?></a>
                            <a href="apps-filemanager.php" class="dropdown-item" key="t-file-manager"><?php echo $lang["File_Manager"]; ?></a>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-email"
                                    role="button">
                                    <span key="t-email"><?php echo $lang["Email"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-email">
                                    <a href="email-inbox.php" class="dropdown-item" key="t-inbox"><?php echo $lang["Inbox"]; ?></a>
                                    <a href="email-read.php" class="dropdown-item" key="t-read-email"><?php echo $lang["Read_Email"]; ?></a>

                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-blog"
                                            role="button">
                                            <span key="t-email-templates"><?php echo $lang["Templates"]; ?></span> <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-blog">
                                            <a href="email-template-basic.php" class="dropdown-item" key="t-basic-action"><?php echo $lang["Basic_Action"]; ?></a>
                                            <a href="email-template-alert.php" class="dropdown-item" key="t-alert-email"><?php echo $lang["Alert_Email"]; ?></a>
                                            <a href="email-template-billing.php" class="dropdown-item" key="t-bill-email"><?php echo $lang["Billing_Email"]; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-ecommerce"
                                    role="button">
                                    <span key="t-ecommerce"><?php echo $lang["Ecommerce"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-ecommerce">
                                    <a href="ecommerce-products.php" class="dropdown-item" key="t-products"><?php echo $lang["Products"]; ?></a>
                                    <a href="ecommerce-product-detail.php" class="dropdown-item" key="t-product-detail"><?php echo $lang["Product_Detail"]; ?></a>
                                    <a href="ecommerce-orders.php" class="dropdown-item" key="t-orders"><?php echo $lang["Orders"]; ?></a>
                                    <a href="ecommerce-customers.php" class="dropdown-item" key="t-customers"><?php echo $lang["Customers"]; ?></a>
                                    <a href="ecommerce-cart.php" class="dropdown-item" key="t-cart"><?php echo $lang["Cart"]; ?></a>
                                    <a href="ecommerce-checkout.php" class="dropdown-item" key="t-checkout"><?php echo $lang["Checkout"]; ?></a>
                                    <a href="ecommerce-shops.php" class="dropdown-item" key="t-shops"><?php echo $lang["Shops"]; ?></a>
                                    <a href="ecommerce-add-product.php" class="dropdown-item" key="t-add-product"><?php echo $lang["Add_Product"]; ?></a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-crypto"
                                    role="button">
                                    <span key="t-crypto"><?php echo $lang["Crypto"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-crypto">
                                    <a href="crypto-wallet.php" class="dropdown-item" key="t-wallet"><?php echo $lang["Wallet"]; ?></a>
                                    <a href="crypto-buy-sell.php" class="dropdown-item" key="t-buy"><?php echo $lang["Buy_Sell"]; ?></a>
                                    <a href="crypto-exchange.php" class="dropdown-item" key="t-exchange"><?php echo $lang["Exchange"]; ?></a>
                                    <a href="crypto-lending.php" class="dropdown-item" key="t-lending"><?php echo $lang["Lending"]; ?></a>
                                    <a href="crypto-orders.php" class="dropdown-item" key="t-orders"><?php echo $lang["Orders"]; ?></a>
                                    <a href="crypto-kyc-application.php" class="dropdown-item" key="t-kyc"><?php echo $lang["KYC_Application"]; ?></a>
                                    <a href="crypto-ico-landing.php" class="dropdown-item" key="t-ico"><?php echo $lang["ICO_Landing"]; ?></a>
                                </div>
                            </div>
                            
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-project"
                                    role="button">
                                    <span key="t-projects"><?php echo $lang["Projects"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-project">
                                    <a href="projects-grid.php" class="dropdown-item" key="t-p-grid"><?php echo $lang["Projects_Grid"]; ?></a>
                                    <a href="projects-list.php" class="dropdown-item" key="t-p-list"><?php echo $lang["Projects_List"]; ?></a>
                                    <a href="projects-overview.php" class="dropdown-item" key="t-p-overview"><?php echo $lang["Project_Overview"]; ?></a>
                                    <a href="projects-create.php" class="dropdown-item" key="t-create-new"><?php echo $lang["Create_New"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-task"
                                    role="button">
                                    <span key="t-tasks"><?php echo $lang["Tasks"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-task">
                                    <a href="tasks-list.php" class="dropdown-item" key="t-task-list"><?php echo $lang["Task_List"]; ?></a>
                                    <a href="tasks-kanban.php" class="dropdown-item" key="t-kanban-board"><?php echo $lang["Kanban_Board"]; ?></a>
                                    <a href="tasks-create.php" class="dropdown-item" key="t-create-task"><?php echo $lang["Create_Task"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-contact"
                                    role="button">
                                    <span key="t-contacts"><?php echo $lang["Contacts"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-contact">
                                    <a href="contacts-grid.php" class="dropdown-item" key="t-user-grid"><?php echo $lang["User_Grid"]; ?></a>
                                    <a href="contacts-list.php" class="dropdown-item" key="t-user-list"><?php echo $lang["User_List"]; ?></a>
                                    <a href="contacts-profile.php" class="dropdown-item" key="t-profile"><?php echo $lang["Profile"]; ?></a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-blog"
                                    role="button">
                                    <span key="t-blog"><?php echo $lang["Blog"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-blog">
                                    <a href="blog-list.php" class="dropdown-item" key="t-blog-list"><?php echo $lang["Blog_List"]; ?></a>
                                    <a href="blog-grid.php" class="dropdown-item" key="t-blog-grid"><?php echo $lang["Blog_Grid"]; ?></a>
                                    <a href="blog-details.php" class="dropdown-item" key="t-blog-details"><?php echo $lang["Blog_Details"]; ?></a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-jobs" role="button">
                                    <span key="t-jobs"><?php echo $lang["Jobs"]; ?></span>
                                    <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-jobs">
                                    <a href="job-list.php" class="dropdown-item" key="t-job-list"><?php echo $lang["Job_List"]; ?></a>
                                    <a href="job-grid.php" class="dropdown-item" key="t-job-grid"><?php echo $lang["Job_Grid"]; ?></a>
                                    <a href="job-apply.php" class="dropdown-item" key="t-apply-job"><?php echo $lang["Apply_Job"]; ?></a>
                                    <a href="job-details.php" class="dropdown-item" key="t-job-details"><?php echo $lang["Job_Details"]; ?></a>
                                    <a href="job-categories.php" class="dropdown-item" key="t-Jobs-categories"><?php echo $lang["Jobs_Categories"]; ?></a>
                                    <div class="dropdown">
                                        <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-candidate" role="button">
                                            <span key="t-candidate"><?php echo $lang["Candidate"]; ?></span>
                                            <div class="arrow-down"></div>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="topnav-candidate">
                                            <a href="candidate-list.php" class="dropdown-item" key="t-list"><?php echo $lang["List"]; ?></a>
                                            <a href="candidate-overview.php" class="dropdown-item" key="t-overview"><?php echo $lang["Overview"]; ?></a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button"
                        >
                            <i class="bx bx-collection me-2"></i><span key="t-components"><?php echo $lang["Components"]; ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-components">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-form"
                                    role="button">
                                    <span key="t-forms"><?php echo $lang["Forms"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-form">
                                    <a href="form-elements.php" class="dropdown-item" key="t-form-elements"><?php echo $lang["Form_Elements"]; ?></a>
                                    <a href="form-layouts.php" class="dropdown-item" key="t-form-layouts"><?php echo $lang["Form_Layouts"]; ?></a>
                                    <a href="form-validation.php" class="dropdown-item" key="t-form-validation"><?php echo $lang["Form_Validation"]; ?></a>
                                    <a href="form-advanced.php" class="dropdown-item" key="t-form-advanced"><?php echo $lang["Form_Advanced"]; ?></a>
                                    <a href="form-editors.php" class="dropdown-item" key="t-form-editors"><?php echo $lang["Form_Editors"]; ?></a>
                                    <a href="form-uploads.php" class="dropdown-item" key="t-form-upload"><?php echo $lang["Form_File_Upload"]; ?></a>
                                    <a href="form-xeditable.php" class="dropdown-item" key="t-form-xeditable"><?php echo $lang["Form_Xeditable"]; ?></a>
                                    <a href="form-repeater.php" class="dropdown-item" key="t-form-repeater"><?php echo $lang["Form_Repeater"]; ?></a>
                                    <a href="form-wizard.php" class="dropdown-item" key="t-form-wizard"><?php echo $lang["Form_Wizard"]; ?></a>
                                    <a href="form-mask.php" class="dropdown-item" key="t-form-mask"><?php echo $lang["Form_Mask"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-table"
                                    role="button">
                                    <span key="t-tables"><?php echo $lang["Tables"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-table">
                                    <a href="tables-basic.php" class="dropdown-item" key="t-basic-tables"><?php echo $lang["Basic_Tables"]; ?></a>
                                    <a href="tables-datatable.php" class="dropdown-item" key="t-data-tables"><?php echo $lang["Data_Tables"]; ?></a>
                                    <a href="tables-responsive.php" class="dropdown-item" key="t-responsive-table"><?php echo $lang["Responsive_Table"]; ?></a>
                                    <a href="tables-editable.php" class="dropdown-item" key="t-editable-table"><?php echo $lang["Editable_Table"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-charts"
                                    role="button">
                                    <span key="t-charts"><?php echo $lang["Charts"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-charts">
                                    <a href="charts-apex.php" class="dropdown-item" key="t-apex-charts"><?php echo $lang["Apex_Charts"]; ?></a>
                                    <a href="charts-echart.php" class="dropdown-item" key="t-e-charts"><?php echo $lang["E_Charts"]; ?></a>
                                    <a href="charts-chartjs.php" class="dropdown-item" key="t-chartjs-charts"><?php echo $lang["Chartjs_Charts"]; ?></a>
                                    <a href="charts-flot.php" class="dropdown-item" key="t-flot-charts"><?php echo $lang["Flot_Charts"]; ?></a>
                                    <a href="charts-tui.php" class="dropdown-item" key="t-ui-charts"><?php echo $lang["Toast_UI_Charts"]; ?></a>
                                    <a href="charts-knob.php" class="dropdown-item" key="t-knob-charts"><?php echo $lang["Jquery_Knob_Charts"]; ?></a>
                                    <a href="charts-sparkline.php" class="dropdown-item" key="t-sparkline-charts"><?php echo $lang["Sparkline_Charts"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-icons"
                                    role="button">
                                    <span key="t-icons"><?php echo $lang["Icons"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-icons">
                                    <a href="icons-boxicons.php" class="dropdown-item" key="t-boxicons"><?php echo $lang["Boxicons"]; ?></a>
                                    <a href="icons-materialdesign.php" class="dropdown-item" key="t-material-design"><?php echo $lang["Material_Design"]; ?></a>
                                    <a href="icons-dripicons.php" class="dropdown-item" key="t-dripicons"><?php echo $lang["Dripicons"]; ?></a>
                                    <a href="icons-fontawesome.php" class="dropdown-item" key="t-font-awesome"><?php echo $lang["Font_Awesome"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-map"
                                    role="button">
                                    <span key="t-maps"><?php echo $lang["Maps"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-map">
                                    <a href="maps-google.php" class="dropdown-item" key="t-g-maps"><?php echo $lang["Google_Maps"]; ?></a>
                                    <a href="maps-vector.php" class="dropdown-item" key="t-v-maps"><?php echo $lang["Vector_Maps"]; ?></a>
                                    <a href="maps-leaflet.php" class="dropdown-item" key="t-l-maps"><?php echo $lang["Leaflet_Maps"]; ?></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-more" role="button"
                        >
                            <i class="bx bx-file me-2"></i><span key="t-extra-pages"><?php echo $lang["Extra_Pages"]; ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-more">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-invoice"
                                    role="button">
                                    <span key="t-invoices"><?php echo $lang["Invoices"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-invoice">
                                    <a href="invoices-list.php" class="dropdown-item" key="t-invoice-list"><?php echo $lang["Invoice_List"]; ?></a>
                                    <a href="invoices-detail.php" class="dropdown-item" key="t-invoice-detail"><?php echo $lang["Invoice_Detail"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-auth"
                                    role="button">
                                    <span key="t-authentication"><?php echo $lang["Authentication"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-auth">
                                    <a href="auth-login.php" class="dropdown-item" key="t-login"><?php echo $lang["Login"]; ?></a>
                                    <a href="auth-login-2.php" class="dropdown-item" key="t-login-2"><?php echo $lang["Login_2"]; ?></a>
                                    <a href="auth-register.php" class="dropdown-item" key="t-register"><?php echo $lang["Register"]; ?></a>
                                    <a href="auth-register-2.php" class="dropdown-item" key="t-register-2"><?php echo $lang["Register_2"]; ?></a>
                                    <a href="auth-recoverpw.php" class="dropdown-item" key="t-recover-password"><?php echo $lang["Recover_Password"]; ?></a>
                                    <a href="auth-recoverpw-2.php" class="dropdown-item" key="t-recover-password-2"><?php echo $lang["Recover_Password_2"]; ?></a>
                                    <a href="auth-lock-screen.php" class="dropdown-item" key="t-lock-screen"><?php echo $lang["Lock_Screen"]; ?></a>
                                    <a href="auth-lock-screen-2.php" class="dropdown-item" key="t-lock-screen-2"><?php echo $lang["Lock_Screen_2"]; ?></a>
                                    <a href="auth-confirm-mail.php" class="dropdown-item" key="t-confirm-mail"><?php echo $lang["Confirm_Mail"]; ?></a>
                                    <a href="auth-confirm-mail-2.php" class="dropdown-item" key="t-confirm-mail-2"><?php echo $lang["Confirm_Email_2"]; ?></a>
                                    <a href="auth-email-verification.php" class="dropdown-item" key="t-email-verification"><?php echo $lang["Email_verification"]; ?></a>
                                    <a href="auth-email-verification-2.php" class="dropdown-item" key="t-email-verification-2"><?php echo $lang["Email_Verification_2"]; ?></a>
                                    <a href="auth-two-step-verification.php" class="dropdown-item" key="t-two-step-verification"><?php echo $lang["Two_step_verification"]; ?></a>
                                    <a href="auth-two-step-verification-2.php" class="dropdown-item" key="t-two-step-verification-2"><?php echo $lang["Two_Step_Verification_2"]; ?></a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-utility"
                                    role="button">
                                    <span key="t-utility"><?php echo $lang["Utility"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-utility">
                                    <a href="pages-starter.php" class="dropdown-item" key="t-starter-page"><?php echo $lang["Starter_Page"]; ?></a>
                                    <a href="pages-maintenance.php" class="dropdown-item" key="t-maintenance"><?php echo $lang["Maintenance"]; ?></a>
                                    <a href="pages-comingsoon.php" class="dropdown-item" key="t-coming-soon"><?php echo $lang["Coming_Soon"]; ?></a>
                                    <a href="pages-timeline.php" class="dropdown-item" key="t-timeline"><?php echo $lang["Timeline"]; ?></a>
                                    <a href="pages-faqs.php" class="dropdown-item" key="t-faqs"><?php echo $lang["FAQs"]; ?></a>
                                    <a href="pages-pricing.php" class="dropdown-item" key="t-pricing"><?php echo $lang["Pricing"]; ?></a>
                                    <a href="pages-404.php" class="dropdown-item" key="t-error-404"><?php echo $lang["Error_404"]; ?></a>
                                    <a href="pages-500.php" class="dropdown-item" key="t-error-500"><?php echo $lang["Error_500"]; ?></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-layout" role="button"
                        >
                            <i class="bx bx-layout me-2"></i><span key="t-layouts"><?php echo $lang["Layouts"]; ?></span> <div class="arrow-down"></div>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="topnav-layout">
                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-verti"
                                    role="button">
                                    <span key="t-vertical"><?php echo $lang["Vertical"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-verti">
                                    <a href="layouts-light-sidebar.php" class="dropdown-item" key="t-light-sidebar"><?php echo $lang["Light_Sidebar"]; ?></a>
                                    <a href="layouts-compact-sidebar.php" class="dropdown-item" key="t-compact-sidebar"><?php echo $lang["Compact_Sidebar"]; ?></a>
                                    <a href="layouts-icon-sidebar.php" class="dropdown-item" key="t-icon-sidebar"><?php echo $lang["Icon_Sidebar"]; ?></a>
                                    <a href="layouts-boxed.php" class="dropdown-item" key="t-boxed-width"><?php echo $lang["Boxed_Width"]; ?></a>
                                    <a href="layouts-preloader.php" class="dropdown-item" key="t-preloader"><?php echo $lang["Preloader"]; ?></a>
                                    <a href="layouts-colored-sidebar.php" class="dropdown-item" key="t-colored-sidebar"><?php echo $lang["Colored_Sidebar"]; ?></a>
                                    <a href="layouts-scrollable.php" class="dropdown-item" key="t-scrollable"><?php echo $lang["Scrollable"]; ?></a>
                                </div>
                            </div>

                            <div class="dropdown">
                                <a class="dropdown-item dropdown-toggle arrow-none" href="#" id="topnav-layout-hori"
                                    role="button">
                                    <span key="t-horizontal"><?php echo $lang["Horizontal"]; ?></span> <div class="arrow-down"></div>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="topnav-layout-hori">
                                    <a href="layouts-horizontal.php" class="dropdown-item" key="t-horizontal"><?php echo $lang["Horizontal"]; ?></a>
                                    <a href="layouts-hori-topbar-light.php" class="dropdown-item" key="t-topbar-light"><?php echo $lang["Topbar_Light"]; ?></a>
                                    <a href="layouts-hori-boxed-width.php" class="dropdown-item" key="t-boxed-width"><?php echo $lang["Boxed_Width"]; ?></a>
                                    <a href="layouts-hori-preloader.php" class="dropdown-item" key="t-preloader"><?php echo $lang["Preloader"]; ?></a>
                                    <a href="layouts-hori-colored-header.php" class="dropdown-item" key="t-colored-topbar"><?php echo $lang["Colored_Header"]; ?></a>
                                    <a href="layouts-hori-scrollable.php" class="dropdown-item" key="t-scrollable"><?php echo $lang["Scrollable"]; ?></a>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>