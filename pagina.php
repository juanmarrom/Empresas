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
				$offset =  ($pagina_actual - 1) * 10;
				$sql = $_SESSION['sql_busqueda'] . " LIMIT 10 OFFSET $offset;";
				//echo $sql;
				//exit;
				$stmt = $conn->prepare("$sql");			
				$stmt->execute();
				$result = $stmt->get_result();
				$numero_filas = $_SESSION['resultado'];
				$numero_paginas = ceil ($numero_filas / 10);
				$html = Util::getCabeceraBusqueda($numero_filas);				
				$mostrar = 0;
				$marca = 10;
				$paginas = "";
				$paginas =  Util::getPaginacionBusqueda($pagina_actual, $numero_paginas) ;
				$html .= $paginas;
				$bandera = 10;
				while ($row = $result->fetch_assoc()) {					
					$mostrar++;
					if ($mostrar <= 10) {
						$html .= Util::getCuerpoBusqueda($row, $mostrar, $bandera);
						$bandera--;
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
