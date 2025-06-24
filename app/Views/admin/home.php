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
                  <canvas id="attendanceDoughnut" style="height: 300px;"></canvas>
              </div>
          </div>
        </div>
        <div class="col">
          <div class="card">
              <div class="card-header text-center">
                  <h3>Distribusi Kehadiran Per Kelas Hari Ini</h3>
              </div>
              <div class="card-body">
                  <canvas id="attendancePerClassChart" style="height: 400px;"></canvas>
              </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const ctx = document.getElementById('attendanceDoughnut').getContext('2d');
  const total = 
      <?= $attendance_data['present'] ?> +
      <?= $attendance_data['late'] ?> +
      <?= $attendance_data['sick'] ?> +
      <?= $attendance_data['excused'] ?> +
      <?= $attendance_data['absent'] ?>;

  const attendanceDoughnut = new Chart(ctx, {
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

  // === PER CLASS BAR CHART ===
    const ctxClass = document.getElementById('attendancePerClassChart').getContext('2d');

    const labelsClass = <?= json_encode($class_list) ?>;

    const attendancePerClass = <?= json_encode($attendance_per_class) ?>;

    const datasetPresent = labelsClass.map(kelas => attendancePerClass[kelas]?.present ?? 0);
    const datasetLate    = labelsClass.map(kelas => attendancePerClass[kelas]?.late ?? 0);
    const datasetSick    = labelsClass.map(kelas => attendancePerClass[kelas]?.sick ?? 0);
    const datasetExcused = labelsClass.map(kelas => attendancePerClass[kelas]?.excused ?? 0);
    const datasetAbsent  = labelsClass.map(kelas => attendancePerClass[kelas]?.absent ?? 0);

    const attendancePerClassChart = new Chart(ctxClass, {
        type: 'bar',
        data: {
            labels: labelsClass,
            datasets: [
                {
                    label: 'Tepat Waktu',
                    data: datasetPresent,
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'Terlambat',
                    data: datasetLate,
                    backgroundColor: '#FFA000'
                },
                {
                    label: 'Sakit',
                    data: datasetSick,
                    backgroundColor: '#9C27B0'
                },
                {
                    label: 'Izin',
                    data: datasetExcused,
                    backgroundColor: '#1976D2'
                },
                {
                    label: 'Alpa',
                    data: datasetAbsent,
                    backgroundColor: '#D32F2F'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    formatter: function(value) {
                        return value > 0 ? value : '';
                    }
                }
            },
            scales: {
                x: {
                    stacked: false
                },
                y: {
                    beginAtZero: true
                }
            }
        },
        plugins: [ChartDataLabels]
    });

</script>
<?= $this->endSection() ?>