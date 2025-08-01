
$(document).keydown(function(e){
  if(e.keyCode==123) { 
    e.preventDefault();
    $('#bsimpan').click(); 
  }        
});  

$("#calcash").click(function(){
  let diskon = Number($('#diskon').val().split('.').join('').toString().replace(',','.'))  
  let ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.'))  

  $("#cash").val(ttrans-diskon);
  _hitungtotal();  
});

$("#caldebit").click(function(){
  let diskon = Number($('#diskon').val().split('.').join('').toString().replace(',','.'))  
  let ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.'))  

  $("#debit").val(ttrans-diskon);
  _hitungtotal();  
});

$("#calcredit").click(function(){
  let diskon = Number($('#diskon').val().split('.').join('').toString().replace(',','.'))  
  let ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.'))  

  $("#credit").val(ttrans-diskon);
  _hitungtotal();  
});

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
  onBeforeMask: function (value, opts) {
    return value;
  },
  removeMaskOnSubmit:false
});

$('#bankdebit,#bankcredit').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "dropdownParent": $('#modal'),
    "ajax": {
        "url": base_url+"Select_Master/view_bank",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": function(params) {
          return {
            search: params.term
          }
        },
        "processResults": function (data, page) {
        return {
          results: data
        };
      },
    }
});

$("#cash,#debit,#credit,#diskon").on("keyup", function(e){ 
  _hitungtotal();
});    

var _hitungtotal = (function(v){
  try{
    let ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.'));
    let tdiskon = Number($('#diskon').val().split('.').join('').toString().replace(',','.'));    
    let tbulat = Number($('#tpembulatan').val().split('.').join('').toString().replace(',','.'));            
    let tcash = Number($('#cash').val().split('.').join('').toString().replace(',','.'));
    let tdebit = Number($('#debit').val().split('.').join('').toString().replace(',','.')); 
    let tcredit = Number($('#credit').val().split('.').join('').toString().replace(',','.'));     
    let ttransnet = ttrans-tdiskon+tbulat;
    let tbayar = tcash+tdebit+tcredit;
    let tsisa = tbayar-ttransnet;

    $("#ttransnet").val(ttransnet);
    $("#tbayar").val(tbayar);
    $("#tsisa").val(tsisa);

    if(tbayar == 0) $("#tbayar").attr('placeholder', '0,00');
    if(tsisa == 0) $("#tsisa").attr('placeholder', '0,00');

  } catch(err) {
    toastr.error(err);
  }
});       

var _IsValid = (function(){
  let totalbayar = Number($('#tbayar').val().split('.').join('').toString().replace(',','.'));      
  let sisa = Number($('#tsisa').val().split('.').join('').toString().replace(',','.'));

  if(!totalbayar>0){
    $('#tbayar').attr('data-title','Total bayar masih 0 !');
    $('#tbayar').tooltip('show');
    $('#cash').focus();
    return 0;        
  }
  if(sisa<0){
    $('#tsisa').attr('data-title','Jumlah pembayaran masih kurang !');
    $('#tsisa').tooltip('show');
    $('#cash').focus();
    return 0;        
  }

  return 1;
});

$("#b50").click(function(){
  $("#cash").val(50000);
  _hitungtotal();  
});

$("#b100").click(function(){
  $("#cash").val(100000);
  _hitungtotal();  
});

$("#b150").click(function(){
  $("#cash").val(150000);
  _hitungtotal();  
});

$("#b200").click(function(){
  $("#cash").val(200000);
  _hitungtotal();  
});

$("#b500").click(function(){
  $("#cash").val(500000);
  _hitungtotal();  
});

$("#bsimpan").click(function(){
  if(_IsValid()==0) return;

  let   trigger = $('#modaltrigger').val(),
        pin =  $('#pin').val(),   
        tcash = Number($('#cash').val().split('.').join('').toString().replace(',','.')),
        tdebit = Number($('#debit').val().split('.').join('').toString().replace(',','.')),                               
        tdebitno = $('#debitno').val(),
        tcreditno = $('#creditno').val(),
        tdebitbank = $('#bankdebit').val(),
        tdebitbankn = $('#bankdebit option:selected').text(),
        tcreditbank = $('#bankcredit').val(),                
        tcreditbankn = $('#bankcredit option:selected').text(),                        
        tcredit = Number($('#credit').val().split('.').join('').toString().replace(',','.')),
        tbayar = Number($('#tbayar').val().split('.').join('').toString().replace(',','.')),
        tdiskon = Number($('#diskon').val().split('.').join('').toString().replace(',','.')),        
        tsisa = Number($('#tsisa').val().split('.').join('').toString().replace(',','.'));         
                   
  $("#"+trigger).contents().find("#pin").val(pin);
  $("#"+trigger).contents().find("#bayardiskon").val(tdiskon);     
  $("#"+trigger).contents().find("#bayarcash").val(tcash);  
  $("#"+trigger).contents().find("#bayardebit").val(tdebit);
  $("#"+trigger).contents().find("#bayardebitno").val(tdebitno); 
  $("#"+trigger).contents().find("#bayardebitbank").val(tdebitbank);  
  $("#"+trigger).contents().find("#bayardebitbankn").val(tdebitbankn);                        
  $("#"+trigger).contents().find("#bayarcreditbank").val(tcreditbank);                        
  $("#"+trigger).contents().find("#bayarcreditbankn").val(tcreditbankn);                          
  $("#"+trigger).contents().find("#bayarcredit").val(tcredit);
  $("#"+trigger).contents().find("#bayarcreditno").val(tcreditno);                      
  $("#"+trigger).contents().find("#tbayartrigger").val(tbayar+tdiskon);  

  $('#modal').modal('hide');                      
});


$('#modal').on('hidden.bs.modal', function() {
  let   trigger = $('#modaltrigger').val();
  $("#"+trigger).focus();     
});  
