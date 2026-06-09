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

    // Additional planet data
    const planetExtraData = {

        mercury: {
            image: "images/planets/mercury.jpg",
            gallery: [
                "images/gallery/mercury1.jpg",
                "images/gallery/mercury2.jpg",
                "images/gallery/mercury3.jpg"
            ],
            moons: [
                "Mercury has no natural moons."
            ],
            facts: [
                "Diameter: 4,879 km",
                "Mass: 3.30 × 10²³ kg",
                "Gravity: 3.7 m/s²",
                "Length of Day: 59 Earth days",
                "Length of Year: 88 Earth days",
                "Average Temperature: 167°C"
            ]
        },

        venus: {
            image: "images/planets/venus.jpg",
            gallery: [
                "images/gallery/venus1.jpg",
                "images/gallery/venus2.jpg",
                "images/gallery/venus3.jpg"
            ],
            moons: [
                "Venus has no natural moons."
            ],
            facts: [
                "Diameter: 12,104 km",
                "Mass: 4.87 × 10²⁴ kg",
                "Gravity: 8.87 m/s²",
                "Length of Day: 243 Earth days",
                "Length of Year: 225 Earth days",
                "Average Temperature: 465°C"
            ]
        },

        earth: {
            image: "images/planets/earth.jpg",
            gallery: [
                "images/gallery/earth1.jpg",
                "images/gallery/earth2.jpg",
                "images/gallery/earth3.jpg"
            ],
            moons: [
                "Moon"
            ],
            facts: [
                "Diameter: 12,742 km",
                "Mass: 5.97 × 10²⁴ kg",
                "Gravity: 9.81 m/s²",
                "Length of Day: 24 Hours",
                "Length of Year: 365.25 Days",
                "Surface Water Coverage: 71%"
            ]
        },

        pluto: {
            image: "images/planets/pluto.jpg",
            gallery: [
                "images/gallery/pluto1.jpg",
                "images/gallery/pluto2.jpg",
                "images/gallery/pluto3.jpg"
            ],
            moons: [
                "Charon",
                "Nix",
                "Hydra",
                "Kerberos",
                "Styx"
            ],
            facts: [
                "Diameter: 2,377 km",
                "Mass: 1.31 × 10²² kg",
                "Gravity: 0.62 m/s²",
                "Length of Day: 6.4 Earth Days",
                "Length of Year: 248 Earth Years",
                "Average Temperature: -229°C"
            ]
        },

        europa: {
            image: "images/planets/europa.jpg",
            gallery: [
                "images/gallery/europa1.jpg",
                "images/gallery/europa2.jpg",
                "images/gallery/europa3.jpg"
            ],
            moons: [
                "Europa is itself a moon of Jupiter."
            ],
            facts: [
                "Parent Planet: Jupiter",
                "Diameter: 3,121 km",
                "Surface: Water Ice",
                "Likely Contains a Subsurface Ocean",
                "Orbital Period: 3.55 Days",
                "Average Temperature: -160°C"
            ]
        }
    };

    // Get planet ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const planetId = urlParams.get('id') || 3;

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
                `${planet.name} is classified as a ${planet.type} located ${planet.distance_from_sun} AU from the Sun.`;

            document.getElementById('atmosphere-text').textContent =
                `The primary atmospheric composition consists of: ${planet.atmosphere}.`;

            const extra = planetExtraData[planet.name.toLowerCase()];

            if (extra) {

                // Main Planet Image
                const planetImage = document.getElementById('planet-image');

                if (planetImage) {
                    planetImage.src = extra.image;
                    planetImage.alt = planet.name;
                }

                // Facts Tab
                const factsList = document.getElementById('facts-list');

                if (factsList) {
                    factsList.innerHTML = extra.facts
                        .map(fact => `<li>${fact}</li>`)
                        .join('');
                }

                // Moon Tab
                const moonList = document.getElementById('moon-list');

                if (moonList) {
                    moonList.innerHTML = extra.moons.length
                        ? extra.moons.map(moon => `<li>${moon}</li>`).join('')
                        : '<li>No moons recorded.</li>';
                }

                // Image Gallery
                const gallery = document.getElementById('planet-gallery');

                if (gallery) {
                    gallery.innerHTML = extra.gallery
                        .map(img =>
                            `<img src="${img}" class="gallery-photo" alt="${planet.name}">`
                        )
                        .join('');
                }
            }

        } else {

            document.getElementById('planet-name').textContent = 'Planet Not Found';

        }

    } catch (error) {

        console.error('Error fetching planet details:', error);

    }

});