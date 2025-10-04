// --- FRONTEND JAVASCRIPT LOGIC (script.js) with Leaflet.js Integration ---

const AirsightApp = {
    map: null, // To hold the map instance

    init() {
        // Initialize the Leaflet map
        this.initializeMap(); 

        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') this.handleSearch();
        });

        this.renderMap();
        this.renderAqiGuidance();
        
        // Initial search for a default location
        this.searchForCity("Denver"); 
    },

    initializeMap() {
        // Create a map in the "map-container" div, set the view to a central US location
        this.map = L.map('map-container').setView([39.82, -98.57], 4);

        // Add a tile layer from OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);
    },

    async handleSearch() {
        const query = document.getElementById('search-input').value.toLowerCase().trim();
        if (!query) return;
        this.searchForCity(query);
    },
    
    async searchForCity(city) {
        document.getElementById('initial-message').classList.add('hidden');
        document.getElementById('results-container').classList.add('hidden');
        document.getElementById('loader').classList.remove('hidden');

        // Fetch city data from our backend API
        const cityData = await this.fetchDataForCity(city);
        
        document.getElementById('loader').classList.add('hidden');

        if (cityData) {
            document.getElementById('results-container').classList.remove('hidden');
            this.updateDashboard(city, cityData);
            
            // Pan the map to the new city's location
            if (this.map && cityData.lat && cityData.lon) {
                this.map.flyTo([cityData.lat, cityData.lon], 8); // Zoom in closer
            }

        } else {
            alert('Location not found. Please try another city.');
            document.getElementById('results-container').classList.add('hidden');
            document.getElementById('initial-message').classList.remove('hidden');
        }
    },

    async fetchDataForCity(city) {
        try {
            const response = await fetch(`api.php?city=${encodeURIComponent(city)}`);
            if (!response.ok) {
                console.error("API Error:", response.statusText);
                return null;
            }
            return await response.json();
        } catch (error) {
            console.error("Failed to fetch data:", error);
            return null;
        }
    },

    updateDashboard(city, data) {
        const aqiDetails = this.getAQIDetails(data.aqi);
        const badge = document.getElementById('current-aqi-badge');
        badge.textContent = `AQI ${data.aqi}`;
        badge.className = 'aqi-badge'; // Reset classes
        badge.style.backgroundColor = `${aqiDetails.hex}30`;
        badge.style.color = aqiDetails.hex;

        this.renderCurrentPollutants(data.pollutants);
        this.renderForecastList(data.forecast);
    },

    renderCurrentPollutants(pollutants) {
        const container = document.getElementById('current-pollutants');
        container.innerHTML = '';
        // Use PM2.5, O3, and NO2 from your api.php response
        const pollutantData = [
            { name: 'PM2.5', value: pollutants.pm25.toFixed(2), unit: 'µg/m³' },
            { name: 'Ozone (O₃)', value: pollutants.o3.toFixed(2), unit: 'µg/m³' },
            { name: 'NO₂', value: pollutants.no2.toFixed(2), unit: 'µg/m³' }
        ];
        pollutantData.forEach(p => {
            container.innerHTML += `
                <div class="current-pollutant text-center">
                    <div class="value">${p.value}</div>
                    <div class="unit text-xs">${p.name}</div>
                </div>
            `;
        });
    },

    renderForecastList(forecast) {
        const list = document.getElementById('forecast-list');
        list.innerHTML = '';
        // Use the first 3 days from the forecast array
        forecast.slice(0, 3).forEach(item => {
            const aqiDetails = this.getAQIDetails(item.aqi);
            const barWidth = Math.min(100, (item.aqi / 300) * 100);
            list.innerHTML += `
                <li class="forecast-item">
                    <span class="day">${item.day}</span>
                    <div class="aqi-trend">
                        <div class="aqi-bar-container">
                            <div class="aqi-bar" style="width: ${barWidth}%; background-color: ${aqiDetails.hex};"></div>
                        </div>
                        <span class="font-bold w-8 text-right" style="color: ${aqiDetails.hex};">${item.aqi}</span>
                    </div>
                </li>
            `;
        });
    },

    renderAqiGuidance() {
        const container = document.getElementById('aqi-guidance');
        const guidanceLevels = [
            this.getAQIDetails(25),   // Good
            this.getAQIDetails(75),   // Moderate
            this.getAQIDetails(125),  // Unhealthy for Sensitive
            this.getAQIDetails(175),  // Unhealthy
        ];
        container.innerHTML = '';
        guidanceLevels.forEach(level => {
            container.innerHTML += `
                <div>
                    <p class="font-semibold" style="color: ${level.hex};">${level.category}</p>
                    <p class="text-gray-600">${level.healthImplications}</p>
                </div>
            `;
        });
    },

    // --- Map Rendering with Leaflet ---
    renderMap() {
        if (!this.map) return;

        // Use the same city list from your original api.php cache for the markers
        const cityData = {
            "New York":    { lat: 40.71, lon: -74.00, aqi: 45 },
            "Los Angeles": { lat: 34.05, lon: -118.24, aqi: 88 },
            "Chicago":     { lat: 41.87, lon: -87.62, aqi: 65 },
            "Houston":     { lat: 29.76, lon: -95.36, aqi: 110 },
            "Phoenix":     { lat: 33.44, lon: -112.07, aqi: 95 },
            "Toronto":     { lat: 43.65, lon: -79.38, aqi: 55 },
            "Mexico City": { lat: 19.43, lon: -99.13, aqi: 155 },
            "Denver":      { lat: 39.73, lon: -104.99, aqi: 72 },
            "Seattle":     { lat: 47.60, lon: -122.33, aqi: 30 }
        };

        for (const city in cityData) {
            const data = cityData[city];
            const aqiDetails = this.getAQIDetails(data.aqi);

            // Create a circle marker instead of a standard pin
            const marker = L.circleMarker([data.lat, data.lon], {
                radius: 8,
                fillColor: aqiDetails.hex,
                color: "#fff",
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(this.map);

            // Add a popup that shows info on click
            marker.bindPopup(`<b>${city}</b><br>AQI: ${data.aqi}`);

            // Add a click event to the marker
            marker.on('click', () => {
                this.searchForCity(city);
            });
        }
    },
            
    getAQIDetails(aqi) {
        if (aqi <= 50) return { category: 'Good', hex: '#22c55e', healthImplications: 'Air quality is satisfactory, and air pollution poses little or no risk.' };
        if (aqi <= 100) return { category: 'Moderate', hex: '#facc15', healthImplications: 'Unusually sensitive individuals should consider reducing prolonged or heavy exertion.' };
        if (aqi <= 150) return { category: 'Unhealthy for Sensitive', hex: '#f97316', healthImplications: 'Sensitive groups should reduce prolonged or heavy outdoor exertion.' };
        if (aqi <= 200) return { category: 'Unhealthy', hex: '#ef4444', healthImplications: 'Everyone should reduce heavy outdoor exertion.' };
        if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#a855f7', healthImplications: 'Everyone should avoid all physical activity outdoors.' };
        return { category: 'Hazardous', hex: '#be123c', healthImplications: 'Remain indoors and keep activity levels low.' };
    }
};

document.addEventListener('DOMContentLoaded', () => AirsightApp.init());