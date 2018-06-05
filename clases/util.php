<?php
 class Util {
    private static $conn = "";

    public static function iniciarConexion($ruta_fichero) {
		$numero_linea = 1;
		$fp = fopen($ruta_fichero, "r");
		$url = "localhost";
		$login = "root";
		$password = "";
		$base_datos = "BD_EMPRESAS";

		while(!feof($fp)) {
			$linea = fgets($fp);
			//echo $linea . "\n";
			if ($numero_linea < 5) {
				list($variable, $valor) = explode('=', $linea);
				$valor = trim($valor);
				//echo "Variable: $variable; Valor: '$valor'\n";
				if ($numero_linea == 1) {
					$url = $valor;
				}
				if ($numero_linea == 2) {
					$login = $valor;
				}
				if ($numero_linea == 3) {
					$password = $valor;
				}
				if ($numero_linea == 4) {
					$base_datos = $valor;
				}
				$numero_linea++;
			}
		}
		fclose($fp);
		self::$conn = new mysqli("$url", "$login", "$password", "$base_datos");
		self::$conn->query("SET NAMES utf8");
		return self::$conn;
    } 

    public static function getConexion(){
	return self::$conn;
    } 

    public static function cerrarConexion(){
	self::$conn->close();
    } 

    public static function getFecha(){
       $anio = date('Y');
       $mes = date('m');
       $dia = date('d');
       return $dia . '/' . $mes . '/' . $anio;
    }
 
    public static function getHora(){
       $hora = date('H');
       $minutos = date('i');
       $segundos = date('s');
       return $hora . ':' . $minutos . ':' . $segundos;
    }

    public static function getCabeceraBusqueda($numero_filas) {
    	$texto = "";
    	if ($numero_filas == 0) {
			$texto = "No se han encontrado empresas con los parametros indicados";
		}
	   	if ($numero_filas == 1) {
			$texto = "Se ha encontrado " . $numero_filas . " empresa</span></div>";
		}
	   	if ($numero_filas > 1) {
			$texto = "Se han encontrado " . $numero_filas . " empresas</span></div>";
		}
		$html = "<div id='id_box_resultado_empresa' class='box-resultado-empresa'><span class='text-estandar-empresa'>" . $texto . "</span></div>";	
		return $html;
    } 

 	public static function getPaginacionBusqueda($pagina_actual, $numero_paginas) {
 		$paginas_a_mostrar = 5;
 		$css_pagina_actual = "";
 		$funcion_click = "";
 		$paginas = "";
 		$mitad = ceil ($paginas_a_mostrar / 2);
 		if ($numero_paginas <= $paginas_a_mostrar) {
			for ($i = 1; $i <= $numero_paginas; $i++) {
				if ($i == $pagina_actual) { 
					$css_pagina_actual = "-sel";
					
				}
				else {
					$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
				}
				$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
				$css_pagina_actual = "";
				$funcion_click = "";
			}		
 		}

		if ($numero_paginas == $paginas_a_mostrar + 1) {
			if ($pagina_actual <= $mitad) {
				for ($i = 1; $i <= $paginas_a_mostrar - 1; $i++) {
					if ($i == $pagina_actual) { 
						$css_pagina_actual = "-sel";
					}
					else {
						$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
					}					
					$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
					$css_pagina_actual = "";
					$funcion_click = "";
				}
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(" . $numero_paginas . ")'>  ..." . $numero_paginas . "  </span>";
			}
			else {
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(1)'>  1...  </span>";				
				for ($i = $mitad; $i <= $paginas_a_mostrar; $i++) {
					if ($i == $pagina_actual) { 
						$css_pagina_actual = "-sel";
					}
					else {
						$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
					}					
					$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
					$css_pagina_actual = "";
					$funcion_click = "";
				}
			}
		}
		if ($numero_paginas > $paginas_a_mostrar + 1) {
			if ($pagina_actual <= $mitad) {
				for ($i = 1; $i <= $mitad + 1; $i++) {
					if ($i == $pagina_actual) { 
						$css_pagina_actual = "-sel";
					}
					else {
						$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
					}					
					$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
					$css_pagina_actual = "";
					$funcion_click = "";
				}
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(" . $numero_paginas . ")'>  ..." . $numero_paginas . "  </span>";
			}
			if ($pagina_actual > $numero_paginas - $mitad) {
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(1)'>  1...  </span>";				
				for ($i = $numero_paginas - $mitad; $i <= $numero_paginas; $i++) {
					if ($i == $pagina_actual) { 
						$css_pagina_actual = "-sel";
					}
					else {
						$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
					}					
					$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
					$css_pagina_actual = "";
					$funcion_click = "";
				}				
			}
			if ($pagina_actual > $mitad && $pagina_actual <= ($numero_paginas - $mitad)) {
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(1)'>  1...  </span>";	
				for ($i = $pagina_actual + 1 - (floor($paginas_a_mostrar / 2)); $i <= $pagina_actual - 1 + (floor($paginas_a_mostrar / 2)) ; $i++) {
					if ($i == $pagina_actual) { 
						$css_pagina_actual = "-sel";
					}
					else {
						$funcion_click = "onclick='pasar_pagina(" . $i . ")'";
					}				
					$paginas .= "<span class='text-estandar-pagina$css_pagina_actual' $funcion_click>  " . $i . "  </span>";
					$css_pagina_actual = "";
					$funcion_click = "";
				}
				$paginas .= "<span class='text-estandar-pagina' onclick='pasar_pagina(" . $numero_paginas . ")'>  ..." . $numero_paginas . "  </span>";
			}
		}
		$paginas = "<div id='id_box_resultado_pagina' class='box-resultado-pagina' >Pagina" . $paginas . "</div>";
		return $paginas;
    } 

 	public static function getCuerpoBusqueda($row, $mostrar, $bandera, $admin, $direccion_user) {
 		$distancia = round($row['DISTANCIA'], 3);
 		$distancia = str_replace(".", ",", $distancia);
 		if ($row['DISTANCIA'] > 0) {
 			$distancia = "<br><span class='text-estandar'>Distancia $distancia KM</span>";
 		}
 		else {
 			$distancia = "";
 		}

		$clase_empresa = "texto_empresa";		
		if ($row['ACTIVA'] == 0) {
			$clase_empresa = "texto_empresa-inactiva";
		}

		$html = "<div id='id_box_resultado' class='box-resultado'>						
				<div class='box-empresa'>
					<div>
						<div class='contenedor_empresa'>     		
							  <span class='" . $clase_empresa . "' onclick='' lang='es'>
								" . $row['NOMBRE'] . "
								<a href='https://www.google.de/search?q=" . str_replace("'", "%27", $row['NOMBRE']) . " " . str_replace("'", "%27", $row['CALLE'])  . " " . $row['NUMERO_CALLE']  . " " . str_replace("'", "%27", $row['CIUDAD'])  . " " . str_replace("'", "%27", $row['REGION']) . "' target='_blank'>
									<i class='fas fa-globe clase_iconos'></i>
								</a>";
		if (!empty($direccion_user)) {
			$html .= "
								<a href='https://www.google.com/maps/dir/?api=1&origin=$direccion_user&destination=" . str_replace("'", "%27", $row['NOMBRE']) . " " . str_replace("'", "%27", $row['CALLE'])  . " " . $row['NUMERO_CALLE']  . " " . str_replace("'", "%27", $row['CIUDAD'])  . " " . str_replace("'", "%27", $row['REGION']) . "' target='_blank'>	
									<img src='images/marker" . $bandera . ".png' alt='Maker$bandera' witdh='25' height='19'>
								</a>";
		}
		else {
			$html .= "<img src='images/marker" . $bandera . ".png' alt='Maker$bandera' witdh='25' height='19'>";
		}
		$html .= "			</span>				
						</div>
						<div class='contenedor_datos_empresa'>
							<div class='clr'></div>
							<div class='box-direccion'>
								<span class='text-estandar'>
								Direccion:" .  $row['CALLE'] . " " . $row['NUMERO_CALLE'] . ", " . $row['CIUDAD'] . ", " . $row['REGION'] . ", " . $row['PAIS'] . "
								</span>							
									<a href='https://www.google.com/maps?q=" . str_replace("'", "%27", $row['NOMBRE']) . " " . str_replace("'", "%27", $row['CALLE'])  . " " . $row['NUMERO_CALLE']  . " " . str_replace("'", "%27", $row['CIUDAD'])  . " " . str_replace("'", "%27", $row['REGION']) . "' target='_blank'>
										<i class='fas fa-map-marker-alt clase_iconos'></i>									 				
									</a>
									<input type='hidden' id='id_latitud_result_" . $mostrar .  "' value='" . $row['LATITUD'] .  "'>
									<input type='hidden' id='id_longitud_result_" . $mostrar .  "' value='" . $row['LONGITUD'] .  "'>
									<input type='hidden' id='id_empresa_result_" . $mostrar .  "' value='" .  $row['NOMBRE'] . "'>
									<input type='hidden' id='id_distancia_result_" . $mostrar .  "' value='" .  $row['DISTANCIA'] . "'>
								$distancia						
							</div>
							<div class='box-actividad'>							
								<span class='text-estandar'>Actividad: " . $row['ACTIVIDAD'] . "</span>							
								
							</div> 	 
						</div>";
						if ($admin) {
							$html .= "<div style='text-align:right;'>
									<button class='btn btn-outline-secondary btn-header' onclick='window.open(\"index_modificar.php?id=" . $row['ID'] . "\", \"_blank\");'>Modificar</button>
							</div>";
						}
					$html .= "</div>					
				</div>
			</div>";
		return $html;
    } 


 } 
?>
