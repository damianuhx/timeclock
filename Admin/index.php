<?php
include "vars.php";

if (isset($_POST['passwort']) && $_POST['passwort']==DB_PASSWORT)
{
    if (setcookie('passwort', $_POST['passwort']))
    {
        echo '<link rel="stylesheet" type="text/css" media="screen" href="main.css">';
        echo 'Passworteingabe erfolgreich. Druecken Sie "Neu Laden" um den administrativen Teil der Stempeluhr benutzen zu koennen.';
        echo '<br/><br/>';
        echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
        echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Neu Laden" />';
        echo '</form>';
    }
}
else if (isset($_COOKIE['passwort']) && $_COOKIE['passwort']==DB_PASSWORT)
{
        echo '<h1>Bearbeiten:</h1>';
        
        echo '<form action="mitarbeiter_liste.php" method="post">';
        echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Mitarbeiter" />';
        echo '</form>';
        
        echo '<form action="filialen_liste.php" method="post">';
        echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Filialen" />';
        echo '</form>';

    $thisyear=date("Y");
    $lastyear=$thisyear-1;

        echo '<form action="feiertage.php" method="get">';
        echo 'Feiertage: ';
        echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$thisyear.'" />';
        echo '<input type="submit" style="font-size:18pt;" name="anzeigen" value="'.$lastyear.'" />';

        echo '</form>';
        
        echo '<hr>';
                
        echo '<form action="backup.php" method="post">';
        echo '<input type="submit" style="font-size:18pt;" name="setzen" value="Backup" />';
        echo '</form>';
    echo '<a href="saldouebertrag.php">Stundensaldo von '.$lastyear.' auf '.$thisyear.' uebertragen <a/>';
}
else
{
    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">';
    echo '<input type="password" name="passwort" />';
    echo '<br/><br/>';
    echo '<input type="submit" style="font-size:18pt;" name="setzen" value="OK" />';
    echo '</form>';


    
}
