<?php
session_start();

//Verbindung zur Datenbank aufnehmen
include("include_connection.php");

include("include_footer.php");

if( isset( $_SESSION["sortierung"] ) ){
	
	$sqlSortierung=$_SESSION["sortierung"];
}

else 

{
	
$sqlSortierung=" ORDER BY mediumTitel ASC";//Standard-Sortierung	
	
}

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
	"mediumVerf",
	"mediumJahr",
	"asc",
	"desc"
	);
	
	//Wenn sich die feld-Variable und die sortierung-Variable im erlaubt array befindet, wird die Sortierung durchgeführt!
	//Wenn nicht, greift die Standard-Sortierung!
	
	if( in_array ($feld,$erlaubt) && in_array ($sortierung,$erlaubt) ) 
	{
		
		$sqlSortierung=" ORDER BY $feld $sortierung";
		$_SESSION["sortierung"]=$sqlSortierung;
		
	}
	
}	

//aktuelle Seite ermitteln

if (  isset($_GET["seite"])    )

{
	//zur Sicherheit nochmals in Zahl umwandeln
	$seite=(int)$_GET["seite"];
}
else 
{
	$seite=1;
}

//Einträge pro Seite festlegen
$eintraege_pro_seite=8;

//Startindex des jeweils 1. Datensatzes pro Seite ermitteln
$start=$seite*$eintraege_pro_seite-$eintraege_pro_seite;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Browse</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<script src="jquery-1.9.1.js"></script>

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
<h1>Alle Medien</h1>
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
$sqlSortierung
LIMIT :erster, :anzahl
";

//$sql.=$sqlSortierung;

//$abfrage=$connection->query($sql);

//Nur bei Limit speziell
$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
$abfrage=$connection->prepare($sql);
$abfrage->bindParam(":erster",$start);
$abfrage->bindParam(":anzahl",$eintraege_pro_seite);
$abfrage->execute();

$abfrage->setFetchMode(PDO::FETCH_OBJ);


//Beginn Tabelle
echo "<table>\n";

echo "<tr>\n"; //Beginn Tabellenüberschrift
	
	
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
	echo "\t<th>Beschreibung</th>\n";
		
echo "<tr>\n"; //Ende Tabellenüberschrift


while (	$zeile=$abfrage->fetch()	) //Die Zeilenvariable macht aus jedem user ein eigenes Objekt
{
	echo "<tr>\n"; //Beginn Tabellen-Zeile 
	
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
			
}

//Ende Tabelle
echo "<table>\n";

//Ermitteln der Anzahl aller Datensätze
// und Erstellen der Seiten-Links
$sql="SELECT count(mediumID) as gesamt FROM medium";
$abfrage=$connection->query($sql);
$abfrage->setFetchMode(PDO::FETCH_OBJ);
$zeile=$abfrage->fetch();

$gesamt=$zeile->gesamt;


//Anzahl der zu erstellenden Seiten ermitteln
$wieviele_seiten=$gesamt / $eintraege_pro_seite;

for($zaehler=0; $zaehler < $wieviele_seiten; $zaehler++)
{
	$seitennummer=$zaehler+1;
	
	//Aktuelle Seite wird hervorgehoben und ist kein Hyperlink mehr
	if ($seite==$seitennummer)
	{
		echo "<strong>$seitennummer&nbsp;</strong> ";
	}
	else 
	{
		echo "<a href='?seite=$seitennummer'>$seitennummer</a>&nbsp;";

	}
	
}

?>

</div>
</body>
</html>