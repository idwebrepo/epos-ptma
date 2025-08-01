/* ========================================================================================== */
/* File Name : penjualan-tunai.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Item } from '../../component.js';
import { Component_Select2_Account } from '../../component.js';

$(function() {
  
  const qparam = new URLSearchParams(this.location.search);  
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth()+1;
  var yyyy = today.getFullYear();
  var isreqbarcode = false;

  if(dd<10) {
    dd='0'+dd
  } 

  if(mm<10) {
    mm='0'+mm
  } 

  today = dd+'/'+mm+'/'+yyyy;
  document.getElementById("curDate").innerHTML = today;
  var myVar=setInterval(function(){myTimer()},1000);

  function myTimer() {
      var d = new Date();
      document.getElementById("curTime").innerHTML = d.toLocaleTimeString()+',';
  }

  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#idtunda')[0]);  
  setupHiddenInputChangeListener($('#notrans')[0]);    
  setupHiddenInputChangeListener($('#tbayartrigger')[0]);      

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak');

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

  if((screen.width <= 767) || (window.matchMedia && window.matchMedia('only screen and (max-width: 767px)').matches))
  {
      $(".mainleft").removeClass("flex-row");             
      $(".mainleft").addClass("flex-column");         
      $("#rightmain").css("position", "absolute");
      $("#rightmain").css("top", "50px");
      $("#rightmain").css("height", "100%");                           
  } 

/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#kontak').keydown(function(e){
    if(e.keyCode==13) { 
      e.preventDefault();
      $('#kodeitem').focus(); 
    }
  });

  $('#cariitem').keydown(function(e){
    if(e.keyCode==13) { 
      e.preventDefault();
      _getlistitem();
      $('.btn-iteml').first().focus();       
    }
  });

  $(this).on("keydown", "input[name^='qty']", function(e){
    if(e.keyCode==13) { 
      e.preventDefault();
      $('#kodeitem').focus(); 
    }
  });

  $('#kodeitem').on('keypress', function(e){

    if(e.keyCode==13) { 
      $(".item").last().focus();
      return;
    }

    if ($(this).val().length >= 6 && isreqbarcode == false) {
      setTimeout(async function(){
          isreqbarcode = true;
          await _tambahItem($("#kodeitem").val());        
          $("#kodeitem").focus();
          isreqbarcode = false;
      },200);
    }

  });  

  $('body').keydown(function(e){
    if(e.keyCode==112) { 
      e.preventDefault();    
      $('#carikontak').click(); 
    }
    if(e.keyCode==113) { 
      e.preventDefault();    
      $('#carikaryawan').click(); 
    }    
    if(e.keyCode==114) { 
      e.preventDefault();    
      $('#kodeitem').focus(); 
    }    
    if(e.keyCode==118) { 
      e.preventDefault();
      $('#cariitem').focus(); 
    }        
    if(e.keyCode==119) { 
      e.preventDefault();
      $('#bpembayaran').click(); 
    }    
    if(e.keyCode==120) { 
      e.preventDefault();
      $('#btunda').click(); 
    }        
    if(e.keyCode==121) { 
      e.preventDefault();
      $('#bsave').click(); 
    }            
    if(e.keyCode==122) { 
      e.preventDefault();
      $('#bcancel').click(); 
    }                
    if(e.keyCode==123) { 
      e.preventDefault();
      $('#chkBarcode').click(); 
    }        
  });  

  $(this).on('select2:open', () => {
    this.querySelector('.select2-search__field').focus();
  });  

  $(this).on("click", ".btn-iteml", async function(){
    await _tambahItem(this.value);
    $("#cariitem").focus();                                      
  });

  $(this).on("click", ".btn-kategoril", function(){
//    alert(this.id);
    if($("#"+this.id).hasClass('active')){
      $("#idkategori").val("");      
      $(".btn-kategoril").removeClass('active');
    }else{
      $("#idkategori").val(this.value);      
      $(".btn-kategoril").removeClass('active');
      $("#"+this.id).addClass('active');      
    }
    _getlistitem();
  });

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/posData";      
  });

  $("#bfullscreen").click(function(){
    parent.window.$("#bfullscreen").click();
  });

  $(document).on('click', function (e) {
    if (
        !$(e.target).is('.open-category, .cat-child') &&
        $('#category-slider').is(':visible')
    ) {
        $('#category-slider').toggle('slide', { direction: 'right' }, 700);
    }
  });

  $("#bkategori").click(function(){
    $('#category-slider').toggle('slide', { direction: 'right' }, 700);    
  });

  $("#biteminfo").click(() => { 
    if(!$('#biteminfo').hasClass('disabled')){
      $.ajax({ 
        "url"    : base_url+"Modal/form_view_item", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": () => {
          parent.window.$(".loader-wrap").removeClass("d-none");      
          parent.window.$(".modal-header").removeClass("bg-primary");
          parent.window.$(".modal-header").addClass("bg-secondary"); 
          parent.window.$(".modal-body").addClass("border-0");           
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Informasi Item");
          parent.window.$("#modaltrigger").val("iframe-page-pos");
        },     
        "error": () => {
          parent.window.$(".loader-wrap").addClass("d-none");      
          console.log('error menampilkan modal informasi item...');
          return;
        },
        "success":async (result) => {
          await parent.window.$(".main-modal-body").html(result);      
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');                  
          parent.window.$(".loader-wrap").addClass("d-none");            
        } 
      })  
    } 
  })

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
          parent.window.$("#modaltrigger").val("iframe-page-pos");
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

  $("#bexit").click(function() {
    parent.window.Swal.fire({
      title: 'Keluar dari POS ?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: `Iya`,
    }).then((result) => {
      if (result.isConfirmed) {
        parent.window.$('body').removeClass('iframe-mode-fullscreen');
        parent.window.$('.content-wrapper').height('auto');
        parent.window.$('.content-wrapper iframe').height('auto');
        parent.window.$('body').trigger('resize');        
        parent.window.$('.content-wrapper').IFrame('removeActiveTab');                    
      }
    })    
  })

  $("#bcustdisplay").click(function() {
      parent.window.Swal.fire({
          title: `Fitur ini akan segera hadir`,
          showDenyButton: false,
          showCancelButton: false,
          confirmButtonText: `Tutup`,
      });
      return;
  });

  $("#blisttunda").click(function() {

      $.ajax({ 
        "url"    : base_url+"Modal/cari_tunda_pos", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal-header").removeClass("bg-primary");
          parent.window.$(".modal-header").addClass("bg-secondary"); 
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Daftar Transaksi Ditunda");
          parent.window.$("#modaltrigger").val("iframe-page-pos");
          parent.window.$('#coltrigger').val('idtunda');                
        },
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');
          console.log('error menampilkan modal daftar tunda transaksi...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        }
      });
  })

  $("#btunda").click(function() {
    let totalbaris = $(".item").length;

    if($('#idkontak').val()=='' || $('#idkontak').val()==0){
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    }

    if(totalbaris == 1 && ($("select[name^='item']").eq(0).val()=='' || $("select[name^='item']").eq(0).val()==null)){
      parent.window.Swal.fire({
          title: `Belum ada transaksi yang dapat ditunda !`,
          showDenyButton: false,
          showCancelButton: false,
          confirmButtonText: `Tutup`,
          onAfterClose: () => {
            setTimeout(() => $("#kodeitem").focus(), 110);
          }
      })
      return;
    }

    // Kode tunda transaksi
    let tgl = $("#tgl").val(),
        idtunda = $("#idtunda").val(),        
        kontak = $("#idkontak").val(),
        karyawan = $("#idkaryawan").val(),            
        catatan = $("#catatan").val(),                        
        pajak = $("#pajak").val(),
        detil = [];

    $("select[name^='item']").each(function(index,element){  
      if(this.value != null && this.value != ''){
        detil.push({
                 item:this.value,
                 qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
                 satuan:$("select[name^='satuan']").eq(index).val(),               
                
                //  harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
                 harga:Number($("select[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
                
                 diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
                 persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
                 gudang:$("select[name^='gudangdetil']").eq(index).val()               
               });
      }
    });

    detil = JSON.stringify(detil);

    const totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
          totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
          totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
          totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.')),
          totalbayar = Number($("#tbayar").val().split('.').join('').toString().replace(',','.')),
          totalsisa = Number($("#tsisa").val().split('.').join('').toString().replace(',','.')),
          cash = $("#bayarcash").val(),
          debit = $("#bayardebit").val(),
          debitbank = $("#bayardebitbank").val(),        
          debitno = $("#bayardebitno").val(),
          diskon = $("#bayardiskon").val(),        
          credit = $("#bayarcredit").val(),
          creditbank = $("#bayarcreditbank").val(),        
          creditno = $("#bayarcreditno").val();                        

    var rey = new FormData();

    rey.set('tgl',tgl);
    rey.set('idtunda',idtunda);
    rey.set('kontak',kontak);
    rey.set('karyawan',karyawan);
    rey.set('catatan',catatan);                
    rey.set('pajak',pajak);
    rey.set('totalqty',totalqty);      
    rey.set('totalsub',totalsub);
    rey.set('totalpajak',totalpajak);            
    rey.set('totaltrans',totaltrans);  
    rey.set('totalbayar',totalbayar);            
    rey.set('totalsisa',totalsisa);                
    rey.set('cash',cash);
    rey.set('debit',debit);
    rey.set('debitbank',debitbank);
    rey.set('debitno',debitno);
    rey.set('credit',credit);      
    rey.set('diskon',diskon);        
    rey.set('creditbank',creditbank);                            
    rey.set('creditno',creditno);                                                                                                                        
    rey.set('detil',detil);

    $.ajax({ 
      "url"    : base_url+"PJ_Penjualan_Tunai/savedatatunda", 
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
          parent.window.toastr.success("Penundaan Transaksi berhasil");                                                             
          _clearForm();
          _addRow();
          _inputFormat();          
          _inputFormatLast();                        
          _formState1();
          _getjumlahtunda();

          $("#idkontak").val($("#ikontakpos").val());
          $("#kontak").val($("#ikontakposkode").val());    
          $("#namakontak").html($("#ikontakposnama").val());  
          $('#pajak').val($("#ipajakpos").val()).change();
          setTimeout(function () {
            $("#kodeitem").focus();
          }, 500);
          return;              
        }                  
      } 
    })      
    
  })

  $("#bmodegrid").click(function() {
    if($(".mainright").hasClass("d-none")) {
      $("#mainleft").addClass("w-40");
      $(".mainright").removeClass("d-none");    
      $("#lbltotal").addClass("d-none");

      $("#th-nama").css("width","155px");                      
      $("#th-satuan").addClass("d-none");                      
      $("#th-jumlah").addClass("d-none");
      $("#th-diskon").addClass("d-none");                            
      $("#th-persen").addClass("d-none");
      $("#th-gudang").addClass("d-none");

      $(".td-satuan").addClass("d-none");
      $(".td-jumlah").addClass("d-none");      
      $(".td-diskon").addClass("d-none");                            
      $(".td-persen").addClass("d-none");
      $(".td-gudang").addClass("d-none"); 
      $(".muang").addClass("d-none");

      $("#bkategori").attr("disabled", false);            
    } else {
      $("#mainleft").removeClass("w-40");
      $(".mainright").addClass("d-none");          
      $("#lbltotal").removeClass("d-none");                      

      $("#th-nama").css("width","260px");                      
      $("#th-satuan").removeClass("d-none");
      $("#th-jumlah").removeClass("d-none");
      $("#th-diskon").removeClass("d-none");                            
      $("#th-persen").removeClass("d-none");
      $("#th-gudang").removeClass("d-none");      

      $(".td-satuan").removeClass("d-none");
      $(".td-jumlah").removeClass("d-none");      
      $(".td-diskon").removeClass("d-none");                            
      $(".td-persen").removeClass("d-none");
      $(".td-gudang").removeClass("d-none");            
      $(".muang").removeClass("d-none");            

      $("#bkategori").attr("disabled", true);                  
    }
  });

  $("#bpembayaran").click(function() {

      if($('#idkontak').val()=='' || $('#idkontak').val()==0){
        $('#kontak').attr('data-title','Pelanggan harus diisi !');
        $('#kontak').tooltip('show');
        $('#kontak').focus();
        return 0;
      }

      if(Number($("#ttrans").val().split('.').join('').toString().replace(',','.'))==0) {
        parent.window.Swal.fire({
            title: `Belum ada transaksi yang harus dibayar !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        });
        return;
      } else {
        var nilaiIdKontak = $("#idkontak").val();
        $.ajax({ 
          "url"    : base_url+"Modal/pembayaran_pos", 
          "type"   : "POST", 
          "dataType" : "html",
          "data": { idkontak: nilaiIdKontak }, 
          "beforeSend": function(){
            parent.window.$('.loader-wrap').removeClass('d-none');
            parent.window.$("#modalsize").addClass('modal-md');       
            parent.window.$(".modal-header").removeClass("bg-primary");
            parent.window.$(".modal-header").addClass("bg-secondary"); 
            parent.window.$(".modal-body,.modal-footer").addClass("border-0");                                                                
            parent.window.$(".modal").modal("show");                  
            parent.window.$(".modal-title").html("Pembayaran");
            parent.window.$("#modaltrigger").val("iframe-page-pos");
            parent.window.$('#coltrigger').val('tbayar');                
          },
          "error": function(){
            parent.window.$('.loader-wrap').addClass('d-none');
            console.log('error menampilkan form pembayaran tunai...');
            return;
          },
          "success": async function(result) {
            let bankdebit = $("<option selected='selected'></option>")
                            .val($('#bayardebitbank').val())
                            .text($('#bayardebitbankn').val()),
                bankkredit = $("<option selected='selected'></option>")
                            .val($('#bayarcreditbank').val())
                            .text($('#bayarcreditbankn').val());

            await parent.window.$(".main-modal-body").html(result);
            parent.window.$('.loader-wrap').addClass('d-none'); 
            parent.window.$('#ttrans').val(Number($("#ttrans").val().split('.').join('').toString().replace(',','.')));
            parent.window.$('#tbayar').val(Number($("#tbayar").val().split('.').join('').toString().replace(',','.')));            
            parent.window.$('#tsisa').val(Number($("#tsisa").val().split('.').join('').toString().replace(',','.')));  
            parent.window.$('#cash').val($("#bayarcash").val());
            parent.window.$('#diskon').val($("#bayardiskon").val());            
            parent.window.$('#debit').val($("#bayardebit").val());
            if($('#bayardebitbank').val() != '') parent.window.$('#bankdebit').append(bankdebit).trigger('change');            
            if($('#bayarcreditbank').val() != '') parent.window.$('#bankcredit').append(bankkredit).trigger('change');                        
            parent.window.$('#debitno').val($("#bayardebitno").val());            
            parent.window.$('#credit').val($("#bayarcredit").val());              
            // parent.window.$('#idkontak').val($("#idkontak").val());              
            parent.window.$('#creditno').val($("#bayarcreditno").val());                                                                               
            setTimeout(function (){
                 parent.window._hitungtotal();
                 parent.window.$('#cash').focus();
            }, 500);            
            return;
          } 
        })
      }
  })

  $(document).ready(function() {
    $("#bsinkron").click(function() {
      $.ajax({ 
        url: base_url + 'client/fetch_data/',
        type: 'POST', 
        success: function(msg) {
          
          alert('Success');
          // window.location.href = base_url + 'page/pos/';
        },
        error: function(xhr, status, error) {
          alert('Error: ' + error);
        }
      });
    });
  });
  

  $("#carikontak").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal-header").removeClass("bg-primary");
          parent.window.$(".modal-header").addClass("bg-secondary"); 
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-pos");
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

          console.log('Kontak Santri')
          parent.window._pilihkategorikontak('2'); 
          
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
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal-header").removeClass("bg-primary");
          parent.window.$(".modal-header").addClass("bg-secondary"); 
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-pos");
          parent.window.$('#coltrigger').val('karyawan');                
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
          parent.window._pilihkategorikontak($("#ikaryawankat").val()); 
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

    $("#idkontak").val($("#ikontakpos").val());
    $("#kontak").val($("#ikontakposkode").val());    
    $("#namakontak").html($("#ikontakposnama").val());  
    $('#pajak').val($("#ipajakpos").val()).change();

  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;   
    _addRow();
    _inputFormat(); 
    _inputFormatLast();                            
    _formState1();
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
        parent.window.$("#modaltrigger").val("iframe-page-pos");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');                  
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);   
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');           
        parent.window._transaksidatatable('view_penjualan_tunai');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
  });

  $("#bcancel").click(function() {

    parent.window.Swal.fire({
      title: 'Batalkan perubahan transaksi ?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: `Iya`,
    }).then((result) => {
      if (result.isConfirmed) {
        _clearForm();
        _addRow();
        _inputFormat();          
        _inputFormatLast();                                
        _formState1();

        $("#idkontak").val($("#ikontakpos").val());
        $("#kontak").val($("#ikontakposkode").val());    
        $("#namakontak").html($("#ikontakposnama").val());  
        $('#pajak').val($("#ipajakpos").val()).change();

        setTimeout(function () {
          $("#kodeitem").focus();
        }, 500);

      }
    })

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

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-pos/${$("#id").val()}`)    
  });

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _getDataTransaksi(idtrans);
  });  

  $('#idtunda').on('change',function(){
    var idtrans = $(this).val();
    _getDataTunda(idtrans);
  });  

  $('#notrans').on('change',function(){
    var notrans = $(this).val();
    _getDataTransaksiNomor(notrans);
  });    

  $('#pajak').on('change',function(){
    _hitungTotal();
  });

  $('#tbayartrigger').on('change',function(){

    $("#tbayar").val($(this).val());
    _hitungTotal();

    if($(this).val() != '') { 
      $('#bsave').click();
    }     
  });

  $("#chkBarcode").click(function(){
    if ($(this).is(":checked"))
    {
      $("#dbarcode").removeClass("d-none");
    } else {
      $("#dbarcode").addClass("d-none");
    }
  })

  $(this).on("keyup", "input[name^='qty']", async function(){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  // $(this).on("keyup", "input[name^='harga']", async function(){
  //     let _idx = $(this).index('.harga');
  //     let jumlah = await _hitungJumlahDetil(_idx);
  //     let subtotal = await _hitungsubtotal();
  //     _hitungTotal();
  // });

  $(this).on("change", "select[name^='harga']", async function(){
    let _idx = $(this).index('.harga');
    let jumlah = await _hitungJumlahDetil(_idx);
    let subtotal = await _hitungsubtotal();
    _hitungTotal();
});

  $(this).on("keypress", "input[name^='diskon']", async function(){
      let _idx = $(this).index('.diskon');
      $("input[name^='persen']").eq(_idx).val('0,00').attr('placeholder','0,00');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });    

  $(this).on("keypress", "input[name^='persen']", async function(){
      let _idx = $(this).index('.persen');
      $("input[name^='diskon']").eq(_idx).val('0,00').attr('placeholder','0,00');
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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga); 
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');

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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');
        
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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');
        
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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);                
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');
      
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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);                
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');
      
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
        // $("input[name^='harga']").eq(_idx).val(_harga).attr('placeholder',_harga);        
        $("select[name^='harga']").eq(_idx).val(_harga).trigger('change');
      
      }            

      await _hitungJumlahDetil(_idx);
      await _hitungsubtotal();
      _hitungTotal();      
  });

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _iditem = $(this).val();      
      let _idx = $(this).index('.item');
      let totalbaris = $(".item").length;

      $.ajax({ 
        "url"    : base_url+"PJ_Penjualan_Tunai/get_item", 
        "type"   : "POST", 
        "data"   : "id="+_iditem+"&kontak="+$("#idkontak").val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(){
          parent.window.toastr.error('Error : Data item tidak valid ...');
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) {
          _getSatuanItem(_iditem, _idx);

          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']),
              gudang = $("<option selected='selected'></option>")
                        .val(result.data[0]['idgudang'])
                        .text(result.data[0]['gudang']);          

          let _qty = Number($("input[name^='qty']").eq(_idx).val().split('.').join('').toString().replace(',','.'));
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change'); 
          if(result.data[0]['multisatuan']==0) {
            $("select[name^='satuan']").eq(_idx).attr('disabled','disabled');
          } else {
            $("select[name^='satuan']").eq(_idx).removeAttr('disabled');            
          }                      
          $(".btn-delrow").eq(_idx).removeClass('d-none');                      

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
          
                  var harga1 = result.data[0]['hargajual'];
                  var harga2 = result.data[0]['hargajual2'];
                  var harga3 = result.data[0]['hargajual3'];
                  var harga4 = result.data[0]['hargajual4'];

                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga1,
                      text: formatNumberLocale(harga1)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga2,
                      text: formatNumberLocale(harga2)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga3,
                      text: formatNumberLocale(harga3)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga4,
                      text: formatNumberLocale(harga4)
                  }));

          switch(result.data[0]['level']){
            case '1':
              // $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);
              $("select[name^='harga']").eq(_idx).val(result.data[0]['hargajual']).trigger('change');
             
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual']);              
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;
            case '2':
              // $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual2']);
              $("select[name^='harga']").eq(_idx).val(result.data[0]['hargajual2']).trigger('change');
              
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual2']);                            
              if(result.data[0]['hargajual2']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                      
            case '3':
              // $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual3']);
              $("select[name^='harga']").eq(_idx).val(result.data[0]['hargajual3']).trigger('change');
              
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual3']);                            
              if(result.data[0]['hargajual3']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                       
            case '4':
              // $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual4']);
              $("select[name^='harga']").eq(_idx).val(result.data[0]['hargajual4']).trigger('change');
              
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual4']);                            
              if(result.data[0]['hargajual4']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                                                                 
            default:
              // $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);
              $("select[name^='harga']").eq(_idx).val(result.data[0]['hargajual']).trigger('change');
              
              $("input[name^='defharga']").eq(_idx).val(result.data[0]['hargajual']);                                
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
          }     

          if(_qty == 0) $("input[name^='qty']").eq(_idx).val(1);     
          if(result.data[0]['gudang'] != null) $("select[name^='gudangdetil']").eq(_idx).append(gudang).trigger('change');

          let jumlah = await _hitungJumlahDetil(_idx);
          let subtotal = await _hitungsubtotal();
          _hitungTotal();

          if($("select[name^='item']").eq(totalbaris-1).val() !== null) {
            _addRow();  
            _inputFormat();            
            _inputFormatLast();                                    
          }
          $("#loader-detil").addClass('d-none');            
          $("input[name^='qty']").eq(_idx).focus();                                  
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
//  Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,null,null);  
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item_persediaan`,null,null);
  Component_Select2('.gudangdetil',`${base_url}Select_Master/view_gudang`,null,null);      
  Component_Select2('.proyekdetil',`${base_url}Select_Master/view_proyek`,null,null);            
} 

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .nilaipajak, .default").val('');
  $('.select2').val('').change();
  $('#pajak').val('0').change();          
  $('#namakontak').html("");
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
  $('.datepicker').attr('disabled', true);
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
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total, .datepicker, #nomor").removeAttr('disabled');

  setTimeout(function () {
    $('#kodeitem').focus();        
  },300);
}

var _formState2 = () => {
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled'); 
  $('#baddrow').attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff"); 
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
}

var _addRow = () => {
  let newrow = " <tr>";
      newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"hidden\" name=\"sat2[]\" class=\"sat2\"><input type=\"hidden\" name=\"sat3[]\" class=\"sat3\"><input type=\"hidden\" name=\"sat4[]\" class=\"sat4\"><input type=\"hidden\" name=\"sat5[]\" class=\"sat5\"><input type=\"hidden\" name=\"sat6[]\" class=\"sat6\"><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td class=\"td-satuan\"><input type=\"hidden\" name=\"jharga1sat1[]\" class=\"jharga1sat1\"><input type=\"hidden\" name=\"jharga2sat1[]\" class=\"jharga2sat1\"><input type=\"hidden\" name=\"jharga3sat1[]\" class=\"jharga3sat1\"><input type=\"hidden\" name=\"jharga4sat1[]\" class=\"jharga4sat1\"><input type=\"hidden\" name=\"defharga[]\" class=\"defharga\"><input type=\"hidden\" name=\"nilaisat2[]\" class=\"nilaisat2\"><input type=\"hidden\" name=\"jharga1sat2[]\" class=\"jharga1sat2\"><input type=\"hidden\" name=\"jharga2sat2[]\" class=\"jharga2sat2\"><input type=\"hidden\" name=\"jharga3sat2[]\" class=\"jharga3sat2\"><input type=\"hidden\" name=\"jharga4sat2[]\" class=\"jharga4sat2\"><input type=\"hidden\" name=\"nilaisat3[]\" class=\"nilaisat3\"><input type=\"hidden\" name=\"jharga1sat3[]\" class=\"jharga1sat3\"><input type=\"hidden\" name=\"jharga2sat3[]\" class=\"jharga2sat3\"><input type=\"hidden\" name=\"jharga3sat3[]\" class=\"jharga3sat3\"><input type=\"hidden\" name=\"jharga4sat3[]\" class=\"jharga4sat3\"><input type=\"hidden\" name=\"nilaisat4[]\" class=\"nilaisat4\"><input type=\"hidden\" name=\"jharga1sat4[]\" class=\"jharga1sat4\"><input type=\"hidden\" name=\"jharga2sat4[]\" class=\"jharga2sat4\"><input type=\"hidden\" name=\"jharga3sat4[]\" class=\"jharga3sat4\"><input type=\"hidden\" name=\"jharga4sat4[]\" class=\"jharga4sat4\"><input type=\"hidden\" name=\"nilaisat5[]\" class=\"nilaisat5\"><input type=\"hidden\" name=\"jharga1sat5[]\" class=\"jharga1sat5\"><input type=\"hidden\" name=\"jharga2sat5[]\" class=\"jharga2sat5\"><input type=\"hidden\" name=\"jharga3sat5[]\" class=\"jharga3sat5\"><input type=\"hidden\" name=\"jharga4sat5[]\" class=\"jharga4sat5\"><input type=\"hidden\" name=\"nilaisat6[]\" class=\"nilaisat6\"><input type=\"hidden\" name=\"jharga1sat6[]\" class=\"jharga1sat6\"><input type=\"hidden\" name=\"jharga2sat6[]\" class=\"jharga2sat6\"><input type=\"hidden\" name=\"jharga3sat6[]\" class=\"jharga3sat6\"><input type=\"hidden\" name=\"jharga4sat6[]\" class=\"jharga4sat6\"><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      
      // newrow += "<td class=\"td-harga\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"muang input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
      newrow += "<td><select name=\"harga[]\" class=\"harga hargaBarang form-control form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
     
      newrow += "<td class=\"td-diskon\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"muang input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
      newrow += "<td class=\"td-persen\"><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";      
      newrow += "<td class=\"td-jumlah\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"muang input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td class=\"d-none\"><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\" style=\"resize:none\"></textarea></td>";
      newrow += "<td class=\"d-none\"><input type=\"hidden\" name=\"noref[]\" class=\"noref\"><input type=\"hidden\" name=\"idrefdet[]\" class=\"idrefdet\"><input type=\"text\" name=\"refnodetil[]\" class=\"refnodetil form-control form-control-sm\" autocomplete=\"off\" readonly></td>";      
      newrow += "<td class=\"td-gudang\"><select name=\"gudangdetil[]\" class=\"gudangdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";      
      newrow += "<td class=\"d-none\"><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";      
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow d-none\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-trash text-secondary\"></i></a></td>";
      newrow += "</tr>";

  $('#tdetil tbody').append(newrow);

  if($(".mainright").hasClass("d-none")) {
    $("#th-nama").css("width","260px");                      
    $("#th-satuan").removeClass("d-none");
    $("#th-jumlah").removeClass("d-none");
    $("#th-diskon").removeClass("d-none");                            
    $("#th-persen").removeClass("d-none");
    $("#th-gudang").removeClass("d-none");      

    $(".td-satuan").removeClass("d-none");
    $(".td-jumlah").removeClass("d-none");      
    $(".td-diskon").removeClass("d-none");                            
    $(".td-persen").removeClass("d-none");
    $(".td-gudang").removeClass("d-none");
    $(".muang").removeClass("d-none");                      
  } else {
    $("#th-nama").css("width","160px");                      
    $("#th-satuan").addClass("d-none");                      
    $("#th-jumlah").addClass("d-none");
    $("#th-diskon").addClass("d-none");                            
    $("#th-persen").addClass("d-none");
    $("#th-gudang").addClass("d-none");

    $(".td-satuan").addClass("d-none");
    $(".td-jumlah").addClass("d-none");      
    $(".td-diskon").addClass("d-none");                            
    $(".td-persen").addClass("d-none");
    $(".td-gudang").addClass("d-none");
    $(".muang").addClass("d-none");    
  }  
}

/**/

var _getlistitem = () => {
  //alert(kategori);return;
  let kategori = $("#idkategori").val();
  let search = $("#cariitem").val();  
  $("#loader-item").removeClass('d-none');
  $("#item-list").html("");

  $.ajax({ 
    "url"    : base_url+"Datatable_Master/view_item_pos",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "kategori="+kategori+"&search="+search,
    "cache"  : false,
    "error"  : function(){
      $("#loader-item").addClass('d-none');      
      console.log('Gagal mengambil data item');
      return;
    },
    "success" : function(result) {
      let row = 0;
      let html = "";
//      alert(result.data[0]['nama']);
      $.each(result.data, function() {
        let _gambar = 'no_image.png';
        if(!result.data[row]['gambar']=='null' || !result.data[row]['gambar']=='') _gambar = result.data[row]['gambar'];

        html = `<button id="${result.data[row]['id']}" type="button" value="${result.data[row]['kode']}" title="${result.data[row]['kode']}  ${result.data[row]['nama']}" class="btn-iteml btn bg-light mx-1 my-2" style="height: 140px">
                  <div class="d-flex flex-column" style="max-width: 90px;width:90px;">
                    <img src="${base_url}assets/dist/img/${_gambar}" width="90" height="70" alt="${result.data[row]['nama']}" class="img-rounded">
                    <span class="overflow-hidden text-bold" style="max-height:45px;font-size:11px;font-family:'tahoma';line-height:1;">${result.data[row]['nama']}</span>
                  </div>
                </button>`;
        $("#item-list").append(html);
        row++;
      });            
      $("#loader-item").addClass('d-none');      
      return;
    }
  })   
}

var _getlistkategori = () => {
  //alert(kategori);return;
  $.ajax({ 
    "url"    : base_url+"Datatable_Master/view_kategori_pos",       
    "type"   : "POST", 
    "dataType" : "json", 
    "cache"  : false,
    "error"  : function(){
      console.log('Gagal mengambil data kategori item');
      return;
    },
    "success" : function(result) {
      let row = 0;
      let html = "";
//      alert(result.data[0]['nama']);
      $.each(result.data, function() {
        html = `<button id="kategori-${result.data[row]['id']}" type="button" value="${result.data[row]['id']}" title="${result.data[row]['nama']}" class="btn-kategoril btn btn-success bg-white mx-1 my-1" style="height: 140px">
                  <div class="d-flex flex-column align-items-center" style="max-width: 70px;width:70px;">
                    <img src="${base_url}assets/dist/img/no_image.png" width="70" height="70" alt="${result.data[row]['nama']}" class="img-rounded">
                    <span class="text-sm overflow-hidden" style="max-height:45px">${result.data[row]['nama']}</span>
                  </div>
                </button>`;
        $("#category-list").append(html);
        row++;
      });            
      return;
    }
  })   
}

var _tambahItem = (id) => {
      if(id=="") return;

      let totalbaris = $(".item").length;

      $.ajax({ 
        "url"    : base_url+"PJ_Penjualan_Tunai/get_item_kode", 
        "type"   : "POST", 
        "data"   : "id="+id+"&kontak="+$("#idkontak").val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(){
          parent.window.toastr.error('Error : Data item tidak valid ...');
          $("#loader-detil").addClass('d-none');          
          $("#kodeitem").val('');                                            
          $("#kodeitem").focus();                                  
          return;
        },
        "success"  : async function(result) {
          $("#loader-detil").addClass('d-none');           
          if(result.data.length == 0) {
              parent.window.toastr.error('Error : Data item tidak ditemukan ...');
              $("#kodeitem").val('');                                            
              $("#kodeitem").focus();                                             
              return;
          } else {

              let satuan = $("<option selected='selected'></option>")
                            .val(result.data[0]['idsatuan'])
                            .text(result.data[0]['namasatuan']),
                    item = $("<option selected='selected'></option>")
                            .val(result.data[0]['id'])
                            .text(result.data[0]['nama']),
                  gudang = $("<option selected='selected'></option>")
                            .val(result.data[0]['idgudang'])
                            .text(result.data[0]['gudang']),                            
                 cekItem = null;          

              for(let i=0;i<totalbaris;i++){
                  if($("select[name^='item']").eq(i).val() == result.data[0]['id']){
                      $("input[name^='qty']").eq(i).val(Number($("input[name^='qty']").eq(i).val().split('.').join('').toString().replace(',','.')) + 1);                                                
                      await _hitungJumlahDetil(i);
                      cekItem = 1;
                      break;
                  }
              }

              if(cekItem !== 1) {
                  _getSatuanItem(result.data[0]['id'], totalbaris-1);
                  $("select[name^='item']").eq(totalbaris-1).append(item).trigger('change');          
                  $("select[name^='satuan']").eq(totalbaris-1).append(satuan).trigger('change'); 
                  if(result.data[0]['multisatuan']==0) {
                    $("select[name^='satuan']").eq(totalbaris-1).attr('disabled','disabled');
                  } else {
                    $("select[name^='satuan']").eq(totalbaris-1).removeAttr('disabled');            
                  }                      
                  $(".btn-delrow").eq(totalbaris-1).removeClass('d-none');                                                   
		
                  $("input[name^='sat2']").eq(totalbaris-1).val(result.data[0]['idsatuan2']);              
                  $("input[name^='sat3']").eq(totalbaris-1).val(result.data[0]['idsatuan3']);              
                  $("input[name^='sat4']").eq(totalbaris-1).val(result.data[0]['idsatuan4']);
                  $("input[name^='sat5']").eq(totalbaris-1).val(result.data[0]['idsatuan5']);
                  $("input[name^='sat6']").eq(totalbaris-1).val(result.data[0]['idsatuan6']);                    
                  $("input[name^='nilaisat2']").eq(totalbaris-1).val(result.data[0]['konversi2']);              
                  $("input[name^='nilaisat3']").eq(totalbaris-1).val(result.data[0]['konversi3']);              
                  $("input[name^='nilaisat4']").eq(totalbaris-1).val(result.data[0]['konversi4']);       
                  $("input[name^='nilaisat5']").eq(totalbaris-1).val(result.data[0]['konversi5']);       
                  $("input[name^='nilaisat6']").eq(totalbaris-1).val(result.data[0]['konversi6']);                           
                  $("input[name^='jharga1sat1']").eq(totalbaris-1).val(result.data[0]['hargajual']);                            
                  $("input[name^='jharga2sat1']").eq(totalbaris-1).val(result.data[0]['hargajual2']);                            
                  $("input[name^='jharga3sat1']").eq(totalbaris-1).val(result.data[0]['hargajual3']);                            
                  $("input[name^='jharga4sat1']").eq(totalbaris-1).val(result.data[0]['hargajual4']);
                  $("input[name^='jharga1sat2']").eq(totalbaris-1).val(result.data[0]['sat2hargajual1']);                            
                  $("input[name^='jharga2sat2']").eq(totalbaris-1).val(result.data[0]['sat2hargajual2']);                            
                  $("input[name^='jharga3sat2']").eq(totalbaris-1).val(result.data[0]['sat2hargajual3']);                            
                  $("input[name^='jharga4sat2']").eq(totalbaris-1).val(result.data[0]['sat2hargajual4']);
                  $("input[name^='jharga1sat3']").eq(totalbaris-1).val(result.data[0]['sat3hargajual1']);                            
                  $("input[name^='jharga2sat3']").eq(totalbaris-1).val(result.data[0]['sat3hargajual2']);                            
                  $("input[name^='jharga3sat3']").eq(totalbaris-1).val(result.data[0]['sat3hargajual3']);                            
                  $("input[name^='jharga4sat3']").eq(totalbaris-1).val(result.data[0]['sat3hargajual4']);                                                                                
                  $("input[name^='jharga1sat4']").eq(totalbaris-1).val(result.data[0]['sat4hargajual1']);                            
                  $("input[name^='jharga2sat4']").eq(totalbaris-1).val(result.data[0]['sat4hargajual2']);                            
                  $("input[name^='jharga3sat4']").eq(totalbaris-1).val(result.data[0]['sat4hargajual3']);                            
                  $("input[name^='jharga4sat4']").eq(totalbaris-1).val(result.data[0]['sat4hargajual4']);
                  $("input[name^='jharga1sat5']").eq(totalbaris-1).val(result.data[0]['sat5hargajual1']);                            
                  $("input[name^='jharga2sat5']").eq(totalbaris-1).val(result.data[0]['sat5hargajual2']);                            
                  $("input[name^='jharga3sat5']").eq(totalbaris-1).val(result.data[0]['sat5hargajual3']);                            
                  $("input[name^='jharga4sat5']").eq(totalbaris-1).val(result.data[0]['sat5hargajual4']);
                  $("input[name^='jharga1sat6']").eq(totalbaris-1).val(result.data[0]['sat6hargajual1']);                            
                  $("input[name^='jharga2sat6']").eq(totalbaris-1).val(result.data[0]['sat6hargajual2']);                            
                  $("input[name^='jharga3sat6']").eq(totalbaris-1).val(result.data[0]['sat6hargajual3']);                            
                  $("input[name^='jharga4sat6']").eq(totalbaris-1).val(result.data[0]['sat6hargajual4']);

                  $("#lvlharga").val(result.data[0]['level']);     
                  
                  // var harga1 = result.data[0]['hargajual'];
                  // var harga2 = result.data[0]['hargajual2'];
                  // var harga3 = result.data[0]['hargajual3'];
                  // var harga4 = result.data[0]['hargajual4'];
                  var harga1 = result.data[0]['hargajual'];
                  var harga2 = result.data[0]['hargajual2'];
                  var harga3 = result.data[0]['hargajual3'];
                  var harga4 = result.data[0]['hargajual4'];

                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga1,
                      text: formatNumberLocale(harga1)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga2,
                      text: formatNumberLocale(harga2)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga3,
                      text: formatNumberLocale(harga3)
                  }));
                  $("select[name^='harga']").eq(totalbaris-1).append($('<option>', {
                      value: harga4,
                      text: formatNumberLocale(harga4)
                  }));

                  switch(result.data[0]['level']){
                    case '1':
                      // $("input[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual']);
                      $("select[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual']).trigger("change");
                      
                      $("input[name^='defharga']").eq(totalbaris-1).val(result.data[0]['hargajual']);              
                      if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(totalbaris-1).attr('placeholder','0,00');
                      break;
                    case '2':
                      // $("input[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual2']);
                      $("select[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual2']).trigger("change");
                      
                      $("input[name^='defharga']").eq(totalbaris-1).val(result.data[0]['hargajual2']);                            
                      if(result.data[0]['hargajual2']==0) $("input[name^='harga']").eq(totalbaris-1).attr('placeholder','0,00');
                      break;                                      
                    case '3':
                      // $("input[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual3']);
                      $("select[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual3']).trigger("change");
                      
                      $("input[name^='defharga']").eq(totalbaris-1).val(result.data[0]['hargajual3']);                            
                      if(result.data[0]['hargajual3']==0) $("input[name^='harga']").eq(totalbaris-1).attr('placeholder','0,00');
                      break;                       
                    case '4':
                      // $("input[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual4']);
                      $("select[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual4']).trigger("change");
                      
                      $("input[name^='defharga']").eq(totalbaris-1).val(result.data[0]['hargajual4']);                            
                      if(result.data[0]['hargajual4']==0) $("input[name^='harga']").eq(totalbaris-1).attr('placeholder','0,00');
                      break;                                                                                 
                    default:
                      // $("input[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual']);
                      $("select[name^='harga']").eq(totalbaris-1).val(result.data[0]['hargajual']).trigger("change");
                      
                      $("input[name^='defharga']").eq(totalbaris-1).val(result.data[0]['hargajual']);                                
                      if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(totalbaris-1).attr('placeholder','0,00');
                  }     

                  $("input[name^='qty']").eq(totalbaris-1).val(1);                            
                  if(result.data[0]['gudang'] != null) $("select[name^='gudangdetil']").eq(totalbaris-1).append(gudang).trigger('change');
                  await _hitungJumlahDetil(totalbaris-1);
                  _addRow();  
                  _inputFormat();       
                  _inputFormatLast();                        
              }

              await _hitungsubtotal();
              _hitungTotal();
              $("#kodeitem,#cariitem").val('');                                            
              return;                                
          }
      } 
      });    
}

// Untuk menambahkan item
function formatNumberLocale(number) {
  return new Intl.NumberFormat('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(number);
}

/* CRUD
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkontak').val()=='' || $('#idkontak').val()==0){
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }

    let totalbaris = $(".item").length;
    for(let i=0;i<totalbaris;i++){
      if(totalbaris==1){
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
          // if(Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
          if(Number($("select[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
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
          if($("select[name^='gudangdetil']").eq(i).val()=='' || $("select[name^='gudangdetil']").eq(i).val()==null){
            parent.window.Swal.fire({
                title: `Gudang pada baris ke-${i+1} harus diisi !`,
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
      } else {
          if($("select[name^='item']").eq(i).val() != '' && $("select[name^='item']").eq(i).val() != null){
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
            // if(Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
            if(Number($("select[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
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
            if($("select[name^='gudangdetil']").eq(i).val()=='' || $("select[name^='gudangdetil']").eq(i).val()==null){
              parent.window.Swal.fire({
                  title: `Gudang pada baris ke-${i+1} harus diisi !`,
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
      }  
    }

    if(Number($("#tbayar").val().split('.').join('').toString().replace(',','.'))==0){
      $('#tbayar').attr('data-title','Pembayaran harus diisi !');
      $('#tbayar').tooltip('show');
      $('#bpembayaran').focus();
      return 0;                                  
    }      
    if(Number($("#tsisa").val().split('.').join('').toString().replace(',','.'))*-1 > 0){
      $('#tsisa').attr('data-title','Jumlah pembayaran belum memenuhi total transaksi !');
      $('#tsisa').tooltip('show');
      $('#bpembayaran').focus();
      return 0;                                  
    }      

    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/deletedata", 
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
        _addRow();
        _inputFormat();
        _inputFormatLast();                        
        _formState1();
        $("#idkontak").val($("#ikontakpos").val());
        $("#kontak").val($("#ikontakposkode").val());    
        $("#namakontak").html($("#ikontakposnama").val());  
        $('#pajak').val($("#ipajakpos").val()).change();                
        parent.window.toastr.success("Transaksi berhasil dihapus");                  
        return;
      } else {        
        parent.window.toastr.error(result);      
        return;
      }
    } 
  })  
}

var _cetakStruk = (nomor) => {
      let opt = $("#icetakpos").val();
      if(opt==0){
        $("#hasilPdf").attr('src',`${base_url}Laporan/preview/page-pos/${nomor}`);
        setTimeout(function(){
                window.frames["hasilPdf"].focus();
                window.frames["hasilPdf"].print();
            },2000);      
      } else {
        parent.window.$(".modal-header").removeClass("bg-primary");
        parent.window.$(".modal-header").addClass("bg-secondary"); 
        parent.window.$("#modalsize").addClass("modal-lg");            
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cetak Struk");
        parent.window.$("#modaltrigger").val("iframe-page-pos");
        parent.window.$(".main-modal-body").html(`<object id="pStruk" data="${base_url}Laporan/preview/page-pos/${nomor}#toolbar=1&navpanes=1&zoom=150%" type="application/pdf" width="100%" height="500"></object>`);
      }
}

var _saveData = () => {

  let id = $("#id").val(),
      idtunda = $("#idtunda").val(),  
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      kontak = $("#idkontak").val(),
      karyawan = $("#idkaryawan").val(),      
      catatan = $("#catatan").val(),      
      pajak = $("#pajak").val(),
      status = $("#status").val(),            
      detil = [];

  $("select[name^='item']").each(function(index,element){  
    if(this.value != null && this.value != ''){
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               
              //  harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               harga:Number($("select[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
               gudang:$("select[name^='gudangdetil']").eq(index).val()               
             });
    }
  });

  detil = JSON.stringify(detil);

  const totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
        totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
        totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
        totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.')),
        totalbayar = Number($("#tbayar").val().split('.').join('').toString().replace(',','.')),
        totalsisa = Number($("#tsisa").val().split('.').join('').toString().replace(',','.')),
        pin = $("#pin").val(),
        cash = $("#bayarcash").val(),
        debit = $("#bayardebit").val(),
        debitbank = $("#bayardebitbank").val(),        
        debitno = $("#bayardebitno").val(),
        diskon = $("#bayardiskon").val(),        
        credit = $("#bayarcredit").val(),
        creditbank = $("#bayarcreditbank").val(),        
        creditno = $("#bayarcreditno").val();                        

  var rey = new FormData();

  rey.set('id',id);
  rey.set('idtunda',idtunda);  
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('karyawan',karyawan);
  rey.set('catatan',catatan);    
  rey.set('pajak',pajak);
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('totalpajak',totalpajak);            
  rey.set('totaltrans',totaltrans);  
  rey.set('totalbayar',totalbayar);            
  rey.set('totalsisa',totalsisa);                
  rey.set('cash',cash);
  rey.set('pin',pin);
  rey.set('debit',debit);
  rey.set('debitbank',debitbank);
  rey.set('debitno',debitno);
  rey.set('credit',credit);      
  rey.set('diskon',diskon);        
  rey.set('creditbank',creditbank);                            
  rey.set('creditno',creditno);                                                                                                                        
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/savedata", 
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
          parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
          _clearForm();
          _addRow();
          _inputFormat();          
          _inputFormatLast();                                  
          _formState1();
          _getjumlahtunda();

          $("#idkontak").val($("#ikontakpos").val());
          $("#kontak").val($("#ikontakposkode").val());    
          $("#namakontak").html($("#ikontakposnama").val());  
          $('#pajak').val($("#ipajakpos").val()).change();
          setTimeout(function () {
            _cetakStruk(result.nomor);            
            $("#kodeitem").focus();
          }, 200);
          return;
      }else if (result.pesan=='invalid_rfid'){
        alert('RFID Tidak Terdeteksi atau Belum Tersetting');
        return;
      }else if (result.pesan=='invalid_pin'){
        alert('PIN Salah Mohon Periksa Pin Anda');
        return;
      }else if (result.pesan=='invalid_saldo'){
        alert('Mohon Maaf Saldo Kurang, silahkan isi Saldo terlebih Dahulu');
        return;
      }else if (result.pesan=='limit_reached'){
        alert('Mohon Maaf Transaksi Mencapai Limit, silahkan menghubungi ADMIN terlebih Dahulu');
        return;
      }                     
    } 
  })
}

var _getDataTunda = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/getdatatunda",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data tunda penjualan tunai !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result){
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

        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);        
        $('#idkaryawan').val(result.data[0]['idkaryawan']);
        $('#karyawan').val(result.data[0]['karyawan']);        
        $('#catatan').val(result.data[0]['catatan']);        

        var rows = 0, _tqty = 0, _tsubtotal = 0, _tpajak = 0, _ttrans = 0, _tbayar = 0, _tsisa = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']),
              _gudangd = $("<option selected='selected'></option>").val(result.data[rows]['idgudang']).text(result.data[rows]['gudang']);                        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');  
          if(result.data[0]['multisatuan']==0) {
            $("select[name^='satuan']").eq(rows).attr('disabled','disabled');
          } else {
            $("select[name^='satuan']").eq(rows).removeAttr('disabled');            
          }                      
          $(".btn-delrow").eq(rows).removeClass('d-none');                       

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

          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
         
          // $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("select[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ",")).trigger('change');
          
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("select[name^='gudangdetil']").eq(rows).append(_gudangd).trigger('change');                        

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
          if(result.data[0]['pajak']=='2'){
            _ttrans = _tsubtotal;
          }else{
            _ttrans = _tsubtotal + _tpajak;
          }
          _tbayar = Number(result.data[0]['tbayar']);            
          _tsisa = _tbayar - _ttrans;

          $('#tqty').val(_tqty.toString().replace('.',','));   
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
          $('#tpajak').val(_tpajak.toString().replace('.',','));               
          $('#ttrans,#ttrans2').val(_ttrans.toString().replace('.',',')); 
          $('#tbayar').val(_tbayar.toString().replace('.',','));
          $('#tsisa').val(_tsisa.toString().replace('.',','));
          $('#bayarcash').val(result.data[0]['bayarcash']);
          $('#bayardiskon').val(result.data[0]['bayardiskon']);          
          $('#bayardebit').val(result.data[0]['bayardebit']);          
          $('#bayardebitbank').val(result.data[0]['bayardebitbank']);
          $('#bayardebitbankn').val(result.data[0]['bayardebitbankn']);            
          $('#bayardebitno').val(result.data[0]['bayardebitno']);                                      
          $('#bayarcredit').val(result.data[0]['bayarcredit']);
          $('#bayarcreditbank').val(result.data[0]['bayarcreditbank']);          
          $('#bayarcreditbankn').val(result.data[0]['bayarcreditbankn']);                    
          $('#bayarcreditno').val(result.data[0]['bayarcreditno']);

        _addRow();
        _inputFormat();
        _inputFormatLast();                                

        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

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
        $('#idkaryawan').val(result.data[0]['idkaryawan']);
        $('#karyawan').val(result.data[0]['karyawan']);        
        $('#catatan').val(result.data[0]['catatan']);                
        $('#namakontak').html(result.data[0]['kontak']);
        $('#pajak').val(result.data[0]['pajak']).change();
        $('#status').val(result.data[0]['status']);        
        $("#lvlharga").val(result.data[0]['level']);          

        var rows = 0, _tqty = 0, _tsubtotal = 0, _tpajak = 0, _ttrans = 0, _tbayar = 0, _tsisa = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']),
              _gudangd = $("<option selected='selected'></option>").val(result.data[rows]['idgudang']).text(result.data[rows]['gudang']);                        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');  

          if(result.data[0]['multisatuan']==0) {
            $("select[name^='satuan']").eq(rows).attr('disabled','disabled');
          } else {
            $("select[name^='satuan']").eq(rows).removeAttr('disabled');            
          }                      

          $(".btn-delrow").eq(rows).removeClass('d-none');                       

          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          
          // $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("select[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ",")).trigger('change');

          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("select[name^='gudangdetil']").eq(rows).append(_gudangd).trigger('change');                        

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
          if(result.data[0]['pajak']=='2'){
            _ttrans = _tsubtotal;
          }else{
            _ttrans = _tsubtotal + _tpajak;
          }
          _tbayar = Number(result.data[0]['tbayar']);            
          _tsisa = _tbayar - _ttrans;

          $('#tqty').val(_tqty.toString().replace('.',','));   
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
          $('#tpajak').val(_tpajak.toString().replace('.',','));               
          $('#ttrans,#ttrans2').val(_ttrans.toString().replace('.',',')); 
          $('#tbayar').val(_tbayar.toString().replace('.',','));
          $('#tsisa').val(_tsisa.toString().replace('.',','));
          $('#bayarcash').val(result.data[0]['bayarcash']);
          $('#bayardiskon').val(result.data[0]['bayardiskon']);          
          $('#bayardebit').val(result.data[0]['bayardebit']);          
          $('#bayardebitbank').val(result.data[0]['bayardebitbank']);
          $('#bayardebitbankn').val(result.data[0]['bayardebitbankn']);            
          $('#bayardebitno').val(result.data[0]['bayardebitno']);                                      
          $('#bayarcredit').val(result.data[0]['bayarcredit']);
          $('#bayarcreditbank').val(result.data[0]['bayarcreditbank']);          
          $('#bayarcreditbankn').val(result.data[0]['bayarcreditbankn']);                    
          $('#bayarcreditno').val(result.data[0]['bayarcreditno']);

        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");
        }

        _addRow();
        _inputFormat();
        _inputFormatLast();                                

        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

      }
}

var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi penjualan tunai !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result){
      _tampildata(result);
    } 
  })
}

var _getjumlahtunda = () => {

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/get_jumlah_tunda",       
    "type"   : "POST", 
    "dataType" : "json", 
    "cache"  : false,
    "error"  : function(){
      console.log('Error mengambil jumlah transaksi yang ditunda...');
      return;
    },
    "success" : function(result){
      $("#amtpending").html(result.data[0]['jumlah']);
    } 
  })
}

var _getDataTransaksiNomor = (nomor) => {

  if(nomor=='' || nomor==null) return;    

  $.ajax({ 
    "url"    : base_url+"PJ_Penjualan_Tunai/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "nomor="+nomor,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');                  
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi penjualan tunai !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {
      _tampildata(result);
    } 
})

}

/**/

  window._hapusbaris = async (obj) => {
    let totalbaris = $(".item").length;

    if($(obj).hasClass('disabled')) return;    
    $(obj).parent().parent().remove();
    await _hitungsubtotal();
    _hitungTotal();

    if(totalbaris==1) {
      _addRow();
      _inputFormat();
      _inputFormatLast();                              
    }  
  }


  $.ajax({ 
    "url"    : base_url+"Settings_Info/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "cache"  : false,
    "beforeSend" : function(){
    },        
    "error"  : function(xhr,status,error){
      parent.window.toastr.error("Error : "+ xhr.status);
      console.error(xhr.responseText);
      return;
    },
    "success" : function(result) {
        $('#ikontakpos').val(result.data[0]['ikontakpos']);           
        $('#ikontakposkode').val(result.data[0]['kontakkode']); 
        $('#ikontakposnama').val(result.data[0]['kontaknama']);
        $('#idkontak').val(result.data[0]['ikontakpos']);           
        $('#kontak').val(result.data[0]['kontakkode']); 
        $('#namakontak').html(result.data[0]['kontaknama']);
        $('#ipajakpos').val(result.data[0]['ipajakpos']);            
        $('#pajak').val(result.data[0]['ipajakpos']).trigger('change');                                               
        $('#icetakpos').val(result.data[0]['icetakpos']);
        $('#ikaryawankat').val(result.data[0]['ikaryawankatpos']);        
        $('#karyawan').attr('placeholder',result.data[0]['karyawankatpos'].charAt(0).toUpperCase() + result.data[0]['karyawankatpos'].slice(1).toLowerCase());                
        if(result.data[0]['ibarcodepos'] == 1) $("#chkBarcode").click();             
//        if(result.data[0]['ikaryawanpos'] == 1) $("#ikaryawanpos").removeClass("d-none");                     
        /**/
        return;
    } 
  })

  if(qparam.get('id')!=null){
      _clearForm();
      _formState1();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }else if(qparam.get('nomor')!=null){
      _clearForm();
      _formState1();  
      $("#notrans").val(qparam.get('nomor')).trigger('change');          
  }else {
      _clearForm();
      _addRow();
      _inputFormat();
      _inputFormatLast();                              
      _formState1();  
  }

  _getlistitem();
  _getlistkategori();  
  _getjumlahtunda();
  $("#bmodegrid").click();

});

var _hitungTotal = () => {

    let p = $('#pajak').val();
    let nilaiP = Number($('#nilaipajak').val()/100);      
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tdp = Number($('#tbayar').val().split('.').join('').toString().replace(',','.'));    
    let tpjk = 0, ttrans = 0, tsisa = 0;
    let nilaiPpn = Number($('#nilaipajak').val());     

    if(p==2){
      tpjk = (100/(100+nilaiPpn))*tsubtotal;
      tpjk = tpjk*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);            
      ttrans = tsubtotal;
      tsisa = tdp-ttrans;
    }else if(p==1){
      tpjk = tsubtotal*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);            
      ttrans = tsubtotal+tpjk;
      tsisa = tdp-ttrans;
    }else{
      tpjk = 0;
      ttrans = tsubtotal+tpjk;
      tsisa = tdp-ttrans;
    }

    tqty = tqty.toString().replace('.',',');
    tsubtotal = tsubtotal.toString().replace('.',',');            
    tpjk = tpjk.toString().replace('.',',');
    ttrans = ttrans.toString().replace('.',',');
    tdp =  tdp.toString().replace('.',',');
    tsisa = tsisa.toString().replace('.',',');

    if(tqty==0) tqty='0,00';
    if(tsubtotal==0) tsubtotal='0,00';
    if(tpjk==0) tpjk='0,00';
    if(ttrans==0) ttrans='0,00';
    if(tdp==0) tdp='0,00';
    if(tsisa==0) tsisa='0,00';

    $('#tqty').val(tqty).attr('placeholder',tqty);
    $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
    $('#tpajak').val(tpjk).attr('placeholder',tpjk);
    $('#ttrans,#ttrans2').val(ttrans).attr('placeholder',ttrans);      
    $('#tbayar').val(tdp).attr('placeholder',tdp);                  
    $('#tsisa').val(tsisa).attr('placeholder',tsisa);            

}

var _hitungJumlahDetil = (idx) => {

  let vqty = Number($("input[name^='qty']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      // vharga = Number($("input[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vharga = Number($("select[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vdiskon = Number($("input[name^='diskon']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vpersen = Number($("input[name^='persen']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vsubtotal = 0;
 
  if(vdiskon != 0) {
    if(vharga==0) return;
    vpersen = (vdiskon/vharga)*100;
    vpersen = vpersen.toFixed(2);    
    vpersen = vpersen.toString().replace('.',',');
    vsubtotal = (vharga-vdiskon)*vqty;
    vsubtotal = vsubtotal.toString().replace('.',',');
    if(vsubtotal==0) vsubtotal='0,00';
    if(vpersen==0) vpersen='0,00';
    $("input[name^='persen']").eq(idx).val(vpersen).attr('placeholder',vpersen);  
    $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
    return vsubtotal;            
  }else{
    vdiskon = (vpersen/100)*vharga;
    vpersen = vpersen.toFixed(2);        
    vsubtotal = (vharga-vdiskon)*vqty;
    vsubtotal = vsubtotal.toString().replace('.',',');  
    vdiskon = vdiskon.toString().replace('.',',');
    if(vsubtotal==0) vsubtotal='0,00';
    if(vdiskon==0) vdiskon='0,00';  
    $("input[name^='diskon']").eq(idx).val(vdiskon).attr('placeholder',vdiskon);  
    $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
    return vsubtotal;    
  }

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

var _getSatuanItem = (id, idx) => {
  $("select[name^='satuan']").eq(idx).select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "allowAddLink": false,
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
     "allowAddLink": false,
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