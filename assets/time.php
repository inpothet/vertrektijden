<?php
header('Content-Type: application/json');
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
//$train = file_get_contents("json/train.json", false,$context);
// Decode requested json
$train_data = json_decode($train, true);
$bus_data = json_decode($bus, true);
$bus_array = array();
foreach ($bus_data['BTMF'] as $key => $bus_value) {
    $bus_array[] = $bus_value['Departures'][0];
}
$train_data = array_slice($train_data,0,3);
function dus_compare($item1, $item2)
{
    $ts1 = strtotime($item1['PlannedDeparture']);
    $ts2 = strtotime($item2['PlannedDeparture']);
    return $ts1 - $ts2;
};
usort($bus_array, 'dus_compare');
$bus_array = array_slice($bus_array,0,3);
foreach ($bus_array as $key => $bus_value) {
    //print_r($bus_value);
    if(is_null($bus_value)) {
        
    }else{
        $bus_number = array('@text' => $bus_value['LineNumber'], 'wijziging' => "false");
        $bus_dest = explode(" via ", $bus_value['Destination']);
        $bus_via = $bus_dest[1];
        if (empty($bus_via)) {
            $bus_text = $bus_value['LineName'];
        } else {
            $bus_text = substr($bus_value['LineName'], 8, 8) . ", " . $bus_dest[1];
        }
        $bus_text = str_replace(' - ', ', ', $bus_text);
        $start_date = new DateTime($bus_value['ExpectedDeparture']);
        $since_start = $start_date->diff(new DateTime($bus_value['PlannedDeparture']));
        $delay = $since_start->i;
        if ($delay > 0) {
            $train_data[] = array('RitNummer' => $bus_value['DestinationCode'],
                'VertrekTijd' => $bus_value['PlannedDeparture'] . "+0200",
                'VertrekVertraging' => "PT" . $since_start->i . "M",
                'VertrekVertragingTekst' => "+" . $since_start->i . " mins",
                'EindBestemming' => $bus_dest[0],
                'TreinSoort' => $bus_value['TransportType'],
                'RouteTekst' => $bus_text,
                'Vervoerder' => $bus_value['AgencyCode'],
                'VertrekSpoor' => $bus_number);
        } else {
            $train_data[] = array('RitNummer' => $bus_value['DestinationCode'],
                'VertrekTijd' => $bus_value['PlannedDeparture'] . "+0200",
                'EindBestemming' => $bus_dest[0],
                'TreinSoort' => $bus_value['TransportType'],
                'RouteTekst' => $bus_text,
                'Vervoerder' => $bus_value['AgencyCode'],
                'VertrekSpoor' => $bus_number);
        }
    }
    }
function do_compare($item1, $item2)
{
    $ts1 = strtotime($item1['VertrekTijd']);
    $ts2 = strtotime($item2['VertrekTijd']);
    return $ts1 - $ts2;
}
usort($train_data, 'do_compare');
$json = json_encode($train_data);
echo $json;
//print_r($bus_array);