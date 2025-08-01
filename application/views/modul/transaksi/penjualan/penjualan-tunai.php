<body id="<?= $id; ?>" class="layout-fixed bg-light overflow-hidden">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css'); ?>">

  <style scoped>
    input[type="text"]:disabled {
      background-color: #fff;
    }

    #hpos1 {
      width: 460px;
    }

    .table-responsive {
      border: 1px solid #ddd;
      height: calc(100vh - 280px);
      max-height: calc(100vh - 280px);
      overflow: auto;
    }

    #mainright {
      height: calc(100vh - 90px);
      max-height: calc(100vh - 90px);
      overflow: hidden;
      overflow-y: scroll;
    }

   /* .mainright {
			position: fixed;
			top: 0;
			right: 0;
			width: 60%;
			height: 100%;
			background-color: #f0f0f0;
			z-index: 99999999;
			display: block;
		}

		.d-none {
			display: none;
		} */

    .w-40 {
      width: 40%;
      max-width: 460px;
    }

    .w-60 {
      width: 64%;
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

    #lbltotal {
      margin-top: -60px;
    }

    .container-absolute input,
    .container-absolute input:focus {
      background-color: transparent;
      font-weight: bold;
      font-size: 2.1rem;
      border: 0px;
      box-shadow: none;
    }

    #stsBarcode {
      display: inline-block;
    }

    #category-slider {
      display: none;
      z-index: 1060;
      height: 550px;
      position: absolute;
      top: 44px;
      right: 0;
      width: 540px;
      background: #fff;
      padding: 10px 10px;
    }

    #category-list {
      max-height: 585px;
      overflow: hidden;
      position: absolute;
    }

    @media (max-width:767px) {
      #mainright {
        height: calc(100vh - 50px);
        overflow: hidden;
        overflow-y: scroll;
      }

      #lbltotal {
        display: none;
      }

      #footpos {
        display: none;
      }

      #btnpos {
        position: relative;
        bottom: 0;
      }

      #curDate,
      #curTime,
      #curUser,
      #curBranch,
      #stsBarcode {
        display: none;
      }

      #hpos1 {
        width: 100%;
      }

      .w-40 {
        width: 100%;
      }

      .w-60 {
        width: 100%;
      }

      #category-slider {
        width: 90%;
      }
    }
  </style>

  <div class="content-wrapper tab-wrap bg-light">
    <div class="content-header bg-secondary px-4 py-2 position-fixed w-100" style="height:45px;">
      <h5 class="text-bold text-light" style="position: absolute;left:20px;z-index:1000;">
        <img src="<?php echo app_url('assets/dist/img/logo-utama.png'); ?>" class="mr-2" alt="Logo" width="25" height="25">
        ePos (ePesantren) <?= @$_SESSION['namaunit']; ?>
      </h5>
      <div class="row">
        <div id="tpr" class="col-sm-12 ml-auto text-right">
          <span id="curDate" class="mr-1 text-sm"></span>
          <span id="curTime" class="mr-2 text-sm"></span>
          <span id="curUser" class="mr-2 text-sm">User : <?= @$_SESSION['nama']; ?>,</span>
          <span id="curBranch" class="mr-2 text-sm d-none">Cabang : N/A</span>
          <div id="stsBarcode" class="form-check mr-2">
            <input type="checkbox" class="form-check-input" id="chkBarcode" tabindex="-1">
            <label class="form-check-label text-sm text-white" for="chkBarcode" role="button">Barcode [F12]</label>
          </div>
          <a id="blisttunda" class="btn btn-sm bg-secondary mt-0 mr-1" title="Daftar Penundaan"><i class="fas fa-table"></i><span id="amtpending" class="badge bg-info">0</span></a>
          <a id="biteminfo" class="btn btn-sm bg-secondary mt-0 mr-1" title="Informasi Barang"><i class="fas fa-search"></i></a>
          <a id="bcustdisplay" class="btn btn-sm bg-secondary mt-0 mr-1 d-none" title="Display Pelanggan"><i class="fas fa-desktop"></i></a>
          <a id="bfullscreen" class="btn btn-sm bg-secondary mt-0 mr-1" title="Mode Fullscreen"><i class="fas fa-expand"></i></a>
          <a id="bexit" class="btn btn-sm bg-red mt-0" title="Kembali ke Dasbor"><i class="fas fa-home"></i></a>
        </div>
      </div>
      <!-- <div class="row">
          <div class="col-sm-4 px-1 mx-0">
            <a id="bpembayaran" class="btn btn-secondary btn-lg text-sm w-100" style="border-radius:0px;">Sinkornisasi</a>
          </div>
      </div> -->
    </div>

    <form id="form-<?= $id; ?>" class="form-horizontal">
      <input type="hidden" id="id" name="id" value="">
      <input type="hidden" id="idtunda" name="idtunda" value="">
      <input type="hidden" id="notrans" name="notrans" value="">
      <input type="hidden" id="status" name="status">
      <input type="hidden" id="ikaryawankat" name="ikaryawankat" class="default">
      <input type="hidden" id="icetakpos" name="icetakpos" class="default">
      <input type="hidden" id="ipajakpos" name="ipajakpos" class="default">
      <input type="hidden" id="ikontakpos" name="ikontakpos" class="default">
      <input type="hidden" id="ikontakposkode" name="ikontakposkode" class="default">
      <input type="hidden" id="ikontakposnama" name="ikontakposnama" class="default">
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

      <div id="lbltotal" style="position: fixed; top: 115px; right: 70px;">
        <h4><input id="ttrans" type="text" class="total form-control numeric text-right border-0 bg-light text-bold" tabindex="-1" style="font-size: 28px" value="0" readonly></h4>
      </div>

      <div class="mainleft d-flex flex-row mt-4 pt-4">
        <section id="mainleft" class="content bg-light">
          <div id="hpos1" class="px-4">
            <div class="form-group row my-1 d-none">
              <div class="col-sm-12">
                <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="Nomor Transaksi [AUTO]" autocomplete="off" disabled>
              </div>
            </div>
            <div class="form-group row my-0 d-none">
              <label for="" class="col-sm-2 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
              <div class="col-sm-2">
                <div class="input-group date">
                  <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off" readonly>
                </div>
              </div>
            </div>
            <div class="form-group row my-1">
              <div class="col-sm-6">
                <div class="input-group" data-target-input="nearest">
                  <input type="hidden" id="idkontak" name="idkontak">
                  <input type="text" id="kontak" name="kontak" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto" placeholder="Pelanggan" readonly>
                  <div id="carikontak" class="input-group-append" role="button" style="height: 30px;">
                    <div class="input-group-text text-sm">F1</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="input-group" data-target-input="nearest">
                  <a id="bsinkron" class="btn btn-danger btn-md text-sm w-100" style="border-radius:0px;">SinKronisasi Data</a>
                </div>
                <center>
                  <div id="loading-gif" style="display: none;">
                    <img src="<?php echo base_url() ?>uploads/loading/loading.gif" height="50" width="50" alt="Sedang Memuat...">
                  </div>
                </center>
              </div>

            </div>
            <div id="ikaryawanpos" class="form-group row my-1 d-none">
              <div class="col-sm-12">
                <div class="input-group" data-target-input="nearest">
                  <input type="hidden" id="idkaryawan" name="idkaryawan">
                  <input type="text" id="karyawan" name="karyawan" class="form-control form-control-sm" autocomplete="off" data-trigger="manual" data-placement="auto" placeholder="" readonly>
                  <div id="carikaryawan" class="input-group-append" role="button" style="height: 30px;">
                    <div class="input-group-text text-sm">F2</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group row my-1">
              <div class="col-sm-12">
                <input type="text" id="catatan" name="catatan" class="form-control form-control-sm" placeholder="Catatan" autocomplete="off" disabled>
              </div>
            </div>
            <div id="dbarcode" class="form-group row my-1 d-none">
              <div class="col-sm-12">
                <input id="kodeitem" type="search" class="form-control form-control-sm" placeholder="Pindai Kode Item / Barcode [F3]" autocomplete="off">
              </div>
            </div>
          </div>
          <div class="container-fluid">
            <div class="row px-2 bg-light">
              <div class="card card-primary card-outline card-outline-tabs mt-0 bg-light" style="box-shadow: none">
                <div class="card-header card-header-sm p-0 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item no-border mx-1 d-none">
                      <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true" tabindex="-1" title="Data Transaksi"><i class="fas fa-list text-gray text-sm"></i></a>
                    </li>
                  </ul>
                </div>
                <div class="card-body card-outline-tabs-body py-0">
                  <div class="tab-content">
                    <div class="row py-0 my-0">
                      <div class="table-responsive mt-0 bg-white" tabindex="-1">
                        <table id="tdetil" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-secondary" style="position: sticky; top:0;z-index: 99999998; opacity: 1;">
                            <tr>
                              <th id="th-nama" class="text-sm text-label text-center text-white border-0" style="width: 260px;">Nama</th>
                              <th id="th-qty" class="text-sm text-label text-center text-white border-0" style="width: 60px">Qty</th>
                              <th id="th-satuan" class="text-sm text-label text-center text-white border-0" style="width: 90px">Satuan</th>
                              <th id="th-harga" class="text-sm text-label text-center text-white border-0" style="width: 160px">Harga</th>
                              <th id="th-diskon" class="text-sm text-label text-center text-white border-0" style="width: 160px">Diskon</th>
                              <th id="th-persen" class="text-sm text-label text-center text-white border-0" style="width: 60px">Disc(%)</th>
                              <th id="th-jumlah" class="text-sm text-label text-center text-white border-0" style="width: 160px">Subtotal</th>
                              <th id="th-gudang" class="text-sm text-label text-center text-white border-0" style="width: 110px">Gudang</th>
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
                <div class="card-footer bg-transparent py-0">
                  <div class="row my-0 px-0">
                    <div class="col-sm-3 mx-0 px-0 bg-white" style="border-top:2px solid #ccc">
                      <label class="text-sm font-weight-normal pt-2 px-2">SUBTOTAL</label>
                    </div>
                    <div class="col-sm-3 my-0 mx-0 px-0">
                      <div class="form-group bg-white pb-0 my-0 pt-1" style="border-top:2px solid #ccc">
                        <input id="tsubtotal" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" disabled>
                      </div>
                    </div>
                    <div class="col-sm-3 mx-0 px-0 bg-white" style="border-top:2px solid #ccc">
                      <label class="text-sm font-weight-normal pt-2 px-2">PPN <span id="persen-ppn"><?php if ($ppnjual > 0) echo '(' . $ppnjual . '%)'; ?></label>
                    </div>
                    <div class="col-sm-3 mx-0 px-0">
                      <div class="form-group bg-white pb-0 my-0 pt-1" style="border-top:2px solid #ccc">
                        <input type="hidden" id="nilaipajak" value="<?= $ppnjual; ?>" class="nilaipajak">
                        <input id="tpajak" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" disabled>
                      </div>
                    </div>
                    <div class="d-none">
                      <div class="form-group">
                        <label class="text-sm px-2 font-weight-normal">Pajak *</label>
                        <select id="pajak" name="pajak" class="form-control form-control-sm select2" style="width:100%">
                          <option value="0">Tanpa Pajak</option>
                          <option value="1">Belum Termasuk Pajak</option>
                          <option value="2">Termasuk Pajak</option>
                        </select>
                      </div>
                    </div>
                    <div class="d-none">
                      <div class="form-group">
                        <label class="text-sm px-2 font-weight-normal">Total Qty</label>
                        <input id="tqty" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row my-0 py-0">
                    <div class="col-sm-3 mx-0 px-0 bg-white" style="height:29px">
                      <label class="text-sm font-weight-normal pt-1 px-2" style="overflow:hidden;white-space: nowrap;">TOTAL</label>
                    </div>
                    <div class="col-sm-3 mx-0 px-0">
                      <div class="form-group bg-white py-0 my-0 pt-0">
                        <input id="ttrans2" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white pt-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                      </div>
                    </div>
                    <div class="col-sm-3 mx-0 px-0 bg-white" style="height:29px">
                      <label class="text-sm font-weight-normal pt-1 px-2">DIBAYAR</label>
                    </div>
                    <div class="col-sm-3 mx-0 px-0">
                      <div class="form-group bg-white py-0 my-0 pt-0">
                        <div class="input-group">
                          <input id="tbayar" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white pt-0" value="0" data-trigger="manual" data-placement="auto" disabled>
                        </div>
                      </div>
                    </div>

                    <div class="d-none">
                      <div class="form-group">
                        <label class="text-sm px-2 font-weight-normal">KEMBALI</label>
                        <input id="tsisa" type="text" class="total form-control form-control-sm numeric border-0 font-weight-bold bg-white" value="0" data-trigger="manual" data-placement="auto" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row my-0 py-0 pt-3">
                    <div class="col-sm-4 px-1 mx-0">
                      <a id="bpembayaran" class="btn btn-secondary btn-lg text-sm w-100" style="border-radius:0px;">BAYAR [F8]</a>
                    </div>
                    <div class="col-sm-4 px-1 mx-0">
                      <a id="btunda" class="btn bg-olive btn-lg text-sm w-100" style="border-radius:0px;">TUNDA [F9]</a>
                      <a id="bsave" class="btn bg-success text-sm d-none" style="border-radius:0px;">Simpan [F10]</a>
                    </div>
                    <div class="col-sm-4 px-1 mx-0">
                      <a id="bcancel" class="btn btn-secondary btn-lg text-sm w-100" style="border-radius:0px;">BATAL [F11]</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <div id="rightmain" class="mainright mt-1 w-60 d-none" style="z-index: 99999999">
          <input id="cariitem" type="search" class="form-control form-control-sm" placeholder="Cari Nama Item...[F7]" autocomplete="off" />
          <input type="hidden" id="idkategori" value="">
          <div class="clearfix" style="height:5px;"></div>
          <section id="mainright" class="content bg-white">
            <div id="loader-item" class="text-md d-none" style="position: absolute;"><i class="fas fa-spinner fa-spin ml-2 mt-2"></i></div>
            <div class="row">
              <div class="col-sm-12 py-4 px-4">
                <div id="ajaxproducts">
                  <div id="item-list" class="ml-4">
                  </div>
                </div>
                <div style="clear:both;"></div>
              </div>
            </div>
          </section>
        </div>
      </div>

      <div class="clearfix"></div>
      <div class="d-flex flex-row">
      </div>
    </form>
  </div>


  <!-- Control Sidebar -->
  <div class="rotate btn-cat-con">
    <button id="bmodegrid" type="button" id="open-image-item" class="btn bg-secondary open-imageitem text-white">Tampilkan Gambar Produk</button>
    <button id="bkategori" type="button" class="btn bg-olive open-category text-white" disabled>Kategori</button>
  </div>
  <div class="bg-primary btn-group-vertical btn-top d-none">
  </div>
  <div class="btn-group-vertical bg-light d-none" style="border:none;">
  </div>

  <div id="category-slider" class="bg-secondary">
    <div id="category-list">
    </div>
  </div>
  <!-- /.control-sidebar -->
  <iframe id="hasilPdf" name="hasilPdf" src="" class="d-none"></iframe>
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