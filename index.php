
<?php
// Load Config
$ini = parse_ini_file('assets/config/config.ini');
// define config value's
$app = $ini[app_name];
$town = $ini[town];
?>
<head>
    <title><?php echo $app?></title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
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
    <table class="tijden">
        <thead>
        <tr id="header" class="even">
            <td class="tijd">Tijd</td>
            <td class="to_via">Eindbestemming</td>
            <td class="spoorh">Spoor</td>
            <td class="vervoer">Vervoerder</td>
            <td>Opmerkingen</td>
            <td class="clock"><i class="fa fa-clock-o" aria-hidden="true"></i> <span id="hours">00</span><span id="colon">:</span><span id="minutes">00</span> <?php echo ucfirst($town);?></td>
        </tr>
        </thead>
        <tbody id="departure">

        </tbody>
    </table>
</div>
<div>
</div>
<script src="assets/js/app.js"></script>
