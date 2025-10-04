<?php
// --- BACKEND API ENDPOINT (api.php) - Corrected Version ---

header('Content-Type: application/json');

// --- CONFIGURATION ---
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

if (empty($airData) || empty($weatherData)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch external API data.']);
    exit;
}

// ### SIMULATED DATA FUSION MODEL (Ground + TEMPO Satellite) ###
$ground_pm25 = $airData['list'][0]['components']['pm2_5'];
$ground_o3 = $airData['list'][0]['components']['o3'];
$ground_no2 = $airData['list'][0]['components']['no2'];

function simulate_satellite_reading($ground_value) {
    // A more stable simulation
    return $ground_value * (1 + (rand(-5, 5) / 100));
}
$fused_pm25 = ($ground_pm25 * 0.7) + (simulate_satellite_reading($ground_pm25) * 0.3);
$fused_o3 = ($ground_o3 * 0.7) + (simulate_satellite_reading($ground_o3) * 0.3);
$fused_no2 = ($ground_no2 * 0.7) + (simulate_satellite_reading($ground_no2) * 0.3);

// ### STANDARD AQI CALCULATION (Using the FUSED data) ###
function calculateAqi($concentration, $pollutant) {
    $breakpoints = [
        'pm25' => [[0.0, 12.0, 0, 50], [12.1, 35.4, 51, 100], [35.5, 55.4, 101, 150], [55.5, 150.4, 151, 200], [150.5, 250.4, 201, 300], [250.5, 500.4, 301, 500]],
        'o3'   => [[0, 106, 0, 50], [107, 137, 51, 100], [138, 167, 101, 150], [168, 204, 151, 200], [205, 785, 201, 300]]
    ];
    if (!isset($breakpoints[$pollutant])) return 0;
    foreach ($breakpoints[$pollutant] as $bp) {
        if ($concentration >= $bp[0] && $concentration <= $bp[1]) {
            return round((($bp[3] - $bp[2]) / ($bp[1] - $bp[0])) * ($concentration - $bp[0]) + $bp[2]);
        }
    }
    return 500; // Return max value if it exceeds breakpoints
}

$pm25_aqi = calculateAqi($fused_pm25, 'pm25');
$o3_aqi = calculateAqi($fused_o3, 'o3');
$overallAqi = max($pm25_aqi, $o3_aqi);


// ### FINAL DATA TRANSFORMATION & RESPONSE ASSEMBLY ###
function getWeatherIcon($iconCode) {
    $iconMap = ["01d"=>"☀️","01n"=>"🌙","02d"=>"⛅","02n"=>"☁️","03d"=>"☁️","03n"=>"☁️","04d"=>"☁️","04n"=>"☁️","09d"=>"🌧️","09n"=>"🌧️","10d"=>"🌦️","10n"=>"🌧️","11d"=>"⛈️","11n"=>"⛈️","13d"=>"❄️","13n"=>"❄️","50d"=>"💨","50n"=>"💨"];
    return $iconMap[$iconCode] ?? "❓";
}

// --- REVISED FORECAST LOGIC ---

// Generate 7-day forecast with a smoother, more realistic trend
$dailyForecast = [];
for ($i = 0; $i < 7; $i++) {
    // Use a sine wave for gentle, predictable daily fluctuation
    $fluctuation = round(10 * sin($i * 0.9));
    $dailyForecast[] = [
        "day" => date("l", strtotime("+$i day")),
        "aqi" => max(0, $overallAqi + $fluctuation)
    ];
}

// Generate 48-hour forecast simulating a diurnal (daily) pattern
$hourlyForecast = [];
$currentHour = (int)date('G');
for ($i = 0; $i < 48; $i++) {
    $hourOfDay = ($currentHour + $i) % 24;
    $fluctuation = 0;
    // Simulate higher AQI during morning (6-9) and evening (17-20) rush hours
    if ($hourOfDay >= 6 && $hourOfDay <= 9) {
        $fluctuation = rand(5, 12);
    } elseif ($hourOfDay >= 17 && $hourOfDay <= 20) {
        $fluctuation = rand(3, 10);
    } else { // Lower AQI overnight and mid-day
        $fluctuation = rand(-8, 2);
    }
    $hourlyForecast[] = [
        "hour" => $hourOfDay,
        "aqi" => max(0, $overallAqi + $fluctuation)
    ];
}

$response = [
    "lat" => (float)$lat,
    "lon" => (float)$lon,
    "aqi" => $overallAqi,
    "pollutants" => [ "o3" => $fused_o3, "no2" => $fused_no2, "so2" => $airData['list'][0]['components']['so2'], "pm25" => $fused_pm25 ],
    "weather" => [ "temp" => $weatherData['main']['temp'], "humidity" => $weatherData['main']['humidity'], "wind" => $weatherData['wind']['speed'] . " mph", "icon" => getWeatherIcon($weatherData['weather'][0]['icon']) ],
    "forecast" => $dailyForecast,
    "hourly_forecast" => $hourlyForecast
];

echo json_encode($response);

?>