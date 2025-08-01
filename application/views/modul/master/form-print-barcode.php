  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Pilih Item Barang</label>                    
    <div class="col-sm-8">
      <select id="item" name="item" class="form-control select2 w-100" data-trigger="manual" data-placement="auto">
      </select>                         
    </div>
  </div>                              
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Ukuran Kertas Label</label>                    
    <div class="col-sm-8">
      <select id="size" name="size" class="form-control select2 w-100" data-trigger="manual" data-placement="auto">
        <option value="25" selected="">26 mm x 15 mm</option>
        <option value="30" >33 mm x 15 mm</option>        
        <option value="48" >58 mm x 15 mm</option>                
      </select>                         
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Jumlah</label>                    
    <div class="col-sm-4">
      <input type="tel" id="jumlah" name="jumlah" class="form-control form-control-sm numeric" data-trigger="manual" data-placement="auto" autocomplete="off" value="1">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Pilih Layout</label>                    
    <div class="col-sm-8">
      <div class="form-group mt-1">
      <div class="custom-control custom-radio">
      <input class="custom-control-input" type="radio" id="bentuk1" name="bentuk" checked>
      <label for="bentuk1" role="button" class="custom-control-label text-sm font-weight-normal pt-1"><img src="<?php echo app_url('assets/dist/img/barcode-1.png'); ?>" width="160" height="60"></label>
      </div>
      <div class="custom-control custom-radio mt-4">
      <input class="custom-control-input" type="radio" id="bentuk2" name="bentuk">
      <label for="bentuk2" role="button" class="custom-control-label text-sm font-weight-normal pt-1"><img src="<?php echo app_url('assets/dist/img/barcode-2.png'); ?>" width="160" height="60"></label>
      </div>
      </div>
    </div>
  </div>                                    
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a id="btutup" class="btn btn-light text-sm mx-2 px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
              <a id="bPrint" class="btn btn-primary text-sm mx-0 px-4"><i class="fas fa-print"></i> Cetak</a>
          </div>
      </div>                
  </div>

  <script src="<?php echo app_url('assets/dist/js/modul/master/form-print-barcode.js'); ?>"></script> 