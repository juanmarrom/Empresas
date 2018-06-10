<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {			
		}
		else { //SESION ERRONEA
			header("location:index.php?error=session1"); 
		}
	}	
	else { //SESSION ERRONEA
		header("location:index.php?error=session1"); 
	}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/alta.css">
<link rel="shortcut icon" href="http://opencampus.uols.org/theme/image.php/lasalle1314/theme/1464558442/favicon">
<script>
	function validar() {
	    var pass = document.forms["alta"]["pass"].value;
	    var pass2 = document.forms["alta"]["pass2"].value;
	    if (pass != pass2) {
		alert("Las contraseñas tienen que coincidir");
			return false;
	    }
	}
</script>
</head>
<body>
<div id="wrap">
	<header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
	<div class="container">
		<div class="wrap-login">
			<form action="cambiar_clave_BD.php" method="post" onsubmit="return validar();" name="alta">
				<span class="login-form-title">
					Cambiar contraseña
				</span>
				<div class="wrap-login2">						

					<div class="wrap-input rs1">	
						<input class="input-class" type="password" name="pass_ante" placeholder="Contraseña anterior" required pattern=".{6,}" title="Al menos 6 caracteres">
					</div>

					<div class="wrap-input rs1">
						<input class="input-class" type="password" name="pass" placeholder="Nueva contraseña" required pattern=".{6,}" title="Al menos 6 caracteres">
					</div>
					<div class="wrap-input rs1">
						<input class="input-class" type="password" name="pass2" placeholder="Repita nueva contraseña" required pattern=".{6,}" title="Al menos 6 caracteres">
					</div>					
											
					<div class="container-login-form-btn">
						<button class="login-form-btn">
							Aceptar
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
