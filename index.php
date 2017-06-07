<?php
// Load Config
$ini = parse_ini_file('config/config.ini');
// define config value's
$station = $ini[station];
$town = $ini[town];
$stop = $ini[stop];
$key = $ini[api_key];
$version = $ini[version];

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
//$bus = file_get_contents("https://api.vertrektijd.info/departures/_nametown/$town/$stop", false,$context);
$bus = file_get_contents("test.json", false,$context);
// Decode requested json
$train_data = json_decode($train, true);
$bus_data = json_decode($bus, true);
?>
<table>
    <caption>Vertrektijden eerstvolgende treinen</caption>
    <thead>
    <th>Vervoerder</th>
    <th>Type</th>
    <th>Spoor</th>
    <th>Eindbestemming</th>
    <th>vertrektijd</th>
    <th>Opmerkingen</th>
    </thead>
    <tbody id="vertrek_trein">
    <?php
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
        $delay = $train_value['VertrekVertragingTekst'];
        $actual = $time . " " . $delay;
        $info = "Vertraagd";
    }else{
        $actual = substr($train_value['VertrekTijd'], 11, 5);
    }
    //Train Type
    if ($train_value['TreinSoort']== "Intercity"){
        $train_type = "IC";
    }else{
        $actual = substr($train_value['VertrekTijd'], 11, 5);
    }

    echo '<td><img src="img/'. $train_value['Vervoerder'] .'.png" width="32px"></td>';
    echo "<td>" . $train_value['TreinSoort'] . '</td>';
    echo "<td class='spoor'>" . $train_value['VertrekSpoor']['@text'] . "</td>";
    echo "<td>" . $train_value['EindBestemming'] . "<br><small>". $train_value['RouteTekst'] ."</small></td>";
    echo "<td>" . $actual . "</td>";
    echo "<td>" . $info . "</td>";
    echo "</tr>";
}
?>

    <?php
    foreach ($bus_data['BTMF'] as $key => $bus_value) {
        echo "<tr>";
        echo '<td><img src="img/' . $bus_value['Departures'][0]['AgencyCode'] . '.png" width="32px"></td>';
        echo "<td>" . $bus_value['Departures'][0]['TransportType'] . '</td>';
        echo "<td>" . $bus_value['Departures'][0]['LineNumber'] . "</td>";
        echo "<td>" . $bus_value['Departures'][0]['Destination'] . "</td>";
        echo "<td>" . substr($bus_value['Departures'][0]['PlannedDeparture'],11,5) . "</td>";
        echo "<td>" . "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>

<img src="img/"