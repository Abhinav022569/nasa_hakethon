<?php
// --- BACKEND API ENDPOINT (api.php) ---

header('Content-Type: application/json');

// This data structure now includes a 'forecast' array for each city,
// making it a predictive data source.
$dataCache = [
    "new york" => [
        "lat" => 40.71, "lon" => -74.00, "aqi" => 45,
        "pollutants" => ["o3" => 30, "no2" => 15, "so2" => 5, "pm25" => 10, "hcho" => 4],
        "weather" => ["temp" => 72, "humidity" => 60, "wind" => "8 mph W", "icon" => "☀️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 45],
            ["day" => "Tomorrow", "aqi" => 52],
            ["day" => "Mon", "aqi" => 58],
            ["day" => "Tue", "aqi" => 48],
            ["day" => "Wed", "aqi" => 65]
        ]
    ],
    "los angeles" => [
        "lat" => 34.05, "lon" => -118.24, "aqi" => 88,
        "pollutants" => ["o3" => 50, "no2" => 38, "so2" => 8, "pm25" => 30, "hcho" => 9],
        "weather" => ["temp" => 85, "humidity" => 55, "wind" => "6 mph NW", "icon" => "☀️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 88],
            ["day" => "Tomorrow", "aqi" => 95],
            ["day" => "Mon", "aqi" => 105],
            ["day" => "Tue", "aqi" => 92],
            ["day" => "Wed", "aqi" => 110]
        ]
    ],
    "chicago" => [
        "lat" => 41.87, "lon" => -87.62, "aqi" => 65,
        "pollutants" => ["o3" => 40, "no2" => 25, "so2" => 6, "pm25" => 18, "hcho" => 6],
        "weather" => ["temp" => 68, "humidity" => 70, "wind" => "12 mph N", "icon" => "☁️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 65],
            ["day" => "Tomorrow", "aqi" => 72],
            ["day" => "Mon", "aqi" => 68],
            ["day" => "Tue", "aqi" => 75],
            ["day" => "Wed", "aqi" => 60]
        ]
    ],
    "houston" => [
        "lat" => 29.76, "lon" => -95.36, "aqi" => 110,
        "pollutants" => ["o3" => 70, "no2" => 40, "so2" => 12, "pm25" => 35, "hcho" => 12],
        "weather" => ["temp" => 88, "humidity" => 75, "wind" => "10 mph S", "icon" => "🌦️"],
         "forecast" => [
            ["day" => "Today", "aqi" => 110],
            ["day" => "Tomorrow", "aqi" => 115],
            ["day" => "Mon", "aqi" => 120],
            ["day" => "Tue", "aqi" => 105],
            ["day" => "Wed", "aqi" => 98]
        ]
    ],
    "phoenix" => [
        "lat" => 33.44, "lon" => -112.07, "aqi" => 95,
        "pollutants" => ["o3" => 60, "no2" => 35, "so2" => 7, "pm25" => 28, "hcho" => 8],
        "weather" => ["temp" => 102, "humidity" => 20, "wind" => "5 mph E", "icon" => "☀️"],
         "forecast" => [
            ["day" => "Today", "aqi" => 95],
            ["day" => "Tomorrow", "aqi" => 102],
            ["day" => "Mon", "aqi" => 95],
            ["day" => "Tue", "aqi" => 110],
            ["day" => "Wed", "aqi" => 100]
        ]
    ],
    "toronto" => [
        "lat" => 43.65, "lon" => -79.38, "aqi" => 55,
        "pollutants" => ["o3" => 35, "no2" => 20, "so2" => 4, "pm25" => 15, "hcho" => 5],
        "weather" => ["temp" => 65, "humidity" => 65, "wind" => "9 mph SW", "icon" => "☁️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 55],
            ["day" => "Tomorrow", "aqi" => 60],
            ["day" => "Mon", "aqi" => 55],
            ["day" => "Tue", "aqi" => 68],
            ["day" => "Wed", "aqi" => 50]
        ]
    ],
    "mexico city" => [
        "lat" => 19.43, "lon" => -99.13, "aqi" => 155,
        "pollutants" => ["o3" => 90, "no2" => 65, "so2" => 15, "pm25" => 60, "hcho" => 15],
        "weather" => ["temp" => 75, "humidity" => 80, "wind" => "4 mph E", "icon" => "⛈️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 155],
            ["day" => "Tomorrow", "aqi" => 160],
            ["day" => "Mon", "aqi" => 150],
            ["day" => "Tue", "aqi" => 165],
            ["day" => "Wed", "aqi" => 158]
        ]
    ],
    "denver" => [
        "lat" => 39.73, "lon" => -104.99, "aqi" => 72,
        "pollutants" => ["o3" => 45, "no2" => 27, "so2" => 5, "pm25" => 22, "hcho" => 7],
        "weather" => ["temp" => 78, "humidity" => 40, "wind" => "15 mph W", "icon" => "💨"],
        "forecast" => [
            ["day" => "Today", "aqi" => 72],
            ["day" => "Tomorrow", "aqi" => 80],
            ["day" => "Mon", "aqi" => 75],
            ["day" => "Tue", "aqi" => 85],
            ["day" => "Wed", "aqi" => 70]
        ]
    ],
    "seattle" => [
        "lat" => 47.60, "lon" => -122.33, "aqi" => 30,
        "pollutants" => ["o3" => 20, "no2" => 10, "so2" => 2, "pm25" => 8, "hcho" => 3],
        "weather" => ["temp" => 66, "humidity" => 72, "wind" => "7 mph NW", "icon" => "🌧️"],
        "forecast" => [
            ["day" => "Today", "aqi" => 30],
            ["day" => "Tomorrow", "aqi" => 35],
            ["day" => "Mon", "aqi" => 40],
            ["day" => "Tue", "aqi" => 28],
            ["day" => "Wed", "aqi" => 33]
        ]
    ],
];

$city = isset($_GET['city']) ? strtolower(trim($_GET['city'])) : '';
$response = [];

if (!empty($city) && array_key_exists($city, $dataCache)) {
    $response = $dataCache[$city];
} else {
    http_response_code(404);
    $response = ['error' => 'City not found'];
}

echo json_encode($response);

