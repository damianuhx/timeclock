<link rel="stylesheet" type="text/css" media="screen" href="edit.css">
    
<?php
include ('vars.php');

//insert new filiale:
//filialenname
//7xoeffnungszeiten
//1xarbeitstage

if ($_GET['neu']=='Neu')
{
    new_filiale();
}

$filialen=sql_get('SELECT * FROM filialen ORDER BY filialen_name ');

echo '<h2>Filialen</h2>';

echo '<form action="mitarbeiter_liste.php" method="get">'."\n";
echo '<input type="submit" name="neu" value="<< Mitarbeiter" />'."\n";
echo '</form>';
$thisyear=date("Y");
$lastyear=$thisyear-1;

echo '<form action="feiertage.php" method="get">';
echo 'Feiertage: ';
echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$thisyear.'" />';
echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$lastyear.'" />';

echo '</form>';
echo '<form action="filialen_liste.php" method="get">'."\n";
echo '<input type="submit" name="neu" value="Neu" />'."\n";
echo '</form>';



echo '<table>';
echo '<tr>';
echo '<th class="left">Filialenname</th>';
echo '<th class="short">Bearbeiten</th>';
//echo '<th class="short"></th>';
echo '</tr>';
    
foreach($filialen as $entry)
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
    
    echo '<td class="left">'.$entry['filialen_name'].'</td>';
    echo '<td class="short">';
    echo '<form action="filialen.php" method="get">'."\n";
    echo '<input type="hidden" name="filialen_id" value="'.$entry['filialen_id'].'" />'."\n";
    echo '<input type="submit" name="purpose" value="Bearbeiten" />'."\n";
    echo '</form>';
    echo '</td>';

    echo '</tr>';
}
echo '</table>';
?>