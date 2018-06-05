<?php 
function geocode($address) {
    $address = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=AIzaSyCEUxuelm-ruuX7STQP7iDdk-KpoRedKCY"; 
    $resp_json = file_get_contents($url);     
    $resp = json_decode($resp_json, true); 
    if($resp['status']=='OK') { 
        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
        $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";         
        if($lati && $longi && $formatted_address){        
            $data_arr = array();                         
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
             );             
            return $data_arr;             
        }
        else{
            return null;
        }         
    } 
    else{
        return null;
    }
}
?>

<?php
session_start();    
if(isset($_SESSION["busqueda"])) {
    require_once './clases/util.php';           
    Util::iniciarConexion("./conf.txt");
    $conn =  Util::getConexion();
    if($_SESSION["busqueda"] == session_id()) {
        if($_POST) {
            // get latitude, longitude and formatted address
            $data_arr = geocode($_POST['address']);
            if (empty($data_arr)) {
                $myObj->latitude = null;
                $myObj->longitude = null;
                $myObj->formatted_address = null;
            }
            else {
                $myObj->latitude = $data_arr[0];
                $myObj->longitude = $data_arr[1];
                $myObj->formatted_address = $data_arr[2];
                $sql_insert = "INSERT INTO AUDITORIA_POSICION (ID_USUARIO, POSICION, LATITUD, LONGITUD) VALUES (?,?,?,?);";
                $stmt2 = $conn->prepare("$sql_insert");
                $stmt2->bind_param("isdd", $_SESSION['id_usuario'], $_POST['address'], $data_arr[0], $data_arr[1]);
                $stmt2->execute();
                $stmt2->close();                
            }    
            $myJSON = json_encode($myObj);
            echo $myJSON;
        }
    }
    else {
        echo "Error 1" . $_POST['address'];
    }   
}
else {
    echo "Error 2" . $_POST['address'];
}     
?>
