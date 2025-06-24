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
        <li class="breadcrumb-item active" aria-current="page">Buat Akun</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="section-header">INFORMASI AKUN</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="first_name" class="col-form-label">Nama Depan</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="last_name" class="col-form-label">Nama Belakang</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
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
                                        <label for="username" class="col-form-label">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="section-header">KATA SANDI</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="password" class="col-form-label">Kata Sandi</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="col-form-label">Konfirmasi Kata Sandi</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
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
        $('form').validate({
            rules: {
                username: {
                    remote: {
                        url: '<?= base_url('admin/users/check/username/') ?>',
                        type: "post",
                        data:{
                            username: function() {
                                return $("#username").val();
                            },
                            user_id: '<?= $user['id'] ?? 0 ?>'
                        },
                        dataType: 'json',
                        dataFilter: (response)=>{
                            if (response == 'false') {
                                return false
                            }
                            return true
                        },
                        delay: 5000,
                    }
                },
                email : {
                    email: true,
                    remote: {
                        url: '<?= base_url('admin/users/check/email/') ?>',
                        type: "post",
                        data:{
                            email: function() {
                                return $("#email").val();
                            },
                            user_id: '<?= $user['id'] ?? 0 ?>'
                        },
                        dataType: 'json',
                        dataFilter: (response)=>{
                            if (response == 'false') {
                                return false
                            }
                            return true
                        },
                        delay: 5000,
                    }
                },
                email : {
                    email: true
                },
                confirm_password: {
                    equalTo: '#password'
                },
                gender : {
                    required : true
                }
            },
            messages : {
                username: {
                    remote : "Nama pengguna sudah digunakan"
                },
                email: {
                    remote : "Email sudah digunakan"
                }
            }
        })
    });
</script>
<?= $this->endSection() ?>