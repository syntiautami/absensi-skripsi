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
                <a href="<?= base_url('admin/academic-year/') ?>">Tahun Ajaran</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Buat Tahun Ajaran</li>
        </ol>
    </nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
        <div class="card">
            <form action="<?= base_url('admin/academic-year/create/') ?>" method="post">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="start_date" class="col-form-label">Tanggal Mulai</label>
                            <div class="input-group date" id="start_date_picker" data-target-input="nearest">
                                <input type="text" name="start_date" class="form-control datetimepicker-input"
                                    data-target="#start_date_picker" />
                                <div class="input-group-append" data-target="#start_date_picker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="end_date" class="col-form-label">Tanggal Akhir</label>
                            <div class="input-group date" id="end_date_picker" data-target-input="nearest">
                                <input type="text" name="end_date" class="form-control datetimepicker-input"
                                    data-target="#end_date_picker"/>
                                <div class="input-group-append" data-target="#end_date_picker" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="in_session" name="in_session">
                        <label class="form-check-label" for="in_session">Sedang Berjalan</label>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= base_url('admin/academic-year/') ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
    $(function () {
        $('#start_date_picker').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $('#end_date_picker').datetimepicker({
            format: 'DD-MM-YYYY'
        });

        // Ini buat pastiin begitu klik input langsung keluar
        $('#start_date_picker input').on('focus', function () {
            $('#start_date_picker').datetimepicker('show');
        });
        $('#end_date_picker input').on('focus', function () {
            $('#end_date_picker').datetimepicker('show');
        });
    });
</script>


<?= $this->endSection() ?>