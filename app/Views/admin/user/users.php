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
        <li class="breadcrumb-item active" aria-current="page"><?= $role['alt_name'] ?></li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <div class="card-body">
                <table id="usersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Nama</th>
                            <th class="text-center" style="width: 300px;">Nama Pengguna</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($user_roles as $item): ?>
                        <tr>
                            <td>
                                <div class="user-pic">
                                    <img src="<?= base_url($item['profile_photo'] ?? 'assets/img/users/default.jpg') ?>" alt="<?= esc("{$item['first_name']} {$item['last_name']}") ?>">
                                    <span><?= esc("{$item['first_name']} {$item['last_name']}") ?></span>
                                </div>
                            </td>
                            <td><?= esc($item['username']) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/users/'.$role['id'].'/edit/'.$item['user_id'].'/user/') ?>" class="btn btn-sm btn-primary">Ubah</a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/users/'.$role['id'].'/create/') ?>" class="btn btn-primary">
                        Buat Akun
                    </a>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#usersTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "info" : false,
            "order" : [['0', 'asc']]
        });
    });
</script>
<?= $this->endSection() ?>