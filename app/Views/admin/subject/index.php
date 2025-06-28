<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Mata Pelajaran</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <div class="card-body">
                <?php
                    $homeroom = session()->get('homeroom_teacher');
                ?>
                <table id="subjectTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Mata Pelajaran</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject) : ?>
                            <tr>
                                <td class="text-center">
                                    <?= $subject['name'] ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= base_url('admin/subject/'.$subject['id'].'/edit/') ?>" class="btn btn-sm btn-primary">Ubah</a>
                                    <a data-url="<?= base_url('admin/subject/'.$subject['id'].'/delete/') ?>" class="btn btn-sm btn-danger btn-delete-subject">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    <a href="<?= base_url('admin/subject/create/') ?>" class="btn btn-primary">
                        Buat Mata Pelajaran
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function () {
        $('#subjectTable').DataTable({
            "responsive": true,
            "lengthChange" : false,
            "paging" : false,
            "info" : false,
            "fixedHeader" : true,
        });

        $('#subjectTable').delegate('tbody td .btn-delete-subject', 'click', function(){
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