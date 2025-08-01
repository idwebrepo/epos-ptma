$(function() {    

    $('.numeric').inputmask({
        alias:'numeric',
        digits:'0',
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

    $('#size').select2({
        "theme":"bootstrap4",
        "dropdownParent": $('#modal'),
        "minimumResultsForSearch": "Infinity"     
    });

    $('#item').select2({
        "allowClear": false,
        "theme":"bootstrap4",
        "dropdownParent": $('#modal'),          
        "allowAddLink": false,                                               
        "ajax": {
            "url": base_url+"Select_Master/view_item",
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
        },
         "templateResult": itemSelect,    
    });

    function itemSelect(par){
        if(!par.id){
          return par.text;
        }
        var $par = $('<div class=\'pb-1\' style=\'border-bottom:1px dotted #86cfda;\'><span class=\'font-weight-bold\' style=\'opacity:.8;\'>'+par.kode+'</span><br/><span>'+par.text+'</span></div>');
        return $par;
    }

    $("#bPrint").click(function() {

        if($("#item").val()=="" || $("#item").val()==null) {
            $('#item').attr('data-title','Pilih item terlebih dahulu !');
            $('#item').tooltip('show');
            $('#item').focus();            
            return;
        }

        if($("#jumlah").val()=="" || $("#jumlah").val()==0) {
            $('#jumlah').attr('data-title','Jumlah tidak boleh 0 !');
            $('#jumlah').tooltip('show');
            $('#jumlah').focus();            
            return;
        }        

        var $html = `<div class="modal fade" id="modalfilter" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                      <div id="modalsize" class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-primary">
                            <h5 class="modal-title text-md" id="myModalLabel"></h5> 
                            <ul id="nav-fkontak" class="navbar-nav d-none">
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

        var iditem = $("#item").val();
        var ukuran = $("#size").val();
        var jumlah = $("#jumlah").val();

        if($('#bentuk1').is(':checked')) { 
            var bentuk = "1";
        } else {
            var bentuk = "2";
        }

        parent.window.$(".usable").html($html);
        parent.window.$("#modalfilter .modal-title").html("Print Preview Barcode");        
        parent.window.$("#modalfilter .main-modal-body").html(`<object id="objBarcode" data="${base_url}Laporan/barcode/${iditem}/${ukuran}/${jumlah}/${bentuk}#toolbar=1&navpanes=1&zoom=150%" type="application/pdf" width="100%" height="500"></object>`);                      
        parent.window.$('#modalfilter .modal-body').css('min-height','calc(100vh - 30vh)');          
        parent.window.$("#modalfilter").modal("show");  

    });    

})