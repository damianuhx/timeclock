<link rel="stylesheet" type="text/css" media="print, screen" href="edit.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
    $(document).ready(function() {
        $("#datepicker").datepicker();
        $("#datepicker").change(function() {
            $("#textboxB, #textboxC").val($(this).val());
        });

    });
</script>


<input type="text" id="datepicker" placeholder="click me"/>
<input type="text" id="textboxB" />
<input type="text" id="textboxC" />