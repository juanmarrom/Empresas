<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<div id="wrap">
  <header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
  <div class="container">
	<div class="limiter">
		<div class="container-login">
			<div class="wrap-login">
				<form class="login-form" action="comprobar_login.php" method="post">
					<span class="login-form-title">
						Bienvenido
					</span>
					<div class="wrap-login2">
						<div class="wrap-input">
							<input class="input-class" type="text" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
						</div>

						<div class="wrap-input rs1">
							<input class="input-class" type="password" name="pass" placeholder="Password" required>
						</div>

						<div class="container-login-form-btn">
							<button class="login-form-btn">
								Aceptar
							</button>
						</div>
					</div>
					<div class="text-center">
						<span class="txt1">
							Has olvidado tu
						</span>

						<a href="#" class="txt2 hov1">
							usuario o contraseña?
						</a>
					</div>
					
					<div class="text-center">
						<span class="txt1">
							Quieres
						</span>

						<a href="alta/gestionar_alta.php" class="txt2 hov1">
							darte de alta?
						</a>
					</div>
					<div class="text-center">
						<span class="txt3">
							<br><br><?php if(isset($_GET["error"])) echo "El usuario o la contraseña son incorrectos"; ?>
						</span>						
					</div>


				</form>
			</div>
		</div>
	</div>
  </div>
</div>
<footer>
<p>©2018 LA SALLE OPEN UNIVERSITY</p>
</footer>
</body>
</html>
