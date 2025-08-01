var isreqbarcode = false;

setTimeout(function (){
        $('#kode').focus();
}, 500);                   

var activeMenu = $("#modaltrigger").val();

if (activeMenu=='iframe-page-pos') {
  $(".modal-body").css("border-color","#336666");
  $(".modal-footer").css("border-color","#336666");  
  $("#btutup").removeClass("btn-primary");
  $("#btutup").addClass("btn-secondary"); 
} else if(activeMenu=='iframe-page-pos2'){
  $(".modal-body").css("border-color","#17a2b8");
  $(".modal-footer").css("border-color","#17a2b8");  
  $("#btutup").removeClass("btn-primary");
  $("#btutup").addClass("btn-info"); 
}

var _clearForm = () => {
	$("#nama").val('');
  $("#alias").val('');  
	$("#harga1").val('');	
	$("#harga2").val('');	
	$("#harga3").val('');	
	$("#harga4").val('');
	$("#stok").val('');
	$("#satuan").val('');							
	$('#itemImage').attr('src', base_url+'assets/dist/img/def-img.png');          	
}

var _cariItem = (value) => {
//	alert(value);
    $.ajax({ 
      "url"    : base_url+"Master_Item/getdataalt",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+value.trim(),
      "cache"  : false,
      "beforeSend" : function(){
        $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');        
        toastr.error("Error : "+xhr.status+" "+error);
        console.error(xhr.responseText);
        $('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
      	_clearForm();
        if (typeof result.pesan !== 'undefined') {
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else {
          if(result.data[0]['row'] > 0){
	          $('#nama').val(result.data[0]['nama']);          
            $('#alias').val(result.data[0]['alias']);              
    			  $("#harga1").val(result.data[0]['hargajual1']).attr('placeholder',result.data[0]['hargajual1']);	
    			  $("#harga2").val(result.data[0]['hargajual2']).attr('placeholder',result.data[0]['hargajual2']);	
    			  $("#harga3").val(result.data[0]['hargajual3']).attr('placeholder',result.data[0]['hargajual3']);	
    			  $("#harga4").val(result.data[0]['hargajual4']).attr('placeholder',result.data[0]['hargajual4']);
    			  $("#stok").val(result.data[0]['stok']).attr('placeholder',result.data[0]['stok']);			  
	          $('#satuan').val(result.data[0]['satuan']);          
	          if(result.data[0]['gambar1'] !== null && result.data[0]['gambar1'] !== ""){
	            $('#itemImage').attr('src', base_url+'assets/dist/img/'+result.data[0]['gambar1']);
	          } else {
	            $('#itemImage').attr('src', base_url+'assets/dist/img/def-img.png');          	
	          }
          }
	      $('.loader-wrap').addClass('d-none');                            
        }
      }
    })
}

$('.numeric').inputmask({
alias:'numeric',
digits:'2',
digitsOptional:false,
isNumeric: true,      
prefix:'',
groupSeparator:".",
placeholder: '0',
radixPoint:",",
autoGroup:true,
autoUnmask:true,
rightAlign:false,
onBeforeMask: function (value, opts) {
  return value;
},
removeMaskOnSubmit:false
});

$('#kode').on('keypress', function(e){

if(e.keyCode==13) { 
  return;
}

if ($(this).val().length >= 6 && isreqbarcode == false) {
  setTimeout(async function(){
      isreqbarcode = true;
      await _cariItem($("#kode").val());        
      $("#kode").focus();
      isreqbarcode = false;
  },200);
}

});  
