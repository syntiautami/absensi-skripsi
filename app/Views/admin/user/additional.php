<?= $this->extend('layouts/base') ?>

<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Home</a>
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
            <form action="" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="section-header">INFORMASI SISWA</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nis" class="col-form-label">Nomor Induk Siswa (NIS)</label>
                                        <input type="text" class="form-control" id="nis" name="nis" value="<?= esc($student['nis']) ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="nisn" class="col-form-label">Nomor Induk Siswa Nasional (NISN)</label>
                                        <input type="text" class="form-control" id="nisn" name="nisn" value="<?= esc($student['nisn']) ?>" required>
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
                        url: '<?= base_url('admin/users/check-username/') ?>',
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
                    email: true
                },
                confirm_password: {
                    equalTo: '#password'
                }
            },
            messages : {
                username: {
                    remote : "Nama pengguna sudah digunakan"
                }
            }
        })
    });
</script>
<?= $this->endSection() ?>