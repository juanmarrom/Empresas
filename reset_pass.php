<?php
	session_start();	
	if(isset($_SESSION["clave_olvidada"])) {
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["clave_olvidada"] == session_id()) {
			$respuesta = "Algo ha ido mal (2)";
			require_once './clases/util.php';			
			Util::iniciarConexion("./conf.txt");
			$conn =  Util::getConexion();

			if(isset($_POST["email"])) {				
				$respuesta = "Algo ha ido mal (3)";
				$sql = "Select ID FROM USUARIO WHERE lower(trim(LOGIN)) = lower(trim(?)) AND ACTIVO = true;";
				try {
					$stmt = $conn->prepare("$sql");
					$stmt->bind_param("s", $_POST["email"]);
					$stmt->execute();
					$stmt->bind_result($id);
					$ret = "location:index.php?error=a";
					$encontrado = false;
					while($stmt->fetch()) {
						$encontrado = true;	   				
					}
					$stmt->close();	
					if ($encontrado) {		
						$nuevo_pass = password_hash("1234567", PASSWORD_DEFAULT);
						$sql_update = "UPDATE USUARIO SET PASSWORD = ? WHERE ID = ?;";						
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("si", $nuevo_pass, $id);
						$stmt2->execute();
						$stmt2->close();						
						$respuesta = "Contraseña cambiada con éxito. Recibirá un email con su nueva clave de acceso.";
						$mail = "Su nueva Contraseña es 1234567";
						//Titulo
						$titulo = "Contraseña buscador de Empresas";
						//cabecera
						$headers = "MIME-Version: 1.0\r\n"; 
						$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
						//dirección del remitente 
						$headers .= "From: juanmarrom@gmail.com\r\n";
						//Enviamos el mensaje a tu_dirección_email 
						$bool = mail($_POST["email"],$titulo,$mail,$headers);
						if($bool){
						}
						else{
						    $respuesta = "Error Mail";
						}

					}
				} 
				catch(Exception $e) {
					session_destroy();
					header("location:index.php?error=catch"); 
				
				}							
			}	
		}
		
	}
	else {
		session_destroy();
		header("location:index.php?error=session"); 
	}
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
	<div class="limiter">
		<div class="container-login">
			<div class="wrap-login">
				<span class="login-form-title">
					<?php echo $respuesta; ?>
				</span>
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
