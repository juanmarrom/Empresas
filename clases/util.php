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

 	public static function getCuerpoBusqueda($ruta_fichero) {
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


 } 
?>
