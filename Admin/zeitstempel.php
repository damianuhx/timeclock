<link rel="stylesheet" type="text/css" media="print, screen" href="edit.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
    $(document).ready(function() {
        $("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
        $("#datepicker").change(function() {
            $("#angemeldet, #abgemeldet").val($(this).val());
        });

    });
</script>

<?php


include ('vars.php');

//include ('functions.php');

if (isset($_COOKIE['passwort']) && $_COOKIE['passwort']==DB_PASSWORT)
{

    if (isset($_GET['period']))
    {
        $arbeitsjahr=substr($_GET['period'],-4);
        $arbeitsmonat=substr($_GET['period'],0, -5);
    }
    else
    {
        echo 'Angabe von Monat und Jahr fehlt.';
        exit();
    }

    if (isset($_GET['mitarbeiter_id']))
    {
        $mitarbeiter_id=$_GET['mitarbeiter_id'];
    }
    else
    {
        echo 'Kein Mitarbeiter ausgewaehlt'.
        exit();    
    }
    
    if ($_GET['purpose']=='Bearbeiten')
    {

        $selected_mitarbeiter = intval(mysqli_real_escape_string($db, $_GET['mitarbeiter_id']));

        $mitarbeitereintrag = sql_get('SELECT * FROM mitarbeiter WHERE mitarbeiter_id="'.$selected_mitarbeiter.'";');
        $mitarbeitereintrag=$mitarbeitereintrag[0];
        //var_dump($mitarbeitereintrag);
        
        echo '<h1>Bearbeiten der Arbeitszeiten</h1>';
        echo $mitarbeitereintrag['vorname'].' '.$mitarbeitereintrag['nachname'].' ('.$mitarbeitereintrag['mitarbeiter_id'].')<br/><br/>';

        echo '<form action="mitarbeiter.php" method="get">'."\n";
        echo '<input type="hidden" name="mitarbeiter_id" value="'.$selected_mitarbeiter.'" />'."\n";
        echo '<input type="submit" name="purpose" value="Mitarbeiter bearbeiten" />'."\n";
        echo '</form>';

        echo '<form action="mitarbeiter_liste.php" method="post">'."\n";
        echo '<input class="noprint" type="submit" name="purpose" value="Zur&uuml;ck" />'."\n";
        echo '</form>';
    
        echo '<form action="zeitstempel.php" method="get">'."\n";
        echo '<input type="hidden" name="mitarbeiter_id" value="'.$_GET['mitarbeiter_id'].'"/>'."\n";
        echo '<input type="hidden" name="period" value="'.$_GET['period'].'"/>'."\n";
        echo '<input type="hidden" name="purpose" value="Abrechnen"/>'."\n";
        echo '<input type="submit" name="none" value="Abrechnung" />'."\n";
        echo '</form>';

        echo 'Datum setzen: ';
        echo '<input style="display: inline-block; ';
        echo 'width: 140px; ';
        echo '" type="text" name="datepicker"';
        echo 'id="datepicker"';
        echo '"></br><br/>'."\n";

        display(array('filialen_id'=>'', 'mitarbeiter_id'=>$_GET['mitarbeiter_id'], 'angemeldet'=>'', 'abgemeldet'=>'', 'korrektur'=>'', 'kommentar'=>''), 'zeitstempel', 'names', 'zeitstempel', $index, $fix, '', array('error'=>'false', 'mitarbeiter_id'=>'fix'), $add=false);

        display(array('filialen_id'=>'', 'mitarbeiter_id'=>$_GET['mitarbeiter_id'], 'angemeldet'=>'', 'abgemeldet'=>'', 'korrektur'=>'', 'kommentar'=>''), 'zeitstempel', 'insert', 'zeitstempel', $index, $fix, '', array('error'=>'false', 'mitarbeiter_id'=>'fix'), false, true, true);
        $korrekturen=sql_get('SELECT * FROM korrekturen WHERE mitarbeiter_id="'.$selected_mitarbeiter.'" AND korrektur_monat="'.$arbeitsmonat.'" AND korrektur_jahr="'.$arbeitsjahr.'";');


        process();


        //Korrekturen
        $korrekturen=sql_get('SELECT * FROM korrekturen WHERE mitarbeiter_id="'.$selected_mitarbeiter.'" AND korrektur_monat="'.$arbeitsmonat.'" AND korrektur_jahr="'.$arbeitsjahr.'";');
        $korrekturensample=array('mitarbeiter_id'=>$selected_mitarbeiter, 'filialen_id'=>'1', 'korrektur_monat'=>$arbeitsmonat, 'korrektur_jahr'=>$arbeitsjahr, 'korrektur_datum'=>'', 'korrektur_dauer'=>'', 'korrektur_kommentar'=>'');
        echo '<br/><h2>Korrekturen</h2>';
        display($korrekturensample, 'korrekturen', 'names', 'korrekturen', 0, array(), '', array('mitarbeiter_id'=>'fix', 'korrektur_monat'=>'show', 'korrektur_jahr'=>'show'), false, false);
        foreach ($korrekturen as $korrektur)
        {
            display($korrektur, 'korrekturen', 'display', 'korrekturen', 0, array(), '', array('mitarbeiter_id'=>'fix', 'korrektur_monat'=>'show', 'korrektur_jahr'=>'show'), false);
        }
        display($korrekturensample, 'korrekturen', 'insert', 'korrekturen', 0, array(), '', array('mitarbeiter_id'=>'fix', 'korrektur_monat'=>'show', 'korrektur_jahr'=>'show'), false);


        if (isset($_GET['mitarbeiter_id']))
        {
            if (isset($arbeitsmonat) && isset($arbeitsjahr))
            {
                if ($_GET['purpose']=='Bearbeiten')
                {

                    $fix=array();
                    $index=0;   
                    //hole alle zeitstempel nach einlogzeit sortiert
                    $zeitstempel=sql_get('SELECT * FROM zeitstempel WHERE `mitarbeiter_id`="'.$_GET['mitarbeiter_id'].'" AND MONTH(`angemeldet`)="'.$arbeitsmonat.'" AND YEAR(`angemeldet`)="'.$arbeitsjahr.'" ORDER BY `angemeldet`;');
                    $lastentry['angemeldet']='0';
        
                    foreach($zeitstempel as $entry)
                    {
                        //Falls anderes Datum: Setze Datumstitel
                        if (intval(date( 'd', strtotime($entry['angemeldet']))) !== intval(date('d',strtotime($lastentry['angemeldet']))) || $lastentry['angemeldet']=='0')
                        {
                            $index++;
                            echo '<br/>';
                            echo '<h2 id="'.strval($index).'">';
                            echo $wochentage[intval(date( 'N', strtotime($entry['angemeldet'])))].' '.date( 'd.m.Y', strtotime($entry['angemeldet'])).'</h2>';
                            display($entry, 'zeitstempel', 'names', 'zeitstempel', $index, $fix, '', array('error'=>'false', 'mitarbeiter_id'=>'fix', 'angemeldet'=>'time', 'abgemeldet'=>'time'));
                            $fix['pre_angemeldet']=date( 'Y-m-d ', strtotime($entry['angemeldet']));
                            $fix['post_angemeldet']=':00';
                            $fix['pre_abgemeldet']=date( 'Y-m-d ', strtotime($entry['angemeldet']));
                            $fix['post_abgemeldet']=':00';  
                        }
            
                        //Stelle Eintrag dar
                        display($entry, 'zeitstempel', 'display', 'zeitstempel', $index, $fix, '', array('error'=>'false', 'mitarbeiter_id'=>'fix', 'angemeldet'=>'time', 'abgemeldet'=>'time'));
            
                        if ($entry['zeitstempel_id']==$_POST['key_zeitstempel_id'] && $_POST['button']=='+')
                        {
                            display($entry, 'zeitstempel', 'insert', 'zeitstempel', $index, $fix, '', array('error'=>'false', 'mitarbeiter_id'=>'fix', 'angemeldet'=>'time', 'abgemeldet'=>'time'));
                        }
            
                        $lastentry=$entry;
                    }
                }
            }
        }
    }
    /***************************************/
    elseif($_GET['purpose']=='Abrechnen')
    {
        $sql='SELECT * FROM zeitstempel  NATURAL JOIN filialen NATURAL JOIN arbeitgeber';
        $sql.=' WHERE mitarbeiter_id="'.$mitarbeiter_id.'" AND MONTH(angemeldet)= "'.$arbeitsmonat.'" AND YEAR(angemeldet)="'.$arbeitsjahr.'"';
        $sql.=' ORDER BY `angemeldet`;';
        $rawdata=sql_get($sql);
    
    //*******************************
    //ZEITSTEMPEL
    //*******************************
        $timedata=array();
        //Jeder Tag wird gef&#8719;llt mit:
        //date (3x int),
        //day (string(2))
        
        //array von zeitstempeln:
        //logintime (int),
        //logouttime(int),
        //correction(int),
        //shop_id(),
        foreach ($rawdata as $rawentry)
        {
            //angemeldet-split
            $field=timestamp2array($rawentry['angemeldet']);
            $day=string2date($field['date']);
            $day=intval($day['day']);
            $timedata[$day]['datum']=string2date($field['date']);
            $entry['login']=time2sec($field['time']);
            //abgmeldet split
            $field=timestamp2array($rawentry['abgemeldet']);
            $entry['logout']=time2sec($field['time']);
            
            $timedata[$day]['day_string']=$wochentage[intval(date('N', strtotime($rawentry['angemeldet'])))];
            
            $entry['samstagszulage']=intval($rawentry['samstagszulage']);
            $entry['filialen_id']=intval($rawentry['filialen_id']);
            $entry['filialenname']=$rawentry['filialen_name'];
            $entry['korrektur']=time2sec($rawentry['korrektur']);
            $entry['kommentar']=$rawentry['kommentar'];
            $timedata[$day]['stempel'][]=$entry;
        }
    //*******************************
    //MITARBEITERDATEN (STATISCH) 1x
    //*******************************
    
        $sql='SELECT * FROM mitarbeiter NATURAL JOIN filialen NATURAL JOIN arbeitgeber ';
        $sql.='WHERE mitarbeiter_id="'.$mitarbeiter_id.'";';
        $rawdata=sql_get($sql);
        
        $staticdata['vorname']=$rawdata[0]['nachname'];
        $staticdata['nachname']=$rawdata[0]['vorname']; 
        $staticdata['code']=$rawdata[0]['code'];
        $staticdata['grad']=intval($rawdata[0]['anstellungsgrad']);
        $staticdata['filialen_id']=$rawdata[0]['filialen_id'];
        $staticdata['filialen_name']=$rawdata[0]['filialen_name'];
        $staticdata['arbeitgeber']=$rawdata[0]['arbeitgeber_name'];
        $staticdata['fest']=intval($rawdata[0]['fest']);
        
    
    //*******************************
    //OEFFNUNGSZEITEN (alle)
    //*******************************
        $filialen=sql_get('SELECT filialen_id FROM filialen');
        $tage=montharray($arbeitsmonat, $arbeitsjahr);
        //$zeiten=array();
        foreach ($filialen as $filiale)
        {
            $filialen_id=intval($filiale['filialen_id']);
            foreach ($tage as $tag)
            {
                $datum=$arbeitsjahr.'-'.$arbeitsmonat.'-'.$tag;
                $sql='SELECT * FROM feiertage NATURAL JOIN filialentage NATURAL JOIN oeffnungstypen';
                $sql.=' WHERE feiertage_datum="'.$datum.'" AND ((feiertag="1" AND filialen_id="'.$filialen_id.'") OR national="1");';
                $buffer=sql_get($sql);
                if (isset($buffer[0]))
                {
                    //$zeiten[$filialen_id][$tag]=$buffer[0];
                }
                else
                {
                    $sql='SELECT * FROM spezialtage NATURAL JOIN oeffnungstypen';
                    $sql.=' WHERE filialen_id="'.$filialen_id.'" AND spezial_datum="'.$datum.'"; ';
                    $buffer=sql_get($sql);
                    if (isset($buffer[0]))
                    {
                        //$zeiten[$filialen_id][$tag]=$buffer[0];
                    }
                    else
                    {
                        $sql='SELECT * FROM normaltage NATURAL JOIN oeffnungstypen';
                        $sql.=' WHERE filialen_id="'.$filialen_id.'" AND wochentag=WEEKDAY("'.$datum.' 10:00:00")+1;';
                        $buffer=sql_get($sql);
                    }
                }
                
            $buffer=$buffer[0];
            $buffer['oeffnung']=time2sec($buffer['oeffnung']);
            $buffer['schliessung']=time2sec($buffer['schliessung']);
            $buffer['morgenpausedauer']=60*intval($buffer['morgenpausedauer']);
            $buffer['morgenpausestart']=time2sec($buffer['morgenpausestart']);
            $buffer['morgenpauseende']=time2sec($buffer['morgenpauseende']);
            $buffer['abendpausedauer']=60*intval($buffer['abendpausedauer']);
            $buffer['abendpausestart']=time2sec($buffer['abendpausestart']);
            $buffer['abendpauseende']=time2sec($buffer['abendpauseende']);
            $buffer['mittagdauer']=60*intval($buffer['mittagdauer']);
            $buffer['mittagstart']=time2sec($buffer['mittagstart']);
            $buffer['mittagende']=time2sec($buffer['mittagende']);
            $buffer['filialen_id']=intval($buffer['mittagende']);
            $buffer['oeffnungsname_id']=intval($buffer['mittagende']);
            $zeiten[$tag][$filialen_id]=$buffer; 
            }
        }
        
    //******************************
    //VERARBEITUNG
    //******************************
        $fulltable=array();
        
        $montharray=montharray($arbeitsmonat, $arbeitsjahr);
        //foreach ($timedata as $daykey=>$daydata)
        $wochentotal=0;
        foreach ($montharray as $key=>$daykey)
        {
            $strtodate=strtotime($arbeitsjahr.'-'.$arbeitsmonat.'-'.$daykey.' 10:00:00');
            $wochentag=$wochentage[intval(date('N', $strtodate))];
           if (isset($timedata[$daykey]))
           {
                $timedata[$daykey]=process_day($timedata[$daykey], $zeiten[$timedata[$daykey]['datum']['day']], $staticdata);
                $wochentotal+=$timedata[$daykey]['gesamttotal']+$timedata[$daykey]['morgenpause']+$timedata[$daykey]['abendpause'];
                if ($staticdata['fest']==1){$wochentotal+=$timedata[$daykey]['zulagetotal'];}
                $fulltable[]=calc_table($timedata[$daykey]);
           }
           if (!calc_table($timedata[$daykey])) //Feiertage und Sondertage anzeigen
           {
                $sql='SELECT * FROM feiertage NATURAL JOIN filialentage NATURAL JOIN mitarbeiter';
                $sql.=' WHERE (feiertag=1 OR anzeigen=1) AND mitarbeiter_id="'.$mitarbeiter_id.'" AND feiertage_datum="'.date('Y-n-j', $strtodate).'";';
                $heute=sql_get($sql);
                $fulltable[]=array(array('Filiale'=>'', 'Datum'=>$wochentag.' '.$daykey.'.'.$arbeitsmonat.'.'.$arbeitsjahr, 'Kommt'=>'', 'Geht'=>'', 'Gesamt'=>'', 'Pause'=>'', 'Tagestotal'=>'', 'Zulage'=>'', 'Wochentotal'=>'', 'Korrektur'=>'', 'Kommentar'=>$heute[0]['feiertage_name']));
           }
            if (($wochentag=='so' || $key==count($montharray)-1) && $wochentotal>0)
            {
                $fulltable[count($fulltable)-1][count($fulltable[count($fulltable)-1])-1]['Wochentotal']=sec2time($wochentotal);
                $wochentotal=0;
            }
           
        }
        
        
        
        foreach ($timedata as $daykey=>$daydata)
        {

        }
    //*******************************
    //DARSTELLUNG
    //*******************************
        //make header & Title
     
        echo '<form action="mitarbeiter_liste.php" method="post">'."\n";
    echo '<input class="noprint" type="submit" name="purpose" value="Zur&uuml;ck" />'."\n";
    echo '</form>';
    
    echo '<form action="mitarbeiter.php" method="get">'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$_GET['mitarbeiter_id'].'" />'."\n";
    echo '<input class="noprint" type="submit" name="purpose" value="Mitarbeiter bearbeiten" />'."\n";
    echo '</form>';

    echo '<form action="zeitstempel.php" method="get">'."\n";
    echo '<input type="hidden" name="mitarbeiter_id" value="'.$_GET['mitarbeiter_id'].'"/>'."\n";
    echo '<input type="hidden" name="period" value="'.$_GET['period'].'"/>'."\n";
    echo '<input type="hidden" name="purpose" value="Bearbeiten"/>'."\n";
    echo '<input class="noprint" type="submit" name="none" value="Bearbeiten" />'."\n";
    echo '</form>';
    
    echo '<h1>Stundenabrechnung '.$monate[intval($arbeitsmonat)].' '.$arbeitsjahr.'</h1>';
    echo '<b>'.$staticdata['nachname'].' '.$staticdata['vorname'].'</b> (#'.$staticdata['code'].')';
    if ($staticdata['fest']==1)
    {
        echo '<span style="padding-left:68px;"></span>';
        echo '<b>Anstellungsgrad:</b> '.$staticdata['grad'].'%';
    }
    echo '<span style="padding-left:68px;"></span>';
    echo '<b>Firma:</b> '.$staticdata['arbeitgeber'];
    
    echo '<title>';
    echo 'Stundenabrechnung '.$monate[intval($arbeitsmonat)].' '.$arbeitsjahr;
    echo ' | '.$staticdata['vorname'].' '.$staticdata['nachname'].' (#'.$staticdata['code'].') | ';
    if ($fest==1)
    {
        echo $anstellungsgrad.'% | ';
    }

    echo $arbeitgeber;
    echo '</title><br/>';
    
    
        //make table
        //var_dump($fulltable);
        if ($staticdata['fest']==1){layout_table($fulltable, array('Filiale', 'Datum', 'Kommt', 'Geht', 'Gesamt', 'Pause', 'Tagestotal', 'Zulage', 'Wochentotal', 'Korrektur', 'Kommentar'));}
        else {layout_table($fulltable, array('Filiale', 'Datum', 'Kommt', 'Geht', 'Gesamt', 'Pause', 'Tagestotal', 'Wochentotal', 'Korrektur', 'Kommentar'));}
        //make korrrekturen-table
        $sql='SELECT * FROM korrekturen NATURAL JOIN filialen';
        $sql.=' WHERE mitarbeiter_id="'.$mitarbeiter_id.'" AND korrektur_monat="'.$arbeitsmonat.'" AND korrektur_jahr="'.$arbeitsjahr.'";';
        $korrekturen=sql_get($sql);
        $korrtable=array();
        $zusatzkorrektur=0;
        foreach ($korrekturen as $korrektur)
        {
            $line=array();
            $line['Filiale']=$korrektur['filialen_name'];
            $line['Datum']=$wochentage[date('N', strtotime($korrektur['korrektur_datum']))].' '.date('j.n.Y', strtotime($korrektur['korrektur_datum']));
            $line['Korrektur']=substr($korrektur['korrektur_dauer'],  0, -3);
            $zusatzkorrektur+=time2sec($korrektur['korrektur_dauer'])/3600;
            $line['Kommentar']=$korrektur['korrektur_kommentar'];
            $korrtable[][0]=$line;
        }
        if ($korrtable)
{
echo '<br/>-<br/>';
        echo '<h3>Korrekturen Nachtrag</h3>';
        layout_table($korrtable, array('Filiale', 'Datum', 'Korrektur', 'Kommentar'));
 }
        echo '<br/>-<br/>'."\n";
        echo '<br/><br/><div style="page-break-inside: avoid;">';
        
        $woche=0;
        foreach($timedata as $key=>$value)
        {
            $wochenbuffer+=$value['gesamttotal'];
            $woche=date('W', strtotime($datum['year'].'-'.$datum['year'].'-'.$datum['year']));

        }
        $monatstotal=(subarray_sum($timedata, 'gesamttotal')+subarray_sum($timedata, 'morgenpause')+subarray_sum($timedata, 'abendpause'))/3600;
        $zulagetotal=(subarray_sum($timedata, 'zulagetotal'))/3600;
        $korrekturtotal=(subarray_sum($timedata, 'korrekturtotal'))/3600;
        
        


//----------------------------Endabrechnung-------------------
//Stundenabrechnung


        $ferien=sql_get('SELECT * FROM ferien WHERE mitarbeiter_id='.$_GET['mitarbeiter_id'].' AND jahr='.$arbeitsjahr.' AND monat='.$arbeitsmonat);
        $ferien=subarray_sum($ferien, 'ferientage');
        $feriendiesesjahr=sql_get('SELECT * FROM ferien WHERE mitarbeiter_id='.$_GET['mitarbeiter_id'].' AND jahr='.$arbeitsjahr.' AND monat<'.$arbeitsmonat);
        $feriendiesesjahr=subarray_sum($feriendiesesjahr, 'ferientage');
        $ferientotal=sql_get('SELECT * FROM feriensaldo WHERE mitarbeiter_id='.$_GET['mitarbeiter_id'].' AND jahr='.$arbeitsjahr.';');
        $ferientotal=subarray_sum($ferientotal, 'ferientage');
        $feriensaldo=$ferientotal-$feriendiesesjahr;
        
        $stundensaldo=sql_get('SELECT SUM(stundensaldo) AS stundensaldototal FROM stundensaldo WHERE mitarbeiter_id="'.$mitarbeiter_id.'" AND jahr="'.$arbeitsjahr.'" AND monat<"'.$arbeitsmonat.'";');
        $stundensaldo=subarray_sum($stundensaldo, 'stundensaldototal');
        $stundensaldoneu=sql_get('SELECT SUM(stundensaldo) AS stundensaldototal FROM stundensaldo WHERE mitarbeiter_id="'.$mitarbeiter_id.'" AND jahr="'.$arbeitsjahr.'" AND monat<="'.$arbeitsmonat.'";');
        $stundensaldoneu=subarray_sum($stundensaldoneu, 'stundensaldototal');
        
        $iststunden=$monatstotal+$korrekturtotal+$zusatzkorrektur;
        if ($staticdata['fest']==1){$iststunden+=$zulagetotal;}
        $sollstunden=sql_get('SELECT * FROM arbeitstage WHERE filialen_id="'.$staticdata['filialen_id'].'" AND jahr="'.$arbeitsjahr.'";');
        $arbeitstage=$sollstunden[0][$monatekurz[$arbeitsmonat]];
        $sollstunden=$sollstunden[0][$monatekurz[$arbeitsmonat]]*intval($staticdata['grad'])*0.01-$ferien;
        $sollstunden*=$workingdayhours;
        $differenzstunden=$iststunden-$sollstunden;
        sql_set('REPLACE INTO stundensaldo (mitarbeiter_id, jahr, monat, stundensaldo) VALUES ('.$mitarbeiter_id.', '.$arbeitsjahr.', '.$arbeitsmonat.', '.sprintf('%0.2f', $differenzstunden).');');

        $saldoueberstunden=$stundensaldo;
        $ueberstunden=$differenzstunden;
        $totalueberstunden=$saldoueberstunden+$ueberstunden;
        
        $saldoferien=$ferientotal-$feriendiesesjahr;
        $ferien=$ferien;
        $totalferien=$saldoferien-$ferien;
        
        
        layout_line(array('Monatstotal:'), array(sprintf('%0.2f', $monatstotal)));
        if ($staticdata['fest'])
        {
            layout_line(array('Zulage:'), array(sprintf('%0.2f', $zulagetotal)));
            layout_line(array('Korrekturen:'), array(sprintf('%0.2f', $korrekturtotal+$zusatzkorrektur)));
            layout_break(1);
            layout_line(array('Ist-Stunden:', 'Saldo &Uuml;berstunden:', 'Saldo Ferien'), array(sprintf('%0.2f', $iststunden), sprintf('%0.2f', $saldoueberstunden), sprintf('%0.1f', $saldoferien)));
            layout_line(array('Soll-Stunden:*', '&Uuml;berstunden:', 'Ferien:'), array(sprintf('%0.2f', $sollstunden), sprintf('%0.2f', $ueberstunden), sprintf('%0.1f', $ferien)));
            layout_break(3);
            layout_line(array('&Uuml;berstunden:', 'Total &Uuml;berstunden:', 'Total Ferien:'), array(sprintf('%0.2f', $differenzstunden), sprintf('%0.2f', $totalueberstunden), sprintf('%0.1f', $totalferien)));
        }
        else
        {
             layout_line(array('Korrekturen:'), array(sprintf('%0.2f', $korrekturtotal+$zusatzkorrektur)));
             layout_break(1);
             layout_line(array('Ist-Stunden:'), array(sprintf('%0.2f', $iststunden)));
        }
        //linien
        echo '<span style="width: 145px; display:inline-block; text-align:left; text-decoration: line-through; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">_________________________________</span>'."\n";
        echo '<span style="width: 157px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"></span>'."\n";
        if ($staticdata['fest'])
        {
            echo '<span style="width: 190px; display:inline-block; text-align:left; text-decoration: line-through; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">_________________________________</span>'."\n";
            echo '<span style="width: 5px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"></span>'."\n";
            echo '<span style="width: 152px; display:inline-block; text-align:left; text-decoration: line-through; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">___________________________________</span>'."\n";
        
echo '<br><br>*Soll-Stunden=('.$arbeitstage.' Arbeitstage x '.$staticdata['grad'].'% Anstellungsgrad - '.$ferien.' Ferientage) x 8.5 Stunden';
}
        echo '<br/><br/><br/>';
    //Ein- & Austritt:
        $eintritt=sql_get('SELECT * FROM eintritte WHERE MONTH(datum)="'.$arbeitsmonat.'" AND YEAR(datum)="'.$arbeitsjahr.'" AND mitarbeiter_id="'.$mitarbeiter_id.'";');
        if (count($eintritt))
        {
            $eintritt=$eintritt[0]['datum'];
            echo '<span style="width: 53px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"><b>Eintritt:</b></span>';
            echo '<span style="width: 80px; display:inline-block; text-align:right; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.us2euro($eintritt).'</span>'."\n";
            
        }
        $austritt=sql_get('SELECT * FROM austritte WHERE MONTH(datum)="'.$arbeitsmonat.'" AND YEAR(datum)="'.$arbeitsjahr.'" AND mitarbeiter_id="'.$mitarbeiter_id.'";');
        if (count($austritt))
        {
            $austritt=$austritt[0]['datum'];
            echo '<br/>';
            echo '<span style="width: 53px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"><b>Austritt:</b></span>';
            echo '<span style="width: 80px; display:inline-block; text-align:right; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.us2euro($austritt).'</span>'."\n";
         
        }

       
        echo '</div><br/>'."\n";
    }
}
else
{
    echo 'Passwort nicht gesetzt!';
}
?>