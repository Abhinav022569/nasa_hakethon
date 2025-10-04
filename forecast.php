<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airsight - AQI Forecast</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link href="style.css" rel="stylesheet">
    <link href="forecast.css" rel="stylesheet">
</head>
<body class="flex">
    <aside class="sidebar">
        <div class="p-4">
            <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-cyan-400"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm.53 5.47a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.72-1.72v5.69a.75.75 0 0 0 1.5 0v-5.69l1.72 1.72a.75.75 0 1 0 1.06-1.06l-3-3Z" clip-rule="evenodd" /></svg>
                Airsight
            </h1>
        </div>
        <nav class="mt-8 flex-grow">
            <a href="index.php" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span>Home</span>
            </a>
             <a href="forecast.php" class="sidebar-link active">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Forecast</span>
            </a>
             <a href="#" class="sidebar-link">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 10-2 0v2a1 1 0 102 0V7zm1-4a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" /></svg>
                <span>About</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <nav class="top-nav">
             <div class="flex items-center gap-4">
                <h2 id="location-name" class="text-xl font-bold text-gray-800">Loading...</h2>
             </div>
             <div class="flex items-center gap-4">
                <div class="search-container">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-gray-400"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z" clip-rule="evenodd" /></svg>
                    <input type="text" id="search-input" placeholder="Search city or region" class="search-input">
                </div>
             </div>
        </nav>
        
        <div class="p-6 grid grid-cols-12 gap-6" id="forecast-content">
            <div class="col-span-12 lg:col-span-8 space-y-6">
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">AQI Forecast (7-day)</h3>
                    <div class="chart-container">
                        <canvas id="daily-forecast-chart"></canvas>
                    </div>
                </div>
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-700 mb-4">Hourly AQI Detail (Next 48 hours)</h3>
                    <div class="chart-container">
                        <canvas id="hourly-forecast-chart"></canvas>
                    </div>
                </div>
            </div>

            <aside class="col-span-12 lg:col-span-4">
                <div class="card p-6 guidance-card space-y-4">
                    <h3 class="font-semibold text-gray-700">Health Guidance</h3>
                    <div id="guidance-content">
                        </div>
                </div>
            </aside>
        </div>
        
        <div id="loader" class="loader-container hidden"><div class="loader"></div></div>
    </main>

    <script src="forecast.js"></script>
</body>
</html>