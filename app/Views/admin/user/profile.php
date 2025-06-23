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
            <a href="<?= base_url('admin/users/') ?>">Pengguna</a>
        </li>
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/users/'.$role['id'].'/') ?>"><?= $role['alt_name'] ?></a>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= esc("{$user['first_name']} {$user['last_name']}") ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <?= $this->include('admin/user/components/tabs') ?>
            <form action="" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="section-header">DATA DIRI</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="gender">Jenis Kelamin</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" <?= (isset($profile['gender']) && $profile['gender'] == 'male') ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="gender_male">Laki-laki</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" <?= (isset($profile['gender']) && $profile['gender'] == 'female') ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="gender_female">Perempuan</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="address" class="col-form-label">Alamat</label>
                                        <input type="text" class="form-control" id="address" name="address" value="<?= esc($profile['address']) ?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="religion" class="col-form-label">Agama</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="<?= esc($profile['religion']) ?>">
                                    </div>
                                </div>
                            </div>
                            <?php if ($role['name'] == 'student') : ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="section-header">DATA ORANG TUA</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="father_name" class="col-form-label">Nama Ayah</label>
                                            <input type="text" class="form-control" id="father_name" name="father_name" value="<?= esc($profile['father_name']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="mother_name" class="col-form-label">Nama Ibu</label>
                                            <input type="text" class="form-control" id="mother_name" name="mother_name" value="<?= esc($profile['mother_name']) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="parent_email" class="col-form-label">Email Orang Tua</label>
                                            <input type="text" class="form-control" id="parent_email" name="parent_email" value="<?= esc($profile['parent_email']) ?>">
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="section-header">FOTO PROFIL</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo">Upload Foto</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*">
                                            <label class="custom-file-label" for="photo">Pilih file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $(document).on('change', '.custom-file-input', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent()
                .find('.custom-file-label')
                .html(inputFile.files[0].name);
        });
        $('form').validate({
            rules :{
                gender: {
                    required: true,
                }
            }
        })
    });
</script>
<?= $this->endSection() ?>