
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= $this->renderSection('title') ?></title>

  <!-- Font Awesome (optional) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition sidebar-mini">
    <!-- Navbar -->
    <?= $this->include('layouts/partials/navbar') ?>

    <!-- Sidebar -->
    <?= $this->include('layouts/partials/' . (session('role') ?? 'teacher') . '/sidebar') ?>

    <!-- Main content -->
    <div class="content-wrapper pt-4">
        <div class="container-fluid">
        <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Footer -->
    <?= $this->include('layouts/partials/footer') ?>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- Bootstrap JS Bundle (sudah termasuk Popper) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>

  <!-- Custom Scripts -->
  <?= $this->renderSection('scripts') ?>
</body>
</html>
