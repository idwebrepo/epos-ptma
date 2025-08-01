<body id="<?php echo $id; ?>" class="layout-fixed overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/table-page.css');?>">

  <!-- Loading Page -->  
  <div class="loader-wrap d-none">
    <div class="loader">
      <div class="box-1 box"></div>
      <div class="box-2 box"></div>
      <div class="box-3 box"></div>
      <div class="box-4 box"></div>
      <div class="box-5 box"></div>
    </div>
  </div>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row">
      <div class="col-sm-11">
      <span class="text-md text-olive">Administrator</span>
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright">
        <a class="nav-link text-lg" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-bars text-gray"></i>
        </a>           
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content px-0 mx-0 ml-2" style="margin-top: 70px;">
      <div class="container-fluid mt-1 px-0 mx-0">      
        <table id="userlog-table" class="table table-sm table-striped table-hover w-100 nowrap d-none">
          <thead>
          <tr>
          <th class="d-none"></th>
          <th></th>
          <th class="text-sm">Nama User</th>
          <th class="text-sm">IP Address</th>
          <th class="text-sm">Tanggal</th>
          <th class="text-sm">Jam</th>
          <th class="text-sm">Aktivitas</th>          
          <th class="text-sm">Level</th>                    
          </tr>
          </thead>
        </table>
      </div>
    </div>    
    <!-- /.Main content -->
  </div>

  <!-- Control Sidebar -->
  <div class="bg-white btn-group-vertical btn-top">
  </div>
  <div class="btn-group-vertical">
      <a id="brefresh" class="btn btn-app">
        <i class="fas fa-sync"></i> <span>Refresh</span>
      </a>            
  </div>    
  <aside id="control-sidebar-r" class="control-sidebar bg-transparent border-0">
  </aside>
  </form>
  <!-- /.control-sidebar -->
 
<!-- JS Vendor -->
<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-select/js/dataTables.select.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables-select/js/select.bootstrap4.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/datatables/colResize.js'); ?>"></script>
<!-- JS Custom -->
<script src="<?php echo app_url('assets/dist/js/modul/administrator/table-userlog.js'); ?>"></script>
</body>
</html>