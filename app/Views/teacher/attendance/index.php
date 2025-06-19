<?= $this->extend('layouts/base') ?>

<?= $this->section('styles') ?>
    <style>
        .card{
            box-shadow: none;
        }
    </style>
<?= $this->endSection() ?>
<?= $this->section('header') ?>
    <?= view('teacher/attendance/components/header', ['role' => 'Teacher']) ?>
<?= $this->endSection() ?>
<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('teacher/') ?>">Home</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Absensi Harian</li>
    </ol>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <section class="content">
        <!-- Card Grid -->
        <div class="card">
            <form action="" method="post">
                <div class="card-body">
                    <div class="row">
                        <?php $no = 1; foreach ($student_class_semesters as $scs): ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <div class="card text-center">
                                    <input type="hidden" name="absence_type[<?= $scs['id'] ?>]">
                                    <div class="card-body p-1 border-1">
                                        <div 
                                            class="user-pic"
                                        >
                                            <img src="<?= base_url('assets/users/default.jpg') ?>" 
                                            alt="Foto Siswa"
                                            data-toggle="modal" 
                                            data-target="#statusModal" 
                                            data-siswa="<?= $scs['id'] ?>" 
                                            class="img-fluid mb-1 rounded-circle border present" 
                                            style="width: 100px; height: 100px; object-fit: cover;cursor: pointer;">
                                        </div>
                                        <div class="user-detail">
                                            <div class="small font-weight-bold"><?= esc("{$scs['first_name']} {$scs['first_name']}") ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header py-2">
                                    <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -10px;">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p id="siswaName" class="text-center font-weight-bold"></p>
                                    <div class="form-group">
                                        <label for="statusSelect">Status:</label>
                                        <select class="form-control" id="statusSelect">
                                        <option value="present">Hadir</option>
                                        <option value="late">Terlambat</option>
                                        <option value="absent">Absen</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer py-2">
                                    <button type="button" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!-- Save Button -->
                    <div class="row">
                        <div class="col text-right mr-5">
                            <button class="btn btn-success px-4">SIMPAN</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#statusModal').on('show.bs.modal', function (event) {
        var card = $(event.relatedTarget) // Card yang di klik
        var siswaId = card.data('siswa') // Ambil nama / id siswa

        var modal = $(this)
        modal.find('#siswaName').text('Nama Siswa ' + siswaId)
    })
</script>
<?= $this->endSection() ?>