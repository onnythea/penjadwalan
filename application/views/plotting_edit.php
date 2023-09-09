<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form id="tab" action="<?= base_url('web/plotting/update'); ?>" method="POST" class="form-horizontal">
                            <div class="card-header container-fluid">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3 class="title-3"><i class="fas fa-calendar-alt"></i>Edit Plotting Mengajar Dosen</h3>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="<?= base_url('web/plotting/import'); ?>" class="btn btn-sm btn-success"><i class="fa fa-upload"></i> Import</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="semester" class=" form-control-label">Semester</label>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <select name="semester" id="semester" class="form-control" required>
                                            <option value='' disabled>Pilih Semester</option>
                                            <option value='1'>Ganjil</option>
                                            <option value='0'>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="tahun_akademik" class=" form-control-label">Tahun Akademik</label>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <select name="tahun_akademik" id="tahun_akademik" class="form-control" required>
                                            <option value='' selected disabled>Pilih Tahun Akademik</option>
                                            <?php
                                            $y = date('Y');
                                            for ($i = $y - 2; $i <= $y + 1; $i++) {
                                                $val_thak = $i . '-' . ($i + 1);
                                                $dis_thak = str_replace('-', '/', $val_thak);
                                            ?>
                                                <option value='<?= $val_thak; ?>'><?= $dis_thak; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="matakuliah" class=" form-control-label">Mata Kuliah</label>
                                    </div>
                                    <div class="col-6 col-md-6">
                                        <select name="matakuliah" id="matakuliah" class="form-control" required>
                                            <option value='' disabled>Pilih Mata kuliah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="kelas" class=" form-control-label">Kelas</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <select name="kelas" id="kelas" class="form-control" required>
                                            <option value='' selected disabled>Pilih Kelas</option>
                                            <?php
                                            $arr_kelas = ['A', 'B', 'C', 'D', 'E', 'F'];
                                            foreach ($arr_kelas as $kelas) {
                                            ?>
                                                <option value='<?= $kelas; ?>'><?= $kelas; ?></option>
                                            <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="dosen" class=" form-control-label">Dosen</label>
                                    </div>
                                    <div class="col-4 col-md-4">
                                        <select name="dosen" id="dosen" class="form-control" required>
                                            <option value='' selected disabled>Pilih Dosen</option>
                                            <?php
                                            if ($dosen->num_rows() > 0) {
                                                foreach ($dosen->result() as $dsn) {
                                                    $kode_dosen = $dsn->nidn;
                                                    $nama_dosen = $dsn->nama;
                                            ?>
                                                    <option value='<?= $kode_dosen; ?>'><?= $nama_dosen; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <input type='hidden' name='kode' value='<?= $kode; ?>'>
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
        $("#semester").on('change', function() {
            var smt = $("#semester").val();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('web/plotting/getmk'); ?>",
                data: {
                    smt: smt
                },
                dataType: 'JSON',
                success: function(res) {
                    $("#matakuliah").html(res.matakuliah);
                }
            });
        });

        function init() {
            var smt = $("#semester").val();
            $.ajax({
                type: 'POST',
                url: "<?= base_url('web/plotting/getmk'); ?>",
                data: {
                    smt: smt
                },
                dataType: 'JSON',
                success: function(res) {
                    $('#semester option[value="<?= $semester; ?>"]').prop('selected', true);
                    $('#tahun_akademik option[value="<?= $data['tahun_akademik']; ?>"]').prop('selected', true);
                    $("#matakuliah").html(res.matakuliah);
                    $('#matakuliah option[value="<?= $data['kode_mk']; ?>"]').prop('selected', true);
                    $('#kelas option[value="<?= $data['kelas']; ?>"]').prop('selected', true);
                    $('#dosen option[value="<?= $data['kode_dosen']; ?>"]').prop('selected', true);
                }
            });
        }
        init();
    });
</script>