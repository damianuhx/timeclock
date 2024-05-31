<?php
include('vars.php');

echo '<h1>Datenbank bearbeiten</h1>';
echo '<h3>1. Tabelle ausw&auml;hlen: </h3>';
echo '<form action="'.$_SERVER['PHP_SELF'].'" method="get">'."\n";
echo '<select name="table">'."\n";
foreach ($tables as $table=>$values)
{
  echo '<option ';
  if ($_GET['table']==$table || $_POST['table']==$table){echo 'selected="selected"';}
  echo ' value="'.$table.'">'.$table.'</option>'."\n";
}
echo '</select>'."\n";
//echo '<input type="text" name="condition" value="'.$_GET['condition'].'"/>';
foreach ($tables as $table=>$entries)
{
 
}
echo '<input type="submit" name="search" value="Anzeigen" />'."\n";
echo '</form>'."\n";


if (isset($_GET['table'])){$table=mysqli_real_escape_string($db, $_GET['table']);}
if (isset($_POST['table'])){$table=mysqli_real_escape_string($db, $_POST['table']);}

echo '<h3>2. Eintr&auml;ge mit folgenden Eigenschaften anzeigen: </h3>';
searchfields($table);

 echo '<hr/>';
 
if (isset($_GET['condition']))
{
  $condition=str_replace ( ';', '', $_GET['condition']);
}
else
{
  $condition='';
  foreach ($tables[$table] as $key=>$value)
  {
    if (isset($_POST[$key]) && $_POST[$key]!=="*")
    {
      $condition.=$key.'="'.mysqli_real_escape_string($db, $_POST[$key]);
      $condition.='" AND ';
    }
  }
  $condition = substr($condition, 0, -4);
  if (isset($_POST['textcondition']))
  {
    $condition.=str_replace ( ';', '', $_POST['textcondition']);
  }
}
//o($condition);

if(isset($_POST['button']))
   {
    if ($_POST['button']=='Aendern')
    {
        $sql='UPDATE '.$table;
        $sql.=' SET ';
        foreach ($_POST as $key => $value)
        {
            if ($key !== 'button' && $key!=='table' && substr($key, 0, 4)!=='key_')
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
            $sql.= substr($key, 4).' = "'.mysqli_real_escape_string($db, $_POST[$key]).'" AND ';
          }
        }
        $sql=substr($sql, 0, -5);
    }
    
    elseif ($_POST['button']=='Einfuegen')
    {
        $sql='INSERT INTO '.$table.' (';
        foreach ($_POST as $key => $value)
        {
            if (array_key_exists($key, $types))
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
                $sql.='"'.mysqli_real_escape_string($db, $value).'", ';
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
            $sql.= substr($key,4).' = "'.mysqli_real_escape_string($db, $_POST[$key]).'" AND ';
          }
        }
        $sql=substr($sql, 0, -5);
    }
    
   $sql.=';';
   $lastquery = mysqli_query($db, $sql);
   echo '&Auml;ndere Datenbank: "'.$sql.'"<br/>';

   if ($lastquery){echo 'Datenbank erfolgreich ge&auml;ndert.</b>'."\n";}
   else {echo 'Fehler in der Datenbankabfrage: "'.mysqli_error ($db).'"'."\n";}
   }

$search=sql_get('SELECT * FROM '.$table.' LIMIT 1;');
//display($search[0], $table, 'search', $table, $condition);

$sql='SELECT * FROM '.$table;
if ($condition!=''){$sql.=' WHERE ';}
$sql.=$condition;
$sql.=';';

echo '<h3>3. F&auml;lle einf&uuml;gen, &auml;ndern oder l&ouml;schen: </h3>';
$array=(sql_get($sql));
display($array[0], $table, 'names', $table, $condition);
display($array[0], $table, 'insert', $table, $condition);
if ($_GET['search']!=='Anzeigen' || isset($_POST['button']))
{
 echo '<hr/>';
 //o($sql);
foreach ($array as $entry)
{
display($entry, $table, 'display', $table, $condition);
}
}

?>