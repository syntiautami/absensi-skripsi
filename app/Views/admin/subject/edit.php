<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/subject/') ?>">Mata Pelajaran</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $subject['name'] ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class=" row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Nama Mata Pelajaran</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?= $subject['name'] ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function(){
        $('form').validate({})
    })
</script>
<?= $this->endSection() ?>