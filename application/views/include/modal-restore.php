  <div class="modal-body">
    <div class="row mx-2">
      <div class="form-group col-sm-12">
        <div class="custom-file">
          <input type="file" accept=".backup" class="custom-file-input" id="customFile">
          <label class="custom-file-label" for="customFile">Pilih File (.backup)</label>
        </div>
        <span class="text-sm text-red">Catatan : Ekstrak terlebih dahulu file backup database (.zip) yang anda download</span>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <div class="form-group">
      <div class="col-sm-offset-3">
        <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
        <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm px-4">Mulai</button>
      </div>
    </div>
  </div>
  <script src="<?= app_url('assets/plugins/bs-custom-file-input.min.js'); ?>"></script>
  <script src="<?= app_url('assets/dist/js/modul/include/modal-restore.js'); ?>"></script>