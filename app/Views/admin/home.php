<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Admin
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  /* Custom style khusus halaman ini */
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  console.log('Admin dashboard loaded');
</script>
<?= $this->endSection() ?>
