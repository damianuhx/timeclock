<!DOCTYPE html>
    
    <head>
        <title>Stundenabrechnungen</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
        <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    </head>
    <body>
    <div id="container">
    <header>
        <h1>Stundenabrechnungen</h1>
    </header>
    <nav>
        
    </nav>
    <aside>
        
    </aside>
    <section id="content">

<?php
include ('vars.php');


$sql = 'SELECT DISTINCT EXTRACT(year FROM angemeldet) AS jahr FROM zeitstempel WHERE EXTRACT(year FROM angemeldet)>0 ORDER BY angemeldet DESC';
$daten = mysqli_query($db, $sql);

?>
    <article>
    <form action="abrechnung.php" method="get">
<label for="select"><h3>Bitte w&auml;hlen Sie den Monat und den Mitarbeiter:<br/></h3></label>
    <select name="jahr">

<?php
    while ($eintrag = (mysqli_fetch_assoc($daten)))
    {
       $jahr = $eintrag['jahr'];
        echo '<option ';
        if ($_GET['jahr']==$jahr){echo 'selected ';}
        echo 'value="'.$jahr.'">'.$jahr.'</option>';
    }
    echo '</select>';
    echo '<select name="monat">';

    for ($i=12; $i>0; $i--)
    {
        echo '<option ';
        if ($_GET['monat']==$i){echo 'selected ';}
        echo 'value="'.$i.'">'.$monate[$i].'</option>';
    }
?>
    </select>
    
    <select name="mitarbeiter">
<?php
    echo '<option value="0">ALLE</option>';
    $daten_mitarbeiter=sql_get('SELECT * FROM mitarbeiter');
    foreach ($daten_mitarbeiter as $entry)
    {
        echo '<option ';
        if ($_GET['mitarbeiter']==$entry['mitarbeiter_id']){echo 'selected ';}
        echo 'value="'.$entry['mitarbeiter_id'].'">'.$entry['nachname'].' '.$entry['vorname'].'</option>';
    }
?>
    </select>
    
    <input type="submit" value="GO">
    </form>

<br><hr><br>
<?php

if (isset($_GET['jahr']) && isset($_GET['monat']) && isset($_GET['mitarbeiter']))
{
$selected_mitarbeiter = intval(mysqli_real_escape_string($db, $_GET['mitarbeiter']));
$arbeitsjahr=$_GET['jahr'];
$arbeitsmonat=$_GET['monat'];

$sql2 = "SELECT * FROM mitarbeiter";
if ($selected_mitarbeiter>0){$sql2.=' WHERE mitarbeiter_id="'.$selected_mitarbeiter.'";';}
$daten2 = mysqli_query($db, $sql2);

echo '<h2>Downloads der Stundenabrechnungen '.$monate[intval($arbeitsmonat)].' '.$arbeitsjahr.'</h2>';

while ($eintrag2 = (mysqli_fetch_assoc($daten2)))
{
    $nachname = ($eintrag2['nachname']); 
    $vorname=($eintrag2['vorname']);
    $mitarbeiter_id=($eintrag2['mitarbeiter_id']);
    $hauptfilialen_id=($eintrag2['filialen_id']);
    $monatslohn=($eintrag2['fest']);
    $hatgearbeitet=false;
    $erroroccurred=false;

    echo '<br>';
    echo '<u>'.$vorname.' '.$nachname.':</u><br>';
    
    $sql = "SELECT * FROM complete WHERE nachname = '".$nachname."' AND vorname = '".$vorname."'";
    if ($arbeitsmonat!=='*'){$sql.= " AND MONTH(angemeldet)= '".$arbeitsmonat."'";}
    if ($arbeitsjahr!=='*'){$sql.= " AND YEAR(angemeldet)= '".$arbeitsjahr."'";}
    $sql.= ' ORDER BY angemeldet;';
    
    $daten = mysqli_query($db, $sql);
    //Erstellt leere Datei mit passendem Dateinamen und Spaltenbeschriftung
    $fileName=$nachname.' '.$vorname.' '.$arbeitsmonat."-".$arbeitsjahr.'.csv';
    $fileHandle = fopen(__DIR__ .'/Dateien/'.$fileName, 'w') or die("can't open file");

    fwrite($fileHandle, "Mitarbeiter;Filiale;Datum;Tag;Kommt;Geht;Gesamt;Pause;Samstagszulage;Tagestotal;Wochentotal;Monatstotal;Monatssoll;Plusstunden");


    //Erstellt arrays f�r Abrechnung
    $date="leer";
    $loginweek="leer";
    $changes=array();
    $works=array();
    $dayworks=array();
    $pauses=array();
    $saturdays=array();
    $monthworks=array();
    $weekworks=array();
    $works=array();
    $working=false;
    $start=0;
    $end=0;
    $beforelunch=false;
    $afterlunch=false;

    $maxmorningpause=$defaultmorningpause*60;
    $maxeveningpause=$defaulteveningpause*60;
    $maxlunch=$defaultlunch*60;
    $paidpause=0;
    $lunch=0;

    
    //////////////////////////////////////////////////////////////////////////////

    
//F�r jedes Einlog/Auslog-Paar werden die Zeiten erfasst und Transformiert und Kennwerte berechnet
    while ($eintrag = (mysqli_fetch_assoc($daten)))
    {
       $login = ($eintrag['angemeldet']);
       $login = strtotime($login);
       $logout = ($eintrag['abgemeldet']);
       $logout = strtotime($logout);
       $loginday=date('d.m.y', $login);
       $logoutday=date('d.m.y', $logout);
       $logintime=date('G:i:s', $login);
       $logouttime=date('G:i:s', $logout);
       $dayname=$wochentage[intval ( date ('w', $login) )];
       $filiale = ($eintrag['filialen_id']);
       $filialenname=$eintrag['filialen_name'];
       $zeitstempel_id=$eintrag['zeitstempel_id'];
       
       $oeffnungszeiten = sql_get('SELECT * FROM oeffnungszeiten WHERE filialen_id = "'.$filiale.'" AND wochentag = "'.$wochentage[date('N', $login)].'";');
       $defaultmorningpause = intval($oeffnungszeiten[0]['morgenpausedauer']);
       $defaulteveningpause = intval($oeffnungszeiten[0]['abendpausedauer']);
       $morningpausestart=strtotime($oeffnungszeiten[0]['morgenpausestart']);
       $morningpauseend=strtotime($oeffnungszeiten[0]['morgenpauseende']);
       $eveningpausestart=strtotime($oeffnungszeiten[0]['abendpausestart']);
       $eveningpauseend=strtotime($oeffnungszeiten[0]['abendpauseende']);
       $defaultlunch = intval($oeffnungszeiten[0]['mittagdauer']);
       $lunchstart=strtotime($oeffnungszeiten[0]['mittagstart']);
       $lunchend=strtotime($oeffnungszeiten[0]['mittagende']);
       
//Falls Login oder Logout vor oder nach Oeffnungs- resp. Schliesszeit setze Zeiten auf diese
        $sql3 = "SELECT * FROM oeffnungszeiten WHERE wochentag ='".$dayname."' AND filialen_id='".$filiale."';"; 
        $daten3 = mysqli_query($db, $sql3);
        $openday=false;
        while ($eintrag3 = (mysqli_fetch_assoc($daten3)))
        {
            $open = (date('y-m-d', $login).' '.$eintrag3['oeffnung']);
            $open = strtotime($open);
            $close = (date('y-m-d', $logout).' '.$eintrag3['schliessung']);
            $close = strtotime($close);
            if ($login<$open){$login=$open;}
            else if ($login>$close){$login=$close;}
            if ($logout<$open){$logout=$open;}
            else if ($logout>$close){$logout=$close;}
            $openday=true;
        }
       
//F�llt die leere Datei mit Arbeitszeiten (f�r jedes Einlog/Auslog-Paar ein Eintrag)
//Falls an einem anderen Tag ausgelogt als eingelogt wurde wird der Eintrag nicht verarbeitet und der Benutzer darauf hingewiesen diese Angaben zu �berpr�fen.
       if ($loginday==$logoutday)
       {
        if ($openday==true)
        {
            if ($date==$loginday || $date=='leer')
            {
                if (strtotime($logintime)<$lunchstart){$beforelunch=true;}
                if (strtotime($logouttime)>$lunchend){$afterlunch=true;}
            }
            if ($date!=$loginday && $date!="leer")
            {    
//Erg�nze Tagestotal
//Tageszusammenfassungscode 1:1 nochmals am ende des prgs
                fwrite($fileHandle, ";".sec2time(array_sum ( $works )));
                $weekworks[]=array_sum ( $works );
                $works=array();
                if ($beforelunch && $afterlunch && $lunch/60 <= 29) {echo('Zu kurze Mittagspause gemacht am '.$lastloginday.': '.intval($lunch/60).' Minuten.<br>');}
                $lunch=0;
                $beforelunch=false;
                $afterlunch=false;
                if (strtotime($logintime)<$lunchstart){$beforelunch=true;}
                if (strtotime($logouttime)>$lunchend){$afterlunch=true;}
            
//falls andere Woche: setze Wochentotal
                if (date('W', $login) != $loginweek && $loginweek!='leer')
                {
                    fwrite($fileHandle, ";".sec2time(array_sum ( $weekworks )));
                    $monthworks[]=array_sum ( $weekworks );
                    $weekworks=array();
                }
            }
            if ($date!=$loginday)
            {
//Setze Datum und Pausen zur�ck
                $date=$loginday;
                $working=true;
                $maxmorningpause=$defaultmorningpause*60;
                $maxeveningpause=$defaulteveningpause*60;
                $maxlunch=$defaultlunch*60;
                $start=$login;
                $loginweek=date('W', $login);
                $firstpause=0;
            }
            
//F�llt Arrays f�r Tagesabrechnung
            fwrite($fileHandle, "\n");
            
// Berechne Pause: Ende = login, Start = letztes Logout
            if ($working==true)
            {
                $end=$login;
                $working=false;
                $lastpause=$end-$start;
                $lastpauseentry=$end-$start;
                $firstpause++;
                $starttime=strtotime(date('G:i:s', $start));
                $endtime=strtotime(date('G:i:s', $end));
                
                
                if ($end<$start)
                {
                    echo '<b>Fehler:</b> Mehrere Eintraege fuer die selbe Zeitperiode. Bitte in Datenbank korrigieren: Mitarbeiter: '.$vorname.' '.$nachname.' ('.$mitarbeiter_id.') am '.$loginday.'.<br>';
                    $lastpause=0;
                    $erroroccurred=true;
                    sql_set('UPDATE zeitstempel SET error="1" WHERE zeitstempel_id="'.$zeitstempel_id.'";');
                    sql_set('UPDATE zeitstempel SET error="1" WHERE zeitstempel_id="'.$lastzeitstempel_id.'";');
                }
                
                while ($maxmorningpause > 0 && $lastpause > 0 && $starttime+$paidpause>$morningpausestart && $starttime+$paidpause<$morningpauseend)
                {
                        $maxmorningpause--;
                        $lastpause--;
                        $lastpauseentry--;
                        $paidpause++;  
                }
                while ($maxeveningpause > 0 && $lastpause > 0 && $starttime+$paidpause>$eveningpausestart && $starttime+$paidpause<$eveningpauseend)
                {
        
                    $maxeveningpause--;
                    $lastpause--;
                    $lastpauseentry--;
                    $paidpause++;
                    
                }
                
                
                if ($starttime<$lunchstart && $endtime>$lunchstart){$starttime=$lunchstart;}
                $lastpause=$endtime-$starttime;
                
                while ($maxlunch > 0 && $lastpause > 0 && $starttime+$lunch>=$lunchstart && $starttime+$lunch<=$lunchend)
                {
                    $lunch++;
                    $maxlunch--;
                    $lastpause--;
                }
                
                $lastpause=$end-$start;
                if ($lastpause==1 || $lastpause==-1){$lastpause=0;}
            }
//Berechne Arbeitszeit des Eintrages
            if ($working==false)
            {
                $start=$login;
                $end=$logout;
                $working=true;
                if (date('D', $login)=='sat' && $monatslohn==1){$saturday=0.134*($logout-$login+$paidpause);}
                else {$saturday=0;}
                $works[]=$end-$start+$paidpause+$saturday;
                $saturdays[]=$saturday;
            }
//Setze Anfangszeit der Pause (wird im n�chsten Loop-durchlauf weiterverwendet.)
            if ($working==true)
            {
                $start=$logout;
            }
        
            
//Trage Zeile ein
            $worktime=sec2time($logout-$login+$paidpause);
            fwrite($fileHandle, $nachname.' '.$vorname.';'.$filialenname.';'.$loginday.';'.$dayname.';'.$logintime.';'.$logouttime.';'.$worktime.';'.sec2time($lastpauseentry).';'.sec2time($saturday));
            $hatgearbeitet=true;
            
            $paidpause=0;
            $lastpause=0;
            
        }
        else
        {
            echo '<b>Fehler:</b> Eintrag, an Tag an dem Filiale geschlossen war ('.$loginday.'). Wurde nicht ausgewertet. Mitarbeiter: '.$vorname.' '.$nachname.' ('.$mitarbeiter_id.') <br>';
            sql_set('UPDATE zeitstempel SET error="1" WHERE zeitstempel_id="'.$zeitstempel_id.'";');
            $erroroccurred=true;
        }
       }
        else
        {
             echo '<b>Fehler:</b> Anmeldezeit ('.$loginday.') war an einem anderen Tag als Abmeldezeit ('.$logoutday.'). Bitte pruefen sie diese Angaben von Hand. Mitarbeiter: '.$vorname.' '.$nachname.' ('.$mitarbeiter_id.') <br>';
            sql_set('UPDATE zeitstempel SET error="1" WHERE zeitstempel_id="'.$zeitstempel_id.'";');
            $erroroccurred=true;
        }
    $lastzeitstempel_id=$eintrag['zeitstempel_id'];
    $lastloginday=$loginday;
}

//Endabrechnung: Tag, Woche, Monat
if ($beforelunch && $afterlunch && $lunch/60 <= 29) {echo('Zu kurze Mittagspause gemacht am '.$lastloginday.': '.intval($lunch/60).' Minuten.<br>');}
fwrite($fileHandle, ";".sec2time(array_sum ( $works )));
$weekworks[]=array_sum ( $works );
$works=array();

//setze wochen- und monatstotal
fwrite($fileHandle, ";".sec2time(array_sum ( $weekworks )));
$monthworks[]=array_sum ( $weekworks );
$weekworks=array();
fwrite($fileHandle, ";".sec2time(array_sum ( $monthworks )));

//berechne differenz zum soll:
$monatsist=array_sum ( $monthworks );


$werktagejahr=sql_get('SELECT * FROM arbeitstage WHERE filialen_id="'.$hauptfilialen_id.'" AND jahr="'.date('Y', $login).'";');
$ferientagejahr=sql_get('SELECT * FROM ferien WHERE mitarbeiter_id="'.$mitarbeiter_id.'" AND jahr="'.date('Y', $login).'" AND monat="'.date('n', $login).'";');
$ferientage=0;
if (isset($ferientagejahr[0])){$ferientage=intval($ferientagejahr[0]['ferientage']);}

$werktage=intval($werktagejahr[0][$monatekurz[intval(date('n', $login))]]);
$monatssoll=($werktage-$ferientage)*$tagessoll*60*60;
$monatsdifferenz=$monatsist-$monatssoll;

fwrite($fileHandle, ";".sec2time($monatssoll));
fwrite($fileHandle, ";".sec2time($monatsdifferenz));
fwrite($fileHandle,"\n");
fclose($fileHandle);

//Erstellt Download-Link f�r erstellte Datei
if ($hatgearbeitet==true)
{
    $fileUrlName=str_replace(' ', '%20', $fileName);
    if ($erroroccurred==true){echo '=><a href="http://'.$_SERVER['HTTP_HOST'].'/stempeluhr/bearbeiten.php?table=zeitstempel&rawcondition=Suchen&condition=mitarbeiter_id%3D%22'.$mitarbeiter_id.'%22+AND+error%3D%221%22+AND+YEAR(angemeldet)%3D%22'.$arbeitsjahr.'%22+AND+MONTH(angemeldet)%3D%22'.$arbeitsmonat.'%22">Fehler beheben</a><br/><br/>';}
    else {echo '<br/>';}
    echo '<b>Download: <a href='.$host = 'http://'.$_SERVER['HTTP_HOST'].'/stempeluhr/Dateien/'.str_replace(' ', '%20', $fileName).'>'.$fileName.'</a></b>';
    echo "<br/><br/>";
}
else
{
    echo "F&uuml;r diese Person sind keine Arbeitszeiten eingetragen diesen Monat.<br>";
}

}
} 


?>
</article>
    </section>
    <footer>
        
    </footer>
    </div>
    </body>
</html>