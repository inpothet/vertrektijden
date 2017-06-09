<?php
// Load Config
$ini = parse_ini_file('config/config.ini');
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
//$bus = file_get_contents("json/bus.json", false,$context);
// Decode requested json
$train_data = json_decode($train, true);
$bus_data = json_decode($bus, true);
$bus_array = array();
foreach ($bus_data['BTMF'] as $key => $bus_value) {
    $bus_array[] = $bus_value['Departures'][0];
}
$bus_array1 = array();
foreach ($bus_array as $key => $bus_value) {
    $bus_number = array('@text' => $bus_value['LineNumber']);
    $bus_dest =  explode(" via ",$bus_value['Destination']);
    $train_data[] = array('RitNummer' => $bus_value['DestinationCode'],
        'VertrekTijd' => $bus_value['PlannedDeparture']."+0200",
        'EindBestemming' => $bus_dest[0],
        'TreinSoort' => $bus_value['TransportType'],
        'RouteTekst' => $bus_value['LineName'] ." - ". $bus_dest[1],
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
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
foreach ($train_data as $key => $bus_value) {
    echo $bus_value['Vervoerder'] . '&nbsp';
    echo $bus_value['TreinSoort'] . '&nbsp';
    echo $bus_value['VertrekSpoor']['@text'] . "&nbsp";
    echo $bus_value['EindBestemming'] ." ". $bus_value['RouteTekst'] . "&nbsp";
    echo $bus_value['VertrekTijd'] . "&nbsp";
    echo "<br>";
}
//foreach ($bus_array as $key => $bus_value) {
//    echo $bus_value['AgencyCode'] . '&nbsp';
//    echo $bus_value['TransportType'] . '&nbsp';
//    echo $bus_value['LineNumber'] . "&nbsp";
//    echo $bus_value['Destination'] . "&nbsp";
//    echo $bus_value['PlannedDeparture'] . "&nbsp";
//    echo "<br>";
//}