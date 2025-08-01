  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
    <div class="modal-body" style="position: relative;border-color: #336666">
    <div class="btn-group-vertical border-0 bg-white" style="position: absolute;height:200px;margin-top:10px;margin-right: 10px;">
      <a id="b50" class="btn btn-sm btn-secondary text-bold my-1" style="border-radius:5px;width:100px;"><span class="text-white">50.000</span></a>
      <a id="b100" class="btn btn-sm btn-secondary text-bold my-1" style="border-radius:5px;width:100px;"><span class="text-white">100.000</span></a>
      <a id="b150" class="btn btn-sm btn-secondary text-bold my-1" style="border-radius:5px;width:100px;"><span class="text-white">150.000</span></a>
      <a id="b200" class="btn btn-sm btn-secondary text-bold my-1" style="border-radius:5px;width:100px;"><span class="text-white">200.000</span></a>
      <a id="b500" class="btn btn-sm btn-secondary text-bold my-1" style="border-radius:5px;width:100px;"><span class="text-white">500.000</span></a>

      <br>
      <?php 
        $kontak = $_POST['idkontak'];
        $cust = $this->db->query("SELECT KSALDO FROM bkontak WHERE KID = '$kontak'")->row_array();
        $cSaldo = 0;
        if($cust && isset($cust['KSALDO']) && !empty($cust['KSALDO'])){
          $cSaldo =$cust['KSALDO'];
        }
        echo 'Saldo Saat Ini : <br> Rp. '. number_format($cSaldo,2,',','.');
      ?>
    </div>        
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">TOTAL TRANSAKSI</label>
      <div class="col-sm-5">
          <input type="tel" id="ttrans" name="ttrans" class="total form-control form-control-sm" readonly>
      </div>
    </div>                                                      
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">DISKON CASHBACK</label>
      <div class="col-sm-5">
          <input type="tel" id="diskon" name="diskon" class="total form-control form-control-sm numeric" value="0" data-trigger="manual" data-placement="auto">
      </div>
    </div>                                              
    <div class="row d-none">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">PEMBULATAN</label>
      <div class="col-sm-5">
          <input type="tel" id="tpembulatan" name="tpembulatan" class="total form-control form-control-sm border-0 numeric" value="0" disabled>
      </div>
    </div>           
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">TOTAL HARUS DIBAYAR</label>
      <div class="col-sm-5">
          <input type="tel" id="ttransnet" name="ttransnet" class="total form-control form-control-sm border-0 numeric" value="0" disabled>
      </div>
    </div>                                                                                                         
    <hr class="row col-sm-9" />
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">UANG TUNAI</label>
      <div class="col-sm-5">
          <div class="input-group">        
            <input type="tel" id="cash" name="cash" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="calcash" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>
          </div>
      </div>
    </div>
    <hr class="row col-sm-9" />
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">NOMINAL</label>
      <div class="col-sm-5">
          <div class="input-group">
            <input type="tel" id="debit" name="debit" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="caldebit" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>            
          </div>
      </div>
    </div>                          
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">METODE</label>
      <div class="col-sm-5">
          <select id="bankdebit" class="form-control form-control-sm select2"></select>
      </div>
    </div>                              
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">NOMOR RFID</label>
      <div class="col-sm-5">
          <input type="number" id="debitno" name="debitno" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>  
    <div class="row d-none">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Credit Card</label>
      <div class="col-sm-7">        
        <div class="input-group">
            <input type="text" id="credit" name="credit" class="form-control form-control-sm numeric" autocomplete="off" value="0" data-trigger="manual" data-placement="auto">
            <div id="calcredit" class="input-group-append" role="button">
                <div class="input-group-text px-1 py-0 bg-white"><i class="fas fa-calculator text-secondary"></i></div>
            </div>  
        </div>
      </div>
    </div>                          
    <div class="row d-none">
      <label class="col-sm-5 col-form-label text-sm font-weight-normal">Bank</label>
      <div class="col-sm-7">
          <select id="bankcredit" class="form-control form-control-sm select2"></select>
      </div>
    </div>                              
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-normal">PIN</label>
      <div class="col-sm-5">
          <input type="password" id="creditno" name="creditno" class="form-control form-control-sm" autocomplete="off">
      </div>
    </div>
    <hr class="row col-sm-9" />
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-bold">TOTAL DIBAYAR</label>
      <div class="col-sm-5">
          <input type="text" id="tbayar" name="tbayar" class="total form-control form-control-sm border-0 numeric" value="0" data-trigger="manual" data-placement="auto" disabled>
      </div>
    </div>                                      
    <div class="row">
      <label class="col-sm-4 col-form-label text-sm font-weight-bold">KEMBALI</label>
      <div class="col-sm-5">
          <input type="text" id="tsisa" name="tsisa" class="total form-control form-control-sm border-0 numeric" value="0" data-trigger="manual" data-placement="auto" disabled>
      </div>
    </div>
    <hr />    
    </div>
    <div class="modal-footer" style="border-color: #336666">
      <div class="row px-0 mx-0">
<!--            <a class="btn btn-danger btn-sm mx-2" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal [Esc]</a> -->
            <button type="button" id="bsimpan" name="bsimpan" class="btn btn-secondary btn-sm px-2 font-weight-normal"><i class="fas fa-save"></i> SIMPAN & CETAK [F12]</button>
      </div>       
    </div>
</form>

<script src="<?php echo app_url('assets/dist/js/modul/transaksi/penjualan/pembayaran-pos.js'); ?>"></script> 