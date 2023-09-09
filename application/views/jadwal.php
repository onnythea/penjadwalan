<style type="text/css">
    #loading-div-background {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    #loading-div {
        width: 250px;
        height: 190px;
        background-color: #ffffff;
        border: 1px solid #336699;
        text-align: center;
        color: #336699;
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -150px;
        margin-top: -100px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
    }
</style>
<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="title-3"><i class="fas fa-clock-o"></i>Jadwal Perkuliahan</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <form class="form-horizontal">
                            <div class="card-body">
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="semester" class=" form-control-label">Semester</label>
                                    </div>
                                    <div class="col-3 col-md-3">
                                        <select name="semester_tipe" id="semester_tipe" class="form-control" required>
                                            <option value='' disabled selected>Pilih Semester</option>
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
                                            if ($thak->num_rows() > 0) {
                                                foreach ($thak->result() as $th) {
                                                    $val_thak = $th->tahun_akademik;
                                                    $dis_thak = str_replace('-', '/', $val_thak);
                                            ?>
                                                    <option value='<?= $val_thak; ?>'><?= $dis_thak; ?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="jumlah_populasi" class=" form-control-label">Jumlah Populasi</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="text" value='10' name="jumlah_populasi" id="jumlah_populasi" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="probabilitas_crossover" class=" form-control-label">Probabilitas CrossOver</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="text" value='0.70' name="probabilitas_crossover" id="probabilitas_crossover" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="probabilitas_mutasi" class=" form-control-label">Probabilitas Mutasi</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="text" value='0.40' name="probabilitas_mutasi" id="probabilitas_mutasi" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <div class="col col-md-3">
                                        <label for="jumlah_generasi" class=" form-control-label">Probabilitas Mutasi</label>
                                    </div>
                                    <div class="col-2 col-md-2">
                                        <input type="text" value='10000' name="jumlah_generasi" id="jumlah_generasi" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-refresh"></i> Generate Jadwal</button>
                                <button type="button" class="btn btn-sm btn-success" id="btn_export"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                            </div>
                        </form>
                        <div class="card-body">
                            <div id="loading-div-background">
                                <div id="loading-div" class="ui-corner-all">
                                    <img style="height:120px;width:160px;margin:5px;" src="<?= base_url('assets/images/loading2.gif'); ?>" alt="Loading.." /><br />Generating Timetable<br />Please wait..
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="datatabel">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Hari</th>
                                            <th>Jam</th>
                                            <th>Kode MK</th>
                                            <th>Nama MK</th>
                                            <th>Kelas</th>
                                            <th>Nama Dosen</th>
                                            <th>Ruang</th>
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
            var smt = $("#semester_tipe").val();
            var thak = $("#tahun_akademik").val();

            if (smt !== '' && thak !== '') {
                pagination("datatabel", base_url + "web/penjadwalan/data/" + smt + "/" + thak, []);
            }
        }

        $("#semester_tipe").on('change', function() {
            load_jadwal();
        });

        $("#tahun_akademik").on('change', function() {
            load_jadwal();
        });

        var spinner = $('#loading-div-background');
        $(function() {
            $('form').submit(function(e) {
                e.preventDefault();
                spinner.show();
                $.ajax({
                    url: '<?= base_url('web/generate_jadwal'); ?>',
                    data: $(this).serialize(),
                    type: 'POST',
                    dataType: 'JSON'
                }).done(function(resp) {
                    spinner.hide();
                    if (resp.status === 'success') {
                        Swal.fire(
                            'Berhasil',
                            resp.pesan,
                            'success'
                        );
                        load_jadwal();
                    } else {
                        Swal.fire(
                            'Kesalahan',
                            resp.pesan,
                            'error'
                        );
                    }
                });
            });
        });
    });

    $("#btn_export").click(function() {
        var smt = $("#semester_tipe").val();
        var thak = $("#tahun_akademik").val();

        $.ajax({
            url: '<?= base_url('web/excel_export'); ?>',
            data: {
                smt: smt,
                thak: thak
            },
            type: 'POST',
            dataType: 'JSON',
            success: function(result) {
                if (result.status == 'success') {
                    var url_ = "<?= base_url('web/do_excel_export/'); ?>" + smt + "/" + thak;
                    location.href = url_;
                } else {
                    Swal.fire(
                        'Kesalahan',
                        'Data tidak ditemukan',
                        'error'
                    );
                }
            }
        });
    });
</script>