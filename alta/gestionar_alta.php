<?php
	session_start();
	$_SESSION['gestionar_alta']  = session_id();

/*	require_once '../clases/util.php';
	echo 'La fecha de hoy es: ' . Util::getFecha();
	Util::iniciarConexion("../conf.txt");
	$conn =  Util::getConexion();
$sql = "select N_SECTOR FROM DATOS_BRUTOS GROUP BY N_SECTOR;";
$result = $conn->query($sql);
$id_tabla = 1;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id_tabla: " . $id_tabla . " - N_SECTOR: " . $row["N_SECTOR"]. "<br>";
    }
} 
else {
    echo "Consulta sin resultados";
}	
Util::cerrarConexion();
echo "cerrada<br>";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id_tabla: " . $id_tabla . " - N_SECTOR: " . $row["N_SECTOR"]. "<br>";
    }
} 
else {
    echo "Consulta sin resultados";

i	la variable correspondiente es de tipo entero
d	la variable correspondiente es de tipo double
s	la variable correspondiente es de tipo string


}*/	

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
			<div class="wrap-login">
				<form action="comprobar_alta.php" method="post">
					<span class="login-form-title">
						Alta en Web de búsqueda de empresas
					</span>
					<div class="wrap-login2">						
						<div class="wrap-input validate-input">
							<span>Email</span>
							<input class="input-class" type="text" name="email" placeholder="Email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
						</div>
						
						<div class="wrap-input rs1 validate-input">
							<span>Nombre</span>
							<input class="input-class" type="text" name="nombre" placeholder="Nombre" required>
						</div>

						<div class="wrap-input rs1 validate-input">
							<span>Apellido1</span>
							<input class="input-class" type="text" name="ape1" placeholder="Apellido1" required>
						</div>
						<div class="wrap-input rs1 validate-input">
							<span>Apellido2</span>
							<input class="input-class" type="text" name="ape2" placeholder="Apellido2">
						</div>

						<div class="wrap-input rs1 validate-input">
							<span>Contraseña</span>	
							<input class="input-class" type="password" name="pass" placeholder="Contraseña" required pattern=".{6,}" title="Al menos 6 caracteres">
						</div>

						<div class="wrap-input rs1 validate-input">
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
			</div>
		</div>
	</div>
</section>	
<footer>
<p>©2018 LA SALLE OPEN UNIVERSITY</p>
</footer>
</body>
</html>
