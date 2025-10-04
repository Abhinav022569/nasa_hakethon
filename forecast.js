// --- FORECAST PAGE JAVASCRIPT LOGIC (forecast.js) ---

const ForecastApp = {
    dailyChart: null,
    hourlyChart: null,

    init() {
        document.getElementById('search-input').addEventListener('keyup', (event) => {
            if (event.key === 'Enter') {
                const query = event.target.value.trim();
                if (query) this.fetchAndRenderData({ city: query });
            }
        });
        // Initial load with a default city
        this.fetchAndRenderData({ city: "Houston, Texas" });
    },

    async fetchAndRenderData(location) {
        document.getElementById('loader').classList.remove('hidden');
        document.getElementById('forecast-content').classList.add('hidden');

        const data = await this.fetchApiData(location);

        document.getElementById('loader').classList.add('hidden');
        document.getElementById('forecast-content').classList.remove('hidden');

        if (data) {
            const displayName = this.capitalize(location.city || `Location (${data.lat.toFixed(2)}, ${data.lon.toFixed(2)})`);
            document.getElementById('location-name').textContent = displayName;

            this.renderDailyChart(data.forecast);
            this.renderHourlyChart(data.hourly_forecast);
            this.renderHealthGuidance(data.aqi);
        } else {
            alert('Location not found. Please try another search.');
            document.getElementById('location-name').textContent = "Not Found";
        }
    },

    async fetchApiData(location) {
        let apiUrl = `api.php?city=${encodeURIComponent(location.city)}`;
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) return null;
            return await response.json();
        } catch (error) {
            console.error("Failed to fetch API data:", error);
            return null;
        }
    },

    renderDailyChart(forecastData) {
        const ctx = document.getElementById('daily-forecast-chart').getContext('2d');
        const labels = forecastData.map(d => d.day.substring(0, 3)); // Mon, Tue, etc.
        const data = forecastData.map(d => d.aqi);
        const pointColors = data.map(aqi => this.getAQIDetails(aqi).hex);

        if (this.dailyChart) this.dailyChart.destroy();

        this.dailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Avg. AQI',
                    data: data,
                    borderColor: '#0891b2',
                    backgroundColor: pointColors,
                    tension: 0.3,
                    fill: false,
                }]
            },
            options: this.getChartOptions('7-Day Average AQI')
        });
    },

    renderHourlyChart(hourlyData) {
        const ctx = document.getElementById('hourly-forecast-chart').getContext('2d');
        const labels = hourlyData.map(h => h.hour + ':00');
        const data = hourlyData.map(h => h.aqi);
        const pointColors = data.map(aqi => this.getAQIDetails(aqi).hex);

        if (this.hourlyChart) this.hourlyChart.destroy();

        this.hourlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Hourly AQI',
                    data: data,
                    backgroundColor: pointColors,
                    borderColor: pointColors,
                    borderWidth: 1
                }]
            },
            options: this.getChartOptions('AQI Forecast for the Next 48 Hours')
        });
    },

    renderHealthGuidance(aqi) {
        const container = document.getElementById('guidance-content');
        const level = this.getAQIDetails(aqi);

        container.innerHTML = `
            <div class="guidance-section">
                <p class="guidance-title">General Population</p>
                <p class="guidance-text">${level.healthImplications}</p>
            </div>
            <div class="guidance-section">
                <p class="guidance-title">Sensitive Groups</p>
                <p class="guidance-text">${this.getSensitiveGroupGuidance(aqi)}</p>
            </div>
             <div class="guidance-section">
                <p class="guidance-title">Current AQI</p>
                <p class="guidance-text font-bold" style="color: ${level.hex};">${aqi} - ${level.category}</p>
            </div>
        `;
    },

    getChartOptions(title) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: { display: false, text: title }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#e5e7eb' } },
                x: { grid: { display: false } }
            }
        };
    },

    getAQIDetails(aqi) {
        if (aqi <= 50) return { category: 'Good', hex: '#22c55e', healthImplications: 'Air quality is excellent. No health risks.' };
        if (aqi <= 100) return { category: 'Moderate', hex: '#facc15', healthImplications: 'Air quality is acceptable. Some may experience minor symptoms.' };
        if (aqi <= 150) return { category: 'Unhealthy for Sensitive', hex: '#f97316', healthImplications: 'General public not likely to be affected.' };
        if (aqi <= 200) return { category: 'Unhealthy', hex: '#ef4444', healthImplications: 'Everyone may begin to experience health effects.' };
        if (aqi <= 300) return { category: 'Very Unhealthy', hex: '#a855f7', healthImplications: 'Health alert: everyone may experience more serious health effects.' };
        return { category: 'Hazardous', hex: '#be123c', healthImplications: 'Health warnings of emergency conditions. The entire population is more than likely to be affected.' };
    },

    getSensitiveGroupGuidance(aqi) {
        if (aqi <= 50) return 'No restrictions for sensitive groups.';
        if (aqi <= 100) return 'Consider reducing prolonged or heavy exertion outdoors.';
        if (aqi <= 150) return 'Reduce prolonged or heavy exertion. Take more breaks during all outdoor activities.';
        if (aqi <= 200) return 'Avoid prolonged or heavy exertion. Consider moving activities indoors or rescheduling.';
        if (aqi <= 300) return 'Avoid all physical activity outdoors. Move activities indoors or reschedule to another time.';
        return 'Remain indoors and keep activity levels low. Follow tips for keeping particle levels low indoors.';
    },

    capitalize(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }
};

document.addEventListener('DOMContentLoaded', () => ForecastApp.init());