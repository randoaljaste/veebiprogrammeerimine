<?php
	//muutujad
	$myName = "Rando";
	$myFamilyName = "Aljaste";
	
	//hindan päeva osa 
	$hourNow = date("H");
	$partOfDay = "";
	if ($hourNow < 8){
		$partOfDay = "varajane hommik";
	}
	if ($hourNow >= 8){
		$partOfDay = "koolipäev";
	}
	if ($hourNow > 16){
		$partOfDay = "vaba aeg";
	}
	//echo $partOfDay
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Rando programmeerib veebi</title>
</head>
<body>
<body style="background-color:powderblue;">
	<h1><?php echo $myName ." " .$myFamilyName; ?>, veebiprogrammeerimine</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<?php
		echo "<p>Algas PHP õppimine.</p>";
		echo "<p>Täna on ";
		echo date("d.m.Y.") .", kell oli lehe avamise hetkel " .date("H:i:s");
		echo ", hetkel on " .$partOfDay .".</p>";
		
	?>



</body>
</html>