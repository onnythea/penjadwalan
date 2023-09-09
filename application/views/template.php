<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title><?= $title; ?> - Penjadwalan dengan Algoritma Genetika</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets'); ?>/images/icon/icon-gold.png" />

    <!-- Fontfaces CSS-->
    <link href="<?= base_url('assets'); ?>/css/font-face.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="<?= base_url('assets'); ?>/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="<?= base_url('assets'); ?>/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/vector-map/jqvmap.min.css" rel="stylesheet" media="all">
    <link href="<?= base_url('assets'); ?>/vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Data Table JS
		============================================ -->
    <link href="<?= base_url('assets'); ?>/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap.css" rel="stylesheet">
    <!-- Main CSS-->
    <link href="<?= base_url('assets'); ?>/css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- MENU SIDEBAR-->
        <?php $this->load->view('sidebar'); ?>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container2">
            <!-- HEADER DESKTOP-->
            <?php $this->load->view('header_desktop'); ?>
            <?php $this->load->view('right_sidebar'); ?>
            <!-- END HEADER DESKTOP-->
            <?php $this->load->view($page); ?>

            <!-- END PAGE CONTAINER-->
            <div class="text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="footer">
                                <p><small>Copyright © 2023. All rights reserved. Created by <a href="https://inspirasicoding.wordpress.com">OnnyThea</a>.</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS-->
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="<?= base_url('assets'); ?>/vendor/slick/slick.min.js">
    </script>
    <script src="<?= base_url('assets'); ?>/vendor/wow/wow.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/animsition/animsition.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="<?= base_url('assets'); ?>/vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="<?= base_url('assets'); ?>/vendor/circle-progress/circle-progress.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/select2/select2.min.js">
    </script>
    <script src="<?= base_url('assets'); ?>/vendor/vector-map/jquery.vmap.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/vector-map/jquery.vmap.min.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/vector-map/jquery.vmap.sampledata.js"></script>
    <script src="<?= base_url('assets'); ?>/vendor/vector-map/jquery.vmap.world.js"></script>

    <script src="<?= base_url('assets'); ?>/js/data-table/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets'); ?>/js/data-table/data-table-act.js"></script>

    <!-- Main JS-->
    <script src="<?= base_url('assets'); ?>/js/main.js"></script>
    <!-- Sweetalert -->
    <script src="<?= base_url('assets'); ?>/vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    <?php
    if (!empty($this->session->userdata('notif'))) {
        $notif = $this->session->userdata('notif');
        $this->session->unset_userdata('notif');
    }
    if (!empty($notif)) {
        $pesan = $notif['pesan'];
        switch ($notif['icon']) {
            case 'success':
                echo "<script>Swal.fire('Berhasil','$pesan','success');</script>";
                break;
            case 'error':
                echo "<script>Swal.fire('Kesalahan','$pesan','error');</script>";
                break;
        }
    }
    ?>
</body>

</html>
<!-- end document-->