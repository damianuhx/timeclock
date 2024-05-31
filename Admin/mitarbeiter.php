<link rel="stylesheet" type="text/css" media="screen" href="edit.css">
    
<?php

include ('vars.php');

if (isset($_COOKIE['passwort']) && $_COOKIE['passwort']==DB_PASSWORT)
{
process();

$zeitstempel=sql_get('SELECT DISTINCT MONTH(angemeldet), YEAR(angemeldet) FROM zeitstempel WHERE mitarbeiter_id='.$_GET['mitarbeiter_id'].' ORDER BY angemeldet DESC;');

echo 'Zeiten: ';
echo '<form action="zeitstempel.php" method="get">'."\n";
    echo '<select name="period">'."\n";
    
    foreach ($zeitstempel as $value)
    {
    echo '<option ';
    echo ' value="'.$value['MONTH(angemeldet)'].'/'.$value['YEAR(angemeldet)'].'">'.$value['MONTH(angemeldet)'].'/'.$value['YEAR(angemeldet)'].'</option>'."\n";
    }
    echo '</select>'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$_GET['mitarbeiter_id'].'" />'."\n";
    echo '<input type="submit" name="purpose" value="Bearbeiten" />'."\n";
    echo '<input type="submit" name="purpose" value="Abrechnen" />'."\n";
    echo '</form>'."\n";


echo '<h1>Mitarbeiter Bearbeiten</h1>';

echo '<form action="mitarbeiter_liste.php" method="post">'."\n";
echo '<input type="submit" name="purpose" value="Mitarbeiter-Liste" />'."\n";
echo '</form>';
    
if (isset($_GET['mitarbeiter_id']))
{
    $mitarbeiter=sql_get('SELECT * FROM mitarbeiter WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
    $filialen=sql_get('SELECT * FROM werwo WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
    $ferien=sql_get('SELECT * FROM ferien WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'" ORDER BY jahr DESC, monat DESC;');
    $filialen_sample=sql_get('SELECT * FROM werwo LIMIT 1;');
    $filialen_sample=$filialen_sample[0];
    $ferien_sample=sql_get('SELECT * FROM ferien LIMIT 1;');
    $ferien_sample=$ferien_sample[0];
    $feriensaldo=sql_get('SELECT * FROM feriensaldo WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'" ORDER BY jahr DESC;');
    $feriensaldo_sample=sql_get('SELECT * FROM feriensaldo LIMIT 1;');
    $eintritte=sql_get('SELECT * FROM eintritte WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
    $austritte=sql_get('SELECT * FROM austritte WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
    
    $feriensaldo_sample=$feriensaldo_sample[0];
    
    $mitarbeiter=$mitarbeiter[0];

    
    //Allgemeine Angaben
    echo '<br/><h2>Allgemeine Angaben</h2>';
    
    display($mitarbeiter, 'mitarbeiter', 'names', 'mitarbeiter', 0, array(), '', array(), false);
    display($mitarbeiter, 'mitarbeiter', 'display', 'mitarbeiter', 0, array(), '', array(), false, false);
    
    
    //Arbeitsorte d. Mitarbeiters
    echo '<br/><h2>Arbeitsorte</h2>';
    display($filialen_sample, 'werwo', 'names', 'werwo', 0, array('mitarbeiter_id'=>$_GET['mitarbeiter_id']), '', array('mitarbeiter_id'=>'fix'), $add=false);
    foreach ($filialen as $filiale){display($filiale, 'werwo', 'display', 'werwo', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);}
    display($filialen_sample, 'werwo', 'insert', 'werwo', 0, array('mitarbeiter_id'=>$_GET['mitarbeiter_id']), '', array('mitarbeiter_id'=>'fix'), $add=false);


    //EINTRITT/AUSTRITT
    echo '<br/><h2>Eintritte</h2>';
    display(array('datum'=>''), 'eintritte', 'names', 'eintritte', 0, array(), '', array(), $add=false);
    foreach ($eintritte as $eintritt){display($eintritt, 'eintritte', 'display', 'eintritte', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);}
    display(array('datum'=>'', 'mitarbeiter_id'=>$_GET['mitarbeiter_id']), 'eintritte', 'insert', 'eintritte', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);
    
    echo '<br/><h2>Austritte</h2>';
    display(array('datum'=>''), 'austritte', 'names', 'austritte', 0, array(), '', array(), $add=false);
    foreach ($austritte as $austritt){display($austritt, 'austritte', 'display', 'austritte', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);}
    display(array('datum'=>'', 'mitarbeiter_id'=>$_GET['mitarbeiter_id']), 'austritte', 'insert', 'austritte', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);
    
    
    //Ferien
    echo '<br/><h2>Ferien</h2>';
    display($ferien_sample, 'ferien', 'names', 'ferien', 0, array(), '', array('mitarbeiter_id'=>'fix', 'jahr'=>'show', 'monat'=>'show'), $add=false);
    foreach ($ferien as $entry){display($entry, 'ferien', 'display', 'ferien', 0, array(), '', array('mitarbeiter_id'=>'fix', 'jahr'=>'show', 'monat'=>'show'), $add=false);}
    $ferien_sample['mitarbeiter_id']=$_GET['mitarbeiter_id'];
    display($ferien_sample, 'ferien', 'insert', 'ferien', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);

    //Feriensaldo
    echo '<br/><h2>Feriensaldo</h2>';
    display($feriensaldo_sample, 'feriensaldo', 'names', 'feriensaldo', 0, array(), '', array('mitarbeiter_id'=>'fix', 'jahr'=>'show'), $add=false);
    foreach ($feriensaldo as $entry){display($entry, 'feriensaldo', 'display', 'feriensaldo', 0, array(), '', array('mitarbeiter_id'=>'fix', 'jahr'=>'show'), $add=false);}
    $feriensaldo_sample['mitarbeiter_id']=$_GET['mitarbeiter_id'];
    display($feriensaldo_sample, 'feriensaldo', 'insert', 'feriensaldo', 0, array(), '', array('mitarbeiter_id'=>'fix'), $add=false);

}
}
else
{
    echo 'Bitte geben Sie zuerst das Datenbank-Passwort ein und vergewissern Sie sich, dass Cookies aktiviert sind.<br/><br/>';
    echo '<form action="index.php" method="post">';
    echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Passwort eingeben" />';
    echo '</form>';
}
?>
           