<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}
	

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>usersInfo</title>
	<style>
div.container {
    width: 100%;
    border: 1px solid gray;
}

header, footer {
    padding: 1em;
    color: white;
    background-color: black;
    clear: left;
    text-align: center;
}

nav {
    float: left;
    max-width: 160px;
    margin: 0;
    padding: 1em;
}

nav ul {
    list-style-type: none;
    padding: 0;
}
   
nav ul a {
    text-decoration: none;
}

article {
    margin-left: 170px;
    border-left: 1px solid gray;
    padding: 1em;
    overflow: hidden;
}
</style>
</head>
<body>
<div class="container">

<header>
   <h1>Rando veebiprogrammeerimine</h1>
</header>
  
<nav>
  <ul>
    <p><a href="?logout=1">Logi välja!</a></p>
	<p><a href="main.php">Pealeht</a></p>
  </ul>
</nav>

<article>
  <h1>Kõik süsteemi kasutajad</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<?php $stmt = $mysqli->prepare("SELECT id, firstname, lastname, email, birthday, gender FROM vpusers");
	$stmt->bind_result($id, $firstnameFromDb, $lastnameFromDb, $emailFromDb, $passwordFromDb, $birthdayFromDb, $genderFromDb);
	$stmt->execute();
	
	echo "<table border=1 style="border: 1 px solid black; border-collapse: collapse">";
	echo "<tr><th>ID</th>";
	echo "<th>Eesnimi</th>";
	echo "<th>perekonnanimi</th>";
	echo "<th>e-posti aadress</th>";
	echo "<th>Sünnipäev</th>";
	echo "<th>Sugu</th></tr>";
	while($stmt->fetch()){
	echo "<tr><td>" .$id ."</td>";
	echo "<td>" .$firstnameFromDb ."</td>";
	echo "<td>" .$lastnameFromDb ."</td>";
	echo "<td>" .$emailFromDb ."</td>";
	echo "<td>" .$birthdayFromDb ."</td>";
	echo "<td>" .$genderFromDb ."</td></tr>";
	</table>
	
}
?>
	
</article>
<footer>Copyright &copy; Rando Aljaste</footer>

</div>
	

</body>
</html>