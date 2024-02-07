<div class="content-header row">
    <div class="content-header-left col-md-12 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="return false;"><?= $this->lang->line("Penjualan"); ?></a></li>
                    <li class="breadcrumb-item active"><?= $this->lang->line($this->title); ?></li>
                </ol>
            </div>
        </div>
        <!-- <h3 class="content-header-title mb-0">Basic DataTables</h3> -->
    </div>
</div>
<div class="content-body">
    <!-- Alternative pagination table -->
    <section id="pagination">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header header-elements-inline <?= $this->session->e_color;?> bg-darken-1 text-white">
                        <h4 class="card-title"><i class="feather icon-list"></i> <?= $this->lang->line("Daftar"); ?> <?= $this->lang->line($this->title); ?></h4>
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
                        <form class="form form-horizontal" action="<?= base_url($this->folder); ?>" method="post">
                                <div class="form-body">
                                    <h6 class="form-section"></h6>
                                    <div class="form-group row m-auto">
                                        <div class="col-md-8 row m-auto">
                                            <div class="col-md-5">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <!-- <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color;?> bg-darken-1 text-white" type="button"><i class="feather icon-calendar"></i></button>
                                                        </div> -->
                                                        <select name="month" id="month" class="form-control select2">
                                                            <option value="01" <?php if($month=='01'){ echo "selected";}?>>Januari</option>
                                                            <option value="02" <?php if($month=='02'){ echo "selected";}?>>Februari</option>
                                                            <option value="03" <?php if($month=='03'){ echo "selected";}?>>Maret</option>
                                                            <option value="04" <?php if($month=='04'){ echo "selected";}?>>April</option>
                                                            <option value="05" <?php if($month=='05'){ echo "selected";}?>>Mei</option>
                                                            <option value="06" <?php if($month=='06'){ echo "selected";}?>>Juni</option>
                                                            <option value="07" <?php if($month=='07'){ echo "selected";}?>>Juli</option>
                                                            <option value="08" <?php if($month=='08'){ echo "selected";}?>>Agustus</option>
                                                            <option value="09" <?php if($month=='09'){ echo "selected";}?>>September</option>
                                                            <option value="10" <?php if($month=='10'){ echo "selected";}?>>Oktober</option>
                                                            <option value="11" <?php if($month=='11'){ echo "selected";}?>>November</option>
                                                            <option value="12" <?php if($month=='12'){ echo "selected";}?>>Desember</option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <!-- <div class="input-group-prepend">
                                                            <button class="btn <?= $this->session->e_color;?> bg-darken-1 text-white" required type="button"><i class="feather icon-calendar"></i></button>
                                                        </div> -->
                                                        <select name="year" id="year" class="form-control select2">
                                                            <?php 
                                                                for ($i=2021; $i <= date('Y')+1 ; $i++) { ?>
                                                                    <option value="<?= $i;?>" <?php if($year==$i){ echo "selected";}?>><?= $i;?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <fieldset>
                                                    <div class="input-group">
                                                        <button class="btn btn-block <?= $this->session->e_color;?> bg-darken-1 text-white" type="submit"><i class="feather icon-search fa-lg"></i></button>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                </div>
                            </form>

                            <?php if (check_role($this->i_menu, 1)) {
                                $i_menu = $this->i_menu;
                            } else {
                                $i_menu = "";
                            } ?>
                            <input type="hidden" id="i_menu" value="<?= $i_menu; ?>">
                            <input type="hidden" id="path" value="<?= $this->folder; ?>">
                            <div class="table-responsive">
                                <table class="table table-xs display nowrap table-striped table-bordered" id="serverside">
                                    <thead class="<?= $this->session->e_color;?> bg-darken-1 text-white">
                                        <tr>
                                            <th>No</th>
                                            <th><?= $this->lang->line("Periode"); ?></th>
                                            <th><?= $this->lang->line("Nama Area Provinsi"); ?></th>
                                            <th><?= $this->lang->line("Target"); ?></th>
                                            <th><?= $this->lang->line("Aksi"); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ Alternative pagination table -->
</div>