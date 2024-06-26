<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAMPO</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" href="images/Masseylogo.jpg" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style='background-color: #ffffff'>
<nav class="navbar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <a class="navbar-brand" href="#">
                    <img src="images/Masseylogo.jpg" alt="Logo" class="d-inline-block align-text-top massey-logo">
                </a>
            </div>
        </div>
        <div class="row">

        </div>
        <div class="row">
            <div class="col-2 align-content-center">
                <a data-mdb-ripple-init class="btn btn-light social" target="_blank" style="background-color: #3b5998;" href="https://www.facebook.com/MasseyFergusonSA" role="button">
                    <i class="fab fa-facebook-f" style="color: white"></i>
                </a>
            </div>
            <div class="col-2 align-content-center">
                <a data-mdb-ripple-init class="btn btn-light instagram social" target="_blank" style="background-color: #E1306C;" href="https://www.instagram.com/masseyfergusonsouthafrica/" role="button">
                    <i class="fab fa-instagram" style="color: white"></i>
                </a>
            </div>
            <div class="col-8">
                <div class="welcome-text">NAMPO 2024</div>
            </div>
        </div>
    </div>
</nav>
<?php error_reporting(E_ALL);
ini_set('display_errors', 1); ?>
<?php include('apis.php'); ?>

<div class="dimmed">
    <!-- This div has the class "dimmed" -->

    <div class="d-flex flex-column align-items-center text-center" style="height: 80%">

        <img src="images/logo_no_bg.png" alt="alternative-text">

        <br><br><br>
        <b style="font-size: 32px"> Thank you for your support at NAMPO 2024</b>
        <br><br>

        <div class="justify-content-center" style="font-size: 26px">For more information on Massey Ferguson products follow the link below:</div>
        <br><br><br>

        <a href="https://www.agcocorp.com/" target="_blank" class="btn agco-button" style="font-family: Helvetica Neue\ 57, Helvetica, Arial, sans-serif !important;">Find out more</a>
    </div>
</div>

<?php if (!empty($showVideos) && (!empty($chanelData))): ?>
    <div class="container-fluid">
        <div class="row p-1">
            <div class="col-sm p-1">
                <iframe src="<?= $chanelData[0] ?>"   class='iframe-main'></iframe><br>
                <iframe src="<?= $chanelData[3] ?>"   class='iframe-main'></iframe>
            </div>
            <div class="col-sm mt-3 mt-sm-0 p-1">
                <iframe src="<?= $chanelData[1] ?>"   class='iframe-secondary'></iframe><br>
                <iframe src="<?= $chanelData[2] ?>"   class='iframe-secondary'></iframe><br>
                <iframe src="<?= $chanelData[4] ?>"   class='iframe-secondary'></iframe>
            </div>
            <div class="col-sm mt-1 mt-sm-0 p-1">
                <div id="map" style="border: darkgrey 0 solid"></div>
                <div id="telemetry" class="border border-light border-4 p-3 text-bg-light mt-3">
                    <p align="center" class="h5">Telemetry</p>
                    <p><b></b><span id="tel0">Updating..</span></b></p>
                    <p><b>Parked: </b><span id="tel3">Updating..</span></p>
                    <p><b>Speed: </b><span id="tel1">Updating..</span></p>
                    <p><b>Direction: </b><span id="tel2">Updating..</span></p>
                    <p><b>Mileage: </b><span id="tel4">Updating..</span></p>
                </div>

            </div>
        </div>
    </div>

<?php endif; ?>
<script>
    // Initialize the map and set its view
    const map = L.map('map').setView([-27.233164, 26.666305], 17);

    // Define the car icon
    const carIcon = L.icon({
        iconUrl: 'images/car-image.png',
        iconSize: [45, 35], // size of the icon
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

            function degToCompass(num) {
                var val = Math.floor((num / 22.5) + 0.5);
                var arr = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW"];
                return arr[(val % 16)];
            }

            //Insert telemetry
            console.log(data);
            document.getElementById('tel0').innerHTML = data.online === 1 ? 'Online' : 'Offline';
            document.getElementById('tel1').innerHTML = data.speed/10 + ' km/h';
            document.getElementById('tel2').innerHTML = degToCompass(data.direction);
            document.getElementById('tel3').innerHTML = Math.floor(data.park/60) + ' min';
            document.getElementById('tel4').innerHTML = data.mileage/1000 + ' km';


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
        setInterval(fetchData, 5000);
    };
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
