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
					$condiciones = $condiciones . " AND ID_MERCADO = $id_mercado";
				}
				if (is_numeric($id_ccomercial) && $id_ccomercial != -1) {
					$condiciones = $condiciones . " AND ID_CENTRO_COMERCIAL = $id_ccomercial";
				}
				if (is_numeric($id_galeria) && $id_galeria != -1) {
					$condiciones = $condiciones .  " AND ID_GALERIA = $id_galeria";
				}				
				
				if (is_numeric($id_grupo_actividad) && $id_grupo_actividad != -1) {
					$condiciones = $condiciones . " AND ID_GRUPO_ACTIVIDAD = $id_grupo_actividad";
				}
				if (is_numeric($id_actividad) && $id_actividad != -1) {
					$condiciones = $condiciones . " AND ID_ACTIVIDAD = $id_actividad";
				}
				$order_by = " ORDER BY NOMBRE";
				$busqueda_radial = "";
				if ( ( (is_numeric($latitud_user) && $latitud_user != 0) || (is_numeric($longitud_user) && $longitud_user != 0) ) && 
					is_numeric($radio) && $radio >= 0) {
					$busqueda_radial = ",( 6371 * acos(cos(radians($latitud_user)) * cos(radians(LATITUD)) * cos(radians(LONGITUD) - radians($longitud_user)) + sin(radians($latitud_user)) * sin(radians(LATITUD)))) AS DISTANCIA";
					$order_by = " HAVING DISTANCIA < $radio/1000 ORDER BY DISTANCIA";
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
				$nombre_empresa = trim($nombre_empresa);
				if (!empty($nombre_empresa)) {
					$sql = $sql . "AND NOMBRE LIKE ? AND ACTIVA IS TRUE $order_by ";
					$nombre_empresa = '%' . $nombre_empresa . "%";
					//echo $sql;
					//exit;
					$stmt = $conn->prepare("$sql");				
					$stmt->bind_param("s", $nombre_empresa);
				}
				else {
					$sql = $sql . " AND ACTIVA IS TRUE $order_by ";
					$stmt = $conn->prepare("$sql");
				}
				$stmt->execute();
				$result = $stmt->get_result();
				$número_filas = $result->num_rows;
				//echo "número_filas - $número_filas\n";
				$html = "";
				$javascript = "";
				$id_resultado = array();
				$mostrar = 0;
				$marca = 10;
				while ($row = $result->fetch_assoc()) {
					$mostrar++;
					$id_resultado[] = $row['ID'];
					$html .= "<div id='id_box_resultado' class='box-resultado'>						
						<div class='box-empresa'>
							<div>
								<div class='contenedor_empresa'>     		
									  <span class='texto_empresa' onclick='' lang='es'>
										" . $row['NOMBRE'] . "
										<a href='https://www.google.de/search?q=" . $row['NOMBRE'] . " " . $row['CALLE']  . " " . $row['NUMERO_CALLE']  . " " . $row['CIUDAD']  . " " . $row['REGION'] . "' target='_blank'>
											<i class='fas fa-globe clase_iconos'></i>
										</a>
									</span>				
								</div>
								<div class='contenedor_datos_empresa'>
									<div class='clr'></div>
									<div class='box-direccion'>
										<span class='text-estandar'>
										Direccion:" .  $row['CALLE'] . " " . $row['NUMERO_CALLE'] . ", " . $row['CIUDAD'] . ", " . $row['REGION'] . ", " . $row['PAIS'] . "
										</span>							
											<a href='https://www.google.com/maps?q=" . $row['NOMBRE'] . " " . $row['CALLE']  . " " . $row['NUMERO_CALLE']  . " " . $row['CIUDAD']  . " " . $row['REGION'] . "' target='_blank'>
												<i class='fas fa-map-marker-alt clase_iconos'></i>									 				
											</a>
											<input type='hidden' id='id_latitud_result_" . $mostrar .  "' value='" . $row['LATITUD'] .  "'>
											<input type='hidden' id='id_longitud_result_" . $mostrar .  "' value='" . $row['LONGITUD'] .  "'>
											<input type='hidden' id='id_empresa_result_" . $mostrar .  "' value='" .  $row['CALLE'] . " " . $row['NUMERO_CALLE'] . ", " . $row['CIUDAD'] . ", " . $row['REGION'] . ", " . $row['PAIS'] . "'>
																
									</div>
									<div class='box-actividad'>							
										<span class='text-estandar'>Actividad: " . $row['ACTIVIDAD'] . "</span>							
										
									</div>   
								 </div>
							</div>					
						</div>
					</div>";
				}						
				

				/*
				$resultado = 0;
				$tiempo_fin=microtime(true)
				$tiempo = $tiempo_fin-$tiempo_inicio;
				$_SESSION['id_usuario']
				$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA (ID_USUARIO, TIEMPO, RESULTADO VALUES (?,?,?);";						
				$stmt2 = $conn->prepare("$sql_insert");
				$stmt2->bind_param("iii", $_SESSION['id_usuario'], $tiempo,$resultado);
				$stmt2->execute();
				$insert_id = $conn->insert_id;
				$stmt2->close();
				if($insert_id === 0) {
					foreach($_POST as $nombre_campo => $valor){ 
						//$asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
						//eval($asignacion); 
						$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA_DETALLE (ID_AUDITORIA_BUSQUEDA, CAMPO, VALOR) VALUES (?,?,?);";						
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("iss", $_SESSION['id_usuario'], $nombre_campo, $valor);
						$stmt2->execute();
						$insert_id = $conn->insert_id;
						$stmt2->close();																
					}			
					foreach ($id_resultado as $id_auditoria) {
						$sql_insert = "INSERT INTO AUDITORIA_RESULTADO_BUSQUEDA (ID_AUDITORIA_BUSQUEDA, ID_EMPRESA) VALUES (?,?);";
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("iss", $_SESSION['id_usuario'], $id_auditoria);
						$stmt2->execute();
						$insert_id = $conn->insert_id;
						$stmt2->close();																
					}					
				}
				else {
					
				}	*/
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
