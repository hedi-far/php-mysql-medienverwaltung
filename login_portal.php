<?php
session_start();

include("include_connection.php");

$userVorname=$_SESSION["userVorname"]; 
$userZuname=$_SESSION["userZuname"];

if ( empty ($_SESSION["userID"] ) )
	{
		header("location:login.php");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Willkommen!</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="wrapper">

<?php

echo "<h1>Willkommen, $userVorname $userZuname!</h1>";

if (   $_SESSION["userRolle"]==0   ) 
{
	echo "Sie sind angemeldet als AdministratorIn! Was möchten Sie tun?";
}
	
if (   $_SESSION["userRolle"]==1   ) 
{
	echo "Sie sind angemeldet als BibliothekarIn! Was möchten Sie tun?";
}

if (   $_SESSION["userRolle"]==2   ) 
{
	echo "Sie sind angemeldet als StudierendeR! Was möchten Sie tun?";
}

echo "<br>\n";
echo "<br>\n";

//Diesen Link sehen alle Gruppen:
echo "<a href='logout.php' onclick=\"return confirm('Jetzt ausloggen?');\">Logout</a><br>\n";

//Diesen Link sehen alle Gruppen:
echo "<h2>Recherche:</h2>";
echo "<a href='alle_medien.php'>Browse</a><br>\n";
echo "<a href='medium_suche.php'>In der Datenbank suchen</a><br>\n";


//Abfragen User-Rolle. Gruppe 0:
if (   $_SESSION["userRolle"]==0   ) 
	{
	echo "<h2>Administration:</h2>";
	echo "<a href='medium_wartung.php'>Datensatz anlegen/bearbeiten/löschen</a><br>\n";
	echo "<a href='neuer_user.php'>NeueN BenutzerIn anlegen</a><br>\n";
	
	}

//Abfragen User-Rolle. Gruppe 1:
if (   $_SESSION["userRolle"]==1   ) 
{
	echo "<h2>Administration:</h2>";
	echo "<a href='medium_wartung.php'>Datensatz anlegen/bearbeiten/löschen</a><br>\n";
}
echo "<br>\n";





?>


</div>
</body>
</html>