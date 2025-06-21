<?php
    $base_class_url = base_url('admin/classes/academic-year/' . $academic_year['id'] . '/semester/' . $semester['id'] . '/class/' . $class_semester['id'] . '/');
    $current = current_url();
    $tabs = [
        ''             => 'Detail',
        'students/'    => 'Siswa',
        'class-hour/'  => 'Jam Masuk',
        'timetable/'   => 'Jadwal Pelajaran',
    ];
?>
<div class="card-header p-0">
    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
        <?php
        foreach ($tabs as $path => $label) {
            $url = $base_class_url . $path;
            $active = ($current == $url || strpos($path, $viewing_sub) !== false) ? 'active' : '';
            echo "<li class='nav-item'>
                    <a class='nav-link $active' href='$url'>$label</a>
                  </li>";
        }
        ?>
    </ul>
</div>