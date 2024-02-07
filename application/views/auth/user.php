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
                    <li class="breadcrumb-item active"><?= $this->lang->line('Ganti Profil'); ?>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- <form class="form-validation" novalidate> -->
<?php echo form_open_multipart('Pengguna/edit') ?>


<div class="content-body">
    <!-- Form wizard with step validation section start -->
    <section id="validation">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><i class="fa fa-user"></i></i> <?= $this->lang->line('Ganti Profil'); ?></h4>
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

                        <fieldset>
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="form-group">
                                            <table  id="tablecover">
                                                <thead>
                                                    <tr>
                                                        <th width="50%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                        <td class="text-center">
                                                            <img src="<?php echo base_url(); ?>assets/images/avatar/<?= $data->ava; ?>" width="479" height="497">
                                                        </td>
                                                    </tr>
                                                </thead>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <!-- Step 2 -->
                                <h6><?= $this->lang->line('Unggah Profil Baru'); ?></h6>
                                <fieldset>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="controls">


                                                <input type="hidden" id="i_user" name="i_user" value="<?= $data->i_user; ?>">
                                                <input type="hidden" id="e_name" name="e_name" value="<?= $data->e_user_name; ?>">
                                                <input type="hidden" id="fotoold" name="fotoold" value="<?= $data->ava; ?>">
                                                <input type="file" accept="image/png, image/gif, image/jpeg" name="fotonew" class="form-control" placeholder="fotonew">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <button type="submit" class="btn btn-warning round btn-min-width mr-1"><i class="icon-paper-plane mr-1"></i><?= $this->lang->line("Ubah"); ?></button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Form wizard with step validation section end -->
</div>
<?php echo form_close(); ?>
