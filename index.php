<?php

/* Seite zum Filialen ausw�hlen.
*/

include ('vars.php');

$db = mysqli_connect(DB_HOST, DB_BENUTZER, DB_PASSWORT, DB_NAME);
$sql = 'SELECT * FROM filialen ORDER BY filialen_name ASC';
$daten = mysqli_query($db, $sql);

    
?>
<!DOCTYPE html>
    
    <head>
        <title>Stempeluhr</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
        <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    </head>
    <body>
    <div id="container">
    <header>
        <h1>Stempeluhr</h1>
    </header>
    <nav>
        
    </nav>
    <aside>
        
    </aside>
    <section id="content">
    
    <article>
    <form action="anmelden.php" method="get">
    <label for="select">
        <h3>Bitte w&auml;hlen Sie die Filiale:&nbsp;&nbsp;</h3>
    </label>
    <select name="filiale">
<?php

    while ($filialen = (mysqli_fetch_assoc($daten))) {
       $filialen_name = ($filialen['filialen_name']);
       echo '<option value="'.$filialen_name.'">'.$filialen_name.'</option>';
    }
?>
    </select>
    <input type="submit" value="GO">
    </form>
    </article>
    </section>
    </div>
    <footer>
    </footer>
    </div>
    </body>
</html>