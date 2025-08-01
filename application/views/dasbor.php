<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (isset($error_message) && !empty($error_message)) {
  echo '<script>alert("' . $error_message . '");</script>';
}
?>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?php echo app_url('/assets/dist/css/modul/dasbor.css'); ?>">

  <div class="loader-wrap d-none no-print">
    <div class="spinner"></div>
  </div>
  <div id="main-wrapper" class="wrapper no-print">
    <nav class="main-header navbar navbar-expand navbar-primary navbar-dark border-0 p-0">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button" tabindex="-1">
            <div id="nav-icon3">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link bg-primary" data-toggle="dropdown" tabindex="-1">
            <i class="fas fa-user px-2 pt-1 text-light"></i>
            <b id="user-topnav" class="font-weight-normal"><?= @$_SESSION['nama']; ?></b>
            <i class="fas fa-caret-down px-2"></i>
            <input type="hidden" id="roleuser" value="<?= $_SESSION['user'] ?>" />
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <a id="bProfil" href="javascript:void(0)" class="dropdown-item text-sm"><i class="fas fa-key text-info"></i><span class="ml-1">Ubah Password</span></a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item text-sm pl-4" data-widget="iframe-close" data-type="all-other">&nbsp;&nbsp;Tutup Tab Lainnya</a>
            <a href="#" class="dropdown-item text-sm pl-4" data-widget="iframe-close" data-type="all">&nbsp;&nbsp;Tutup Semua Tab</a>

            <?php if ($_SESSION['kode'] == 0) { ?>
              <div class="dropdown-divider"></div>
              <a id="bBackup" href="javascript:void(0)" class="dropdown-item text-sm"><i class="fas fa-hdd text-info"></i><span class="ml-1">Backup Database</span></a>
              <a id="bRestore" href="javascript:void(0)" class="dropdown-item text-sm"><i class="fas fa-undo text-info"></i><span class="ml-1">Restore Database</span></a>
            <?php } ?>
            <div class="dropdown-divider"></div>
            <a href="#" onClick="keluar()" class="dropdown-item text-sm"><i class="fas fa-sign-out-alt text-info"></i><span class="ml-1">Logout</span></a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a id="bNotif" class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell text-light text-md mt-1"></i>
            <span id="totalNotif" class="badge badge-danger navbar-badge ml-4 px-1 text-sm" style="font-family: arial; border-radius:50%"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <a id="spinnerNotif" class="dropdown-item dropdown-header text-primary d-none"><i class="fa fa-spinner fa-spin"></i> Mengambil Data...</a>
            <a id="notifstokexpired" href="#" class="dropdown-item text-sm">
              <i class="fas fa-bell mr-2 text-info"></i><span id="tExpired">0</span> Item Kadaluarsa
              <span class="float-right text-muted text-sm"></span>
            </a>
            <div class="dropdown-divider"></div>
            <a id="notifstokmin" href="#" class="dropdown-item text-sm">
              <i class="fas fa-bell mr-2 text-info"></i><span id="tMinimum">0</span> Item Mencapai Minimum Stok
              <span class="float-right text-muted text-sm"></span>
            </a>
            <div class="dropdown-divider"></div>
            <a id="notifar" href="#" class="dropdown-item text-sm">
              <i class="fas fa-bell mr-2 text-info"></i><span id="tAR">0</span> Piutang Telah Jatuh Tempo
              <span class="float-right text-muted text-sm"></span>
            </a>
            <div class="dropdown-divider"></div>
            <a id="notifap" href="#" class="dropdown-item text-sm">
              <i class="fas fa-bell mr-2 text-info"></i><span id="tAP">0</span> Hutang Telah Jatuh Tempo
              <span class="float-right text-muted text-sm"></span>
            </a>
            <div class="dropdown-divider"></div>
            <a id="notiforderpembelian" href="#" class="dropdown-item text-sm">
              <i class="fas fa-bell mr-2 text-info"></i><span id="tPO">0</span> Order Pembelian Belum Selesai
              <span class="float-right text-muted text-sm"></span>
            </a>
            <div class="dropdown-divider"></div>
            <a id="bReloadNotif" href="javascript:void(0)" class="dropdown-item dropdown-footer text-primary">Reload Notifikasi</a>
          </div>
        </li>
        <li class="nav-item">
          <a id="bfullscreen" class="nav-link bg-primary" data-widget="fullscreen" href="#" role="button" tabindex="-1">
            <i class="fas fa-expand-arrows-alt pt-1"></i>
          </a>
        </li>
      </ul>
    </nav>
    <div class="content-wrapper iframe-mode bg-white" data-widget="iframe" data-loading-screen="false">
      <div id="iframe-navtab" class="nav navbar navbar-expand navbar-primary navbar-dark border-none p-0">
        <ul class="navbar-nav navbar-tab overflow-hidden text-sm" role="tablist">
          <li class="nav-item active tab-index" role="presentation">
            <a class="nav-link active" data-toggle="row" id="tab-index" href="#panel-index" role="tab" aria-controls="panel-index" aria-selected="true" tabindex="-1">Dasbor
            </a>
          </li>
        </ul>
        <a class="nav-link bg-primary px-2 py-2" href="javascript:void(0)" data-widget="iframe-scrollleft" tabindex="-1">
          <i class="fas fa-angle-left text-sm"></i>
        </a>
        <a class="nav-link bg-primary px-2 py-2" href="javascript:void(0)" data-widget="iframe-scrollright" tabindex="-1">
          <i class="fas fa-angle-right text-sm"></i>
        </a>
        <!--
          <a id="full-iframe" class="nav-link bg-primary px-3 py-2" href="#" data-widget="iframe-fullscreen" tabindex="-1">
            <i class="fas fa-expand text-sm"></i>
          </a>
    -->
      </div>
      <div class="tab-content">
        <div class="tab-pane fade active show panel-index" id="panel-index" role="tabpanel" aria-labelledby="tab-index">
          <iframe src="<?= base_url('Page_Starter'); ?>"></iframe>
        </div>
        <div class="tab-empty">
        </div>
        <div class="tab-loading">
        </div>
      </div>
    </div>
    <footer id="footer" class="main-footer bg-white text-sm text-gray border-0 py-1 my-0">
      <strong>&copy; <a class="text-gray" href="#" tabindex="-1"><?= $copy; ?></a>.</strong>
      <div class="float-right d-sm-inline-block">
        Versi <?= $versi; ?>
      </div>
    </footer>
  </div>
  <input type="hidden" class="noclear" id="decqty" name="decqty" value="<?= $decimalqty ?>">
  <div class="modal fade print" id="modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div id="modalsize" class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content py-0">
        <div class="modal-header bg-primary no-print">
          <h5 class="modal-title text-md" id="myModalLabel"></h5>
          <ul id="nav-kkontak" class="navbar-nav d-none">
            <li class="nav-item dropdown d-sm-inline-block">
              <a href="javascript:void(0)" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
                <i class="fas fa-caret-down px-2 text-light"></i>
              </a>
              <div class="list-kategori dropdown-menu dropdown-menu-sm dropdown-menu-left">
              </div>
            </li>
          </ul>
          <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true" tabindex="-1">&times;</button>
          <input type="hidden" id="modaltrigger" name="modaltrigger" value="">
          <input type="hidden" id="coltrigger" name="coltrigger" value="">
        </div>
        <div class="main-modal-body">
        </div>
      </div>
    </div>
  </div>
  <div class="usable print"></div>