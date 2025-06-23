<?= $this->extend('layouts/auth/base') ?>
<?= $this->section('content') ?>
<div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
  <div class="row w-100 vh-100">
    <div class="col-md-6 d-none d-md-block align-content-center" >
      <div class="d-flex align-items-center justify-content-center bg-light" style="height:80%;">
        <img src="<?= base_url('assets/img/login.png') ?>" alt="Login Illustration" class="img-fluid">
      </div>
    </div>
    <div class="col-md-6 align-content-center">
      <div class="card shadow rounded p-4 justify-content-center align-items-center" style="height:80%;">
        <div class="img-logo text-center">
          <img class="text-center" src="<?= base_url('assets/img/logo.png') ?>" 
          alt="Logo" 
          class="brand-image img-circle"
          style="opacity: .8; width:100px; height:auto;">
        </div>
        <h3 class="text-center mt-3"><b>Masuk</b></h3>
        <p class="mb-3">Selamat Datang Kembali !</p>
        <form class="mt-3" action="" method="post" style="width: 80%;">
          <div class="form-group">
            <label for="">Nama Pengguna</label>
            <input type="text" name="login" class="form-control" placeholder="Masukkan Nama Pengguna" required>
          </div>
          <div class="form-group">
            <label for="">Kata Sandi</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan Kata Sandi" required>
          </div>
          <!-- <div class="form-group">
            <div class="form-check">
              <input type="checkbox" class="form-check-input">
              <label for="" class="form-check-label">Ingat Kata Sandi</label>
            </div>
          </div> -->
          <div class="mt-5">
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
