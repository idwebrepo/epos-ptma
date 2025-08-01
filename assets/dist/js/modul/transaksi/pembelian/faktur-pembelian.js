/* ========================================================================================== */
/* File Name : faktur-pembelian.js
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
  setupHiddenInputChangeListener($('#iddp')[0]);  
  setupHiddenInputChangeListener($('#notrans')[0]);  
  setupHiddenInputChangeListener($('#idreferensi')[0]);

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak');
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin');
  Component_Select2('#gudang',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');    

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }    

  $('#nofakturpajak').inputmask({
    mask: "999.999-99.99999999"
  });

  if($('#nilaipph22').val() != '') {
    $('#col-pph22').removeClass('d-none');
    $('#col-clear').removeClass('col-sm-2');    
  }

/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#kontak').keydown(function(e){
    if(e.keyCode==13) { $('#carikontak').click(); }
  });

  $('#refnomor').keydown(function(e){
    if(e.keyCode==13) { $('#caripb').click(); }
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

  $("#dTglFaktur").click(function() {
    if($(this).attr('role')) {
      $("#tglpajak").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/fpbData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-fpb");
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

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-fpb/${$("#id").val()}`)    
  });

  $("#carikontak").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-fpb");
          parent.window.$('#coltrigger').val('vendor');                
        },        
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');      
          console.log('error menampilkan modal cari kontak...');
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
          parent.window.$('.loader-wrap').removeClass('d-none');          
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak Person");
          parent.window.$("#modaltrigger").val("iframe-page-fpb");
          parent.window.$('#coltrigger').val('vendor');                
        },         
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');
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
          parent.window.$('.loader-wrap').removeClass('d-none');          
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-fpb");
          parent.window.$('#coltrigger').val('bagbeli');                
        },
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');          
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

  $("#caripb").click(function() {    
    if($(this).attr('role')) {        
      if($('#idkontak').val()==''){
        $('#kontak').attr('data-title','Vendor harus diisi !');
        $('#kontak').tooltip('show');
        $('#kontak').focus();
        return;
      } 

    let totalbaris = $(".item").length;
    let stringpb = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringpb = stringpb + ',' + $("input[name^='noref']").eq(i).val();
        } else {
          stringpb = $("input[name^='noref']").eq(i).val();
        }
    }    

    $.ajax({ 
//      "url"    : base_url+"Modal/cari_transaksi_r", 
      "url"    : base_url+"Modal/cari_transaksi_multiple",
      "type"   : "POST", 
      "dataType" : "html", 
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Penerimaan Barang");
        parent.window.$("#modaltrigger").val("iframe-page-fpb");        
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari penerimaan barang...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);    
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
        parent.window.$("#param1").val(stringpb); 
        parent.window._transaksidatatable('view_cari_terima_barang_new',$('#idkontak').val());
        setTimeout(function (){
             parent.window.$("#modal input[type='search']").focus();
        }, 500);
        return;
      } 
    });   
  }
  });

  $("#buangmuka").click(function() {    
    if($(this).hasClass("disabled")) return;

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 


/*
    let totalbaris = $(".item").length;
    let stringpb = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringpb = stringpb + ',' + $("input[name^='nomordp']").eq(i).val();
        } else {
          stringpb = $("input[name^='nomordp']").eq(i).val();
        }
    }    

    if(stringpb == "") {
        parent.window.Swal.fire({
          title: 'Tidak ada uang muka pada transaksi ini !',
          showDenyButton: false,
          showCancelButton: false,
          confirmButtonText: `Tutup`,
        })
        return;      
    }
*/

    let totalbaris = $(".idinv").length;
    let stringdp = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringdp = stringdp + ',' + `'${$("input[name^='nomorinv']").eq(i).val()}'`;
        } else {
          stringdp = `'${$("input[name^='nomorinv']").eq(i).val()}'`;
        }
    }    

    $.ajax({ 
      "url"    : base_url+"Modal/cari_faktur",
      "type"   : "POST", 
      "dataType" : "html", 
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Uang Muka");
        parent.window.$("#modaltrigger").val("iframe-page-fpb");
        parent.window.$('#coltrigger').val('iddp');                
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari uang muka...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);    
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)'); 
        parent.window.$("#param1").val(stringdp);                  
        parent.window._transaksidatatable('view_cari_uang_muka_pembelian',$('#idkontak').val());
        setTimeout(function (){
             parent.window.$("#modal input[type='search']").focus();
        }, 500);
        return;
      } 
    });   
  });  

  $("#badd").click(function() {
    _clearForm();
    _addRow('faktur');
    _addRow('dp');    
    _inputFormat();          
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

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html", 
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-fpb");        
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);   
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');         
        parent.window._transaksidatatable('view_faktur_pembelian');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
  });

  $("#baddrow").click(function() {
    _addRow('faktur');
    _inputFormat();
    $("select[name^='item']").last().focus();        
  });

  $("#bcancel").click(function() {
    _clearForm();
    _addRow('faktur');
    _addRow('dp');    
    _inputFormat();
    _formState2();
  });

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#pajak').on('change',function(){
    _hitungTotal();
  });

  $('#chkpph22').on('click',function(){
    _hitungTotal();
  });  

  $('#iddp').on('change',function(){
    const id = $(this).val();
    _getDataDp(id);
  });  

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });    

  $('#notrans').on('change',function(){
    var notrans = $(this).val();
    _formState2();
    _getDataTransaksiNomor(notrans);
  });    

  $('#idreferensi').on('change',function(){
    var id = $(this).val();
    _getTerimaBarang(id);
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

  $(this).on("keyup", "input[name^='jmlbayarinv']", async function(){
      let _idx = $(this).index('.jmlbayarinv');
      await _hitungsubtotaldp();
      _hitungTotal();
  });  

  $(this).on("keyup", "input[name^='diskon']", async function(){
      let _idx = $(this).index('.diskon');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"PB_Faktur_Pembelian/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$(this).val(),
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
          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']),
              gudang = $("<option selected='selected'></option>")
                        .val(result.data[0]['idgudang'])
                        .text(result.data[0]['gudang']);          
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          $("select[name^='satuan']").eq(_idx).prop("disabled", true);            
          $("input[name^='harga']").eq(_idx).val(result.data[0]['harga']);    
          if(result.data[0]['gudang'] != null) $("select[name^='gudang']").eq(_idx).append(gudang).trigger('change');            

          if(result.data[0]['harga']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');     

          let jumlah = await _hitungJumlahDetil(_idx);
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
  Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,'form_satuan','Satuan');  
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
  $('#tuangmuka tbody').html('');            
//  $("#bresetdp").addClass("d-none");                 
}

var _formState1 = () => {
  $('.input-group-append').attr('data-dismiss','modal');
  $('.input-group-append').attr('data-toggle','modal');
  $('.input-group-append').attr('role','button');    
  $('.btn-step2').addClass('disabled');
  $('.btn-step1').removeClass('disabled');
  $('#baddrow').removeAttr('disabled');           
  $(":input").not(":button, :submit, :reset, :radio, .total").removeAttr('disabled');
  $(".satuan").prop('disabled',true);                 
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

var _addRow = (t1) => {
  if(t1=='faktur'){  
    let newrow = " <tr>";
        newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
        newrow += "<td><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><input type=\"hidden\" name=\"noref[]\" class=\"noref\"><input type=\"hidden\" name=\"nomordp[]\" class=\"nomordp\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
        newrow += "<td><input type=\"hidden\" name=\"sdid[]\" class=\"sdid\"><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
        newrow += "<td><input type=\"text\" name=\"sunotrans[]\" class=\"sunotrans form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
        newrow += "</tr>";
    $('#tdetil tbody').append(newrow);
  }
  if(t1=='dp'){
    var newrow = " <tr>";
        newrow += "<td><input type=\"hidden\" name=\"idinv\" class=\"idinv\"><input type=\"text\" name=\"nomorinv[]\" class=\"nomorinv form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"tglinv[]\" class=\"tglinv form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"totalinv[]\" class=\"totalinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"terbayarinv[]\" class=\"terbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarinv[]\" class=\"jmlbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbarisdp($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
        newrow += "</tr>";
    $('#tuangmuka tbody').append(newrow);
  }  
}

/**/

/* CRUD
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
    if ($('#termin').val()=='' || $('#termin').val()==null || $('#termin').val()==0){
      $('#termin').attr('data-title','Termin harus diisi !');      
      $('#termin').tooltip('show');
      $('#termin').focus();
      return 0;
    }    
    if ($('#uraian').val()==''){
      $('#uraian').attr('data-title','Uraian harus diisi !');      
      $('#uraian').tooltip('show');
      $('#uraian').focus();
      return 0;
    }

    let totalbaris = $(".item").length;
    for(let i=0;i<totalbaris;i++){
      if($("select[name^='item']").eq(i).val()=='' || $("select[name^='item']").eq(i).val()==null){
        parent.window.Swal.fire({
            title: `Item pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }
      if(Number($("input[name^='qty']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
        parent.window.Swal.fire({
            title: `Qty pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }
      if(Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
        parent.window.Swal.fire({
            title: `Harga pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }      
      if($("input[name^='sunotrans']").eq(i).val()==''){
        parent.window.Swal.fire({
            title: `No. Penerimaan pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }                        
    }
    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        _clearForm();
        _addRow('faktur');
        _addRow('dp');    
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
}

var _saveData = () => {

  const id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        refnomor = $("#idreferensi").val(),
        kontak = $("#idkontak").val(),
        person = $("#idperson").val(),
        karyawan = $("#idbagbeli").val(),
        termin = $("#termin").val(),
        pajak = $("#pajak").val(),
        gudang = $("#gudang").val(),      
        alamat = $("#alamat").val(), 
        status = $("#status").val(), 
        nopajak = $("#nofakturpajak").val(),
        tglpajak = $("#tglpajak").val(),
        memo = $("#memo").val(),    
//        iddp = $("#iddp").val(),  
        uraian = $("#uraian").val();

  var detil = [], detildp = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
               catatan:$("textarea[name^='catatan']").eq(index).val(),
               noref:$("input[name^='noref']").eq(index).val(),
               noref2:$("input[name^='sdid']").eq(index).val()               
             });
  }); 

  $("input[name^='idinv']").each(function(index,element){  
      detildp.push({
               iddp:this.value,
               jumlah:Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'))
             });
  });   

  detil = JSON.stringify(detil);  
  detildp = JSON.stringify(detildp);    

  const totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
        totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
        totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
        totalpph22 = Number($("#tpajakpph22").val().split('.').join('').toString().replace(',','.')),        
        totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.')),
        totaldp = Number($("#tdp").val().split('.').join('').toString().replace(',','.')),
        totalsisa = Number($("#tsisa").val().split('.').join('').toString().replace(',','.'));  

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('refnomor',refnomor);  
  rey.set('kontak',kontak);
  rey.set('person',person);
  rey.set('karyawan',karyawan);    
  rey.set('termin',termin);
  rey.set('gudang',gudang);  
  rey.set('pajak',pajak);
  rey.set('nopajak',nopajak);    
  rey.set('tglpajak',tglpajak);  
  rey.set('memo',memo);            
  rey.set('alamat',alamat); 
  rey.set('uraian',uraian); 
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('totalpajak',totalpajak);            
  rey.set('totalpph22',totalpph22);              
  rey.set('totaltrans',totaltrans);   
  rey.set('totaldp',totaldp);            
  rey.set('totalsisa',totalsisa);                             
  rey.set('detil',detil);
  rey.set('detildp',detildp);

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/savedata", 
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
      parent.window.toastr.error("Error : "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");                                            
      result = JSON.parse(result);
      if(result.pesan=='sukses'){
          parent.window.Swal.fire({
              title: `Anda ingin mencetak transaksi ini ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((printing) => {
              if (printing.isConfirmed) {
                window.open(`${base_url}Laporan/preview/page-fpb/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              _addRow('faktur');
              _addRow('dp');    
              _inputFormat();
              _formState1();
              return;
          })
      }                  
    } 
  })

}


var _tampildata = (result) => {
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { 
        
        var term = $("<option selected='selected'></option>").val(result.data[0]['idtermin']).text(result.data[0]['termin']);
        var gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']);                        

        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
        $('#uraian').val(result.data[0]['uraian']);            
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#idperson').val(result.data[0]['idperson']);
        $('#person').val(result.data[0]['person']);
        $('#idbagbeli').val(result.data[0]['idkaryawan']);
        $('#bagbeli').val(result.data[0]['namakaryawan']);                                    
        $('#termin').append(term).trigger('change');
        $('#gudang').append(gudang).trigger('change');            
        $('#pajak').val(result.data[0]['pajak']).change();
        $('#status').val(result.data[0]['status']);        
        $('#alamat').val(result.data[0]['alamat']);
        $('#memo').val(result.data[0]['catatan']); 

        var rows = 0, rows2 = 0, _tqty = 0, _tsubtotal = 0, _tpajak = 0, _tpph22 = 0, _ttrans = 0, _tdp = 0, _tsisa = 0;
        
        $('#tdetil tbody').html('');
        $('#tuangmuka tbody').html('');

        $.each(result.data, function() {
          _addRow('faktur');
          _inputFormat();

          const _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
                _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']);                        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));            
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));                        
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']);                                               
          $("input[name^='noref']").eq(rows).val(result.data[rows]['btguid']);                                               
          $("input[name^='sdid']").eq(rows).val(result.data[rows]['btgdid']);
          $("input[name^='sunotrans']").eq(rows).val(result.data[rows]['btgnotrans']);
          $("input[name^='nomordp']").eq(rows).val(result.data[rows]['btgnodp']);                                                                             

          _tqty += Number(result.data[rows]['qtydetil']);
          _tsubtotal += Number(result.data[rows]['subtotaldetil']);            

          //atur placeholder numeric jika 0
          if(result.data[rows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(result.data[rows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');                        

          rows++;
        });


        $.each(result.data2, function() {
          _addRow('dp');
          _inputFormat();

          $("input[name^='idinv']").eq(rows2).val(result.data2[rows2]['iddp']);                                                                      
          $("input[name^='nomorinv']").eq(rows2).val(result.data2[rows2]['nomordp']);
          $("input[name^='tglinv']").eq(rows2).val(result.data2[rows2]['tgldp']);
          $("input[name^='totalinv']").eq(rows2).val(result.data2[rows2]['totaldp'].replace(".", ","));
          $("input[name^='terbayarinv']").eq(rows2).val(result.data2[rows2]['sisa'].replace(".", ","));
          $("input[name^='jmlbayarinv']").eq(rows2).val(result.data2[rows2]['totalbayar'].replace(".", ","));          

          if(result.data2[rows2]['totaldp']==0) $("input[name^='totalinv']").eq(rows2).attr('placeholder','0,00');            
          if(result.data2[rows2]['sisa']==0) $("input[name^='terbayarinv']").eq(rows2).attr('placeholder','0,00');            
          if(result.data2[rows2]['totalbayar']==0) $("input[name^='jmlbayarinv']").eq(rows2).attr('placeholder','0,00');            

          rows2++;
        });

        _tpajak = Number(result.data[0]['tpajak']);
        _tpph22 = Number(result.data[0]['tpph22']);

        if(result.data[0]['pajak']=='2'){
          _ttrans = _tsubtotal;
        }else{
          _ttrans = _tsubtotal + _tpajak + _tpph22;
        }            

        if(_tpph22 > 0) $("#chkpph22").prop('checked', true);

        _tdp = Number(result.data[0]['tdp']);            
        _tsisa = _ttrans - _tdp;

        if(_tdp == 0) _tdp = '0,00';

        $('#tqty').val(_tqty.toString().replace('.',','));   
        $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
        $('#tpajak').val(_tpajak.toString().replace('.',','));               
        $('#tpajakpph22').val(_tpph22.toString().replace('.',','));                       
        $('#ttrans').val(_ttrans.toString().replace('.',','));
        $('#tdp').val(_tdp.toString().replace('.',','));                       
        $('#tsisa').val(_tsisa.toString().replace('.',','));
        $('#nofakturpajak').val(result.data[0]['nofakturpjk']);        
        $('#tglpajak').datepicker('setDate',result.data[0]['tglpajak']);                    
        _hitungTotal();

        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
        }
        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

      }
}


var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  if($('#notrans').val()!=="") return;

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      $('#modaltransaksi').modal('hide');           
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi faktur pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {
      _tampildata(result); 
    } 
})

}

var _getDataTransaksiNomor = (nomor) => {

  if(nomor=='' || nomor==null) return;    

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "nomor="+nomor,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');                  
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi faktur pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {
      _tampildata(result);
    } 
})

}


var _getTerimaBarang = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/getdatapenerimaan",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      parent.window.toastr.error('Error : Gagal mengambil data penerimaan barang !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async function(result){
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
        var datarows = 0;
        var rows = $(".item").length;         

        if(rows==1 && Number($('#tqty').val().split('.').join('').toString().replace(',','.'))=='0'){
          $('#tdetil tbody').html('');
          rows = 0;
        }

        var _tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.')), 
            _tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.')),
            _tpajak = Number($('#tpajak').val().split('.').join('').toString().replace(',','.')), 
            _ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.')), 
            _tdp = Number($('#tdp').val().split('.').join('').toString().replace(',','.')), 
            _tsisa = Number($('#tsisa').val().split('.').join('').toString().replace(',','.'));

        var detilharga = 0, detildiskon = 0, detilsubtotal = 0;

        $.each(result.data, function() {
          _addRow('faktur');
          _inputFormat();          

          var _item = $("<option selected='selected'></option>").val(result.data[datarows]['iditem']).text(result.data[datarows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datarows]['idsatuan']).text(result.data[datarows]['satuan']),
              _proyek = $("<option selected='selected'></option>").val(result.data[datarows]['idproyek']).text(result.data[datarows]['proyek']),
              _gudangd = $("<option selected='selected'></option>").val(result.data[datarows]['idgudang']).text(result.data[datarows]['gudang']);                        

          if(result.data[datarows]['hargainvoice'] > 0){
              detilharga = result.data[datarows]['hargainvoice'];
              detildiskon = result.data[datarows]['diskoninvoice'];              
              detilsubtotal = result.data[datarows]['subtotalinvoice'];
          } else {
              detilharga = result.data[datarows]['hargadetil'];
              detildiskon = result.data[datarows]['diskon'];              
              detilsubtotal = result.data[datarows]['subtotaldetil'];
          }

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[datarows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(detilharga.replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(detildiskon.replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[datarows]['persendiskon'].replace(".", ","));
          $("input[name^='noref']").eq(rows).val(result.data[datarows]['sdidsu']);                                               
          $("input[name^='sdid']").eq(rows).val(result.data[datarows]['sdid']); 
          $("input[name^='nomordp']").eq(rows).val(result.data[datarows]['iddp']);          
          $("input[name^='sunotrans']").eq(rows).val(result.data[datarows]['nomor']);                                                                   
          $("input[name^='subtotal']").eq(rows).val(detilsubtotal.replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[datarows]['catdetil']);

          _tqty += Number(result.data[datarows]['qtydetil']);
          _tsubtotal += Number(detilsubtotal);            

          if(result.data[datarows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(detilharga==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(detildiskon==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[datarows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(detilsubtotal==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');

          $("select[name^='satuan']").eq(rows).prop('disabled',true); 

          datarows++;
          rows++;
        });

          if($("#id").val()==''){
              var term = $("<option selected='selected'></option>").val(result.data[0]['idtermin']).text(result.data[0]['termin']);
              var gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']);
              $('#idperson').val(result.data[0]['idperson']);
              $('#person').val(result.data[0]['person']);
              $('#idbagbeli').val(result.data[0]['idkaryawan']);
              $('#bagbeli').val(result.data[0]['namakaryawan']);                                    
              if(result.data[0]['termin'] != null) $('#termin').append(term).trigger('change');
              $('#gudang').append(gudang).trigger('change');            
              if(result.data[0]['pajak'] != null) $('#pajak').val(result.data[0]['pajak']).change();
              $('#alamat').val(result.data[0]['alamat']);
          }   

          $('#tqty').val(_tqty.toString().replace('.',','));   
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
          _hitungTotal();

          parent.window.$('.loader-wrap').addClass('d-none');   
          return;                       
      }
    }
  });
}

var _getDataDp = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/getdatauangmuka",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      alert('Error : Gagal mengambil data uang muka pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async function(result){

      if (typeof result.pesan !== 'undefined') {
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { 
        var datarows = 0;
        var rows = $(".idinv").length;         

        if(rows==1 && $("input[name^='idinv']").eq(0).val()==''){
          $('#tuangmuka tbody').html('');
          rows = 0;
        }

        $.each(result.data, function() {
          _addRow('dp');
          _inputFormat();

          $("input[name^='idinv']").eq(rows).val(result.data[datarows]['id']);                                                                      
          $("input[name^='nomorinv']").eq(rows).val(result.data[datarows]['nomor']);
          $("input[name^='tglinv']").eq(rows).val(result.data[datarows]['tgl']);
          $("input[name^='totalinv']").eq(rows).val(result.data[datarows]['totaltrans'].replace(".", ","));
          $("input[name^='terbayarinv']").eq(rows).val(result.data[datarows]['terbayar'].replace(".", ","));
          $("input[name^='jmlbayarinv']").eq(rows).val(result.data[datarows]['totalbayar'].replace(".", ","));          

          if(result.data[datarows]['totaltrans']==0) $("input[name^='totalinv']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['terbayar']==0) $("input[name^='terbayarinv']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['totalbayar']==0) $("input[name^='jmlbayarinv']").eq(rows).attr('placeholder','0,00');            

          datarows++;
          rows++;
        });

        _hitungsubtotaldp();
        _hitungTotal();

        parent.window.$('.loader-wrap').addClass('d-none');   
        return;                       
      }
    }
  });
}

/**/

  if(qparam.get('id')!=null){
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }else if(qparam.get('nomor')!=null){
      _clearForm();
      _formState2();  
      $("#notrans").val(qparam.get('nomor')).trigger('change');          
  }else {
      _clearForm();
      _addRow('faktur');
      _addRow('dp');    
      _inputFormat();
      _formState1();  
  }

});

function _hitungTotal() {

    let p = $('#pajak').val();
    let nilaiPpn = Number($('#nilaipajak').val());     
    let nilaiP = Number($('#nilaipajak').val()/100);      
    let nilaiPph = Number($('#nilaipph22').val());     
    let nilaiPph22 = Number($('#nilaipph22').val()/100);               
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tdp = Number($('#tdp').val().split('.').join('').toString().replace(',','.'));    
    let tpjk = 0, tpph22 = 0, ttrans = 0, tsisa = 0;

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

    tsisa = ttrans-tdp;      

    tqty = tqty.toString().replace('.',',');
    tsubtotal = tsubtotal.toString().replace('.',',');            
    tpjk = tpjk.toString().replace('.',',');
    tpph22 = tpph22.toString().replace('.',',');        
    ttrans = ttrans.toString().replace('.',',');
    tdp =  tdp.toString().replace('.',',');
    tsisa = tsisa.toString().replace('.',',');

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
    if(tdp==0) tdp='0,00';
    if(tsisa==0) tsisa='0,00';

    $('#tqty').val(tqty).attr('placeholder',tqty);
    $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
    $('#tpajak').val(tpjk).attr('placeholder',tpjk);
    $('#tpajakpph22').val(tpph22).attr('placeholder',tpph22);    
    $('#ttrans').val(ttrans).attr('placeholder',ttrans);      
    $('#tdp').val(tdp).attr('placeholder',tdp);                  
    $('#tsisa').val(tsisa).attr('placeholder',tsisa);            

}

function _hitungJumlahDetil(idx){
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

function _hitungsubtotal(){
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

function _hitungsubtotaldp(){
  let tdp = 0;

  $('.idinv').each(function(index,element) {
    tdp += Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.')); 
  });    

  tdp = tdp.toString().replace('.',',');            
  if(tdp==0) tdp='0,00';  
  $('#tdp').val(tdp).attr('placeholder',tdp);        
  return;
}

window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}

window._hapusbarisdp = async (obj) => {
  if($(obj).hasClass('disabled')) return;

  $(obj).parent().parent().remove();
  await _hitungsubtotaldp();
  _hitungTotal();
}