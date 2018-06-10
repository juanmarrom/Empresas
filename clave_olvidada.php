<?php
	session_start();
	$_SESSION['clave_olvidada']  = session_id();
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="shortcut icon" href="http://opencampus.uols.org/theme/image.php/lasalle1314/theme/1464558442/favicon">

</head>
<body>
<div id="wrap">
	<header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
	<div class="container">
		<div class="wrap-login">
			<form action="reset_pass.php" method="post">
				<span class="login-form-title">
					Resetear contraseña
				</span>
				<div class="wrap-login2">						

					<div class="wrap-input rs1">	
						<input class="input-class" type="text" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
					</div>	
											
					<div class="container-login-form-btn">
						<button class="login-form-btn">
							Resetear
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>	
<footer>
<p>©2018 LA SALLE OPEN UNIVERSITY</p>
</footer>
</div>
</body>
</html>
