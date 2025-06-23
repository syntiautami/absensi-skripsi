<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <?php foreach ($attendance_data as $key => $value): ?>
                <div class="col dashboard-<?= $key ?>">
                    <div class="small-box <?= $key ?>">
                        <div class="inner text-center">
                            <h3><?= $value ?></h3>
                            <p class="text-uppercase"><?= $key ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
            </div>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
</script>
<?= $this->endSection() ?>