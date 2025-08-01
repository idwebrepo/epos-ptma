setTimeout(function (){
  $('#protocol').focus();
}, 500);

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});

$('.numeric').inputmask({
  alias:'numeric',
  digits:'0',
  digitsOptional:false,
  isNumeric: true,      
  prefix:'',
  rightAlign: !1,
  groupSeparator:"",
  placeholder: '',
  radixPoint:"",
  autoGroup:true,
  autoUnmask:true,
  onBeforeMask: function (value, opts) {
    return value;
  },
  removeMaskOnSubmit:false
});   

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#protocol').val()==''){
      $('#protocol').attr('data-title','Email protocol harus diisi !');      
      $('#protocol').tooltip('show');
      $('#protocol').focus();
      return 0;
    }

    if ($('#host').val()==''){
      $('#host').attr('data-title','Email host harus diisi !');      
      $('#host').tooltip('show');
      $('#host').focus();
      return 0;
    }

    if ($('#port').val()==''){
      $('#port').attr('data-title','Port harus diisi !');      
      $('#port').tooltip('show');
      $('#port').focus();
      return 0;
    }

    if ($('#sender').val()==''){
      $('#sender').attr('data-title','Email harus diisi !');      
      $('#sender').tooltip('show');
      $('#sender').focus();
      return 0;
    }

    if ($('#passmail').val()==''){
      $('#passmail').attr('data-title','Password email harus diisi !');      
      $('#passmail').tooltip('show');
      $('#passmail').focus();
      return 0;
    }

    return 1;
});

var _saveData = (function(){

  const rey = new FormData();  
  rey.set('protocol', $("#protocol").val());
  rey.set('host', $("#host").val());  
  rey.set('port', $("#port").val());
  rey.set('email', $("#sender").val());
  rey.set('password', $("#passmail").val());

  $.ajax({ 
    "url"    : base_url+"Settings_Info/savemailconfig", 
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
        toastr.success("Konfigurasi email berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});