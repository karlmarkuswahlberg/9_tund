<?php

	//selleks, et functions.php �hendatud oleks.
	
	require_once("functions.php");
	
	//kui kasutaja on sisselogitu, suuna teisele lehele. Ei saaks logituna minna tagasi login lehele.
	//kontrollin kas sessioonimuutuja on olemas? 
	if(isset($_SESSION['user_id'])){
		header("Location: data.php");
	}
	
    
  // muuutujad errorite jaoks
	$email_error = "";
	$password_error = "";
	$create_email_error = "";
	$create_password_error = "";
  // muutujad v��rtuste jaoks
	$email = "";
	$password = "";
	$create_email = "";
	$create_password = "";
	if($_SERVER["REQUEST_METHOD"] == "POST") {
    // *********************
    // **** LOGI SISSE *****
    // *********************
		if(isset($_POST["login"])){
			if ( empty($_POST["email"]) ) {
				$email_error = "See v�li on kohustuslik";
			}else{
        // puhastame muutuja v�imalikest �leliigsetest s�mbolitest
				$email = cleanInput($_POST["email"]);
			}
			if ( empty($_POST["password"]) ) {
				$password_error = "See v�li on kohustuslik";
			}else{
				$password = cleanInput($_POST["password"]);
			}
      // Kui oleme siia j�udnud, v�ime kasutaja sisse logida
			if($password_error == "" && $email_error == ""){
				//echo "V�ib sisse logida! Kasutajanimi on ".$email." ja parool on ".$password;
			
                $hash = hash("sha512", $password);
                
                $login_response = $User->logInUser($email, $hash);
                
				var_dump($login_response); //echoga ei saa k�tte, kuna see on objekt.
            
			
//tr�kin v�lja sisseloginud kasutaja emaili
//echo $login_response->success->user->email;

				//sisselogimine �nnestus.
				if (isset($login_response->success)){
					$_SESSION["user_id"] = $login_response->success->user->id;
					$_SESSION["user_email"] = $login_response->success->user->email;
					
					header("Location: data.php");
					
				}
            
			
            }
		} // login if end
    // *********************
    // ** LOO KASUTAJA *****
    // *********************
    if(isset($_POST["create"])){
			if ( empty($_POST["create_email"]) ) {
				$create_email_error = "See v�li on kohustuslik";
			}else{
				$create_email = cleanInput($_POST["create_email"]);
			}
			if ( empty($_POST["create_password"]) ) {
				$create_password_error = "See v�li on kohustuslik";
			} else {
				if(strlen($_POST["create_password"]) < 8) {
					$create_password_error = "Peab olema v�hemalt 8 t�hem�rki pikk!";
				}else{
					$create_password = cleanInput($_POST["create_password"]);
				}
			}
			if(	$create_email_error == "" && $create_password_error == ""){
				
                //echo "V�ib kasutajat luua! Kasutajanimi on ".$create_email." ja parool on ".$create_password;
                
                // tekitan paroolir�si
                $hash = hash("sha512", $create_password);
                
                //salvestan andmebaasi
               $response = $User->createUser($create_email, $hash); //see on edasi functions.php failis, kuhu siit saadetakse create_email ja hash. 
                
                
            }
        } // create if end
		
		

		
	}
  // funktsioon, mis eemaldab k�ikv�imaliku �leliigse tekstist
  function cleanInput($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
  }
   
 
  // paneme �henduse kinni
 
  
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Log in</h2>
  
  <?php if(isset($login_response->error)): ?>
  
  <p style="color:red;"><?=$login_response->error->message;?></p>
  
  <?php endif; ?>
  
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
  	<input name="email" type="email" placeholder="E-post" value="<?php echo $email; ?>"> <?php echo $email_error; ?><br><br>
  	<input name="password" type="password" placeholder="Parool" value="<?php echo $password; ?>"> <?php echo $password_error; ?><br><br>
  	<input type="submit" name="login" value="Log in">
  </form>

  <h2>Create user</h2>
  
  <?php if(isset($response->success)): ?>
  
  <p style="color:green;"><?=$response->success->message;?><p>
  
  <?php elseif(isset($response->error)): ?>
  
  <p style="color:red;"><?=$response->error->message;?><p>
  
  <?php endif; ?>
  
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
  	<input name="create_email" type="email" placeholder="E-post" value="<?php echo $create_email; ?>"> <?php echo $create_email_error; ?><br><br>
  	<input name="create_password" type="password" placeholder="Parool"> <?php echo $create_password_error; ?> <br><br>
  	<input type="submit" name="create" value="Create user">
  </form>
<body>
<html>