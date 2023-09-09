<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="tab" action="<?= base_url('web/ruang/update'); ?>" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3 class="title-3"><i class="fas fa-desktop"></i>Tambah Data Ruang Kuliah</h3>
                                    </div>
                                    <div class="col-md-2 float-right">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="nama" class=" form-control-label">Nama Ruangan</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nama" name="nama" value="<?= $data['nama']; ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kapasitas" class=" form-control-label">Kapasitas</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="number" min='1' value="<?= $data['kapasitas']; ?>" id="kapasitas" name="kapasitas" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="jenis" class=" form-control-label">Jenis</label>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <select name="jenis" id="jenis" class="form-control" required>
                                            <?php
                                            $sel_0 = '';
                                            $sel_1 = '';
                                            $sel_2 = '';
                                            switch ($data['jenis']) {
                                                case 'TEORI':
                                                    $sel_1 = ' SELECTED';
                                                    break;
                                                case 'LABORATORIUM':
                                                    $sel_2 = ' SELECTED';
                                                    break;
                                                default:
                                                    $sel_0 = ' SELECTED';
                                            }
                                            ?>
                                            <option value='' <?= $sel_0; ?> disabled></option>
                                            <option value='TEORI' <?= $sel_1; ?>>Teori</option>
                                            <option value='LABORATORIUM' <?= $sel_2; ?>>Laboratorium</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm">
                                    <i class="fa fa-refresh"></i> Ulangi
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="history.back()">
                                    <i class="fa fa-minus-circle"></i> Batal
                                </button>
                                <input type='hidden' name='kode' value='<?= $data['kode']; ?>'>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jquery JS-->
<script src="<?= base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>