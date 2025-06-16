<?= $this->extend('layouts/auth/base') ?>
<?= $this->section('title') ?>
Login
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100">
    <div class="col-md-6 d-none d-md-block">
      <div class="h-100 d-flex align-items-center justify-content-center bg-light">
        <img src="<?= base_url('assets/img/login-illustration.svg') ?>" alt="Login Illustration" class="img-fluid">
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow rounded p-4">
        <h3 class="text-center mb-4"><b>Login</b></h3>
        <form action="<?= base_url('') ?>" method="post">
          <div class="mb-3">
            <input type="text" name="login" class="form-control" placeholder="Username/Email" required>
          </div>
          <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
