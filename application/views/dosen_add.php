<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="tab" action="<?= base_url('web/dosen/save'); ?>" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3 class="title-3"><i class="zmdi zmdi-account-calendar"></i>Tambah Data dosen</h3>
                                    </div>
                                    <div class="col-md-2 float-right">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="nidn" class=" form-control-label">NIP/NIDN</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nidn" name="nidn" class="form-control" required>
                                        <span class="help-block text-muted"><em>Masukkan NIP atau NIDN</em></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="nama" class=" form-control-label">Nama</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nama" name="nama" class="form-control" required>
                                        <span class="help-block text-muted"><em>Masukkan Nama Dosen (tanpa gelar atau sapaan)</em></span>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="alamat" class=" form-control-label">Alamat</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="alamat" name="alamat" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="telp" class=" form-control-label">No Tlp/HP</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="telp" name="telp" class="form-control" required>
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