<?php
    $hariList = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $bulanList = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];


    $hariInggris = $selected_date->format('l');
    $hari = $hariList[$hariInggris];
    $tanggal = $selected_date->format('d');
    $bulan = $bulanList[$selected_date->format('m')];
    $tahun = $selected_date->format('Y');
?>
<header class="navbar navbar-expand navbar-white navbar-light">
    <div class="d-flex align-items-center p-2 w-100">

        <!-- Kiri: Logo + Judul -->
        <div class="d-flex align-items-center mr-auto">
            <div class="mr-3">
                <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo Sekolah" style="width: 50px; height: 50px; object-fit: cover;">
            </div>
            <div>
                <div class="font-weight-bold">SMA IT ALIA TANGERANG</div>
                <div class="text-muted"><?= $hari ?>, <?= $tanggal ?> <?= $bulan ?> <?= $tahun ?></div>
            </div>
        </div>

        <!-- Kanan: Legend Bulat -->
        <div class="d-none d-md-flex align-items-center">
            <span class="badge badge-success rounded-circle mr-2" style="width: 20px; height: 20px;">&nbsp;</span> Hadir
            <span class="ml-3 badge badge-danger rounded-circle mr-2" style="width: 20px; height: 20px;">&nbsp;</span> Alpha
            <span class="ml-3 badge badge-warning rounded-circle mr-2" style="width: 20px; height: 20px;">&nbsp;</span> Terlambat
            <span class="ml-3 badge badge-primary rounded-circle mr-2" style="width: 20px; height: 20px;">&nbsp;</span> Izin
            <span class="ml-3 badge badge-info rounded-circle mr-2" style="width: 20px; height: 20px;">&nbsp;</span> Sakit
        </div>

    </div>
</header>
