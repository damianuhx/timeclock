<?php

include ('vars.php');
process();

if (isset($_GET['filialen_id']))
{
    echo 'Filiale: '.$_GET['filialen_id'];
    $feiertage=sql_get('SELECT feiertage_id, feiertage_name, feiertage_datum, national FROM feiertage NATURAL JOIN filialentage WHERE filialen_id='.$_GET['filialen_id'].' AND EXTRACT(YEAR FROM feiertage_datum)='.$_GET['anzeigen'].' ORDER BY feiertage_datum DESC;');
}
else
{
    $feiertage=sql_get('SELECT * FROM feiertage  WHERE EXTRACT(YEAR FROM feiertage_datum)='.$_GET['anzeigen'].' ORDER BY feiertage_datum DESC;');
}

echo '<form action="index.php" method="post">'."\n";
echo '<input type="submit" name="purpose" value="Zur&uuml;ck" />'."\n";
echo '</form>';

echo '<h1> Feiertage</h1>';

display($feiertage[0], 'feiertage', 'names', 'feiertage', 0, array(), '', array(), false, false);
foreach ($feiertage as $feiertag)
{
    display($feiertag, 'feiertage', 'display', 'feiertage', 0, array(), '', array(), false);
}
display($feiertage[0], 'feiertage', 'insert', 'feiertage', 0, array(), '', array(), false);
    
?>