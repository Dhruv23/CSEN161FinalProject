// explore.js
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('planet-grid');
    const typeCheckboxes = document.querySelectorAll('input[name="type"]');
    const atmosphereSelect = document.getElementById('atmosphere-select');
    const distanceSlider = document.getElementById('distance');
    const distanceVal = document.getElementById('distance-val');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');

    // Update distance label
    distanceSlider.addEventListener('input', (e) => {
        distanceVal.textContent = e.target.value + ' AU';
    });

    // Fetch and render data
    async function fetchPlanets() {
        // Gather filter values
        const selectedTypes = Array.from(typeCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value)
            .join(',');

        const params = new URLSearchParams({
            types: selectedTypes,
            atmosphere: atmosphereSelect.value,
            max_distance: distanceSlider.value,
            search: searchInput.value
        });

        try {
            const response = await fetch(`explore.php?${params.toString()}`);
            const data = await response.json();

            // Clear current grid
            grid.innerHTML = '';

            if (data.length === 0) {
                grid.innerHTML = '<p>No celestial bodies found.</p>';
                return;
            }

            // Render new cards
            data.forEach(planet => {
                const card = document.createElement('div');
                card.className = 'planet-card';
                card.innerHTML = `
                    <div class="planet-icon"></div>
                    <h3>${planet.name}</h3>
                    <p style="color: #888; font-size: 0.9em;">${planet.type}</p>
                    <p style="font-size: 0.8em; margin-top: 10px;">Dist: ${planet.distance_from_sun} AU</p>
                `;
                grid.appendChild(card);
            });
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    // Attach event listeners to trigger fetch automatically
    typeCheckboxes.forEach(cb => cb.addEventListener('change', fetchPlanets));
    atmosphereSelect.addEventListener('change', fetchPlanets);
    distanceSlider.addEventListener('change', fetchPlanets);
    searchBtn.addEventListener('click', fetchPlanets);
    searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') fetchPlanets();
    });

    // Initial load
    fetchPlanets();
});