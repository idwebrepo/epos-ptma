  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Masukkan Barcode</label>                    
    <div class="col-sm-8">
      <input type="search" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>                              
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Nama Item</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm border-0" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
  </div>                                
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Nama Alias</label>                    
    <div class="col-sm-8">
      <textarea class="form-control form-control-sm border-0 mb-2" placeholder="" id="alias" name="alias" autocomplete="off" data-trigger="manual" data-placement="auto" disabled rows="4" style="resize:none;height: 60px;"></textarea>
    </div>
  </div>                                  
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Harga Jual 1</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm border-0 numeric" placeholder="" id="harga1" name="harga1" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Harga Jual 2</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm border-0 numeric" placeholder="" id="harga2" name="harga2" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Harga Jual 3</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm border-0 numeric" placeholder="" id="harga3" name="harga3" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Harga Jual 4</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm border-0 numeric" placeholder="" id="harga4" name="harga4" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Stok Tersedia</label>                    
    <div class="col-sm-6">
      <input type="text" class="form-control form-control-sm border-0 numeric" placeholder="" id="stok" name="stok" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">
    </div>
    <div class="col-sm-2">
      <input type="text" class="form-control form-control-sm border-0" placeholder="" id="satuan" name="satuan" autocomplete="off" data-trigger="manual" data-placement="auto" disabled="">      
    </div>    
  </div>      
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Gambar Item</label>                    
    <div id="targetgambar" class="col-sm-8">
      <img id="itemImage" src="<?php echo app_url('assets/dist/img/def-img.png'); ?>" width="180" height="150" style="border:1px solid #eee;">          
    </div>
  </div>  
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a id="btutup" class="btn btn-primary text-sm mx-2 px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Tutup</a>
          </div>
      </div>                
  </div>
<script src="<?php echo app_url('assets/dist/js/modul/master/form-info-item.js'); ?>"></script> 