<form class="form-validation" novalidate>
    <div class="content-header row">
        <div class="content-header-left col-md-12 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Master'); ?></a></li>
                        <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line('Area'); ?></a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url($this->folder); ?>"><?= $this->lang->line($this->title); ?></a></li>
                        <li class="breadcrumb-item active"><?= $this->lang->line('Detail'); ?> <?= $this->lang->line($this->title); ?></li>
                    </ol>
                </div>
            </div>
            <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
        </div>
    </div>
    <div class="content-body">
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-eye"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line("Pengguna"); ?></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
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
                            <div class="card-body card-dashboard">
                                <table class="table table-sm table-column table-bordered" id="tablecover">
                                    <thead>
                                        <tr>
                                            <th width="50%;"><?= $this->lang->line("Kode Area Cakupan"); ?></th>
                                            <th><?= $data->i_area_cover_id; ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Nama Area Cakupan"); ?></th>
                                            <th><?= $data->e_area_cover_name; ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Status"); ?></th>
                                            <?php $aktif = ($data->f_area_cover_active == 't') ? $this->lang->line('Aktif') : $this->lang->line('Tidak Aktif'); ?>
                                            <?php $color = ($data->f_area_cover_active == 't') ? "success" : "danger"; ?>
                                            <th><span class="badge badge-<?= $color; ?>"><?= $aktif; ?></span></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Dibuat"); ?></th>
                                            <th><?= date('d-m-Y H:i:s', strtotime($data->d_area_cover_entry)); ?></th>
                                        </tr>
                                        <tr>
                                            <th><?= $this->lang->line("Diubah"); ?></th>
                                            <th><?= date('d-m-Y H:i:s', strtotime($data->d_area_cover_update)); ?></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="content-body">
        <section id="pagination">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header header-elements-inline <?= $this->session->e_color; ?> bg-darken-1 text-white">
                            <h4 class="card-title"><i class="feather icon-grid"></i> <?= $this->lang->line("Detail"); ?> <?= $this->lang->line($this->title); ?></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
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
                            <div class="card-body card-dashboard">
                                <div class="form-body">
                                    <table class="table table-sm table-column table-bordered" id="tablecoverarea">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5%">No</th>
                                                <th width="30%"><?= $this->lang->line("Nama Area Provinsi"); ?></th>
                                                <th width="70%"><?= $this->lang->line("Nama Kota / Kabupaten"); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $ii = 0;
                                            if ($detail->num_rows() > 0) {
                                                foreach ($detail->result() as $row) {
                                                    $ii++; ?>
                                                    <tr>
                                                        <td class="text-center"><?= $ii; ?></td>
                                                        <td><?= $row->e_area_name; ?></td>
                                                        <td>
                                                            <?php $i = 0;
                                                            foreach (json_decode($row->city) as $city) {
                                                                $i++; ?>
                                                                <?= $i . '. ' . explode("|", $city)[1]; ?><br>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-actions">
                                    <a href="<?= base_url($this->folder); ?>" class="btn btn-secondary round btn-min-width mr-1"><i class="icon-action-undo mr-1"></i><?= $this->lang->line("Kembali"); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</form>