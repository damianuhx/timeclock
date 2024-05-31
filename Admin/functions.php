<?php

$db = mysqli_connect(DB_HOST, DB_BENUTZER, DB_PASSWORT, DB_NAME);
$debug=true;
$alt=false;

$error1='<p>Die Seite konnte leider nicht richtig geladen werden! Um zur&uuml;ck zur Filialenauswahl zu gelangen, bitte klicken Sie <a href="index.php">hier</a><br/></p>';
$error2='<p>Sie sind bereits angemeldet.<br/></p>'."\n";
$error3='<p>Sie sind bereits abgemeldet.<br/></p>'."\n";

$tagessoll=8.5;

$wochentage=array('so', 'mo', 'di', 'mi', 'do', 'fr', 'sa', 'so');
$monate=array('', 'Januar', 'Februar', 'M&auml;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
$monatekurz=array('', 'jan', 'feb', 'mar', 'apr', 'mai', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dez');
$daysecs=24*60*60;
$workingdayhours=8.5;

$layouts=array
(
    'mitarbeiter'=>array
    (
        'mitarbeiter_id'=>'auto',
        'vorname'=>'string',
        'nachname'=>'string',
        'hauptfiliale'=>'key',
        'werwo'=>'list',
        'fest'=>'bool'
    ),
    'zeitstempel'=>array
    (
        'zeitstempel_id'=>'auto',
        'last_updated'=>'void',
        'angemeldet'=>'stamp',
        'abgemeldet'=>'stamp',
        'sortby'=>'angemeldet',
        'displayby'=>'mitarbeiter_id'
    )
    
    
    
);

$types=array(
    'mitarbeiter_id'=>'key',
    'nachname' => 'string',
    'vorname' => 'string',
    'aktiv' => 'bool',
    'anstellungsgrad' => 'int',
    'fest' => 'bool',
    'hauptfiliale' => 'key',
    'error' => 'bool',
    'filialen_id' => 'key',
    'standort' => 'string',
    'angemeldet' => 'date',
    'abgemeldet' => 'date',
    'kommentar' => 'string',
    'filialen_name' => 'string',
    'jahr' => 'int',
    'monat' => 'int',
    'jan'=> 'show',
    'feb' => 'show',
    'mar' => 'show',
    'apr' => 'show',
    'mai' => 'show',
    'jun' => 'show',
    'jul' => 'show',
    'aug' => 'show',
    'sep' => 'show',
    'okt' => 'show',
    'nov' => 'show',
    'dez' => 'show',
    'ferientage' => 'int',
    'wochentag' => 'string',
    'oeffnung' => 'date_s',
    'schliessung' => 'date_s',
    'morgenpausedauer' => 'int',
    'morgenpausestart' => 'date_s',
    'morgenpauseende' => 'date_s',
    'abendpausedauer' => 'int',
    'abendpausestart' => 'date_s',
    'abendpauseende' => 'date_s',
    'mittagdauer' => 'int',
    'mittagstart' => 'date_s',
    'mittagende' => 'date_s',
    'zeitstempel_id' => 'key',
    'angemeldet' => 'date',
    'abgemeldet' => 'date',
    'korrektur_dauer' => 'date_s',
    'korrektur' => 'date_s',
    'arbeitgeber_id' => 'key',
    'arbeitgeber_name' => 'string',
    'ferientage' => 'int',
    'ip_login' => 'show',
    'ip_logout' => 'show',
    'arbeitgeber_id' => 'key',
    'arbeitgeber_name'=>'string',
    'code'=>'string',
    'feiertage_id'=>'key',
    'feiertage_name'=> 'string',
    'feiertage_datum'=> 'date',
    'oeffnungsname_id'=>'key',
    'oeffnungsname'=>'string',
    'spezial_datum'=>'date',
    'spezial_name'=>'string',
    'feiertag'=>'bool',
    'anzeigen'=>'bool',
    'korrekturen_id'=>'key',
    'korrektur_monat'=>'int',
    'korrektur_jahr'=>'int',
    'korrektur_datum'=>'date',
    'korrektur_kommentar'=>'string',
    'national'=>'bool',
    'datum'=>'date'
    );

$tables=array(
    'mitarbeiter'=>array(
        'filialen_id'=>'filialen'), 
    'zeitstempel'=>array(
        'filialen_id'=>'filialen',
        'mitarbeiter_id' => 'mitarbeiter'),
    'ferien'=>array(
        'mitarbeiter_id' => 'mitarbeiter'),
    'oeffnungstypen'=>array(
        'filialen_id'=>'filialen',
        'oeffnungsname_id'=>'oeffnungsname'),
    'normaltage' => array(
        'oeffnungsname_id'=>'oeffnungsname'),
    'spezialtage'=>array(
        'filialen_id'=>'filialen',
        'oeffnungsname_id'=>'oeffnungsname'),
    'filialentage'=>array(
        'feiertage_id'=>'feiertage',
        'filialen_id'=>'filialen',
        'oeffnungsname_id'=>'oeffnungsname'),
    'arbeitstage'=>array(
        'filialen_id' => 'filialen'),
    'werwo'=>array(
        'mitarbeiter_id' => 'mitarbeiter',
        'filialen_id'=>'filialen'),
    'feriensaldo'=>array(
        'mitarbeiter_id' => 'mitarbeiter'),
    'filialen' => array(
        'arbeitgeber_id' => 'arbeitgeber',),
    'korrekturen' => array(
        'filialen_id' => 'filialen')
);

$pkeys=array(
    'mitarbeiter' => array('mitarbeiter_id'),
    'werwo' => array('mitarbeiter_id', 'filialen_id'),
    'filialen' => array('filialen_id'),
    'oeffnungszeiten' => array('filialen_id', 'wochentag'),
    'zeitstempel' => array('zeitstempel_id'),
    'ferien'=>array('mitarbeiter_id', 'jahr', 'monat'),
    'feriensaldo'=>array('mitarbeiter_id', 'jahr'),
    'arbeitstage'=>array('filialen_id', 'jahr'),
    'oeffnungstypen'=>array('oeffnungsname_id', 'filialen_id'),
    'oeffnungsname'=>array('oeffnungsname_id'),
    'spezialtage'=>array('spezial_datum', 'filialen_id'),
    'filialentage'=>array('feiertage_id', 'filialen_id'),
    'feiertage'=>array('feiertage_id'),
    'normaltage'=>array('wochentag', 'filialen_id'),
    'korrekturen'=>array('korrekturen_id'),
    'eintritte'=>array('mitarbeiter_id', 'datum'),
    'austritte'=>array('mitarbeiter_id', 'datum')
);





function sql_get($command)
{
    global $db;
    global $debug;
    
    //if ($debug==true){o($command);}
    $array=array();
    $daten = mysqli_query($db, $command);
    
    while ($entry = mysqli_fetch_assoc($daten) )
    {
        $array[]=$entry;
    }
    return ($array);
}

function o($output)
{
    global $debug;
    if ($debug==true)
    {
        echo '>> '.$output.'<br/>'."\n";
    }
    return true;
}

function sql_set($command)
{
    global $db;
    $return = mysqli_query($db, $command);
    //o($command);
    return ($return);
}


function display($values, $thistable, $purpose='display', $table, $index=0, $fix=array(), $condition='', $displays=array(), $add=true, $delete=true, $id=false)
{

    global $tables;
    global $types;
    global $pkeys;
    
    $link=$_SERVER['PHP_SELF'].'?table='.string2url($table).'&condition='.string2url($condition);
    //if($_SERVER['QUERY_STRING']!==''){$link.='&'.$_SERVER['QUERY_STRING'];}
    foreach($_GET as $key => $value)
    {
        if ($key!=='table' && $key!== 'condition')
        {
            $link.='&';
            $link.=string2url($key);
            $link.='=';
            if ($key=='korrektur'){$link.=string2url(time2min($value));}
            else {$link.=string2url($value);}
        }
    }
    if ($index!==0){$link.='#'.strval($index);}
    
    echo '<form action="'.$link.'" method="post">'."\n";
    echo '<input type="hidden" value="'.$table.'" name="table"/>'."\n";
    if ($purpose=='display' && $add==true)
    {
        echo '<input type="submit" name="button" value="+" />'."\n";
    }
    else if ($purpose=='insert' && count($values) && $add==true)
    {
        echo '<input type="submit" name="button" value="x" />'."\n";
    }
    else if ($purpose=='names' && $add==true)
    {
        echo '<span style="width: 33px; display:inline-block; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"> </span>';          
    }
    
    foreach ($values as $key=>$value)
    {
    if (array_key_exists ( $key, $types))
    {
        $type=$types[$key];
        if (array_key_exists($key, $displays))
        {
            $type=$displays[$key];
        }
        
        if ($purpose!=='names')
        {
            if ($type=='string' || $type=='date' || $type=='date_s' || $type=='time' || $type == 'int')
            {
                if ($type=='time'){$value=substr($value, 10,6); }
                echo '<input style="display: inline-block; ';
                if ($type == 'int'){echo 'width:50px; ';}
                else if ($type == 'date') {echo 'width: 140px; ';}
                else if ($type == 'time' || $type=='date_s') {echo 'width: 70px; '  ;}
                else {echo 'width: 90px; ';}
                echo '" type="text" name="'.$key.'"';
                if ($id)
                {
                    echo ' id="'.$key.'" ';
                }
                if ($purpose=='display'){echo 'value="'.$value.'"';}
                echo '">'."\n";
            }
            
            elseif ($type=='bool')
            {
                echo '<input type="hidden" value="0" name="'.$key.'"/>'."\n";
                echo '<input style="display: inline-block; width: 50px" type="checkbox" value="1" name="'.$key.'"';
                if ($value>0 && $purpose=='display'){echo ' checked';}
                echo '>';
            }
            elseif ($type=='fix')
            {
                echo '<input type="hidden" value="'.$value.'" name="'.$key.'"/>'."\n";
            }
            elseif ($type=='show')
            {
                echo '<span style="width: 50px; display:inline-block; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.$value.'</span>';
                echo '<input type="hidden" value="'.$value.'" name="'.$key.'"/>'."\n";
            }
            elseif ($type=='false')
            {
                echo '<input type="hidden" value="0" name="'.$key.'"/>'."\n";
            }
            elseif ($type=='true')
            {
                echo '<input type="hidden" value="1" name="'.$key.'"/>'."\n";
            }
        }
        if ($purpose=='names' && $type!=='key' && $type!=='fix' && $type!=='false' && $type!=='true')
        {
            echo '<span style="width:';
            if ($type=='date'){echo '143';}
            else if ($type=='string'){echo '93';}
            else if ($type == 'time' || $type=='date_s'){echo '73';}
            else if ($type=='bool' || $type == 'int' || $type=='show') {echo '54';}
            echo 'px; display:inline-block; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.$key.'</span>';
        }
        
        if (in_array($key, $pkeys[$thistable]) && $purpose=='display')
            {
                     echo '<input type="hidden" name="key_'.$key.'" value="'.$value.'">'."\n";
            }
            
        if ($type=='key')
        {
            
            if (count($pkeys[$thistable])>1 || !in_array($key, $pkeys[$thistable]))
            {
                if ($purpose!=='names')
                {
                    $table=$tables[$thistable][$key];
                    
                    $options=array();
                


                    if ($purpose=='insert' && $table=='feiertage')
                    {
                        $options=sql_get('SELECT * FROM '.$table.' WHERE EXTRACT(YEAR FROM feiertage_datum)='.date("Y"));
                    }
                    else
                    {
                        $options = sql_get('SELECT * FROM ' . $table);
                    }

                    echo '<select style="display: inline-block; width: 120px" name="'.$key.'">'."\n";
                    foreach ($options as $option)
                    {
                        $optionstring='';
                        foreach ($option as $entrykey => $entry)
                        {
                            if ($types[$entrykey] == 'string')
                            {
                                $optionstring.=$entry.' ';
                            }
                            if (in_array($entrykey, $pkeys[$table]))
                            {
                                $thisvalue=$entry;
                            }
                        }
                        $optionstring=substr($optionstring,0,-1);
                        echo '<option value="'.$thisvalue.'"';
                        if ($value==$option[$pkeys[$table][0]]) {echo ' selected';}
                        echo '>'.$optionstring."</option>\n";
                    }
            
                    echo "</select>\n";
                }
                if ($purpose=='names')
                {
                    echo '<span style="width:124px; display:inline-block; text-overflow: cut; white-space: nowrap; overflow: hidden;">'.$key.'</span>';
                }
                
            }
            
        }
        $value=next($values);
    }
    }
    foreach ($fix as $key=>$value)
    {
    echo '<input type="hidden" value="'.$value.'" name="'.$key.'"/>'."\n";
    }
    if ($purpose=='display')
    {
        echo '<input type="submit" name="button" value="Aendern" />'."\n";
        if ($delete==true){echo '<input type="submit" name="button" value="Loeschen" />'."\n";}
    }
    else if ($purpose=='insert' && count($values))
    {
        echo '<input type="submit" name="button" value="Einfuegen" />'."\n";
    }
    else if ($purpose=='search')
    {
        echo '<input type="submit" name="button" value="Auswaehlen" />'."\n";
    }
    
    echo '</form>'."\n";
}


function searchfields($tablename)
{
    global $tables;
    global $types;
    
    $options=sql_get('SELECT * FROM '.$tablename.' LIMIT 1;');
    $nonkeys='';
    
    foreach ($options[0] as $column=>$type)
    {
                if (array_key_exists ( $column, $tables[$tablename]))
                {
                    echo '<span style="display: inline-block; width: 143px; ">'.$column.'</span>';
                }
                else
                {
                    $nonkeys.=$column.', ';
                }
                
    }
    
    $nonkeys=substr($nonkeys, 0 , -2);
    echo $nonkeys;
                    
    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">'."\n";
    foreach ($tables[$tablename] as $column=>$type)
    {
        if (array_key_exists ( $column, $tables[$tablename]))
        {
            $options=sql_get('SELECT * FROM '.$tablename.' LIMIT 1;');
            //o ('SELECT * FROM '.$tablename.' LIMIT 1;');
            $fcolumn=$column;
                if (array_key_exists ( $fcolumn, $tables[$tablename]))
                {
                    echo '<select style="display: inline-block; width: 140px;" name="'.$fcolumn.'">'."\n";
                     echo '<option value="*">ALLE</option>';
                    $ftable=sql_get('SELECT * FROM '.$tables[$tablename][$fcolumn]);
                    foreach ($ftable as $fentry)
                    {
                        echo '<option ';
                        if ($_POST[$fcolumn]==$fentry[$tables[$tablename][$fcolumn].'_id']){echo 'selected="selected"';}
                        echo 'value="'.$fentry[$tables[$tablename][$fcolumn].'_id'].'">';
                        foreach ($fentry as $fkey=>$fvalue)
                        {
                            if ($types[$fkey]=='string'){echo $fvalue.' ';}
                        }
                        echo '</option>'."\n";
                    }
                    echo '</select>'."\n";   
                }
            
            
        }
    }
    if (isset($_GET['condition'])){$defaultcondition=$_GET['condition'];}
    if (isset($_POST['textcondition'])){$defaultcondition=$_POST['textcondition'];}
    
    echo '<input type="hidden" name="textcondition" value=" " />'."\n";
    echo "<input type='text' style='width:350px;' name='textcondition' value='".$defaultcondition."' />\n";
    echo '<input type="hidden" name="table" value="'.$tablename.'" />'."\n";
    echo '<input type="submit" name="rawcondition" value="Suchen" />'."\n";
    echo '</form>'."\n";
}

function string2url($string)
{
    $string=str_replace("=", "%3D", $string);
    $string=str_replace(" ", "+", $string);
    $string=str_replace('"', '%22', $string);
    return $string;
}

function process()
{
 global $_POST;
 if ($_POST['button']!=='+')
 {
 global $types;
 global $tables;
 global $pkeys;
 
 $table=$_POST['table'];
 
 //preprocess: add pre_ and post_ values...
 foreach ($_POST as $key=>$value)
 {
    if (substr($key, 0, 4)=='pre_')
    {
        $_POST[substr($key, 4)]=$_POST[$key].$_POST[substr($key, 4)];
    }
    if (substr($key, 0, 5)=='post_')
    {
        $_POST[substr($key, 5)]=$_POST[substr($key, 5)].$_POST[$key];
    }
 }
 
 if(isset($_POST['button']))
   {
 if ($_POST['button']=='Aendern')
    {
        $sql='UPDATE '.$table;
        $sql.=' SET ';
        foreach ($_POST as $key => $value)
        {
            if ($key !== 'button' && $key!=='table' && substr($key, 0, 4)!=='key_' && substr($key, 0, 4)!=='pre_' && substr($key, 0, 5)!=='post_')
            {
                $sql.=$key.'= "'.$value.'", ';
            }
        }
        $sql=substr($sql, 0, -2);
        $sql.=' WHERE ';
        foreach ($_POST as $key=>$value)
        {
          if (substr($key, 0, 4)=='key_')
          {
            $sql.= substr($key, 4).' = "'.$_POST[$key].'" AND ';
            //$sql.= substr($key, 4).' = "'.mysqli_real_escape_string($db, $_POST[$key]).'" AND ';
          }
        }
        $sql=substr($sql, 0, -5);
    }
    
    elseif ($_POST['button']=='Einfuegen')
    {
        $sql='INSERT INTO '.$table.' (';
        foreach ($_POST as $key => $value)
        {
            if (array_key_exists($key, $types) )
            {
                $sql.=$key.', ';
            }
        }
        $sql=substr($sql, 0, -2);
        $sql.=') VALUES (';
        
        foreach ($_POST as $key => $value)
        {
            if (array_key_exists($key, $types))
            {
                $sql.='"'.$value.'", ';
            }
        }
        $sql=substr($sql, 0, -2);
        $sql.=')';
    }
    
    elseif ($_POST['button']=='Loeschen')
    {
        $sql='DELETE FROM '.$table;
        $sql.=' WHERE ';
        foreach ($_POST as $key=>$value)
        {
          if (substr($key, 0, 4)=='key_')
          {
            $sql.= substr($key, 4).' = "'.$_POST[$key].'" AND ';
            //$sql.= substr($key,4).' = "'.mysqli_real_escape_string($db, $_POST[$key]).'" AND ';
          }
        }
        $sql=substr($sql, 0, -5);
        $sql.=';';
    }
   //echo '&Auml;ndere Datenbank: "'.$sql.'"<br/>';

   $lastquery = sql_set($sql);
   /*if ($lastquery){echo 'Datenbank erfolgreich ge&auml;ndert.</b>'."\n";}
   else {echo 'Fehler in der Datenbankabfrage: "'.mysqli_error ($db).'"'."\n";}*/
   
   }
 }
}

function new_mitarbeiter()
{
    $entry=sql_get('SELECT * FROM mitarbeiter WHERE nachname="_neuer" LIMIT 1');
    if (!count($entry)){sql_set ('INSERT INTO mitarbeiter (aktiv, nachname, vorname, fest, anstellungsgrad, filialen_id, code) VALUES (1, "_neuer", "Eintrag", 0, 100, 1, "00000")');}
}

function new_filiale()
{
    global $wochentage;
    $entry=sql_get('SELECT * FROM filialen WHERE filialen_name="_neue Filiale" LIMIT 1');
    if (!count($entry))
    {
        sql_set ('INSERT INTO filialen (filialen_name) VALUES ("_neue Filiale")');
    }
    $filialen_id=sql_get('SELECT filialen_id FROM filialen WHERE filialen_name="_neue Filiale";');
    $filialen_id=$filialen_id[0];
    $filialen_id=$filialen_id['filialen_id'];
    foreach($wochentage as $wochentag)
    {
        if ($wochentag!=='so')
        {
            sql_set ('INSERT INTO oeffnungszeiten (filialen_id, wochentag, mittagdauer, mittagstart, mittagende) VALUES ('.$filialen_id.', "'.$wochentag.'", 30, "11:30:00", "14:45:00");');
        }
    }
}
function fill_days($last, $next)
{
    global $alt;
    global $wochentage;
    global $monatslohn;
    
//insert empty days
    //$daydifference=intval(date('j', $login))-intval(date('j', $datestamp));
    $datestamp=strtotime($last);
    $login=strtotime($next);
    $daydifference=round(($login-$datestamp)/60/60/24);
            
            
    while ($daydifference-->0)
    {
        $datestamp+=60*60*24;
        
        if ($alt==true)
        {
            echo '<tr class="alt last">';
            $alt=false;
        }
        elseif ($alt==false)
        {
            echo '<tr class="last">';
           $alt=true;
        }
           
        echo '<td>-</td>'."\n";
        echo '<td style="text-align: right">'.$wochentage[intval(date('N', $datestamp))].' '.date('d.m.y', $datestamp).'</td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        if ($monatslohn){echo '<td class="short"></td>'."\n";}
        echo '<td class="long"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '<td class="short"></td>'."\n";
        echo '</tr>'."\n";
        
        
    }
            
}

function time2min($time)
{
    $parts=explode(':', $time);
    $min=intval($parts[0]);
    if (count($parts)>1){$min+=$parts[1]*60;}
    return $min;
}


//split
function timestamp2array($string)
{
    $parts=explode ( ' ' , $string, 2);
    $timestamp=array();
    $timestamp['date']=$parts[0];
    $timestamp['time']=$parts[1];
    return $timestamp;
}


//time2sec
function time2sec($time)
{
    $times=explode(":",$time);
    $seconds=0;
    $seconds+=3600*$times[0];
    if ($times[1]){$seconds+=60*$times[1];}
    if ($times[2]){$seconds+=$times[2];}
    //$seconds = strtotime('1970-01-01 ' . $time . 'GMT');
    return $seconds;
}

//sec2time
function sec2time($time)
{
if ($time==0){return('');}
$time+=30;
$minus='';
if ($time<0)
{
    $minus='-';
    $time*=-1;
}
$hour = $time / 3600;    // to get hours
$minute = ($time) / 60 % 60;    // to get minutes
$hour = substr(sprintf('%02d', $hour),0,2);
$minute = substr(sprintf('%02d', $minute),0,2);
$second = substr(sprintf('%02d', $second),0,2);
return ($minus.$hour.':'.$minute);
}

//string2date
function string2date($string, $euro=false)
{
    
    if ($euro)
    {
        $parts=explode('.', $string, 3);
        $date['day']=intval($parts[0]);
        $date['month']=intval($parts[1]);
        $date['year']=intval($parts[2]);
    }
    else
    {
        $parts=explode('-', $string, 3);
        $date['year']=intval($parts[0]);
        $date['month']=intval($parts[1]);
        $date['day']=intval($parts[2]);
    }
    return $date;
}

//date2string
function date2string($date, $euro=false)
{
    if ($euro)
    {
        return ($date['day'].'.'.$date['month'].'.'.$date['year']);
    }
    else
    {
        return ($date['year'].'-'.$date['month'].'-'.$date['day']);
    }
}


//timespan
function timespan($start, $end, $open=0, $close=86400, $max=0)
{
    if ($start<$open){$start=$open;}
    if ($end>$close){$end=$close;}
    $duration=$end-$start;
    if ($duration<0){$duration=0;}
    if ($max==0 || $duration<=$max){return $duration;}
    elseif ($max!==0 && $duration>$max){return $max;}
}

//process day: makes pretransformed timestamp data to rawtable
function process_day($daydata, $shopdata, $staticdata) //shopdata=oeffnungs- und pausenzeiten
{
    //Calculate pauses
    foreach ($daydata['stempel'] as $key=>$line)
    {
        $thisshop=$shopdata[$line['filialen_id']];
        $daydata['stempel'][$key]['gesamt']=timespan($line['login'], $line['logout'], $shopdata[$line['filialen_id']]['oeffnung'], $shopdata[$line['filialen_id']]['schliessung']);


  
 if ($key>0)
        {
            $daydata['stempel'][$key]['pause']=timespan($daydata['stempel'][$key-1]['logout'], $line['login']);
        
            if ($daydata['stempel'][$key-1]['logout']<$thisshop['morgenpauseende'] && $line['login']>$thisshop['morgenpausestart'])
            {
                $daydata['stempel'][$key]['morgenpause']=timespan($daydata['stempel'][$key-1]['logout'], $line['login'], $thisshop['morgenpausestart'], $thisshop['morgenpauseende']);
                $morgenpausedauer=$shopdata[$line['filialen_id']]['morgenpausedauer'];
            }
            if ($daydata['stempel'][$key-1]['logout']<$thisshop['abendpauseende'] && $line['login']>$thisshop['abendpausestart'])
            {
                $daydata['stempel'][$key]['abendpause']=timespan($daydata['stempel'][$key-1]['logout'], $line['login'], $thisshop['abendpausestart'], $thisshop['abendpauseende']);
                $abendpausedauer=$shopdata[$line['filialen_id']]['abendpausedauer'];
            }
            if ($daydata['stempel'][$key-1]['logout']<$thisshop['mittagende'] && $line['login']>$thisshop['mittagstart'])
            {
                $daydata['stempel'][$key]['mittagpause']=timespan($daydata['stempel'][$key-1]['logout'], $line['login'], $thisshop['mittagstart'], $thisshop['mittagende']);
                $mittagdauer=$shopdata[$line['filialen_id']]['mittagdauer'];
            }
        }
    }
    
    //limit pauses and compute total
    $mittagbuffer=false;
    $totalmorgenpause=0;
    $totalabendpause=0;
    $totalpause=0;
    $totalsamstag=0;
    foreach ($daydata['stempel'] as $key=>$line)
    {
        $totalpause+=$line['pause'];
        $morgenpausebuffer=0;
        $abendpausebuffer=0;
        while($shopdata[$line['filialen_id']]['morgenpausedauer']>0 && $line['morgenpause']-->0)
        {
            $shopdata[$line['filialen_id']]['morgenpausedauer']--;
            $morgenpausebuffer++;  
        }
        $daydata['stempel'][$key]['morgenpause']=$morgenpausebuffer;
        $totalmorgenpause+=$morgenpausebuffer;
        

        while($shopdata[$line['filialen_id']]['abendpausedauer']>0 && $line['abendpause']-->0)
        {
            $shopdata[$line['filialen_id']]['abendpausedauer']--;
            $abendpausebuffer++;
            
        }
        $daydata['stempel'][$key]['abendpause']=$abendpausebuffer;
        
        
        
        $totalabendpause+=$abendpausebuffer;
        
        if ($line['mittagpause']>=$shopdata[$line['filialen_id']]['mittagdauer'])
            {$mittagbuffer=true;}
            
        if ($daydata['day_string']=='sa' && $daydata['stempel'][$key]['samstagszulage']==1){$daydata['stempel'][$key]['zulage']=0.1314*$daydata['stempel'][$key]['gesamt'];}
    }
    
    
                
    $daydata['mittag']=$mittagbuffer;
    $daydata['morgenpause']=$totalmorgenpause;
    $daydata['abendpause']=$totalabendpause;
    $daydata['pause']=$totalpause;
    $daydata['samstag']=$totalsamstag;
    $daydata['gesamttotal']=subarray_sum($daydata['stempel'], 'gesamt');
    $daydata['korrekturtotal']=subarray_sum($daydata['stempel'], 'korrektur');
    $daydata['zulagetotal']=subarray_sum($daydata['stempel'], 'zulage');
    $daydata['tagestotal']=$daydata['gesamttotal']+$totalmorgenpause+$totalabendpause;

    
                
    return ($daydata);
}

function calc_table($daydata)
{
    $table=array();
    $entry=array();
    $pausetotal=0;
    $gesamttotal=0;
    $korrekturtotal=0;
    $zulagetotal=0;
    
    foreach ($daydata['stempel'] as $key=>$line)
    {
        //1. Filiale
        $entry['Filiale']=$line['filialenname'];
        //2. Wochentag & Datum
        $entry['Datum']=$daydata['day_string'].' '.$daydata['datum']['day'].'.'.$daydata['datum']['month'].'.'.$daydata['datum']['year'];
        //different rows for all
        //3. kommt
        $entry['Kommt']=sec2time($line['login']);
        //4. geht
        $entry['Geht']=sec2time($line['logout']);
        //5. gesamt=gesamt+morgenpause+abendpause
        $entry['Gesamt']=sec2time($line['gesamt']+$line['morgenpause']+$line['abendpause']);
        //Pause
        $entry['Pause']='';
        //7. Tagestotal=gesamt+korrektur
        $entry['Tagestotal']='';
        //8. Zulage
        $entry['Zulage']='';
        //9. Wochentotal
        $entry['Wochentotal']='';
        //10.Korrektur
        $entry['Korrektur']=sec2time($line['korrektur']);
        //11.Kommentar
        $entry['Kommentar']=$line['kommentar'];
        if (time2sec($entry['Gesamt'])>=0)
        {
            $table[]=$entry;
            //Total
            
            
            if ($line['pause']>0){$pausetotal+=$line['pause'];}
            if ($line['gesamt']>0){$gesamttotal+=$line['gesamt']+$line['morgenpause']+$line['abendpause'];}
            if ($line['korrektur']>0){$korrekturtotal+=$line['korrektur'];}
            if ($line['zulage']>0){$zulagetotal+=$line['zulage'];}
        }
    }
    //6. Pause
    if (count($table)>0)
    {
        $last=count($table)-1;
        //$table[$last]['Pause']=sec2time($pausetotal);
        $table[$last]['Pause']=sec2time($pausetotal);
        //7. Tagestotal=gesamt+korrektur
        $table[$last]['Tagestotal']=sec2time($gesamttotal+$korrekturtotal);
        //8. Zulage
        $table[$last]['Zulage']=sec2time($zulagetotal);
    
        //last week row
        //9. wochentotal
        return $table;
    }
    else
    {
        return array();
    }
}

function layout_table($table, $header)
{
    global $alt;
    //header 
    echo '<table>'."\n";
    echo '<THEAD>'."\n";
    echo '<tr>'."\n";
    foreach ($header as $col)
    {
        echo '<th';
        if ($col !== 'Filiale' && $col!=='Datum' && $col!=='Kommentar'){echo ' class="short"';}
        if ($col=='Kommentar'){echo ' class="long"';}
        if ($col=='Datum'){echo ' style="text-align: right"';}
        echo '>'.$col.'</th>'."\n";
    }
    echo '</tr>'."\n";
    echo '</THEAD>'."\n";
    
    foreach ($table as $part)
    {
        $newday=true;
        foreach ($part as $pos => $line)
        {
            if ($alt==true)
            {
                if ($newday)
                {
                    echo '<tr class="alt last">';
                    $newday=$false;
                }
                else
                {
                    echo '<tr class="alt">';
                }
                $alt=false;
            }
            elseif ($alt==false)
            {
               if ($newday)
               {
                    echo '<tr class="last">';
                    $newday=false;
                }
                //if ($col=='Datum'){echo ' style="text-align: right"';}
                else {echo '<tr>';}
               $alt=true;
            }
           
            foreach ($header as $col)
            {
                echo '<td ';
                if ($col !== 'Filiale' && $col!=='Datum' && $col!=='Kommentar'){echo ' class="short"';}
                if ($col=='Datum'){echo ' style="text-align: right"';}
                if ($col=='Kommentar'){echo ' class="long"';}
                echo '>'.$line[$col].'</td>'."\n";
            }
            echo '</tr>'."\n";
        }  
    }
    echo '</table>'."\n";
}


function layout_line($keys, $values)
{
    if  (isset($values[0]))
    {
        echo '<span style="width: 93px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"><b>'.$keys[0].'</b> </span>';
        echo '<span style="width: 50px; display:inline-block; text-align:right; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.$values[0].'</span>'."\n";
        echo '<span style="width: 160px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"></span>'."\n";
        if  (isset($values[1]))
        {
            echo '<span style="width: 130px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"><b>'.$keys[1].'</b></span>';
            echo '<span style="width: 50px; display:inline-block; text-align:right; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.$values[1].'</span>'."\n";
            if  (isset($values[2]))
            {
                echo '<span style="width: 20px; display:inline-block;"></span>';
                echo '<span style="width: 90px; display:inline-block; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"><b>'.$keys[2].'</b></span> ';
                echo '<span style="width: 50px; display:inline-block; text-align:right; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;">'.$values[2].'</span>'."\n";
            }
        }
    }
    echo '<br/>';
}

function layout_break($lines)
{
    echo '<span style="width: 146px; display:inline-block; text-align:left; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">_________________________________</span>'."\n";
    echo '<span style="width: 157px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"></span>'."\n";
    if ($lines>=2)
    {
        echo '<span style="width: 190px; display:inline-block; text-align:left; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">_________________________________</span>'."\n";
        echo '<span style="width: 5px; display:inline-block; text-align:left; text-overflow: \'.\'; white-space: nowrap; overflow: hidden;"></span>'."\n";
        if ($lines >= 3)
        {
            echo '<span style="width: 152px; display:inline-block; text-align:left; vertical-align:top; text-overflow: \'\'; white-space: nowrap; overflow: hidden;">___________________________________</span>'."\n";
            
        }
    }
    echo '<br/><br/>';
}

function subarray_sum($array, $key)
{
    $buffer=0;
    foreach ($array as $subarray)
    {
        $buffer+=$subarray[$key];
    }
    return $buffer;
}

function montharray($month, $year)
{
    $ndays=date('t', strtotime($year.'-'.$month.'-1'));
    $ndays=range(1, $ndays);
    return $ndays;
}

function us2euro($date)
{
    $buffer=strtotime($date.' 10:00:00');
    $buffer=date('j.n.Y', $buffer);
    return $buffer;
}


/* backup the db OR just a table */
function backup_tables()
{
    $host=DB_HOST;
        $user=DB_BENUTZER;
        $pass=DB_PASSWORT;
        $name=DB_NAME;
	$link = mysql_connect($host,$user,$pass);
	mysql_select_db($name,$link);
	
	//get all of the tables
	$tables = array();
	$result = mysql_query('SHOW TABLES');
	while($row = mysql_fetch_row($result))
	{
	    $tables[] = $row[0];
	}
	
	//cycle through
	foreach($tables as $table)
	{
	    $result = mysql_query('SELECT * FROM '.$table);
	    $num_fields = mysql_num_fields($result);
		
	    $return.= 'DROP TABLE '.$table.';';
	    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
	    $return.= "\n\n".$row2[1].";\n\n";
		
	    for ($i = 0; $i < $num_fields; $i++) 
	    {
		while($row = mysql_fetch_row($result))
		{
		    $return.= 'INSERT INTO '.$table.' VALUES(';
		    for($j=0; $j<$num_fields; $j++) 
		    {
			$row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j<($num_fields-1)) { $return.= ','; }
		    }
		    $return.= ");\n";
		}
	    }
	    $return.="\n\n\n";
	}
	
	//save file
        $filename='db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql';
	$handle = fopen($filename,'w+');
	fwrite($handle,$return);
	fclose($handle);
        return $filename;	
}

?>