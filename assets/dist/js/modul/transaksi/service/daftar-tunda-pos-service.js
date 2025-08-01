$.fn.dataTable.ext.errMode = 'none';      

var tabeltransaksi = null;

$(".modal-body").css("border-color","#17a2b8");
$(".modal-footer").css("border-color","#17a2b8");  
$("#bpilihtransaksi").removeClass("btn-primary");
$("#bpilihtransaksi").addClass("btn-info");

tabeltransaksi=$('#transaksi-table').DataTable({
  "destroy":true,
  "processing": true,
  "serverSide": true,
  "lengthChange": false,
  "searching": true,
  "ordering": true,
  "pagingType":"simple",        
  "order": [[0, 'desc' ]],      
  "select":true,      
  "dom": '<"#sTable"f><"top"p>tr<"clear">',        
  "ajax": {
      "url":base_url+"Datatable_Transaksi/view_tunda_pos_service",
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
        { "data": "tanggal" },
        { "data": "waktu" },
        { "data": "kontak" },
        { "data": "mekanik" },        
        { "data": "total" }          
  ],
  "columnDefs": [
        {
          "render": (data, type, row) => {
               data = commaSeparateNumber(data);
               data = "<span style='float:right'>"+data+"</span>";
               return data;
          },
          "targets": [6]
        }                    
  ],  
  "drawCallback": function() {
    var total = tabeltransaksi.data().count();

    if(total>0){
      $(".modal-body").removeClass("noresultfound");                                   
    }else{
      $(".modal-body").addClass("noresultfound");                                   
    }

    $('#modal input').focus();                                
    $('#transaksi-table').removeClass("d-none");   
  }    
});

$('#transaksi-table').on('dblclick','tr',function(e){
  $('#transaksi-table').DataTable().rows(this).select();              
  restable();
});

$("#bhapus").click(hapusTundaService);

$("#bpilihtransaksi").click(restable);

function restable(){
  const id = $('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data(),
      trigger = $('#modaltrigger').val();         
  $('#modal').modal('hide');        
  $("#"+trigger).contents().find("#idtunda").val(id).trigger('change');
  return;        
}

function hapusTundaService(){
  const id = $('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data();

  if(id == undefined) return;

  $.ajax({ 
      "url"    : base_url+"SV_Penjualan_Tunai/deletedatatunda", 
      "type"   : "POST", 
      "data"   : "id="+id,
      "beforeSend" : () => {
          $(".loader-wrap").removeClass("d-none");
      },
      "error": (xhr, status, error) => {
          $(".loader-wrap").addClass("d-none");
          toastr.error("Error : "+xhr.status+", "+error);      
          console.log(xhr.responseText);      
          return;
      },
      "success": (result) => {
        if (result=='sukses') {
            $(".loader-wrap").addClass("d-none");
            toastr.success("Data berhasil dihapus");                         
            $('#transaksi-table').DataTable().ajax.reload();
            return;             
        } else {
            $(".loader-wrap").addClass("d-none");
            toastr.error(result);                         
            return;                                 
        }
      },
      "complete" : function(){
          _refreshJumlahTunda();        
      } 
  })
}

function _refreshJumlahTunda(){
    $.ajax({ 
      "url"    : base_url+"SV_Penjualan_Tunai/get_jumlah_tunda",       
      "type"   : "POST", 
      "dataType" : "json", 
      "cache"  : false,
      "success" : function(result){
        let trigger = $('#modaltrigger').val();        
        $("#"+trigger).contents().find("#amtpending").html(result.data[0]['jumlah']);
      } 
    })                
}
