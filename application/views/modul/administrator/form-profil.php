<form class='form-horizontal' method="post">
    <div class="modal-body">
	    <div id="rPassword" class="row">
	      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Password Baru</label>                    
	      <div class="col-sm-9 input-group">
	        <input type="password" class="form-control form-control-sm" id="pwd" name="pwd" autocomplete="off" data-trigger="manual" data-placement="auto">
	        <div id="dEyePwd" class="input-group-append" role="button">
	            <div class="input-group-text bg-white border-0"><i class="fa fa-eye-slash text-secondary"></i></div>
	        </div>                
	      </div>
	    </div>
    </div>
    <div class="modal-footer">
        <div class="form-group">
            <div class="col-sm-offset-3">
                <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
                <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>                
    </div>
</form>                            

<script type="text/javascript">
$(function () {
	$("#pwd").focus();

    $("#dEyePwd").click(function(){
        if($('#pwd').attr("type") == "text"){
            $('#pwd').attr('type', 'password');
            $('#dEyePwd i').addClass( "fa-eye-slash" );
            $('#dEyePwd i').removeClass( "fa-eye" );
        }else if($('#pwd').attr("type") == "password"){
            $('#pwd').attr('type', 'text');
            $('#dEyePwd i').removeClass( "fa-eye-slash" );
            $('#dEyePwd i').addClass( "fa-eye" );
        }
    });    

	$('#pwd').keydown(function(e){
		if(e.keyCode==13) { 
		  e.preventDefault();
		  $("#submit").click();		  
		}
	});

    $("#submit").click(function(){
      if (_IsValid()===0) return;

      var rey = new FormData();  
      rey.set('pass', $("#pwd").val());

      $.ajax({ 
        "url"    : base_url+"Admin_User/savepassword", 
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
        "success": async function(result) {
          if(result=='sukses'){
            $('#modal').modal('hide');                
            toastr.success("Password berhasil disimpan");                  
          } else {        
            toastr.error(result);                          
            return;
          }
          $(".loader-wrap").addClass("d-none");                                                          
        } 
      });

    });

    var _IsValid = (function(){
        if ($('#pwd').val()==''){
          $('#pwd').attr('data-title','Password harus diisi..');      
          $('#pwd').tooltip('show');
          $('#pwd').focus();
          return 0;
        }
        return 1;
    });    

})
</script>