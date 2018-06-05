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
					$query = "";
					$tipo = $_POST['tipo'];
					$user = $_POST['user'];
					$tabla = "<table class='table'>
					<thead>
					<tr>";
					if (is_numeric($tipo) && is_numeric($user)) {
										
						if ($tipo == 0) { //Login
							$query = "SELECT (SELECT CONCAT(NOMBRE, ' ', APELLIDO_1 ,' ', APELLIDO_2) FROM USUARIO WHERE ID = ID_USUARIO) as NOMBRE, CUANDO, IP, LOGIN, LOGOUT, NAVEGADOR FROM AUDITORIA_LOGIN ";
							$tabla .= "<td>USUARIO</td><td>CUANDO</td><td>LOGIN</td><td>LOGOUT</td><td>IP</td><td>NAVEGADOR</td></tr></thead>";
						}
						if ($tipo == 1) { // busqueda
							$query = "SELECT (SELECT CONCAT(NOMBRE, ' ', APELLIDO_1 ,' ', APELLIDO_2) FROM USUARIO WHERE ID = ID_USUARIO) as NOMBRE, CUANDO, TIEMPO, RESULTADO FROM AUDITORIA_BUSQUEDA ";
							$tabla .= "<td>USUARIO</td><td>CUANDO</td><td>TIEMPO</td><td>ENCONTRADOS</td></tr></thead>";
						}

						if ($user == 0) { //todos
							$query .= " ORDER BY ID DESC LIMIT 100";
						}
						else {
							$query .= " WHERE ID_USUARIO = $user ORDER BY ID DESC LIMIT 100";
						}
						$stmt = $conn->prepare("$query");
						$stmt->execute();
						$result = $stmt->get_result();				
						$response = array();
						while ($row = $result->fetch_assoc()) {
							if ($tipo == 0) { //Login								
								$tabla .= "<tr><td>" . $row['NOMBRE'] . "</td><td>" . $row['CUANDO'] . "</td><td>" . $row['LOGIN'] . "</td><td>" . $row['LOGOUT'] . "</td><td>" . $row['IP'] . "</td><td>" . $row['NAVEGADOR'] . "</td></tr>";
							}
							if ($tipo == 1) { // busqueda								
								$tabla .= "<tr><td>" . $row['NOMBRE'] . "</td><td>" . $row['CUANDO'] . "</td><td>" . $row['TIEMPO'] . "</td><td>" . $row['RESULTADO'] . "</td></tr>";
							}

						}
						$tabla .= "</table>";
						$stmt->close();
						echo $tabla;
						exit;
					}
					else {
						session_destroy();
						header("location:index.php?error=ataque"); 
					}
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