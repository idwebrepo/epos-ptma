function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}     
setTimeout(function (){
        $('#kode').focus();
    }, 500);                
$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode mata uang harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama mata uang harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    if ($('#simbol').val()==''){
      $('#simbol').attr('data-title','Simbol mata uang harus diisi !');      
      $('#simbol').tooltip('show');
      $('#simbol').focus();
      return 0;
    }    
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        simbol = $("#simbol").val();

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('simbol',simbol);  

  $.ajax({ 
    "url"    : base_url+"Master_Uang/savedata", 
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
        toastr.success("Data mata uang berhasil disimpan");  
        try{
          $('#iframe-page-matauang').contents().find('#submitfilter').click();        
        } catch(err){
          console.log(err);
        }                                
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
      "url"    : base_url+"Master_Uang/getdata",       
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
        if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form
          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#simbol').val(result.data[0]['simbol']);          

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}