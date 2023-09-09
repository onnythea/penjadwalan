<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="tab" action="<?= base_url('web/dayoff/save'); ?>" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h3 class="title-3"><i class="fas fa-ban"></i>Edit Data Day Off Dosen</h3>
                                    </div>
                                    <div class="col-md-2 float-right">
                                        &nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="nama" class=" form-control-label">Nama Dosen</label>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <select name='kode_dosen' class='form-control' id='kode_dosen' required>
                                            <option value='' selected disabled></option>
                                            <?php
                                            if ($dosen->num_rows() > 0) {
                                                foreach ($dosen->result() as $dd) {
                                                    if ($dd->nidn == $kode_dosen) {
                                                        $sel_ = ' SELECTED';
                                                    } else {
                                                        $sel_ = '';
                                                    }
                                            ?>
                                                    <option value='<?= $dd->nidn; ?>' <?= $sel_; ?>><?= $dd->nama; ?>
                                                <?php
                                                }
                                            }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="hari" class=" form-control-label">Hari Off</label>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <select name='hari' class='form-control' id='hari' required>
                                            <option value='' selected disabled></option>
                                            <?php
                                            if ($hari->num_rows() > 0) {
                                                foreach ($hari->result() as $hr) {
                                                    if ($hr->kode == $kode_hari) {
                                                        $sel_ = ' SELECTED';
                                                    } else {
                                                        $sel_ = '';
                                                    }
                                            ?>
                                                    <option value='<?= $hr->kode; ?>' <?= $sel_; ?>><?= $hr->nama; ?>
                                                <?php
                                                }
                                            }
                                                ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-4">
                                        <label for="jam" class=" form-control-label">Jam Off</label>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <div id="jam" class="form-check"><em>pilih dosen dulu</em></div>
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
<script>
    $(document).ready(function() {
        function fill_jam() {
            var dosen = $("#kode_dosen").val();
            var hari = $("#hari").val();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('web/dayoff/getdata'); ?>",
                data: {
                    dosen: dosen,
                    hari: hari
                },
                dataType: 'JSON',
                success: function(data) {
                    $("#jam").html(data.jam);
                }
            });
        }
        $("#kode_dosen").on('change', function() {
            fill_jam();
        });
        $("#hari").on('change', function() {
            fill_jam();
        });
        fill_jam();
    });
</script>