$.fn.dataTable.ext.errMode = 'none';      

var activeMenu = $("#modaltrigger").val();

if (activeMenu=='iframe-page-pos') {
  $(".modal-body").css("border-color","#336666");
  $(".modal-footer").css("border-color","#336666");  
  $("#bpilihunit").removeClass("btn-primary");
  $("#bpilihunit").addClass("btn-secondary"); 
//  $("#bAddunit").addClass("d-none");   
  $("th").addClass("bg-secondary");   
} else if(activeMenu=='iframe-page-pos2'){
  $(".modal-body").css("border-color","#17a2b8");
  $(".modal-footer").css("border-color","#17a2b8");  
  $("#bpilihunit").removeClass("btn-primary");
  $("#bpilihunit").addClass("btn-info"); 
//  $("#bAddunit").addClass("d-none");   
  $("th").addClass("bg-info");   
}

var tabelunit = null,
    katId = null;

$('#contact-table').on('dblclick','tr',function(e){
  $('#contact-table').DataTable().rows(this).select();  
  restable();
});

$("#bAddUnit").click(function() {
    var $html = `<div class="modal fade" id="modalfilter" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                  <div id="modalsize" class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h5 class="modal-title text-md" id="myModalLabel"></h5> 
                        <ul id="nav-funit" class="navbar-nav d-none">
                          <li class="nav-item dropdown d-sm-inline-block">
                            <a href="javascript:void(0)" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
                              <i class="fas fa-caret-down px-2 text-light"></i>
                            </a>
                            <div class="list-kategori dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
                            </div>        
                          </li>
                        </ul>                        
                        <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true" tabindex="-1">&times;</button>
                      </div>
                      <div class="main-modal-body">
                      </div>
                    </div>
                  </div>
                </div>`;

    parent.window.$(".usable").html($html);

    $.ajax({ 
        "url"    : base_url+"Modal/form_unit", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": () => {
            parent.window.$(".loader-wrap").removeClass("d-none");                              
            parent.window.$("#modalfilter .modal-title").html("Tambah unit");
        },         
        "error": () => {
            parent.window.$(".loader-wrap").addClass("d-none");                    
            parent.window.toastr.error('Error menampilkan modal form unit...');
            return;
        },
        "success": (result) => {
            parent.window.$("#modalfilter .main-modal-body").html(result);
            parent.window.$('#modalfilter .modal-body').css('min-height','calc(100vh - 30vh)');          
            parent.window.$("#modalfilter").modal("show");
            parent.window._inputFormat();
            parent.window.$(".loader-wrap").addClass("d-none");                    
            return;
        } 
    });  
});

$("#bpilihunit").click(restable);

var _unitdatatable = function(){
  katId = $('#idkatunit').val();
  tabelunit= $('#contact-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",        
    "order": [[ 2, 'asc' ]],      
    "select":true,      
    "dom": '<"#sTable"f><"top"p>tr<"clear">',        
    "ajax": {
        "url":base_url+"Datatable_Master/view_table_unit/"+katId,
        "type":"post",
        "beforeSend": function(){
          $(".loader-wrap").addClass("d-none");            
        }                   
    },
    "deferRender": true,
    "bInfo":false,
    "aLengthMenu": [[20, 50, 100],[20, 50, 100]],
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },                              
    "columns": [
          { "data": "id" },
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          }, 
          { "data": "id" },   
          { "data": "nama" },       
    ],
    "drawCallback": function(settings, json) {
      var total = tabelunit.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }
      $('#modal input').focus();                                     
      $('#contact-table').removeClass("d-none"); 
    }        
  });    
}

var _lstkategoriunit = function(){
  $.ajax({ 
    "url"    : base_url+"Select_Master/view_kategori_unit", 
    "type"   : "POST", 
    "dataType" : "json", 
    error: function(){
      console.log('error ambil kategori unit...');
      return;
    },
    success: function(data) {
      var list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihunitaktif('All');">
                    <i id="cAll" class="cTrue fas fa-check mr-2 d-none"></i>Semua
                  </a> `;
      $('.list-kategori').append(list);

      $.each(data, function(index,element) {
        list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihunitaktif('`+element.id+`');">
                      <i id="c`+element.id+`" class="cTrue fas fa-check mr-2 d-none"></i> `+element.text+
               ` </a> `;
        $('.list-kategori').append(list);
      });
  } 
  });
  $("#nav-kunit").removeClass("d-none");  
}

function _pilihunitaktif(id){
  if(id!=='All'){
    $('#idkatunit').val(id);
  }else{
    $('#idkatunit').val('');          
  }

  $('.cTrue').addClass('d-none');
  $('#c'+id).removeClass('d-none');  
  _unitdatatable();
}       

function restable(){
  const id = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),0).data(),
      kode = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),2).data(),
      nama = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),3).data(),
      alamat = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),5).data(),     
      trigger = $('#modaltrigger').val(),
      coltrigger = $('#coltrigger').val();
    
  sincron(kode);           
  
  if(coltrigger=='vendor'){
    $("#"+trigger).contents().find("#idunit").val(id); 
    $("#"+trigger).contents().find("#unit").val(id);                     
    $("#"+trigger).contents().find("#namaunit").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#unit").focus();
  }else if(coltrigger=='unit'){
    $("#"+trigger).contents().find("#idunit").val(id); 
    $("#"+trigger).contents().find("#unit").val(kode);                     
    $("#"+trigger).contents().find("#namaunit").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#unit").focus();
  }else if(coltrigger=='unit2'){
    $("#"+trigger).contents().find("#idunit2").val(id); 
    $("#"+trigger).contents().find("#unit2").val(kode);                     
    $("#"+trigger).contents().find("#namaunit2").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#unit2").focus();          
  }else if(coltrigger=='customer'){
    $("#"+trigger).contents().find("#idunit").val(id); 
    $("#"+trigger).contents().find("#unit").val(kode);                     
    $("#"+trigger).contents().find("#namaunit").html(nama);                
    $("#"+trigger).contents().find("#alamat").val(alamat);                                                          
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#unit").focus();          
  }else if(coltrigger=='bagbeli'){
    $("#"+trigger).contents().find("#idbagbeli").val(id); 
    $("#"+trigger).contents().find("#bagbeli").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#bagbeli").focus();
  }else if(coltrigger=='salesman'){
    $("#"+trigger).contents().find("#idsalesman").val(id); 
    $("#"+trigger).contents().find("#salesman").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#salesman").focus();
  }else if(coltrigger=='baggudang'){
    $("#"+trigger).contents().find("#idbaggudang").val(id); 
    $("#"+trigger).contents().find("#baggudang").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#baggudang").focus();          
  }else if(coltrigger=='karyawan'){
    $("#"+trigger).contents().find("#idkaryawan").val(id); 
    $("#"+trigger).contents().find("#karyawan").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#karyawan").focus();          
  }                          
  return;        
}