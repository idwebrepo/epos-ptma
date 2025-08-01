<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<body>
	<script>
		function _loader() {
			if ($('#username').val() !== '' && $('#password').val() !== '') {
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
	<div class="container-fluid">
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
							<div class="col-md-9 col-lg-9 mx-auto">
								<h3 class="login-heading">Login</h3>
								<div class="dropdown-divider mb-4"></div>
								<?= @$pesan; ?>
								<form id="formlogin" method="post">
									<div class="form-label-group">
										<input type="text" name="username" id="username" class="form-control text-sm" placeholder="Nama User" value="<?php echo @$username; ?>" required autofocus>
										<label class="text-sm form-label-sm" for="username">Nama User</label>
									</div>
									<div class="form-label-group">
										<input type="password" name="password" id="password" class="form-control text-sm" placeholder="Password" required>
										<label class="text-sm" for="password">Password</label>
									</div>
									<button class="btn btn-lg btn-primary btn-block btn-login mb-4" name="submit" type="submit" onclick="_loader();">Masuk</button>
									<a href="resetpassword" class="btn btn-light btn-sm mb-2 text-primary text-bold" id="lupa" name="lupa" style="float:right">Lupa Password ?</a>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>