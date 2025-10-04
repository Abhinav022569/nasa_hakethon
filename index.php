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
    <!-- Left Navigation Sidebar -->
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
            <a href="#" class="sidebar-link active">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span>Home</span>
            </a>
             <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                <span>Map</span>
            </a>
             <a href="#" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Forecast</span>
            </a>
             <a href="#" class="sidebar-link">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 10-2 0v2a1 1 0 102 0V7zm1-4a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" /></svg>
                <span>About</span>
            </a>
        </nav>
        <div class="p-4 mt-auto text-xs text-gray-400">
            <p class="font-semibold">Data Sources:</p>
            <p>TEMPO, Ground, Weather</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1">
        <!-- Top Navigation -->
        <nav class="top-nav">
             <div class="flex items-center gap-4">
                <a href="#" class="nav-tab active">Map</a>
                <a href="#" class="nav-tab">Forecast</a>
                <a href="#" class="nav-tab">Alerts</a>
                <a href="#" class="nav-tab">TEMPO</a>
                <a href="#" class="nav-tab">Ground</a>
                <a href="#" class="nav-tab">Weather</a>
             </div>
             <div class="flex items-center gap-4">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" /></svg>
                    <input type="text" id="search-input" placeholder="Search city or region" class="search-input">
                </div>
                <button class="share-button">Share</button>
                <img src="https://i.pravatar.cc/32?u=a042581f4e29026704d" alt="User" class="rounded-full">
             </div>
        </nav>
        
        <!-- Content Grid -->
        <div class="p-6 grid grid-cols-12 gap-6">
            <!-- Map and Central Content -->
            <div class="col-span-12 lg:col-span-8">
                <div class="map-card">
                   <div class="map-controls">
                        <div class="map-legend">
                            <span class="legend-item"><span class="dot bg-green-500"></span>Good</span>
                            <span class="legend-item"><span class="dot bg-yellow-400"></span>Moderate</span>
                            <span class="legend-item"><span class="dot bg-red-500"></span>Unhealthy</span>
                        </div>
                        <div>
                             <button class="map-button">Enable Alerts</button>
                             <button class="map-button secondary">Download Data</button>
                        </div>
                   </div>
                   <div class="map-container" id="map-container">
                   </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <aside class="col-span-12 lg:col-span-4 space-y-6">
                <div id="loader" class="loader-container hidden"><div class="loader"></div></div>
                <div id="results-container" class="hidden space-y-6">
                    <!-- Current Conditions -->
                    <div class="card p-4">
                        <div class="flex justify-between items-center mb-3">
                             <h3 class="font-semibold text-gray-700">Current Conditions</h3>
                             <span id="current-aqi-badge" class="aqi-badge"></span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">Based on TEMPO + Ground + Weather</p>
                        <div id="current-pollutants" class="grid grid-cols-3 gap-3">
                            <!-- Current pollutants injected here -->
                        </div>
                    </div>
                    
                    <!-- Forecast -->
                    <div class="card p-4">
                        <div class="flex justify-between items-center mb-3">
                             <h3 class="font-semibold text-gray-700">Forecast</h3>
                             <div class="toggle-switch">
                                 <button class="toggle-btn active">Daily</button>
                                 <button class="toggle-btn">Hourly</button>
                             </div>
                        </div>
                        <ul id="forecast-list" class="space-y-3">
                           <!-- Forecast items injected here -->
                        </ul>
                    </div>

                    <!-- Notification Settings -->
                    <div class="card p-4">
                        <h3 class="font-semibold text-gray-700 mb-4">Notification Settings</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label for="aqi-toggle" class="text-sm text-gray-600">AQI above 100</label>
                                <div class="switch-container"><input type="checkbox" id="aqi-toggle" class="switch"><label for="aqi-toggle"></label></div>
                            </div>
                             <div class="flex justify-between items-center">
                                <label for="pm25-toggle" class="text-sm text-gray-600">PM2.5 above 35 µg/m³</label>
                                <div class="switch-container"><input type="checkbox" id="pm25-toggle" class="switch"><label for="pm25-toggle"></label></div>
                            </div>
                        </div>
                    </div>

                    <!-- AQI Guidance -->
                    <div class="card p-4">
                        <h3 class="font-semibold text-gray-700 mb-2">AQI Levels & Guidance</h3>
                        <div id="aqi-guidance" class="space-y-2 text-xs">
                             <!-- Guidance injected here -->
                        </div>
                    </div>
                </div>
                <!-- Initial State Message -->
                <div id="initial-message" class="text-center text-gray-400 mt-10">
                    <h3 class="font-semibold text-gray-600">Awaiting Location</h3>
                    <p class="text-sm">Search for a city to view air quality data and forecasts.</p>
                </div>
            </aside>
        </div>
    </main>

    <!-- Link to the external JavaScript file -->
    <script src="script.js"></script>
</body>
</html>

