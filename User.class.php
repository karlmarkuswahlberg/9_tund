<?php
class User{ //this-iga  saab kätte kui on klass
	
	
	private $connection;
	
	//see funktsioon käivitub kui tekitame uue instantsi. 
	//nt: new User()
	function __construct($mysqli){ //siin võtame mysqli ühenduse vastu, mis tektiati functions.php üleval.
		
		
		$this->connection = $mysqli //$this-> on see klass e User. ja see aitab private $connectionit siia functionisse kättesaadavaks teha.
		
	}
	function logInUser($email, $hash){
		
		
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?"); //muutus siin. läbi connectioni.
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
		

		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		$stmt->execute();
        $stmt->close();
			
	}
	
} ?>