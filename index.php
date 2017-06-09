
<?php
// Load Config
$ini = parse_ini_file('assets/config/config.ini');
// define config value's
$station = $ini[station];
$town = $ini[town];
$stop = $ini[stop];
$key = $ini[api_key];
$version = $ini[version];
$app = $ini[app_name];

$opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=>"Accept: application/json\r\n" .
            "Accept-Version: $version\r\n" .
            "X-Vertrektijd-Client-Api-Key: $key\r\n"
    )
);
$context = stream_context_create($opts);
// request Json for train and bus
$train = file_get_contents("https://api.vertrektijd.info/ns/_departures?station=$station", false,$context);
$bus = file_get_contents("https://api.vertrektijd.info/departures/_nametown/$town/$stop", false,$context);
//$train = file_get_contents("assets/json/train.json", false,$context);
//$bus = file_get_contents("assets/json/bus.json", false,$context);
// Decode requested json
$train_data = json_decode($train, true);
$bus_data = json_decode($bus, true);
?>
<head>
    <title><?php echo $app?></title>
    <link href="assets/css/app.css" type="text/css" rel="stylesheet">
</head>
<table>

    <caption>Vertrektijden eerstvolgende treinen</caption>
    <thead>
    <tr>
        <th>Vervoerder</th>
        <th>Type</th>
        <th>Spoor/lijn</th>
        <th>Eindbestemming</th>
        <th>vertrektijd</th>
        <th>Opmerkingen</th>
        <th><?php echo date(H)?><blink>:</blink><?php echo date(i)?></th>
    </tr>
    </thead>
    <tbody id="vertrek">
    <?php
    $train_data = array_slice($train_data, 0 , 4);
    $bus_array = array();
    foreach ($bus_data['BTMF'] as $key => $bus_value) {
        $bus_array[] = $bus_value['Departures'][0];
    }
    $bus_array1 = array();
    foreach ($bus_array as $key => $bus_value) {
        $bus_number = array('@text' => $bus_value['LineNumber']);
        $bus_dest =  explode(" via ",$bus_value['Destination']);
        $bus_via = $bus_dest[1];
        if (empty($bus_via)){
            $bus_text = $bus_value['LineName'];
        }
        else{
            $bus_text = $bus_value['LineName'] ." - ". $bus_dest[1];
        }

        $train_data[] = array('RitNummer' => $bus_value['DestinationCode'],
            'VertrekTijd' => $bus_value['PlannedDeparture']."+0200",
            'EindBestemming' => $bus_dest[0],
            'TreinSoort' => $bus_value['TransportType'],
            'RouteTekst' => $bus_text,
            'Vervoerder' => $bus_value['AgencyCode'],
            'VertrekSpoor' => $bus_number);
    }
    function do_compare($item1, $item2)
    {
        $ts1 = strtotime($item1['VertrekTijd']);
        $ts2 = strtotime($item2['VertrekTijd']);
        return $ts1 - $ts2;
    }
    usort($train_data, 'do_compare');
foreach ($train_data as $key => $train_value) {
    echo "<tr>";
    //Notification
        if (isset( $train_value['Opmerkingen']['Opmerking'])){
        $info = $train_value['Opmerkingen']['Opmerking'].$train_value['ReisTip'];;
    }elseif(isset( $train_value['ReisTip'])){
        $info = $train_value['ReisTip'];
    }else{
        $info = "";
        }
    // Time Of Departure
    if (isset( $train_value['VertrekVertraging'])){
        $time = substr($train_value['VertrekTijd'], 11, 5);
        $delay = substr($train_value['VertrekVertragingTekst'],0,2);
        $actual = $time . " " . $delay;
        $info = "Vertraagd";
    }else{
        $actual = substr($train_value['VertrekTijd'], 11, 5);
    }

    echo '<td><img class="logo" src="assets/img/'. $train_value['Vervoerder'] .'.png"></td>';
    echo "<td>" . $train_value['TreinSoort'] . '</td>';
    echo "<td class='spoor'>" . $train_value['VertrekSpoor']['@text'] . "</td>";
    echo "<td class='text-center'>" . $train_value['EindBestemming'] . "<br><small>". $train_value['RouteTekst'] ."</small></td>";
    echo "<td>" . $actual . "</td>";
    echo "<td>" . $info . "</td>";
    echo "</tr>";
}
    ?>
    </tbody>
</table><script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
