<?php
include("include_footer.php");

include("include_connection.php");

$sqlSortierung=" ORDER BY mediumID DESC";//Standard-Sortierung

//Wenn einer der Sortier-Links geklickt wurde und die 
//Information feld und Sortierung mit GET übergeben wurden
if( isset( $_GET["feld"] )   && isset( $_GET["sortierung"] )    )
{

	$feld=$_GET["feld"];
	$sortierung=$_GET["sortierung"];
	
	$erlaubt=array(
	"mediumID",
	"mediumArt",
	"mediumTitel",
	"mediumVerfasser",
	"mediumJahr",
	"asc",
	"desc"
	);
	
	//Wenn sich die feld-Variable und die sortierung-Variable im erlaubt array befindet, wird die Sortierung durchgeführt!
	//Wenn nicht, greift die Standard-Sortierung!
	
	if( in_array ($feld,$erlaubt) && in_array ($sortierung,$erlaubt) ) 
	{
		
		$sqlSortierung=" ORDER BY $feld $sortierung";
		
	}
	
}	

//Einfügen neuer Artikel START
if( isset($_POST["senden"]) )
{
	
	$mediumArt=$_POST["mediumArt"];
	$mediumTitel=$_POST["mediumTitel"];
	$mediumVerf=$_POST["mediumVerf"];
	$mediumJahr=$_POST["mediumJahr"];
	$mediumOrt=$_POST["mediumOrt"];
	$mediumVerl=$_POST["mediumVerl"];
	$mediumNr=$_POST["mediumNr"];
	$mediumSprache=$_POST["mediumSprache"];
	$mediumDescr=$_POST["mediumDescr"];
			

	$sql="
		INSERT INTO medium 
			(
			mediumTitel, 
			mediumVerf, 
			mediumJahr, 
			mediumOrt, 
			mediumVerl, 
			mediumArt, 
			mediumNr, 
			mediumSprache, 
			mediumDescr
			
		
		)
			
		VALUES
		(
			
			:titel,
			:verf,
			:jahr,
			:ort,
			:verl,
			:art,
			:nr,
			:sprache,
			:descr
			
		)
	";
	
	
	$abfrage=$connection->prepare($sql);
		
	$abfrage->bindParam(":titel",$mediumTitel);
	$abfrage->bindParam(":verf",$mediumVerf);
	$abfrage->bindParam(":jahr",$mediumJahr);
	$abfrage->bindParam(":ort",$mediumOrt);
	$abfrage->bindParam(":verl",$mediumVerl);
	$abfrage->bindParam(":art",$mediumArt);
	$abfrage->bindParam(":nr",$mediumNr);
	$abfrage->bindParam(":sprache",$mediumSprache);
	$abfrage->bindParam(":descr",$mediumDescr);
		
	$abfrage->execute();
	
}//Einfügen neuer Artikel ENDE

//Löschen eines bestehenden Artikels START
if( isset($_GET["mediumID"])   )
{
	$mediumID=(int)$_GET["mediumID"];
	
	$sql="
	DELETE FROM medium WHERE mediumID= :id
	";
		
	$abfrage=$connection->prepare($sql);//prepare-Befehl ist eine vorgefertigte Funktion
	//Benannte Parameter werden befüllt
	$abfrage->bindParam(":id",$mediumID);
	
	//Abfrage wird ausgeführt
	$abfrage->execute();
	
	//Damit beim Reload die Daten nicht nachgefragt wird
	header("location:medium_wartung.php");
}

//Löschen eines bestehenden Artikels ENDE

?>

<!DOCTYPE html>
<html>
<head>
	<title>Datensatz erstellen/bearbeiten/löschen</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script> 
	<script src="https://cloud.tinymce.com/stable/tinymce.min.js"></script>

<script>
tinymce.init({ selector:'textarea' });
</script>
  

<script>
$(document).ready(function(){	
//Text von "Mehrinhalt" verstecken	
	$(".mehrinhalt").hide();
	$(".aufzu").click(function(){
		var aktuell=$(this);
		$(this).prev().slideToggle(1000,function(){
			
			if(aktuell.text()=="schliessen")
			{
				aktuell.text("Beschreibung lesen");
			}
			else
			{
				aktuell.text("schliessen");
			}
		});	
	});


});
</script>
</head>
<body>
<div id="wrapper">

<h1> Datensatz anlegen/bearbeiten/löschen</h1>

<!--Beginn Formular-->
<form action="<?php  echo $_SERVER["PHP_SELF"]; ?>" method="post"> 

<fieldset> <!--Rahmen um Formular-->

<legend>Neues Medium anlegen</legend>

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
<input type="text" name="mediumTitel" required><br>
<br>
Verfasser:<br>
<input type="text" name="mediumVerf" required> <br>
<br>
Erscheinungsjahr:<br>
<input type="text" name="mediumJahr" > <br>
<br>
Erscheinungsort:<br>
<input type="text" name="mediumOrt" ><br>
<br>
Verlag:<br>
<input type="text" name="mediumVerl" ><br>
<br>
Standard-Nummer:<br>
<input type="text" name="mediumNr" > <br>
<br>
Sprache:<br>
<input type="text" name="mediumSprache" ><br>
<br>
Beschreibung:<br>
<textarea name="mediumDescr">  </textarea> <br> 

<input type="submit" name="senden" value="Speichern" onclick="return confirm('Wollen Sie den Datensatz speichern?')">
<input type="button" name="abbruch" value="Abbrechen" onclick="location.href = 'login_portal.php'"><br>
</fieldset>
</form>
<!--Ende Formular --->

<?php

//Auswahl der Tabelle und Felder
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
FROM medium 
";

$sql.=$sqlSortierung;

//Infos aus DB abholen mittels "Einkaufszettel sql";
$abfrage=$connection->query($sql);
//wie wollen wir es geliefert? - Zustellart = Ausgabeart definieren, hier als Objekt
$abfrage->setFetchMode(PDO::FETCH_OBJ);

//Zustellung in einer Tabelle

//Beginn Tabelle
echo "<table>\n";

echo "<tr>\n"; //Beginn Tabellenüberschrift
	
	echo "\t<th>ID<br>
	<a href='?feld=mediumID&sortierung=asc'>&uArr;</a> 
	<a href='?feld=mediumID&sortierung=desc'>&dArr;</a> 
	</th>\n"; 
	
	echo "\t<th>Medien-Gruppe<br>
	<a href='?feld=mediumArt&sortierung=asc'>&uArr;</a> 
	<a href='?feld=mediumArt&sortierung=desc'>&dArr;</a> 
	</th>\n"; 

	echo "\t<th>Titel<br>
	<a href='?feld=mediumTitel&sortierung=asc'>&uArr;</a> 
	<a href='?feld=mediumTitel&sortierung=desc'>&dArr;</a>
	</th>\n";
	
	echo "\t<th>Verfasser<br>
	<a href='?feld=mediumVerf&sortierung=asc'>&uArr;</a> 
	<a href='?feld=mediumVerf&sortierung=desc'>&dArr;</a>
	</th>\n";
	
	echo "\t<th>Erscheinungsjahr<br>
	<a href='?feld=mediumJahr&sortierung=asc'>&uArr;</a> 
	<a href='?feld=mediumJahr&sortierung=desc'>&dArr;</a>
	</th>\n";
	
	echo "\t<th>Erscheinungsort </th>\n";
	echo "\t<th>Verlag</th>\n";
	echo "\t<th>Standard-Nummer </th>\n";
	echo "\t<th>Sprache </th>\n";
	echo "\t<th>Beschreibung </th>\n";
	
	echo "\t<th>Löschen</th>\n";
	echo "\t<th>Bearbeiten</th>\n";
	
	echo "<tr>\n"; //Ende Tabellenüberschrift

//Ausgabe:
while (	$zeile=$abfrage->fetch()	) //Die Zeilenvariable macht aus jedem user ein eigenes Objekt
{
	echo "<tr>\n"; //Beginn Tabellen-Zeile 
	
		echo "\t<td>$zeile->mediumID </td>\n";
		echo "\t<td>$zeile->mediumArt </td>\n";
		echo "\t<td>$zeile->mediumTitel </td>\n";	
		echo "\t<td>$zeile->mediumVerf</td>\n";
		echo "\t<td>$zeile->mediumJahr</td>\n";
		echo "\t<td>$zeile->mediumOrt</td>\n";
		echo "\t<td>$zeile->mediumVerl</td>\n";
		echo "\t<td>$zeile->mediumNr</td>\n";
		echo "\t<td>$zeile->mediumSprache</td>\n";
		echo "<td>
			<p>
			<div class='mehrinhalt'>
				$zeile->mediumDescr 
				</p>
			</div>
				<p class='aufzu' style='cursor:pointer;'>
				Beschreibung lesen</p>
			</td>";
				
		//Löschen-Feld
		echo "\t<td align='center'><a href='?mediumID=$zeile->mediumID' title='Datensatz löschen' onclick=\"return confirm('Diesen Datensatz wirklich löschen?');\"><i class='fa fa-trash' aria-hidden='true'></i></a></td>\n";
	
		//Bearbeiten-Feld
		echo "\t<td align='center'><a href='medium_update.php?mediumID=$zeile->mediumID' title='Datensatz bearbeiten'><i class='fa fa-edit' aria-hidden='true'></i></a></td>\n";
		
	echo "<tr>\n"; //Ende Tabellen-Zeile
}
//Ende Tabelle
echo "<table>\n";
?>

</div>
</body>
</html>