$(function(){

	$('#resetdari').datepicker('setDate','01-mm-yy');
	$('#resetsampai').datepicker('setDate','dd-mm-yy');

	$("#dTglResetDari").click(function() {
	  $("#resetdari").focus();
	});

	$("#dTglResetSampai").click(function() {
	  $("#resetsampai").focus();
	});

	$("#bresetnomor").click(function() {
		if($("#resettipe").val()==null || $("#resettipe").val()=="") return;
			parent.window.Swal.fire({
				title: `Anda yakin akan mereset nomor transaksi ?`,
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonText: `Iya`,
			}).then((result) => {
				if (result.isConfirmed) {
					_resetnomor();
				}
			})		
	});

	var textSelect = (param) => {
		if(!param.id) return param.text;
		var $param = $('<span>'+param.kode+' - '+param.text+'</span>');
		return $param;
	}

	$('#ikaryawankatpos').select2({
	   "allowClear": true,
	   "theme":"bootstrap4",
	   "ajax": {
	      "url": base_url+"Select_Master/view_kategori_kontak",
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

	$('#resettipe').select2({
	   "allowClear": true,
	   "theme":"bootstrap4",
	   "ajax": {
	      "url": base_url+"Select_Master/view_jenis_transaksi",
	      "type": "post",
	      "dataType": "json",                                       
	      "delay": 800,
	      "data": function(params) {
	        return {
	          search: params.term,
	          nfa:null
	        }
	      },
	      "processResults": function (data, page) {
	      return {
	        results: data
	      };
	    },
	  },
	   "templateResult": textSelect,              	  
	});  

	$('#icetakpos,#ibarcodepos,#ipajakpos,#ipajakbeli,#ipajakjual,#multidivisi,#multiproyek,#multisatuan,#multikurs,#multigudang,#ikaryawanpos').select2({
	     "minimumResultsForSearch": "Infinity",                 
	     "theme":"bootstrap4"
	});

	$('#ippnbeli,#ippnjual,#ippnpos').select2({
	     "allowClear": true,
	     "theme":"bootstrap4",
		 "allowAddLink": true,
		 "addLink": "form_pajak",  
		 "linkTitle": "Pajak",	     
	     "ajax": {
	        "url": base_url+"Select_Master/view_pajak_ppn",
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
	});

	$('#ipph22beli,#ipph22jual').select2({
	     "allowClear": true,
	     "theme":"bootstrap4",
		 "allowAddLink": true,
		 "addLink": "form_pajak",  
		 "linkTitle": "Pajak",	     	     
	     "ajax": {
	        "url": base_url+"Select_Master/view_pajak_pph",
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
	});	

	$("#bclearkontak").click(function(){
		$("#idkontak").val("");
		$("#kontak").val("");		
		$("#namakontak").html("");				
	});

	$("#btn-tab-utility").click(function(){
	    $(".tab-wrap").removeClass("noresultfound-x");                                   
		$("#badd").addClass("disabled");
		$("#bedit").addClass("disabled");	
		$("#bdelete").addClass("disabled");
		$("#bsave").removeClass("disabled");
		$("#brefresh").addClass("disabled");		
	    setTimeout(function () {
	      $('#icetakpos').focus();        
	    },300);	
	});

	$("#carikontak").click(function() {
		if($(this).attr('role')) {
		  $.ajax({ 
		    "url"    : base_url+"Modal/cari_kontak", 
		    "type"   : "POST", 
		    "dataType" : "html", 
		    "beforeSend": function(){
		      parent.window.$('.loader-wrap').removeClass('d-none');
		      parent.window.$(".modal").modal("show");                  
		      parent.window.$(".modal-title").html("Cari Kontak");
		      parent.window.$("#modaltrigger").val("iframe-page-settings");
		      parent.window.$('#coltrigger').val('kontak');                
		    },
		    "error": function(){
		      parent.window.$('.loader-wrap').addClass('d-none');
		      console.log('error menampilkan modal cari kontak...');
		      return;
		    },
		    "success": function(result) {
		      parent.window.$(".main-modal-body").html(result);
		      parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
		      parent.window._lstkategorikontak();
		      parent.window._pilihkategorikontak('9'); 
		      setTimeout(function (){
		           parent.window.$('#modal input').focus();
		      }, 500);
		      return;
		    } 
		  });
		}    
	});

	var _resetnomor = () => {
	  const tipe = $("#resettipe").val(),
	        dari = $("#resetdari").val(),
	        sampai = $("#resetsampai").val();

	  var rey = new FormData();  
	  rey.set('tipe',tipe);
	  rey.set('dari',dari);
	  rey.set('sampai',sampai);

	  $.ajax({ 
	    "url"    : base_url+"Settings_Nomor/resetnomor", 
	    "type"   : "POST", 
	    "data"   : rey,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	      parent.window.$(".loader-wrap").removeClass("d-none");	    	
	    },
	    "error": function(xhr, status, error){
	      parent.window.$(".loader-wrap").addClass("d-none");	    		      
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      parent.window.$(".loader-wrap").addClass("d-none");	    		      	    	
	      if(result!=='sukses'){
	      	parent.window.toastr.error(result);
	      } else {
	      	parent.window.toastr.success("Reset nomor transaksi berhasil");	      	
	      }
	    } 
	  });
	}

  $(this).on("select2:select", "#ikaryawanpos", function(){
  	//alert(this.value);

  });

})