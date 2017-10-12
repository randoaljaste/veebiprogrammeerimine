<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	$notice= "";
	
	//kui pole sisseloginud, liigume login lehele
	if(!isset($_SESSION["userId"])){
		header("Location: login.php");
		exit();
	}
	
	//väljalogimine
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: login.php");
		exit();
	}
	
	if(isset($_POST["ideaButton"])){
		
		if(isset($_POST["idea"]) and !empty($_POST["idea"])){
			//echo $_POST["ideaColor"];
			$notice = saveIdea($_POST["idea"], $_POST["ideaColor"]);
		}
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
	<p><a href="usersInfo.php">Kasutajate andmebaas</a></p>
  </ul>
</nav>

<article>
  <h1>Kõik süsteemi kasutajad</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<h2<Lisa oma hea mõte</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<label>Hea mõte: </label>
		<input name="idea" type="text">
		<br>
		<label>Mõttega seonduv värv: </label>
		<input name="ideaColor" type="color">
		<br>
		<input name="ideaButton" type="submit" value="Salvesta mõte!">
		<span><?php echo $notice; ?></span>
		
	</from>
	<hr>
	<div style="width: 40%">
		<?php echo listIdeas(); ?>
	
	</div>
</article>
<footer>Copyright &copy; Rando Aljaste</footer>

</div>
	

</body>
</html>