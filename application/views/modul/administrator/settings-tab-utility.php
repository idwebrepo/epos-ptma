<div class="tab-pane text-sm px-2 mx-4" id="tab-utility" role="tabpanel" aria-labelledby="btn-tab-utility">
  <div class="card card-light collapsed-card">
    <div class="card-header" data-card-widget="collapse" role="button">
      <h3 class="card-title text-sm text-gray font-weight-bold">Reset Nomor Transaksi</h3>
      <div class="card-tools py-0 my-0">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
        </button>
      </div>
    </div>
    <div class="card-body bg-light mx-0 px-4">
      <div class="row mt-2">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tipe Transaksi</label>
        <div class="col-sm-3">
          <select id="resettipe" name="resettipe" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm font-weight-normal">Dari Tanggal</label>
        <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" id="resetdari" class="form-control form-control-sm datepicker" autocomplete="off">
                <div id="dTglResetDari" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
              </div>                
        </div>            
      </div>                                                                                                                              
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm font-weight-normal">Sampai Tanggal</label>
        <div class="col-sm-2">
              <div class="input-group date">
                <input type="text" id="resetsampai" class="form-control form-control-sm datepicker" autocomplete="off">
                <div id="dTglResetSampai" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                </div>
              </div>                
        </div>            
      </div>
      <div class="row mt-2">      
        <div class="col-sm-12">
          <button type="button" id="bresetnomor" class="btn btn-primary btn-sm"><span>Mulai Reset</span></button>
        </div>      
      </div>
      <div style='clear:both'></div>
    </div>      
  </div>
  <div class="card card-light collapsed-card">
    <div class="card-header" data-card-widget="collapse" role="button">
      <h3 class="card-title text-sm text-gray font-weight-bold">Pengaturan Fitur</h3>
      <div class="card-tools py-0 my-0">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
        </button>
      </div>
    </div>
    <div class="card-body bg-light mx-0 px-4">
      <div class="row mt-2">
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Multi Divisi / Department</label>
        <div class="col-sm-3">
          <select id="multidivisi" name="multidivisi" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>     
        </div>
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Desimal (Uang)</label>
        <div class="col-sm-3">
          <input type="number" class="form-control form-control-sm" id="idecimal" name="idecimal">
        </div>        
      </div>                                  
      <div class="row mt-0">
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Multi Proyek</label>
        <div class="col-sm-3">
          <select id="multiproyek" name="multiproyek" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>     
        </div>
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Desimal (Qty)</label>
        <div class="col-sm-3">
          <input type="number" class="form-control form-control-sm" id="idecimalqty" name="idecimalqty">
        </div>        
      </div>                                        
      <div class="row mt-0">
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Multi Satuan</label>
        <div class="col-sm-3">
          <select id="multisatuan" name="multisatuan" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>     
        </div>
      </div>                                              
      <div class="row mt-0">
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Multi Currency</label>
        <div class="col-sm-3">
          <select id="multikurs" name="multikurs" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>     
        </div>
      </div>                                                    
      <div class="row mt-0">
        <label class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Multi Gudang</label>
        <div class="col-sm-3">
          <select id="multigudang" name="multigudang" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>     
        </div>
      </div>                                                          
      <div style='clear:both'></div>
    </div>      
  </div>
  <div class="card card-light collapsed-card">
    <div class="card-header" data-card-widget="collapse" role="button">
      <h3 class="card-title text-sm text-gray font-weight-bold">Pengaturan Transaksi Pembelian</h3>
      <div class="card-tools py-0 my-0">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
        </button>
      </div>
    </div>
    <div class="card-body bg-light mx-0 px-4">
      <div class="row mt-2">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Default Pajak</label>
        <div class="col-sm-3">
          <select id="ipajakbeli" name="ipajakbeli" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tanpa Pajak</option>
            <option value="1">Belum Termasuk Pajak</option>
            <option value="2">Termasuk Pajak</option>
          </select>     
        </div>
      </div>                                  
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">PPN</label>
        <div class="col-sm-3">
          <select id="ippnbeli" name="ippnbeli" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>                                        
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">PPH 22</label>
        <div class="col-sm-3">
          <select id="ipph22beli" name="ipph22beli" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>                                              
      <div style='clear:both'></div>
    </div>      
  </div>
  <div class="card card-light collapsed-card">
    <div class="card-header" data-card-widget="collapse" role="button">
      <h3 class="card-title text-sm text-gray font-weight-bold">Pengaturan Transaksi Penjualan</h3>
      <div class="card-tools py-0 my-0">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
        </button>
      </div>
    </div>
    <div class="card-body bg-light mx-0 px-4">
      <div class="row mt-2">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Default Pajak</label>
        <div class="col-sm-3">
          <select id="ipajakjual" name="ipajakjual" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tanpa Pajak</option>
            <option value="1">Belum Termasuk Pajak</option>
            <option value="2">Termasuk Pajak</option>
          </select>     
        </div>
      </div>                                  
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">PPN</label>
        <div class="col-sm-3">
          <select id="ippnjual" name="ippnjual" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>                                        
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">PPH 22</label>
        <div class="col-sm-3">
          <select id="ipph22jual" name="ipph22jual" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>                                              
      <div style='clear:both'></div>
    </div>      
  </div>    
  <div class="card card-light collapsed-card">
    <div class="card-header" data-card-widget="collapse" role="button">
      <h3 class="card-title text-sm text-gray font-weight-bold">Pengaturan POS (Penjualan Tunai)</h3>
      <div class="card-tools py-0 my-0">
        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
        </button>
      </div>
    </div>
    <div class="card-body bg-light mx-0 px-4">
      <div class="row mt-2">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Pilihan cetak</label>
        <div class="col-sm-3">
          <select class="form-control form-control-sm select2" id="icetakpos" name="icetakpos" data-trigger="manual" data-placement="auto">
            <option value="0">Direct Print</option>
            <option value="1">Print Preview</option>                  
          </select>
        </div>
      </div>                              
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Default Pelanggan <a id="bclearkontak" class="btn btn-sm btn-light text-sm text-primary mx-0 px-0 py-0" style="float:right"><i class="fas fa-trash"></i></a></label>
        <div class="col-sm-3">
              <div class="input-group">
                <input type="hidden" id="idkontak" name="idkontak">                    
                <input type="text" class="form-control form-control-sm" id="kontak" name="kontak" autocomplete="off" data-trigger="manual" data-placement="auto" readonly="">
                <div id="carikontak" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                </div>
              </div>                
        </div>
        <div id="namakontak" class="col-sm-4 col-form-label-sm"></div>
      </div>                
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Barcode</label>
        <div class="col-sm-1">
          <select class="form-control form-control-sm select2" id="ibarcodepos" name="ibarcodepos" data-trigger="manual" data-placement="auto">
            <option value="0">Tidak</option>
            <option value="1">Ya</option>                 
          </select>
        </div>
      </div>                               
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Default Pajak</label>
        <div class="col-sm-3">
          <select id="ipajakpos" name="ipajakpos" class="form-control form-control-sm select2" style="width:100%">
            <option value="0">Tanpa Pajak</option>
            <option value="1">Belum Termasuk Pajak</option>
            <option value="2">Termasuk Pajak</option>
          </select>     
        </div>
      </div>
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">PPN</label>
        <div class="col-sm-3">
          <select id="ippnpos" name="ippnpos" class="form-control form-control-sm select2" style="width:100%">
          </select>     
        </div>
      </div>                              
      <div class="row mt-0">
        <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Karyawan (POS Service)</label>
        <div class="col-sm-1">
          <select class="form-control form-control-sm select2" id="ikaryawanpos" name="ikaryawanpos" data-trigger="manual" data-placement="auto">
            <option value="0">Tidak</option>
            <option value="1">Ya</option>                 
          </select>
        </div>
        <div class="col-sm-2">
          <select class="form-control form-control-sm select2" id="ikaryawankatpos" name="ikaryawankatpos" data-trigger="manual" data-placement="auto">
          </select>
        </div>        
      </div>                                                   
      <div style='clear:both'></div>
    </div>
  </div>
</div>