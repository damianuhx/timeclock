<?php
include ('vars.php');
if (isset($_COOKIE['passwort']) && $_COOKIE['passwort']==DB_PASSWORT)
{
    if (isset($_GET['mitarbeiter_id']))
    {
        $mitarbeiter=sql_get('SELECT * FROM mitarbeiter WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
        
        if (isset($_GET['loeschen']) && $_GET['loeschen']==1)
        {
            sql_set('DELETE FROM mitarbeiter WHERE mitarbeiter_id="'.$_GET['mitarbeiter_id'].'";');
            
            echo 'Mitarbeiter gel&ouml;scht!';
            echo '<br/>';
            echo '<br/>';
            echo '<br/>';
            echo '<form action="mitarbeiter_liste.php" method="post">';
            echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Zur&uuml;ck" />';
            echo '</form>';
        
        }
        else
        {
            echo 'Sind Sie sicher, dass Sie folgenden Mitarbeiter dauerhaft loeschen moechten?';
            var_dump ($mitarbeiter[0]);
        
            echo '<form action="loeschen.php" method="get">'."\n";
            echo '<input type="hidden" name="mitarbeiter_id" value="'.$_GET['mitarbeiter_id'].'"/>'."\n";
            echo '<input type="hidden" name="loeschen" value="1"/>'."\n";
            echo '<input type="submit" name="none" value="L&Ouml;SCHEN" />'."\n";
            echo '</form>';
        }
    }
}
?>