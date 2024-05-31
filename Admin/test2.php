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


<form action="/stempeluhr/admin/zeitstempel.php?table=zeitstempel&condition=&period=2/2016&mitarbeiter_id=1008&purpose=Bearbeiten#" method="post">
    <input type="hidden" value="zeitstempel" name="table"/>

    <input type="hidden" value="1008" name="mitarbeiter_id"/>
    <input style="display: inline-block; width: 140px;" type="text" name="datepicker" id="datepicker">
    <input style="display: inline-block; width: 140px; " type="text" name="angemeldet" id="angemeldet" >
    <input style="display: inline-block; width: 140px; " type="text" name="abgemeldet" id="abgemeldet" >
    <input type="submit" name="button" value="Einfuegen" />

</form>

