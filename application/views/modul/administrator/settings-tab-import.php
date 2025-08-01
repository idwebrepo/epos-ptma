<script src="<?php echo base_url('assets/plugins/dropzone/dropzone.min.js'); ?>"></script>
<style>
    .dropzone .dz-preview {
        background: #f8f9fa !important;
    }

    .dz-message {
        margin-top: 0 !important;
        top: auto !important;
        cursor: pointer;
    }

    .dz-details {
        background: none !important;
        text-align: center !important;
    }

    .dropzone.dz-clickable {
        padding-bottom: 100px !important;
    }

    .dropzone.dz-started .dz-message {
        opacity: 1 !important;
    }

    .dropzone-disabled {
        cursor: no-drop;
    }

    .col-centered{
        float: none;
        margin: 0 auto;
    }

    .dz-message_disabled{
        font-size: 1rem;
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 100px;
        margin-top: -30px;
        color: #5A8DEE;
        text-align: center; 
    }
</style>

<div class="tab-pane text-sm px-2 mx-4" id="tab-import" role="tabpanel" aria-labelledby="btn-tab-import">
<section class="row mt-4">
                <div class="col-12 col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h1 class="text-md ml-4">Data Akun (COA)</h1>
                                <div style="position: absolute;top:10px;right: 25px;z-index:1">
                                    <button onclick="download('template_coa.xlsx')" title="download template COA" type="button" class="download_template btn btn-icon rounded-circle btn-light">
                                        <i class="fas fa-download text-md" style="color:#5A8DEE"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="dropzone dropzone-area bg-white" id="dropzone-coa">
                                                        <div class="text-sm text-center" id="dz-coa-result">
                                                            <p class="py-0 my-0 text-bold text-success" id="dz-coa-sukses"></p>
                                                            <p class="py-0 my-0 text-bold text-red" id="dz-coa-gagal"></p>
                                                        </div>                                                       
                                                        <div class="dz-message">
                                                        Upload File
                                                        </div>
                                                    </div>
                                                    <div class="dropzone dropzone-disabled bg-white" id="dropzone-coa-disabled" style="display: none">
                                                        <div class="dz-message_disabled">
                                                            <div style="">
                                                                <i style="font-size: 2.5rem !important;" class="fas fa-times"></i>
                                                            </div>
                                                            Tidak Tersedia
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                        
                <div class="col-12 col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h1 class="text-md ml-4">Barang & Jasa</h1>
                                <div style="position: absolute;top:10px;right: 25px;z-index:1">
                                    <button onclick="download('template_produk.xlsx')" title="download template data item" type="button" class="download_template btn btn-icon rounded-circle btn-light">
                                        <i class="fas fa-download text-md" style="color:#5A8DEE"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="dropzone dropzone-area bg-white" id="dropzone-item">
                                                        <div class="text-sm text-center" id="dz-item-result">
                                                            <p class="py-0 my-0 text-bold text-success" id="dz-item-sukses"></p>
                                                            <p class="py-0 my-0 text-bold text-red" id="dz-item-gagal"></p>
                                                        </div>                                                        
                                                        <div class="dz-message">
                                                        Upload File
                                                        </div>
                                                    </div>
                                                    <div class="dropzone dropzone-disabled bg-white" id="dropzone-item-disabled" style="display: none">
                                                        <div class="dz-message_disabled">
                                                            <div style="">
                                                                <i style="font-size: 2.5rem !important;" class="fas fa-times"></i>
                                                            </div>
                                                            Tidak Tersedia
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
</section>
<section class="row">
                <div class="col-12 col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h1 class="text-md ml-4">Kontak</h1>
                                <div style="position: absolute;top:10px;right: 25px;z-index:1">
                                    <button onclick="download('template_kontak.xlsx')" title="download template data kontak" type="button" class="download_template btn btn-icon rounded-circle btn-light">
                                        <i class="fas fa-download text-md" style="color:#5A8DEE"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="dropzone dropzone-area bg-white" id="dropzone-kontak">
                                                        <div class="dz-message">
                                                        Upload File
                                                        </div>
                                                    </div>
                                                    <div class="dropzone dropzone-disabled bg-white" id="dropzone-kontak-disabled" style="display: none;">
                                                        <div class="dz-message_disabled">
                                                            <div style="">
                                                                <i style="font-size: 3rem !important;" class="fas fa-times"></i>
                                                            </div>
                                                            Tidak Tersedia
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                  
                <div class="col-12 col-md-6 col-xs-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h1 class="text-md ml-4">Aset Tetap</h1>
                                <div style="position: absolute;top:10px;right: 25px;z-index:1">
                                    <button onclick="download('template_aset_tetap.xlsx')" title="download template data fa" type="button" class="download_template btn btn-icon rounded-circle btn-light">
                                        <i class="fas fa-download text-md" style="color:#5A8DEE"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="dropzone dropzone-area bg-white" id="dropzone-fa">
                                                        <div class="text-sm text-center" id="dz-fa-result">
                                                            <p class="py-0 my-0 text-bold text-success" id="dz-fa-sukses"></p>
                                                            <p class="py-0 my-0 text-bold text-red" id="dz-fa-gagal"></p>
                                                        </div>                                                        
                                                        <div class="dz-message">
                                                        Upload File
                                                        </div>
                                                    </div>
                                                    <div class="dropzone dropzone-disabled bg-white" id="dropzone-fa-disabled" style="display: none">
                                                        <div class="dz-message_disabled">
                                                            <div style="">
                                                                <i style="font-size: 2.5rem !important;" class="fas fa-times"></i>
                                                            </div>
                                                            Tidak Tersedia
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                              
</section>
</div>

<script>
Dropzone.autoDiscover = false

function download(url){
    window.open(base_url+'assets/download/'+url, '_blank');
}

// Dropzone COA
  let upload_coa = new Dropzone("#dropzone-coa", {
        addRemoveLinks: false,
        init: function() {
            this.on("success", function(file,response) {
                $('#dz-coa-sukses').html(`<i class="fas fa-check text-sm mr-2"></i>${response.sukses} akun berhasil disimpan`);
    
                if(response.gagal > 0) {
                    $('#dz-coa-gagal').html(`<i class="fas fa-times text-sm mr-2"></i>${response.gagal} akun gagal disimpan`);                
                } else {
                    $('#dz-coa-gagal').html("");
                }
            }); 

            this.on("maxfilesexceeded", function(file){
              this.removeAllFiles();
              this.addFile(file);
            });           
        },
        url: base_url + "Import/importcoa",
        maxFilesize: 5,
        method: "POST",
        data : "",
        uploadMultiple: false,
        maxFiles: 1,
        paramName: "file",
        dictInvalidFileType: "Type file ini tidak dizinkan",
        acceptedFiles: '.xls,.xlsx',
        removedfile: function(file) {
            //var file_ = file.name;
            file.previewElement.remove();
            // tambahkan ajax untuk menghapus file di server
            //.....
        },
  });

// Dropzone Item
  let upload_item = new Dropzone("#dropzone-item", {
        addRemoveLinks: false,
        init: function() {
            this.on("success", function(file,response) {
                $('#dz-item-sukses').html(`<i class="fas fa-check text-sm mr-2"></i>${response.sukses} item berhasil disimpan`);

                if(response.gagal > 0) {
                    $('#dz-item-gagal').html(`<i class="fas fa-times text-sm mr-2"></i>${response.gagal} item gagal disimpan`);                
                } else {
                    $('#dz-item-gagal').html("");
                }
            }); 

            this.on("maxfilesexceeded", function(file){
              this.removeAllFiles();
              this.addFile(file);
            });           
        },
        url: base_url + "Import/importitem",
        maxFilesize: 5,
        method: "POST",
        data : "",
        uploadMultiple: false,
        maxFiles: 1,
        paramName: "file",
        dictInvalidFileType: "Type file ini tidak dizinkan",
        acceptedFiles: '.xls,.xlsx',
        removedfile: function(file) {
            //var file_ = file.name;
            file.previewElement.remove();
            // tambahkan ajax untuk menghapus file di server
            //.....
        },
  });

// Dropzone FA
  let upload_fa = new Dropzone("#dropzone-fa", {
        addRemoveLinks: false,
        init: function() {
            this.on("success", function(file,response) {
                $('#dz-fa-sukses').html(`<i class="fas fa-check text-sm mr-2"></i>${response.sukses} aset tetap berhasil disimpan`);

                if(response.gagal > 0) {
                    $('#dz-fa-gagal').html(`<i class="fas fa-times text-sm mr-2"></i>${response.gagal} aset tetap gagal disimpan`);                
                } else {
                    $('#dz-fa-gagal').html("");
                }
            }); 

            this.on("maxfilesexceeded", function(file){
              this.removeAllFiles();
              this.addFile(file);
            });           
        },
        url: base_url + "Import/importfa",
        maxFilesize: 5,
        method: "POST",
        data : "",
        uploadMultiple: false,
        maxFiles: 1,
        paramName: "file",
        dictInvalidFileType: "Type file ini tidak dizinkan",
        acceptedFiles: '.xls,.xlsx',
        removedfile: function(file) {
            file.previewElement.remove();
        },
  }); 
   
</script>