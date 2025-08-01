<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<body>
  <script>
    function _loader() {
      if ($('#email').val() !== '' && $('#captcha').val() !== '') {
        $('.loader-wrap').removeClass('d-none');
      }
    }
  </script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/login.css'); ?>">

  <style scoped>
    @media (max-width:767px) {
      .login-brand {
        display: none;
      }
    }
  </style>

  <div class="loader-wrap d-none">
    <div class="loader">
      <div class="box-1 box"></div>
      <div class="box-2 box"></div>
      <div class="box-3 box"></div>
      <div class="box-4 box"></div>
      <div class="box-5 box"></div>
    </div>
  </div>
  <div class="container-fluid bg-light">
    <div class="row no-gutter">
      <div class="login-brand position-absolute">
        <img src="<?= app_url('assets/dist/img/logo-utama.png'); ?>" class="brand-image mt-4 ml-4">
        <span class="brand-text text-xl ml-4"><?= $app_name; ?></span>
        <a class="vendor-text text-sm ml-2">
          <?= $vendor_text; ?>
        </a>
      </div>
      <div class="d-md-flex col-lg-4 bg-secondary bg-image">
      </div>
      <div class="col-lg-2"></div>
      <div class="col-lg-4">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-12 mx-auto">
                <h3 class="login-heading">Reset Password</h3>
                <?= @$pesan; ?>
                <div class="dropdown-divider mb-4"></div>
                <form id="formReset" method="post">
                  <div class="form-label-group mb-4">
                    <input type="text" name="email" id="email" class="form-control text-sm" placeholder="&nbsp" value="" required autofocus>
                    <label class="text-sm form-label-sm" for="email">Ketik Alamat Email Disini</label>
                  </div>
                  <div id="imgcaptcha" class="form-label-group bg-white" style="width: 100%; border-radius:50px">
                    <center><?= $captcha ?></center>
                  </div>
                  <div id="textcaptcha" class="form-label-group">
                    <input type="text" class="form-control text-sm" placeholder="&nbsp" name="captcha" id="captcha" required>
                    <label class="text-sm form-label-sm" for="captcha">Ketik Kode Keamanan Diatas</label>
                  </div>
                  <div class="clearfix"></div>
                  <div class="form-label-group mt-2 mb-4">
                    <button class="btn btn-block btn-login btn-primary" name="submit" type="submit" onclick="_loader();">Submit</button>
                  </div>
                  <a href="login" class="btn btn-sm btn-light text-primary text-bold" name="login" style="float:right">Kembali Login</a>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>