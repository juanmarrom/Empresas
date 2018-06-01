<?php
	require_once './clases/util.php';			
	Util::iniciarConexion("./conf.txt");
	$conn =  Util::getConexion();
	$sql = "Select ID, NOMBRE FROM EMPRESA WHERE NOMBRE LIKE %?%";	
	$sql_insert = "INSERT INTO AUDITORIA_BUSQUEDA (ID_USUARIO, TIEMPO, RESULTADO) VALUES (?,?,?);";
		try {
			$stmt = $conn->prepare("$sql");
			$stmt->bind_param("s", "Za");
			$stmt->execute();
			$result = $stmt->get_result();
			$numero_filas = mysql_num_rows($result);
			echo "número_filas - $número_filas\n";
			while ($row = $result->fetch_assoc()){
				$stmt2 = $conn->prepare("$sql_insert");
				$stmt2->bind_param("iii", 1, 10,5);
				$stmt2->execute();
				$stmt2->close();
			}						
		} 
		catch(Exception $e) {
			echo "Exception - $e\n";
		}					
?>
