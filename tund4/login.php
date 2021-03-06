<?php
	require("../../../config.php");

	
	$loginEmail = "";
	$notice = "";
	$signupFirstName = "";
	$signupFamilyName = "";
	$signupEmail = "";
	$gender = "";
	$signupBirthDay = null;
	$signupBirthMonth = null;
	$signupBirthYear = null;
	$signupBirthDate = null;
	
	
	$signupFirstNameError ="";
	$signupFamilyNameError ="";
	$signupBirthDayError = "";
	$signupGenderError = "";
	$signupEmailError = "";
	$signupPasswordError = "";
	$loginEmailError = "";

	//kas on kasutajanimi sisestatud
if (isset ($_POST["loginEmail"])){
		if (empty ($_POST["loginEmail"])){
			$loginEmailError ="NB! Ilma selleta ei saa sisse logida!";
		} else {
			$loginEmail = $_POST["loginEmail"];
		}
	}
	
	if(!empty($loginEmail) and !empty($_POST["loginPassword"])){
		//echo "Hakkan sisse logima!";
		$notice = signIn($loginEmail, $_POST["loginPassword"]);
	}


	//kontrollime, kas kirjutati eesnimi
	if (isset ($_POST["signupFirstName"])){
		if (empty ($_POST["signupFirstName"])){
			$signupFirstNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFirstName = $_POST["signupFirstName"];
		}
	}
	
	//kontrollime, kas kirjutati perekonnanimi
	if (isset ($_POST["signupFamilyName"])){
		if (empty ($_POST["signupFamilyName"])){
			$signupFamilyNameError ="NB! Väli on kohustuslik!";
		} else {
			$signupFamilyName = $_POST["signupFamilyName"];
		}
	}
	
	//kas on sünni kuupäev määratud
	if (isset ($_POST["signupBirthDay"])){
		$signupBirthDay = $_POST["signupBirthDay"];
		//echo $signupBirthDay;
	} else {
		$signupBirthDayError = "Kuupäeva pole sisestatud!";
	}
	
	//kas kuu määratud
	if(isset($_POST["signupBirthMonth"])){
		$signupBirthMonth = intval($_POST["signupBirthMonth"]);
	} else {
		$signupBirthDayError .= " Kuu pole sisestatud!";
	}
	
	//kas on sünni aasta määratud
	if (isset ($_POST["signupBirthYear"])){
		$signupBirthYear = $_POST["signupBirthYear"];
		$signupBirthYearError = "Aasta pole määratud!";
	} else {
		$signupBirthDayError .= " Aasta pole määratud!";
	}
	
	//kontrollin, kas kuupäev on valiidne
	if (isset($_POST["signupBirthDay"]) and isset($_POST["signupBirthMonth"]) and isset ($_POST["signupBirthYear"])){
		if (checkdate(intval($_POST["signupBirthMonth"]), intval($_POST["signupBirthDay"]), intval($_POST["signupBirthYear"]))){
			$birthDate = date_create($_POST["signupBirthMonth"] ."/" .$_POST["signupBirthDay"] ."/" .$_POST["signupBirthYear"]);
			$signupBirthDate = date_format($birthDate, "Y-m-d");
			$signupBirthDate;
		} else {
			$signupBirthDayError = "Sünnikuupäev pole valiidne!";
			echo $signupBirthDayError;
		}

	}
	
	//kontrollime, kas kirjutati kasutajanimeks email
	if (isset ($_POST["signupEmail"])){
		if (empty ($_POST["signupEmail"])){
			$signupEmailError ="NB! Väli on kohustuslik!";
		} else {
			$signupEmail = test_input($_POST["signupEmail"]);
			$signupEmail = filter_var($signupEmail, FILTER_SANITIZE_EMAIL);
			if(!filter_var($signupEmail, FILTER_VALIDATE_EMAIL)){
				$signupEmailError = "Sisestatud e-postiaadress pole nõutud kujul!";
			}
		}
	}
	
	if (isset ($_POST["signupPassword"])){
		if (empty ($_POST["signupPassword"])){
			$signupPasswordError = "NB! Väli on kohustuslik!";
		} else {
			//polnud tühi
			if (strlen($_POST["signupPassword"]) < 8){
				$signupPasswordError = "NB! Liiga lühike salasõna, vaja vähemalt 8 tähemärki!";
			}
		}
	}
	
	if (isset($_POST["gender"]) && !empty($_POST["gender"])){ //kui on määratud ja pole tühi
			$gender = intval($_POST["gender"]);
		} else {
			$signupGenderError = " (Palun vali sobiv!) Määramata!";
	}
	
	
	//UUE KASUTAJA LISAMINE ANDMEBAASI
	if (empty ($signupFirstNameError) and empty ($signupFamilyNameError) and empty ($signupBirthDayError) and empty ($signupGenderError) and empty ($signupEmailError) and empty ($signupPasswordError) and !empty($_POST["signupPassword"])) {
		echo "Hakkan andmeid salvestama!";
		$signupPassword = hash("sha512", $_POST["signupPassword"]);
		//ühendus serveriga
		$database = "if17_aljarand";
		$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $database);
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
	
	//Tekitame kuupäeva valiku
	$signupDaySelectHTML = "";
	$signupDaySelectHTML .= '<select name="signupBirthDay">' ."\n";
	$signupDaySelectHTML .= '<option value="" selected disabled>päev</option>' ."\n";
	for ($i = 1; $i < 32; $i ++){
		if($i == $signupBirthDay){
			$signupDaySelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupDaySelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ." \n";
		}
		
	}
	$signupDaySelectHTML.= "</select> \n";
	

	//tekitame sünnikuu valiku
	$monthNamesEt = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	$signupMonthSelectHTML = "";
	$signupMonthSelectHTML .= '<select name="signupBirthMonth">' ."\n";
	$signupMonthSelectHTML .= '<option value="" selected disabled>kuu</option>' ."\n";
	foreach ($monthNamesEt as $key=>$month){
		if ($key + 1 === $signupBirthMonth){
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'" selected>' .$month ."</option> \n";
		} else {
			$signupMonthSelectHTML .= '<option value="' .($key + 1) .'">' .$month ."</option> \n"; 
		}
	}
	$signupMonthSelectHTML .= "</select> \n";
	
	
	//Tekitame aasta valiku
	$signupYearSelectHTML = "";
	$signupYearSelectHTML .= '<select name="signupBirthYear">' ."\n";
	$signupYearSelectHTML .= '<option value="" selected disabled>aasta</option>' ."\n";
	$yearNow = date("Y");
	for ($i = ($yearNow - 10); $i > 1900; $i --){
		if($i == $signupBirthYear){
			$signupYearSelectHTML .= '<option value="' .$i .'" selected>' .$i .'</option>' ."\n";
		} else {
			$signupYearSelectHTML .= '<option value="' .$i .'">' .$i .'</option>' ."\n";
		}
		
	}
	$signupYearSelectHTML.= "</select> \n";



?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Tere tulemast!</title>
</head>
<body>
<body style="background-color:lightgreen;">

<h1>Logi sisse</h1>
<form method="POST">
<label>Teie kasutajanimi(E-mail): </label>
<br>
<input name="loginEmail" type="email" value="<?php echo $loginEmail; ?>"><span><?php echo $loginEmailError; ?></span>
<br>
<br>
<label>Teie parool: </label>
<br>
<input name="loginPassword" value="Logi sisse" type="password"><span></span>
<br>
<input name="signinButton" type="submit" value="Logi sisse"><span><?php echo $notice; ?></span>

</form>
<h1>Registeeri ennast kasutajaks</h1>
<p>Kui te ei ole veel ennast kasutajaks registreerinud, siis on selleks viimane aeg! Tee seda kohe!</p>




<form method="POST">
<label>Eesnimi:</label>
<input name="signupFirstName" type="text" value="<?php echo $signupFirstName; ?>">
<span><?php echo $signupFirstNameError; ?></span>
<br>
<br>
<label>Perekonnanimi:</label>
<input name="signupFamilyName" type="text" value="<?php echo $signupFamilyName; ?>">
<span><?php echo $signupFamilyNameError; ?></span>
<br><br>
<label>Palun määra oma sünnikuupäev: </label>
		<?php
			echo $signupDaySelectHTML ."\n" .$signupMonthSelectHTML ."\n" .$signupYearSelectHTML;
		?>
		<span><?php echo $signupBirthDayError; ?></span>
		
		
		<br><br>

<label>Sugu:</label>
	<br>
<input type="radio" name="gender" value="1" <?php if ($gender == '1') {echo 'checked';} ?>><label>Mees</label>
<input type="radio" name="gender" value="2" <?php if ($gender == '2') {echo 'checked';} ?>><label>Naine</label>
<span><?php echo $signupGenderError; ?></span>
<br>
<br>
<label>Kasutajanimi(E-mail):</label>
<input name="signupEmail" tyle="email" value="<?php echo $signupEmail; ?>">
<span><?php echo $signupEmailError; ?></span>
<br>
<label>Parool:</label>
<input name="signupPassword" type="password">
<span><?php echo $signupPasswordError; ?></span>
<br><br>
<input name="submit"type="submit" value="Loo kasutaja">
</form>


</body>
</html>