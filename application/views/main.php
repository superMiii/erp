<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title><?= $this->session->userdata('e_company_name'); ?></title>
    <link rel="apple-touch-icon" href="<?= base_url(); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/vendors.min.css">
    <?= put_headers(); ?>
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/components.min.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/fonts/simple-line-icons/style.min.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 2-columns fixed-navbar <?= $this->session->userdata('fix_menu'); ?>" data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">

    <!-- BEGIN: Header-->
    <!-- <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow"> -->
    <!-- <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-light bg-gradient-x-cyan"> -->
    <nav class="header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top navbar-dark <?= $this->session->e_color; ?> bg-darken-1">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-lg-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu font-large-1"></i></a></li>
                    <li class="nav-item mr-auto">
                        <a class="navbar-brand" href="<?= base_url(); ?>">
                            <img class="brand-logo" alt="stack admin logo" src="<?= base_url(); ?>app-assets/images/logo/stack-logo-light.png">
                            <img class="brand-text" alt="stack admin logo" src="<?= base_url(); ?>app-assets/images/logo/fix7.png">
                        </a>
                    </li>
                    <li class="nav-item d-none d-lg-block nav-toggle">
                        <?php if ($this->session->userdata('fix_menu') == 'menu-collapsed') { ?>
                            <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse" onclick="set_collapse('menu-expanded'); return false;">
                                <i class="toggle-icon feather icon-toggle-left font-medium-3 white" data-ticon="feather.icon-toggle-left"></i>
                            </a>
                        <?php } else { ?>
                            <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse" onclick="set_collapse('menu-collapsed'); return false;">
                                <i class="toggle-icon feather icon-toggle-right font-medium-3 white" data-ticon="feather.icon-toggle-left"></i>
                            </a>
                        <?php } ?>
                    </li>
                    <li class="nav-item d-lg-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <!--  <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="feather icon-menu"></i></a></li> -->

                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon feather icon-maximize"></i></a></li>

                        <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($this->session->userdata('i_company') == "") {
                                    echo 'Pilih Company';
                                } else {
                                    echo $this->session->userdata('e_company_name');
                                } ?></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <?php if (get_company()) {
                                    foreach (get_company()->result() as $key) {
                                        $status = ($this->session->userdata('i_company') == $key->i_company) ? 'active' : '';
                                ?>
                                        <a href="#" onclick="set_company('<?= $key->i_company; ?>','<?= $key->e_company_name; ?>','<?= $key->f_company_plusppn; ?>','<?= $key->n_ppn; ?>','<?= $key->f_plus_meterai; ?>','<?= $key->v_meterai; ?>','<?= $key->v_meterai_limit; ?>','<?= $key->e_color; ?>'); return false;" class="dropdown-item <?= $status; ?>"><?= $key->e_company_name; ?></a>
                                <?php }
                                } ?>
                            </div>
                        </li>
                        <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($this->session->userdata('i_department') == "") {
                                    echo 'Pilih Department';
                                } else {
                                    echo $this->session->userdata('e_department_name');
                                } ?></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <?php if (get_department()) {
                                    foreach (get_department()->result() as $key) {
                                        $status = ($this->session->userdata('i_department') == $key->i_department) ? 'active' : '';
                                ?>
                                        <a href="#" onclick="set_department('<?= $key->i_department; ?>','<?= $key->e_department_name; ?>'); return false;" class="dropdown-item <?= $status; ?>"><?= $key->e_department_name; ?></a>
                                <?php }
                                } ?>
                            </div>
                        </li>

                        <li class="dropdown dropdown-language nav-item">
                            <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php if ($this->session->userdata('i_level') == "") {
                                    echo 'Pilih Level';
                                } else {
                                    echo $this->session->userdata('e_level_name');
                                } ?></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <?php if (get_level()) {
                                    foreach (get_level()->result() as $key) {
                                        $status = ($this->session->userdata('i_level') == $key->i_level) ? 'active' : '';
                                ?>
                                        <a href="#" onclick="set_level('<?= $key->i_level; ?>','<?= $key->e_level_name; ?>'); return false;" class="dropdown-item <?= $status; ?>"><?= $key->e_level_name; ?></a>
                                <?php }
                                } ?>
                            </div>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <!-- <li class="dropdown dropdown-language nav-item">
                            <?php if ($this->session->userdata('language') == 'indonesia') { ?>
                                <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-id"></i><span class="selected-language"></span></a>
                            <?php } else { ?> -->

                        <!-- <a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-gb"></i><span class="selected-language"></span></a> -->

                        <!-- <?php } ?>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag">
                                <?php if ($this->session->userdata('language') == 'indonesia') { ?> -->

                        <!-- <a class="dropdown-item" href="#" onclick="set_language('english'); return false;"><i class="flag-icon flag-icon-gb"></i> English</a> -->

                        <!-- <?php } else { ?>
                                    <a class="dropdown-item" href="#" onclick="set_language('indonesia'); return false;"><i class="flag-icon flag-icon-id"></i> Indonesia</a>
                                <?php } ?>
                            </div>
                        </li> -->



                        <!-- <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-bell"></i><span class="badge badge-pill badge-danger badge-up">5</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span><span class="notification-tag badge badge-danger float-right m-0">5 New</span></h6>
                                </li>
                                <li class="scrollable-container media-list"><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="feather icon-plus-square icon-bg-circle bg-cyan"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">You have new order!</h6>
                                                <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor sit amet, consectetuer elit.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">30 minutes ago</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="feather icon-download-cloud icon-bg-circle bg-blue bg-darken-4 bg-darken-1"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading red darken-1">99% Server load</h6>
                                                <p class="notification-text font-small-3 text-muted">Aliquam tincidunt mauris eu risus.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Five hour ago</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="feather icon-alert-triangle icon-bg-circle bg-yellow bg-darken-3"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
                                                <p class="notification-text font-small-3 text-muted">Vestibulum auctor dapibus neque.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="feather icon-check-circle icon-bg-circle bg-cyan"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Complete the task</h6><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last week</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="feather icon-file icon-bg-circle bg-teal"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Generate monthly report</h6><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
                                            </div>
                                        </div>
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all notifications</a></li>
                            </ul>
                        </li> -->

                        <!-- <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon feather icon-mail"></i><span class="badge badge-pill badge-warning badge-up">3</span></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Messages</span><span class="notification-tag badge badge-warning float-right m-0">4 New</span></h6>
                                </li>
                                <li class="scrollable-container media-list"><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-online avatar-sm rounded-circle"><img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></div>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Margaret Govan</h6>
                                                <p class="notification-text font-small-3 text-muted">I like your portfolio, let's start.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-busy rounded-circle"><img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-2.png" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Bret Lezama</h6>
                                                <p class="notification-text font-small-3 text-muted">I have seen your work, there is</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Tuesday</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <div class="avatar avatar-online avatar-sm rounded-circle"><img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-3.png" alt="avatar"><i></i></div>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Carie Berra</h6>
                                                <p class="notification-text font-small-3 text-muted">Can we have call in this week ?</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Friday</time></small>
                                            </div>
                                        </div>
                                    </a><a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left"><span class="avatar avatar-sm avatar-away rounded-circle"><img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-6.png" alt="avatar"><i></i></span></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Eric Alsobrook</h6>
                                                <p class="notification-text font-small-3 text-muted">We have project party this saturday.</p><small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">last month</time></small>
                                            </div>
                                        </div>
                                    </a></li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all messages</a></li>
                            </ul>
                        </li> -->
                        <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <!-- <div class="avatar avatar-online"><img src="<?= base_url(); ?>assets/images/default.jpg" alt="avatar"><i></i></div><span class="user-name"><?= $this->session->userdata('e_user_name'); ?></span> -->
                                <div class="avatar avatar-online"><img src="<?= base_url(); ?>assets/images/avatar/<?= $this->session->userdata('ava'); ?>" alt="avatar"><i></i></div><span class="user-name"><?= $this->session->userdata('e_user_name'); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="<?= base_url('main/change_password'); ?>"><i class="fa fa-lock"></i> <?= $this->lang->line('Profil'); ?></a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="<?= base_url('main/user'); ?>"><i class="fa fa-user"></i></i> <?= $this->lang->line('Ganti Profil'); ?></a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="<?= base_url('auth/logout'); ?>"><i class="feather icon-power"></i> <?= $this->lang->line('Keluar'); ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- END: Header-->


    <!-- BEGIN: Main Menu-->
    <!-- <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true"> -->
    <?php if ($this->session->userdata('fix_menu') == 'menu-collapsed') {
        $ex = '';
    } else {
        $ex = 'expanded';
    } ?>
    <div class="main-menu menu-fixed menu-accordion menu-shadow <?= $ex; ?> menu-light" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header">
                    <span>Main</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Main"></i>
                </li>
                <?php
                if ($this->session->userdata('fix_menu') == 'menu-collapsed') {
                    $open = 'menu-collapsed-open';
                } else {
                    $open = 'open';
                }
                if (get_menu()) {
                    foreach (get_menu()->result() as $key) {
                        if ($key->e_folder == '#') { ?>
                            <li class="nav-item <?php if ($this->session->userdata('idmenu_1') == $key->i_menu) echo $open; ?>">
                                <a href="#" class="nav-link"><i class="<?= $key->icon; ?>"></i>
                                    <span class="menu-title text-bold-500" data-i18n=""><?= $this->lang->line($key->e_menu); ?></span></a>
                                <ul class="menu-content" data-submenu-title="Layouts">
                                    <?php foreach (get_sub_menu($key->i_menu)->result() as $row) {
                                        if ($row->e_folder == '#') { ?>
                                            <li class="<?php if ($this->session->userdata('idmenu_2') == $row->i_menu) echo $open; ?>"><a class="menu-item text-bold-400" href="#"><i class="<?= $row->icon; ?>"></i> <?= $this->lang->line($row->e_menu); ?></a>
                                                <ul class="menu-content">
                                                    <?php foreach (get_sub_menu($row->i_menu)->result() as $kuy) { ?>
                                                        <li class="<?php if ($this->session->userdata('idmenu_3') == $kuy->i_menu) echo ' active hover'; ?>"><a onclick="set_activemenu('<?= $key->i_menu; ?>','<?= $row->i_menu; ?>','<?= $kuy->i_menu; ?>', '<?= $kuy->e_folder; ?>'); return true;" data-toggle="tooltip" data-original-title="<?= $this->lang->line($kuy->e_menu); ?>" data-html="true" class="menu-item ml-1"><?= $this->lang->line($kuy->e_menu); ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } else { ?>
                                            <li class="<?php if ($this->session->userdata('idmenu_3') == $row->i_menu) echo ' active hover'; ?>"><a onclick="set_activemenu('<?= $key->i_menu; ?>','<?= 'skip'; ?>','<?= $row->i_menu; ?>', '<?= $row->e_folder; ?>'); return true;" data-toggle="tooltip" data-original-title="<?= $this->lang->line($row->e_menu); ?>" data-html="true" class="menu-item text-bold-400"><i class="<?= $row->icon; ?>"></i> <?= $this->lang->line($row->e_menu); ?></a></li>
                                    <?php }
                                    } ?>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a href="<?= base_url($key->e_folder); ?>" class="text-bold-500">
                                    <i class="<?= $key->icon; ?>"></i>
                                    <span>
                                        <?= $this->lang->line($key->e_menu); ?>
                                    </span>
                                </a>
                            </li>
                <?php }
                    }
                } ?>
                <li class="nav-item">
                    <a href="<?= base_url('auth/logout'); ?>" class="text-bold-500">
                        <i class="feather icon-log-out"></i>
                        <span>
                            <?= $this->lang->line('Keluar'); ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content" style="background-image: url('<?= base_url(); ?>assets/images/Assetbg.png');background-repeat: no-repeat;background-attachment: fixed;background-size: 100% 100%;">
        <!-- <div class="app-content content"> -->
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <?= $contents; ?>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Customizer-->
    <!-- <div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-xl-block"><a class="customizer-close" href="#"><i class="feather icon-x font-medium-3"></i></a><a class="customizer-toggle bg-danger" href="#"><i class="feather icon-settings font-medium-3 fa-spin fa fa-spin fa-fw white"></i></a>
        <div class="customizer-content p-2">
            <h4 class="text-uppercase mb-0">Theme Customizer</h4>
            <hr>
            <p>Customize & Preview in Real Time</p>

            <h5 class="mt-1 text-bold-500">Layout Options</h5>
            <ul class="nav nav-tabs nav-underline nav-justified layout-options">
                <li class="nav-item">
                    <a class="nav-link layouts active" id="baseIcon-tab21-base" data-toggle="tab" aria-controls="tabIcon21-base" href="#tabIcon21-base" aria-expanded="true">Layouts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation" id="baseIcon-tab22-base" data-toggle="tab" aria-controls="tabIcon22-base" href="#tabIcon22-base" aria-expanded="false">Navigation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar" id="baseIcon-tab23-base" data-toggle="tab" aria-controls="tabIcon23-base" href="#tabIcon23-base" aria-expanded="false">Navbar</a>
                </li>
            </ul>
            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="tabIcon21-base" aria-expanded="true" aria-labelledby="baseIcon-tab21-base">

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="collapsed-sidebar" id="collapsed-sidebar">
                        <label class="custom-control-label" for="collapsed-sidebar">Collapsed Menu</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="fixed-layout" id="fixed-layout">
                        <label class="custom-control-label" for="fixed-layout">Fixed Layout</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="boxed-layout" id="boxed-layout">
                        <label class="custom-control-label" for="boxed-layout">Boxed Layout</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="static-layout" id="static-layout">
                        <label class="custom-control-label" for="static-layout">Static Layout</label>
                    </div>

                </div>
                <div class="tab-pane" id="tabIcon22-base" aria-labelledby="baseIcon-tab22-base">

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="native-scroll" id="native-scroll">
                        <label class="custom-control-label" for="native-scroll">Native Scroll</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="right-side-icons" id="right-side-icons">
                        <label class="custom-control-label" for="right-side-icons">Right Side Icons</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="bordered-navigation" id="bordered-navigation">
                        <label class="custom-control-label" for="bordered-navigation">Bordered Navigation</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="flipped-navigation" id="flipped-navigation">
                        <label class="custom-control-label" for="flipped-navigation">Flipped Navigation</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="collapsible-navigation" id="collapsible-navigation">
                        <label class="custom-control-label" for="collapsible-navigation">Collapsible Navigation</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="static-navigation" id="static-navigation">
                        <label class="custom-control-label" for="static-navigation">Static Navigation</label>
                    </div>

                </div>
                <div class="tab-pane" id="tabIcon23-base" aria-labelledby="baseIcon-tab23-base">

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="brand-center" id="brand-center">
                        <label class="custom-control-label" for="brand-center">Brand Center</label>
                    </div>

                    <div class="custom-control custom-checkbox mb-1">
                        <input type="checkbox" class="custom-control-input" name="navbar-static-top" id="navbar-static-top">
                        <label class="custom-control-label" for="navbar-static-top">Static Top</label>
                    </div>

                </div>
            </div>

            <hr>

            <h5 class="mt-1 text-bold-500">Navigation Color Options</h5>
            <ul class="nav nav-tabs nav-underline nav-justified color-options">
                <li class="nav-item">
                    <a class="nav-link nav-semi-light active" id="color-opt-1" data-toggle="tab" aria-controls="clrOpt1" href="#clrOpt1" aria-expanded="false">Semi Light</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-semi-dark" id="color-opt-2" data-toggle="tab" aria-controls="clrOpt2" href="#clrOpt2" aria-expanded="false">Semi Dark</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-dark" id="color-opt-3" data-toggle="tab" aria-controls="clrOpt3" href="#clrOpt3" aria-expanded="false">Dark</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-light" id="color-opt-4" data-toggle="tab" aria-controls="clrOpt4" href="#clrOpt4" aria-expanded="true">Light</a>
                </li>
            </ul>
            <div class="tab-content px-1 pt-1">
                <div role="tabpanel" class="tab-pane active" id="clrOpt1" aria-expanded="true" aria-labelledby="color-opt-1">
                    <div class="row">
                        <div class="col-6">
                            <h6>Solid</h6>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-blue-grey" data-bg="bg-blue-grey" id="default-solid">
                                <label class="custom-control-label" for="default-solid">Default</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-primary" data-bg="bg-primary" id="primary-solid">
                                <label class="custom-control-label" for="primary-solid">Primary</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-danger" data-bg="bg-danger" id="danger-solid">
                                <label class="custom-control-label" for="danger-solid">Danger</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-success" data-bg="bg-success" id="success-solid">
                                <label class="custom-control-label" for="success-solid">Success</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-blue" data-bg="bg-blue" id="blue">
                                <label class="custom-control-label" for="blue">Blue</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-cyan" data-bg="bg-cyan" id="cyan">
                                <label class="custom-control-label" for="cyan">Cyan</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-pink" data-bg="bg-pink" id="pink">
                                <label class="custom-control-label" for="pink">Pink</label>
                            </div>

                        </div>
                        <div class="col-6">
                            <h6>Gradient</h6>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" checked class="custom-control-input bg-blue-grey" data-bg="bg-primary bg-darken-4" id="bg-primary bg-darken-4">
                                <label class="custom-control-label" for="bg-primary bg-darken-4">Default</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-primary" data-bg="bg-gradient-x-primary" id="bg-gradient-x-primary">
                                <label class="custom-control-label" for="bg-gradient-x-primary">Primary</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-danger" data-bg="bg-gradient-x-danger" id="bg-gradient-x-danger">
                                <label class="custom-control-label" for="bg-gradient-x-danger">Danger</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-success" data-bg="bg-gradient-x-success" id="bg-gradient-x-success">
                                <label class="custom-control-label" for="bg-gradient-x-success">Success</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-blue" data-bg="bg-gradient-x-blue" id="bg-gradient-x-blue">
                                <label class="custom-control-label" for="bg-gradient-x-blue">Blue</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-cyan" data-bg="bg-gradient-x-cyan" id="bg-gradient-x-cyan">
                                <label class="custom-control-label" for="bg-gradient-x-cyan">Cyan</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-slight-clr" class="custom-control-input bg-pink" data-bg="bg-gradient-x-pink" id="bg-gradient-x-pink">
                                <label class="custom-control-label" for="bg-gradient-x-pink">Pink</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="clrOpt2" aria-labelledby="color-opt-2">

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" checked class="custom-control-input bg-default" data-bg="bg-default" id="opt-default">
                        <label class="custom-control-label" for="opt-default">Default</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-primary" data-bg="bg-primary" id="opt-primary">
                        <label class="custom-control-label" for="opt-primary">Primary</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-danger" data-bg="bg-danger" id="opt-danger">
                        <label class="custom-control-label" for="opt-danger">Danger</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-success" data-bg="bg-success" id="opt-success">
                        <label class="custom-control-label" for="opt-success">Success</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-blue" data-bg="bg-blue" id="opt-blue">
                        <label class="custom-control-label" for="opt-blue">Blue</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-cyan" data-bg="bg-cyan" id="opt-cyan">
                        <label class="custom-control-label" for="opt-cyan">Cyan</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-sdark-clr" class="custom-control-input bg-pink" data-bg="bg-pink" id="opt-pink">
                        <label class="custom-control-label" for="opt-pink">Pink</label>
                    </div>

                </div>
                <div class="tab-pane" id="clrOpt3" aria-labelledby="color-opt-3">
                    <div class="row">
                        <div class="col-6">
                            <h3>Solid</h3>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-blue-grey" data-bg="bg-blue-grey" id="solid-blue-grey">
                                <label class="custom-control-label" for="solid-blue-grey">Default</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-primary" data-bg="bg-primary" id="solid-primary">
                                <label class="custom-control-label" for="solid-primary">Primary</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-danger" data-bg="bg-danger" id="solid-danger">
                                <label class="custom-control-label" for="solid-danger">Danger</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-success" data-bg="bg-success" id="solid-success">
                                <label class="custom-control-label" for="solid-success">Success</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-blue" data-bg="bg-blue" id="solid-blue">
                                <label class="custom-control-label" for="solid-blue">Blue</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-cyan" data-bg="bg-cyan" id="solid-cyan">
                                <label class="custom-control-label" for="solid-cyan">Cyan</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-pink" data-bg="bg-pink" id="solid-pink">
                                <label class="custom-control-label" for="solid-pink">Pink</label>
                            </div>

                        </div>

                        <div class="col-6">
                            <h3>Gradient</h3>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" class="custom-control-input bg-blue-grey" data-bg="bg-primary bg-darken-4" id="bg-primary bg-darken-41">
                                <label class="custom-control-label" for="bg-primary bg-darken-41">Default</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-primary" data-bg="bg-gradient-x-primary" id="bg-gradient-x-primary1">
                                <label class="custom-control-label" for="bg-gradient-x-primary1">Primary</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-danger" data-bg="bg-gradient-x-danger" id="bg-gradient-x-danger1">
                                <label class="custom-control-label" for="bg-gradient-x-danger1">Danger</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-success" data-bg="bg-gradient-x-success" id="bg-gradient-x-success1">
                                <label class="custom-control-label" for="bg-gradient-x-success1">Success</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-blue" data-bg="bg-gradient-x-blue" id="bg-gradient-x-blue1">
                                <label class="custom-control-label" for="bg-gradient-x-blue1">Blue</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-cyan" data-bg="bg-gradient-x-cyan" id="bg-gradient-x-cyan1">
                                <label class="custom-control-label" for="bg-gradient-x-cyan1">Cyan</label>
                            </div>

                            <div class="custom-control custom-radio mb-1">
                                <input type="radio" name="nav-dark-clr" checked class="custom-control-input bg-pink" data-bg="bg-gradient-x-pink" id="bg-gradient-x-pink1">
                                <label class="custom-control-label" for="bg-gradient-x-pink1">Pink</label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="clrOpt4" aria-labelledby="color-opt-4">

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-blue-grey" data-bg="bg-blue-grey bg-lighten-4" id="light-blue-grey">
                        <label class="custom-control-label" for="light-blue-grey">Default</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-primary" data-bg="bg-primary bg-lighten-4" id="light-primary">
                        <label class="custom-control-label" for="light-primary">Primary</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-danger" data-bg="bg-danger bg-lighten-4" id="light-danger">
                        <label class="custom-control-label" for="light-danger">Danger</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-success" data-bg="bg-success bg-lighten-4" id="light-success">
                        <label class="custom-control-label" for="light-success">Success</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-blue" data-bg="bg-blue bg-lighten-4" id="light-blue">
                        <label class="custom-control-label" for="light-blue">Blue</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-cyan" data-bg="bg-cyan bg-lighten-4" id="light-cyan">
                        <label class="custom-control-label" for="light-cyan">Cyan</label>
                    </div>

                    <div class="custom-control custom-radio mb-1">
                        <input type="radio" name="nav-light-clr" class="custom-control-input bg-pink" data-bg="bg-pink bg-lighten-4" id="light-pink">
                        <label class="custom-control-label" for="light-pink">Pink</label>
                    </div>

                </div>
            </div>

            <hr>

            <h5 class="mt-1 mb-1 text-bold-500">Menu Color Options</h5>
            <div class="form-group">
                <div class="btn-group customizer-sidebar-options" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-outline-info" data-sidebar="menu-light">Light Menu</button>
                    <button type="button" class="btn btn-outline-info" data-sidebar="menu-dark">Dark Menu</button>
                </div>
            </div>
        </div>
    </div> -->
    <!-- End: Customizer-->


    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="text-sm-right clearfix blue-grey lighten-2 mb-0 px-2"><span class="float-md-right d-block d-md-inline-block">Created : 2021 - <?= date('Y'); ?> </p>
    </footer>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="<?= base_url(); ?>app-assets/vendors/js/vendors.min.js?v=77"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="<?= base_url(); ?>app-assets/vendors/js/charts/apexcharts/apexcharts.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="<?= base_url(); ?>app-assets/js/core/app-menu.js?v=77"></script>
    <script src="<?= base_url(); ?>app-assets/js/core/app.js?v=77"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="<?= base_url(); ?>app-assets/js/scripts/cards/card-statistics.js"></script>
    <!-- END: Page JS-->

    <script src="<?= base_url(); ?>app-assets/js/scripts/customizer.min.js" type="text/javascript"></script>
    <!-- END STACK JS-->
    <script>
        var base_url = "<?= base_url(); ?>";
        var current_link = "<?= $this->session->userdata('current_link'); ?>";
        var lang = "<?= $this->session->userdata('language'); ?>";

        var g_BulanSelanjutnya = "<?= $this->lang->line('Bulan Selanjutnya'); ?>";
        var g_BulanSebelumnya = "<?= $this->lang->line('Bulan Sebelumnya'); ?>";
        var g_PilihBulan = "<?= $this->lang->line('Pilih Bulan'); ?>";
        var g_PilihTahun = "<?= $this->lang->line('Pilih Tahun'); ?>";
        var g_Januari = "<?= $this->lang->line('Januari'); ?>";
        var g_Februari = "<?= $this->lang->line('Februari'); ?>";
        var g_Maret = "<?= $this->lang->line('Maret'); ?>";
        var g_April = "<?= $this->lang->line('April'); ?>";
        var g_Mei = "<?= $this->lang->line('Mei'); ?>";
        var g_Juni = "<?= $this->lang->line('Juni'); ?>";
        var g_July = "<?= $this->lang->line('July'); ?>";
        var g_Agustus = "<?= $this->lang->line('Agustus'); ?>";
        var g_September = "<?= $this->lang->line('September'); ?>";
        var g_Oktober = "<?= $this->lang->line('Oktober'); ?>";
        var g_Nopember = "<?= $this->lang->line('Nopember'); ?>";
        var g_Desember = "<?= $this->lang->line('Desember'); ?>";
        var g_Jan = "<?= $this->lang->line('Jan'); ?>";
        var g_Feb = "<?= $this->lang->line('Feb'); ?>";
        var g_Mar = "<?= $this->lang->line('Mar'); ?>";
        var g_Apr = "<?= $this->lang->line('Apr'); ?>";
        var g_Mei = "<?= $this->lang->line('Mei'); ?>";
        var g_Jun = "<?= $this->lang->line('Jun'); ?>";
        var g_Jul = "<?= $this->lang->line('Jul'); ?>";
        var g_Ags = "<?= $this->lang->line('Ags'); ?>";
        var g_Sep = "<?= $this->lang->line('Sep'); ?>";
        var g_Okt = "<?= $this->lang->line('Okt'); ?>";
        var g_Nop = "<?= $this->lang->line('Nop'); ?>";
        var g_Des = "<?= $this->lang->line('Des'); ?>";
        var g_Ming = "<?= $this->lang->line('Ming'); ?>";
        var g_Sen = "<?= $this->lang->line('Sen'); ?>";
        var g_Sel = "<?= $this->lang->line('Sel'); ?>";
        var g_Rab = "<?= $this->lang->line('Rab'); ?>";
        var g_Kam = "<?= $this->lang->line('Kam'); ?>";
        var g_Jum = "<?= $this->lang->line('Jum'); ?>";
        var g_Sab = "<?= $this->lang->line('Sab'); ?>";
        var g_Hariini = "<?= $this->lang->line('Hari ini'); ?>";
        var g_Bersihkan = "<?= $this->lang->line('Bersihkan'); ?>";
        var g_Tutup = "<?= $this->lang->line('Tutup'); ?>";

        var g_pilihdata = "<?= $this->lang->line('Pilih Data'); ?>";
        var g_maaf = "<?= $this->lang->line('Maaf'); ?>";
        var g_exist = "<?= $this->lang->line('Exist'); ?>";
        var g_detailmin = "<?= $this->lang->line('Detail Minimun'); ?>";

        var g_ppn = "<?= $this->session->userdata('n_ppn'); ?>";
        var g_f_company_plusppn = "<?= $this->session->userdata('f_company_plusppn'); ?>";
    </script>
    <script src="<?= base_url(); ?>app-assets/js/scripts/tooltip/tooltip.js?v=77" type="text/javascript"></script>
    <script src="<?= base_url(); ?>assets/js/custom.js?v=77"></script>

    <script src="../../../app-assets/vendors/js/extensions/unslider-min.js"></script>
    <!-- BEGIN PAGE JS-->
    <?= put_footer(); ?>

</body>
<!-- END: Body-->

</html>