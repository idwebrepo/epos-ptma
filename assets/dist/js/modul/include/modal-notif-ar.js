$.fn.dataTable.ext.errMode = 'none';      

var tabeltransaksi = null;

$(function(){
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
        "url":base_url+"Datatable_Transaksi/view_notif_ar",
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
          { "data": "nomor" },
          { "data": "tanggal" },
          { "data": "jatuhtempo" },          
          { "data": "kontak" },
          { "data": "total" },          
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
})
