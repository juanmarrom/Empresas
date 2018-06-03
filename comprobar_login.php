<?php
	session_start();
	$_SESSION['busqueda']  = session_id();	
	require_once './clases/util.php';			
	Util::iniciarConexion("./conf.txt");
	$conn =  Util::getConexion();
	if(isset($_POST["email"]) && isset($_POST["pass"])) {				
		$sql = "Select ID, PASSWORD, ES_ADMIN, NOMBRE, APELLIDO_1, APELLIDO_2, ID_IDIOMA FROM USUARIO WHERE lower(trim(LOGIN)) = lower(trim(?)) AND ACTIVO = true;";
		try {
			$stmt = $conn->prepare("$sql");
			/*i - integer
			d - double
			s - string
			b - BLOB*/
			$stmt->bind_param("s", $_POST["email"]);
			$stmt->execute();
			$stmt->bind_result($id, $pass, $es_admin, $nombre, $apellido_1, $apellido_2, $idioma);
			$ret = "location:index.php?error=a";
			$correcto = false;
			$encontrado = false;
			while($stmt->fetch()) {
				$encontrado = true;
				if (password_verify($_POST["pass"], $pass)) {
					//AUDITORIA_LOGIN
					$correcto = true;
					$_SESSION['idioma'] = $idioma;
					$_SESSION['admin'] = $es_admin;
					$_SESSION['nombre'] = $nombre;
					$_SESSION['apellido_1'] = $apellido_1;
					$_SESSION['apellido_2'] = $apellido_2;
					$_SESSION['login'] = $_POST["email"];
					$_SESSION['id_usuario'] = $id;
					$_SESSION['sql_busqueda'] = "";
				}
   				
			}
			$stmt->close();	
			if ($encontrado) {		
				$user_agent = $_SERVER["HTTP_USER_AGENT"];
				$ip = $_SERVER["REMOTE_ADDR"];
				$sql_insert = "INSERT INTO AUDITORIA_LOGIN (ID_USUARIO, LOGIN, NAVEGADOR, IP, ADMIN, CORRECTO) VALUES (?,true,?,?,?,?);";						

				$stmt2 = $conn->prepare("$sql_insert");
				$stmt2->bind_param("issii", $id, $user_agent,$ip,$es_admin,$correcto);
				if ($correcto) {
					$ret = "location:index_busqueda.php";
				}
				else {
					$ret = "location:index.php?error=pass";
				}
				$stmt2->execute();
				if($conn->insert_id === 0) {
					$ret = "location:index.php?error=insert";
				}
				$stmt2->close();
			}
			header("$ret");
		} 
		catch(Exception $e) {
			session_destroy();
			header("location:index.php?error=catch"); 
		
		}					
	} 
	else {
		session_destroy();
		header("location:index.php?error=session"); 
	}
?>
