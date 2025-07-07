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
        <a href="<?= base_url('admin/users/' . $role['id'] . '/') ?>"><?= $role['alt_name'] ?></a>
    </li>
    <li class="breadcrumb-item active" aria-current="page"><?= esc("{$user['first_name']} {$user['last_name']}") ?></li>
</ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="content">
    <div class="card">
        <?= $this->include('admin/user/components/tabs') ?>
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
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?= esc($user['first_name']) ?>" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name" class="col-form-label">Nama Belakang</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?= esc($user['last_name']) ?>" required>
                                </div>
                            </div>
                        </div>
                        <?php if ($role['name'] != 'student') : ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="username" class="col-form-label">Nama Pengguna</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?= esc($user['username']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>" required>
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
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="confirm_password" class="col-form-label">Konfirmasi Kata Sandi</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="">
                                    </div>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email" class="col-form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= esc($user['email']) ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="section-header">TIPE AKUN</label>
                            </div>
                        </div>
                        <?php $no = 1;
                        foreach ($roles as $_role): ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" name="roles[]" value="<?= $_role['id'] ?>" <?= in_array($_role['id'], $user_roles) ? 'checked' : '' ?>>
                                        <label for="roles[]" class="form-check-label"><?= $_role['alt_name'] ?></label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/users/' . $role['id'] . '/') ?>" class="btn btn-secondary mr-2">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        $('form').validate({
            rules: {
                username: {
                    remote: {
                        url: '<?= base_url('admin/users/check/username/') ?>',
                        type: "post",
                        data: {
                            username: function() {
                                return $("#username").val();
                            },
                            user_id: '<?= $user['id'] ?? 0 ?>'
                        },
                        dataType: 'json',
                        dataFilter: (response) => {
                            if (response == 'false') {
                                return false
                            }
                            return true
                        },
                        delay: 5000,
                    }
                },
                email: {
                    email: true,
                    remote: {
                        url: '<?= base_url('admin/users/check/email/') ?>',
                        type: "post",
                        data: {
                            email: function() {
                                return $("#email").val();
                            },
                            user_id: '<?= $user['id'] ?? 0 ?>'
                        },
                        dataType: 'json',
                        dataFilter: (response) => {
                            if (response == 'false') {
                                return false
                            }
                            return true
                        },
                        delay: 5000,
                    }
                },
                confirm_password: {
                    equalTo: '#password'
                }
            },
            messages: {
                username: {
                    remote: "Nama pengguna sudah digunakan"
                },
                email: {
                    remote: "Email sudah digunakan"
                }
            }
        })
    });
</script>
<?= $this->endSection() ?>