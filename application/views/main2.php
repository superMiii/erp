<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Aplikasi | ERP</title>
    <link rel="apple-touch-icon" href="<?= base_url(); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/colors/palette-tooltip.css">
    <?= put_headers(); ?>
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/app.css">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/fonts/font-awesome/css/font-awesome.min.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <!-- END Custom CSS-->
</head>

<body class="vertical-layout vertical-menu 2-columns   menu-expanded fixed-navbar" data-open="click" data-menu="vertical-menu" data-col="2-columns">
    <!-- fixed-top-->
    <nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
        <div class="navbar-wrapper">
            <div class="navbar-header">
                <ul class="nav navbar-nav flex-row">
                    <li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
                    <li class="nav-item">
                        <a class="navbar-brand" href="<?= base_url(); ?>">
                            <img class="brand-logo" alt="stack admin logo" src="<?= base_url(); ?>app-assets/images/logo/stack-logo-light.png">
                            <h2 class="brand-text">Stack</h2>
                        </a>
                    </li>
                    <li class="nav-item d-md-none">
                        <a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container content">
                <div class="collapse navbar-collapse" id="navbar-mobile">
                    <ul class="nav navbar-nav mr-auto float-left">
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu"></i></a></li>
                        <li class="nav-item d-none d-md-block"><a class="nav-link nav-link-expand" href="#"><i class="ficon ft-maximize"></i></a></li>

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
                                        <a href="#" onclick="set_company('<?= $key->i_company; ?>','<?= $key->e_company_name; ?>','<?= $key->f_company_plusppn; ?>'); return false;" class="dropdown-item <?= $status; ?>"><?= $key->e_company_name; ?></a>
                                <?php }
                                } ?>
                            </div>
                        </li>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                        <?php //echo $this->session->userdata('i_company'). " ; ". $this->session->userdata('e_company_name'). " ; ". $this->session->userdata('f_company_plusppn');
                        ?>
                        <!-- <li>                             
                        <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>"><i class="ft-power"></i>
                                    Logout</a>
                                </li> -->

                    </ul>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-language nav-item"><a class="dropdown-toggle nav-link" id="dropdown-flag" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-gb"></i><span class="selected-language"></span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdown-flag"><a class="dropdown-item" href="#"><i class="flag-icon flag-icon-gb"></i> English</a>
                                <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>
                                <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-cn"></i> Chinese</a>
                                <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> German</a>
                            </div>
                        </li>
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-bell"></i>
                                <span class="badge badge-pill badge-default badge-danger badge-default badge-up">5</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">Notifications</span>
                                        <span class="notification-tag badge badge-default badge-danger float-right m-0">5
                                            New</span>
                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="ft-plus-square icon-bg-circle bg-blue bg-darken-4 text-white"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">You have new order!</h6>
                                                <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor
                                                    sit amet, consectetuer elit.</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">30 minutes ago</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="ft-download-cloud icon-bg-circle bg-blue bg-darken-4 text-white"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading red darken-1">99% Server load</h6>
                                                <p class="notification-text font-small-3 text-muted">Aliquam tincidunt
                                                    mauris eu risus.</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Five hour ago</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="ft-alert-triangle icon-bg-circle bg-yellow bg-darken-3"></i>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
                                                <p class="notification-text font-small-3 text-muted">Vestibulum auctor
                                                    dapibus neque.</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="ft-check-circle icon-bg-circle bg-cyan"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Complete the task</h6>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last week</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left align-self-center"><i class="ft-file icon-bg-circle bg-teal"></i></div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Generate monthly report</h6>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Last month</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all notifications</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-notification nav-item">
                            <a class="nav-link nav-link-label" href="#" data-toggle="dropdown"><i class="ficon ft-mail"></i>
                                <span class="badge badge-pill badge-default badge-warning badge-default badge-up">3</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0">
                                        <span class="grey darken-2">Messages</span>
                                        <span class="notification-tag badge badge-default badge-warning float-right m-0">4
                                            New</span>
                                    </h6>
                                </li>
                                <li class="scrollable-container media-list">
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <span class="avatar avatar-sm avatar-online rounded-circle">
                                                    <img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Margaret Govan</h6>
                                                <p class="notification-text font-small-3 text-muted">I like your
                                                    portfolio, let's start.</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Today</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <span class="avatar avatar-sm avatar-busy rounded-circle">
                                                    <img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-2.png" alt="avatar"><i></i></span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Bret Lezama</h6>
                                                <p class="notification-text font-small-3 text-muted">I have seen your
                                                    work, there is</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Tuesday</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <span class="avatar avatar-sm avatar-online rounded-circle">
                                                    <img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-3.png" alt="avatar"><i></i></span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Carie Berra</h6>
                                                <p class="notification-text font-small-3 text-muted">Can we have call in
                                                    this week ?</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">Friday</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <div class="media">
                                            <div class="media-left">
                                                <span class="avatar avatar-sm avatar-away rounded-circle">
                                                    <img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-6.png" alt="avatar"><i></i></span>
                                            </div>
                                            <div class="media-body">
                                                <h6 class="media-heading">Eric Alsobrook</h6>
                                                <p class="notification-text font-small-3 text-muted">We have project
                                                    party this saturday.</p>
                                                <small>
                                                    <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">last month</time>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center" href="javascript:void(0)">Read all messages</a></li>
                            </ul>
                        </li>
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <span class="avatar avatar-online">
                                    <img src="<?= base_url(); ?>app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span>
                                <span class="user-name">John Doe</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="user-profile.html"><i class="ft-user"></i> Edit Profile</a>
                                <a class="dropdown-item" href="email-application.html"><i class="ft-mail"></i> My
                                    Inbox</a>
                                <a class="dropdown-item" href="user-cards.html"><i class="ft-check-square"></i> Task</a>
                                <a class="dropdown-item" href="chat-application.html"><i class="ft-message-square"></i>
                                    Chats</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>"><i class="ft-power"></i>
                                    Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class=" navigation-header">
                    <span>General</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="General"></i>
                </li>
                <?php
                if (get_menu()) {
                    foreach (get_menu()->result() as $key) {
                        if ($key->e_folder == '#') { ?>
                            <li class="nav-item <?php if ($this->session->userdata('idmenu_1') == $key->i_menu) echo ' open'; ?>">
                                <a href="#" class="nav-link"><i class="<?= $key->icon; ?>"></i>
                                    <span class="menu-title" data-i18n=""><?= $key->e_menu; ?></span></a>
                                <ul class="menu-content" data-submenu-title="Layouts">
                                    <?php foreach (get_sub_menu($key->i_menu)->result() as $row) {
                                        if ($row->e_folder == '#') { ?>
                                            <li class="<?php if ($this->session->userdata('idmenu_2') == $row->i_menu) echo ' open'; ?>"><a class="menu-item" href="#"><i class="<?= $row->icon; ?>"></i> <?= $row->e_menu; ?></a>
                                                <ul class="menu-content">
                                                    <?php foreach (get_sub_menu($row->i_menu)->result() as $kuy) { ?>
                                                        <li class="<?php if ($this->session->userdata('idmenu_3') == $kuy->i_menu) echo ' active hover'; ?>"><a onclick="set_activemenu('<?= $key->i_menu; ?>','<?= $row->i_menu; ?>','<?= $kuy->i_menu; ?>', '<?= $kuy->e_folder; ?>'); return true;" data-toggle="tooltip" data-original-title="<?= $kuy->e_menu; ?>" data-html="true" class="menu-item ml-1"><?= $kuy->e_menu; ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                        <?php } else { ?>
                                            <li class="<?php if ($this->session->userdata('idmenu_3') == $row->i_menu) echo ' active hover'; ?>"><a onclick="set_activemenu('<?= $key->i_menu; ?>','<?= 'skip'; ?>','<?= $row->i_menu; ?>', '<?= $row->e_folder; ?>'); return true;" data-toggle="tooltip" data-original-title="<?= $row->e_menu; ?>" data-html="true" class="menu-item"><i class="<?= $row->icon; ?>"></i> <?= $row->e_menu; ?></a></li>
                                    <?php }
                                    } ?>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a href="<?= base_url($key->e_folder); ?>">
                                    <i class="<?= $key->icon; ?>"></i>
                                    <span>
                                        <?= $key->e_menu; ?>
                                    </span>
                                </a>
                            </li>
                <?php }
                    }
                } ?>
                <li class=" nav-item"><a href="#"><i class="ft-align-left"></i><span class="menu-title" data-i18n="">Menu levels</span></a>
                    <ul class="menu-content">
                        <li><a class="menu-item" href="#">Second level</a>
                        </li>
                        <li><a class="menu-item" href="#">Second level child</a>
                            <ul class="menu-content">
                                <li><a class="menu-item" href="#">Third level</a>
                                </li>
                                <li><a class="menu-item" href="#">Third level child</a>
                                    <ul class="menu-content">
                                        <li><a class="menu-item" href="#">Fourth level</a>
                                        </li>
                                        <li><a class="menu-item" href="#">Fourth level</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="app-content content">
        <div class="content-wrapper">
            <?= $contents; ?>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <footer class="footer footer-static footer-light navbar-border">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">Copyright &copy; 2018 <a class="text-bold-800 grey darken-2" href="https://themeforest.net/user/pixinvent/portfolio?ref=pixinvent" target="_blank">PIXINVENT
                </a>, All rights reserved. </span>
            <span class="float-md-right d-block d-md-inline-block d-none d-lg-block">Hand-crafted & Made with <i class="ft-heart pink"></i></span>
        </p>
    </footer>
</body>

</html>
<!-- BEGIN VENDOR JS-->
<script src="<?= base_url(); ?>app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
<!-- BEGIN VENDOR JS-->
<!-- BEGIN STACK JS-->
<script src="<?= base_url(); ?>app-assets/js/core/app-menu.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>app-assets/js/core/app.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>app-assets/js/scripts/customizer.js" type="text/javascript"></script>
<!-- END STACK JS-->
<script>
    var base_url = "<?= base_url(); ?>";
    var current_link = "<?= $this->session->userdata('current_link'); ?>";
</script>
<script src="<?= base_url(); ?>app-assets/js/scripts/tooltip/tooltip.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/js/custom.js"></script>
<!-- BEGIN PAGE JS-->
<?= put_footer(); ?>
<!-- END BEGIN PAGE JS-->