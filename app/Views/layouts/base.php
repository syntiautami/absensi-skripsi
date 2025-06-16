<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $this->renderSection('title') ?></title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
  
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
  <?= $this->include('layouts/partials/navbar') ?>
  <?= $this->include('layouts/partials/' . (session('role') ?? 'teacher') . '/sidebar') ?>

  <div class="content-wrapper pt-3">
    <div class="container-fluid">
      <?= $this->renderSection('content') ?>
    </div>
  </div>

  <?= $this->include('layouts/partials/footer') ?>
</div>

<!-- jQuery -->
<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap -->
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE -->
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
