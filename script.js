// --- FRONTEND JAVASCRIPT LOGIC (script.js) ---

const AirsightApp = {
    init() {
        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') this.handleSearch();
        });
        this.renderMap();
        this.renderAqiGuidance();
        // Initial search for a default location
        this.searchForCity("Denver"); 
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

        const cityData = await this.fetchDataForCity(city);
        
        document.getElementById('loader').classList.add('hidden');

        if (cityData) {
            document.getElementById('results-container').classList.remove('hidden');
            this.updateDashboard(city, cityData);
        } else {
            alert('Location not found. Please try another city.');
            document.getElementById('results-container').classList.add('hidden');
            document.getElementById('initial-message').classList.remove('hidden');
        }
    },

    async fetchDataForCity(city) {
        try {
            // Use a small delay to simulate network request
            await new Promise(resolve => setTimeout(resolve, 500));
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
        // Update Current Conditions
        const aqiDetails = this.getAQIDetails(data.aqi);
        const badge = document.getElementById('current-aqi-badge');
        badge.textContent = `AQI ${data.aqi}`;
        badge.style.backgroundColor = `${aqiDetails.hex}30`;
        badge.style.color = aqiDetails.hex;
        this.renderCurrentPollutants(data.pollutants);

        // Update Forecast List
        this.renderForecastList(data.forecast);
    },

    renderCurrentPollutants(pollutants) {
        const container = document.getElementById('current-pollutants');
        container.innerHTML = '';
        const pollutantData = [
            { name: 'PM2.5', value: pollutants.pm25, unit: 'µg/m³' },
            { name: 'Ozone (O₃)', value: pollutants.o3, unit: 'ppb' },
            { name: 'NO₂', value: pollutants.no2, unit: 'ppb' }
        ];
        pollutantData.forEach(p => {
            container.innerHTML += `
                <div class="current-pollutant">
                    <div class="value">${p.value}</div>
                    <div class="unit">${p.name}</div>
                </div>
            `;
        });
    },

    renderForecastList(forecast) {
        const list = document.getElementById('forecast-list');
        list.innerHTML = '';
        forecast.slice(0, 3).forEach(item => { // Show first 3 days
            const aqiDetails = this.getAQIDetails(item.aqi);
            const barWidth = Math.min(100, (item.aqi / 300) * 100); // Scale bar width up to 300 AQI
            list.innerHTML += `
                <li class="forecast-item">
                    <span class="day">${item.day}</span>
                    <div class="aqi-trend">
                        <div class="aqi-bar-container">
                            <div class="aqi-bar" style="width: ${barWidth}%; background-color: ${aqiDetails.hex};"></div>
                        </div>
                        <span class="font-bold" style="color: ${aqiDetails.hex};">${item.aqi}</span>
                    </div>
                </li>
            `;
        });
    },

    renderAqiGuidance() {
        const container = document.getElementById('aqi-guidance');
        const guidanceLevels = [
            this.getAQIDetails(25), // Good
            this.getAQIDetails(75), // Moderate
            this.getAQIDetails(125), // Unhealthy for Sensitive
            this.getAQIDetails(175), // Unhealthy
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

    // --- Map Rendering ---
    renderMap() {
        const mapContainer = document.getElementById('map-points-container');
        if (!mapContainer) return;
        mapContainer.innerHTML = ''; 

        const cityCoords = {
            "new york": { x: 780, y: 280, aqi: 45 }, "los angeles": { x: 230, y: 410, aqi: 88 },
            "chicago": { x: 600, y: 310, aqi: 65 }, "houston": { x: 500, y: 550, aqi: 110 },
            "phoenix": { x: 300, y: 450, aqi: 95 }, "toronto": { x: 720, y: 250, aqi: 55 },
            "mexico city": { x: 450, y: 700, aqi: 155 }, "denver": { x: 400, y: 350, aqi: 72 },
            "seattle": { x: 200, y: 150, aqi: 30 }
        };

        for (const city in cityCoords) {
            const data = cityCoords[city];
            const aqiDetails = this.getAQIDetails(data.aqi);
            const point = document.createElement('div');
            point.className = 'map-point';
            // Note: These coordinates are arbitrary for this placeholder map
            point.style.left = `${(data.x / 1024) * 100}%`;
            point.style.top = `${(data.y / 768) * 100}%`;
            point.style.backgroundColor = aqiDetails.hex;
            point.title = `${this.capitalize(city)} - AQI: ${data.aqi}`;
            point.onclick = () => {
                document.getElementById('search-input').value = city;
                this.handleSearch();
            };
            mapContainer.appendChild(point);
        }
    },
            
    getAQIDetails(aqi) {
        if (aqi <= 50) return { category: 'Good', hex: '#22c55e', healthImplications: 'Air quality is satisfactory, and air pollution poses little or no risk.' };
        if (aqi <= 100) return { category: 'Moderate', hex: '#facc15', healthImplications: 'Unusually sensitive individuals should consider reducing prolonged or heavy exertion.' };
        if (aqi <= 150) return { category: 'Unhealthy for Sensitive', hex: '#f97316', healthImplications: 'Sensitive groups should reduce prolonged or heavy outdoor exertion.' };
        if (aqi <= 200) return { category: 'Unhealthy', hex: '#ef4444', healthImplications: 'Everyone should reduce heavy outdoor exertion.' };
        if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#a855f7', healthImplications: 'Everyone should avoid all physical activity outdoors.' };
        return { category: 'Hazardous', hex: '#be123c', healthImplications: 'Remain indoors and keep activity levels low.' };
    },
            
    capitalize(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }
};

document.addEventListener('DOMContentLoaded', () => AirsightApp.init());

