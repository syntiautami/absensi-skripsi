<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= view('components/header', ['role' => 'Admin']) ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <nav aria-label="breadcrumb" style="margin-top:1rem;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/') ?>">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/') ?>">Kelas</a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/') ?>"><?= esc($academic_year['name']) ?></a>
            </li>
            <li class="breadcrumb-item">
                <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>">Semester <?= esc($semester['name']) ?></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Buat Kelas</li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label class="col-form-label">Kelas</label>
                                <select name="grade_id" class="form-control">
                                    <?php foreach ($grades as $item): ?>
                                        <option value="<?= $item['id'] ?>"><?= $item['section_name'] ?> <?= $item['name'] ?></option>
                                        <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class=" row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="name" class="col-form-label">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('admin/classes/academic-year/'.$academic_year['id'].'/semester/'.$semester['id'].'/class/') ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>
