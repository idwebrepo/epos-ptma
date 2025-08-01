/* ========================================================================================== */
/* File Name : order-pembelian.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Inputmask_Numeric_Flexible } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Item } from '../../component.js';

$(function() {

  const qparam = new URLSearchParams(this.location.search);  

  setupHiddenInputChangeListener($('#id')[0]);
  
  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak');
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin');  

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

  if($('#nilaipph22').val() != '') {
    $('#col-pph22').removeClass('d-none');
    $('#col-clear').removeClass('col-sm-8');    
    $('#col-clear').addClass('col-sm-6');        
  }

/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#kontak').keydown(function(e){
    if(e.keyCode==13) { $('#carikontak').click(); }
  });

  $('#person').keydown(function(e){
    if(e.keyCode==13) { $('#cariperson').click(); }
  });  

  $('#bagbeli').keydown(function(e){
    if(e.keyCode==13) { $('#caribagbeli').click(); }
  });

  $(this).on('select2:open', () => {
    this.querySelector('.select2-search__field').focus();
  });  

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/opbData";      
  });

  $("#bViewJurnal").click(function() {
      if($("#id").val()=="") return;

      $.ajax({ 
        "url"    : base_url+"Modal/lihat_jurnal", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");            
          parent.window.$(".modal-title").html("Jurnal "+$("#nomor").val());
          parent.window.$("#modaltrigger").val("iframe-page-opb");
          parent.window.$('#coltrigger').val('');                
        },        
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');      
          console.log('error menampilkan modal jurnal...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._transaksidatatable($("#nomor").val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
  }); 

  $("#carikontak").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");          
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-opb");
          parent.window.$('#coltrigger').val('vendor');   
        },
        "error": function(){
          console.log('error menampilkan modal cari kontak...');
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        },
        "success": function(result) {                
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak('6'); 

          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);

          return;
        } 
      });
    }    
  });

  $("#cariperson").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak_attention", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");                    
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak Person");
          parent.window.$("#modaltrigger").val("iframe-page-opb");
          parent.window.$('#coltrigger').val('vendor');  
        },        
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");          
          console.log('error menampilkan modal cari kontak attention...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);  
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');                                                       
          parent.window._persondatatable($('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });  

  $("#caribagbeli").click(function() {
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");                              
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-opb");
          parent.window.$('#coltrigger').val('bagbeli');                
        },
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");          
          console.log('error menampilkan modal cari kontak...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');                
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak('4'); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);  
          return;
        } 
      });    
    }      
  });

  $("#badd").click(function() {
    _clearForm();
    _addRow();
    _inputFormat();
    _inputFormatLast();          
    _formState1();
  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;    
    _formState1();
  });

  $("#bdelete").click(function() {
    if($('#id').val()=='') return;
    const nomor = $("#nomor").val();
    parent.window.Swal.fire({
      title: 'Anda yakin akan menghapus '+nomor+'?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: `Iya`,
    }).then((result) => {
      if (result.isConfirmed) {
        _deleteData();      
      }
    })
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-opb/${$("#id").val()}`)    
  });

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");                              
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-opb");        
      },       
      "error": function(){
        console.log('error menampilkan modal cari transaksi...');
        parent.window.$(".loader-wrap").addClass("d-none");                              
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);      
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window._transaksidatatable('view_order_pembelian');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);  
        return;
      } 
    });   
  });  

  $("#baddrow").click(function() {
    _addRow();
    _inputFormat();
    _inputFormatLast();
    $("select[name^='item']").last().focus();        
  });

  $("#bcancel").click(function() {
    _clearForm();
    _addRow();
    _inputFormat();
    _inputFormatLast();
    _formState2();
  });

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#pajak').on('change',function(e){
    _hitungTotal();
  });

  $('#chkpph22').on('click',function(e){
    _hitungTotal();
  });

  $('#id').on('change',function(e){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });

  $(this).on("keyup", "input[name^='qty']", async function(){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='harga']", async function(){
      let _idx = $(this).index('.harga');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='diskon']", async function(){
      let _idx = $(this).index('.diskon');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  

  $(this).on("select2:select", "select[name^='satuan']", async function(){
      let _idx = $(this).index('.satuan');
      let idsatuanpilih = $(this).val();
      let idsatuan2 = $("input[name^='sat2']").eq(_idx).val();
      let idsatuan3 = $("input[name^='sat3']").eq(_idx).val();      
      let idsatuan4 = $("input[name^='sat4']").eq(_idx).val();      
      let idsatuan5 = $("input[name^='sat5']").eq(_idx).val();            
      let idsatuan6 = $("input[name^='sat6']").eq(_idx).val();      
      let nilai2 = $("input[name^='nilaisat2']").eq(_idx).val();
      let nilai3 = $("input[name^='nilaisat3']").eq(_idx).val();      
      let nilai4 = $("input[name^='nilaisat4']").eq(_idx).val();            
      let nilai5 = $("input[name^='nilaisat5']").eq(_idx).val();            
      let nilai6 = $("input[name^='nilaisat6']").eq(_idx).val();                        
      let _harga = $("input[name^='defharga']").eq(_idx).val();

      if(idsatuanpilih==idsatuan2) {
        _harga = _harga*nilai2;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan3) {
        _harga = _harga*nilai3;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan4) {
        _harga = _harga*nilai4;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan5) {
        _harga = _harga*nilai5;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan6) {
        _harga = _harga*nilai6;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);                        
      } else {
        _harga = _harga;
        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      }            

      await _hitungJumlahDetil(_idx);
      await _hitungsubtotal();
      _hitungTotal();      
  });

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');
      let _iditem = $(this).val();

      $.ajax({ 
        "url"    : base_url+"PB_Order_Pembelian/get_item", 
        "type"   : "POST", 
        "data"   : "id="+_iditem,
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(){
          console.log('error ambil satuan item...');
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) {
          _getSatuanItem(_iditem, _idx);

          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']);          

          let _qty = Number($("input[name^='qty']").eq(_idx).val().split('.').join('').toString().replace(',','.'));
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          if(result.data[0]['multisatuan']==0) $("select[name^='satuan']").eq(_idx).attr('disabled','disabled');                      
          $("input[name^='harga']").eq(_idx).val(result.data[0]['harga']);    
          $("input[name^='defharga']").eq(_idx).val(result.data[0]['harga']);              
          $("input[name^='sat2']").eq(_idx).val(result.data[0]['idsatuan2']);              
          $("input[name^='sat3']").eq(_idx).val(result.data[0]['idsatuan3']);              
          $("input[name^='sat4']").eq(_idx).val(result.data[0]['idsatuan4']);
          $("input[name^='sat5']").eq(_idx).val(result.data[0]['idsatuan5']);
          $("input[name^='sat6']").eq(_idx).val(result.data[0]['idsatuan6']);                    
          $("input[name^='nilaisat2']").eq(_idx).val(result.data[0]['konversi2']);              
          $("input[name^='nilaisat3']").eq(_idx).val(result.data[0]['konversi3']);              
          $("input[name^='nilaisat4']").eq(_idx).val(result.data[0]['konversi4']);                        
          $("input[name^='nilaisat5']").eq(_idx).val(result.data[0]['konversi5']);                        
          $("input[name^='nilaisat6']").eq(_idx).val(result.data[0]['konversi6']);                                            
          
          if(result.data[0]['harga']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');     

          if(_qty == 0) $("input[name^='qty']").eq(_idx).val(1);

          let jumlah = await _hitungJumlahDetil(_idx);

          //Hitung Total Quantity Dan Sub Total
          let subtotal = await _hitungsubtotal();
          _hitungTotal();
          $("#loader-detil").addClass('d-none');    
          return;                    
      } 
      });
  });

  $(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
  });  

/**/


/* ========================================================================================== */

var _inputFormat = () => {
  Component_Inputmask_Numeric('.numeric');
  Component_Inputmask_Numeric_Flexible('.qty,#tqty', $("#decimalqty").val());  
//  Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,'form_satuan','Satuan');  
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item`,'form_item','Item');  
}

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .nilaipajak, .noclear").val('');
  $(":checkbox").prop("checked", false); 
  $('.select2').val('').change();    
  $('#pajak').val($('#defpajak').val()).change();    
  $('#namakontak').html("");
  $('#namaperson').html("");      
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
  $('.total').val('0');
  $('#tdetil tbody').html('');
}

var _formState1 = () => {
  $('.input-group-append').attr('data-dismiss','modal');
  $('.input-group-append').attr('data-toggle','modal');
  $('.input-group-append').attr('role','button');    
  $('.btn-step2').addClass('disabled');
  $('.btn-step1').removeClass('disabled');
  $('#baddrow').removeAttr('disabled');       
  $(":input").not(":button, :submit, :reset, :radio, .total").removeAttr('disabled');
  setTimeout(function () {
    $('#kontak').focus();        
  },300);
}

var _formState2 = () => {
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled'); 
  $('#baddrow').attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff"); 
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
}

var _addRow = () => {
  var newrow = " <tr>";
      newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"hidden\" name=\"sat2[]\" class=\"sat2\"><input type=\"hidden\" name=\"sat3[]\" class=\"sat3\"><input type=\"hidden\" name=\"sat4[]\" class=\"sat4\"><input type=\"hidden\" name=\"sat5[]\" class=\"sat5\"><input type=\"hidden\" name=\"sat6[]\" class=\"sat6\"><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><input type=\"hidden\" name=\"defharga[]\" class=\"defharga\"><input type=\"hidden\" name=\"nilaisat2[]\" class=\"nilaisat2\"><input type=\"hidden\" name=\"nilaisat3[]\" class=\"nilaisat3\"><input type=\"hidden\" name=\"nilaisat4[]\" class=\"nilaisat4\"><input type=\"hidden\" name=\"nilaisat5[]\" class=\"nilaisat5\"><input type=\"hidden\" name=\"nilaisat6[]\" class=\"nilaisat6\"><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
      newrow += "<td><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}

/**/

/* Fungsi CRUD
/* ***********
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }
    if ($('#idbagbeli').val()==''){
      $('#bagbeli').attr('data-title','Karyawan harus diisi !');      
      $('#bagbeli').tooltip('show');
      $('#bagbeli').focus();
      return 0;
    }
    if ($('#uraian').val()==''){
      $('#uraian').attr('data-title','Uraian harus diisi !');      
      $('#uraian').tooltip('show');
      $('#uraian').focus();
      return 0;
    }

    const totalbaris = $(".item").length;
    for(let i=0;i<totalbaris;i++){
      if($("select[name^='item']").eq(i).val()=='' || $("select[name^='item']").eq(i).val()==null){
        $("select[name^='item']").eq(i).attr('data-title','Item harus diisi !');      
        $("select[name^='item']").eq(i).tooltip('show');      
        $("select[name^='item']").eq(i).focus();
        return 0;
      }
    }
    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PB_Order_Pembelian/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Err: "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        _clearForm();
        _addRow();
        _inputFormat();
        _formState1();
        parent.window.toastr.success("Transaksi berhasil dihapus");                  
        return;
      } else {        
        parent.window.toastr.error(result);      
        return;
      }
    } 
  })  
};

var _saveData = () => {

const id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      refnomor = $("#refnomor").val(),
      kontak = $("#idkontak").val(),
      person = $("#idperson").val(),
      karyawan = $("#idbagbeli").val(),
      termin = $("#termin").val(),
      pajak = $("#pajak").val(),
      alamat = $("#alamat").val(), 
      status = $("#status").val(),            
      uraian = $("#uraian").val();

  var detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
               catatan:$("textarea[name^='catatan']").eq(index).val()
             });

  }); 

  detil = JSON.stringify(detil);  

  var totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
      totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
      totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
      totalpph22 = Number($("#tpajakpph22").val().split('.').join('').toString().replace(',','.')),      
      totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.'));  

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('refnomor',refnomor);  
  rey.set('kontak',kontak);
  rey.set('person',person);
  rey.set('karyawan',karyawan);    
  rey.set('termin',termin);
  rey.set('pajak',pajak);  
  rey.set('alamat',alamat); 
  rey.set('uraian',uraian); 
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('totalpajak',totalpajak);            
  rey.set('totalpph22',totalpph22);              
  rey.set('totaltrans',totaltrans);              
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"PB_Order_Pembelian/savedata", 
    "type"   : "POST", 
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Err: "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      result = JSON.parse(result);
      parent.window.$(".loader-wrap").addClass("d-none");                                            
      if(result.pesan=='sukses'){
          parent.window.Swal.fire({
              title: `Anda ingin mencetak transaksi ini ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((printing) => {
              if (printing.isConfirmed) {
                window.open(`${base_url}Laporan/preview/page-opb/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              _addRow();
              _inputFormat();
              _inputFormatLast();
              _formState1();
              return;
          })
      }                  
    } 
  })
}

var _getDataTransaksi = (id) => {
  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PB_Order_Pembelian/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi order pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
        $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();

        var term = $("<option selected='selected'></option>").val(result.data[0]['idtermin']).text(result.data[0]['termin']);            
        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
        $('#refnomor').val(result.data[0]['noref']);            
        $('#uraian').val(result.data[0]['uraian']);            
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#idperson').val(result.data[0]['idperson']);
        $('#person').val(result.data[0]['person']);
        $('#idbagbeli').val(result.data[0]['idkaryawan']);
        $('#bagbeli').val(result.data[0]['namakaryawan']);                                    
        $('#termin').append(term).trigger('change');
        $('#pajak').val(result.data[0]['pajak']).change();
        $('#status').val(result.data[0]['status']);        
        $('#alamat').val(result.data[0]['alamat']);   

        var rows = 0,
            _tqty = 0,
            _tsubtotal = 0, 
            _tpajak = 0,
            _tpph22 = 0, 
            _ttrans = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']);                        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("input[name^='defharga']").eq(rows).val(result.data[rows]['hargadef']);          
          $("input[name^='sat2']").eq(rows).val(result.data[rows]['idsatuan2']);              
          $("input[name^='sat3']").eq(rows).val(result.data[rows]['idsatuan3']);              
          $("input[name^='sat4']").eq(rows).val(result.data[rows]['idsatuan4']);
          $("input[name^='sat5']").eq(rows).val(result.data[rows]['idsatuan5']);
          $("input[name^='sat6']").eq(rows).val(result.data[rows]['idsatuan6']);                   
          $("input[name^='nilaisat2']").eq(rows).val(result.data[rows]['konversi2']);              
          $("input[name^='nilaisat3']").eq(rows).val(result.data[rows]['konversi3']);              
          $("input[name^='nilaisat4']").eq(rows).val(result.data[rows]['konversi4']);
          $("input[name^='nilaisat5']").eq(rows).val(result.data[rows]['konversi5']);
          $("input[name^='nilaisat6']").eq(rows).val(result.data[rows]['konversi6']);                                            
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));                                    
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']);                                               

          _tqty += Number(result.data[rows]['qtydetil']);
          _tsubtotal += Number(result.data[rows]['subtotaldetil']);            

          if(result.data[rows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(result.data[rows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');                        

          _getSatuanItem(result.data[rows]['iditem'], rows);

          rows++;

        });

          _tpajak = Number(result.data[0]['tpajak']);
          _tpph22 = Number(result.data[0]['tpph22']);          

          if(result.data[0]['pajak']=='2'){
            _ttrans = _tsubtotal;
          }else{
            _ttrans = _tsubtotal + _tpajak + _tpph22;
          }

          if(_tpph22 > 0) $("#chkpph22").prop('checked', true);

          $('#tqty').val(_tqty.toString().replace('.',','));   
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
          $('#tpajak').val(_tpajak.toString().replace('.',','));               
          $('#tpajakpph22').val(_tpph22.toString().replace('.',','));                         
          $('#ttrans').val(_ttrans.toString().replace('.',',')); 

        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
        }
        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

      }
    } 
  })
}

/**/

  if(qparam.get('id')==null){
      _clearForm();
      _addRow();
      _inputFormat();
      _inputFormatLast();
      _formState1();  
  }else{
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }

});

var _hitungTotal = () => {
    let p = $('#pajak').val();
    let nilaiPpn = Number($('#nilaipajak').val()); 
    let nilaiP = Number($('#nilaipajak').val()/100);
    let nilaiPph = Number($('#nilaipph22').val());      
    let nilaiPph22 = Number($('#nilaipph22').val()/100);          
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tpjk = 0, tpph22 = 0, ttrans = 0;

    if(p==2){
      tpjk = (100/(100+nilaiPpn))*tsubtotal;
      tpjk = tpjk*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);
      if($('#chkpph22').prop('checked') == true) {
        tpph22 = (100/(100+nilaiPph))*tsubtotal;
        tpph22 = tpph22*nilaiPph22;  
        tpph22 = Math.floor((tpph22*100)/100);        
      }   
      ttrans = tsubtotal;
    }else if(p==1){
      tpjk = tsubtotal*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);
      if($('#chkpph22').prop('checked') == true) {      
        tpph22 = tsubtotal*nilaiPph22;      
        tpph22 = Math.floor((tpph22*100)/100);                
      }
      ttrans = tsubtotal+tpjk+tpph22;
    }else{
      tpjk = 0;
      tpph22 = 0;
      ttrans = tsubtotal+tpjk+tpph22;
    }

    tqty = tqty.toString().replace('.',',');
    tsubtotal = tsubtotal.toString().replace('.',',');            
    tpjk = tpjk.toString().replace('.',',');
    tpph22 = tpph22.toString().replace('.',',');    
    ttrans = ttrans.toString().replace('.',',');

    if(tqty==0) {
      if($("#decimalqty").val()==0) tqty='0';
      if($("#decimalqty").val()==1) tqty='0,0';      
      if($("#decimalqty").val()==2) tqty='0,00';      
      if($("#decimalqty").val()==3) tqty='0,000';      
      if($("#decimalqty").val()==4) tqty='0,0000';
      if($("#decimalqty").val()==5) tqty='0,00000';                  
    }
    if(tsubtotal==0) tsubtotal='0,00';
    if(tpjk==0) tpjk='0,00';
    if(tpph22==0) tpph22='0,00';    
    if(ttrans==0) ttrans='0,00';

    $('#tqty').val(tqty).attr('placeholder', tqty);
    $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
    $('#tpajak').val(tpjk).attr('placeholder',tpjk);
    $('#tpajakpph22').val(tpph22).attr('placeholder',tpph22);    
    $('#ttrans').val(ttrans).attr('placeholder',ttrans);      

}

var _hitungJumlahDetil = (idx) => {
  let vqty = Number($("input[name^='qty']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vharga = Number($("input[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vdiskon = Number($("input[name^='diskon']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vsubtotal = 0;
 
  vsubtotal = (vharga-vdiskon)*vqty;
  vsubtotal = vsubtotal.toString().replace('.',',');  

  if(vsubtotal==0) vsubtotal='0,00';
  $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
  
  return vsubtotal;
}

var _hitungsubtotal = () => {
  let tqty = 0, tsubtotal = 0;
  
  $('.item').each(function(index,element) {
    tqty += Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tsubtotal += Number($("input[name^='subtotal']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  tqty = tqty.toString().replace('.',',');
  tsubtotal = tsubtotal.toString().replace('.',',');            

  if(tqty==0) tqty='0,00';
  if(tsubtotal==0) tsubtotal='0,00';

  $('#tqty').val(tqty).attr('placeholder',tqty);
  $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
  return;
}

window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}

var _getSatuanItem = (id, idx) => {
  //alert(idx);
  $("select[name^='satuan']").eq(idx).select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "allowAddLink": true,
     "addLink": "form_satuan",  
     "linkTitle": "Satuan",  
     "linkSize":"modal-md",                                            
     "ajax": {
        "url": base_url+"Select_Master/view_satuan_filter",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": function(params) {
          return {
            search: params.term,
            iditem: id
          }
        },
        "processResults": function (data, page) {
        return {
          results: data
        };
      },
    }
  });
}

var _inputFormatLast = () => {
  //alert(idx);
  $("select[name^='satuan']").last().select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "allowAddLink": true,
     "addLink": "form_satuan",  
     "linkTitle": "Satuan",  
     "linkSize":"modal-md",                                            
     "ajax": {
        "url": base_url+"Select_Master/view_satuan",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": function(params) {
          return {
            search: params.term
          }
        },
        "processResults": function (data, page) {
        return {
          results: data
        };
      },
    }
  });
}