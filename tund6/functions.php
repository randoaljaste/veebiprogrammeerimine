<?php
	$database = "if17_aljarand";
	require("../../../config.php");
	//alustame sessiooni
	session_start();
	
	
	//sisselogimise funktsioon
	
function signIn($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, password FROM vpusers WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb);
		$stmt->execute();
	
	
	//kontrollime kasutajat
	if($stmt->fetch()){
		$hash = hash("sha512", $password);
		if($hash == $passwordFromDb){
			$notice = "Kõik korras! Logisimegi sisse!";
			
			//salvestame sessioonimuutujad
			$_SESSION["userId"] = $id;
			$_SESSION["firstname"] = $firstnameFromDb;
			$_SESSION["lastname"] = $lastnameFromDb;
			$_SESSION["userEmail"] = $emailFromDb;
			
			//liigume pealehele
			header("Location: main.php");
			exit();
	} else {
		$notice = "Sisestasite vale salasõna!";
	}
	} else {
		$notice = "Sellist kasutajat (" .$email .") ei ole!";
	}
}

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
		$stmt->close();
		$mysqli->close();
	}
	
	function saveIdea($idea, $color){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpuserideas (userid, idea, ideacolor) VALUES (?, ?, ?)");
		
		$stmt->bind_param("iss", $_SESSION["userId"], $idea, $color);
		if ($stmt->execute()){
			$notice = "Mõte on salvestatud!";
		} else {
			$notice = "Salvestamisel tekkis viga!: " .$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function usertable(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt= $mysqli->prepare("SELECT id, firstname, lastname, email, birthday, gender FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($id, $firstname, $lastname, $email, $birthday, $gender);
		if ($stmt->execute()){
			$notice .= '<table border="1" style="border: 1px solid black; border-collapse: collapse"><tr><th>ID</th><th>Eesnimi</th><th>perekonnanimi</th><th>e-posti aadress</th><th>Sünnipäev</th><th>Sugu</th></tr>';
			while($stmt->fetch()){
				$notice .= '<tr><td> ' .$id .'</td><td> ' .$firstname .'</td><td>' .$lastname .'</td><td>' .$email .'</td><td>' .$birthday .'</td><td>' .$gender .'</td></tr>';
			}
		$notice .= "</table>";
		
		}
		return $notice;
	}
	
	
	
	function listIdeas(){
		$notice = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt= $mysqli->prepare("SELECT idea, ideacolor FROM vpuserideas WHERE userid = ? ORDER BY id DESC");
		echo $mysqli->error;
		$stmt->bind_param("i", $_SESSION["userId"]);
		$stmt->bind_result($idea, $color);
		
		
		while($stmt->fetch()){
			//<p style="background-color: #ff5577">Hea mõte</p>
			$notice .= '<p style="background-color: ' .$color .'">' .$idea ."</p> \n";
		
		}
		$stmt->close();
		$mysqli->close();
		return $notice;
	}
	
	function latestIdea(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt= $mysqli->prepare("SELECT idea FROM vpuserideas WHERE id =(SELECT MAX(id) FROM vpuserideas)");
		echo $mysqli->error;
		$stmt->bind_result($idea);
		$stmt->execute();
		$stmt->fetch();
		
		
		$stmt->close();
		$mysqli->close();
		echo $idea;
		return $idea;
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