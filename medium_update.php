<?php

include("include_footer.php");

//Verbindung zur Datenbank aufnehmen
include("include_connection.php");

$ok=true;
$bericht="";

if ( isset($_GET["mediumID"])    )
{
		$mediumID=(int)$_GET["mediumID"];
}

if ( isset($_POST ["mediumID"])    )
{
		$mediumID=(int)$_POST["mediumID"];
}

//Speichern der Änderungen START
//Der folgende Code wird nur ausgeführt, wenn der senden-Button gedrückt wurde
if( isset($_POST["senden"]) )
{
	$mediumID=(int)$_POST["mediumID"];
	$mediumArt=$_POST["mediumArt"];
	$mediumTitel=$_POST["mediumTitel"];
	$mediumVerf=$_POST["mediumVerf"];
	$mediumJahr=$_POST["mediumJahr"];
	$mediumOrt=$_POST["mediumOrt"];
	$mediumVerl=$_POST["mediumVerl"];
	$mediumNr=$_POST["mediumNr"];
	$mediumSprache=$_POST["mediumSprache"];
	$mediumDescr=$_POST["mediumDescr"];
	

	if	($ok===true)
		{	
		$bericht.="Änderungen gespeichert!";
	
	$sql="
	UPDATE medium SET
	mediumID= :id,
	mediumArt= :art,
	mediumTitel= :titel,
	mediumVerf= :verf,
	mediumJahr= :jahr,
	mediumOrt= :ort,
	mediumVerl= :verl,
	mediumNr= :nr,
	mediumSprache= :sprache,
	mediumDescr= :descr
	WHERE mediumID= :id 
	";
	
	$abfrage=$connection->prepare($sql);//prepare-Befehl ist eine vorgefertigte Funktion
	//Benannte Parameter werden befüllt
	$abfrage->bindParam(":id",$mediumID);
	$abfrage->bindParam(":art",$mediumArt);
	$abfrage->bindParam(":titel",$mediumTitel);
	$abfrage->bindParam(":verf",$mediumVerf);
	$abfrage->bindParam(":jahr",$mediumJahr);
	$abfrage->bindParam(":ort",$mediumOrt);
	$abfrage->bindParam(":verl",$mediumVerl);
	$abfrage->bindParam(":nr",$mediumNr);
	$abfrage->bindParam(":sprache",$mediumSprache);
	$abfrage->bindParam(":descr",$mediumDescr);
	
	
	//Abfrage wird ausgeführt
	$abfrage->execute();
	
	echo "<script>
			alert('$bericht')
		</script>";
	}
	
}
//Speichern der Änderungen ENDE


?>



<!DOCTYPE html>
<html>
<head>
	<title>Datensatz bearbeiten/löschen</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script> 
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>

<script>
tinymce.init({ selector:'textarea' });
</script>
	
</head>
<body>
<div id="wrapper">

<?php

$sql="
SELECT 
mediumID,
mediumArt,
mediumTitel, 
mediumVerf,
mediumJahr,
mediumOrt,
mediumVerl,
mediumNr,
mediumSprache,
mediumDescr
FROM 
medium 
WHERE mediumID= :id
";

$abfrage=$connection->prepare($sql);

$abfrage->bindParam(":id",$mediumID);
	
$abfrage->execute();

$abfrage->setFetchMode(PDO::FETCH_OBJ);
$zeile=$abfrage->fetch(); 


?>

<!--Beginn Formular-->
<h1>Datensatz bearbeiten</h1>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"];    ?>"> 

<fieldset> <!--Rahmen um Formular-->

<br>
<input type="hidden" name="mediumID" value="<?php echo $zeile->mediumID ?>"><br>

Art des Mediums:<br>
<?php
//Array in include_connection.php

echo "<select name='mediumArt'>\n";

foreach($mediumArtArray as $element)

{
	
	if ($zeile->mediumArt==$element)
	{
	echo "<option selected>$element</option>\n";
	}
	else 
	{
	echo "<option>$element</option>\n";
	}
		
}

echo  "</select>\n";
?>
<br>
Titel:<br>
<input type="text" name="mediumTitel" value="<?php echo $zeile->mediumTitel  ?>"><br>

Verfasser:<br>
<input type="text" name="mediumVerf" value="<?php echo $zeile->mediumVerf  ?>"><br>

Erscheinungsjahr:<br>
<input type="text" name="mediumJahr" value="<?php echo $zeile->mediumJahr  ?>"> <br>

Erscheinungsort:<br>
<input type="text" name="mediumOrt" value="<?php echo $zeile->mediumOrt  ?>"><br>

Verlag:<br>
<input type="text" name="mediumVerl" value="<?php echo $zeile->mediumVerl  ?>"><br>

Standard-Nummer:<br>
<input type="text" name="mediumNr" value="<?php echo $zeile->mediumNr  ?>"><br>

Sprache:<br>
<input type="text" name="mediumSprache" value="<?php echo $zeile->mediumSprache  ?>"><br>

Beschreibung:<br>
<textarea name="mediumDescr"><?php echo $zeile->mediumDescr  ?></textarea><br> 

<br>
<br>

<input type="submit" name="senden" value="Speichern" onclick="return confirm('Wollen Sie die Änderungen speichern?')">

<input type="button" name="abbruch" value="Zurück zur Übersicht" onclick="location.href = 'medium_wartung.php'"><br>

</fieldset>
</form>

<!--Ende Formular-->



</div>
</body>
</html>