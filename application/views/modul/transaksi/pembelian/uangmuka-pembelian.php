<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper tab-wrap mx-0">
    <!-- Content Header (Page header) -->
    <div class="content-header bg-white px-4 py-2 position-fixed w-100">
      <div class="row pl-2">
      <span class="text-md text-olive">Pembelian</span>                
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
            <i class="fas fa-caret-down px-2 text-olive text-lg"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
            <a id="bTable" href="#" class="dropdown-item text-sm"><i class="fas fa-folder-open text-gray"></i>
            <span class="ml-1">Data <?= $page_caption;?></span></a>
            <a id="bViewJurnal" href="#" class="dropdown-item text-sm"><i class="fas fa-search text-gray"></i>
            <span class="ml-1">Lihat Jurnal</span></a>                                    
          </div>        
        </li>
      </ul>         
      </div>
      <div class="row">
      <div class="col-sm-11">
      <h5><?= $page_caption;?></h5> 
      </div>
      <div id="btnsideright">
      </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content" style="margin-top: 70px">
      <form id="form-<?= $id; ?>" class="form-horizontal">
      <input type="hidden" id="id" name="id" value="">
      <input type="hidden" id="notrans" name="notrans" value="">      
      <input type="hidden" id="status" name="status">      
      <div class="container-fluid container-absolute-fina">
          <!-- Isi -->
          <div class="row mt-2">
            <div class="col-sm-6">
            </div>            
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Tanggal *</label>
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append" role="button">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                  </div>                
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">No. Transaksi</label>
                <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="[Auto]" autocomplete="off">
              </div>
            </div>
          </div>
      </div>
    </section>   

    <section class="content">
      <div id="dLeftFirst" class="container-fluid left px-2">
        <div class="row px-2">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Vendor *</label>
          <div class="col-sm-2">
                <div class="input-group">
                  <input type="hidden" id="idkontak" name="idkontak">                    
                  <input type="text" class="form-control form-control-sm" id="kontak" name="kontak" autocomplete="off" data-trigger="manual" data-placement="auto">
                  <div id="carikontak" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                  </div>
                </div>                
          </div>
          <div id="namakontak" class="col-sm-4 col-form-label-sm"></div>
        </div>  
        <div class="row px-2">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Uang Muka [D] *</label>
          <div class="col-sm-3">
              <select id="coadebit" name="coadebit" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
          <div class="col-sm-3"></div>
          <div id="stslunas" class="form-check ml-4">
            <input type="checkbox" class="form-check-input" id="chklunas" tabindex="-1" checked>
            <label class="form-check-label text-sm" for="chklunas" role="button">Lunas</label>
          </div>         
        </div>        
        <div id="hutang" class="row px-2 d-none">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Hutang [K] *</label>
          <div class="col-sm-3">
              <select id="coahutang" name="coahutang" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
        </div>
        <div id="kasbank" class="row px-2">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">COA Kas/Bank [K] *</label>
          <div class="col-sm-3">
              <select id="coakredit" name="coakredit" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
        </div>
        <div id="termin" class="row px-2 d-none">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Termin *</label>
          <div class="col-sm-3">
              <select id="term" name="term" class="form-control form-control-sm select2" data-trigger="manual" data-placement="top" style="width: 100%">
              </select>     
          </div>
        </div>        
        <div class="row px-2">
          <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Uraian *</label>
          <div class="col-sm-6">
            <textarea class="form-control form-control-sm" id="uraian" name="uraian" autocomplete="off" data-trigger="manual" data-placement="auto" style="height:4em"></textarea>
          </div>
        </div>
        <div class="row px-2 pt-2">
          <label class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Jumlah</label>
          <div class="col-sm-2">
            <input id="jumlah" type="text" class="form-control form-control-sm numeric">
          </div>
        </div>          
        <div class="row px-2">
          <!-- <label class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Uang</label> -->
          <div class="col-sm-1">
            <input type="hidden" id="iduangkredit" name="iduang">
            <input id="uang" type="hidden" class="form-control form-control-sm" readonly="" tabindex="-1" value="Rp">
          </div>
        </div>  
        <div class="row px-2">
          <label class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Kurs</label>
          <div class="col-sm-1">
            <input id="kurs" type="text" class="form-control form-control-sm numeric" readonly="" tabindex="-1" value="1,00">
          </div>                                                        
        </div>                                                                                                               
      </div>
    </section>
    <section class="content">
    </section> 
</div>

<!-- Control Sidebar -->
<div class="bg-white btn-group-vertical btn-top">
</div>
<div class="btn-group-vertical">
    <a id="badd" class="btn btn-app btn-step2 disabled">
      <span class="badge bg-success"></span>
      <i class="fas fa-plus"></i> <span>Tambah</span>
    </a>
    <a id="bedit" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-edit"></i> <span>Edit</span>
    </a>
    <a id="bdelete" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-teal"></span>
      <i class="fas fa-trash"></i> <span>Hapus</span>
    </a>    
    <a id="bsearch" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-success"></span>
      <i class="fas fa-search"></i> <span>Cari</span>
    </a>
    <a id="bprint" class="btn btn-app btn-step2 disabled" >
      <span class="badge bg-purple"></span>
      <i class="fas fa-print"></i> <span>Cetak</span>
    </a>        
    <a id="bsave" class="btn btn-app btn-step1">
      <span class="badge bg-success"></span>
      <i class="fas fa-save"></i> <span>Simpan</span>
    </a>
    <a id="bcancel" class="btn btn-app btn-step1">
      <span class="badge bg-purple"></span>
      <i class="fas fa-times"></i> <span>Batal</span>
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
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/input_hidden.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<?php echo app_url('assets/dist/js/modul/transaksi/pembelian/uangmuka-pembelian.js'); ?>"></script>
</body>
</html>