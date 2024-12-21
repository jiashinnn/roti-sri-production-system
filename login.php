<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include('./db_connect.php');
?>

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">

	<title>Login | Production System</title>
	<?php include('./header.php'); ?>
	<?php
	if (isset($_SESSION['login_id']))
		header("location:index.php?page=home");
	?>

</head>
<style>
	body {
		width: 100%;
		height: calc(100%);
		position: fixed;
		top: 0;
		left: 0;
		background-image: url('images/login_background.png');
		background-repeat: no-repeat;
		background-attachment: fixed;
		background-size: 100% 100%;
	}

	main#main {
		width: 100%;
		height: calc(100%);
		display: flex;
	}
</style>

<body>
	<main id="main">
		<div class="align-self-center w-100">
			<div class="logo" style="margin-left: 250px">
				<a href="index.php"><img src="images/logo_name_w.png"></a>
			</div>
			<div class="card col-md-4" style="margin-left: 180px;">
				<div class="card-body">
					<form id="login-form">
						<div class="form-group">
							<label for="email" class="control-label text-dark">Email</label>
							<input type="text" id="email" name="email" class="form-control form-control-sm" required>
						</div>
						<div class="form-group">
							<label for="password" class="control-label text-dark">Password</label>
							<input type="password" id="password" name="password" class="form-control form-control-sm" required>
						</div>
						<center><button type="submit" class="btn-sm btn-block btn-wave col-md-4 btn-primary">Login</button></center>
						<a href="forgot-password.php" class="auth-link text-black">Forgot password?</a>
					</form>
				</div>
			</div>
		</div>
	</main>

	<a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

	<script>
		$('#login-form').submit(function(e) {
			e.preventDefault();
			$('button[type="submit"]').attr('disabled', true).text('Logging in...');
			if ($(this).find('.alert-danger').length > 0) {
				$(this).find('.alert-danger').remove();
			}
			$.ajax({
				url: 'ajax.php?action=login',
				method: 'POST',
				data: $(this).serialize(),
				error: function(err) {
					console.log(err);
					$('button[type="submit"]').removeAttr('disabled').text('Login');
				},
				success: function(resp) {
					try {
						resp = JSON.parse(resp);
						if (resp.status === 'success') {
							location.href = 'index.php?page=home';
						} else {
							$('#login-form').prepend('<div class="alert alert-danger">' + resp.message + '</div>');
							$('button[type="submit"]').removeAttr('disabled').text('Login');
						}
					} catch (e) {
						console.log('Error parsing response:', resp);
						$('button[type="submit"]').removeAttr('disabled').text('Login');
					}
				}
			});
		});
	</script>
</body>

</html>