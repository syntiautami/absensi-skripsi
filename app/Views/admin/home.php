<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            
            <div class="card-header">
                <h5 class="mb-0 font-weight-bold">KEHADIRAN HARI INI</h5>
                <small class="text-muted" style="font-size: 1em;">Kelas <?= esc(session()->get('homeroom_teacher')['grade_name'])  ?> <?= esc(session()->get('homeroom_teacher')['class_code'])  ?></small>
            </div>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>