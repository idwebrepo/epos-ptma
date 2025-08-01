$(function () {
  bsCustomFileInput.init();

  $("#submit").click(function(){
  //alert($('#customFile')[0].files[0]);
    var rey = new FormData();    
    rey.set('file', $('#customFile')[0].files[0]);

    $.ajax({ 
      "url"    : base_url+"Admin_BackupDatabase/restore", 
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
        toastr.error("Error : "+xhr.status+" "+error);      
        console.log(xhr.responseText);      
        return;
      },
      "success": function(result) {
        console.log(result);              
        $(".loader-wrap").addClass("d-none");        
        if(result=='sukses'){
          $("#modal").modal("hide");
          toastr.success(`Database berhasil direstore`);
          return;
        }else{
          toastr.error(result);
          return;          
        }
      } 
    })
  })
});