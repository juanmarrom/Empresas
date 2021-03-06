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
					$id = $_POST['id'];
					$texto = $_POST['texto'];
					$id_idioma = $_POST['id_idioma'];
					if (is_numeric($id)) {
						$sql_update = "UPDATE TEXTO SET TEXTO = ? WHERE ID = ?;";						
						$stmt2 = $conn->prepare("$sql_update");
						$stmt2->bind_param("si", $texto,$id);
						$stmt2->execute();
						$stmt2->close();

					}
					else {
						session_destroy();
						header("location:index.php?error=ataque"); 
					}
					echo "OK";
				} 
				catch(Exception $e) {
					echo "Exception - $e\n";
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