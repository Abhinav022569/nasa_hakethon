<?php
require 'data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AeroGuard - Futuristic Air Quality Monitor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="p-4 lg:p-8">

    <div class="max-w-screen-2xl mx-auto">
        <!-- Header -->
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold tracking-wider text-white">
                <i class="fas fa-wind mr-2 text-blue-400"></i>AeroGuard
            </h1>
            <div class="flex items-center space-x-3">
                <div class="w-3 h-3 bg-green-400 rounded-full status-dot"></div>
                <span class="text-sm text-green-400 font-medium">Real-time data</span>
            </div>
        </header>

        <!-- Main Grid -->
        <main class="grid grid-cols-1 lg:grid-cols-4 xl:grid-cols-5 gap-8">
            
            <!-- Left Column -->
            <div class="lg:col-span-1 xl:col-span-1 space-y-8">
                <!-- Search Location -->
                <div class="glass-card p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white">Search Location</h2>
                    <div class="relative">
                        <input id="location-input" type="text" placeholder="Enter City or ZIP code" class="w-full bg-transparent border-2 border-gray-600 rounded-lg py-3 px-4 focus:outline-none focus:border-blue-400 transition duration-300">
                        <button class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <button class="w-full btn-primary text-white font-bold py-3 px-4 rounded-lg mt-4">Search</button>
                </div>

                <!-- Recent Searches -->
                <div class="glass-card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-white">Recent Searches</h2>
                        <button class="text-sm text-gray-400 hover:text-white transition">Clear</button>
                    </div>
                    <ul id="recent-searches" class="space-y-4">
                        <!-- JS will populate this -->
                    </ul>
                </div>

                <!-- Futuristic Tips -->
                <div class="glass-card p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white"><i class="fas fa-lightbulb mr-2 text-yellow-300"></i>Futuristic Tips</h2>
                    <div id="tip-container" class="text-gray-300 space-y-3">
                       <!-- JS will populate this -->
                    </div>
                </div>
            </div>

            <!-- Center Column -->
            <div class="lg:col-span-3 xl:col-span-2 space-y-8">
                <!-- Main AQI Display -->
                <div class="glass-card p-8">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white" id="current-city">San Francisco, CA</h3>
                            <p class="text-gray-400" id="update-time">Updated just now</p>
                        </div>
                        <div id="aqi-status-badge" class="px-4 py-1 rounded-full text-sm font-bold">Good</div>
                    </div>

                    <div class="flex flex-col md:flex-row items-center justify-around gap-8 mt-8">
                        <!-- AQI Gauge -->
                        <div class="relative">
                           <svg class="w-48 h-48 transform -rotate-90" viewBox="0 0 120 120">
                                <circle class="aqi-gauge-bg" cx="60" cy="60" r="54" fill="none" stroke-width="12"></circle>
                                <circle id="aqi-gauge-fg" class="aqi-gauge-fg" cx="60" cy="60" r="54" fill="none" stroke-width="12" pathLength="100"></circle>
                           </svg>
                           <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span id="aqi-value" class="text-5xl font-bold">42</span>
                                <span class="text-lg text-gray-400">AQI</span>
                           </div>
                        </div>

                        <!-- Pollutant Details -->
                        <div class="grid grid-cols-3 gap-x-6 gap-y-6 w-full md:w-auto">
                            <!-- JS will populate this -->
                        </div>
                    </div>
                </div>

                <!-- Live Map -->
                 <div class="glass-card p-6 h-96 lg:h-auto flex-grow">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-white">Live Map — United States</h2>
                        <div class="flex items-center space-x-2 text-sm text-blue-400">
                             <div class="w-2 h-2 bg-blue-400 rounded-full status-dot"></div>
                             <span>Streaming</span>
                        </div>
                    </div>
                    <div class="relative w-full h-full min-h-[300px] lg:min-h-0 rounded-lg overflow-hidden">
                        <img src="https://placehold.co/800x600/0a0a1a/e0e0e0?text=Live+Map" class="w-full h-full object-cover opacity-20" alt="Map of United States">
                        <div class="map-overlay"></div>
                        <div id="map-points-container">
                            <!-- JS will generate map points -->
                        </div>
                    </div>
                 </div>
            </div>
            
            <!-- Right Column -->
            <div class="lg:col-span-4 xl:col-span-2">
                 <div class="glass-card p-6 h-full">
                    <h2 class="text-xl font-semibold mb-4 text-white"><i class="fas fa-notes-medical mr-2 text-cyan-300"></i>Health Recommendation</h2>
                    <div id="health-recommendation" class="text-gray-300 leading-relaxed">
                        <!-- JS will populate this -->
                    </div>
                </div>
            </div>

        </main>
        
        <!-- Footer -->
        <footer class="text-center text-gray-500 mt-12 text-sm">
            <p>© 2025 AeroGuard Industries. All rights reserved.</p>
            <div class="mt-2 space-x-4">
                <a href="#" class="hover:text-white">Privacy</a>
                <span>&middot;</span>
                <a href="#" class="hover:text-white">Terms</a>
                <span>&middot;</span>
                <a href="#" class="hover:text-white">Contact</a>
            </div>
        </footer>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const mockData = <?php echo json_encode($mockData); ?>;
        const recentSearches = <?php echo json_encode($recentSearches); ?>;
        const futuristicTips = <?php echo json_encode($futuristicTips); ?>;
        const mapPoints = <?php echo json_encode($mapPoints); ?>;

        const aqiGaugeFg = document.getElementById('aqi-gauge-fg');
        const aqiValue = document.getElementById('aqi-value');
        const aqiStatusBadge = document.getElementById('aqi-status-badge');
        const currentCity = document.getElementById('current-city');
        const updateTime = document.getElementById('update-time');
        const healthRecommendation = document.getElementById('health-recommendation');
        const pollutantDetailsContainer = document.querySelector('.grid.grid-cols-3.gap-x-6');
        const recentSearchesContainer = document.getElementById('recent-searches');
        const tipContainer = document.getElementById('tip-container');
        const mapPointsContainer = document.getElementById('map-points-container');
        const locationInput = document.getElementById('location-input');
        
        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'good': return 'green';
                case 'moderate': return 'yellow';
                case 'unhealthy': return 'red';
                default: return 'gray';
            }
        }

        function getAqiColorClass(aqi) {
            if (aqi <= 50) return { stroke: '#4ade80', bg: 'bg-green-500', text: 'text-glow-green' }; // green
            if (aqi <= 100) return { stroke: '#facc15', bg: 'bg-yellow-400', text: 'text-glow-yellow' }; // yellow
            if (aqi <= 150) return { stroke: '#f87171', bg: 'bg-red-500', text: 'text-glow-red' }; // red
            return { stroke: '#ef4444', bg: 'bg-red-600', text: 'text-glow-red' };
        }
        
        function updateDashboard(location) {
            const data = mockData[location];
            if (!data) return;

            // Update AQI gauge
            const colorClasses = getAqiColorClass(data.aqi);
            const aqiPercentage = Math.min(data.aqi / 2, 100); // Scale AQI for gauge
            aqiGaugeFg.style.strokeDasharray = 100;
            aqiGaugeFg.style.strokeDashoffset = 100 - aqiPercentage;
            aqiGaugeFg.style.stroke = colorClasses.stroke;

            // Update text values with animations
            let currentAqi = parseInt(aqiValue.textContent);
            const interval = setInterval(() => {
                if (currentAqi < data.aqi) currentAqi++;
                else if (currentAqi > data.aqi) currentAqi--;
                else clearInterval(interval);
                aqiValue.textContent = currentAqi;
            }, 10);
            
            aqiValue.className = `text-5xl font-bold ${colorClasses.text}`;
            aqiStatusBadge.textContent = data.status;
            aqiStatusBadge.className = `px-4 py-1 rounded-full text-sm font-bold text-white ${colorClasses.bg}`;
            currentCity.textContent = location;
            healthRecommendation.textContent = data.recommendation;

            // Update Pollutant Details
            pollutantDetailsContainer.innerHTML = `
                ${createPollutantCard('PM2.5', data.pm25, 'µg/m³')}
                ${createPollutantCard('PM10', data.pm10, 'µg/m³')}
                ${createPollutantCard('O3', data.o3, 'ppb')}
                ${createPollutantCard('CO', data.co, 'ppm')}
                ${createPollutantCard('SO2', data.so2, 'ppb')}
                ${createPollutantCard('NO2', data.no2, 'ppb')}
            `;
        }

        function createPollutantCard(name, value, unit) {
            return `
                <div class="text-center p-3 rounded-lg bg-black bg-opacity-20 hover:bg-opacity-40 transition">
                    <p class="text-sm text-gray-400">${name}</p>
                    <p class="text-xl font-semibold text-white">${value}</p>
                    <p class="text-xs text-gray-500">${unit}</p>
                </div>
            `;
        }
        
        function populateRecentSearches() {
            recentSearchesContainer.innerHTML = recentSearches.map(item => `
                <li class="flex justify-between items-center cursor-pointer p-3 -m-3 rounded-lg hover:bg-black hover:bg-opacity-20 transition search-item" data-location="${item.location}">
                    <span class="font-medium"><i class="fas fa-map-marker-alt mr-3 text-gray-500"></i>${item.location}</span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full text-white bg-${getStatusColor(item.status)}-500">${item.status}</span>
                </li>
            `).join('');
        }

        function cycleTips() {
            let currentTipIndex = 0;
            setInterval(() => {
                currentTipIndex = (currentTipIndex + 1) % futuristicTips.length;
                const tip = futuristicTips[currentTipIndex];
                tipContainer.innerHTML = `
                 <div class="flex items-start space-x-3">
                        <i class="fas ${tip.icon} text-cyan-300 mt-1"></i>
                        <p>${tip.text}</p>
                    </div>`;
            }, 7000);
            // Initial tip
            const initialTip = futuristicTips[0];
            tipContainer.innerHTML = `<div class="flex items-start space-x-3">
                        <i class="fas ${initialTip.icon} text-cyan-300 mt-1"></i>
                        <p>${initialTip.text}</p>
                    </div>`;
        }

        function populateMap() {
            mapPointsContainer.innerHTML = mapPoints.map(point => `
                <div class="map-point" style="left: ${point.x}; top: ${point.y}; background-color: ${getStatusColor(point.status)}; animation-delay: ${Math.random() * 1.5}s"></div>
            `).join('');
        }

        // Event Listeners
        document.querySelector('.btn-primary').addEventListener('click', () => {
             const location = locationInput.value.trim();
             // In a real app, you would have an API call here.
             // We will simulate it by checking if the location exists in mockData.
             const titleCaseLocation = location.split(' ').map(w => w.charAt(0).toUpperCase() + w.substring(1).toLowerCase()).join(' ');
             if(mockData[titleCaseLocation]) {
                updateDashboard(titleCaseLocation);
             } else {
                alert('Location not found. Try "San Francisco, CA", "Denver, CO", etc.');
             }
        });
        
        locationInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                document.querySelector('.btn-primary').click();
            }
        });

        recentSearchesContainer.addEventListener('click', (e) => {
            const item = e.target.closest('.search-item');
            if (item) {
                const location = item.dataset.location;
                updateDashboard(location);
                locationInput.value = location;
            }
        });

        // Initial Load
        updateDashboard('San Francisco, CA');
        populateRecentSearches();
        cycleTips();
        populateMap();

        // Fake real-time update
        setInterval(() => {
            updateTime.textContent = 'Updated just now';
        }, 60000);

    });
</script>
</body>
</html>
