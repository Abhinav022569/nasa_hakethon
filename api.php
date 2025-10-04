<?php
// --- BACKEND API ENDPOINT (api.php) ---

header('Content-Type: application/json');

// --- CONFIGURATION ---
$apiKey = "ddc33827ca40c6fedfbe8fe5fe867d76";
$city = isset($_GET['city']) ? strtolower(trim($_GET['city'])) : '';

// --- VALIDATION ---
if (empty($city)) {
    http_response_code(400);
    echo json_encode(['error' => 'City parameter is required']);
    exit;
}

// --- API CALLS ---

// 1. Get Geocoding Data (Latitude and Longitude)
$geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q={$city}&limit=1&appid={$apiKey}";
$geoResponse = @file_get_contents($geoUrl);
if ($geoResponse === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch geolocation data']);
    exit;
}
$geoData = json_decode($geoResponse, true);

if (empty($geoData)) {
    http_response_code(404);
    echo json_encode(['error' => 'City not found']);
    exit;
}

$lat = $geoData[0]['lat'];
$lon = $geoData[0]['lon'];

// 2. Get Air Pollution Data
$airUrl = "http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$apiKey}";
$airResponse = @file_get_contents($airUrl);
if ($airResponse === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch air pollution data']);
    exit;
}
$airData = json_decode($airResponse, true);

// 3. Get Weather Data
$weatherUrl = "http://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=imperial&appid={$apiKey}";
$weatherResponse = @file_get_contents($weatherUrl);
if ($weatherResponse === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch weather data']);
    exit;
}
$weatherData = json_decode($weatherResponse, true);

// --- DATA TRANSFORMATION ---

// Function to get weather icon
function getWeatherIcon($iconCode) {
    $iconMap = [
        "01d" => "☀️", "01n" => "🌙",
        "02d" => "⛅", "02n" => "☁️",
        "03d" => "☁️", "03n" => "☁️",
        "04d" => "☁️", "04n" => "☁️",
        "09d" => "🌧️", "09n" => "🌧️",
        "10d" => "🌦️", "10n" => "🌧️",
        "11d" => "⛈️", "11n" => "⛈️",
        "13d" => "❄️", "13n" => "❄️",
        "50d" => "💨", "50n" => "💨",
    ];
    return isset($iconMap[$iconCode]) ? $iconMap[$iconCode] : "❓";
}

$response = [
    "lat" => $lat,
    "lon" => $lon,
    "aqi" => $airData['list'][0]['main']['aqi'],
    "pollutants" => [
        "o3" => $airData['list'][0]['components']['o3'],
        "no2" => $airData['list'][0]['components']['no2'],
        "so2" => $airData['list'][0]['components']['so2'],
        "pm25" => $airData['list'][0]['components']['pm2_5'],
        "hcho" => 0 // HCHO is not available in this API, so we'll use a placeholder
    ],
    "weather" => [
        "temp" => $weatherData['main']['temp'],
        "humidity" => $weatherData['main']['humidity'],
        "wind" => $weatherData['wind']['speed'] . " mph " . $weatherData['wind']['deg'] . "°",
        "icon" => getWeatherIcon($weatherData['weather'][0]['icon'])
    ],
    // The forecast data from OpenWeatherMap requires a paid subscription.
    // For this example, we will generate some dummy forecast data.
    "forecast" => [
        ["day" => "Today", "aqi" => $airData['list'][0]['main']['aqi']],
        ["day" => "Tomorrow", "aqi" => $airData['list'][0]['main']['aqi'] + 5],
        ["day" => "Mon", "aqi" => $airData['list'][0]['main']['aqi'] - 2],
        ["day" => "Tue", "aqi" => $airData['list'][0]['main']['aqi'] + 8],
        ["day" => "Wed", "aqi" => $airData['list'][0]['main']['aqi'] - 5]
    ]
];


// --- SEND RESPONSE ---
echo json_encode($response);

?>