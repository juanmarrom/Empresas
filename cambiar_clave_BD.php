<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {	
			if(isset($_POST["pass_ante"]) && isset($_POST["pass"]) && isset($_POST["pass2"])) {				
				$sql = "Select ID, PASSWORD FROM USUARIO WHERE lower(trim(LOGIN)) = lower(trim(?)) AND ACTIVO = true;";
				try {
					$stmt = $conn->prepare("$sql");
					/*i - integer
					d - double
					s - string
					b - BLOB*/
					$stmt->bind_param("s", $_SESSION['login']);
					$stmt->execute();
					$stmt->bind_result($id, $pass);
					$ret = "location:index.php?error=a";
					$cambiar = 0;
					while($stmt->fetch()) {
						if (password_verify($_POST["pass_ante"], $pass)) {
							if ($_POST["pass"] == $_POST["pass2"]) {
								$cambiar = 1;
							}
						}
						else {
							$respuesta = "Error en la clave";
						}
		   				
					}
					$stmt->close();	
					if ($cambiar == 1) {
						$nuevo_pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
						$sql_update = "UPDATE USUARIO SET PASSWORD = ? WHERE ID = ?;";						
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("si", $nuevo_pass,$id);
						$stmt2->execute();
						$stmt2->close();						
						$respuesta = "Contraseña cambiada con éxito";
					}
				} 
				catch(Exception $e) {
					session_destroy();
					header("location:index.php?error=catch"); 
				
				}	
			} 
			else {
				session_destroy();
				header("location:index.php?error=session"); 
			}

		}
		else { //SESION ERRONEA
			header("location:index.php?error=session1"); 
		}
	}	
	else { //SESSION ERRONEA
		header("location:index.php?error=session2"); 
	}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="css/main_busqueda.css">
<link rel="shortcut icon" href="http://opencampus.uols.org/theme/image.php/lasalle1314/theme/1464558442/favicon">
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
