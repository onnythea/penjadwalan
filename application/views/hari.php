<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="title-3"><i class="fas fa-calendar-o"></i>Data hari kuliah</h3>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= base_url('web/hari/add'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Data</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="datatabel">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Hari</th>
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
        pagination("datatabel", base_url + "web/hari/data", []);
    });
</script>
<script type="text/javascript">
    function hapus(id) {
        var id = id;
        Swal.fire({
            title: 'Hapus data hari ini?',
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
                    url: "<?= base_url('web/hari/hapus'); ?>",
                    data: {
                        id: id
                    },
                    dataType: 'JSON',
                    success: function(results) {
                        if (results.sukses === 'true') {
                            Swal.fire(
                                'Berhasil',
                                'Data hari berhasil dihapus',
                                'success'
                            );
                            setTimeout(function() {
                                pagination("datatabel", base_url + "web/hari/data", []);
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