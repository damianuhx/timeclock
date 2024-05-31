<link rel="stylesheet" type="text/css" media="screen" href="edit.css">
    
<?php
include ('vars.php');

if ($_GET['neu']=='Neu')
{
    new_mitarbeiter();
}

$mitarbeiter=sql_get('SELECT * FROM mitarbeiter ORDER BY nachname ');
echo '<h2>Mitarbeiter</h2>';

echo '<form action="filialen_liste.php" method="get">'."\n";
echo '<input type="submit" name="filialen" value="<< Filialen" />'."\n";
echo '</form>';
echo '<form action="feiertage.php" method="post">'."\n";
echo '<input type="submit" name="purpose" value="<< Feiertage" />'."\n";
echo '</form>';
echo '<form action="mitarbeiter_liste.php" method="get">'."\n";
echo '<input type="submit" name="neu" value="Neu" />'."\n";
echo '</form>';



echo '<table>';
echo '<tr>';
echo '<th class="left">Name</th>';
echo '<th class="short">Bearbeiten</th>';
echo '<th class="short">L&ouml;schen</th>';
echo '<th>Stundenabrechnung</th>';
echo '</tr>';

foreach($mitarbeiter as $entry)
{
    if ($alt==true){
        echo '<tr class="alt">';
        $alt=false;
    }
    elseif ($alt==false)
    {
        echo '<tr>';
        $alt=true;
    }

    echo '<td class="left">'.$entry['nachname'].' '.$entry['vorname'].'</td>';
    echo '<td class="short">';
    echo '<form action="mitarbeiter.php" method="get">'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$entry['mitarbeiter_id'].'" />'."\n";
    echo '<input type="submit" name="purpose" value="Bearbeiten" />'."\n";
    echo '</form>';
    echo '</td>';
    echo '<td class="short">';
    echo '<form action="loeschen.php" method="get">'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$entry['mitarbeiter_id'].'" />'."\n";
    echo '<input type="submit" name="purpose" value="Loeschen" />'."\n";
    echo '</form>';
    echo '</td>';
    echo '<td>';
    
    $zeitstempel=sql_get('SELECT DISTINCT MONTH(angemeldet), YEAR(angemeldet) FROM zeitstempel WHERE mitarbeiter_id='.$entry['mitarbeiter_id'].' ORDER BY angemeldet DESC;');
        
    
    echo '<form action="zeitstempel.php" method="get">'."\n";
    echo '<select name="period">'."\n";
    
    
    
    foreach ($zeitstempel as $value)
    {
    echo '<option ';
    echo ' value="'.$value['MONTH(angemeldet)'].'/'.$value['YEAR(angemeldet)'].'">'.$value['MONTH(angemeldet)'].'/'.$value['YEAR(angemeldet)'].'</option>'."\n";
    }
    echo '</select>'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$entry['mitarbeiter_id'].'" />'."\n";
    echo '<input type="submit" name="purpose" value="Bearbeiten" />'."\n";
    echo '<input type="submit" name="purpose" value="Abrechnen" />'."\n";
    echo '</form>'."\n";
    
    echo '</td>';
    echo '</tr>';
}

?>