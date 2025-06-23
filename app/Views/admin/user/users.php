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
                            <?php if ($role['name'] != 'student') : ?>
                                <th class="text-center" style="width: 300px;">Nama Pengguna</th>
                            <?php endif ?>
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
                            <?php if ($role['name'] != 'student') : ?>
                            <td><?= esc($item['username']) ?></td>
                            <?php endif ?>
                            <td class="text-center">
                                <a href="<?= base_url('admin/users/'.$role['id'].'/edit/'.$item['user_id'].'/user/') ?>" class="btn btn-sm btn-primary">Ubah</a>
                                <a class="btn btn-sm btn-danger btn-delete-user"
                                data-url="<?= base_url('admin/users/'.$role['id'].'/delete/'.$item['user_id'].'/') ?>">
                                    Hapus
                                </a>
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
            paging: true,
            searching: true
        });

        $('.btn-delete-user').click(function(){
            const url = $(this).data('url');

            Swal.fire({
                title: 'Anda yakin ingin menghapus data ini ?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        })
    });
</script>
<?= $this->endSection() ?>