// --- FRONTEND JAVASCRIPT LOGIC (script.js) - Global Heatmap Version ---

const AirsightApp = {
    map: null,
    tempMarker: null,
    heatLayer: null,

    init() {
        this.initializeMap(); 
        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') this.handleSearch();
        });
        this.renderAqiGuidance();
        this.renderHeatmap(); 
        this.searchForLocation({ city: "New Delhi" }); // Default to a new location
    },

    initializeMap() {
        // Center the map to show more of the world
        this.map = L.map('map-container').setView([20, 0], 2); 
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(this.map);

        this.map.on('click', (e) => {
            const { lat, lng } = e.latlng;
            if (this.tempMarker) {
                this.tempMarker.setLatLng(e.latlng);
            } else {
                this.tempMarker = L.marker(e.latlng).addTo(this.map);
            }
            this.tempMarker.bindPopup(`Fetching data...`).openPopup();
            this.searchForLocation({ lat: lat, lon: lng, name: "Clicked Location" });
        });
    },

    renderHeatmap() {
        if (!this.map) return;
        
        // *** EXPANDED: Global list of cities for the heatmap ***
        const heatPoints = [
            // North America
            [40.71, -74.00, 45],  // New York
            [34.05, -118.24, 88], // Los Angeles
            [19.43, -99.13, 155], // Mexico City
            [43.65, -79.38, 55],  // Toronto

            // South America
            [-23.55, -46.63, 75], // São Paulo, Brazil
            [-34.60, -58.38, 60], // Buenos Aires, Argentina

            // Europe
            [51.50, -0.12, 40],  // London, UK
            [48.85, 2.35, 50],   // Paris, France
            [55.75, 37.61, 68],  // Moscow, Russia

            // Asia
            [35.68, 139.69, 55], // Tokyo, Japan
            [28.61, 77.20, 180], // New Delhi, India
            [39.90, 116.40, 160], // Beijing, China
            [25.03, 121.56, 45], // Taipei, Taiwan

            // Africa
            [30.04, 31.23, 110], // Cairo, Egypt
            [-26.20, 28.04, 80], // Johannesburg, South Africa

            // Australia
            [-33.86, 151.20, 35]  // Sydney, Australia
        ];

        this.heatLayer = L.heatLayer(heatPoints, {
            radius: 20,
            blur: 30, // Increased blur for a smoother global look
            maxZoom: 10,
            max: 200, 
            gradient: { 0.25: 'green', 0.5: 'yellow', 0.75: 'orange', 1.0: 'red' }
        }).addTo(this.map);
    },

    // --- The rest of the script remains unchanged ---
    async handleSearch() { /* ... no changes ... */ },
    async searchForLocation(location) { /* ... no changes ... */ },
    async fetchLocationData(location) { /* ... no changes ... */ },
    updateDashboard(name, data) { /* ... no changes ... */ },
    renderCurrentPollutants(pollutants) { /* ... no changes ... */ },
    renderForecastList(forecast) { /* ... no changes ... */ },
    renderAqiGuidance() { /* ... no changes ... */ },
    getAQIDetails(aqi) { /* ... no changes ... */ },
    capitalize(str) { /* ... no changes ... */ }
};

// Paste the full, unchanged functions here from your previous script
AirsightApp.handleSearch = function() { const query = document.getElementById('search-input').value.trim(); if (!query) return; this.searchForLocation({ city: query }); };
AirsightApp.searchForLocation = function(location) {
    document.getElementById('initial-message').classList.add('hidden'); document.getElementById('results-container').classList.add('hidden'); document.getElementById('loader').classList.remove('hidden');
    this.fetchLocationData(location).then(locationData => {
        document.getElementById('loader').classList.add('hidden');
        if (locationData) {
            document.getElementById('results-container').classList.remove('hidden');
            const displayName = location.name || this.capitalize(location.city);
            this.updateDashboard(displayName, locationData);
            if (this.map && locationData.lat && locationData.lon) {
                this.map.flyTo([locationData.lat, locationData.lon], 8);
                if (this.tempMarker) {
                    this.tempMarker.setLatLng([locationData.lat, locationData.lon]);
                    this.tempMarker.bindPopup(`<b>${displayName}</b><br>AQI: ${locationData.aqi}`).openPopup();
                }
            }
        } else {
            alert('Location not found. Please try another city.');
            if (this.tempMarker) { this.map.removeLayer(this.tempMarker); } this.tempMarker = null;
            document.getElementById('results-container').classList.add('hidden'); document.getElementById('initial-message').classList.remove('hidden');
        }
    });
};
AirsightApp.fetchLocationData = async function(location) {
    let apiUrl = 'api.php?';
    if (location.lat && location.lon) { apiUrl += `lat=${location.lat}&lon=${location.lon}`; } 
    else if (location.city) { apiUrl += `city=${encodeURIComponent(location.city)}`; } 
    else { return null; }
    try { const response = await fetch(apiUrl); if (!response.ok) return null; return await response.json(); } 
    catch (error) { console.error("Failed to fetch data:", error); return null; }
};
AirsightApp.updateDashboard = function(name, data) {
    const aqiDetails = this.getAQIDetails(data.aqI);
    const badge = document.getElementById('current-aqi-badge');
    badge.textContent = `AQI ${data.aqi}`;
    badge.style.backgroundColor = `${aqiDetails.hex}30`;
    badge.style.color = aqiDetails.hex;
    this.renderCurrentPollutants(data.pollutants);
    this.renderForecastList(data.forecast);
};
AirsightApp.renderCurrentPollutants = function(pollutants) {
    const container = document.getElementById('current-pollutants'); container.innerHTML = '';
    const poll_data = [{ n: 'PM2.5', v: pollutants.pm25.toFixed(2) }, { n: 'Ozone (O₃)', v: pollutants.o3.toFixed(2) }, { n: 'NO₂', v: pollutants.no2.toFixed(2) }];
    poll_data.forEach(p => { container.innerHTML += `<div class="current-pollutant text-center"><div class="value">${p.v}</div><div class="unit text-xs">${p.n}</div></div>`; });
};
AirsightApp.renderForecastList = function(forecast) {
    const list = document.getElementById('forecast-list'); list.innerHTML = '';
    forecast.slice(0, 3).forEach(item => {
        const aqiDetails = this.getAQIDetails(item.aqi); const barWidth = Math.min(100, (item.aqi / 300) * 100);
        list.innerHTML += `<li class="forecast-item"><span class="day">${item.day}</span><div class="aqi-trend"><div class="aqi-bar-container"><div class="aqi-bar" style="width: ${barWidth}%; background-color: ${aqiDetails.hex};"></div></div><span class="font-bold w-8 text-right" style="color: ${aqiDetails.hex};">${item.aqi}</span></div></li>`;
    });
};
AirsightApp.renderAqiGuidance = function() {
    const container = document.getElementById('aqi-guidance');
    const levels = [this.getAQIDetails(25), this.getAQIDetails(75), this.getAQIDetails(125), this.getAQIDetails(175)];
    container.innerHTML = ''; levels.forEach(level => { container.innerHTML += `<div><p class="font-semibold" style="color: ${level.hex};">${level.category}</p><p class="text-gray-600">${level.healthImplications}</p></div>`; });
};
AirsightApp.getAQIDetails = function(aqi) {
    if (aqi <= 50) return { category: 'Good', hex: '#22c55e', healthImplications: 'Air quality is satisfactory...' };
    if (aqi <= 100) return { category: 'Moderate', hex: '#facc15', healthImplications: 'Unusually sensitive individuals...' };
    if (aqi <= 150) return { category: 'Unhealthy for Sensitive', hex: '#f97316', healthImplications: 'Sensitive groups should reduce...' };
    if (aqi <= 200) return { category: 'Unhealthy', hex: '#ef4444', healthImplications: 'Everyone should reduce...' };
    if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#a855f7', healthImplications: 'Everyone should avoid...' };
    return { category: 'Hazardous', hex: '#be123c', healthImplications: 'Remain indoors...' };
};
AirsightApp.capitalize = function(str) { return str.replace(/\b\w/g, char => char.toUpperCase()); };

document.addEventListener('DOMContentLoaded', () => AirsightApp.init());