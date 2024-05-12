<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAMPO</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" href="/images/Masseylogo.png" type="image/x-icon"> <!-- Favicon link -->

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
</head>
<body style='background: #ffffff'>
<div class="container">
    <img src="images/Masseylogo.jpg" alt="Massey Logo" class="massey-logo">
    <div class="social-icons">
        <a href="https://www.instagram.com/masseyfergusonsouthafrica/" target="_blank"><i class="fab fa-instagram"></i></a>
        <a href="https://www.facebook.com/MasseyFergusonSA" target="_blank"><i class="fab fa-facebook-f"></i></a>
    </div>
    <div class="welcome-text">NAMPO 2024</div>
</div>
<?php //error_reporting(E_ALL);
//ini_set('display_errors', 1); ?>
<?php include('apis.php'); ?>

<?php if (!empty($showVideos) && (!empty($chanelData))): ?>

    <?php foreach ($chanelData as $chanel): ?>
        <iframe src="<?= $chanel ?>"  scrolling="no" class='iframe-main'></iframe>
    <?php endforeach; ?>

<?php endif; ?>

<div id="map" style="border: darkgrey 0 solid">
</div>
<script>
    // Initialize the map and set its view
    const map = L.map('map').setView([-27.233164, 26.666305], 17);

    // Define the car icon
    const carIcon = L.icon({
        iconUrl: 'images/car-image.png',
        iconSize: [38, 38], // size of the icon
        iconAnchor: [19, 19]// point of the icon which will correspond to marker's location
    });

    //Add a tile layer to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 17,
        minZoom: 15,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    //
    googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}',{
        maxZoom: 20,
        subdomains:['mt0','mt1','mt2','mt3']
    });
    googleStreets.addTo(map);

    // googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    //     maxZoom: 20,
    //     subdomains:['mt0','mt1','mt2','mt3']
    // });
    // googleSat.addTo(map);


    // Add the marker with the car icon
    const marker = L.marker([-27.233164, 26.666305], { icon: carIcon }).addTo(map);

    // Function to fetch data from the API
    async function fetchData() {
        try {
            const response = await fetch('map_api.php');
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            const newLat = data.lat;
            const newLng = data.lng;

            // Update the marker's position
            marker.setLatLng([newLat, newLng]);
            map.panTo(new L.LatLng(newLat, newLng));
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    // Fetch data initially and then every 10 seconds
    window.onload = () => {
        fetchData();
        setInterval(fetchData, 10000);
    };
</script>

</body>

</html>
