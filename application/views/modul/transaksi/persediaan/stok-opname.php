<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

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
      <div class="row pl-2">
      <span class="text-md text-olive">Inventori</span>                
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
    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value=""> 
    <input type="hidden" id="status" name="status">  
    <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
    <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
    <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
    <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">        
    <input type="hidden" class="noclear" id="decimalqty" name="decimalqty" value="<?= $decimalqty ?>">                                           
    <section class="content" style="margin-top:70px">
    <div class="container-fluid pt-4">
        <div class="form-group row my-0">
          <label class="col-sm-2 col-form-label text-sm px-4 font-weight-normal">Karyawan *</label>
          <div class="col-sm-2">
                <div class="input-group" data-target-input="nearest">
                  <input type="hidden" id="idkontak" name="idkontak">                    
                  <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto">
                  <div id="carikontak" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                  </div>
                </div>                
          </div>
          <div id="namakontak" class="col-sm-4 text-sm overflow-hidden text-nowrap pt-1"></div>                        
        </div>
        <div class="form-group row my-0">
          <label class="col-sm-2 col-form-label text-sm px-4 font-weight-normal">Gudang *</label>
          <div class="col-sm-2">
            <select id="gudang" name="gudang" class="form-control form-control-sm select2" style="width:100%" data-trigger="manual" data-placement="auto">
            </select>
          </div>
        </div>  
      </div>
      <div class="container-fluid container-absolute-80">
          <!-- Isi -->
          <div class="row mt-0">
            <div class="col-sm-3">
            </div>           
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">No. Ref</label>              
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idreferensi" name="idreferensi">
                    <input type="text" id="refnomor" name="refnomor" class="form-control form-control-sm" autocomplete="off">
                  </div>              
              </div>  
            </div>                                                                                                                        
            <div class="col-sm-3">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Tanggal *</label>
                  <div class="input-group date">
                    <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                    <div id="dTgl" class="input-group-append">
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

    <section class="content mt-2">
      <div class="container-fluid">
        <div class="form-group row mt-4">
          <label class="col-sm-2 col-form-label text-sm px-4 font-weight-normal">Uraian *</label>
          <div class="col-sm-10">
              <textarea class="form-control form-control-sm" rows="1" id="uraian" name="uraian" data-trigger="manual" data-placement="auto"></textarea>
          </div>
        </div>                           
          <div class="card card-primary card-outline card-outline-tabs mt-2" style="box-shadow: none">
            <div class="card-header card-header-sm p-0 border-bottom-0">
              <ul class="nav nav-tabs bg-light" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item no-border mx-1">
                  <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1" title="Data Transaksi"><i class="fas fa-list text-gray text-md"></i></a>
                </li>
              </ul>              
            </div>
            <div class="card-body card-outline-tabs-body">
              <div class="tab-content">
                <div class="row">             
                <div class="table-responsive pt-0" tabindex="-1">
                      <table id="tdetil" class="table table-hover table-sm table-transaksi">
                        <thead class="bg-light">
                          <tr>
                            <th class="text-sm text-label text-center border-0" style="width: 250px">Nama</th>
                            <th class="text-sm text-label text-center border-0" style="width: 80px">Qty Stok</th>                            
                            <th class="text-sm text-label text-center border-0" style="width: 80px">Qty</th>
                            <th class="text-sm text-label text-center border-0" style="width: 80px">Selisih</th>                            
                            <th class="text-sm text-label text-center border-0" style="width: 90px">Satuan</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 170px">Harga</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 170px">Diskon</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 60px">Disc(%)</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 170px">Jumlah</th>
                            <th class="text-sm text-label text-center border-0" style="width: 200px">Catatan</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 150px">No. Ref</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 110px">Proyek</th>
                            <th class="text-sm text-label text-center border-0 d-none" style="width: 110px">Gudang</th>
                            <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                        </tfoot>
                      </table>
                    <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2"></i>Tambah Data</button>
                    <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading item data...</span>
                </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row px-1">
            <div class="col-sm-8"></div>                    
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Qty</label>
                <input id="tqty" type="text" class="total form-control form-control-sm border-0" value="0" disabled>
                <input id="tsubtotal" type="text" class="total form-control form-control-sm numeric d-none" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Selisih</label>
                <input id="tselisih" type="text" class="total form-control form-control-sm numeric" value="0" disabled>
              </div>
            </div>            
          </div>
      </div>
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
<script type="module" src="<?php echo app_url('assets/dist/js/modul/transaksi/persediaan/stok-opname.js'); ?>"></script>
</body>
</html>