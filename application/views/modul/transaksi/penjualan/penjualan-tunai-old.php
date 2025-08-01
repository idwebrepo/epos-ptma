
<body id="<?= $id; ?>" class="layout-fixed bg-light">
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

  <style scoped>
    input[type="text"]:disabled{
      background-color: #fff;
    }
    .btn-cat-con {
        position: fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        right: -236px;
        top: 50%;
        z-index: 6;
        width: 500px;
        height: 40px;
    }    
    .rotate {
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
        -webkit-transform-origin: 50% 50%;
        -moz-transform-origin: 50% 50%;
        -ms-transform-origin: 50% 50%;
        -o-transform-origin: 50% 50%;
        transform-origin: 50% 50%;
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }    
    #lbltotal{
      margin-top: -60px;
    }
    #btnpos{
      position: absolute;
      z-index: 100000;
      bottom: 10px;
    }    
    .container-absolute input,
    .container-absolute input:focus{
      background-color: transparent;
      font-weight: bold;
      font-size: 2.1rem;
      border:0px;
      box-shadow: none;
    } 
    @media (max-width:767px){
      #tpr {
        position: absolute;
        top:0;
        right: 0;
      }
      #lbltotal{
        display: none;
      }
      #footpos {
        display: none;
      }
      #btnpos{
        position: relative;
        bottom: 0;
      }          
    }       
  </style>

  <div class="content-wrapper tab-wrap mx-0 bg-light">
    <div class="content-header bg-secondary px-4 py-2 position-fixed w-100" style="height:40px;">
      <h5 class="text-bold text-light" style="position: absolute;top:7px;left:20px;z-index:1000;"><img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" class="mr-2" alt="Logo" width="25" height="25">
        POS v1</h5>       
      <div class="row">
      <div id="tpr" class="col-sm-12 ml-auto text-right py-0 my-0">
        <span id="curDate" class="mr-1 text-sm"></span>        
        <span id="curTime" class="mr-2 text-sm"></span>                
        <span class="mr-2 text-sm">User : <?= @$_SESSION['nama']; ?>,</span>
        <span class="mr-2 text-sm">Cabang : n/a</span>        
        <div class="form-check" style="display: inline-block;">
          <input type="checkbox" class="form-check-input" id="chkBarcode" tabindex="-1">
          <label class="form-check-label text-sm text-white mr-2" for="chkBarcode" role="button">Barcode [F12]</label>
        </div>
        <a id="blisttunda" class="btn btn-sm bg-secondary mt-0 mr-1" title="Daftar Penundaan"><i class="fas fa-table"></i></a>
        <a id="bcustdisplay" class="btn btn-sm bg-secondary mt-0 mr-1" title="Display Pelanggan"><i class="fas fa-desktop"></i></a>        
        <a id="bfullscreen" class="btn btn-sm bg-secondary mt-0 mr-1" title="Mode Fullscreen"><i class="fas fa-expand"></i></a>                
        <a id="bexit" class="btn btn-sm bg-red mt-0" title="Kembali ke Dasbor"><i class="fas fa-home"></i></a>                         
      </div>
      </div>
    </div>

    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value="">     
    <input type="hidden" id="notrans" name="notrans" value="">                                      
    <input type="hidden" id="status" name="status">                                     
    <input type="hidden" id="icetakpos" name="icetakpos" class="default">
    <input type="hidden" id="ipajakpos" name="ipajakpos" class="default">
    <input type="hidden" id="ikontakpos" name="ikontakpos" class="default">
    <input type="hidden" id="ikontakposkode" name="ikontakposkode" class="default">
    <input type="hidden" id="ikontakposnama" name="ikontakposnama" class="default">            
    <section class="content bg-light" style="margin-top: 35px;">
      <div class="container-fluid mt-4 pt-4">
          <div class="form-group row my-1 ml-2">
            <div class="col-sm-4">
              <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="Nomor Transaksi [AUTO]" autocomplete="off" disabled>
            </div>
          </div> 
          <div class="form-group row my-0 mx-2 d-none">
            <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
            <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off" readonly>
              </div>                
            </div>
          </div>
          <div class="form-group row my-1 ml-2">
            <div class="col-sm-4">
                  <div class="input-group" data-target-input="nearest">
                    <input type="hidden" id="idkontak" name="idkontak">                    
                    <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto" placeholder="Pelanggan [F1]" readonly>
                    <div id="carikontak" class="input-group-append" role="button" style="height: 30px;">
                        <div class="input-group-text text-sm">F1</div>
                    </div>
                  </div>                
            </div>
            <div id="namakontak" class="col-sm-5 text-sm overflow-hidden text-nowrap pt-1 d-none"></div>                        
          </div>
      </div>
      <div id="lbltotal" class="container-absolute pr-3">
        <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-8">
            <h4><input id="ttrans" type="text" class="total form-control numeric text-right" tabindex="-1" value="0" readonly></h4>
          </div>
        </div>
      </div>                                                                         
    </section>

    <section class="content pt-4">
      <div class="container-fluid">
        <div class="row px-2 bg-light"> 
          <div class="card card-primary card-outline card-outline-tabs mt-2 bg-light" style="box-shadow: none">
            <div class="card-header card-header-sm p-0 border-bottom-0">
              <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item no-border mx-1 d-none">
                  <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1" title="Data Transaksi"><i class="fas fa-list text-gray text-sm"></i></a>
                </li>
                <li class="nav-item no-border col-sm-12">
                  <div id="dbarcode" class="row d-none px-2 pt-0">
                    <div class="col-sm-12">
                        <input id="kodeitem" type="search" class="form-control form-control-sm" placeholder="Pindai Kode Item / Barcode [F3]" autocomplete="off">
                    </div>
                  </div>                  
                </li>
              </ul>
            </div>
            <div class="card-body card-outline-tabs-body py-0">
              <div class="tab-content">
                <div class="row py-0 my-0">
                <div class="table-responsive mt-0 bg-white" tabindex="-1" style="border:1px solid #ddd;height:calc(100vh - 310px);max-height:calc(100vh - 310px);overflow: auto;">
                      <table id="tdetil" class="table table-hover table-sm table-transaksi">
                        <thead class="bg-secondary" style="position: sticky; top:0;z-index: 99999999; opacity: 1;">
                          <tr>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 260px;">Nama</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 60px">Qty</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 90px">Satuan</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 170px">Harga</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 170px">Diskon</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 60px">Disc(%)</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 170px">Jumlah</th>
                            <th class="text-sm text-label text-center text-white border-0" style="width: 110px">Gudang</th>
                            <th class="text-sm text-label text-center border-0" style="width: 50px"></th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="9" class="pt-2 my-0">
                              <span id="loader-detil" class="text-sm d-none"><i class="fas fa-spinner fa-spin ml-2"></i></span>
                            </td>
                          </tr>
                        </tfoot>
                      </table>
                </div>
                </div>
              </div>
            </div>
            </div>
          </div>         
          <div class="row mt-0 px-2 d-none">
            <div class="col-sm-8"></div>            
            <div class="col-sm-2 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Transaksi</label>
                <input id="ttrans" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
          </div>
      </div>   
    </section>     
</div>

<div id="footpos" class="container-fluid py-0 my-0" style="position: absolute;bottom:10px;right:70px;z-index:1000;">
          <div class="row my-0 mt-2 px-2">
            <div class="col-sm-6"></div>
            <div class="col-sm-3 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Pajak *</label>
                  <select id="pajak" name="pajak" class="form-control form-control-sm select2" style="width:100%">
                    <option value="0">Tanpa Pajak</option>
                    <option value="1">Belum Termasuk Pajak</option>
                    <option value="2">Termasuk Pajak</option>
                  </select>     
              </div>
            </div>
            <div class="col-sm-2 d-none">
              <div class="form-group">
                <label class="text-sm px-2 font-weight-normal">Total Qty</label>
                <input id="tqty" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-1 mx-0 px-0 bg-white" style="height: 50px;border-top:2px solid #ccc">
              <label class="text-sm font-weight-normal pt-4 px-2">Total</label>            
            </div>
            <div class="col-sm-2 mx-0 px-0">
              <div class="form-group bg-white pt-3 pb-0 my-0" style="height: 50px;border-top:2px solid #ccc">
                <input id="tsubtotal" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" disabled>
              </div>
            </div>
            <div class="col-sm-1 mx-0 px-0 bg-white" style="height: 50px;border-top:2px solid #ccc">
              <label class="text-sm font-weight-normal pt-4 px-2">Pajak</label>            
            </div>
            <div class="col-sm-2 mx-0 px-0">
              <div class="form-group bg-white pt-3 pb-0 my-0" style="height: 50px;border-top:2px solid #ccc">
                <input type="hidden" id="nilaipajak" value="10" class="nilaipajak">                                
                <input id="tpajak" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" disabled>
              </div>
            </div>
          </div>
          <div class="row my-0 py-0 px-2">
            <div class="col-sm-6"></div>                                                                                                          
            <div class="col-sm-1 mx-0 px-0 bg-white" style="height: 50px">
              <label class="text-sm font-weight-normal pt-2 px-2">Dibayar</label>            
            </div>
            <div class="col-sm-2 mx-0 px-0">
              <div class="form-group bg-white py-0 my-0" style="height: 50px">
                <input type="hidden" id="bayarcash" value="0">
                <input type="hidden" id="bayardebit" value="0"> 
                <input type="hidden" id="bayardebitbank">
                <input type="hidden" id="bayardebitbankn">                
                <input type="hidden" id="bayardebitno">                                                                               
                <input type="hidden" id="bayarcredit" value="0">                                
                <input type="hidden" id="bayardiskon" value="0">                                                
                <input type="hidden" id="bayarcreditbank">
                <input type="hidden" id="bayarcreditbankn">                
                <input type="hidden" id="bayarcreditno"> 
                <input type="hidden" id="tbayartrigger" value="0">                                                                        
                <div class="input-group" data-target-input="nearest">                
                  <input id="tbayar" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" data-trigger="manual" data-placement="auto" disabled>
                  <div class="input-group-append d-none" role="button" style="border-left:1px solid #ddd;z-index:1000">
                      <div class="input-group-text border-0"><i class="fa fa-ellipsis-h"></i></div>
                  </div>                                  
                </div>
              </div>
            </div>            
            <div class="col-sm-1 mx-0 px-0 bg-white" style="height: 50px;">
              <label class="text-sm font-weight-normal pt-2 px-2" style="overflow: hidden;white-space: nowrap;">(Sisa)/Kembalian</label>            
            </div>
            <div class="col-sm-2 mx-0 px-0">
              <div class="form-group bg-white py-0 my-0" style="height: 50px">
                <input id="tsisa" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" data-trigger="manual" data-placement="auto" disabled>
              </div>
            </div>
          </div>
</div>

      <div id="btnpos" class="container-fluid ml-4">
        <div class="row">
          <div class="col-sm-4">
            <div class="row">
              <div class="col-sm-12 px-0 py-1 mx-0"><a id="bpembayaran" class="btn bg-secondary text-sm w-100" style="border-radius:0px;">Pembayaran [F8]</a></div>
            </div>
            <div class="row">
              <div class="col-sm-4 px-0 mx-0"><a id="btunda" class="btn bg-secondary text-sm w-100 text-white" style="border-radius:0px;">Tunda [F9]</a></div>
              <div class="col-sm-4 px-1 mx-0"><a id="bsave" class="btn bg-secondary text-sm w-100" style="border-radius:0px;">Simpan [F10]</a></div>
              <div class="col-sm-4 px-0 mx-0"><a id="bcancel" class="btn bg-red text-sm w-100" style="border-radius:0px;">Batal [F11]</a></div>
            </div>            
          </div>
        </div>
      </div>                                                                                   

<!-- Control Sidebar -->
<div class="rotate btn-cat-con">
    <button id="bmodegrid" type="button" id="open-image-item" class="btn bg-black open-imageitem text-white">Tampilkan Gambar Produk</button>
    <button id="bmodegrid" type="button" id="open-image-item" class="btn bg-success open-imageitem text-white" disabled="">Kategori</button>    
</div>
<div class="bg-primary btn-group-vertical btn-top d-none">
</div>
<div class="btn-group-vertical bg-light d-none" style="border:none;"> 
</div>    
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
<script type="module" src="<?php echo app_url('assets/dist/js/modul/transaksi/penjualan/penjualan-tunai.js'); ?>"></script>
</body>
</html>