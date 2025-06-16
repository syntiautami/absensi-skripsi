<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('teacher/') ?>" class="brand-link">
    <img src="<?= base_url('assets/img/logo.png') ?>" 
         alt="Logo" 
         class="brand-image img-circle elevation-3"
         style="opacity: .8">
    <span class="brand-text font-weight-light">Sistem Absensi</span>
  </a>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
        
        <!-- Absensi with Submenu -->
        <li class="nav-item has-treeview <?= in_array($viewing ?? '', ['attendance', 'attendance-subject']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($viewing ?? '', ['attendance', 'attendance-subject']) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>
              Absensi
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('teacher/attendance/') ?>" class="nav-link <?= ($viewing ?? '') === 'attendance' ? 'active' : '' ?>">
                <p>Absensi Harian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('teacher/attendance/subject/') ?>" class="nav-link <?= ($viewing ?? '') === 'attendance-subject' ? 'active' : '' ?>">
                <p>Absensi Subject</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Laporan -->
        <li class="nav-item has-treeview <?= in_array($viewing ?? '', ['report', 'report-subject']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($viewing ?? '', ['report', 'report-subject']) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>
              Laporan
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('teacher/attendance/report/') ?>" class="nav-link <?= ($viewing ?? '') === 'report' ? 'active' : '' ?>">
                <p>Absensi Harian</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('teacher/attendance/report/subject/') ?>" class="nav-link <?= ($viewing ?? '') === 'report-subject' ? 'active' : '' ?>">
                <p>Absensi Subject</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</aside>
