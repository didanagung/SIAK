<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="Creative Tim">
    <title>SIA | Register</title>
    <!-- Favicon -->
    <link href="<?= base_url('assets/img/brand/logo.jpeg') ?>" rel="icon" type="image/jpeg">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bungee+Shade" rel="stylesheet">
    <!-- Icons -->
    <link href="<?= base_url('assets/vendor/nucleo/css/nucleo.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/vendor/@fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="<?= base_url('assets/css/argon.css?v=1.0.0') ?>" rel="stylesheet">
</head>

<body class="bg-default">
    <div class="main-content">
        <!-- Header -->
        <div class="header bg-gradient-warning py-7 py-lg-8">
            <div class="separator separator-bottom separator-skew zindex-100">
                <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
                </svg>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-Secondary shadow-lg border-0">

                        <div class="card-body px-lg-5 py-lg-5">
                            <div class="text-center text-muted mb-4">
                                <h1 class="my-5" style="font-family: 'Bungee Shade';font-size:40px;">REGISTER</h1>
                            </div>
                            <form role="form" action="<?= base_url('register') ?>" method="post">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Nama" type="text" name="nama">
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Username" type="text" name="username">
                                    </div>
                                </div>
                                <div class="form-group mb-3" hidden>
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" type="text" name="role" value="bendahara">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Jenis Kelamin (Laki-Laki atau Perempuan)" type="text" name="jk">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Alamat" type="text" name="alamat">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Email" type="email" name="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <input class="form-control" placeholder="Password" type="password" name="password">
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mt-4">Register</button>
                                </div>
                            </form>
                            <div class="text-center mt-2">
                                <p class="text-sm">Sudah Punya Akun? <a href="<?= base_url('login') ?>">&laquo Login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="<?= base_url('assets/vendor/jquery/dist/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <!-- Argon JS -->
    <script src="<?= base_url('assets/js/argon.js?v=1.0.0') ?>"></script>
    <!-- SWEETALERT -->
    <script src="<?= base_url('assets/vendor/sweetalert/sweetalert2.all.min.js') ?>"></script>

    <?php
    $formErrorNama = form_error('nama');
    $formErrorUsername = form_error('username');
    $formErrorJk = form_error('jk');
    $formErrorAlamat = form_error('alamat');
    $formErrorEmail = form_error('email');
    $formErrorPassword = form_error('password');

    if (!empty($formErrorNama) || !empty($formErrorUsername) || !empty($formErrorJk) || !empty($formErrorAlamat) || !empty($formErrorEmail) || !empty($formErrorPassword)) :
    ?>
        <!-- SCRIPT SWEETALERT INLINE -->
        <script>
            $(window).on('load', function() {
                let pesan = "Data Tidak Lengkap atau Username Sudah dipakai";
                swal('Oops!', pesan, 'error');
            });
        </script>
    <?php endif; ?>

    <?php
    $pesan = $this->session->flashdata('pesan_error');
    if (!empty($pesan)) :
    ?>
        <!-- SCRIPT SWEETALERT INLINE -->
        <script>
            $(window).on('load', function() {
                let pesan = "<?= $pesan ?>";
                swal('Oops!', pesan, 'error');
            });
        </script>
    <?php endif; ?>
</body>

</html>