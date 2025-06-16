<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Teacher
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  /* Custom style khusus halaman ini */
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  console.log('Teacher dashboard loaded');
</script>
<?= $this->endSection() ?>
