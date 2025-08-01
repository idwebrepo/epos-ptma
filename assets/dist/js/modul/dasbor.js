var openTabLedger = (param1, param2, param3) => {
  if(param2 != '' || param2 != null) {
    doOpenTab(param2,param3,param1);
  } else {
    parent.window.Swal.fire({
      title: 'Transaksi tidak bisa ditampilkan',
      showDenyButton: false,
      showCancelButton: false,
      confirmButtonText: `Tutup`,
    });          
  }
}

var keluar = () => {
    $('.loader-wrap').removeClass('d-none');
    location.href="login/aksi_logout";      
}

var _hideSidebarFocused = () => {
    if ($('.main-sidebar').hasClass('sidebar-focused')) {
      $('.main-sidebar').removeClass('sidebar-focused');    
    }    
    if(screen.width<768){
      $('.main-sidebar').PushMenu('collapse');
    }
}

var doOpenTab = (uniqueLink, title, transId) => {
    if($("#iframe-"+uniqueLink).length>0){
        $('.content-wrapper').IFrame('switchTab', '#tab-'+uniqueLink);
        $("#iframe-"+uniqueLink).contents().find("#notrans").val(transId).trigger('change');        
    }else{
        $('.content-wrapper').IFrame('createTab', title, uniqueLink.replace('-','/')+`/?nomor=${transId}`, uniqueLink, true, title);
    }
}

var _notifExpired = () => {
  $.ajax({ 
    "url"    : base_url+"Master_Item/getItemExpired", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $("#tExpired").html(result.data[0]['row']);
      $("#spinnerNotif").addClass("d-none");      
      return;
    } 
  });   
}

var _notifMinStok = () => {
  $.ajax({ 
    "url"    : base_url+"Master_Item/getItemMinStok", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $("#tMinimum").html(result.data[0]['row']);
      $("#spinnerNotif").addClass("d-none");
      return;
    } 
  });   
}

var _notifAR = () => {
  $.ajax({ 
    "url"    : base_url+"PJ_Faktur_Penjualan/getPiutangTempo", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $("#tAR").html(result.data[0]['row']);
      $("#spinnerNotif").addClass("d-none");      
      return;
    } 
  });   
}

var _notifAP = () => {
  $.ajax({ 
    "url"    : base_url+"PB_Faktur_Pembelian/getHutangTempo", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $("#tAP").html(result.data[0]['row']);
      $("#spinnerNotif").addClass("d-none");      
      return;
    } 
  });   
}

var _notifPO = () => {
  $.ajax({ 
    "url"    : base_url+"PB_Order_Pembelian/getorderprocess", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $("#tPO").html(result.data[0]['row']);
      $("#spinnerNotif").addClass("d-none");      
      return;
    } 
  });   
}

var _totalnotif = () => {
  $.ajax({ 
    "url"    : base_url+"Dasbor/totalNotif", 
    "type"   : "POST", 
    "dataType" : "json", 
    "cache" : false,
    "beforeSend": function(){
//      $("#spinnerNotif").removeClass("d-none");
    },     
    "error": function(xhr, status, error){
      console.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
//      alert(result.data[0]['total']);
      $("#totalNotif").html(result.data[0]['total']);      
      return;
    } 
  });   
}

if((screen.width <= 767) || (window.matchMedia && window.matchMedia('only screen and (max-width: 767px)').matches))
{
    $("#footer").addClass("d-none");         
} 

toastr.options = {
    "positionClass": "toast-top-right"
};

this.addEventListener('contextmenu', function(e){
  e.preventDefault();
}); 

$(document).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
    $(e.target).tooltip('hide');
    }, 2000);
});  

$(document).on('select2:open', function() {
  this.querySelector('.select2-search__field').focus();
});  

$('#modal').on('hidden.bs.modal', function() {
    $(".usable").html("");                                        
    $("#modal .main-modal-body").html('');
    $("#modal .list-kategori").html('');
    $("#nav-kkontak").addClass("d-none");     
    $("#modalsize").removeClass("modal-lg");
    $("#modalsize").removeClass("modal-sm");     
    $(".modal-header").removeClass('bg-secondary');
    $(".modal-header").removeClass('bg-info');        
    $(".modal-header").addClass('bg-primary');        
});  

$('#modal').on('shown.bs.modal', function() {
    if(
        (screen.width <= 767) || 
        (window.matchMedia && 
         window.matchMedia('only screen and (max-width: 767px)').matches
        )
      ){
        $("#modalsize").css('min-width','100%');
        $("#modalsize").css('padding','0');            
        $("#modalsize").css('margin-left','0'); 
        $("#footer").addClass("d-none");         
      }
});  

$("#bProfil").click(function() { 
  $.ajax({ 
    "url"    : base_url+"Modal/form_profil", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){
      $(".loader-wrap").removeClass("d-none");
      $(".modal").modal("show");                  
      $(".modal-title").html("Ubah Password");
      $("#modaltrigger").val("");        
    },     
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");      
      toastr.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");            
      $(".main-modal-body").html(result); 
      return;
    } 
  });   
});

$("#bBackup").click(function() { 
  Swal.fire({
    title: 'Pastikan tidak ada kegiatan sebelum membackup data, Lanjutkan ?',
    showDenyButton: false,
    showCancelButton: true,
    confirmButtonText: `Ya`,
  }).then(function(res){
    if (res.isConfirmed) {
      $.ajax({ 
        "url"    : base_url+"Admin_BackupDatabase/backup", 
        "type"   : "POST",         
        "beforeSend": function(){
          $(".loader-wrap").removeClass("d-none");
        },     
        "error": function(xhr, status, error){
          $(".loader-wrap").addClass("d-none");      
          toastr.error("Kesalahan : "+xhr.status+", "+error);      
          return;
        },
        "success": function(result) {
          $(".loader-wrap").addClass("d-none");  
          toastr.success(`Database berhasil dibackup : ${result}`);
          window.open(`${base_url}assets/backup/${result}`,'download-backup');
          return;
        } 
      });   
    }
  });          
});

$("#bRestore").click(function() { 
  Swal.fire({
    title: 'Pastikan tidak ada kegiatan sebelum merestore database, Lanjutkan ?',
    showDenyButton: false,
    showCancelButton: true,
    confirmButtonText: `Ya`,
  }).then(function(res){
    if (res.isConfirmed) {
      $.ajax({ 
        "url"    : base_url+"Modal/form_restore", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Restore Database");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal restore db...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
//          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
    }
  });          
});

$("#bReloadNotif,#bNotif").click(function() { 
    _notifExpired();
    _notifMinStok();    
    _notifAR();
    _notifAP();
    _notifPO();
    _totalnotif();
});

$("#notifap").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/notifAP", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Hutang Jatuh Tempo");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal hutang jatuh tempo...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
});

$("#notifar").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/notifAR", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Piutang Jatuh Tempo");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal piutang jatuh tempo...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
});

$("#notifstokmin").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/notifstokminimum", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Stok Minimum");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal minimum stok...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
});

$("#notifstokexpired").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/notifstokexpired", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Stok Expired");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal stok expired...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
});

$("#notiforderpembelian").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/notiforderpembelian", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("PO Belum Selesai");
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal po belum selesai...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window.$(".loader-wrap").addClass("d-none");          
          return;
        } 
      });
});

setTimeout(function(){
  _totalnotif();
},1000);