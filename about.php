<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Airsight - About</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link href="style.css" rel="stylesheet">
    <link href="about.css" rel="stylesheet">
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
            <a href="index.php" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                <span>Home</span>
            </a>
            <a href="forecast.php" class="sidebar-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span>Forecast</span>
            </a>
             <a href="about.php" class="sidebar-link active">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                <span>About</span>
            </a>
        </nav>
    </aside>

    <main class="flex-1">
        <div class="p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-cyan-100 text-cyan-600 p-2 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">About AirSight</h2>
            </div>

            <div class="card p-6 mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Air quality forecasting for healthier decisions</h3>
                <p class="text-gray-600">We provide AQI predictions and health guidance across North America to support individuals, families, and agencies in planning safer activities.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="card p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">What we do</h4>
                    <ul class="space-y-4">
                        <li class="about-item">
                            <h5 class="font-semibold">7-day AQI forecasts</h5>
                            <p class="text-gray-500 text-sm">City and regional outlooks using standardized AQI scales.</p>
                        </li>
                        <li class="about-item">
                            <h5 class="font-semibold">Hourly detail</h5>
                            <p class="text-gray-500 text-sm">Short-term AQI changes to plan commutes and outdoor time.</p>
                        </li>
                        <li class="about-item">
                            <h5 class="font-semibold">Health guidance</h5>
                            <p class="text-gray-500 text-sm">Actionable advice tailored for the general public and sensitive groups.</p>
                        </li>
                    </ul>
                </div>
                <div class="card p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Health guidance</h4>
                    <ul class="space-y-4">
                        <li class="about-item">
                            <h5 class="font-semibold">General population</h5>
                            <p class="text-gray-500 text-sm">Limit outdoor exertion if AQI > 100; prefer morning or evening.</p>
                        </li>
                        <li class="about-item">
                            <h5 class="font-semibold">Sensitive groups</h5>
                            <p class="text-gray-500 text-sm">Consider masks and reduce outdoor time when AQI > 100.</p>
                        </li>
                        <li class="about-item">
                            <h5 class="font-semibold">Indoor tips</h5>
                            <p class="text-gray-500 text-sm">Use HEPA filtration and close windows during high AQI periods.</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card p-6">
                 <h4 class="text-lg font-semibold text-gray-800 mb-4">Contact</h4>
                 <div class="space-y-3">
                     <div class="flex justify-between items-center contact-item">
                         <span class="font-medium text-gray-700">Support</span>
                         <a href="mailto:support@airsight.app" class="text-cyan-600 font-semibold">support@airsight.app</a>
                     </div>
                     <div class="flex justify-between items-center contact-item">
                         <span class="font-medium text-gray-700">Press</span>
                         <a href="mailto:press@airsight.app" class="text-cyan-600 font-semibold">press@airsight.app</a>
                     </div>
                     <div class="flex justify-between items-center contact-item">
                         <span class="font-medium text-gray-700">Feedback</span>
                         <span class="text-gray-500">Share ideas to improve AQI forecasts and guidance.</span>
                     </div>
                 </div>
            </div>
        </div>
    </main>
    <script src="about.js"></script>
</body>
</html>
