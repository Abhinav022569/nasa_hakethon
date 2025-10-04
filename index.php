<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airsight - Air Quality Forecast</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="style.css" rel="stylesheet">
</head>
<body class="flex">
    <aside class="sidebar">
        <div class="p-4">
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-cyan-400">
                  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" />
                </svg>
                Airsight
            </h1>
        </div>
        <nav class="mt-8 flex-grow">
            <a href="index.php" class="sidebar-link active">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span>Home</span>
            </a>
            <a href="forecast.php" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Forecast</span>
            </a>
             <a href="about.php" class="sidebar-link">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                <span>About</span>
            </a>
        </nav>
        <div class="p-4 mt-auto text-xs text-gray-400">
        </div>
    </aside>

    <main class="flex-1">
        <nav class="top-nav">
             <div class="flex items-center gap-4">
                <a href="#" class="nav-tab active">Map</a>
                <a href="forecast.php" class="nav-tab">Forecast</a>
             </div>
             <div class="flex items-center gap-4">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" /></svg>
                    <input type="text" id="search-input" placeholder="Search city or region" class="search-input">
                </div>
             </div>
        </nav>
        
        <div class="p-6 grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8">
                <div class="map-card">
                   <div class="map-controls">
                        <div class="map-legend">
                            <span class="legend-item"><span class="dot bg-green-500"></span>Good</span>
                            <span class="legend-item"><span class="dot bg-yellow-400"></span>Moderate</span>
                            <span class="legend-item"><span class="dot bg-red-500"></span>Unhealthy</span>
                        </div>
                   </div>
                   <div class="map-container" id="map-container">
                   </div>
                </div>
            </div>

            <aside class="col-span-12 lg:col-span-4 space-y-6">
                <div id="loader" class="loader-container hidden"><div class="loader"></div></div>
                <div id="results-container" class="hidden space-y-6">
                    <div class="card p-4">
                        <div class="flex justify-between items-center mb-3">
                             <h3 class="font-semibold text-gray-700">Current AIR Conditions</h3>
                             <span id="current-aqi-badge" class="aqi-badge"></span>
                        </div>
                        <div id="current-pollutants" class="grid grid-cols-3 gap-3">
                            </div>
                    </div>
                    
                    <div class="card p-4">
                        <div class="flex justify-between items-center mb-3">
                             <h3 class="font-semibold text-gray-700">Forecast</h3>
                             <div class="toggle-switch">
                                 <button class="toggle-btn active">Daily</button>
                                 <button class="toggle-btn">Hourly</button>
                             </div>
                        </div>
                        <ul id="forecast-list" class="space-y-3">
                           </ul>
                    </div>

                    <div class="card p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">AQI Levels & Guidance</h3>
                        <div id="aqi-guidance" class="space-y-2 text-xs">
                             </div>
                    </div>
                </div>
                <div id="initial-message" class="text-center text-gray-400 mt-10">
                    <h3 class="font-semibold text-gray-600">Awaiting Location</h3>
                    <p class="text-sm">Search for a city to view air quality data and forecasts.</p>
                </div>
            </aside>
        </div>
    </main>

    <script src="script.js"></script>
</body>
</html>
