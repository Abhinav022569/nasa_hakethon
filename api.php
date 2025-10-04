<?php
// --- BACKEND API ENDPOINT (api.php) - Final Perfected Version ---

header('Content-Type: application/json');

// --- CONFIGURATION ---
// This is the API key you have been using.
$apiKey = "ddc33827ca40c6fedfbe8fe5fe867d76"; 

// --- INPUT HANDLING & VALIDATION ---
$lat = isset($_GET['lat']) ? $_GET['lat'] : null;
$lon = isset($_GET['lon']) ? $_GET['lon'] : null;
$city = isset($_GET['city']) ? strtolower(trim($_GET['city'])) : '';

if (!$lat && !$lon && empty($city)) {
    http_response_code(400);
    echo json_encode(['error' => 'City or lat/lon parameters are required']);
    exit;
}

// --- API CALLS ---

// Step 1: Get coordinates if they aren't provided
if (!$lat || !$lon) {
    $geoUrl = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($city) . "&limit=1&appid={$apiKey}";
    $geoData = json_decode(@file_get_contents($geoUrl), true);
    if (empty($geoData)) {
        http_response_code(404);
        echo json_encode(['error' => 'City not found']);
        exit;
    }
    $lat = $geoData[0]['lat'];
    $lon = $geoData[0]['lon'];
}

// Step 2: Fetch Air Pollution and Weather data using coordinates
$airData = json_decode(@file_get_contents("http://api.openweathermap.org/data/2.5/air_pollution?lat={$lat}&lon={$lon}&appid={$apiKey}"), true);
$weatherData = json_decode(@file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=imperial&appid={$apiKey}"), true);

// Validate that we received data from the APIs
if (empty($airData) || empty($weatherData)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch external API data for the specified location.']);
    exit;
}

// ####################################################################
// ### SIMULATED DATA FUSION MODEL (Ground + TEMPO Satellite)       ###
// ####################################################################

// 1. Get Ground Data from OpenWeatherMap
$ground_pm25 = $airData['list'][0]['components']['pm2_5'];
$ground_o3 = $airData['list'][0]['components']['o3'];
$ground_no2 = $airData['list'][0]['components']['no2'];

// 2. Simulate TEMPO Satellite Data by applying a small variation
function simulate_satellite_reading($ground_value) {
    $variation = 1 + (rand(-15, 15) / 100); // Apply a +/- 15% random variation
    return $ground_value * $variation;
}
$tempo_pm25 = simulate_satellite_reading($ground_pm25);
$tempo_o3 = simulate_satellite_reading($ground_o3);
$tempo_no2 = simulate_satellite_reading($ground_no2);

// 3. Fuse the Data using a Weighted Average (70% Ground, 30% Satellite)
$fused_pm25 = ($ground_pm25 * 0.7) + ($tempo_pm25 * 0.3);
$fused_o3 = ($ground_o3 * 0.7) + ($tempo_o3 * 0.3);
$fused_no2 = ($ground_no2 * 0.7) + ($tempo_no2 * 0.3);

// ####################################################################
// ### STANDARD AQI CALCULATION (Using the FUSED data)              ###
// ####################################################################

function calculateAqi($concentration, $pollutant) {
    // Breakpoints for AQI calculation based on US EPA standards
    $breakpoints = [
        'pm25' => [[0.0, 12.0, 0, 50], [12.1, 35.4, 51, 100], [35.5, 55.4, 101, 150], [55.5, 150.4, 151, 200], [150.5, 250.4, 201, 300], [250.5, 500.4, 301, 500]],
        'o3'   => [[0, 106, 0, 50], [107, 137, 51, 100], [138, 167, 101, 150], [168, 204, 151, 200], [205, 785, 201, 300]]
    ];
    if (!isset($breakpoints[$pollutant])) return 0;
    foreach ($breakpoints[$pollutant] as $bp) {
        if ($concentration >= $bp[0] && $concentration <= $bp[1]) {
            // Linear interpolation formula
            return round((($bp[3] - $bp[2]) / ($bp[1] - $bp[0])) * ($concentration - $bp[0]) + $bp[2]);
        }
    }
    return 0; // Return 0 if outside defined ranges
}

// Calculate individual AQI for each major pollutant using the fused concentrations
$pm25_aqi = calculateAqi($fused_pm25, 'pm25');
$o3_aqi = calculateAqi($fused_o3, 'o3');
// The final AQI is the highest of the individual pollutant scores
$overallAqi = max($pm25_aqi, $o3_aqi);


// ####################################################################
// ### FINAL DATA TRANSFORMATION & RESPONSE ASSEMBLY                ###
// ####################################################################
function getWeatherIcon($iconCode) {
    $iconMap = ["01d"=>"☀️","01n"=>"🌙","02d"=>"⛅","02n"=>"☁️","03d"=>"☁️","03n"=>"☁️","04d"=>"☁️","04n"=>"☁️","09d"=>"🌧️","09n"=>"🌧️","10d"=>"🌦️","10n"=>"🌧️","11d"=>"⛈️","11n"=>"⛈️","13d"=>"❄️","13n"=>"❄️","50d"=>"💨","50n"=>"💨"];
    return $iconMap[$iconCode] ?? "❓";
}

// Assemble the final response object
$response = [
    "lat" => (float)$lat,
    "lon" => (float)$lon,
    "aqi" => $overallAqi,
    "pollutants" => [
        "o3"   => $fused_o3,
        "no2"  => $fused_no2,
        "so2"  => $airData['list'][0]['components']['so2'],
        "pm25" => $fused_pm25,
        "hcho" => 0 // Placeholder as this data is not available
    ],
    "weather" => [
        "temp"     => $weatherData['main']['temp'],
        "humidity" => $weatherData['main']['humidity'],
        "wind"     => $weatherData['wind']['speed'] . " mph",
        "icon"     => getWeatherIcon($weatherData['weather'][0]['icon'])
    ],
    "forecast" => [
        ["day" => "Today", "aqi" => $overallAqi],
        ["day" => "Tomorrow", "aqi" => max(0, $overallAqi + rand(-10, 15))], // Ensure forecast isn't negative
        ["day" => date("l", strtotime("+2 day")), "aqi" => max(0, $overallAqi + rand(-15, 20))],
    ]
];

// --- SEND RESPONSE ---
echo json_encode($response);

?>