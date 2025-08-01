<form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Protocol</label>                    
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" placeholder="" id="protocol" name="protocol" autocomplete="off" data-trigger="manual" data-placement="auto" value="<?= $protocol ?>">
    </div>
  </div>                              
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Host</label>                    
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" placeholder="" id="host" name="host" autocomplete="off" data-trigger="manual" data-placement="auto" value="<?= $host ?>">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Port</label>                    
    <div class="col-sm-2">
      <input type="text" class="form-control form-control-sm numeric" placeholder="" id="port" name="port" autocomplete="off" value="<?= $port ?>">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Email Address</label>                    
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" placeholder="" id="sender" name="sender" autocomplete="off" value="<?= $sender ?>">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Password</label>                    
    <div class="col-sm-9">
      <input type="password" class="form-control form-control-sm" placeholder="" id="passmail" name="passmail" autocomplete="off" value="<?= $password ?>">
    </div>
  </div>    
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
              <button type="button" id="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
          </div>
      </div>                
  </div>
</form> 
<script src="<?php echo app_url('assets/dist/js/modul/administrator/form-config-email.js'); ?>"></script> 