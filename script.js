// --- FRONTEND JAVASCRIPT LOGIC (script.js) ---

const AirsightApp = {
    map: null,
    tempMarker: null,
    currentData: null, // Stores the latest API response

    init() {
        this.initializeMap();
        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') this.handleSearch();
        });
        this.renderAqiGuidance();
        this.setupToggleButtons();
        // CHANGED: Default search location is now Houston, Texas
        this.searchForLocation({ city: "Houston, Texas" });
    },

    setupToggleButtons() {
        const toggleButtons = document.querySelectorAll('.toggle-switch .toggle-btn');
        // Daily button
        toggleButtons[0].addEventListener('click', () => {
            toggleButtons[0].classList.add('active');
            toggleButtons[1].classList.remove('active');
            this.renderDailyForecast();
        });
        // Hourly button
        toggleButtons[1].addEventListener('click', () => {
            toggleButtons[1].classList.add('active');
            toggleButtons[0].classList.remove('active');
            this.renderHourlyForecast();
        });
    },

    initializeMap() {
        // CHANGED: Initial map view is now centered on Houston, Texas
        this.map = L.map('map-container').setView([29.76, -95.37], 7);
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

    async handleSearch() {
        const query = document.getElementById('search-input').value.trim();
        if (!query) return;
        this.searchForLocation({ city: query });
    },

    async searchForLocation(location) {
        document.getElementById('initial-message').classList.add('hidden');
        document.getElementById('results-container').classList.add('hidden');
        document.getElementById('loader').classList.remove('hidden');

        const locationData = await this.fetchLocationData(location);

        document.getElementById('loader').classList.add('hidden');

        if (locationData) {
            document.getElementById('results-container').classList.remove('hidden');
            const displayName = location.name || this.capitalize(location.city);
            this.updateDashboard(displayName, locationData);

            if (this.map && locationData.lat && locationData.lon) {
                this.map.flyTo([locationData.lat, locationData.lon], 8);
                if (this.tempMarker) {
                    this.tempMarker.setLatLng([locationData.lat, locationData.lon]);
                } else {
                    this.tempMarker = L.marker([locationData.lat, locationData.lon]).addTo(this.map);
                }
                this.tempMarker.bindPopup(`<b>${displayName}</b><br>AQI: ${locationData.aqi}`).openPopup();
            }
        } else {
            alert('Location not found. Please try another city.');
            if (this.tempMarker) { this.map.removeLayer(this.tempMarker); }
            this.tempMarker = null;
            document.getElementById('results-container').classList.add('hidden');
            document.getElementById('initial-message').classList.remove('hidden');
        }
    },

    async fetchLocationData(location) {
        let apiUrl = 'api.php?';
        if (location.lat && location.lon) {
            apiUrl += `lat=${location.lat}&lon=${location.lon}`;
        } else if (location.city) {
            apiUrl += `city=${encodeURIComponent(location.city)}`;
        } else { return null; }

        try {
            const response = await fetch(apiUrl);
            if (!response.ok) return null;
            return await response.json();
        } catch (error) {
            console.error("Failed to fetch data:", error);
            return null;
        }
    },

    updateDashboard(name, data) {
        this.currentData = data; // Store the full data object
        const aqiDetails = this.getAQIDetails(data.aqi);
        const badge = document.getElementById('current-aqi-badge');
        badge.textContent = `AQI ${data.aqi}`;
        badge.style.backgroundColor = `${aqiDetails.hex}30`;
        badge.style.color = aqiDetails.hex;
        this.renderCurrentPollutants(data.pollutants);
        this.renderDailyForecast(); // Render daily forecast by default
    },

    renderCurrentPollutants(pollutants) {
        const container = document.getElementById('current-pollutants'); container.innerHTML = '';
        const poll_data = [{ n: 'PM2.5', v: pollutants.pm25.toFixed(2) }, { n: 'Ozone (O₃)', v: pollutants.o3.toFixed(2) }, { n: 'NO₂', v: pollutants.no2.toFixed(2) }];
        poll_data.forEach(p => { container.innerHTML += `<div class="current-pollutant text-center"><div class="value">${p.v}</div><div class="unit text-xs">${p.n}</div></div>`; });
    },

    renderDailyForecast() {
        if (!this.currentData) return;
        const list = document.getElementById('forecast-list');
        list.innerHTML = '';
        this.currentData.forecast.slice(0, 3).forEach(item => {
            const aqiDetails = this.getAQIDetails(item.aqi);
            const barWidth = Math.min(100, (item.aqi / 300) * 100);
            list.innerHTML += `<li class="forecast-item"><span class="day">${item.day}</span><div class="aqi-trend"><div class="aqi-bar-container"><div class="aqi-bar" style="width: ${barWidth}%; background-color: ${aqiDetails.hex};"></div></div><span class="font-bold w-8 text-right" style="color: ${aqiDetails.hex};">${item.aqi}</span></div></li>`;
        });
    },

    renderHourlyForecast() {
        if (!this.currentData) return;
        const list = document.getElementById('forecast-list');
        list.innerHTML = '';
        this.currentData.hourly_forecast.slice(0, 5).forEach(item => {
            const aqiDetails = this.getAQIDetails(item.aqi);
            const barWidth = Math.min(100, (item.aqi / 300) * 100);

            let hour = item.hour % 12;
            if (hour === 0) hour = 12;
            const ampm = item.hour >= 12 ? 'PM' : 'AM';
            const displayTime = `${hour} ${ampm}`;

            list.innerHTML += `<li class="forecast-item"><span class="day">${displayTime}</span><div class="aqi-trend"><div class="aqi-bar-container"><div class="aqi-bar" style="width: ${barWidth}%; background-color: ${aqiDetails.hex};"></div></div><span class="font-bold w-8 text-right" style="color: ${aqiDetails.hex};">${item.aqi}</span></div></li>`;
        });
    },

    renderAqiGuidance() {
        const container = document.getElementById('aqi-guidance');
        const levels = [this.getAQIDetails(25), this.getAQIDetails(75), this.getAQIDetails(125), this.getAQIDetails(175)];
        container.innerHTML = ''; levels.forEach(level => { container.innerHTML += `<div><p class="font-semibold" style="color: ${level.hex};">${level.category}</p><p class="text-gray-600">${level.healthImplications}</p></div>`; });
    },

    getAQIDetails(aqi) {
        if (aqi <= 50) return { category: 'Good', hex: '#22c55e', healthImplications: 'Air quality is satisfactory...' };
        if (aqi <= 100) return { category: 'Moderate', hex: '#facc15', healthImplications: 'Unusually sensitive individuals...' };
        if (aqi <= 150) return { category: 'Unhealthy for Sensitive', hex: '#f97316', healthImplications: 'Sensitive groups should reduce...' };
        if (aqi <= 200) return { category: 'Unhealthy', hex: '#ef4444', healthImplications: 'Everyone should reduce...' };
        if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#a855f7', healthImplications: 'Everyone should avoid...' };
        return { category: 'Hazardous', hex: '#be123c', healthImplications: 'Remain indoors...' };
    },

    capitalize(str) { return str.replace(/\b\w/g, char => char.toUpperCase()); }
};

document.addEventListener('DOMContentLoaded', () => AirsightApp.init());