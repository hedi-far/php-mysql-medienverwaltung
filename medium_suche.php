<?php

include("include_footer.php");

include("include_connection.php");

if( isset($_POST["senden"]) )
{
	$suche="%".$_POST["suche"]."%";
	$sucheV="%".$_POST["sucheV"]."%";
	
}

else
{
	$suche="";
	$sucheV="";
	
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Suchen</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script> 

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

<h1>Suche</h1>
<fieldset>
<!--Beginn Such-Feld -->

<form method="post" action="<?php echo $_SERVER["PHP_SELF"];    ?>">
Suche nach Titel:
<br>
<input type="text" name="suche"> 
<br>

Suche nach VerfasserIn:
<br>
<input type="text" name="sucheV"> 
<br> 
<br>
<input type="submit" name="senden" value="Suchen">
<input type="button" name="abbruch" value="Abbrechen" onclick="location.href = 'login_portal.php'"><br>
</form>

</fieldset>
<!--Ende Such-Feld-->

<?php

//Durchführen der Suche 
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
WHERE 
mediumTitel like :suche AND
mediumVerf like :sucheV 
ORDER BY mediumArt ASC
";

$abfrage=$connection->prepare($sql);//prepare-Befehl ist eine vorgefertigte Funktion
//Benannte Parameter werden befüllt
$abfrage->bindParam(":suche",$suche);
$abfrage->bindParam(":sucheV",$sucheV);

//Abfrage wird ausgeführt
$abfrage->execute();

//Bericht Anfang
$menge =$abfrage->rowCount();

$ausgabe = ($menge == 0) ? "Keine" : $menge;
{
if( isset($_POST["senden"]) )
	

echo $ausgabe . " Datensätze gefunden!";
}//Bericht Ende

//wie wollen wir es geliefert? - Zustellart = Ausgabeart definieren, hier als Objekt
$abfrage->setFetchMode(PDO::FETCH_OBJ);


//"Merk-Variable" zum Zwischenspeichern der Gruppe
$GruppenUeberschrift=""; 

//Beginn Tabelle
$output= "<table>\n";

$output.= "<tr>\n"; //Beginn Tabellenüberschrift
	
	$output.= "\t<th>Titel </th>\n";
	$output.= "\t<th>Verfasser </th>\n";
	$output.= "\t<th>Erscheinungsjahr </th>\n";
	$output.= "\t<th>Erscheinungsort </th>\n";
	$output.= "\t<th>Verlag</th>\n";
	$output.= "\t<th>Standard-Nummer </th>\n";
	$output.= "\t<th>Sprache </th>\n";
	$output.= "\t<th>Beschreibung </th>\n";
	
	
	$output.= "<tr>\n"; //Ende Tabellenüberschrift



while (	$zeile=$abfrage->fetch()	) //Die Zeilenvariable macht aus jedem user ein eigenes Objekt

{
	//Zwischenüberschrift mit jeweiliger Gruppe
		if($zeile->mediumArt<>$GruppenUeberschrift)
		{
		$output.= "<tr><td colspan='9'><h3>$zeile->mediumArt</h3></td></tr>";
		}	
	
	
	$output.= "<tr>\n"; //Beginn Tabellen-Zeile
			
		$GruppenUeberschrift=$zeile->mediumArt;

		$output.= "\t<td>$zeile->mediumTitel </td>\n";	
		$output.= "\t<td>$zeile->mediumVerf</td>\n";
		$output.= "\t<td>$zeile->mediumJahr</td>\n";
		$output.= "\t<td>$zeile->mediumOrt</td>\n";
		$output.= "\t<td>$zeile->mediumVerl</td>\n";
		$output.= "\t<td>$zeile->mediumNr</td>\n";
		$output.= "\t<td>$zeile->mediumSprache</td>\n";
		$output.= "<td>
			<p>
			<div class='mehrinhalt'>
				$zeile->mediumDescr 
				</p>
			</div>
				<p class='aufzu' style='cursor:pointer;'>
				Beschreibung lesen</p>
			</td>";
			
	$output.= "<tr>\n"; //Ende Tabellen-Zeile
}
//Ende Tabelle
$output.= "<table>\n";

if ($menge>0)
{
	echo $output;
}

?>



</div>
</body>
</html>