<?php
	session_start();
	$_SESSION['busqueda']  = session_id();	
	require_once './clases/util.php';			
	Util::iniciarConexion("./conf.txt");
	$conn =  Util::getConexion();
	if($_SESSION["busqueda"] == session_id()) {				
		try {
			$user_agent = $_SERVER["HTTP_USER_AGENT"];
			$ip = $_SERVER["REMOTE_ADDR"];
			$correcto = 1;
			$sql_insert = "INSERT INTO AUDITORIA_LOGIN (ID_USUARIO, LOGOUT, NAVEGADOR, IP, ADMIN, CORRECTO) VALUES (?,true,?,?,?,?);";
			$stmt2 = $conn->prepare("$sql_insert");
			$stmt2->bind_param("issii", $_SESSION['id_usuario'], $user_agent,$ip,$_SESSION['admin'],$correcto);
			$stmt2->execute();
			$ret = "location:index.php";
			if($conn->insert_id === 0) {
				$ret = "location:index.php?error=logout";
			}
			$stmt2->close();
			session_destroy();		
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
