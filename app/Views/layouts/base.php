<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMA IT Alia</title>
  <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.css') ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">

  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.min.css') ?>">  
  
  <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
  
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

  <div id="waiting-overlay" style="
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      z-index: 9999;
      justify-content: center;
      align-items: center;
      color: #fff;
      font-size: 1.5rem;
  ">
      <div>
          <i class="fas fa-spinner fa-spin fa-3x mb-3"></i><br>
          Mohon tunggu...
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
<script src="<?= base_url('assets/adminlte/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.min.js') ?>"></script>

<script src="<?= base_url('assets/adminlte/plugins/select2/js/select2.full.min.js') ?>"></script>

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
<script>
  $.validator.setDefaults({
      errorElement: 'div',
      errorPlacement: function (error, element) {
          error.addClass('invalid-feedback');
          element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
          if ($(element).is(':radio')) {
              $(element).closest('.form-group').addClass('is-invalid');
          } else {
              $(element).addClass('is-invalid');
          }
      },
      unhighlight: function (element, errorClass, validClass) {
          if ($(element).is(':radio')) {
              $(element).closest('.form-group').removeClass('is-invalid');
          } else {
              $(element).removeClass('is-invalid');
          }
      }
  });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>