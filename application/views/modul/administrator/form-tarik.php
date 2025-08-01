<form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
    <div class="row">
        <input type="hidden" class="form-control form-control-sm" placeholder="" id="idsaldo" name="idsaldo" autocomplete="off" data-trigger="manual" data-placement="auto" readonly>
        <input type="hidden" class="form-control form-control-sm" placeholder="" id="idunit" name="idunit" autocomplete="off" data-trigger="manual" data-placement="auto" readonly>
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode Toko</label>
      <div class="col-sm-9"> 
        <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto" readonly>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Toko</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto" readonly>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Saldo Saat Ini</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="sisaSaldo" name="sisaSaldo" autocomplete="off" data-trigger="manual" data-placement="auto" readonly>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nominal Penarikan</label>
      <div class="col-sm-9">
        <input type="number" class="form-control form-control-sm" placeholder="" id="nominal" name="nominal" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>
    <!-- make select option -->
    <!-- <div class="row mt-2 px-1">
      <div class="card card-primary card-outline card-outline-tabs w-100" style="box-shadow: none">
        <div class="card-header card-header-sm p-0">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true">Rekap Transaksi Penarikan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm" id="btn-tab-report" data-toggle="pill" href="#tab-report" role="tab" aria-controls="tab-report" aria-selected="false">Laporan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm" id="btn-tab-widget" data-toggle="pill" href="#tab-widget" role="tab" aria-controls="tab-widget" aria-selected="false">Widget Dasbor</a>
            </li>
          </ul>
        </div>
        <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 my-1">
          <div class="tab-content">

            <div class="tab-pane fade active show text-sm" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
              <div class="row mx-0 px-0">
                <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                  <table id="tmenu" class="table table-hover table-sm table-transaksi py-0 my-0">
                    <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                      <tr>
                        <th class="text-sm text-label text-left border-0" style="width: 10px"></th>
                        <th class="text-sm text-label text-left border-0" style="width: 150px"></th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Buka</th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Tambah</th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Edit</th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Hapus</th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Cetak</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <div class="tab-pane fade text-sm" id="tab-report" role="tabpanel" aria-labelledby="btn-tab-report">
              <div class="row mx-0 px-0">
                <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                  <table id="treport" class="table table-hover table-sm table-transaksi w-100">
                    <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                      <tr>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 10px"></th>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 220px"></th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Buka</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <div class="tab-pane fade text-sm" id="tab-widget" role="tabpanel" aria-labelledby="btn-tab-widget">
              <div class="row mx-0 px-0">
                <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                  <table id="twidget" class="table table-hover table-sm table-transaksi w-100">
                    <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                      <tr>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 10px"></th>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 220px"></th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Akses</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

            <div class="tab-pane fade text-sm" id="tab-widget" role="tabpanel" aria-labelledby="btn-tab-widget">
              <div class="row mx-0 px-0">
                <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                  <table id="twidget" class="table table-hover table-sm table-transaksi w-100">
                    <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                      <tr>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 10px"></th>
                        <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 220px"></th>
                        <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Akses</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div> -->
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

<script src="<?php echo app_url('assets/dist/js/modul/administrator/form-tarik.js'); ?>"></script>