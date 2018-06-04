<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			$id_pais = $_POST['id_pais'];
			$id_region =  $_POST['id_region'];
			$id_provincia =  $_POST['id_provincia'];
			$id_ciudad =  $_POST['id_ciudad'];
			$id_distrito =  $_POST['id_distrito'];
			$id_barrio =  $_POST['id_barrio'];
			$id_calle =  $_POST['id_calle'];
			$id_numero =  $_POST['id_numero'];
			$id_grupo_actividad =  $_POST['id_grupo_actividad'];
			$id_actividad =  $_POST['id_actividad'];
			$id_mercado =  $_POST['id_mercado'];
			$id_ccomercial =  $_POST['id_ccomercial'];
			$id_galeria =  $_POST['id_galeria']; 
			$nombre_empresa =  $_POST['nombre_empresa'];
			$latitud_user =  $_POST['latitud_user'];
			$longitud_user =  $_POST['longitud_user']; 
			$direccion_user =  $_POST['direccion_user'];
			$radio = $_POST['radio'];
			
			$tiempo_inicio = microtime(true);
			///INSERTAR AUDITORIA
			try {
				$condiciones = " WHERE 1=1  ";
				if (is_numeric($id_pais) && $id_pais != -1) {
					$condiciones = $condiciones . " AND ID_PAIS = $id_pais";
				}
				if (is_numeric($id_region) && $id_region != -1) {
					$condiciones = $condiciones .  " AND ID_REGION = $id_region";
				}
				if (is_numeric($id_provincia) && $id_provincia != -1) {
					$condiciones = $condiciones .  " AND ID_PROVINCIA = $id_provincia";
				}
				if (is_numeric($id_ciudad) && $id_ciudad != -1) {
					$condiciones = $condiciones .  " AND ID_CIUDAD = $id_ciudad";
				}
				if (is_numeric($id_distrito) && $id_distrito != -1) {
					$condiciones = $condiciones . " AND ID_DISTRITO = $id_distrito";
				}
				if (is_numeric($id_barrio) && $id_barrio != -1) {
					$condiciones = $condiciones .  " AND ID_BARRIO = $id_barrio";
				}
				if (is_numeric($id_calle) && $id_calle != -1) {
					$condiciones = $condiciones .  " AND ID_CALLE = $id_calle";
				}
				if (is_numeric($id_numero) && $id_numero != -1) {
					$condiciones = $condiciones . " AND ID_NUMERO_CALLE = $id_numero";
				}
				
				if (is_numeric($id_mercado) && $id_mercado != -1) {
					$condiciones = $condiciones . " AND ID_MERCADO IN ( SELECT ID FROM MERCADO WHERE NOMBRE IN (SELECT NOMBRE FROM MERCADO WHERE ID = $id_mercado AND ID_CIUDAD = $id_ciudad) AND ID_CIUDAD = $id_ciudad )";
				}
				if (is_numeric($id_ccomercial) && $id_ccomercial != -1) {
					$condiciones = $condiciones . " AND ID_CENTRO_COMERCIAL IN ( SELECT ID FROM CENTRO_COMERCIAL WHERE NOMBRE IN (SELECT NOMBRE FROM CENTRO_COMERCIAL WHERE ID = $id_ccomercial AND ID_CIUDAD = $id_ciudad) AND ID_CIUDAD = $id_ciudad )";
				}
				if (is_numeric($id_galeria) && $id_galeria != -1) {
					$condiciones = $condiciones .  " AND ID_GALERIA IN ( SELECT ID FROM GALERIA WHERE NOMBRE IN (SELECT NOMBRE FROM GALERIA WHERE ID = $id_galeria AND ID_CIUDAD = $id_ciudad) AND ID_CIUDAD = $id_ciudad )";
				}				
				

				if ( (preg_match("/^[0-9]*$/", $id_grupo_actividad) == 1 || preg_match("/^[0-9,]*$/", $id_grupo_actividad) == 1)  && $id_grupo_actividad != -1) {
					$condiciones = $condiciones . " AND ( ID_GRUPO_ACTIVIDAD IN ($id_grupo_actividad)";
				}

				if ( (preg_match("/^[0-9]*$/", $id_actividad) == 1 || preg_match("/^[0-9,]*$/", $id_actividad) == 1)  && $id_actividad != -1) {
					if ($id_grupo_actividad != -1) {
						$condiciones = $condiciones . " OR ID_ACTIVIDAD IN ($id_actividad)";
					}
					else {
						$condiciones = $condiciones . " AND ID_ACTIVIDAD IN ($id_actividad)";
					} 
				}
				if ($id_grupo_actividad != -1) {
					$condiciones = $condiciones . " )";
				}
				$order_by = " ORDER BY NOMBRE";
				$busqueda_radial = "";
				if ( ( (is_numeric($latitud_user) && $latitud_user != 0) || (is_numeric($longitud_user) && $longitud_user != 0) ) && 
					is_numeric($radio) && $radio >= 0) {
					$busqueda_radial = ",( 6371 * acos(cos(radians($latitud_user)) * cos(radians(LATITUD)) * cos(radians(LONGITUD) - radians($longitud_user)) + sin(radians($latitud_user)) * sin(radians(LATITUD)))) AS DISTANCIA";
					$order_by = " HAVING DISTANCIA < $radio/1000 ORDER BY DISTANCIA";
				}
				else {
					$busqueda_radial = ",0 AS DISTANCIA";
				}
				
				$idioma = $_SESSION['idioma'];
				$stmt = "";
				$sql = "SELECT ID, NOMBRE, LATITUD, LONGITUD,
(SELECT NOMBRE FROM PAIS WHERE PAIS.ID_PAIS = EMPRESA.ID_PAIS AND ID_IDIOMA=$idioma) as PAIS,
(SELECT NOMBRE FROM REGION WHERE REGION.ID_REGION = EMPRESA.ID_REGION AND ID_IDIOMA=$idioma) as REGION,
(SELECT NOMBRE FROM PROVINCIA WHERE PROVINCIA.ID_PROVINCIA = EMPRESA.ID_PROVINCIA AND ID_IDIOMA=$idioma) as PROVINCIA,
(SELECT NOMBRE FROM CIUDAD WHERE CIUDAD.ID_CIUDAD = EMPRESA.ID_CIUDAD AND ID_IDIOMA=$idioma) as CIUDAD,
(SELECT NOMBRE FROM DISTRITO WHERE DISTRITO.ID_DISTRITO = EMPRESA.ID_DISTRITO AND ID_IDIOMA=$idioma) as DISTRITO,
(SELECT NOMBRE FROM BARRIO WHERE BARRIO.ID_BARRIO = EMPRESA.ID_BARRIO AND ID_IDIOMA=$idioma) as BARRIO,
(SELECT NOMBRE FROM CALLE WHERE CALLE.ID = EMPRESA.ID_CALLE) as CALLE,
(SELECT NOMBRE FROM NUMERO_CALLE WHERE NUMERO_CALLE.ID = EMPRESA.ID_NUMERO_CALLE) as NUMERO_CALLE,
(SELECT NOMBRE FROM ACTIVIDAD WHERE ACTIVIDAD.ID_ACTIVIDAD = EMPRESA.ID_ACTIVIDAD AND ID_IDIOMA=$idioma) as ACTIVIDAD 
$busqueda_radial
FROM EMPRESA $condiciones ";
				$sql_paginar = "";
				$nombre_empresa = trim($nombre_empresa);
				if (!empty($nombre_empresa)) {
					$sql_paginar =  $sql . "AND NOMBRE LIKE '%" . $nombre_empresa . "%' AND ACTIVA IS TRUE $order_by ";
					$sql = $sql . "AND NOMBRE LIKE ? AND ACTIVA IS TRUE $order_by ";
					$nombre_empresa = "%" . $nombre_empresa . "%";
					//echo $sql;
					//exit;
					$stmt = $conn->prepare("$sql");				
					$stmt->bind_param("s", $nombre_empresa);
				}
				else {
					$sql = $sql . " AND ACTIVA IS TRUE $order_by ";
					$sql_paginar = $sql;
					$stmt = $conn->prepare("$sql");
				}
				$_SESSION['sql_busqueda'] = $sql_paginar;
				$stmt->execute();
				$result = $stmt->get_result();
				$numero_filas = $result->num_rows;
				$_SESSION['resultado'] = $numero_filas;

				//AUDITORIA

				$tiempo_fin=microtime(true);
				$tiempo = $tiempo_fin-$tiempo_inicio;
				$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA (ID_USUARIO, TIEMPO, RESULTADO) VALUES (?,?,?);";						
				$stmt2 = $conn->prepare("$sql_insert");
				$stmt2->bind_param("iii", $_SESSION['id_usuario'], $tiempo,$numero_filas);
				$stmt2->execute();
				$insert_id = $conn->insert_id;
				$stmt2->close();
				if($insert_id != 0) {
					foreach($_POST as $nombre_campo => $valor){ 
						$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA_DETALLE (ID_AUDITORIA_BUSQUEDA, CAMPO, VALOR) VALUES (?,?,?);";
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("iss", $insert_id, $nombre_campo, $valor);
						$stmt2->execute();
						$insert_id = $conn->insert_id;
						$stmt2->close();																
					}
				}
				//echo "numero_filas - $numero_filas\n";
				$numero_paginas = ceil ($numero_filas / 10);
				$html = Util::getCabeceraBusqueda($numero_filas);
				$javascript = "";
				$mostrar = 0;
				$marca = 10;
				$paginas = Util::getPaginacionBusqueda(1, $numero_paginas);
				$html .= $paginas;
				$sql_insert = "INSERT INTO AUDITORIA_RESULTADO_BUSQUEDA (ID_AUDITORIA_BUSQUEDA, ID_EMPRESA) VALUES (?,?);";
				$bandera = 10;
				while ($row = $result->fetch_assoc()) {					
					$mostrar++;
					if ($mostrar <= 10) {
						$html .= Util::getCuerpoBusqueda($row, $mostrar, $bandera, $_SESSION['admin']);
						$bandera--;
					}
					$sql_insert = "INSERT INTO AUDITORIA_RESULTADO_BUSQUEDA (ID_AUDITORIA_BUSQUEDA, ID_EMPRESA) VALUES (?,?);";
					$stmt2 = $conn->prepare("$sql_insert");
					$stmt2->bind_param("ii", $insert_id, $row['ID']);
					$stmt2->execute();
					$stmt2->close();					
				}						
				$html .= $paginas;
				echo $html;
				exit;
			} 
			catch(Exception $e) { // ERROR
				echo "$e";			
			}
				
		}
		else { //SESION ERRONEA
			echo "Session Fallo 1";
		}
	}	
	else { //SESSION ERRONEA
		echo "Session Fallo 2";
	}
?>
