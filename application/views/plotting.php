<?php
if (!empty($this->session->userdata('init'))) {
    $state = true;
    $init = $this->session->userdata('init');
    $smt_init = $init['semester'];
    $thak_init = $init['tahun_akademik'];

    $this->session->unset_userdata('init');
} else {
    $state = false;
}
?>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="title-3"><i class="fas fa-calendar-alt"></i>Plotting Mengajar Dosen</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    &nbsp;
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
                                        <?php
                                        if ($state) {
                                            $sel_ = '';
                                            $sel_0 = '';
                                            $sel_1 = '';
                                            switch ($smt_init) {
                                                case 0:
                                                    $sel_0 = ' SELECTED';
                                                    break;
                                                case 1:
                                                    $sel_1 = ' SELECTED';
                                                    break;
                                                default:
                                                    $sel_ = ' SELECTED';
                                            }
                                        ?>
                                            <option value='' disabled<?= $sel_; ?>>Pilih Semester</option>
                                            <option value='1' <?= $sel_1; ?>>Ganjil</option>
                                            <option value='0' <?= $sel_0; ?>>Genap</option>
                                        <?php
                                        } else {
                                        ?>
                                            <option value='' disabled>Pilih Semester</option>
                                            <option value='1'>Ganjil</option>
                                            <option value='0'>Genap</option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="tahun_akademik" class=" form-control-label">Tahun Akademik</label>
                                </div>
                                <div class="col-3 col-md-3">
                                    <select name="tahun_akademik" id="tahun_akademik" class="form-control" required>
                                        <?php
                                        if ($state) {
                                        ?>
                                            <option value='' disabled>Pilih Tahun Akademik</option>
                                            <?php
                                            if ($thak->num_rows() > 0) {
                                                foreach ($thak->result() as $th) {
                                                    $val_thak = $th->tahun_akademik;
                                                    $dis_thak = str_replace('-', '/', $val_thak);
                                                    $sel_ = '';
                                                    if ($val_thak == $thak_init) {
                                                        $sel_ = ' SELECTED';
                                                    }
                                            ?>
                                                    <option value='<?= $val_thak; ?>' <?= $sel_; ?>><?= $dis_thak; ?></option>
                                            <?php
                                                }
                                            }
                                        } else {
                                            ?>
                                            <option value='' selected disabled>Pilih Tahun Akademik</option>
                                            <?php
                                            if ($thak->num_rows() > 0) {
                                                foreach ($thak->result() as $th) {
                                                    $val_thak = $th->tahun_akademik;
                                                    $dis_thak = str_replace('-', '/', $val_thak);
                                            ?>
                                                    <option value='<?= $val_thak; ?>'><?= $dis_thak; ?></option>
                                        <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= base_url('web/plotting/import'); ?>" class="btn btn-sm btn-success"><i class="fa fa-upload"></i> Import</a>
                            <a href="<?= base_url('web/plotting/add'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Data</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="datatabel">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode MK</th>
                                            <th>Nama MK</th>
                                            <th>Jumlah SKS</th>
                                            <th>Kelas</th>
                                            <th>Nama Dosen</th>
                                            <th>Aksi</th>
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
    </div>
</div>

<!-- Jquery JS-->
<script src="<?= base_url('assets'); ?>/vendor/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
    base_url = "<?= base_url(); ?>";
</script>
<script src="<?= base_url('assets/js/pagination.js'); ?>"></script>
<script>
    $(document).ready(function() {
        function load_jadwal() {
            var smt = $("#semester").val();
            var thak = $("#tahun_akademik").val();

            if (smt !== '' && thak !== '') {
                pagination("datatabel", base_url + "web/plotting/data/" + smt + "/" + thak, []);
            }
        }

        $("#semester").on('change', function() {
            load_jadwal();
        });

        $("#tahun_akademik").on('change', function() {
            load_jadwal();
        });

        <?php
        if ($state) {
            echo 'load_jadwal()';
        }
        ?>
    });

    function hapus(id) {
        var id = id;
        Swal.fire({
            title: 'Hapus data plotting ini?',
            text: "Operasi ini tidak dapat dibatalkan!",
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: "Tidak, Batalkan!",
            reverseButtons: !0
        }).then(function(e) {
            /* Read more about isConfirmed, isDenied below */
            if (e.value === true) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('web/plotting/hapus'); ?>",
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(results) {
                        if (results.sukses === 'true') {
                            Swal.fire(
                                'Berhasil',
                                'Data plotting berhasil dihapus',
                                'success'
                            );
                            setTimeout(function() {
                                var smt = $("#semester").val();
                                var thak = $("#tahun_akademik").val();
                                pagination("datatabel", base_url + "web/plotting/data/" + smt + "/" + thak, []);
                            }, 1000);
                        } else {
                            Swal.fire(
                                'Gagal',
                                results.pesan,
                                'error'
                            );
                        }
                    }
                });
            } else {
                e.dismiss;
            }
        }, function(dismiss) {
            Swal.fire('Dibatalkan', 'Operasi dibatalkan oleh user', 'error');
        })
    }
</script>