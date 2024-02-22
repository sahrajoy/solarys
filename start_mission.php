<?php 

//VIEW START LOC
$header = [
    'Authorization: Bearer ' . $token,
];
// Set cURL options for a GET request
curl_setopt($curl, CURLOPT_URL, "https://api.spacetraders.io/v2/systems/:systemSymbol/waypoints/:waypointSymbol");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

// Execute curl session
$response = curl_exec($curl);

// Close curl session
curl_close($curl);

// Display the response
echo $response;