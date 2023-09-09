<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="f_dosen" action="<?= base_url('import/matakuliah'); ?>" name="f_matakuliah" enctype="multipart/form-data" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="title-3"><i class="fas fa-tasks"></i>Import Data Mata Kuliah</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a target="_blank" href="<?= base_url('upload/format_import_matakuliah.xlsx'); ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Template Excel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="nidn" class=" form-control-label">Pilih File</label>
                                    </div>
                                    <div class="col-12 col-md-9">
                                        <input type="file" id="import_excel" name="import_excel" class="form-control" required>
                                        <span class="help-block text-muted"><em>Pilih file sesuai format</em></span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-save"></i> Upload
                                </button>
                                <button type="reset" class="btn btn-danger btn-sm" onclick="history.back()">
                                    <i class="fa fa-ban"></i> Batal
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