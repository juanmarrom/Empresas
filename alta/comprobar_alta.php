<?php
	session_start();	
	if(isset($_SESSION["gestionar_alta"])) {
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["gestionar_alta"] == session_id()) {
			$respuesta = "Algo ha ido mal (2)";
			require_once '../clases/util.php';			
			Util::iniciarConexion("../conf.txt");
			$conn =  Util::getConexion();

			if(isset($_POST["email"]) && isset($_POST["nombre"]) && isset($_POST["ape1"]) && isset($_POST["pass"]) && isset($_POST["pass2"])) {				$respuesta = "Algo ha ido mal (3)";
				if ($_POST["pass"] == $_POST["pass2"]) {
					$respuesta = "El usuario se ha podido añadir con éxito";
					if (isset($_POST["ape2"])) {						
					}
					else {
						$_POST["ape2"] = "";
					}
					$sql = "insert into USUARIO (EMAIL, LOGIN, NOMBRE, APELLIDO_1, ES_ADMIN, ACTIVO, PASSWORD, ID_IDIOMA, APELLIDO_2)";
					$sql = $sql . " VALUES (?, ?, ?, ?, false, true, ?, 1, ?)";
					try {
						$stmt = $conn->prepare("$sql");
						/*i - integer
						d - double
						s - string
						b - BLOB*/
						$stmt->bind_param("ssssss", $_POST["email"], $_POST["email"],$_POST["nombre"], $_POST["ape1"], password_hash($_POST["pass"], PASSWORD_DEFAULT), $_POST["ape2"]);
						$stmt->execute();
						$stmt->close();
					} 
					catch(Exception $e) {
						$respuesta = "Se ha producido un error";
						if($conn->errno === 1062) $respuesta = "El usuario ya esta registrado";
						
					}
					
				}	
			}
		
		}
	} 
	else {
		session_destroy();
		header("location:gestionar_alta.php"); 
	}
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/alta.css">
</head>
<body>
<header><img src="http://opencampus.uols.org/theme/lasalle1314/pix/logo-uols-lsuniversities.png"></header>
<section>
	<div id="wrap">
		<div class="container">
			<span class="login-form-title">
				<?php echo $respuesta; ?>
			</span>
		</div>
	</div>
</section>	
<footer>
<p>©2018 LA SALLE OPEN UNIVERSITY</p>
</footer>
</body>
</html>
