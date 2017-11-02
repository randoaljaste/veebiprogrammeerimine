<?php
	//et pääseks ligi sessioonile ja funktsioonidele
	require("functions.php");
	require("editideafunctions.php");
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
		updateIdea($_POST["id"], test_input($_POST["idea"]), $_POST["ideaColor"]);
		// jään siia samasse
		header("Location: ?id=" .$_POST["id"]);
		exit();
		}
	
	if(isset($_GET["delete"])){
		deleteIdea($_GET["id"]);
		header("Location: usersideas.php");
		exit();
		
	}
	
	$idea = getSingleIdea($_GET["id"]);
	

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
	<p><a href="usersideas.php">Head mõtted</a></p>
  </ul>
</nav>

<article>
  <h1>Kõik süsteemi kasutajad</h1>
	<p>See veebileht on loodud õppetööraames ning ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<h2>Toimeta mõtet</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
		<input name="id" type="hidden" value="<?php echo $_GET["id"]; ?>">
		<label>Hea mõte: </label>
		<textarea name="idea"><?php echo $idea->text; ?></textarea>
		<br>
		<label>Mõttega seonduv värv: </label>
		<input name="ideaColor" type="color" value="<?php echo $idea->color; ?>">
		<br>
		<input name="ideaButton" type="submit" value="Salvesta mõte!">
		<span><?php echo $notice; ?></span>
		
	</form>
	<p><a href="?id=<?= $_GET['id']; ?>&delete=1 ">Kustuta see mõte!</p>
	<!-- <a href="?id=19&delete"> -->
	<hr>
	
	
</article>
<footer>Copyright &copy; Rando Aljaste</footer>

</div>
	

</body>
</html>