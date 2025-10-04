<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AeroForecast - Real-time Air Quality</title>
    <!-- Tailwind CSS for utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Link to the external stylesheet -->
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div id="app" class="min-h-screen flex flex-col">

        <!-- Header -->
        <header class="header fixed top-0 left-0 right-0 z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                <h1 class="text-2xl md:text-3xl font-bold tracking-wider font-display text-shadow">
                    AeroForecast
                </h1>
                <span class="text-sm hidden sm:block text-gray-400">Real-time Atmospheric Analysis</span>
            </div>
        </header>
        
        <div class="pt-20"> <!-- Padding to offset for fixed header -->
            <!-- Notification Banner -->
            <div id="notification-banner" class="notification-banner text-center p-3 font-semibold shadow-lg">
                <span id="notification-text"></span>
                <button onclick="AeroForecastApp.closeNotification()" class="ml-4 font-bold text-xl">&times;</button>
            </div>

            <!-- Main Content -->
            <main class="flex-grow container mx-auto p-4 sm:p-6 lg:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

                    <!-- Left Panel: Controls and Data Display -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Search Card -->
                        <div class="card p-5">
                            <h2 class="text-xl font-semibold mb-3 font-display">Atmospheric Scan</h2>
                            <div class="flex items-center space-x-2">
                                <input type="text" id="search-input" placeholder="Enter City, State, or Zip..." class="search-input w-full">
                                <button id="search-button" class="search-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Results Display Card -->
                        <div id="results-card" class="card p-5 hidden">
                            <div id="loader" class="loader hidden"></div>
                            <div id="results-content" class="hidden">
                                <div class="text-center mb-4">
                                    <h3 id="location-name" class="text-3xl font-bold font-display"></h3>
                                    <p class="text-gray-400 text-sm mt-1">Air Quality Index (AQI)</p>
                                    <div id="aqi-pill-container" class="mt-3"></div>
                                    <p id="aqi-category" class="font-semibold mt-2 text-lg"></p>
                                </div>

                                <div class="border-b border-gray-700">
                                    <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                                        <button onclick="AeroForecastApp.showTab('health')" id="tab-health" class="tab-button active">Health Intel</button>
                                        <button onclick="AeroForecastApp.showTab('pollutants')" id="tab-pollutants" class="tab-button">Pollutants</button>
                                        <button onclick="AeroForecastApp.showTab('weather')" id="tab-weather" class="tab-button">Atmos Data</button>
                                    </nav>
                                </div>

                                <div id="tab-content-health" class="tab-content mt-4 text-sm text-gray-300"></div>
                                <div id="tab-content-pollutants" class="tab-content hidden mt-4 space-y-3"></div>
                                <div id="tab-content-weather" class="tab-content hidden mt-4 space-y-3"></div>

                                <div class="mt-6">
                                     <h4 class="text-lg font-semibold text-gray-300 mb-2 font-display">Forecast</h4>
                                     <div id="forecast-container" class="grid grid-cols-3 gap-2 text-center"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel: Map -->
                    <div class="lg:col-span-2 card p-2">
                        <div class="map-container">
                            <svg class="w-full h-full map-svg" viewBox="0 0 1024 768" xmlns="http://www.w3.org/2000/svg">
                                <path d="M152.06,201.21L135.5,233.5l-2.5,15l2.5,7l-3,3.5l-10,24.5l-1,34l-14.5,29.5l-4,13.5l-2.5,22l-1.5,14l-12.5,27.5l-2,4.5l-1.5,13l-4.5,12l-12,9l-2,10.5l-1.5,9.5l3.5,6l1,13.5l1.5,13.5l-4.5,2.5l-1.5,8.5l-3.5,3.5l-3.5,11l-2,10.5l-1,22l2.5,11.5l14.5,23l14.5,11l20,13.5l12.5,1.5l14.5-2l14-4l24-10.5l11-8.5l8-10.5l15.5-31l13-33.5l4-14l6.5-19.5l10.5-21l16.5-25.5l10-10l12-7.5l22-7l19.5-2l21.5-1l12,1.5l30,12.5l17.5,13.5l12.5,14l15,22.5l13.5,27l12.5,31l11,28l12,23l15,19l19,15.5l10.5,5l16.5,5.5l19.5,4l16.5,1.5l22-1.5l20.5-4l21.5-7l17-8l15.5-12.5l13.5-16l11.5-19l9.5-22l7-22.5l4.5-20l2-16.5l-0.5-19.5l-3-17.5l-5.5-15.5l-8.5-13l-12-11l-16-9.5l-20.5-8l-24.5-7.5l-28.5-7l-32-6.5l-35.5-6l-38.5-5.5l-41-5l-43-4.5l-45-4l-46.5-3.5l-47.5-3l-48.5-2.5l-49-2l-49.5-1.5l-49.5-1l-49.5-0.5h-49.5l-49,0.5l-48.5,1l-48,1.5l-47,2l-46,2.5l-45,3l-43.5,3.5l-42.5,4l-41,4.5l-39.5,5l-38,5.5l-36.5,6l-34.5,6.5l-32.5,7l-30.5,7.5l-28.5,8l-26,8.5l-23.5,9l-21,9.5l-18.5,10l-16,10.5l-13.5,11l-11,11.5l-8.5,12l-6,12.5l-3.5,13l-1,13.5l1,14l2.5,14.5l3.5,15l4,15.5l4.5,16l4.5,16.5l4,17l3.5,17.5l2.5,18l1.5,18.5l0.5,19l-0.5,19.5l-1.5,20l-2,20.5l-3,21l-3.5,21.5l-4,22l-4.5,22.5l-5,23l-5,23.5l-5,24l-5,24.5l-5,25l-4.5,25.5l-4.5,26l-4,26.5l-3.5,27l-3,27.5l-2.5,28l-2,28.5l-1.5,29l-1,29.5l-0.5,30v30l0.5,29.5l1,29l1.5,28.5l2,28l2.5,27.5l3,27l3.5,26.5l4,26l4.5,25.5l4.5,25l5,24.5l5,24l5,23.5l5,23l4.5,22.5l4.5,22l4,21.5l3.5,21l3,20.5l2.5,20l1.5,19.5l0.5,19l-0.5,18.5l-1.5,18l-2.5,17.5l-3.5,17l-4,16.5l-4.5,16l-4.5,15.5l-4.5,15l-4,14.5l-3.5,14l-2.5,13.5l-1.5,13l-0.5,12.5v12l0.5,11.5l1,11l1.5,10.5l2,10l2.5,9.5l3,9l3.5,8.5l4,8l4,7.5l4.5,7l4.5,6.5l4.5,6l4.5,5.5l4.5,5l4,4.5l4,4l3.5,3.5l3.5,3l3,2.5l2.5,2l2,1.5l1.5,1l0.5,0.5h-0.5l-1-0.5l-1.5-1l-2-1.5l-2.5-2l-3-2.5l-3.5-3l-3.5-3.5l-4-4l-4-4.5l-4.5-5l-4.5-5.5l-4.5-6l-4.5-6.5l-4.5-7l-4.5-7.5l-4-8l-4-8.5l-3.5-9l-3.5-9.5l-3-10l-2.5-10.5l-2-11l-1.5-11.5l-1-12l-0.5-12.5v-13l0.5-13.5l1-14l1.5-14.5l2-15l2.5-15.5l3-16l3.5-16.5l3.5-17l4-17.5l4-18l4.5-18.5l4.5-19l4.5-19.5l5-20l5-20.5l5-21l5-21.5l5-22l5-22.5l5-23l4.5-23.5l4.5-24l4.5-24.5l4-25l4-25.5l4-26l3.5-26.5l3.5-27l3-27.5l2.5-28l2-28.5l1.5-29l1-29.5l0.5-30v-30l-0.5-29.5l-1-29l-1.5-28.5l-2-28l-2.5-27.5l-3-27l-3.5-26.5l-4-26l-4-25.5l-4.5-25l-4.5-24.5l-4.5-24l-4.5-23.5l-4.5-23l-5-22.5l-5-22l-5-21.5l-5-21l-5-20.5l-4.5-20l-4.5-19.5l-4.5-19l-4.5-18.5l-4.5-18l-4-17.5l-4-17l-3.5-16.5l-3.5-16l-3-15.5l-3-15l-2.5-14.5l-2-14l-1.5-13.5l-1-13l-0.5-12.5v-12l0.5-11.5l0.5-11l1-10.5l1.5-10l1.5-9.5l2-9l2.5-8.5l2.5-8l3-7.5l3-7l3.5-6.5l3.5-6l3.5-5.5l4-5l4-4.5l4-4l4-3.5l4-3l4-2.5l4.5-2l4.5-1.5l4.5-1l4.5-0.5h4.5l4.5,0.5l4.5,1l4.5,1.5l4.5,2l4.5,2.5l4.5,3l4.5,3.5l4.5,4l4.5,4.5l4.5,5l4,5.5l4,6l4,6.5l3.5,7l3.5,7.5l3.5,8l3,8.5l3,9l2.5,9.5l2.5,10l2,10.5l1.5,11l1.5,11.5l1,12l0.5,12.5v13l-0.5,13.5l-1,14l-1.5,14.5l-1.5,15l-2,15.5l-2.5,16l-2.5,16.5l-3,17l-3,17.5l-3.5,18l-3.5,18.5l-3.5,19l-3.5,19.5l-4,20l-4,20.5l-4,21l-4,21.5l-4,22l-4,22.5l-4,23l-4,23.5l-3.5,24l-3.5,24.5l-3.5,25l-3.5,25.5l-3.5,26l-3,26.5l-3,27l-2.5,27.5l-2.5,28l-2,28.5l-1.5,29l-1,29.5l-0.5,30v30l0.5,29.5l1,29l1.5,28.5l2,28l2.5,27.5l2.5,27l3,26.5l3,26l3.5,25.5l3.5,25l3.5,24.5l3.5,24l3.5,23.5l4,23l4,22.5l4,22l4,21.5l4,21l4,20.5l4,20l4,19.5l3.5,19l3.5,18.5l3.5,18l3.5,17.5l3,17l3,16.5l2.5,16l2.5,15.5l2,15l1.5,14.5l1.5,14l1,13.5l0.5,13v-12.5l-0.5-12l-0.5-11.5l-1-11l-1.5-10.5l-1.5-10l-2-9.5l-2-9l-2.5-8.5l-2.5-8l-3-7.5l-3-7l-3-6.5l-3.5-6l-3.5-5.5l-3.5-5l-4-4.5l-4-4l-4-3.5l-4-3l-4-2.5l-4.5-2l-4.5-1.5l-4.5-1l-4.5-0.5h-4.5l-4.5,0.5l-4.5,1l-4.5,1.5l-4.5,2l-4.5,2.5l-4.5,3l-4.5,3.5l-4,4l-4,4.5l-4,5l-4,5.5l-3.5,6l-3.5,6.5l-3.5,7l-3.5,7.5l-3,8l-3,8.5l-2.5,9l-2.5,9.5l-2,10l-1.5,10.5l-1.5,11l-1,11.5l-0.5,12v12.5l0.5,13l1,13.5l1,14l1.5,14.5l1.5,15l2,15.5l2,16l2.5,16.5l2.5,17l2.5,17.5l3,18l3,18.5l3,19l3.5,19.5l3.5,20l3.5,20.5l3.5,21l3.5,21.5l3.5,22l3.5,22.5l3.5,23l3,23.5l3,24l3,24.5l3,25l2.5,25.5l2.5,26l2.5,26.5l2,27l2,27.5l1.5,28l1.5,28.5l1,29l0.5,29.5v30l-0.5,29.5l-1,29l-1.5,28.5l-1.5,28l-2,27.5l-2,27l-2.5,26.5l-2.5,26l-2.5,25.5l-3,25l-3,24.5l-3,24l-3,23.5l-3,23l-3.5,22.5l-3.5,22l-3.5,21.5l-3.5,21l-3.5,20.5l-3.5,20l-3.5,19.5l-3.5,19l-3.5,18.5l-3,18l-3,17.5l-3,17l-2.5,16.5l-2.5,16l-2.5,15.5l-2,15l-2,14.5l-1.5,14l-1.5,13.5l-1,13l-0.5,12.5z" />
                            </svg>
                            <div id="map-points-container" class="absolute top-0 left-0 w-full h-full"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-transparent text-white mt-8">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
                <p>&copy; 2025 AeroForecast // Simulated data based on NASA TEMPO mission parameters.</p>
            </div>
        </footer>

    </div>

    <!-- Link to the external JavaScript file -->
    <script src="script.js"></script>
</body>
</html>
