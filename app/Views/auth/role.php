<?= $this->extend('layouts/auth/base') ?>
<?= $this->section('content') ?>
<div class="container text-center mt-5">
  <div class="row justify-content-center">
    <div class="col-md-12 col-xs-12">
      <img class="text-center" src="<?= base_url('assets/img/logo.png') ?>" 
          alt="Logo" 
          class="brand-image img-circle"
          style="opacity: .8; width:auto; height:150px;">
    </div>
  </div>
  <div class="row justify-content-center mt-5">
    <?php foreach ($roles as $role): ?>
      <div class="col-md-3">
        <a href="<?= base_url('role/' . $role['name']) ?>/" class="btn btn-primary btn-lg btn-block mb-3 justify-content-center align-content-center" style="height: 100px;">
          Pilih Modul <?= ucfirst($role['alt_name']) ?>
        </a>
      </div>
    <?php endforeach ?>
  </div>
</div>
<?= $this->endSection() ?>
