<?php
// Outputting page to JSON
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

// Headers for departure request
$opts = array(
    'http'=>array(
        'method'=>"GET",
        'header'=>"Accept: application/json\r\n" .
            "Accept-Version: $version\r\n" .
            "X-Vertrektijd-Client-Api-Key: $key\r\n"
    )
);

// Render headers for departure request
$context = stream_context_create($opts);

// Request departure times for train and bus (Real Time)
$train = file_get_contents("https://api.vertrektijd.info/ns/_departures?station=$station", false,$context);
$train_comments = file("https://api.vertrektijd.info/ns/_disruptions?station=$station&actual=false", false,$context);
$bus = file_get_contents("https://api.vertrektijd.info/departures/_nametown/$town/$stop", false,$context);

// Request departure times for train and bus (Testing)
//$bus = file_get_contents("json/bus.json", false,$context);
//$train = file_get_contents("json/train.json", false,$context);

// Decode requested json
$train_data = json_decode($train, true);
$train_comment = json_decode($train_comments[0], true);
$bus_data = json_decode($bus, true);

// Sorting bus data to array
$bus_array = array();
foreach ($bus_data['BTMF'] as $key => $bus_value) {
    $bus_array = array_merge($bus_array,$bus_value['Departures']);
}

// Final departure comparison
function departure_compare($item1, $item2)
{
    $ts1 = strtotime($item1['VertrekTijd']);
    $ts2 = strtotime($item2['VertrekTijd']);
    return $ts1 - $ts2;
}

// make the final array for the bus
foreach ($bus_array as $key => $bus_value) {
    if(is_null($bus_value)) {
        
    }else{
        // Create array for Line Number
        $bus_number = array('@text' => $bus_value['LineNumber'], 'wijziging' => "false");

        // Seperate bus name if it goes though another place
        $bus_dest = explode(" via ", $bus_value['Destination']);

        // Check if bus goes through another place and if so added it to route
        $bus_via = $bus_dest[1];
        if (empty($bus_via)) {
            $bus_text = $bus_value['LineName'];
        } else {
            $bus_text = substr($bus_value['LineName'], 8, 8) . ", " . $bus_dest[1];
        }

        // Clean up Route
        $bus_text = str_replace(' - ', ', ', $bus_text);

        // Calculate if there is a Delay
        $start_date = new DateTime($bus_value['ExpectedDeparture']);
        $since_start = $start_date->diff(new DateTime($bus_value['PlannedDeparture']));
        $delay = $since_start->i;

        // If there is a Delay create other array than if not
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

// Sort departure times on time
usort($train_data, 'departure_compare');
if(!empty($train_comment["Storingen"][Ongepland])){
    $train_data = array_slice($train_data, 0, 14);
    array_push($train_data, $train_comment[Storingen][Ongepland]);
}else{
    $train_data = array_slice($train_data, 0, 15);
}

// Encode array to JSON
$json_departure = json_encode($train_data, JSON_PRETTY_PRINT);
$json_comment = json_encode($train_comment["Storingen"]["Ongepland"]);
// Print JSON
echo $json_departure;

