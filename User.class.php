<?php
class User{ //this-iga  saab k�tte kui on klass
	
	
	private $connection;
	
	//see funktsioon k�ivitub kui tekitame uue instantsi. 
	//nt: new User()
	function __construct($mysqli){ //siin v�tame mysqli �henduse vastu, mis tektiati functions.php �leval.
		
		
		$this->connection = $mysqli; //$this-> on see klass e User. ja see aitab private $connectionit siia functionisse k�ttesaadavaks teha.
		
	}
	function logInUser($email, $hash){
		
		
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?"); //muutus siin. l�bi connectioni.
        $stmt->bind_param("ss", $email, $hash);
        $stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
			if($stmt->fetch()){
                    echo "Kasutaja logis sisse id=".$id_from_db;
					
					$_SESSION['logged_in_user_id'] =  $id_from_db;
					$_SESSION['logged_in_user_email'] =  $email_from_db;
					
					header("Location: data.php");
					
                }else{
                    echo "Wrong credentials!";
                }
                $stmt->close();
				
	}
	
	function createUser($create_email, $hash){
		
		//objekt, kus tagastame errori (id, message) v�i success'i (message).
		$response = new StdClass();
		
		
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
		$stmt->execute();
		//kontrollime, kas saime rea andmeid. 
		if($stmt->fetch){
			
			//siis saime ja kui saime, siis email on juba olemas. ei saa regada.
			$error = new StdClass();
			$error->id = 0;
			$error->message = "E-mail on juba kasutusel!";
			
			$response->error = $error;
			
			return $response; //kuna ei taha, et alumine pool �ldse k�ivituks. ehk kasutaja loomine j��ks katki.
			
		}
		
		//siia tuleb siis kui email on vaba, saab regada.
	
	
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		if($stmt->execute()){
			//sisestamine �nnestus kui siiani on j�udnud.
			
			$success = new StdClass();
			$success->message = "Kasutaja loodud!";
			$response->success = $success;
			
		}else{
			
			//ei �nnestunud
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi l�ks nihu!";
			
			$response->error = $error;
		}
        $stmt->close();
		
		return $response;
	}
	
} ?>