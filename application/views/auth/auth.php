<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Stack admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, stack admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>LOGIN</title>
    <link rel="apple-touch-icon" href="<?= base_url(); ?>app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url(); ?>app-assets/images/ico/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/forms/icheck/icheck.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/forms/icheck/custom.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN STACK CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/app.css">
    <!-- END STACK CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/menu/menu-types/vertical-menu-modern.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/css/pages/login-register.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css">
    <!-- END Custom CSS-->
    
    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/vendors/css/forms/icheck/icheck.css"> -->

    <!-- <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>app-assets/styleku.css"> -->
</head>

<style>
input[type="checkbox"] {
    margin-left: 5px;
}
</style>
<body class="vertical-layout vertical-menu-modern 1-column  bg-full-screen-image menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-md-4 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <img src="<?= base_url(); ?>app-assets/images/logo/stack-logo-dark.png" alt="branding logo">
                                        <!-- <p class="font-large-1 text-uppercase text-primary"><STRONG>KOTA PELANGI GROUP</STRONG></p> -->
                                    </div>
                                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                        <span><strong>HEBAT</strong></span>
                                    </h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <h6><?= $this->session->flashdata('message'); ?></h6>
                                        <form class="form-horizontal" action="<?php base_url() . '/auth/' ?>" method="post" novalidate>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" id="user-name" name="username" placeholder="Your Username" required autofocus>
                                                <div class="form-control-position">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control" id="user-password" name="password" placeholder="Enter Password" required>
                                                
                                                <!-- <div class="controls skin-square">
                                                    <input type="checkbox" id="showPassword" name="showPassword">
                                                    <label for="showPassword"> Show Password</label>
                                                </div> -->
                                                <!-- <label class="checkbox-container">
                                                    <input type="checkbox" name="showPassword" id="showPassword">
                                                    <span class="checkmark"></span>
                                                    Show Password
                                                </label> -->
                                                <div class="form-control-position">
                                                    <i class="fa fa-key"></i>
                                                </div>
                                                <br><input type="checkbox" id="showPassword"> Show Password
                                            </fieldset>
                                            <button type="submit" class="btn btn-outline-primary btn-block"><i class="ft-unlock"></i> Login</button>
                                        </form>
                                    </div>
                                    <p class="card-subtitle line-on-side text-muted text-center font-small-3 mx-2 my-1">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <!-- BEGIN VENDOR JS-->
    <script src="<?= base_url(); ?>app-assets/vendors/js/vendors.min.js?v=9" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="<?= base_url(); ?>app-assets/vendors/js/forms/validation/jqBootstrapValidation.js?v=9" type="text/javascript"></script>
    <script src="<?= base_url(); ?>app-assets/vendors/js/forms/icheck/icheck.min.js?v=9" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN STACK JS-->
    <script src="<?= base_url(); ?>app-assets/js/core/app-menu.js?v=9" type="text/javascript"></script>
    <script src="<?= base_url(); ?>app-assets/js/core/app.js?v=9" type="text/javascript"></script>
    <script src="<?= base_url(); ?>app-assets/js/scripts/customizer.js?v=9" type="text/javascript"></script>
    <!-- END STACK JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="<?= base_url(); ?>app-assets/js/scripts/forms/form-login-register.js?v=9" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->

    <script src="<?= base_url(); ?>app-assets/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <script src="<?= base_url(); ?>app-assets/js/scripts/forms/checkbox-radio.min.js" type="text/javascript"></script>
</body>

</html>