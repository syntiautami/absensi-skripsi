<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SMA IT Alia</title>

  <!-- Bootstrap & AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
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
                <?php $no = 1; foreach ($daily_entries as $item): ?>
                  <?php
                    $photo = !empty($item['profile_photo']) ? base_url($item['profile_photo']) : base_url('assets/img/users/default.jpg');
                    $timeRaw = !empty($item['clock_out']) ? $item['clock_out'] : $item['clock_in'];
                    $timeFormatted = (new DateTime($timeRaw))->format('H:i:s');
                  ?>
                  <div class="card mb-2 p-2" data-id="<?= $student_data[$item['profile_id']]['id'] ?>">
                      <div class="d-flex align-items-center">
                          <div class="mr-2">
                            <img src="<?= $photo ?>" class="img-fluid rounded-circle" height="50" width="50">
                          </div>
                          <div>
                              <strong class="student-name"><?= esc("{$item['first_name']} {$item['last_name']}") ?></strong><br>
                              <small class="student-class"><?= esc("{$student_data[$item['profile_id']]['grade_name']} {$student_data[$item['profile_id']]['code']}") ?></small><br>
                              <span class="student-time text-success d-flex align-items-center">
                                <span class="status-box <?= $item['status'] ?> me-2" style="width: 10px; height: 10px; display: inline-block; border-radius: 2px;"></span>
                                <?= $timeFormatted ?>
                              </span>
                          </div>
                      </div>
                  </div>
                <?php endforeach ?>
            </div>
        </div>

        <!-- Konten Tengah -->
        <div class="col-md-9 d-flex flex-column">
            <!-- Profile besar -->
            <div class="student-container">
              <div class="card flex-grow-1 p-4 text-center mb-0" style="border-radius: 7px 7px 0 0;">
                <div class="text-center">
                  <img src="<?= base_url('/default.jpg') ?>" class="rounded-circle mb-3" height="420" width="420">
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
                    <h2 class="text-present"><?= $studentStatisticData['present'] ?></h2>
                    <p class="m-0">Tepat Waktu</p>
                </div>
                <div class="card flex-fill mr-2 p-3 bg-warning text-white text-center">
                    <h2 class="text-late"><?= $studentStatisticData['late'] ?></h2>
                    <p class="m-0">Terlambat</p>
                </div>
                <div class="card flex-fill mr-2 p-3 bg-danger text-white text-center">
                    <h2 class="text-absent"><?= $studentStatisticData['absent'] ?></h2>
                    <p class="m-0">Alpa</p>
                </div>
                <div class="card flex-fill p-3 bg-primary text-white text-center">
                    <h2 class="text-total"><?= $studentStatisticData['total'] ?></h2>
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
            <span class="student-time text-success d-flex align-items-center">
              <span class="student-time text-success d-flex align-items-center"></span>
            </span>
        </div>
    </div>
</div>

  <!-- Scripts -->
  <script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    let serverTimestamp = <?= $date ?> * 1000; // PHP timestamp in ms
    function finishTapping(elem){
      elem.prop('disabled', false);
      elem.focus()
      $('#waiting-overlay').hide();
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
      const existingCard = document.querySelector(`.card[data-id="${data.id}"]`);
      if (existingCard) {
        return;
      }
      const $card = $('.card-empty').clone();
      $card.removeClass('card-empty').show();
      $card.attr('data-id',data.id);

      // Isi data
      $card.find('img').attr('src', data.img);
      $card.find('.student-name').text(data.name);
      $card.find('.student-class').text(data.kelas);
      $card.find('.student-time').html(`
        <span class="status-box ${data.status} me-2"></span>${data.time}
      `);

      const $container = $('.students-container');

      // Cek jumlah card
      const thresholdCard = 4
      if ($container.children('.card').length >= thresholdCard) {
          // Hapus yang paling bawah (last)
          $container.children('.card').last().remove();
      }

      // Masukkan card baru di atas
      $container.prepend($card);
    }

    function updateDashboard(data){
      $('.student-container').find('.student-name').text(data['name'])
      $('.student-container').find('.student-class').text(data['kelas'])
      $('.student-container').find('.student-time').text(data['time'])
      $('.student-container').find('.student-time').find('.status-box').addClass(data.status)
    }

    function showNotif(data){
      let type = 'success';
      let title = 'INFO';
      let text = `
        ${data.name} ${data.kelas} <br> <br>
        Anda telah melakukan absensi <br>hari ini pada pukul ${data.time}
      `;

      if (data.status == 'home'){
        text = `
          ${data.name} ${data.kelas} <br> <br>
          Anda telah melakukan absensi pulang hari ini pada pukul ${data.time}
        `
      }else if (data.status == 'absent'){
        type = 'info';
        text = `
          ${data.name} ${data.kelas} <br> <br>
          Anda terhitung alfa hari ini. <br>
          silahkan hubungi guru piket.
        `;
      }else if (data.status == 'late'){
        type = 'warning';
        text = `
          ${data.name} ${data.kelas} <br> <br>
          Anda terlambat!!! <br> 
          Anda telah melakukan absensi <br>hari ini pada pukul ${data.time}
        `;
      }

      Swal.fire({
        icon: type,
        title: title,
        html: text,
        customClass : {
          confirmButton : 'btn-primary'
        }
      });
    }

    function updateStatistik(data){
      let status = data['status'];
      let present = parseInt($('.text-present').text())
      let absent = parseInt($('.text-absent').text())
      let late = parseInt($('.text-late').text())
      let total = parseInt($('.text-total').text())

      if (data.status == 'absent') {
        absent += 1;
        $('.text-absent').text(absent)
        total += 1;
        $('.text-total').text(total)
      }else if (data.status == 'late') {
        late += 1;
        $('.text-late').text(late)
        total += 1;
        $('.text-total').text(total)
      }else if (data.status == 'present') {
        present += 1;
        $('.text-present').text(present)
        total += 1;
        $('.text-total').text(total)
      }
    }
    $(function(){
      $("input[name='barcode-number']").focus()
      updateClock();
      setInterval(updateClock, 1000);
      $("input[name='barcode-number']").on('change', function(e){
        $('#waiting-overlay').show();
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
            console.log(data)

            // change student container Large
            updateDashboard(data)

            // insert to students list
            if (!['re-tap','absent'].includes(status)) {
              addStudentCard(data)
            }

            updateStatistik(data)
            showNotif(data)
          },
          error: function(xhr, status, thrownError ) {
            finishTapping(elemInput)
            console.log()
            if (xhr.status == 404) {
              Swal.fire({
                icon: 'error',
                title: 'INFO',
                html: xhr.responseJSON['message'],
                customClass : {
                  confirmButton : 'btn-primary'
                }
              });
            }
          }
        });
      })
    });
  </script>
</body>
</html>

