document.addEventListener('DOMContentLoaded', async () => {
    // Tab Logic
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');
            document.getElementById(btn.dataset.tab).classList.add('active');
        });
    });

    // Fetch Planet Data based on URL ID
    const urlParams = new URLSearchParams(window.location.search);
    const planetId = urlParams.get('id') || 3; // Default to Earth (id 3 in your DB)

    try {
        const response = await fetch(`planet.php?id=${planetId}`);
        const planet = await response.json();

        if (planet && !planet.error) {
            document.getElementById('planet-name').textContent = planet.name;

            document.getElementById('quick-stats').innerHTML = `
                <p><strong>Type:</strong> ${planet.type}</p>
                <p><strong>Distance from Sun:</strong> ${planet.distance_from_sun} AU</p>
                <p><strong>Atmosphere:</strong> ${planet.atmosphere}</p>
            `;

            document.getElementById('overview-text').textContent =
                `${planet.name} is classified as a ${planet.type} located ${planet.distance_from_sun} AU from the sun.`;

            document.getElementById('atmosphere-text').textContent =
                `The primary atmospheric composition consists of: ${planet.atmosphere}.`;

            document.getElementById('facts-list').innerHTML = `
                <li>Classification: ${planet.type.toUpperCase()}</li>
                <li>Orbital Distance: ${planet.distance_from_sun} Astronomical Units</li>
            `;

            // Change sphere color based on planet for visual flair
            const sphere = document.getElementById('planet-sphere');
            if (planet.name.toLowerCase() === 'mars') sphere.style.background = 'radial-gradient(circle at 30% 30%, #c1440e, #e77d11)';
            if (planet.name.toLowerCase() === 'venus') sphere.style.background = 'radial-gradient(circle at 30% 30%, #e8c170, #8b6914)';
            if (planet.name.toLowerCase() === 'mercury') sphere.style.background = 'radial-gradient(circle at 30% 30%, #888, #333)';
        } else {
            document.getElementById('planet-name').textContent = "Not Found";
        }
    } catch (error) {
        console.error('Error fetching planet details:', error);
    }
});