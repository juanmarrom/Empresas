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
					$stmt = $conn->prepare("SELECT ID FROM IDIOMA");
					$stmt->execute();
					$result = $stmt->get_result();				
					$idiomas = array();
					while ($row = $result->fetch_assoc()) {
						$idiomas[] = $row['ID'];
					}
					$stmt->close();

					$longitud = count($idiomas);

					if($longitud > 0) {
						$query = "";
						$app = $_POST['app'];
						$variable = $_POST['variable'];
						$sql_insert = "INSERT INTO VARIABLE_TEXTO (ID_APLICACION, VARIABLE) VALUES (?,?);";						
						$stmt2 = $conn->prepare("$sql_insert");
						$stmt2->bind_param("is", $app,$variable);
						$stmt2->execute();
						$insert_id = $conn->insert_id;
						$stmt2->close();
						if($insert_id != 0) {
							for($i=0; $i<$longitud; $i++) {
								$sql_insert = "INSERT INTO TEXTO (ID_VARIABLE, ID_IDIOMA) VALUES (?,?);";
								$stmt2 = $conn->prepare("$sql_insert");
								$stmt2->bind_param("ii", $insert_id, $idiomas[$i]);
								$stmt2->execute();
								$stmt2->close();																
							}
						}
						echo "OK";
						exit;
					}
					else {
						session_destroy();
						header("location:index.php?error=ataque"); 
					}
				} 
				catch(Exception $e) {
					echo "ERROR";
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