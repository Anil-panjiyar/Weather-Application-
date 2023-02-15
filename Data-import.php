<?php
require_once('my-api.php');

// Select weather data for given parameters
$sql = "SELECT *
FROM weather WHERE city = '{$_GET['city']}'
AND weather_when >= DATE_SUB(NOW(), INTERVAL 10 SECOND)
ORDER BY weather_when DESC limit 1";
$result = $mysqli -> query($sql);

// If 0 record found
if ($result->num_rows == 0) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . $_GET['city'] . "&appid=922d0531c05a033a1ca30e4e348c149d";

// Get data from openweathermap and store in JSON object 
$data = file_get_contents($url);
$json = json_decode($data, true);

// Fetch required fields
$weather_description = $json['weather'][0]['description'];
$weather_temperature = $json['main']['temp'];
$weather_wind = $json['wind']['speed'];
$weather_when = date("Y-m-d H:i:s"); // now
$city = $json['name'];
$hum = $json["main"]["humidity"];
$max = $json['main']['temp_max'];
$min = $json['main']['temp_min'];
$icon = $json['weather'][0]['icon'];

 

// Build INSERT SQL statement

$sql = "INSERT INTO weather (weather_description, weather_temperature, weather_wind, weather_when, city, Minimum_Temperature, Maximum_temperature, Humidity,icon)
VALUES('{$weather_description}', {$weather_temperature}, {$weather_wind}, '{$weather_when}', '{$city}','{$min}','{$max}','{$hum}','{$icon}')";

if (!$mysqli -> query($sql)) {
echo("<h4>SQL error description: " . $mysqli -> error . "</h4>");

}
}

// Execute SQL query
$sql = "SELECT *FROM weather
ORDER BY weather_when DESC limit 1";
$result = $mysqli -> query($sql); 
// Get data, convert to JSON and print

$row = $result -> fetch_assoc();
print json_encode($row);
$sql = "SELECT *FROM weather
WHERE city = '{$_GET['city']}'
AND weather_when >= DATE_SUB(NOW(), INTERVAL 10 SECOND)
ORDER BY weather_when DESC limit 1";
$result = $mysqli -> query($sql);

// Free result set and close connection
$result -> free_result();
$mysqli -> close();


?>