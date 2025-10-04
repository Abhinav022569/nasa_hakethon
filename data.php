<?php

$mockData = [
    'San Francisco, CA' => [
        'aqi' => 42,
        'status' => 'Good',
        'pm25' => 8,
        'pm10' => 14,
        'o3' => 22,
        'co' => 0.4,
        'so2' => 3,
        'no2' => 10,
        'recommendation' => 'Air quality is excellent. It\'s a great day to be active outside.'
    ],
    'Seattle, WA' => [
        'aqi' => 25,
        'status' => 'Good',
        'pm25' => 5,
        'pm10' => 9,
        'o3' => 18,
        'co' => 0.2,
        'so2' => 1,
        'no2' => 7,
        'recommendation' => 'Air quality is fantastic. Enjoy the fresh air!'
    ],
    'Denver, CO' => [
        'aqi' => 78,
        'status' => 'Moderate',
        'pm25' => 25,
        'pm10' => 40,
        'o3' => 35,
        'co' => 0.8,
        'so2' => 6,
        'no2' => 15,
        'recommendation' => 'Air quality is acceptable. Unusually sensitive individuals should consider reducing prolonged or heavy exertion outdoors.'
    ],
    'Los Angeles, CA' => [
        'aqi' => 121,
        'status' => 'Unhealthy',
        'pm25' => 45,
        'pm10' => 70,
        'o3' => 50,
        'co' => 1.2,
        'so2' => 9,
        'no2' => 25,
        'recommendation' => 'Members of sensitive groups may experience health effects. The general public is less likely to be affected.'
    ]
];

$recentSearches = [
    ['location' => 'Seattle, WA', 'status' => 'Good'],
    ['location' => 'Denver, CO', 'status' => 'Moderate'],
    ['location' => 'Los Angeles, CA', 'status' => 'Unhealthy']
];

$futuristicTips = [
    ['icon' => 'fa-shield-alt', 'text' => 'Activate personal bio-filters when AQI exceeds 100 for enhanced protection.'],
    ['icon' => 'fa-robot', 'text' => 'Deploy air-purifying drones in your home for optimal indoor air quality.'],
    ['icon' => 'fa-leaf', 'text' => 'Utilize genetically engineered houseplants that absorb 5x more pollutants.'],
    ['icon' => 'fa-satellite-dish', 'text' => 'Check hyperlocal satellite data for real-time air quality shifts in your immediate vicinity.']
];

$mapPoints = [
    ['x' => '20%', 'y' => '40%', 'status' => 'Good'],
    ['x' => '25%', 'y' => '60%', 'status' => 'Moderate'],
    ['x' => '50%', 'y' => '30%', 'status' => 'Good'],
    ['x' => '75%', 'y' => '70%', 'status' => 'Unhealthy'],
    ['x' => '80%', 'y' => '45%', 'status' => 'Moderate']
];

?>
