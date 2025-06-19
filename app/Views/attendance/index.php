<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMA IT Alia</title>

  <!-- Bootstrap & AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?= $this->renderSection('styles') ?>
</head>
<body class="hold-transition">
  <div class="container-fluid p-5">
    <div class="row">
        <!-- Sidebar Kiri -->
        <div class="col-md-3">
            <!-- Hari, Jam -->
            <div class="mb-3 p-3 bg-light rounded">
                <small id="day-date">Hari, Tanggal</small>
                <h2 id="day" class="text-center">07:00:00</h2>
            </div>

            <!-- Search Box -->
            <div class="mb-3">
                <input type="text" class="form-control" name="barcode-number" placeholder="Cari Nomor ID">
            </div>

            <!-- List Siswa -->
            <div class="students-container" style="max-height: 500px; overflow-y: auto;">
                
            </div>
        </div>

        <!-- Konten Tengah -->
        <div class="col-md-9 d-flex flex-column">
            <!-- Profile besar -->
            <div class="student-container">
              <div class="card flex-grow-1 p-4 text-center mb-0" style="border-radius: 7px 7px 0 0;">
                <div class="text-center">
                  <img src="<?= base_url('assets/users/default.jpg') ?>" class="rounded-circle mb-3" height="420" width="420">
                </div>
                <h3 class="student-name">Nama</h3>
                <h5 class="text-muted student-class">Kelas</h5>
              </div>
              <div class="bg-success text-center text-white p-2" style="border-radius: 0 0 7px 7px;">
                <h2 class="m-0 student-time">00:00</h2>
              </div>
            </div>

            <!-- Statistik kotak bawah -->
            <div class="d-flex justify-content-between mt-4">
                <div class="card flex-fill mr-2 p-3 bg-success text-white text-center">
                    <h2 class="text-present">0</h2>
                    <p class="m-0">Tepat Waktu</p>
                </div>
                <div class="card flex-fill mr-2 p-3 bg-warning text-white text-center">
                    <h2 class="text-late"><?= $late ?></h2>
                    <p class="m-0">Terlambat</p>
                </div>
                <div class="card flex-fill mr-2 p-3 bg-danger text-white text-center">
                    <h2 class="text-absent"><?= $absent ?></h2>
                    <p class="m-0">Alpa</p>
                </div>
                <div class="card flex-fill p-3 bg-primary text-white text-center">
                    <h2 class="text-total">0</h2>
                    <p class="m-0">Total</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-2 p-2 card-empty" style="display: none;">
    <div class="d-flex align-items-center">
        <div class="mr-2">
            <img src="" class="img-fluid rounded-circle" height="50" width="50">
        </div>
        <div>
            <strong class="student-name"></strong><br>
            <small class="student-class"></small><br>
            <span class="student-time text-success"></span>
        </div>
    </div>
</div>

  <!-- Scripts -->
  <script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>
  <script>
    let serverTimestamp = <?= $date ?> * 1000; // PHP timestamp in ms
    function finishTapping(elem){
      elem.prop('disabled', false);
      elem.focus()
    }
    function updateClock() {
        let now = new Date(serverTimestamp);
        let hh = now.getHours().toString().padStart(2, '0');
        let mm = now.getMinutes().toString().padStart(2, '0');
        let ss = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('day').textContent = `${hh}:${mm}:${ss}`;

        // Hari dan Tanggal
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        let dayName = days[now.getDay()];
        let dayNumber = now.getDate();
        let monthName = months[now.getMonth()];
        let year = now.getFullYear();

        document.getElementById('day-date').textContent = `${dayName}, ${dayNumber} ${monthName} ${year}`;
        serverTimestamp += 1000;
    }
    function addStudentCard(data) {
      // Clone template
      const $card = $('.card-empty').clone();
      $card.removeClass('card-empty').show();
      $card.attr('data-id',data.id);

      // Isi data
      $card.find('img').attr('src', data.img);
      $card.find('.student-name').text(data.name);
      $card.find('.student-class').text(data.kelas);
      $card.find('.student-time').text(data.timestamp);

      const $container = $('.students-container');

      // Cek jumlah card
      if ($container.children('.card').length >= 5) {
          // Hapus yang paling bawah (last)
          $container.children('.card').last().remove();
      }

      // Masukkan card baru di atas
      $container.prepend($card);
    }

    function updateDashboard(data){
      $('.student-container').find('.student-name').text(data['name'])
      $('.student-container').find('.student-class').text(data['kelas'])
      $('.student-container').find('.student-time').text(data['timestamp'])
    }

    function showNotif(data){

    }

    function updateStatistik(data){

    }
    $(function(){
      updateClock();
      setInterval(updateClock, 1000);
      $("input[name='barcode-number']").on('change', function(e){
        const barcode = $(this).val();
        $(this).val('')
        const elemInput = $(this);
        elemInput.prop('disabled', true);
        $.ajax({
          url: '<?= base_url("attendance/tapping/"); ?>',
          method: 'POST',
          data: { barcode: barcode },
          success: function(response) {
            finishTapping(elemInput)
            const data = response['data'];
            const status = data['status']

            // change student container Large
            updateDashboard(data)

            // insert to students list
            if (!['re-tap','absent'].includes(status)) {
              addStudentCard(data)
            }

            updateStatistik(data)
            showNotif(data)
          },
          error: function(xhr) {
            finishTapping(elemInput)
            console.log(xhr)
          }
        });
      })
    });
  </script>
</body>
</html>

