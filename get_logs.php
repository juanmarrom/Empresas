<?php
	$conn = "";
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			if($_SESSION['admin']) {
				try {
					$query = "";
					$tipo = $_POST['tipo'];
					$user = $_POST['user'];
					$tabla = "<table class='table'>
					<thead>
					<tr>";
					if (is_numeric($tipo) && is_numeric($user)) {
										
						if ($tipo == 0) { //Login
							$query = "SELECT (SELECT CONCAT(NOMBRE, ' ', APELLIDO_1 ,' ', APELLIDO_2) FROM USUARIO WHERE ID = ID_USUARIO) as NOMBRE, CUANDO, IP, LOGIN, LOGOUT, NAVEGADOR,
								(SELECT EMAIL FROM USUARIO WHERE ID = ID_USUARIO) as EMAIL
							 FROM AUDITORIA_LOGIN ";
							$tabla .= "<td>USUARIO</td><td>CUANDO</td><td>LOGIN</td><td>LOGOUT</td><td>IP</td><td>NAVEGADOR</td></tr></thead>";
						}
						if ($tipo == 1) { // busqueda
							$idioma = $_SESSION['idioma'];
							$query = "SELECT (SELECT CONCAT(NOMBRE, ' ', APELLIDO_1 ,' ', APELLIDO_2) FROM USUARIO WHERE ID = ID_USUARIO) as NOMBRE, CUANDO, TIEMPO, RESULTADO,
							(SELECT NOMBRE FROM PAIS WHERE ID_PAIS IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_pais' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS PAIS,
							(SELECT NOMBRE FROM REGION WHERE ID_REGION IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_region' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS REGION,
							(SELECT NOMBRE FROM PROVINCIA WHERE ID_PROVINCIA IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_provincia' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS PROVINCIA,
							(SELECT NOMBRE FROM CIUDAD WHERE ID_CIUDAD IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_ciudad' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS CIUDAD,
							(SELECT NOMBRE FROM DISTRITO WHERE ID_DISTRITO IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_distrito' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS DISTRITO,
							(SELECT NOMBRE FROM BARRIO WHERE ID_BARRIO IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_barrio' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS BARRIO,
							(SELECT NOMBRE FROM CALLE WHERE ID IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_calle' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID)) AS CALLE,
							(SELECT NOMBRE FROM NUMERO_CALLE WHERE ID IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_numero' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID)) AS NUMERO,
							(SELECT NOMBRE FROM MERCADO WHERE ID IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_mercado' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID)) AS MERCADO,
							(SELECT NOMBRE FROM GALERIA WHERE ID IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_galeria' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID)) AS GALERIA,
							(SELECT NOMBRE FROM CENTRO_COMERCIAL WHERE ID IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_ccomercial' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID)) AS CCOMERCIAL,
							(SELECT NOMBRE FROM ACTIVIDAD WHERE ID_ACTIVIDAD IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_actividad' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS ACTIVIDAD,
							(SELECT NOMBRE FROM GRUPO_ACTIVIDAD WHERE ID_GRUPO_ACTIVIDAD IN (SELECT VALOR FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'id_grupo_actividad' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) AND ID_IDIOMA=$idioma) AS GRUPO_ACTIVIDAD,
							(SELECT max(VALOR) FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'nombre_empresa' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) as EMPRESA,
							(SELECT max(VALOR) FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'direccion_user' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) as DIRECCION,
							(SELECT max(VALOR) FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'radio' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) as RADIO,
							(SELECT max(VALOR) FROM AUDITORIA_BUSQUEDA_DETALLE WHERE CAMPO = 'no_activa' and ID_AUDITORIA_BUSQUEDA = AUDITORIA_BUSQUEDA.ID) as NO_ACTIVA
							FROM AUDITORIA_BUSQUEDA ";

							$tabla .= "<td>USUARIO</td><td>CUANDO</td><td>TIEMPO</td><td>ENCONTRADOS</td><td>DETALLE</td></tr></thead>";
						}

						if ($user == 0) { //todos
							$query .= " ORDER BY ID DESC LIMIT 100";
						}
						else {
							$query .= " WHERE ID_USUARIO = $user ORDER BY ID DESC LIMIT 100";
						}
						$stmt = $conn->prepare("$query");
						$stmt->execute();
						$result = $stmt->get_result();				
						$response = array();
						while ($row = $result->fetch_assoc()) {
							if ($tipo == 0) { //Login								
								$tabla .= "<tr><td>" . $row['EMAIL'] . "<br>". $row['NOMBRE'] . "</td><td>" . $row['CUANDO'] . "</td><td>" . $row['LOGIN'] . "</td><td>" . $row['LOGOUT'] . "</td><td>" . $row['IP'] . "</td><td>" . $row['NAVEGADOR'] . "</td></tr>";
							}
							if ($tipo == 1) { // busqueda	
								$detalle = "";
								if ($row['PAIS'] != "") {
									$detalle .= "País:".$row['PAIS'];			
								}
								if ($row['REGION'] != "") {
									$detalle .= ",Región:".$row['REGION'];			
								}
								if ($row['CIUDAD'] != "") {
									$detalle .= ",Ciudad:".$row['CIUDAD'];	
								}
								if ($row['CALLE'] != "") {
									$detalle .= ",Calle:".$row['CALLE'];			
								}
								if ($row['MERCADO'] != "") {
									$detalle .= ",Mercado:".$row['MERCADO'];			
								}
								if ($row['GALERIA'] != "") {
									$detalle .= ",Galería:".$row['GALERIA'];			
								}
								if ($row['CCOMERCIAL'] != "") {
									$detalle .= ",C. Comercial:".$row['CCOMERCIAL'];			
								}								
								if ($row['DIRECCION'] != "") {
									$detalle .= ",Dirección:".$row['DIRECCION'];			
								}
								if ($row['RADIO'] != "") {
									$detalle .= ",Radio:".$row['RADIO'] ." metros";			
								}
								if ($row['ACTIVIDAD'] != "") {
									$detalle .= ",Actvidad:".$row['ACTIVIDAD'];				
								}
								if ($row['GRUPO_ACTIVIDAD'] != "") {
									$detalle .= ",Grupo Actvidad:".$row['GRUPO_ACTIVIDAD'];				
								}																
								if ($row['EMPRESA'] != "") {
									$detalle .= ",Empresa:".$row['EMPRESA'];			
								}
								if ($row['NO_ACTIVA'] != "-1") {
									$detalle .= ",No activa";			
								}								
								$tabla .= "<tr><td>" . $row['NOMBRE'] . "</td><td>" . $row['CUANDO'] . "</td><td>" . $row['TIEMPO'] . " segundos</td><td>" . $row['RESULTADO'] . " empresas</td><td>" . $detalle . "</td></tr>";
							}
						}
						$tabla .= "</table>";
						$stmt->close();
						echo $tabla;
						exit;
					}
					else {
						session_destroy();
						header("location:index.php?error=ataque"); 
					}
				} 
				catch(Exception $e) {
					echo "Exception - $e\n";
				}					
			}
			else {
				session_destroy();
				header("location:index.php?error=admin"); 
			}
		}
		else {
			session_destroy();
			header("location:index.php?error=session"); 
		}
	}
	else {
		session_destroy();
		header("location:index.php?error=session"); 
	}
	exit;
?>