<?php

function Login()
{
    if(empty($_POST['username']))
    {
        $this->HandleError("Kasutajanime lahter on tühi!");
        return false;
    }
    
    if(empty($_POST['password']))
    {
        $this->HandleError("Salasõna lahte on tühi!!");
        return false;
    }
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if(!$this->CheckLoginInDB($username,$password))
    {
        return false;
    }
    
    session_start();
    
    $_SESSION[$this->GetLoginSessionVar()] = $username;
    
    return true;
}

function CheckLoginInDB($username,$password)
{
    if(!$this->DBLogin())
    {
        $this->HandleError("Logimine ebaõnnestus");
        return false;
    }          
    $username = $this->SanitizeForSQL($username);
    $pwdmd5 = md5($password);
    $qry = "Select name, email from $this->tablename ".
        " where username='$username' and password='$pwdmd5' ".
        " and confirmcode='y'";
    
    $result = mysql_query($qry,$this->connection);
    
    if(!$result || mysql_num_rows($result) <= 0)
    {
        $this->HandleError("Error logging in. ".
            "Kasutajanimi või parool ei sobi!");
        return false;
    }
    return true;
}

?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Logimine</title>
</head>
<body>
<body style="background-color:lightblue;">
<h2>Logi sisse</h2>
<form method="POST">
<input type='hidden' name='submitted' id='submitted' value='1'/>
<label for='username' >Kasutajanimi:</label>
<input type='text' name='username' id='username'  maxlength="50" />
<label for='password' >Salasõna:</label>
<input type='password' name='password' id='password' maxlength="50" />
<input type='submit' name='Submit' value='Sisesta' />
</form>


<h2>Registreeri:</h2>
<form id='register' action='register.php' method='post' 
    accept-charset='UTF-8'>

<input type='hidden' name='submitted' id='submitted' value='1'/>
<label for='frontname' >Eesnimi: </label>
<input name="signupFirstName" type="text" id='name' maxlength="50" />
<br />
<label for='lastname' >Perekonnanimi: </label>
<input name="signupLastName" type="text" id='name' maxlength="50" />
<br />
<label for='gender' >Sugu -- </label>
Mees
<input type="radio" name="gender" value="1">
Naine
<input type="radio" name="gender" value="2">
<br />
<label for='email' >Email:</label>
<input type='text' name='email' id='email' maxlength="50" />
<br />
<label for='username' >Kasutajanimi:</label>
<input name="signupEmail" type="email">
<br />
<label for='password' >Salasõna:</label>
<input type='password' name='password' id='password' maxlength="50" />
<br />
<br />
<input type='submit' name='Submit' value='Registreeri' />

</form>

	
</body>
</html>