<?php
session_start();
error_reporting(0); //zeigt alle Fehler
//error_reporting(0); mit 0 in der Funktion werden keine Fehler angezeigt
include("include_connection.php");

$email="";
$password="";

//Beginn Login-Vorgang
if(isset($_POST["senden"]))
{
	
	//E-Mail überprüfen
	if (  !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)===false  )
	{
			$email=$_POST["email"];
	}
	//Passwort wird gleich verschlüsselt und verschlüsselt abgespeichert
	$password=md5( md5($_POST["password"] ) ) ;
	
	$sql="
	SELECT 
	userID,
	userVorname,
	userZuname,
	userRolle
	FROM user
	WHERE userMail=:email AND userPW=:password
	";
		
	$abfrage=$connection->prepare($sql);//prepare-Befehl ist eine vorgefertigte Funktion
	//Benannte Parameter werden befüllt
	$abfrage->bindParam(":email",$email);
	$abfrage->bindParam(":password",$password);
	
	//Abfrage wird ausgeführt
	$abfrage->execute();

	$abfrage->setFetchMode(PDO::FETCH_OBJ);
	$zeile=$abfrage->fetch(); //ein Objekt wird aufgerufen -> für mehrere Objekte brauche ich eine while-Schleife

	//Wenn der user richtig eingeloggt wurde, wird er ins Portal weitergeleitet
	$_SESSION["userID"]=$zeile->userID;
	$_SESSION["userRolle"]=$zeile->userRolle;
	$_SESSION["userVorname"]=$zeile->userVorname;
	$_SESSION["userZuname"]=$zeile->userZuname;
	
	//Wenn Session NICHT leer, dann ins Portal weiterleiten
	
	if ( !empty ($_SESSION["userID"] ) )
	{
		header("location:login_portal.php");
	}
	else
	{
		echo "<script>
				alert('Anmeldung nicht erfolgreich! Bitte geben Sie die Daten erneut ein.')
			</script>";
	}
	
	
} //Ende Login-Vorgang 

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="wrapper">

<h1>Bibliothek XY - Willkommen!</h1>

<fieldset>
<legend>Bitte melden Sie sich an:</legend>

<form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
E-Mail-Adresse:<br>
<input type="email" name="email">
<br>
Passwort:<br>
<input type="password" name="password">
<input type="submit" name="senden" value="Login">
</form>
</fieldset>

</body>
</html>