<style>
    a[href="#previous"] .btn-danger {
        /* background-color: #ff7588 !important; */
    }
</style>
<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <h3 class="content-header-title mb-0"><?= $this->session->e_user_name; ?></h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html"><?= $this->lang->line('Dashboard'); ?></a>
                    </li>
                    <li class="breadcrumb-item active"><?= $this->lang->line('Profil'); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <!-- Form wizard with step validation section start -->
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fa fa-unlock-alt"></i> <?= $this->lang->line('Setel Ulang Kata Sandi'); ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-h font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                                <li><a data-action="close"><i class="feather icon-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <form action="#" class="steps-validation wizard-circle">

                                <!-- Step 1 -->
                                <h6><?= $this->lang->line('Konfirmasi Kata Sandi'); ?></h6>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="firstName3">
                                                    <?= $this->lang->line('Id Pengguna'); ?> :
                                                    <span class="danger">*</span>
                                                </label>
                                                <input type="text" autofocus placeholder="<?= $this->lang->line('Masukan') . ' ' . $this->lang->line('Id Pengguna'); ?>" class="form-control round required" name="user_id" id="user_id">
                                                <input type="hidden" class="form-control round required" id="i_user_id" name="i_user_id" value="<?= $data->i_user_id; ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="lastName3">
                                                    <?= $this->lang->line('Kata Sandi'); ?> :
                                                    <span class="danger">*</span>
                                                </label>
                                                <input type="password" placeholder="<?= $this->lang->line('Masukan') . ' ' . $this->lang->line('Kata Sandi'); ?>" name="repeat_password" class="form-control round required">
                                                <input type="hidden" class="form-control round required" id="password" name="password" value="<?= decrypt_password($data->e_user_password); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Step 2 -->
                                <h6><?= $this->lang->line('Mengatur Ulang Kata Sandi'); ?></h6>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="firstName3">
                                                    <?= $this->lang->line('Kata Sandi Baru'); ?> :
                                                    <span class="danger">*</span>
                                                </label>
                                                <input type="password" placeholder="<?= $this->lang->line('Masukan') . ' ' . $this->lang->line('Kata Sandi Baru'); ?>" class="form-control round required" name="e_user_password" id="e_user_password">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="lastName3">
                                                    <?= $this->lang->line('Konfirmasi Kata Sandi'); ?> :
                                                    <span class="danger">*</span>
                                                </label>
                                                <input type="password" placeholder="<?= $this->lang->line('Masukan') . ' ' . $this->lang->line('Konfirmasi Kata Sandi'); ?>" name="repeat_e_user_password" class="form-control round required">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Form wizard with step validation section end -->
</div>