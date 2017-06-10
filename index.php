
<?php
// Load Config
$ini = parse_ini_file('assets/config/config.ini');
// define config value's
$app = $ini[app_name];
?>
<head>
    <title><?php echo $app?></title>
    <link href="assets/css/app.css" type="text/css" rel="stylesheet">
</head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script type="text/javascript">
    var toggle = true;
    setInterval(function() {
        var d = new Date().toLocaleTimeString('en-US', { hour12: false, hour: 'numeric', minute: 'numeric' });
        var parts = d.split(":");
        $('#hours').text(parts[0]);
        $('#minutes').text(parts[1]);
        $("#colon").css({ visibility: toggle?"visible":"hidden"});
        toggle=!toggle;
    },1000);
</script>
<div class="tijden">
    <table>
        <thead>
        <tr id="header">
            <td class="tijd">Tijd</td>
            <td class="vervoer">Vervoerder</td>
            <td class="type">Type</td>
            <td class="spoor">Spoor/lijn</td>
            <td class="to_via">Eindbestemming</td>
            <td>Opmerkingen</td>
            <td class="clock"><span id="hours"></span><span id="colon">:</span><span id="minutes"></span></td>
        </tr>
        </thead>
        <tbody id="departure">

        </tbody>
    </table>
</div>
<script src="assets/js/app.js"></script>
