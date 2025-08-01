/* ========================================================================================== */
/* File Name : perintah-perbaikan.js
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

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

/**/

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

  $('#karyawan').keydown(function(e){
    if(e.keyCode==13) { $('#carikaryawan').click(); }
  });

  $(this).on('select2:open', function(){
    this.querySelector('.select2-search__field').focus();
  });  

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/sppukData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-sppuk");
          parent.window.$('#coltrigger').val('customer');                
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
          parent.window._pilihkategorikontak('9'); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });

  $("#carikaryawan").click(function() {
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-sppuk");
          parent.window.$('#coltrigger').val('karyawan');                
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal cari mekanik...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak($("#karyawankat").val());
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

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-sppuk");        
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_perintah_perbaikan');
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

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-sppuk/${$("#id").val()}`)    
  });

  $('#id').on('change',function(){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $(this).on("keyup", "input[name^='qty']", async function(){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
//      let subtotal = await _hitungsubtotal();
//      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='harga']", async function(){
      let _idx = $(this).index('.harga');
      let jumlah = await _hitungJumlahDetil(_idx);
//      let subtotal = await _hitungsubtotal();
//      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='diskon']", async function(){
      let _idx = $(this).index('.diskon');
      let jumlah = await _hitungJumlahDetil(_idx);
//      let subtotal = await _hitungsubtotal();
//      _hitungTotal();
  });  

  $(this).on("select2:select", "select[name^='satuan']", async function(){
      let _idx = $(this).index('.satuan');
      let idsatuanpilih = $(this).val();
      let idsatuan2 = $("input[name^='sat2']").eq(_idx).val();
      let idsatuan3 = $("input[name^='sat3']").eq(_idx).val();      
      let idsatuan4 = $("input[name^='sat4']").eq(_idx).val();      
      let idsatuan5 = $("input[name^='sat5']").eq(_idx).val();            
      let idsatuan6 = $("input[name^='sat6']").eq(_idx).val();      
      let _harga = $("input[name^='defharga']").eq(_idx).val();

      if(idsatuanpilih==idsatuan2) {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat2']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat2']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat2']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat2']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='jharga1sat2']").eq(_idx).val();
          }     

        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan3) {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat3']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat3']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat3']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat3']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='jharga1sat3']").eq(_idx).val();
          }     

        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan4) {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat4']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat4']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat4']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat4']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='jharga1sat4']").eq(_idx).val();
          }     

        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
      } else if(idsatuanpilih==idsatuan5) {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat5']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat5']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat5']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat5']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='jharga1sat5']").eq(_idx).val();
          }     

        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);                
      } else if(idsatuanpilih==idsatuan6) {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat6']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat6']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat6']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat6']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='jharga1sat6']").eq(_idx).val();
          }     

        if(_harga==0) _harga='0,00';
        $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);                
      } else {
          switch($('#lvlharga').val()){
            case '1':
              _harga = $("input[name^='jharga1sat1']").eq(_idx).val();
              break;
            case '2':
              _harga = $("input[name^='jharga2sat1']").eq(_idx).val();
              break;                                      
            case '3':
              _harga = $("input[name^='jharga3sat1']").eq(_idx).val();
              break;                       
            case '4':
              _harga = $("input[name^='jharga4sat1']").eq(_idx).val();
              break;                                                                                 
            default:
              _harga = $("input[name^='defharga']").eq(_idx).val();
          }     

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
        "url"    : base_url+"PJ_Order_Penjualan/get_item", 
        "type"   : "POST", 
        "data"   : "id="+_iditem+"&kontak="+$("#idkontak").val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil satuan item...');
          //console.error(xhr.responseText);
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
          $("input[name^='jharga1sat1']").eq(_idx).val(result.data[0]['hargajual']);                            
          $("input[name^='jharga2sat1']").eq(_idx).val(result.data[0]['hargajual2']);                            
          $("input[name^='jharga3sat1']").eq(_idx).val(result.data[0]['hargajual3']);                            
          $("input[name^='jharga4sat1']").eq(_idx).val(result.data[0]['hargajual4']);
          $("input[name^='jharga1sat2']").eq(_idx).val(result.data[0]['sat2hargajual1']);                            
          $("input[name^='jharga2sat2']").eq(_idx).val(result.data[0]['sat2hargajual2']);                            
          $("input[name^='jharga3sat2']").eq(_idx).val(result.data[0]['sat2hargajual3']);                            
          $("input[name^='jharga4sat2']").eq(_idx).val(result.data[0]['sat2hargajual4']);
          $("input[name^='jharga1sat3']").eq(_idx).val(result.data[0]['sat3hargajual1']);                            
          $("input[name^='jharga2sat3']").eq(_idx).val(result.data[0]['sat3hargajual2']);                            
          $("input[name^='jharga3sat3']").eq(_idx).val(result.data[0]['sat3hargajual3']);                            
          $("input[name^='jharga4sat3']").eq(_idx).val(result.data[0]['sat3hargajual4']);                                                                                
          $("input[name^='jharga1sat4']").eq(_idx).val(result.data[0]['sat4hargajual1']);                            
          $("input[name^='jharga2sat4']").eq(_idx).val(result.data[0]['sat4hargajual2']);                            
          $("input[name^='jharga3sat4']").eq(_idx).val(result.data[0]['sat4hargajual3']);                            
          $("input[name^='jharga4sat4']").eq(_idx).val(result.data[0]['sat4hargajual4']);
          $("input[name^='jharga1sat5']").eq(_idx).val(result.data[0]['sat5hargajual1']);                            
          $("input[name^='jharga2sat5']").eq(_idx).val(result.data[0]['sat5hargajual2']);                            
          $("input[name^='jharga3sat5']").eq(_idx).val(result.data[0]['sat5hargajual3']);                            
          $("input[name^='jharga4sat5']").eq(_idx).val(result.data[0]['sat5hargajual4']);
          $("input[name^='jharga1sat6']").eq(_idx).val(result.data[0]['sat6hargajual1']);                            
          $("input[name^='jharga2sat6']").eq(_idx).val(result.data[0]['sat6hargajual2']);                            
          $("input[name^='jharga3sat6']").eq(_idx).val(result.data[0]['sat6hargajual3']);                            
          $("input[name^='jharga4sat6']").eq(_idx).val(result.data[0]['sat6hargajual4']);

          $("#lvlharga").val(result.data[0]['level']);          

          switch(result.data[0]['level']){
            case '1':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual']);              
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;
            case '2':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual2']);
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual2']);                            
              if(result.data[0]['hargajual2']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                      
            case '3':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual3']);
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual3']);                            
              if(result.data[0]['hargajual3']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                       
            case '4':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual4']);
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual4']);                            
              if(result.data[0]['hargajual4']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                                                                 
            default:
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual']);                                
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
          }     

          if(_qty == 0) $("input[name^='qty']").eq(_idx).val(1);

          let jumlah = await _hitungJumlahDetil(_idx);
//          let subtotal = await _hitungsubtotal();
//          _hitungTotal();
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
  Component_Inputmask_Numeric_Flexible('.qty', $("#decimalqty").val());    
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item`,'form_item','Item');  
  Component_Inputmask_Numeric_Flexible('#odometer', 0);
}

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .nilaipajak, .noclear").val('');
  $(":checkbox").prop("checked", false); 
  $('.select2').val('').change();    
  $('#namakontak').html("");
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
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
    $('#karyawan').focus();        
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
  let newrow = " <tr>";
      newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"hidden\" name=\"sat2[]\" class=\"sat2\"><input type=\"hidden\" name=\"sat3[]\" class=\"sat3\"><input type=\"hidden\" name=\"sat4[]\" class=\"sat4\"><input type=\"hidden\" name=\"sat5[]\" class=\"sat5\"><input type=\"hidden\" name=\"sat6[]\" class=\"sat6\"><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><input type=\"hidden\" name=\"jharga1sat1[]\" class=\"jharga1sat1\"><input type=\"hidden\" name=\"jharga2sat1[]\" class=\"jharga2sat1\"><input type=\"hidden\" name=\"jharga3sat1[]\" class=\"jharga3sat1\"><input type=\"hidden\" name=\"jharga4sat1[]\" class=\"jharga4sat1\"><input type=\"hidden\" name=\"defharga[]\" class=\"defharga\"><input type=\"hidden\" name=\"nilaisat2[]\" class=\"nilaisat2\"><input type=\"hidden\" name=\"jharga1sat2[]\" class=\"jharga1sat2\"><input type=\"hidden\" name=\"jharga2sat2[]\" class=\"jharga2sat2\"><input type=\"hidden\" name=\"jharga3sat2[]\" class=\"jharga3sat2\"><input type=\"hidden\" name=\"jharga4sat2[]\" class=\"jharga4sat2\"><input type=\"hidden\" name=\"nilaisat3[]\" class=\"nilaisat3\"><input type=\"hidden\" name=\"jharga1sat3[]\" class=\"jharga1sat3\"><input type=\"hidden\" name=\"jharga2sat3[]\" class=\"jharga2sat3\"><input type=\"hidden\" name=\"jharga3sat3[]\" class=\"jharga3sat3\"><input type=\"hidden\" name=\"jharga4sat3[]\" class=\"jharga4sat3\"><input type=\"hidden\" name=\"nilaisat4[]\" class=\"nilaisat4\"><input type=\"hidden\" name=\"jharga1sat4[]\" class=\"jharga1sat4\"><input type=\"hidden\" name=\"jharga2sat4[]\" class=\"jharga2sat4\"><input type=\"hidden\" name=\"jharga3sat4[]\" class=\"jharga3sat4\"><input type=\"hidden\" name=\"jharga4sat4[]\" class=\"jharga4sat4\"><input type=\"hidden\" name=\"nilaisat5[]\" class=\"nilaisat5\"><input type=\"hidden\" name=\"jharga1sat5[]\" class=\"jharga1sat5\"><input type=\"hidden\" name=\"jharga2sat5[]\" class=\"jharga2sat5\"><input type=\"hidden\" name=\"jharga3sat5[]\" class=\"jharga3sat5\"><input type=\"hidden\" name=\"jharga4sat5[]\" class=\"jharga4sat5\"><input type=\"hidden\" name=\"nilaisat6[]\" class=\"nilaisat6\"><input type=\"hidden\" name=\"jharga1sat6[]\" class=\"jharga1sat6\"><input type=\"hidden\" name=\"jharga2sat6[]\" class=\"jharga2sat6\"><input type=\"hidden\" name=\"jharga3sat6[]\" class=\"jharga3sat6\"><input type=\"hidden\" name=\"jharga4sat6[]\" class=\"jharga4sat6\"><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
      newrow += "<td class=\"d-none\"><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td class=\"d-none\"><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}

/**/

/* CRUD
/* ========================================================================================== */
var _IsValid = () => {

    if ($('#idkaryawan').val()==''){
      $('#karyawan').attr('data-title','Mekanik harus diisi !');      
      $('#karyawan').tooltip('show');
      $('#karyawan').focus();
      return 0;
    }
    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }
    if($('#nopol').val()==''){
      $('#nopol').attr('data-title','No.Polisi Kendaraan harus diisi !');
      $('#nopol').tooltip('show');
      $('#nopol').focus();
      return 0;
    }    
    if($('#keluhan').val()==''){
      $('#keluhan').attr('data-title','Keluhan pelanggan harus diisi !');
      $('#keluhan').tooltip('show');
      $('#keluhan').focus();
      return 0;
    }        
    if($('#diagnosa').val()==''){
      $('#diagnosa').attr('data-title','Diagnosa mekanik harus diisi !');
      $('#diagnosa').tooltip('show');
      $('#diagnosa').focus();
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
  const id = $("#id").val();
  const nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"SV_Perintah_Perbaikan/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+" "+error);      
      console.error(xhr.responseText);      
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
}

var _saveData = () => {

  const id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        kontak = $("#idkontak").val(),
        karyawan = $("#idkaryawan").val(),
        status = $("#status").val(),            
        nopol = $("#nopol").val(),
        pemilik = $("#pemilik").val(),
        notelp = $("#notelp").val(),      
        alamat = $("#alamat").val(),                        
        keluhan = $("#keluhan").val(),
        temuan = $("#temuan").val(),   
        odo = $("#odometer").val(),
        merek = $("#merek").val(),
        nomesin = $("#nomesin").val(),             
        diagnosa = $("#diagnosa").val();

  let detil = [];

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

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('karyawan',karyawan);       
  rey.set('keluhan',keluhan);
  rey.set('temuan',temuan);
  rey.set('diagnosa',diagnosa);
  rey.set('odo',odo); 
  rey.set('merek',merek); 
  rey.set('nomesin',nomesin);                 
  rey.set('status',status);      
  rey.set('nopol',nopol);
  rey.set('notelp',notelp);
  rey.set('pemilik',pemilik);            
  rey.set('alamat',alamat);              
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"SV_Perintah_Perbaikan/savedata", 
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
                window.open(`${base_url}Laporan/preview/page-sppuk/${result.nomor}`)
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
    "url"    : base_url+"SV_Perintah_Perbaikan/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      alert('Error : Gagal mengambil data surat perintah perbaikan unit !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') {
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { 
        
        $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();

        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#idkaryawan').val(result.data[0]['idkaryawan']);
        $('#karyawan').val(result.data[0]['namakaryawan']);                                    
        $('#status').val(result.data[0]['status']);        
        $("#lvlharga").val(result.data[0]['level']);          
        $('#odometer').val(result.data[0]['odometer']);
        $('#merek').val(result.data[0]['merek']);        
        $('#nomesin').val(result.data[0]['nomesin']);
        $('#keluhan').val(result.data[0]['keluhan']);
        $('#diagnosa').val(result.data[0]['diagnosa']);
        $('#temuan').val(result.data[0]['temuan']);        
        $('#nopol').val(result.data[0]['nopol']);
        $('#pemilik').val(result.data[0]['pemilik']);
        $('#notelp').val(result.data[0]['notelp']);                                
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
          $("input[name^='defharga']").eq(rows).val(result.data[rows]['hargajual']);          
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
          $("input[name^='jharga1sat1']").eq(rows).val(result.data[rows]['hargajual']);                            
          $("input[name^='jharga2sat1']").eq(rows).val(result.data[rows]['hargajual2']);                            
          $("input[name^='jharga3sat1']").eq(rows).val(result.data[rows]['hargajual3']);                            
          $("input[name^='jharga4sat1']").eq(rows).val(result.data[rows]['hargajual4']);
          $("input[name^='jharga1sat2']").eq(rows).val(result.data[rows]['sat2hargajual1']);                            
          $("input[name^='jharga2sat2']").eq(rows).val(result.data[rows]['sat2hargajual2']);                            
          $("input[name^='jharga3sat2']").eq(rows).val(result.data[rows]['sat2hargajual3']);                            
          $("input[name^='jharga4sat2']").eq(rows).val(result.data[rows]['sat2hargajual4']);
          $("input[name^='jharga1sat3']").eq(rows).val(result.data[rows]['sat3hargajual1']);                            
          $("input[name^='jharga2sat3']").eq(rows).val(result.data[rows]['sat3hargajual2']);                            
          $("input[name^='jharga3sat3']").eq(rows).val(result.data[rows]['sat3hargajual3']);                            
          $("input[name^='jharga4sat3']").eq(rows).val(result.data[rows]['sat3hargajual4']);                                                                                
          $("input[name^='jharga1sat4']").eq(rows).val(result.data[rows]['sat4hargajual1']);                            
          $("input[name^='jharga2sat4']").eq(rows).val(result.data[rows]['sat4hargajual2']);                            
          $("input[name^='jharga3sat4']").eq(rows).val(result.data[rows]['sat4hargajual3']);                            
          $("input[name^='jharga4sat4']").eq(rows).val(result.data[rows]['sat4hargajual4']);
          $("input[name^='jharga1sat5']").eq(rows).val(result.data[rows]['sat5hargajual1']);                            
          $("input[name^='jharga2sat5']").eq(rows).val(result.data[rows]['sat5hargajual2']);                            
          $("input[name^='jharga3sat5']").eq(rows).val(result.data[rows]['sat5hargajual3']);                            
          $("input[name^='jharga4sat5']").eq(rows).val(result.data[rows]['sat5hargajual4']);
          $("input[name^='jharga1sat6']").eq(rows).val(result.data[rows]['sat6hargajual1']);                            
          $("input[name^='jharga2sat6']").eq(rows).val(result.data[rows]['sat6hargajual2']);                            
          $("input[name^='jharga3sat6']").eq(rows).val(result.data[rows]['sat6hargajual3']);                            
          $("input[name^='jharga4sat6']").eq(rows).val(result.data[rows]['sat6hargajual4']);

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
    let nilaiP = Number($('#nilaipajak').val()/100);      
    let nilaiPph22 = Number($('#nilaipph22').val()/100);          
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tpjk = 0, tpph22 = 0, ttrans = 0;
    let nilaiPpn = Number($('#nilaipajak').val());     
    let nilaiPph = Number($('#nilaipph22').val());      

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

    $('#tqty').val(tqty).attr('placeholder',tqty);
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