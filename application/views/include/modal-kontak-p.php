<div class="modal-body">
  <input type="hidden" id="idkatkontak" name="idkatkontak">
  <div class="container-fluid px-0">
    <table id="contact-table" class="table table-sm table-striped table-hover table-responsive w-100 bg-light nowrap d-none">
      <thead>
        <tr>
          <th class="d-none"></th>
          <th></th>
          <th class="text-sm">Kode</th>
          <th class="text-sm">Nomor RFID</th>
          <th class="text-sm">Nama</th>
          <th class="text-sm">Kategori</th>
          <th class="text-sm">Alamat</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<div class="modal-footer">
  <div class="col-sm-8">
    <button id="bAddKontak" type="button" class="btn btn-sm btn-light text-primary" style="margin-left: -20px"><i class="fas fa-plus mr-1"></i>Buat Baru</button>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3">
      <a class="btn btn-outline-primary btn-sm px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
      <button type="button" id="bpilihkontak" name="bpilihkontak" class="btn btn-primary btn-sm px-4">Pilih</button>
    </div>
  </div>
</div>

<script src="<?= app_url('assets/dist/js/modul/include/modal-kontak-p.js'); ?>"></script>