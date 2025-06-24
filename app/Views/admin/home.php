<?= $this->extend('layouts/base') ?>
<?= $this->section('header') ?>
    <?= $this->include('components/header') ?>
<?= $this->endSection() ?>

<?= $this->section('breadcrumb') ?>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('admin/') ?>">Sistem Absensi</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Beranda</li>
    </ol>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <section class="content">
      <div class="row">
        <div class="col">
          <div class="card">
              <div class="card-header text-center">
                  <h3>Status Kehadiran Siswa Hari Ini</h3>
              </div>
              <div class="card-body">
                  <canvas id="attendancePieChart" style="height: 300px;"></canvas>
              </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
              <div class="card-header">
                  <h3>Status Kehadiran Siswa Hari Ini</h3>
              </div>
              <div class="card-body">
                  <canvas id="attendancePieChart" style="height: 300px;"></canvas>
              </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const ctx = document.getElementById('attendancePieChart').getContext('2d');

const total = 
    <?= $attendance_data['present'] ?> +
    <?= $attendance_data['late'] ?> +
    <?= $attendance_data['sick'] ?> +
    <?= $attendance_data['excused'] ?> +
    <?= $attendance_data['absent'] ?>;

const attendancePieChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            'Tepat Waktu',
            'Terlambat',
            'Sakit',
            'Izin',
            'Alpa'
        ],
        datasets: [{
            label: 'Status Kehadiran Siswa',
            data: [
                <?= $attendance_data['present'] ?>,
                <?= $attendance_data['late'] ?>,
                <?= $attendance_data['sick'] ?>,
                <?= $attendance_data['excused'] ?>,
                <?= $attendance_data['absent'] ?>
            ],
            backgroundColor: [
                '#4CAF50', // present
                '#FFA000', // late
                '#9C27B0', // sick
                '#1976D2', // excused
                '#D32F2F'  // absent
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    font: {
                        size: 14
                    },
                    padding: 20
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed;
                        const percentage = total ? (value / total * 100).toFixed(1) + '%' : '0%';
                        return `${label}: ${value} (${percentage})`;
                    }
                }
            },
            datalabels: {
                color: '#1a1c1e',
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function(value) {
                    if (value === 0) return ''; // <--- yang ini biar 0% gak muncul
                    let percentage = total ? (value / total * 100).toFixed(1) + '%' : '0%';
                    console.log(percentage)
                    if (percentage.endsWith('.0%')) {
                        percentage = percentage.replace('.0%', '%');
                    }
                    return percentage;
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});

</script>
<?= $this->endSection() ?>