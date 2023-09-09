<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="tab" action="<?= base_url('web/matakuliah/save'); ?>" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3 class="title-3"><i class="fas fa-tasks"></i>Tambah Data Matakuliah</h3>
                                    </div>
                                    <div class="col-md-2 float-right">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kode_mk" class=" form-control-label">Kode Mata Kuliah</label>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <input type="text" id="kode_mk" name="kode_mk" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="nama" class=" form-control-label">Nama Mata Kuliah</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="text" id="nama" name="nama" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="jenis" class=" form-control-label">Jenis</label>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <select name="jenis" id="jenis" class="form-control" required>
                                            <option value='' selected disabled></option>
                                            <option value='TEORI'>Teori</option>
                                            <option value='PRAKTIKUM'>Praktikum</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="sks" class=" form-control-label">Jumlah SKS</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="number" min='1' max='8' value='1' id="sks" name="sks" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="semester" class=" form-control-label">Semester</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="number" min='1' max='8' value='1' id="semester" name="semester" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="aktif" class=" form-control-label">Aktif</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <select name="aktif" id="aktif" class="form-control" required>
                                            <option value='' selected disabled></option>
                                            <option value='True'>Ya</option>
                                            <option value='False'>Tidak</option>
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