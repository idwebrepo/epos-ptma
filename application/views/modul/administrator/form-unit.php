<form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode Unit/Toko</label>
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm" placeholder="" id="kodeunit" name="kodeunit" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
      <div class="col-sm-4">
        <div class="form-check mt-1">
          <input type="checkbox" class="form-check-input" id="aktif" checked>
          <label class="form-check-label text-sm" for="aktif" role="button">Aktif</label>
        </div>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Toko</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="namaunit" name="namaunit" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>
    <!-- <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Pemilik</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="namapemilik" name="namapemilik" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div> -->
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">No Telepon</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="teleponunit" name="teleponunit" autocomplete="off" data-trigger="manual" data-placement="auto">
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
</form>

<script src="<?php echo app_url('assets/dist/js/modul/administrator/form-unit.js'); ?>"></script>