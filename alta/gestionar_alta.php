<?php
	session_start();
	$_SESSION['gestionar_alta']  = session_id();
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
		if (document.getElementById("id_proteccion")) {
			if(document.getElementById("id_proteccion").checked == false) {
				alert("Tiene que aceptar los requisitos de la RGPD");
				return false;
			}
		}	    
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
			<form action="comprobar_alta.php" method="post" onsubmit="return validar();" name="alta">
				<span class="login-form-title">
					Alta en Web de búsqueda de empresas
				</span>
				<div class="wrap-login2">						
					<div class="wrap-input">
						<span>Email</span>
						<input class="input-class" type="text" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
					</div>
					
					<div class="wrap-input rs1">
						<span>Nombre</span>
						<input class="input-class" type="text" name="nombre" placeholder="Nombre" required>
					</div>

					<div class="wrap-input rs1">
						<span>Apellido1</span>
						<input class="input-class" type="text" name="ape1" placeholder="Apellido1" required>
					</div>
					<div class="wrap-input rs1">
						<span>Apellido2</span>
						<input class="input-class" type="text" name="ape2" placeholder="Apellido2">
					</div>

					<div class="wrap-input rs1">
						<span>Contraseña</span>	
						<input class="input-class" type="password" name="pass" placeholder="Contraseña" required pattern=".{6,}" title="Al menos 6 caracteres">
					</div>

					<div class="wrap-input rs1">
						<span>Repita contraseña</span>
						<input class="input-class" type="password" name="pass2" placeholder="Repita contraseña" required pattern=".{6,}" title="Al menos 6 caracteres">
					</div>
											
					<div class="container-login-form-btn">
						<button class="login-form-btn">
							Aceptar
						</button>
					</div>
				</div>				
			</form>
			<br>
			<p><input type="checkbox" id="id_proteccion">
				<span class="text-estandar">
					Sus datos personales serán utilizados única y exclusivamente para poder ofrecerle este servicio de acuerdo con lo establecido RGPD. 
					Una vez esto no se cumpla, serán eliminados. Marque esta casilla si está de acuerdo. 
					En cualquier momento usted tiene derecho a establecer límites sobre el uso que hacemos de sus datos.
				</span>				
			</p>
			<br>
		</div>
	</div>
</div>	
<footer>
<p>©2018 LA SALLE OPEN UNIVERSITY</p>
</footer>
</div>
</body>
</html>
