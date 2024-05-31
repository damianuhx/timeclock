<?php
include ('vars.php');
$today = date('Y-m-d');

if (isset($_POST['id'])){$mitarbeiter_id = mysqli_real_escape_string($db, $_POST['id']);}
if (isset($_POST['filialen_id']))
{
    $filialen_id = mysqli_real_escape_string($db, $_POST['filialen_id']);
}
else
{
    $filialen_id = sql_get('SELECT filialen_id FROM filialen WHERE filialen_name="'.$_GET['filiale'].'" LIMIT 1;');
    $filialen_id = $filialen_id[0]['filialen_id'];
}

?>

<!doctype html>
            <head>
                <title>Stempeluhr</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
                <link rel="stylesheet" type="text/css" media="screen" href="main.css">
            </head>
            <body>
                <div id ="container">
                <header>
                    <h1>Stempeluhr</h1>
                </header>
                <nav>
                <?php echo "<h3>Anmeldung in:</h3><br/><h3>".mysqli_real_escape_string($db, $_GET['filiale'])."</h3>";?>
                </nav>
                <aside>
                    
                </aside>
                <section id="content">
                    
                    
<?php

if (isset($_POST['anmelden']))
{
    $sql = 'SELECT * FROM zeitstempel WHERE mitarbeiter_id = "'.$mitarbeiter_id.'" AND abgemeldet="0000-00-00 00:00:00" AND DATE(angemeldet)="'.$today.'";';
    $daten = mysqli_query($db, $sql);

    if(mysqli_fetch_assoc($daten)){echo $error2;}
    else
    {
        // Name des Mitarbeiters aus Datenbank lesen
        $sql = 'SELECT * FROM mitarbeiter WHERE mitarbeiter_id = '.$mitarbeiter_id.'';
        $daten = mysqli_query($db, $sql);
            
        if(empty($daten)){echo $error1;}
        else {$mitarbeiter = mysqli_fetch_assoc($daten);}
        
    
        $filialen_id = mysqli_real_escape_string($db, $_POST['filialen_id']);
        $sql = 'SELECT * FROM filialen WHERE filialen_id = '.$filialen_id.'';

        $daten = mysqli_query($db, $sql);
        
        $filiale = mysqli_fetch_assoc($daten);
        
        if(empty($filiale)){echo $error1;}
        else
        {
            $eintrag = sql_set("INSERT INTO `zeitstempel` (`zeitstempel_id`, `filialen_id`, `mitarbeiter_id`, `last_updated`, `angemeldet`, `abgemeldet`, `ip_login`) VALUES (NULL, '".$filiale['filialen_id']."', '".$mitarbeiter['mitarbeiter_id']."', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, '0000-00-00 00:00:00', '".$_SERVER['REMOTE_ADDR']."')");
        }
    }
}                  

if ($_POST['abmelden'])
{    
     $sql = 'SELECT * FROM `zeitstempel` WHERE abgemeldet="0000-00-00 00:00:00" AND DATE(angemeldet)="'.$today.'" AND mitarbeiter_id="'.$mitarbeiter_id.'" AND filialen_id="'.$filialen_id.'";';
     $daten = mysqli_query($db, $sql);
     $eintrag = mysqli_fetch_assoc($daten);
     
     if ($eintrag['zeitstempel_id']>0)
     {
        $sql = 'UPDATE `zeitstempel` SET `abgemeldet` = CURRENT_TIMESTAMP, `ip_logout` = "'.$_SERVER['REMOTE_ADDR'].'" WHERE `zeitstempel`.`zeitstempel_id` = "'.$eintrag['zeitstempel_id'].'";';
        $daten = mysqli_query($db, $sql);
     }
     else {echo $error3;}
}

if (isset($_GET['filiale'])){
    $mitarbeiter = array();
    $sql= 'SELECT * FROM filialen NATURAL JOIN werwo NATURAL JOIN mitarbeiterx WHERE aktiv="1" AND filialen_name="'.mysqli_real_escape_string($db, $_GET['filiale']).'";';
    
    $daten = mysqli_query($db, $sql);
    while ($entry = (mysqli_fetch_assoc($daten)))
    {
        $mitarbeiter[]=$entry;
    }
    
    $da=array();
    $weg=array();
    
    foreach ($mitarbeiter as $entry)
    {        
        $sql = 'SELECT * FROM zeitstempel WHERE mitarbeiter_id="'.$entry['mitarbeiter_id'].'" AND DATE(angemeldet)="'.$today.'" AND filialen_id="'.$filialen_id.'" AND DATE(abgemeldet)="0000-00-00";';
        $daten = mysqli_query($db, $sql);
        
        if ($zeiten=mysqli_fetch_assoc($daten))
        {
            $da[]=array_merge ($entry, $zeiten);
        }
        else
        {
            $sql = 'SELECT * FROM zeitstempel WHERE mitarbeiter_id="'.$entry['mitarbeiter_id'].'" AND DATE(angemeldet)="'.$today.'" AND filialen_id="'.$filialen_id.'" ORDER BY abgemeldet DESC';
            $daten = mysqli_query($db, $sql);
            $zeiten=mysqli_fetch_assoc($daten);
            if ($zeiten)
            {
                $time=array();
                $time['seit']=' (seit '.substr($zeiten['abgemeldet'], -8).')';
                $weg[]=array_merge ($entry, $time);
            }
            else
            {
                $time=array();
                $time['seit']='';
                $weg[]=array_merge($entry, $time);
            }
        }
        
    }
    
    echo '<form action="'.$_SERVER['PHP_SELF'].'?filiale='.mysqli_real_escape_string($db, $_GET['filiale']).'" style="float: left; margin-bottom: 2cm;" method="post">';
    echo '<h1><b>Anwesend:</b></h1><br>';
    echo '<select required name="id" size=10 style="width: 300px; font-size:12pt;">';
    foreach ($da as $emp)
    {
        echo '<option name="id" value="'.$emp["mitarbeiter_id"].'">'.$emp["vorname"].' '.$emp["nachname"].' (seit '.substr($emp['angemeldet'], -8).')</option>';
    }
    echo '<input type="hidden" name="filialen_id" value="'.$mitarbeiter[0]['filialen_id'].'" />';
    echo '<br><br>';
    echo '<input type="submit" style="font-size:18pt;" name="abmelden" value="GEHT >>" />';
    echo '</select>';
    echo '</form>';
    
    echo '<form action="'.$_SERVER['PHP_SELF'].'?filiale='.mysqli_real_escape_string($db, $_GET["filiale"]).'" style="float: right; margin-bottom: 2cm;" method="post">';
    echo '<h1><b>Abwesend:</b></h1><br>';
    echo '<select required name="id" size=10 style="width: 300px; font-size:12pt;">';
    foreach ($weg as $emp)
    {
        echo '<option name="id" value="'.$emp["mitarbeiter_id"].'">'.$emp["vorname"].' '.$emp["nachname"].$emp['seit'].'</option>';
    }
    echo '<input type="hidden" name="filialen_id" value="'.$mitarbeiter[0]['filialen_id'].'" />';
    echo '<br><br>';
    echo '<input type="submit" style="font-size:18pt;" name="anmelden" value="<< KOMMT" />';
    echo '</select>';
    echo '</form>';
    
    echo '<footer>';
    echo '<h6 align="center">Stand: '.date('d.m.Y H:i:s').'</h6>';
    echo '<form class="footer" action="'.$_SERVER['PHP_SELF'].'?filiale='.mysqli_real_escape_string($db, $_GET["filiale"]).'" method="post">';
    echo '<input type="hidden" name="filialen_id" value="'.$filialen_id.'" />';
    echo '<input type="submit" name="reload" value="Seite aktualisieren" />';
    echo '</footer>';
}
else
{
    echo "<h3>Keine Filiale ausgew&auml;hlt!</h3> <br>Bitte gehen Sie auf <a href='www.pmd.li/stempeluhr'> www.pmd.li/stempeluhr <a> und w&auml;hlen Sie die Filiale in der Sie arbeiten.";
}


?>

