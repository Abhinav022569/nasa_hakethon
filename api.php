<?php
// --- BACKEND API ENDPOINT (api.php) ---

// Set the content type header to signal that the response is JSON
header('Content-Type: application/json');

// In a real-world application, you would connect to databases and external APIs here.
// For this demonstration, we'll use a static array as our data source.
$dataCache = [
    "new york" => [
        "lat" => 40.71, "lon" => -74.00, "aqi" => 45,
        "pollutants" => ["o3" => 30, "no2" => 15, "so2" => 5, "pm25" => 10, "hcho" => 4],
        "weather" => ["temp" => 72, "humidity" => 60, "wind" => "8 mph W", "icon" => "☀️"]
    ],
    "los angeles" => [
        "lat" => 34.05, "lon" => -118.24, "aqi" => 88,
        "pollutants" => ["o3" => 50, "no2" => 38, "so2" => 8, "pm25" => 30, "hcho" => 9],
        "weather" => ["temp" => 85, "humidity" => 55, "wind" => "6 mph NW", "icon" => "☀️"]
    ],
    "chicago" => [
        "lat" => 41.87, "lon" => -87.62, "aqi" => 65,
        "pollutants" => ["o3" => 40, "no2" => 25, "so2" => 6, "pm25" => 18, "hcho" => 6],
        "weather" => ["temp" => 68, "humidity" => 70, "wind" => "12 mph N", "icon" => "☁️"]
    ],
    "houston" => [
        "lat" => 29.76, "lon" => -95.36, "aqi" => 110,
        "pollutants" => ["o3" => 70, "no2" => 40, "so2" => 12, "pm25" => 35, "hcho" => 12],
        "weather" => ["temp" => 88, "humidity" => 75, "wind" => "10 mph S", "icon" => "🌦️"]
    ],
    "phoenix" => [
        "lat" => 33.44, "lon" => -112.07, "aqi" => 95,
        "pollutants" => ["o3" => 60, "no2" => 35, "so2" => 7, "pm25" => 28, "hcho" => 8],
        "weather" => ["temp" => 102, "humidity" => 20, "wind" => "5 mph E", "icon" => "☀️"]
    ],
    "toronto" => [
        "lat" => 43.65, "lon" => -79.38, "aqi" => 55,
        "pollutants" => ["o3" => 35, "no2" => 20, "so2" => 4, "pm25" => 15, "hcho" => 5],
        "weather" => ["temp" => 65, "humidity" => 65, "wind" => "9 mph SW", "icon" => "☁️"]
    ],
    "mexico city" => [
        "lat" => 19.43, "lon" => -99.13, "aqi" => 155,
        "pollutants" => ["o3" => 90, "no2" => 65, "so2" => 15, "pm25" => 60, "hcho" => 15],
        "weather" => ["temp" => 75, "humidity" => 80, "wind" => "4 mph E", "icon" => "⛈️"]
    ],
    "denver" => [
        "lat" => 39.73, "lon" => -104.99, "aqi" => 72,
        "pollutants" => ["o3" => 45, "no2" => 27, "so2" => 5, "pm25" => 22, "hcho" => 7],
        "weather" => ["temp" => 78, "humidity" => 40, "wind" => "15 mph W", "icon" => "💨"]
    ],
    "seattle" => [
        "lat" => 47.60, "lon" => -122.33, "aqi" => 30,
        "pollutants" => ["o3" => 20, "no2" => 10, "so2" => 2, "pm25" => 8, "hcho" => 3],
        "weather" => ["temp" => 66, "humidity" => 72, "wind" => "7 mph NW", "icon" => "🌧️"]
    ],
];

// Get the requested city from the GET request query parameters
$city = isset($_GET['city']) ? strtolower(trim($_GET['city'])) : '';

$response = [];

// Check if the requested city exists in our data cache
if (!empty($city) && array_key_exists($city, $dataCache)) {
    $response = $dataCache[$city];
} else {
    // If the city is not found, return a 404 error
    http_response_code(404);
    $response = ['error' => 'City not found'];
}

// Encode the PHP array into a JSON string and echo it as the response
echo json_encode($response);
