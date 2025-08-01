/* ========================================================================================== */
/* File Name : table-penjualan-tunai-service.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

var tabel = null;

$(function() {

	$.fn.dataTable.ext.errMode = 'none';      

	Component_Inputmask_Date('.datepicker');
	Component_Scrollbars('.tab-wrap','scroll','scroll');

	if(!parent.window.$(".loader-wrap").hasClass("d-none")){
		parent.window.$(".loader-wrap").addClass("d-none");
	}

	this.addEventListener('contextmenu', function(e){
		e.preventDefault();
	});

	$("#dtgldari").click(function() {
	  $("#tgldari").focus();
	});

	$("#dtglsampai").click(function() {
	  $("#tglsampai").focus();
	});	

	var clearFilter = () => {
		$('#all-chk').prop('checked', false);
		$('#tgldari').datepicker('setDate','01-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
		$('#idkontak,#kontak').val('');
	}

	clearFilter();

	tabel=$('#table').DataTable({
		"processing": true,
		"serverSide": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"pagingType":"simple",    
		"order": [[0, 'desc' ]],
		"select":true,  
		"dom": '<"top"pi>tr<"clear">',
		"ajax": {
		    "url":base_url+"Datatable_Transaksi_Full/view_penjualan_tunai_service",
		    "type":"post",
	        "data": function(data){
	          data.kontak = $('#idkontak').val();
	          data.dari = $('#tgldari').val();
	          data.sampai = $('#tglsampai').val();          
	        }	                       	                       	                       
		},
		"deferRender": true,
		"bInfo":true,    
		"aLengthMenu": datapage,    
		"columns": [
		      { "data": "id" },
	          { "orderable": false,
	            "render": function ( data, type, row ) {
	                var html ="<input type='checkbox' id='"+row.id+"' name='row[]' class='chk mt-1' value='"+row.id+"'>";
	                html = html + "<i class='fas fa-caret-right text-sm ml-2'></i>";
	                return html;
	                }
	          },	      
		      { "data": "nomor" },
		      { "data": "tanggal" },
		      { "data": "idkontak" },		      
		      { "data": "kontak" },
		      { "data": "uraian" },
			  { "data": "total", 
				"className": 'aright',		      
				"render": (data, type, row, meta) => {
					data = accounting.formatMoney(data);
					return data;		        		
				}		      	
			  },		      
		],
		"columnDefs": [
		      {
		        "targets": [4],
		        "visible": false,
		        "searchable": true
		      },                              		      	                      
		],		
	    "drawCallback": function() {
	      var total = tabel.data().count();

	      if(total>0){
	        $(".tab-wrap").removeClass("noresultfound-x");                                   
	      }else{
	        $(".tab-wrap").addClass("noresultfound-x"); 
	      }
	      
		  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
		    parent.window.$(".loader-wrap").addClass("d-none");
		  }

		  if($(".table-utils").hasClass("d-none")){	  
			  $(".table-utils").removeClass("d-none");
		  }	  
		}                    
	});

	new $.fn.dataTable.ColResize(tabel, {
	  isEnabled: true,
	  hoverClass: 'dt-colresizable-hover',
	  hasBoundCheck: true,
	  minBoundClass: 'dt-colresizable-bound-min',
	  maxBoundClass: 'dt-colresizable-bound-max',
	  isResizable: function(column) { 
	    return column.idx !== 1; 
	  },
	  onResize: function(column) {
	  },
	  onResizeEnd: function(column, columns) {
	  }
	});

	$("#badd").click(function() {
		parent.window.$('.loader-wrap').removeClass('d-none');
        parent.window.$('.content-wrapper').IFrame('createTab', 'POS Service', 'page/pos2', 'page-pos2', true, 'POS Service');		
	});

	$("#bedit").click(function() {
        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

        if(typeof id=='undefined') return;

		parent.window.$('.loader-wrap').removeClass('d-none');
        parent.window.$('.content-wrapper').IFrame('createTab', 'POS Service', 'page/pos2/?id='+id, 'page-pos2', true, 'POS Service');				
	});

	$('#table').on('dblclick','tr',function(e){
		e.preventDefault();
		e.stopPropagation();
		tabel.rows(this).select();
		$('#bedit').click();
	})

	$("#bdelete").click(function() {
		let data = [];
		let totalcek = $("input:checkbox[name='row[]']:checked").length;
		let totalrow = $("input:checkbox[name='row[]']").length; 
		let checktabel = document.getElementsByName('row[]');

		if(totalcek>0){
			parent.window.Swal.fire({
				title: `Anda yakin akan menghapus ${totalcek} transaksi ?`,
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonText: `Iya`,
			}).then((result) => {
				if (result.isConfirmed) {
					for(var i=0;i<totalrow;i++){
						var inp=checktabel[i];
						var inpno=$('#table').DataTable().cell($('#table').DataTable().rows(i),2).data();
					    if(inp.checked==true){
					      	data.push({
					      		id: inp.value,
					      		nomor: inpno
					      	});    
					    }
					}


					data = JSON.stringify(data);

					var rey = new FormData();
					rey.set('data',data);
					
//					alert(data);return;

				    $.ajax({ 
				        "url"    : base_url+"SV_Penjualan_Tunai/deletedatamulti", 
				        "type"   : "POST", 
				        "data"   : rey,
				        "processData": false,
				        "contentType": false,
				        "cache"    : false,
				        "beforeSend" : () => {
				            parent.window.$(".loader-wrap").removeClass("d-none");
				        },
				        "error": (xhr, status, error) => {
				            parent.window.$(".loader-wrap").addClass("d-none");
				            parent.window.toastr.error("Error : "+xhr.status+", "+error);      
				            console.log(xhr.responseText);      
				            return;
				        },
				        "success": (result) => {
				        	if (result=='sukses') {
					            parent.window.$(".loader-wrap").addClass("d-none");
					            parent.window.toastr.success("Transaksi berhasil dihapus");      				            
							    _reloaddatatable();					            
					            return;		        	
				        	} else {
					            parent.window.$(".loader-wrap").addClass("d-none");
					            parent.window.toastr.error(result);      				            
					            return;		        					        		
				        	}
				        } 
				    })
				}
			})
		}else{		
			const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

			if(typeof id=='undefined') return;

			parent.window.Swal.fire({
				title: 'Anda yakin akan menghapus '+id+'?',
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonText: `Iya`,
			}).then((result) => {
				if (result.isConfirmed) {
				  _deleteData();      
				}
			})
		}
	});

	$("#bprint").click(() => {
		let totalcek = $("input:checkbox[name='row[]']:checked").length;

		if(totalcek>0){
			let data = '';
			let totalrow = $("input:checkbox[name='row[]']").length; 
			let checktabel = document.getElementsByName('row[]');

			for(var i=0;i<totalrow;i++){
				var inp=checktabel[i];
				//var inpno=$('#table').DataTable().cell($('#table').DataTable().rows(i),2).data();
			    if(inp.checked==true){
			    	data += inp.value + '/';
			    }
			}

			data = data.slice(0,-1);
			window.open(`${base_url}Laporan/multipreview/page-pos2/${data}`);
		} else {		

			const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

	        if(typeof id=='undefined') return;

			window.open(`${base_url}Laporan/preview/page-pos2/${id}`);
		}
	});	

	$("#bprintsppuk").click(() => {
		let totalcek = $("input:checkbox[name='row[]']:checked").length;

		if(totalcek>0){
			let data = '';
			let totalrow = $("input:checkbox[name='row[]']").length; 
			let checktabel = document.getElementsByName('row[]');

			for(var i=0;i<totalrow;i++){
				var inp=checktabel[i];
				//var inpno=$('#table').DataTable().cell($('#table').DataTable().rows(i),2).data();
			    if(inp.checked==true){
			    	data += inp.value + '/';
			    }
			}

			data = data.slice(0,-1);

			if(totalcek==1){
				parent.window.Swal.fire({
					title: `Apakah Anda Ingin Mengubah Catatan Mekanik Terlebih Dahulu ?`,
					showDenyButton: false,
					showCancelButton: true,
					confirmButtonText: `Iya`,
				}).then((result) => {
					if (result.isConfirmed) {
						// Form Ubah Catatan Mekanik
						  $.ajax({ 
						    "url"    : base_url+"Modal/form_catatan_mekanik", 
						    "type"   : "POST", 
						    "dataType" : "html",
						    "beforeSend": function(){      
						      parent.window.$(".loader-wrap").removeClass("d-none");
						      parent.window.$("#modalsize").addClass("modal-lg");
						      parent.window.$(".modal").modal("show");    						                      
						      parent.window.$(".modal-title").html("Ubah Catatan Mekanik");
						      parent.window.$("#modaltrigger").val("iframe-page-pos2data");        
						    },     
						    "error": function(){
						      parent.window.$(".loader-wrap").addClass("d-none");      
						      console.log('error menampilkan form catatan mekanik...');
						      return;
						    },
						    "success": async function(result) {
						      await parent.window.$(".main-modal-body").html(result);
						      await parent.window._getData(data);
						      parent.window.$(".loader-wrap").addClass("d-none");
						    } 
						  });					
					} else {
						window.open(`${base_url}Laporan/multipreview/page-pos2sppuk/${data}`);
					}
				})
			} else {
				window.open(`${base_url}Laporan/multipreview/page-pos2sppuk/${data}`);
			}
		} else {		

			const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

	        if(typeof id=='undefined') return;

			parent.window.Swal.fire({
				title: `Apakah Anda Ingin Mengubah Catatan Mekanik Terlebih Dahulu ?`,
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonText: `Iya`,
			}).then((result) => {
				if (result.isConfirmed) {
					// Form Ubah Catatan Mekanik
					  $.ajax({ 
					    "url"    : base_url+"Modal/form_catatan_mekanik", 
					    "type"   : "POST", 
					    "dataType" : "html",
					    "beforeSend": function(){      
					      parent.window.$(".loader-wrap").removeClass("d-none");
					      parent.window.$("#modalsize").addClass("modal-lg");					      
					      parent.window.$(".modal").modal("show");                  
					      parent.window.$(".modal-title").html("Ubah Catatan Mekanik");
					      parent.window.$("#modaltrigger").val("iframe-page-pos2data");        
					    },     
					    "error": function(){
					      parent.window.$(".loader-wrap").addClass("d-none");      
					      console.log('error menampilkan form catatan mekanik...');
					      return;
					    },
					    "success": async function(result) {
					      await parent.window.$(".main-modal-body").html(result);
					      await parent.window._getData(id);
					      parent.window.$(".loader-wrap").addClass("d-none");
					    } 
					  });					
				} else {
					window.open(`${base_url}Laporan/preview/page-pos2sppuk/${id}`);					
				}
			})
		}
	});

	$("#brefresh").click(function() {
		_reloaddatatable();
	});	

	$("#bfilter").click(function() {
		if($("#fDataTable").hasClass("d-none")){
			$("#table").removeClass("w-100");
			$("#table").addClass("w-75");
			$("#fDataTable").removeClass("d-none");
	        $(".noresultfound-x").css("background-position","30% 160px");                 									
		}else {
			$("#table").removeClass("w-75");
			$("#table").addClass("w-100");
			$("#fDataTable").addClass("d-none");			
	        $(".noresultfound-x").css("background-position","45% 160px");                 									
		}
	});

	$("#bmail").click(() => {
		let totalcek = $("input:checkbox[name='row[]']:checked").length;
		let flags = true;
		var kontak = "";
		var emailto = "";

		if(totalcek>0){
			let data = '';
			let totalrow = $("input:checkbox[name='row[]']").length; 
			let checktabel = document.getElementsByName('row[]');

			for(var i=0;i<totalrow;i++){
				var inp=checktabel[i];
			    if(inp.checked==true){
			    	if(kontak !== '' && kontak !== $('#table').DataTable().cell($('#table').DataTable().rows(i),4).data()) flags=false;
			    	data += inp.value + '/';
					kontak=$('#table').DataTable().cell($('#table').DataTable().rows(i),4).data();							    	
					if(emailto=='') emailto = $('#table').DataTable().cell($('#table').DataTable().rows(i),4).data();
			    }
			}

			data = data.slice(0,-1);

			if(flags==false) {
				parent.window.Swal.fire({
					title: `Ada perbedaan kontak dalam transaksi yang dipilih, lanjutkan?`,
					showDenyButton: false,
					showCancelButton: true,
					confirmButtonText: `Iya`,
				}).then((result) => {
					if (result.isConfirmed) {
						_sendemail(emailto,data);
					}
				})
			} else {
				_sendemail(emailto,data);
			}
		} else {
			let id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		
			let emailto = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),4).data();

	        if(typeof id=='undefined') return;

			_sendemail(emailto,id);
		}
	});	

	var _sendemail = (emailto, data) => {
			var rey = new FormData();  
			rey.set('emailto',emailto);
			rey.set('data',data);

			$.ajax({ 
			"url"    : base_url+"Laporan/multimail/page-pos2/"+emailto+"/"+data, 
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
			  parent.window.toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
			  console.log(xhr.responseText);      
			  return;
			},
			"success": function(result) {
			  parent.window.$(".loader-wrap").addClass("d-none");        

			  if(result=='sukses'){
			    parent.window.$('#modal').modal('hide');                
			    parent.window.toastr.success("Email telah terkirim");                  
			    return;
			  } else {        
			    parent.window.toastr.error(`Email gagal dikirim. Mohon periksa hal berikut ini :<br><br> 
			    	1. Koneksi internet anda<br>
			    	2. Konfigurasi SMTP email pengirim<br>
			    	3. Alamat email kontak yang dituju`);
			    console.log(result);                          
			    return;
			  }
			} 
			});
	};		

	$("#bfilterkontak").click(function() {
		if($(this).attr('role')) {
			$.ajax({ 
				"url"    : base_url+"Modal/cari_kontak", 
				"type"   : "POST", 
				"dataType" : "html", 
				"beforeSend": function(){
				  parent.window.$(".loader-wrap").removeClass("d-none");          
				  parent.window.$(".modal").modal("show");                  
				  parent.window.$(".modal-title").html("Cari Kontak");
				  parent.window.$("#modaltrigger").val("iframe-page-pos2data");
				  parent.window.$('#coltrigger').val('vendor');   
				},
				"error": function(){
				  console.log('error menampilkan modal cari kontak...');
				  parent.window.$(".loader-wrap").addClass("d-none");          
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
			})
		}    
	})

	$('#all-chk').click(function(){
		if ($('#all-chk').is(":checked"))
		{
		   $('.chk').prop('checked', true); 
		} else {
		   $('.chk').prop('checked', false); 
		}
	});  

	$("#submitfilter").click(function() {
	  $('#all-chk').prop('checked', false);
	  $('#table').DataTable().ajax.reload();  
	  if (window.matchMedia('screen and (max-width: 768px)').matches) {
	    $("#coa-table").removeClass("w-75");
	    $("#coa-table").addClass("w-100");
	    $("#fDataTable").addClass("d-none");    
	  }  
	});

	var _reloaddatatable = () => {
		clearFilter();
		$('#table').DataTable().ajax.reload();  
	}  

	var _deleteData = (function(){
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
		const nomor = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

		if(typeof id=='undefined') return;

		$.ajax({ 
		"url"    : base_url+"SV_Penjualan_Tunai/deletedata", 
		"type"   : "POST", 
		"data"   : "id="+id+"&nomor="+nomor,
		"cache"    : false,
		"beforeSend" : function(){
		  parent.window.$(".loader-wrap").removeClass("d-none");
		},
		"error": function(xhr, status, error){
		  parent.window.$(".loader-wrap").addClass("d-none");
		  parent.window.toastr.error("Error : "+xhr.status+", "+error);      
		  console.log(xhr.responseText);      
		  return;
		},
		"success": function(result) {
		  parent.window.$(".loader-wrap").addClass("d-none");        

		  if(result=='sukses'){
		    parent.window.toastr.success("Transaksi berhasil dihapus");                  
		    _reloaddatatable();
		    return;
		  } else {        
		    parent.window.toastr.error(result);      
		    return;
		  }
		} 
		});  
	});

})