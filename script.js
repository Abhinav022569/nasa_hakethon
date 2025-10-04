// --- FRONTEND JAVASCRIPT LOGIC (script.js) ---

const AeroForecastApp = {
    // --- INITIALIZATION ---
    init() {
        // Set up event listeners for the search functionality
        document.getElementById('search-button').addEventListener('click', this.handleSearch.bind(this));
        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') this.handleSearch();
        });
        // Initial map render
        this.renderMap();
    },

    // --- UI & EVENT HANDLING ---
    async handleSearch() {
        const query = document.getElementById('search-input').value.toLowerCase().trim();
        if (!query) return;
        
        const resultsCard = document.getElementById('results-card');
        const loader = document.getElementById('loader');
        const resultsContent = document.getElementById('results-content');

        // Show the results card and loader while fetching data
        resultsCard.classList.remove('hidden');
        loader.classList.remove('hidden');
        resultsContent.classList.add('hidden');

        // Fetch data from our backend API
        const cityData = await this.fetchDataForCity(query);

        // Hide loader and show content
        loader.classList.add('hidden');
        resultsContent.classList.remove('hidden');

        if (cityData) {
            this.updateDashboard(query, cityData);
        } else {
            alert('City not found. Please try another location.');
            resultsCard.classList.add('hidden');
        }
    },
    
    /**
     * Fetches data from the PHP backend API.
     */
    async fetchDataForCity(city) {
        try {
            // Use fetch to make a GET request to our api.php endpoint
            const response = await fetch(`api.php?city=${encodeURIComponent(city)}`);
            if (!response.ok) {
                // If response is not 200 OK, the city was not found or an error occurred
                console.error("API Error:", response.statusText);
                return null;
            }
            // Parse the JSON response
            const data = await response.json();
            return data;
        } catch (error) {
            console.error("Failed to fetch data:", error);
            return null;
        }
    },
            
    showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        // Deactivate all tab buttons
        document.querySelectorAll('.tab-button').forEach(button => button.classList.remove('active'));
        
        // Show the selected tab content and activate the button
        document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        document.getElementById(`tab-${tabName}`).classList.add('active');
    },
            
    showNotification(aqi, city) {
        const banner = document.getElementById('notification-banner');
        const text = document.getElementById('notification-text');
        const aqiDetails = this.getAQIDetails(aqi);
        
        const message = `Air Quality Alert in ${this.capitalize(city)}: AQI is ${aqi} (${aqiDetails.category}).`;
        text.textContent = message;
        
        banner.style.backgroundColor = aqiDetails.hex;
        banner.style.display = 'block';
    },

    closeNotification() {
        document.getElementById('notification-banner').style.display = 'none';
    },

    // --- DATA RENDERING ---
    async renderMap() {
        const mapContainer = document.getElementById('map-points-container');
        mapContainer.innerHTML = ''; 

        // In a real app, you might fetch a list of all stations from your API
        // For the demo, we'll just use the keys from the API response for one city
        // to get the structure. A better way would be a dedicated `/api/cities` endpoint.
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
            point.style.left = `${(data.x / 1024) * 100}%`;
            point.style.top = `${(data.y / 768) * 100}%`;
            point.style.backgroundColor = aqiDetails.hex;
            // Custom property for the glow effect
            point.style.setProperty('--color', aqiDetails.hex);
            point.title = `${this.capitalize(city)} - AQI: ${data.aqi}`;
            point.onclick = () => {
                document.getElementById('search-input').value = city;
                this.handleSearch();
            };
            mapContainer.appendChild(point);
        }
    },
            
    updateDashboard(city, data) {
        document.getElementById('location-name').textContent = this.capitalize(city);
        
        const aqiDetails = this.getAQIDetails(data.aqi);

        document.getElementById('aqi-pill-container').innerHTML = `<div class="aqi-pill font-display" style="background-color: ${aqiDetails.hex};">${data.aqi}</div>`;
        document.getElementById('aqi-category').textContent = aqiDetails.category;
        document.getElementById('aqi-category').style.color = aqiDetails.hex;
        
        document.getElementById('tab-content-health').innerHTML = `<p class="font-semibold mb-2">${aqiDetails.healthImplications}</p><p>${aqiDetails.cautionaryStatement}</p>`;
        
        const pollutants = data.pollutants;
        document.getElementById('tab-content-pollutants').innerHTML = `
            <div class="flex justify-between text-sm"><span class="font-medium text-gray-400">Ozone (O₃)</span><span>${pollutants.o3} µg/m³</span></div>
            <div class="flex justify-between text-sm"><span class="font-medium text-gray-400">Nitrogen Dioxide (NO₂)</span><span>${pollutants.no2} µg/m³</span></div>
            <div class="flex justify-between text-sm"><span class="font-medium text-gray-400">Formaldehyde (HCHO)</span><span>${pollutants.hcho} µg/m³</span></div>
            <div class="flex justify-between text-sm"><span class="font-medium text-gray-400">Sulphur Dioxide (SO₂)</span><span>${pollutants.so2} µg/m³</span></div>
            <div class="flex justify-between text-sm"><span class="font-medium text-gray-400">Fine Particulates (PM2.5)</span><span>${pollutants.pm25} µg/m³</span></div>
        `;

        const weatherData = data.weather;
        document.getElementById('tab-content-weather').innerHTML = `
            <div class="flex justify-between items-center text-sm"><span class="font-medium text-gray-400">Temperature</span><span>${weatherData.icon} ${weatherData.temp}°F</span></div>
            <div class="flex justify-between items-center text-sm"><span class="font-medium text-gray-400">Humidity</span><span>${weatherData.humidity}%</span></div>
            <div class="flex justify-between items-center text-sm"><span class="font-medium text-gray-400">Wind</span><span>${weatherData.wind}</span></div>
        `;
        
        this.generateForecast(data.aqi);
        this.showTab('health');

        if (data.aqi > 100) {
            this.showNotification(data.aqi, city);
        } else {
            this.closeNotification();
        }
    },

    generateForecast(currentAqi) {
        const forecastContainer = document.getElementById('forecast-container');
        forecastContainer.innerHTML = '';
        const days = ['Tomorrow', '+2 Days', '+3 Days'];
        
        for (let i = 0; i < 3; i++) {
            const variance = Math.floor(Math.random() * 31) - 15;
            let forecastAqi = Math.max(0, currentAqi + variance * (i + 0.5));
            forecastAqi = Math.round(forecastAqi);
            const aqiDetails = this.getAQIDetails(forecastAqi);

            const forecastItem = document.createElement('div');
            forecastItem.className = 'p-2 rounded-lg';
            forecastItem.style.backgroundColor = `${aqiDetails.hex}20`; // very subtle bg color
            forecastItem.innerHTML = `
                <p class="font-semibold text-xs text-gray-400">${days[i]}</p>
                <p class="font-bold text-lg font-display" style="color: ${aqiDetails.hex};">${forecastAqi}</p>
                <p class="text-xs text-gray-500">${aqiDetails.category}</p>
            `;
            forecastContainer.appendChild(forecastItem);
        }
    },
            
    // --- UTILITY FUNCTIONS ---
    getAQIDetails(aqi) {
        if (aqi <= 50) return { category: 'Good', hex: '#00e400', healthImplications: 'Air quality is satisfactory. Air pollution poses little or no risk.', cautionaryStatement: 'None.' };
        if (aqi <= 100) return { category: 'Moderate', hex: '#ffff00', healthImplications: 'Acceptable air quality. Health concern for a small number of unusually sensitive people.', cautionaryStatement: 'Sensitive groups should limit prolonged outdoor exertion.' };
        if (aqi <= 150) return { category: 'Unhealthy for Sensitive Groups', hex: '#ff7e00', healthImplications: 'Members of sensitive groups may experience health effects. The general public is not likely to be affected.', cautionaryStatement: 'Limit prolonged outdoor exertion.' };
        if (aqi <= 200) return { category: 'Unhealthy', hex: '#ff0000', healthImplications: 'Everyone may begin to experience health effects.', cautionaryStatement: 'Avoid prolonged outdoor exertion; sensitive groups should avoid all outdoor exertion.' };
        if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#8f3f97', healthImplications: 'Health warnings of emergency conditions for the entire population.', cautionaryStatement: 'Limit outdoor exertion; sensitive groups should remain indoors.' };
        return { category: 'Hazardous', hex: '#7e0023', healthImplications: 'Health alert: everyone may experience more serious health effects.', cautionaryStatement: 'Everyone should avoid all outdoor exertion.' };
    },
            
    capitalize(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }
};

// Initialize the application once the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    AeroForecastApp.init();
});
