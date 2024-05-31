<?php

echo 'test';
include ('vars.php');

if (isset($_GET['jahr']))
{
    $altesjahr=intval($_GET['jahr']);
    $neuesjahr=1+$altesjahr;
    $mitarbeiter=sql_get('SELECT * FROM mitarbeiter');
    //var_dump($mitarbeiter);
    foreach ($mitarbeiter as $eintrag)
    {
        $id=$eintrag['mitarbeiter_id'];
        
        $stundensaldo=sql_get('SELECT * FROM stundensaldo WHERE jahr='.$altesjahr.' AND mitarbeiter_id='.$id.' ORDER BY monat DESC;');
        if (count($stundensaldo))
        {
            $stundensaldo=$stundensaldo[0]['stundensaldo'];
            $sql='INSERT INTO stundensaldo (mitarbeiter_id, stundensaldo, monat, jahr) ';
            $sql.='VALUES ('.$id.', '.$stundensaldo.', 0, '.$neuesjahr.') ';
            $sql.='ON DUPLICATE KEY UPDATE ';  
            $sql.='stundensaldo='.$stundensaldo.';';
            sql_set($sql);
        }
        else
        {
            $stundensaldo=0;
        }
        $feriensaldo=sql_get('SELECT * FROM feriensaldo WHERE jahr='.$altesjahr.' AND mitarbeiter_id='.$id.';');
        if (count($feriensaldo))
        {
            $feriensaldo=$feriensaldo[0]['ferientage'];
        }
        else
        {
            $feriensaldo=0;
        }
        $ferientotal=sql_get('SELECT * FROM ferien WHERE mitarbeiter_id='.$id.' AND jahr='.$altesjahr.';');
        
        $ferientotal=subarray_sum($ferientotal, 'ferientage');
        $ferienimjahr=sql_get('SELECT * FROM mitarbeiter WHERE mitarbeiter_id='.$id.';');
        $ferienimjahr=intval($ferienimjahr[0]['ferienimjahr']);
        $feriensaldo=$feriensaldo-$ferientotal+$ferienimjahr;
        
        $sql='INSERT INTO feriensaldo (mitarbeiter_id, ferientage, jahr) ';
        $sql.='VALUES ('.$id.', '.$feriensaldo.', '.$neuesjahr.') ';
        $sql.='ON DUPLICATE KEY UPDATE ';  
        $sql.='ferientage='.$feriensaldo.';';
        sql_set($sql);
        o($eintrag['nachname'].' '.$eintrag['vorname'].' -- Stunden: '.$stundensaldo.' | Ferien: '.$feriensaldo);
    }
}
?>