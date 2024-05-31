<?php

include ('vars.php');
$thisyear=date("Y");
$lastyear=$thisyear-1;

$mitarbeiter=sql_get('SELECT * FROM mitarbeiter');

$i=0;
foreach ($mitarbeiter as $person)
{
    $stundensaldo=sql_get('SELECT SUM(stundensaldo) AS stundensaldototal FROM stundensaldo WHERE mitarbeiter_id="'.$person['mitarbeiter_id'].'" AND jahr="'.$lastyear.'";');
    $stundensaldo=subarray_sum($stundensaldo, 'stundensaldototal');
    sql_set('INSERT INTO stundensaldo (stundensaldo, mitarbeiter_id, jahr, monat) VALUES ("'.$stundensaldo.'",  "'.$person['mitarbeiter_id'].'", "'.$thisyear.'", 0)');
}

echo 'Stunden erfolgreich uebertragen.';


?>

