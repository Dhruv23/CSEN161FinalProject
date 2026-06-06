<?php
// setup_db_quiz.php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/cosmoguide.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the table
    $db->exec("CREATE TABLE IF NOT EXISTS quiz_questions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT,
        question TEXT,
        option_0 TEXT,
        option_1 TEXT,
        option_2 TEXT,
        option_3 TEXT,
        correct_index INTEGER
    )");

    // Clear existing questions to avoid duplicates on reload
    $db->exec("DELETE FROM quiz_questions");

    // 100 Questions Array: [Category, Question, Opt0, Opt1, Opt2, Opt3, CorrectIndex]
    $questions = [
        // --- General Astronomy (25) ---
        ['General Astronomy', 'What is the closest star system to our Solar System?', 'Sirius', 'Alpha Centauri', 'Betelgeuse', 'Vega', 1],
        ['General Astronomy', 'What is a light-year?', 'Time to travel to the sun', 'Distance light travels in a year', 'Speed of light', 'A year with more sunlight', 1],
        ['General Astronomy', 'What is the most common type of star in the Milky Way?', 'Red Dwarf', 'Blue Giant', 'Yellow Dwarf', 'White Dwarf', 0],
        ['General Astronomy', 'What galaxy is on a collision course with the Milky Way?', 'Triangulum', 'Andromeda', 'Sombrero', 'Whirlpool', 1],
        ['General Astronomy', 'What is the boundary around a black hole called?', 'Event Horizon', 'Singularity', 'Photon Sphere', 'Accretion Disk', 0],
        ['General Astronomy', 'What are comets mostly made of?', 'Rock and iron', 'Ice and dust', 'Liquid methane', 'Plasma', 1],
        ['General Astronomy', 'What process powers the Sun?', 'Nuclear Fission', 'Chemical Burning', 'Nuclear Fusion', 'Geothermal Energy', 2],
        ['General Astronomy', 'What is the term for a star that suddenly increases in brightness?', 'Pulsar', 'Quasar', 'Supernova', 'Magnetar', 2],
        ['General Astronomy', 'Which constellation contains the North Star (Polaris)?', 'Ursa Major', 'Orion', 'Cassiopeia', 'Ursa Minor', 3],
        ['General Astronomy', 'What is the brightest star in the night sky?', 'Sirius', 'Canopus', 'Rigel', 'Polaris', 0],
        ['General Astronomy', 'What shape is the Milky Way galaxy?', 'Elliptical', 'Irregular', 'Spiral', 'Lenticular', 2],
        ['General Astronomy', 'What causes a solar eclipse?', 'Earth blocks the sun', 'Moon blocks the sun', 'Sun blocks the moon', 'Planetary alignment', 1],
        ['General Astronomy', 'What is the estimated age of the universe?', '4.6 billion years', '13.8 billion years', '93 billion years', '1 trillion years', 1],
        ['General Astronomy', 'What invisible substance makes up most of the universe\'s mass?', 'Dark Matter', 'Antimatter', 'Neutrinos', 'Black Holes', 0],
        ['General Astronomy', 'What is the scientific term for shooting stars?', 'Asteroids', 'Meteorites', 'Meteors', 'Comets', 2],
        ['General Astronomy', 'What happens when a massive star collapses under its own gravity?', 'It forms a planet', 'It becomes a comet', 'It forms a black hole', 'It turns into a nebula', 2],
        ['General Astronomy', 'What device is used to measure light spectrums from stars?', 'Telescope', 'Spectrometer', 'Barometer', 'Photometer', 1],
        ['General Astronomy', 'What are exoplanets?', 'Planets without moons', 'Dwarf planets', 'Planets outside our solar system', 'Rogue planets', 2],
        ['General Astronomy', 'What is the name of the theory explaining the origin of the universe?', 'String Theory', 'Big Bang Theory', 'Multiverse Theory', 'Steady State Theory', 1],
        ['General Astronomy', 'What is a nebula?', 'A dying star', 'A giant cloud of dust and gas', 'A cluster of galaxies', 'A type of black hole', 1],
        ['General Astronomy', 'Which type of galaxy has no distinct shape?', 'Spiral', 'Elliptical', 'Irregular', 'Peculiar', 2],
        ['General Astronomy', 'What force holds galaxies together?', 'Electromagnetism', 'Strong Nuclear Force', 'Gravity', 'Weak Nuclear Force', 2],
        ['General Astronomy', 'What are pulsars?', 'Colliding galaxies', 'Rotating neutron stars', 'Active supermassive black holes', 'Dying red giants', 1],
        ['General Astronomy', 'What is the study of the universe as a whole called?', 'Astrology', 'Geology', 'Cosmology', 'Meteorology', 2],
        ['General Astronomy', 'Who first proposed that the Earth revolves around the Sun?', 'Galileo', 'Newton', 'Copernicus', 'Ptolemy', 2],

        // --- Moons (25) ---
        ['Moons', 'What is the largest moon in the Solar System?', 'Titan', 'Ganymede', 'Europa', 'Callisto', 1],
        ['Moons', 'Which planet has the most confirmed moons?', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 1],
        ['Moons', 'Which moon is known for its thick, Earth-like atmosphere?', 'Titan', 'Io', 'Triton', 'Europa', 0],
        ['Moons', 'Which moon is the most volcanically active body in the solar system?', 'Enceladus', 'Triton', 'Io', 'Phobos', 2],
        ['Moons', 'Which planet does the moon Europa belong to?', 'Saturn', 'Uranus', 'Neptune', 'Jupiter', 3],
        ['Moons', 'Which moon has an underground ocean that may harbor life?', 'Europa', 'Phobos', 'Deimos', 'Miranda', 0],
        ['Moons', 'What are the two moons of Mars?', 'Phobos & Deimos', 'Titan & Rhea', 'Europa & Io', 'Triton & Nereid', 0],
        ['Moons', 'Which moon orbits Neptune backwards (retrograde)?', 'Proteus', 'Triton', 'Naiad', 'Larissa', 1],
        ['Moons', 'What is Earth\'s moon\'s actual scientific name?', 'Luna', 'Selene', 'Artemis', 'The Moon', 0],
        ['Moons', 'Which of Saturn\'s moons shoots geysers of water ice into space?', 'Titan', 'Mimas', 'Enceladus', 'Iapetus', 2],
        ['Moons', 'Which moon looks like the "Death Star" from Star Wars?', 'Mimas', 'Tethys', 'Dione', 'Rhea', 0],
        ['Moons', 'What planet does the moon Charon orbit?', 'Pluto', 'Neptune', 'Mars', 'Uranus', 0],
        ['Moons', 'Who discovered the four largest moons of Jupiter?', 'Kepler', 'Galileo', 'Newton', 'Brahe', 1],
        ['Moons', 'What phase is the Moon during a solar eclipse?', 'Full Moon', 'First Quarter', 'New Moon', 'Last Quarter', 2],
        ['Moons', 'Which moon is larger than the planet Mercury?', 'Ganymede', 'Europa', 'Triton', 'Io', 0],
        ['Moons', 'What is the heavily cratered moon of Jupiter called?', 'Callisto', 'Io', 'Europa', 'Amalthea', 0],
        ['Moons', 'Which planet has moons named after Shakespearean characters?', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 2],
        ['Moons', 'How long does it take Earth\'s Moon to complete one orbit?', '24 hours', '27.3 days', '365 days', '31 days', 1],
        ['Moons', 'What is the dark side of the Moon actually called?', 'The Far Side', 'The Night Side', 'The Shadow Side', 'The Eclipse Side', 0],
        ['Moons', 'What are the dark plains on Earth\'s Moon called?', 'Oceans', 'Maria', 'Craters', 'Valles', 1],
        ['Moons', 'Which dwarf planet has a moon shaped like a potato?', 'Haumea', 'Makemake', 'Eris', 'Pluto', 0],
        ['Moons', 'What moon is the largest relative to the size of its host planet?', 'Charon', 'Earth\'s Moon', 'Titan', 'Triton', 0],
        ['Moons', 'Which space mission landed a probe on Titan?', 'Cassini-Huygens', 'Galileo', 'Juno', 'Voyager 1', 0],
        ['Moons', 'What causes the tides on Earth?', 'The Sun', 'Earth\'s rotation', 'The Moon\'s gravity', 'Ocean currents', 2],
        ['Moons', 'Which of Uranus\' moons has the tallest cliff in the solar system?', 'Ariel', 'Umbriel', 'Titania', 'Miranda', 3],

        // --- Space Missions (25) ---
        ['Space Missions', 'Which Apollo mission was the first to land on the Moon?', 'Apollo 8', 'Apollo 10', 'Apollo 11', 'Apollo 13', 2],
        ['Space Missions', 'What was the first artificial satellite sent into space?', 'Explorer 1', 'Sputnik 1', 'Vanguard 1', 'Telstar', 1],
        ['Space Missions', 'Which rover landed on Mars in 2021?', 'Curiosity', 'Opportunity', 'Spirit', 'Perseverance', 3],
        ['Space Missions', 'What is the name of the space telescope launched in 1990?', 'James Webb', 'Kepler', 'Hubble', 'Spitzer', 2],
        ['Space Missions', 'Which spacecraft is currently the farthest human-made object from Earth?', 'Voyager 1', 'Voyager 2', 'Pioneer 10', 'New Horizons', 0],
        ['Space Missions', 'What mission successfully studied Saturn for 13 years?', 'Juno', 'Galileo', 'Cassini', 'Magellan', 2],
        ['Space Missions', 'Who was the first human in space?', 'Neil Armstrong', 'Yuri Gagarin', 'Buzz Aldrin', 'John Glenn', 1],
        ['Space Missions', 'What was the name of the first Space Shuttle?', 'Challenger', 'Discovery', 'Enterprise', 'Columbia', 3],
        ['Space Missions', 'Which mission provided the first close-up images of Pluto?', 'Voyager 2', 'Cassini', 'New Horizons', 'Dawn', 2],
        ['Space Missions', 'What is the successor to the Hubble Space Telescope?', 'Chandra', 'James Webb', 'Kepler', 'Compton', 1],
        ['Space Missions', 'What was the first animal to orbit the Earth?', 'A monkey', 'A dog', 'A cat', 'A mouse', 1],
        ['Space Missions', 'Which spacecraft successfully mapped the surface of Venus?', 'Magellan', 'Mariner 2', 'Venera 9', 'Pioneer Venus', 0],
        ['Space Missions', 'What country launched the Shenzhou spacecraft?', 'Russia', 'Japan', 'China', 'India', 2],
        ['Space Missions', 'Which space station is currently orbiting Earth?', 'Mir', 'Skylab', 'Tiangong', 'International Space Station', 3],
        ['Space Missions', 'What company created the Falcon 9 rocket?', 'Blue Origin', 'Boeing', 'Lockheed Martin', 'SpaceX', 3],
        ['Space Missions', 'What mission successfully landed a probe on a comet?', 'Rosetta', 'Stardust', 'Deep Impact', 'Hayabusa', 0],
        ['Space Missions', 'Which Mars rover operated for over 14 years despite a 90-day planned mission?', 'Sojourner', 'Spirit', 'Opportunity', 'Curiosity', 2],
        ['Space Missions', 'Who was the first American woman in space?', 'Sally Ride', 'Mae Jemison', 'Peggy Whitson', 'Eileen Collins', 0],
        ['Space Missions', 'What was the name of the first module of the ISS launched?', 'Unity', 'Destiny', 'Zarya', 'Zvezda', 2],
        ['Space Missions', 'Which telescope discovered thousands of exoplanets?', 'Hubble', 'James Webb', 'Chandra', 'Kepler', 3],
        ['Space Missions', 'What mission was dubbed the "successful failure"?', 'Apollo 1', 'Apollo 11', 'Apollo 13', 'Gemini 8', 2],
        ['Space Missions', 'Which program preceded Apollo in the US?', 'Mercury', 'Gemini', 'Vostok', 'Skylab', 1],
        ['Space Missions', 'What probe took the famous "Pale Blue Dot" photo?', 'Cassini', 'Voyager 1', 'Pioneer 11', 'Galileo', 1],
        ['Space Missions', 'Which agency launched the Chandrayaan missions to the Moon?', 'NASA', 'ESA', 'Roscosmos', 'ISRO', 3],
        ['Space Missions', 'What was the first spacecraft to orbit Jupiter?', 'Juno', 'Galileo', 'Voyager 1', 'Ulysses', 1],

        // --- Solar System Basics (25) ---
        ['Solar System Basics', 'Which planet is closest to the Sun?', 'Venus', 'Mercury', 'Earth', 'Mars', 1],
        ['Solar System Basics', 'Which planet is known as the Red Planet?', 'Jupiter', 'Venus', 'Saturn', 'Mars', 3],
        ['Solar System Basics', 'What is the hottest planet in our solar system?', 'Mercury', 'Mars', 'Venus', 'Jupiter', 2],
        ['Solar System Basics', 'Which planet has the most prominent ring system?', 'Uranus', 'Neptune', 'Jupiter', 'Saturn', 3],
        ['Solar System Basics', 'What lies between Mars and Jupiter?', 'Oort Cloud', 'Kuiper Belt', 'Asteroid Belt', 'Heliosphere', 2],
        ['Solar System Basics', 'Which planet spins on its side?', 'Uranus', 'Neptune', 'Saturn', 'Venus', 0],
        ['Solar System Basics', 'What is the Great Red Spot on Jupiter?', 'A volcano', 'A massive storm', 'A crater', 'An ocean', 1],
        ['Solar System Basics', 'How many planets are in our solar system?', '7', '8', '9', '10', 1],
        ['Solar System Basics', 'Which planet is the smallest in the solar system?', 'Mars', 'Pluto', 'Mercury', 'Venus', 2],
        ['Solar System Basics', 'Which two planets have no moons?', 'Mercury and Venus', 'Venus and Mars', 'Mars and Jupiter', 'Uranus and Neptune', 0],
        ['Solar System Basics', 'What is the largest volcano in the solar system, located on Mars?', 'Mount Everest', 'Olympus Mons', 'Mauna Kea', 'Elysium Mons', 1],
        ['Solar System Basics', 'What gas makes up most of Earth\'s atmosphere?', 'Oxygen', 'Carbon Dioxide', 'Nitrogen', 'Hydrogen', 2],
        ['Solar System Basics', 'Which planet is famous for rotating backwards compared to most others?', 'Mars', 'Jupiter', 'Venus', 'Uranus', 2],
        ['Solar System Basics', 'What is the region of icy bodies beyond Neptune called?', 'Oort Cloud', 'Asteroid Belt', 'Kuiper Belt', 'Exosphere', 2],
        ['Solar System Basics', 'What percentage of the solar system\'s mass is the Sun?', '50%', '75%', '90%', '99.8%', 3],
        ['Solar System Basics', 'Which planet takes 165 Earth years to orbit the Sun once?', 'Saturn', 'Uranus', 'Neptune', 'Pluto', 2],
        ['Solar System Basics', 'Which is the least dense planet (it could float in water)?', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 1],
        ['Solar System Basics', 'What gives Mars its red color?', 'Lava', 'Iron Oxide (Rust)', 'Red dust clouds', 'Copper deposits', 1],
        ['Solar System Basics', 'Which is the only planet not named after a Roman or Greek god?', 'Earth', 'Uranus', 'Neptune', 'Saturn', 0],
        ['Solar System Basics', 'What defines the edge of the solar system\'s magnetic influence?', 'Kuiper Belt', 'Oort Cloud', 'Heliopause', 'Termination Shock', 2],
        ['Solar System Basics', 'How many dwarf planets are officially recognized by the IAU?', '3', '5', '9', 'Hundreds', 1],
        ['Solar System Basics', 'What is the Sun primarily composed of?', 'Oxygen and Nitrogen', 'Iron and Nickel', 'Helium and Carbon', 'Hydrogen and Helium', 3],
        ['Solar System Basics', 'Which is the fastest orbiting planet?', 'Mercury', 'Venus', 'Earth', 'Jupiter', 0],
        ['Solar System Basics', 'What causes the seasons on Earth?', 'Distance from the sun', 'Axial tilt', 'Solar flares', 'Ocean currents', 1],
        ['Solar System Basics', 'What was the first planet discovered using a telescope?', 'Saturn', 'Uranus', 'Neptune', 'Jupiter', 1]
    ];

    $db->beginTransaction();
    $stmt = $db->prepare("INSERT INTO quiz_questions (category, question, option_0, option_1, option_2, option_3, correct_index) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($questions as $q) {
        $stmt->execute($q);
    }
    $db->commit();

    echo "<h3>Successfully loaded 100 questions into the database!</h3>";

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}
?>