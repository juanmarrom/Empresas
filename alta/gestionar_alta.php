<?php
	require_once '../clases/util.php';
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
}	

?>


i	la variable correspondiente es de tipo entero
d	la variable correspondiente es de tipo double
s	la variable correspondiente es de tipo string


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
				<form>
					<span class="login-form-title">
						Alta en Web de búsqueda de empresas
					</span>
					<div class="wrap-login2">						
						<div class="wrap-input validate-input" data-validate = "Valid email is required: ex@abc.xyz">
							<input class="input-class" type="text" name="email" placeholder="Email">
						</div>
					
						<div class="wrap-input rs1 validate-input" data-validate="Password is required">
							<input class="input-class" type="text" name="nombre" placeholder="Nombre">
						</div>

						<div class="wrap-input rs1 validate-input" data-validate="Password is required">
							<input class="input-class" type="text" name="ape1" placeholder="Apellido1">
						</div>

						<div class="wrap-input rs1 validate-input" data-validate="Password is required">
							<input class="input-class" type="text" name="ape2" placeholder="Apellido2">
						</div>
					

						<div class="wrap-input rs1 validate-input" data-validate="Password is required">
							<input class="input-class" type="password" name="pass" placeholder="Contraseña">
						</div>

						<div class="wrap-input rs1 validate-input" data-validate="Password is required">
							<input class="input-class" type="password" name="pass2" placeholder="Repita contraseña">
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
