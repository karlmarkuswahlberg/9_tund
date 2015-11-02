<?php
		
	//loome AB �henduse
    require_once("../config_global.php");
    $database = "if15_skmw";
    
	//paneme sessiooni serveris t��le, saame kasutada SESSION[] muutujaid. mysql
    session_start();
	
	
    //check connection
   
	
	
	function logInUser($email, $hash){
		
		//muutuja v�ljaspool funktsiooni. GLOBALS saab k�tte k�ik muutujad msi kasutusel.
		 $mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?"); 
        $stmt->bind_param("ss", $email, $hash);
        $stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
			if($stmt->fetch()){
                    echo "Kasutaja logis sisse id=".$id_from_db;
					
					//sessioon salvestatakse serveris
					$_SESSION['logged_in_user_id'] =  $id_from_db;
					$_SESSION['logged_in_user_email'] =  $email_from_db;
					//suuname kasutaja teisele lehele
					header("Location: data.php");
					
                }else{
                    echo "Wrong credentials!";
                }
                $stmt->close();
				$mysqli->close();
	}
	
	
	//siin v�tan login.phpst vastu need muutujad.
	function createUser($create_email, $hash){
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		$stmt->execute();
        $stmt->close();
		$mysqli->close();	
	}
	
	
	function createCarPlate($car_plate, $color){
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO car_plates (user_id, number_plate, color) VALUES (?,?,?)");
		echo $mysqli->error;
		//i on int user id jaoks.
		$stmt->bind_param("iss", $_SESSION['logged_in_user_id'], $car_plate, $color);
		
		//muutuja selleks, mida ta ütleb.
		$message = "";
		
		//kui õnnestus, siis tõene kui ei (viga), siis else.
		if($stmt->execute()){
			//õnnestus
			$message = "Edukalt lisatud andmebaasi!";
		}else{
			//ei õnnestunud
		}
		
        $stmt->close();
		$mysqli->close();
		
		//saadan selle õnnestumise või mitteõnnestumise tagasi.
		return $message;
	}
	
	//selleks, et kuvada tabel lehel välja. 
	function getAllData(){
		
		
		
		$mysqli = new mysqli($GLOBALS["servername"], $GLOBALS["server_username"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, user_id, number_plate, color FROM car_plates");
	//kuna küsimärke pole, siis bind_param jääb vahele.
		$stmt->bind_result($id_from_db, $user_id_from_db, $number_plate_from_db, $color_from_db);
		$stmt->execute();
		
		//iga rea kohta teeme midagi. tsükkel. mis on andmebaasis
		while($stmt->fetch()){
			echo($user_id_from_db);
			
			//õnnestus, saime andmed kätte
			//kuidas saada need andmed massiivi
		}
        $stmt->close();
		$mysqli->close();
		
		
		
		
	}

?>