<?php

define ("DB_HOST", "localhost:8889");
define ("DB_BENUTZER", "root");
define ("DB_PASSWORT", "root");
define ("DB_NAME", "usr_web233_8");

$db = mysqli_connect(DB_HOST, DB_BENUTZER, DB_PASSWORT, DB_NAME);
$debug=true;

$error1='<p>Die Seite konnte leider nicht richtig geladen werden! Um zur&uuml;ck zur Filialenauswahl zu gelangen, bitte klicken Sie <a href="index.php">hier</a><br/></p>';
$error2='<p>Sie sind bereits angemeldet.<br/></p>'."\n";
$error3='<p>Sie sind bereits abgemeldet.<br/></p>'."\n";

$tagessoll=8.5;

$wochentage=array('so', 'mo', 'di', 'mi', 'do', 'fr', 'sa', 'so');
$monate=array('', 'Januar', 'Februar', 'M&auml;rz', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');
$monatekurz=array('', 'jan', 'feb', 'mar', 'apr', 'mai', 'jun', 'jul', 'aug', 'sep', 'okt', 'nov', 'dez');

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
    'oeffnung' => 'date',
    'schliessung' => 'date',
    'angemeldet' => 'date',
    'abgemeldet' => 'date',
    'filialen_name' => 'string',
    'jahr' => 'int',
    'monat' => 'int',
    'jan'=> 'int',
    'feb' => 'int',
    'mar' => 'int',
    'apr' => 'int',
    'mai' => 'int',
    'jun' => 'int',
    'jul' => 'int',
    'aug' => 'int',
    'sep' => 'int',
    'okt' => 'int',
    'nov' => 'int',
    'dez' => 'int',
    'ferientage' => 'int',
    'wochentag' => 'string',
    'oeffnung' => 'time',
    'schliessung' => 'time',
    'morgenpausedauer' => 'int',
    'morgenpausestart' => 'time',
    'morgenpauseende' => 'time',
    'abendpausedauer' => 'int',
    'abendpausestart' => 'time',
    'abendpauseende' => 'time',
    'mittagdauer' => 'int',
    'mittagstart' => 'time',
    'mittagende' => 'time',
    'zeitstempel_id' => 'key',
    'angemeldet' => 'date',
    'abgemeldet' => 'date'
    );

$tables=array(
    'mitarbeiter'=>array(
        'hauptfiliale'=>'filialen'), 
    'zeitstempel'=>array(
        'filialen_id'=>'filialen',
        'mitarbeiter_id' => 'mitarbeiter'),
    'ferien'=>array(
        'mitarbeiter_id' => 'mitarbeiter'),
    'oeffnungszeiten'=>array(
        'filialen_id'=>'filialen'),
    'arbeitstage'=>array(
        'filialen_id' => 'filialen'),
    'werwo'=>array(
        'mitarbeiter_id' => 'mitarbeiter',
        'filialen_id'=>'filialen')
);

$pkeys=array(
    'mitarbeiter' => array('mitarbeiter_id'),
    'werwo' => array('mitarbeiter_id', 'filialen_id'),
    'filialen' => array('filialen_id'),
    'oeffnungszeiten' => array('filialen_id', 'wochentag'),
    'zeitstempel' => array('zeitstempel_id'),
    'ferien'=>array('mitarbeiter_id', 'jahr', 'monat'),
    'arbeitstage'=>array('filialen_id', 'jahr')
);

/*$nkeys =array(
    'mitarbeiter' => array('vorname', 'nachname'),
    'werwo' => array(),
    'filialen' => array('filialen_name'),
    'oeffnungszeiten' => array(),
    'zeitstempel' => array()
);*/


function sec2time($time)
{
if ($time==0){return('');}
$minus='';
if ($time<0)
{
    $minus='-';
    $time*=-1;
}
$hour = $time / 3600;    // to get hours
$minute = $time / 60 % 60;    // to get minutes
$second = $time % 60;
//$hour = substr(sprintf('%02d', $hour),0,2);
$hour=floor($hour);
$minute = substr(sprintf('%02d', $minute),0,2);
$second = substr(sprintf('%02d', $second),0,2);
return ($minus.$hour.':'.$minute.':'.$second);
}


function sql_get($command)
{
    global $db;

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
    if ($debug==true){
        echo '>> '.$output.'<br/>'."\n";
    }
    return true;
}

function sql_set($command)
{
    global $db;
    $return = mysqli_query($db, $command);

    return ($return);
}


function display($values, $thistable, $purpose='display', $table, $condition)
{
    global $tables;
    global $types;
    global $pkeys;
    //global $nkeys;
    //o($condition);
    
    $link=$_SERVER['PHP_SELF'].'?table='.string2url($table).'&condition='.string2url($condition);
    echo '<form action="'.$link.'" method="post">'."\n";
    echo '<input type="hidden" value="'.$table.'" name="table"/>'."\n";
    
    foreach ($values as $key=>$value)
    {
    if (array_key_exists ( $key, $types))
    {
        $type=$types[$key];
        
        if ($purpose!=='names')
        {
            if ($type=='string' || $type=='date' || $type=='time' || $type == 'int')
            {
                echo '<input style="display: inline-block; ';
                if ($type == 'int'){echo 'width:50px;';}
                else if ($type == 'date') {echo 'width: 140px;';}
                else if ($type == 'time') {echo 'width: 70px;';}
                else {echo 'width: 90px;';}
                echo '" type="text" name="'.$key.'"';
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
        }
        if ($purpose=='names' && $type!=='key')
        {
            echo '<span style="width:';
            if ($type=='date'){echo '143';}
            else if ($type=='string'){echo '93';}
            else if ($type == 'time'){echo '73';}
            else if ($type=='bool' || $type == 'int') {echo '54';}
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
                
                    /*foreach($pkeys as $pkeykey => $pkey)
                    {
                    
                        if (count($pkey)==1 && $key == $pkey[0])
                        {
                            $table=$pkeykey;
                            break;
                        }
                    }*/
                    $options=sql_get('SELECT * FROM '.$table);
                    
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
    if ($purpose=='display')
    {
        echo '<input type="submit" name="button" value="Aendern" />'."\n";
        echo '<input type="submit" name="button" value="Loeschen" />'."\n";
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

?>