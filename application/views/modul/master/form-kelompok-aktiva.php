  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>                              
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Nama</label>                    
    <div class="col-sm-8">
      <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Umur</label>                    
    <div class="col-sm-3">
      <div class="input-group">    
        <input type="text" class="form-control form-control-sm numeric" id="umur" name="umur" value="0" autocomplete="off" data-trigger="manual" data-placement="auto">
        <div class="input-group-append">
            <div class="input-group-text bg-white border-0">tahun</div>
        </div>
      </div>          
    </div>
  </div>
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Akun Aktiva</label>                    
    <div class="col-sm-8">
      <select id="coaaktiva" name="coaaktiva" class="form-control select2 w-100">
      </select>                         
    </div>
  </div>
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Akun Akumulasi Penyusutan</label>                    
    <div class="col-sm-8">
      <select id="coapenyusutan" name="coapenyusutan" class="form-control select2 w-100">
      </select>                         
    </div>
  </div>                                                         
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Akun Biaya Penyusutan</label>
    <div class="col-sm-8">
      <select id="coabypenyusutan" name="coabypenyusutan" class="form-control select2 w-100">
      </select>                         
    </div>
  </div>                                                                     
  <div class="row">
    <label for="" class="col-sm-4 col-form-label text-sm text-brown font-weight-normal">Akun Write Off</label>                    
    <div class="col-sm-8">
      <select id="coawo" name="coawo" class="form-control select2 w-100">
      </select>                         
    </div>
  </div>                                                                                 

  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
              <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
          </div>
      </div>                
  </div>

<script src="<?php echo app_url('assets/dist/js/modul/master/form-kelompok-aktiva.js'); ?>"></script> 