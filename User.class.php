<?php

$user1 = new User("karl");
$user2 = new User("markus");


class User{
	
	//User.class.php -> k�ik tuleb siia class sulgude vahele.
	
	//see funktsioon k�ivitub kui tekitame uue instantsi. 
	//nt: new User()
	function __construct($name){
		
		echo $name." <br>";
		
		
	}
	
} ?>