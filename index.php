
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
<script src="assets/js/app.js"></script>
<div class="tijden_div">
    <table class="tijden">
        <thead>
        <tr id="header" class="even">
            <td class="tijd">Tijd</td>
            <td class="to_via">Naar / Opmerkingen</td>
            <td class="spoorh">Spoor</td>
            <td class="vervoer">Vervoerder</td>
            <td><span class="clock"><i class="fa fa-clock-o" aria-hidden="true"></i> <span id="hours">00</span><span id="colon">:</span><span id="minutes">00</span></span></td>
        </tr>
        </thead>
        <tbody id="departure">

        </tbody>
    </table>
</div>
<div>
    <img src="http://lorempixel.com/1080/960/food/">
</div>

