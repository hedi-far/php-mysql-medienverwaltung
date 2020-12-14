<?php
//Verbindung zur Datenbank aufnehmen
include("include_connection.php");

include("include_footer.php");


$userZuname="";
$userVorname="";
$userMail="";
$userPW="";
$userPW2="";
$userRolle="";
$ok=true;
$bericht="";

if( isset($_POST["absenden"])   )
{

	$userZuname=trim( $_POST["userZuname"]);
	$userVorname=trim( $_POST["userVorname"]);
	$userMail=trim( $_POST["userMail"]);
	$userPW=trim( $_POST["userPW"]);
	$userPW2=trim( $_POST["userPW2"]);
	$userRolle=( $_POST["userRolle"]);
	
			
	if ($userPW<>$userPW2) 
		{
		$ok=false;
		$bericht.="Passwörter stimmen nicht überein! Bitte geben Sie die Daten erneut ein.";
	
		echo "<script>
			alert('$bericht')
		</script>";	
	
		}
	
		
	$userPW=md5( md5($_POST["userPW"] ) ) ;
		
	
	if	($ok===true)
		{	
		$bericht.="User wurde angelegt!";
			
		$sql="
			INSERT INTO user
			( 
			userZuname,
			userVorname,
			userMail,
			userPW,
			userRolle
	
			)
			
			VALUES
			(
			:userZuname,
			:userVorname,
			:userMail,
			:userPW,
			:userRolle
			)
			";
	
	$abfrage=$connection->prepare($sql);
	
	$abfrage->bindParam(":userZuname",$userZuname);
	$abfrage->bindParam(":userVorname",$userVorname);
	$abfrage->bindParam(":userMail",$userMail);
	$abfrage->bindParam(":userPW",$userPW);
	$abfrage->bindParam(":userRolle",$userRolle);

	$abfrage->execute();
		
	} 

}	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Neuen User anlegen</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
</head>
<body>


<div id="wrapper">
<h1>Neuen User anlegen:</h1>

<form action="<?php  echo $_SERVER["PHP_SELF"];   ?>" method="post"> 
Vorname:<br>
<input type="text" name="userVorname" ><br>
Zuname:<br>
<input type="text" name="userZuname" required><br>
E-Mail-Adresse:<br>
<input type="email" name="userMail" required><br>
<br>
Passwort:<br>
<input type="password" name="userPW" required><br>
<br>
Passwort wiederholen:<br>
<input type="password" name="userPW2" required><br>
<br>

Bitte wählen Sie eine Rolle:<br>

<input type="radio" name="userRolle" value="<?php echo $userRolle=0; ?>"> Admin<br>
  <input type="radio" name="userRolle" value="<?php echo $userRolle=1; ?>"> BibliothekarIn<br>
  <input type="radio" name="userRolle" value="<?php echo $userRolle=2; ?>" checked> StudierendeR/Sonstige BenutzerInnen <br>
 

<br>
<input type="submit" name="absenden" value="Speichern" onclick="return confirm('User wirklich anlegen?')">
<input type="button" name="abbruch" value="Abbrechen" onclick="location.href = 'login_portal.php'"><br>

</form>

<?php

//Auswahl der Tabelle und Felder
$sql="
SELECT 
userZuname,
userVorname,
userMail,
userRolle
FROM 
user
";

//Infos aus DB abholen mittels "Einkaufszettel sql";
$abfrage=$connection->query($sql);
//wie wollen wir es geliefert? - Zustellart = Ausgabeart definieren, hier als Objekt
$abfrage->setFetchMode(PDO::FETCH_OBJ);

//Zustellung in einer Tabelle

//Beginn Tabelle
echo "<table>\n";

echo "<h2>Bereits vorhandene User:</h2>";

echo "<tr>\n"; //Beginn Tabellenüberschrift
	
	echo "\t<th>Zuname</th>\n"; 
	echo "\t<th>Vorname</th>\n"; 
	echo "\t<th>Mail-Adresse</th>\n";
	echo "\t<th>Rolle</th>\n";

//Ausgabe:
while (	$zeile=$abfrage->fetch()	) //Die Zeilenvariable macht aus jedem user ein eigenes Objekt
{
	echo "<tr>\n"; //Beginn Tabellen-Zeile 
	
		echo "\t<td>$zeile->userZuname</td>\n";
		echo "\t<td>$zeile->userVorname</td>\n";
		echo "\t<td>$zeile->userMail</td>\n";	
		echo "\t<td>$zeile->userRolle</td>\n";
		
		
	echo "<tr>\n"; //Ende Tabellen-Zeile
}

//Ende Tabelle 
echo "<table>\n";
?>

</div>
</body>
</html>