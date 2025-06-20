<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMA IT Alia</title>

  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.css') ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.css') ?>">
  
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
  <?= $this->include('layouts/partials/navbar') ?>
  <?= $this->include('layouts/partials/' . (session('role') ?? 'teacher') . '/sidebar') ?>

  <div class="content-wrapper pt-3">
    <div class="container-fluid">
        <?= $this->renderSection('header') ?>
        <?= $this->renderSection('breadcrumb') ?>
        <?= $this->renderSection('content') ?>
    </div>
  </div>

  <?= $this->include('layouts/partials/footer') ?>
</div>

<!-- jQuery -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-validation/jquery.validate.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-validation/additional-methods.min.js') ?>"></script>
<!-- Bootstrap -->
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE -->
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>
<!-- DataTable -->
<script src="<?= base_url('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<script src="<?= base_url('assets/adminlte/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '<?= session()->getFlashdata('success') ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
  <?php endif; ?>

  <?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?= session()->getFlashdata('error') ?>',
            timer: 3000,
        });
    </script>
  <?php endif; ?>
<?= $this->renderSection('scripts') ?>

<script>
  $('form').validate({
      errorElement: 'div',
      errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
          $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
          $(element).removeClass('is-invalid');
      }
  });
</script>
</body>
</html>
