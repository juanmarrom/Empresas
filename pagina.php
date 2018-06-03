<?php
	session_start();	
	if(isset($_SESSION["busqueda"])) {
		require_once './clases/util.php';			
		Util::iniciarConexion("./conf.txt");
		$conn =  Util::getConexion();
		$respuesta = "Algo ha ido mal (1)";
		if($_SESSION["busqueda"] == session_id()) {
			try {
				$pagina_actual =  $_POST['pagina'];
				$offset =  $pagina_actual * 10;
				$sql = $_SESSION['sql_busqueda'] . " LIMIT 10 OFFSET $offset;";
				//echo $sql;
				//exit;
				$stmt = $conn->prepare("$sql");			
				$stmt->execute();
				$result = $stmt->get_result();
				$numero_filas = $result->num_rows;
				$numero_paginas = ceil ($numero_filas / 10);
				$html = "<div id='id_box_resultado_empresa' class='box-resultado-empresa'><span class='text-estandar-empresa'>Se han encontado " . $numero_filas . " empresas</span></div>";				
				$mostrar = 0;
				$marca = 10;
				$paginas = "";
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina('1')'>  1...  </span>";
				for ($i = $pagina_actual; $i <= $numero_paginas; $i++) {
					if ($i <= $pagina_actual + 9) {
    					$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(" . $i . ")'>  " . $i . "  </span>";
    				}
    				else {
    					$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(" . $i . ")'>  ..." . $numero_paginas . "</span>";
    					break;
    				}
				}
				$paginas = "<div id='id_box_resultado_pagina' class='box-resultado-pagina' >Pagina" . $paginas . "</div>";
				$html .= $paginas;
				while ($row = $result->fetch_assoc()) {					
					$mostrar++;
					if ($mostrar <= 10) {
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
												<input type='hidden' id='id_empresa_result_" . $mostrar .  "' value='" .  $row['NOMBRE'] . "'>
												<input type='hidden' id='id_distancia_result_" . $mostrar .  "' value='" .  $row['DISTANCIA'] . "'>
																	
										</div>
										<div class='box-actividad'>							
											<span class='text-estandar'>Actividad: " . $row['ACTIVIDAD'] . "</span>		
										</div>   
									 </div>
								</div>					
							</div>
						</div>";
					}				
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
