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
            return false;
        }         
    } 
    else{
        echo "ERROR";
        return false;
    }
}
?>

<?php
if($_POST) {
  // get latitude, longitude and formatted address
  $data_arr = geocode($_POST['address']);
	$myObj->latitude = $data_arr[0];
	$myObj->longitude = $data_arr[1];
	$myObj->formatted_address = $data_arr[2];
	$myJSON = json_encode($myObj);
	echo $myJSON;
}
?>
