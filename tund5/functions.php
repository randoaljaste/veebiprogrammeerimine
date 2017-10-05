<?php
	$database = "if17_aljarand";

	// uue kasutaja andmebaasi lisamine
	function signUp($signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword){
		//ühendus serveriga
		$database = "if17_aljarand";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		// käsk serverile
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthday, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
		// s - string, tekst; i-integer, täisarv; d-decimal, ujukomaarv
		$stmt->bind_param("sssiss", $signupFirstName, $signupFamilyName, $signupBirthDate, $gender, $signupEmail, $signupPassword);
		$stmt->execute();
		if ($stmt->execute()){
			echo "Läks väga hästi!";
		} else {
			echo "Tekkis viga: " .$stmt->error;
		}
	}
	//sisestuse kontrollimine
	function test_input($data){
		$data = trim($data); // eemaldab lõpust tühiku, tab vms
		$data = stripslashes($data); // eemaldab \
		$data = htmlspecialchars($data); // eemaldab keelatud märgid
		return $data;
	}
	
	/*$x = 8;
	$y = 5;
	echo "Esimene summa on: " .($x + $y);
	addValues();
		
	function addValues(){
		echo "Teine summa on: " .($GLOBALS["x"] + $GLOBALS["y"]);
		$a = 4;
		$b= 1;
		echo "Kolmas summa on: " .($a + $b);
	}
	
	
	echo "Neljas summa on: " .($a + $b);*/
	
?>
	
	