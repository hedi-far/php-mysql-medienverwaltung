<?php

//Datenbankverbindung aufbauen mittels PHP Data Objects - in PHP hinterlegt!
try
{	//Argumente: (Datenbank, Benutzer, Passwort)
	$connection=new PDO (
	"mysql:host=localhost;dbname=medienverwaltung;charset=utf8",
	"xxx",
	"xxx"
	);
}
catch(PDOException $e) //Falls Verbindung nicht aufgebaut werden kann
{
	die("Keine Verbindung möglich");
}

//Artikelgruppen
$mediumArtArray=array(
"Buch",
"Video-DVD",
"E-Book",
"PDF",
"Musik-CD",
"Hörbuch",
"Sonstige"
);

?>