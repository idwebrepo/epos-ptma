function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}     
setTimeout(function (){
        $('#keluhan').focus();
    }, 500);                
$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
/* End Form Init */

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        idinv = $("#idinv").val(),
        keluhan = $("#keluhan").val(),
        diagnosa = $("#diagnosa").val(),
        temuan = $("#temuan").val();

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('keluhan',keluhan);
  rey.set('diagnosa',diagnosa);
  rey.set('temuan',temuan);  

  $.ajax({ 
    "url"    : base_url+"SV_Perintah_Perbaikan/ubahcatatan", 
    "type"   : "POST", 
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide'); 
        window.open(`${base_url}Laporan/preview/page-pos2sppuk/${idinv}`);                       
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});

function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"SV_Perintah_Perbaikan/getdatacatatan",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');        
        toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
        console.error(xhr.responseText);
        $('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        if (typeof result.pesan !== 'undefined') {
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else {
          $('#idinv').val(id);                      
          $('#id').val(result.data[0]['id']);            
          $('#keluhan').val(result.data[0]['keluhan']);
          $('#diagnosa').val(result.data[0]['diagnosa']);
          $('#temuan').val(result.data[0]['temuan']);
          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}