<link rel="stylesheet" type="text/css" media="screen" href="edit.css">
    
<?php
include ('vars.php');

if (isset($_COOKIE['passwort']) && $_COOKIE['passwort']==DB_PASSWORT)
{

process();

echo '<h1>Filialen Bearbeiten</h1>';

echo '<form action="filialen_liste.php" method="post">'."\n";
echo '<input type="submit" name="purpose" value="Zur&uuml;ck" />'."\n";
echo '</form>';

    $thisyear=date("Y");
    $lastyear=$thisyear-1;

    echo '<form action="feiertage.php" method="get">';
    echo 'Feiertage: ';
    echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$thisyear.'" />';
    echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$lastyear.'" />';

    echo '</form>';

if (isset($_GET['filialen_id']))
{
    $filialen=sql_get('SELECT * FROM filialen WHERE filialen_id="'.$_GET['filialen_id'].'";');
    $oeffnungstypen=sql_get('SELECT * FROM oeffnungstypen WHERE filialen_id="'.$_GET['filialen_id'].'";');
    $spezialtage=sql_get('SELECT * FROM spezialtage WHERE filialen_id="'.$_GET['filialen_id'].'";');
    $normaltage=sql_get('SELECT * FROM normaltage WHERE filialen_id="'.$_GET['filialen_id'].'";');
    $filialentage=sql_get('SELECT * FROM filialentage WHERE filialen_id="'.$_GET['filialen_id'].'";');
    $arbeitstage=sql_get('SELECT * FROM arbeitstage WHERE filialen_id="'.$_GET['filialen_id'].'" ORDER BY jahr;');
    $arbeitstage_sample=sql_get('SELECT * FROM arbeitstage LIMIT 1;');
    $arbeitstage_sample=$arbeitstage_sample[0];
    
    $filiale=$filialen[0];

    //Filialenname
    echo '<br/><h2>Filialenname</h2>';
    display($filiale, 'filialen', 'names', 'filialen', 0, array(), '', array('filialen_id'=>'fix'), false, false);
    display($filiale, 'filialen', 'display', 'filialen', 0, array(), '', array('filialen_id'=>'fix'), false, false);
    $index=0;
    global $wochentage;
    
    
    //oeffnungszeiten
    echo '<br/><h2>&Ouml;ffnungszeiten</h2>';
    display($oeffnungstypen[0], 'oeffnungstypen', 'names', 'oeffnungszeiten', 0, array(), '', array('filialen_id'=>'fix'), false, false);
    foreach ($oeffnungstypen as $oeffnungstyp)
    {
        display($oeffnungstyp, 'oeffnungstypen', 'display', 'oeffnungstypen', 0, array(), '', array('filialen_id'=>'fix'), false);
    }
    display($oeffnungstyp, 'oeffnungstypen', 'insert', 'oeffnungstypen', 0, array(), '', array('filialen_id'=>'fix'), false);
    
    echo '<br/><h3>Regul&auml;re &Ouml;ffnungszeiten</h2>';
    display($normaltage[0], 'normaltage', 'names', 'normaltage', 0, array(), '', array('filialen_id'=>'fix'), false, false);
    foreach ($normaltage as $normaltag)
    {
        display($normaltag, 'normaltage', 'display', 'normaltage', 0, array(), '', array('filialen_id'=>'fix', 'wochentag'=>'show'), false, false);
    }
    
    
    echo '<br/><h3>Sonderverk&auml;ufe</h3>';
    display(array('filialen_id'=>$_GET['filialen_id'], 'spezial_datum'=>'', 'spezial_name'=>'', 'oeffnungsname_id'=>''), 'spezialtage', 'names', 'spezialtage', 0, array(), '', array('filialen_id'=>'fix'), false, false);
    foreach ($spezialtage as $spezialtag)
    {
        display($spezialtag, 'spezialtage', 'display', 'spezialtage', 0, array(), '', array('filialen_id'=>'fix'), false);
    }
    display(array('filialen_id'=>$_GET['filialen_id'], 'spezial_datum'=>'', 'spezial_name'=>'', 'oeffnungsname_id'=>''), 'spezialtage', 'insert', 'spezialtage', 0, array(), '', array('filialen_id'=>'fix'), false);
    
    echo '<br/><h3>Feiertage</h2>';
    display(array('filialen_id'=>$_GET['filialen_id'] , 'feiertage_id'=>'', 'feiertag'=>'', 'anzeigen'=>'', 'oeffnungsname_id'=>''), 'filialentage', 'names', 'filialentage', 0, array(), '', array('filialen_id'=>'fix'), false);
    foreach ($filialentage as $filialentag)
    {
        display($filialentag, 'filialentage', 'display', 'filialentage', 0, array(), '', array('filialen_id'=>'fix'), false);
    }
    display(array('filialen_id'=>$_GET['filialen_id'] , 'feiertage_id'=>'', 'feiertag'=>'', 'anzeigen'=>'', 'oeffnungsname_id'=>''), 'filialentage', 'insert', 'filialentage', 0, array(), '', array('filialen_id'=>'fix'), false);
    
    //berechne arbeitstage
    $allejahre=range(2015, intval(date("Y")));
    foreach($allejahre as $neustesjahr)
    {
        $time=strtotime($neustesjahr.'-1-1 10:00:00'); //hier aktuellster eintrag
        $arbeitstage=array(0,0,0,0,0,0,0,0,0,0,0,0,0);
        while(intval(date('Y', $time))<=$neustesjahr)
        {
            $monat=intval(date('n', $time));
            if (intval(date('N', $time))<=6)
            {
                $diesertag=count(sql_get('SELECT * FROM feiertage NATURAL JOIN filialentage WHERE filialen_id='.$_GET['filialen_id'].' AND feiertag=1 AND feiertage_datum="'.date('Y-n-j', $time).'";'));
                if ($diesertag==0)
                {
                    $arbeitstage[$monat]++;
                }
            }
            if (intval(date('N', $time))==6)
            {
                $arbeitstage[$monat]--;
            }
            $time+=$daysecs;
        }
    
    
    
        sql_set('DELETE FROM arbeitstage WHERE filialen_id="'.$_GET['filialen_id'].'" AND jahr="'.$neustesjahr.'";');
        $sql='INSERT INTO arbeitstage (filialen_id, jahr, jan, feb, mar, apr, mai, jun, jul, aug, sep, okt, nov, dez)';
        $sql.=' VALUES ("'.$_GET['filialen_id'].'", "'.$neustesjahr.'", "'.$arbeitstage[1].'", "';
        $sql.=$arbeitstage[2].'", "'.$arbeitstage[3].'", "'.$arbeitstage[4].'", "'.$arbeitstage[5].'", "'.$arbeitstage[6].'", "';
        $sql.=$arbeitstage[7].'", "'.$arbeitstage[8].'", "'.$arbeitstage[9].'", "'.$arbeitstage[10].'", "'.$arbeitstage[11].'", "';
        $sql.=$arbeitstage[12].'");';
        sql_set($sql);
    }

    
    //arbeitstage
    $arbeitstage=sql_get('SELECT * FROM arbeitstage WHERE filialen_id="'.$_GET['filialen_id'].'" ORDER BY jahr;');
    echo '<br/><h3>Arbeitstage</h3>';
    display($arbeitstage_sample, 'arbeitstage', 'names', 'arbeitstage', 0, array(), '', array('filialen_id'=>'fix'), false);
    foreach ($arbeitstage as $entry)
    {
        display($entry, 'arbeitstage', 'display', 'arbeitstage', 0, array(), '', array('filialen_id'=>'fix', 'jahr'=>'show'), false, false);
    }
    $arbeitstage_sample['filialen_id']=$filialen[0]['filialen_id'];
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
           