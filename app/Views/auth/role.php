<?= $this->extend('layouts/auth/base') ?>
<?= $this->section('content') ?>
<div class="container text-center mt-5">
  <h2>Pilih Role</h2>
  <div class="row justify-content-center mt-4">
    <?php foreach ($roles as $role): ?>
      <div class="col-md-3">
        <a href="<?= base_url('role/' . $role['name']) ?>/" class="btn btn-primary btn-block mb-3">
          <?= ucfirst($role['alt_name']) ?>
        </a>
      </div>
    <?php endforeach ?>
  </div>
</div>
<?= $this->endSection() ?>
