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
			
			$tiempo_inicio = microtime(true);
			///INSERTAR AUDITORIA
			try {
				$condiciones = " WHERE 1=1 AND ";
				if (is_numeric($id_pais) && $id_pais != -1) {
					$condiciones = $condiciones . " ID_PAIS = $id_pais";
				}
				if (is_numeric($id_region) && $id_region != -1) {
					$condiciones = $condiciones .  " ID_REGION = $id_region";
				}
				if (is_numeric($id_provincia) && $id_provincia != -1) {
					$condiciones = $condiciones .  " ID_PROVINCIA = $id_provincia";
				}
				if (is_numeric($id_ciudad) && $id_ciudad != -1) {
					$condiciones = $condiciones .  " ID_CIUDAD = $id_ciudad";
				}
				if (is_numeric($id_distrito) && $id_distrito != -1) {
					$condiciones = $condiciones . " ID_DISTRITO = $id_distrito";
				}
				if (is_numeric($id_barrio) && $id_barrio != -1) {
					$condiciones = $condiciones .  " ID_BARRIO = $id_barrio";
				}
				if (is_numeric($id_calle) && $id_calle != -1) {
					$condiciones = $condiciones .  " ID_CALLE = $id_calle";
				}
				if (is_numeric($id_numero) && $id_numero != -1) {
					$condiciones = $condiciones . " ID_NUMERO_CALLE = $id_numero";
				}
				
				if (is_numeric($id_mercado) && $id_mercado != -1) {
					$condiciones = $condiciones . " ID_MERCADO = $id_mercado";
				}
				if (is_numeric($id_ccomercial) && $id_ccomercial != -1) {
					$condiciones = $condiciones . " ID_CENTRO_COMERCIAL = $id_ccomercial";
				}
				if (is_numeric($id_galeria) && $id_galeria != -1) {
					$condiciones = $condiciones .  " ID_GALERIA = $id_galeria";
				}				
				
				if (is_numeric($id_grupo_actividad) && $id_grupo_actividad != -1) {
					$condiciones = $condiciones . " ID_GRUPO_ACTIVIDAD = $id_grupo_actividad";
				}
				if (is_numeric($id_actividad) && $id_actividad != -1) {
					$condiciones = $condiciones . " ID_ACTIVIDAD = $id_actividad";
				}
				
				if (is_numeric($latitud_user) && $latitud_user != 0) {
					$condiciones = $condiciones . " LATITUD = $latitud_user";
				}
				if (is_numeric($longitud_user) && $longitud_user != 0) {
					$condiciones = $condiciones . " LONGITUD = $longitud_user";
				}
				$stmt = "";
				$sql = "SELECT * FROM EMPRESA $condiciones ";
				$nombre_empresa = trim($nombre_empresa);
				if (!empty($nombre_empresa)) {
					$sql = $sql . "AND NOMBRE LIKE ? AND ACTIVA IS TRUE LIMIT 10";
					$nombre_empresa = '%' . $nombre_empresa . "%";
					$stmt = $conn->prepare("$sql");				
					$stmt->bind_param("s", $nombre_empresa);
				}
				else {
					$stmt = $conn->prepare("$sql");
				}
				$stmt->execute();
				$result = $stmt->get_result();
				$número_filas = $result->num_rows;
				//echo "número_filas - $número_filas\n";
				$html = "";
				$id_resultado = array();
				while ($row = $result->fetch_assoc()) {
					$id_resultado[] = $row['ID'];
					$html .= "<div id='id_box_resultado' class='box-resultado'>						
						<div class='box-empresa'>
							<div>
								<div class='contenedor_empresa'>     		
									  <span class='texto_empresa' onclick='' lang='es'>
										" . $row['NOMBRE'] . "<i class='fas fa-globe clase_iconos'></i>
									</span>				
								</div>
								<div class='contenedor_datos_empresa'>
									<div class='clr'></div>
									<div class='box-direccion'>
										<span class='text-estandar'>Direccion: Calle pepe el de los palotes 30
										</span>							
											<a>
												<i class='fas fa-map-marker-alt clase_iconos'></i>									 				
											</a>							
									</div>
									<div class='box-actividad'>							
										<span class='text-estandar'>Actividad: LA QUE QUIERA QUE SEA</span>							
										
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
