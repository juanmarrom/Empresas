<?php
	require_once './clases/util.php';			
	Util::iniciarConexion("./conf.txt");
	$conn =  Util::getConexion();
	$sql = "Select ID, NOMBRE FROM EMPRESA WHERE NOMBRE like ? limit 10";	
	$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA (ID_USUARIO, TIEMPO, RESULTADO) VALUES (?,?,?);";
		try {
			$stmt = $conn->prepare("$sql");
			$nom = "%zara%";
			$stmt->bind_param("s", $nom);
			$stmt->execute();
			$stmt->bind_result($id, $nombre);
			
			$result = $stmt->get_result();
			$numero_filas =  $result->num_rows;
			echo "número_filas - $numero_filas\n";
			while ($row = $result->fetch_assoc()){
				$stmt2 = $conn->prepare("$sql_insert");
				$num1 = 1;
				$num2 = 10;
				$num3 = 5;
				$stmt2->bind_param("iii", $num1, $num2, $num3);
				$stmt2->execute();
				$stmt2->close();
			}						
		} 
		catch(Exception $e) {
			echo "Exception - $e\n";
		}					
?>
