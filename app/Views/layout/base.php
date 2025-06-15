
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $this->renderSection('title') ?></title>

  <!-- CSS AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/css/adminlte.min.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?= $this->renderSection('navbar') ?>

  <!-- Sidebar -->
  <?= $this->renderSection('sidebar') ?>

  <!-- Content -->
  <div class="content-wrapper p-3">
    <?= $this->renderSection('content') ?>
  </div>

  <!-- Footer -->
  <?= $this->renderSection('footer') ?>
</div>

<!-- JS AdminLTE -->
<script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('adminlte/js/adminlte.min.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
