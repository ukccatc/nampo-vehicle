<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NAMPO</title>
    <link rel="stylesheet" href="main.css">
    <link rel="icon" href="/images/Masseylogo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body style='background-color: #ffffff'>
<nav class="navbar" style="">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="images/Masseylogo.jpg" alt="Logo" class="d-inline-block align-text-top massey-logo">
        </a>
        <div class="welcome-text">NAMPO 2024</div>
    </div>
</nav>
<?php //error_reporting(E_ALL);
//ini_set('display_errors', 1); ?>
<?php include('apis.php'); ?>

<?php if (!empty($showVideos) && (!empty($chanelData))): ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm">
                <iframe src="<?= $chanelData[0] ?>"  scrolling="no" class='iframe-main'></iframe><br>
                <iframe src="<?= $chanelData[3] ?>"  scrolling="no" class='iframe-main'></iframe>
            </div>
            <div class="col-sm">
                <iframe src="<?= $chanelData[1] ?>"  scrolling="no" class='iframe-secondary'></iframe><br>
                <iframe src="<?= $chanelData[2] ?>"  scrolling="no" class='iframe-secondary'></iframe><br>
                <iframe src="<?= $chanelData[4] ?>"  scrolling="no" class='iframe-secondary'></iframe>
            </div>
            <div class="col-sm">
                <div id="map" style="border: darkgrey 0 solid"></div>
                <br>
                <div id="telemetry" class="border border-dark p-3">
                    <p align="center" class="h4">Telemetry</p>
                    <p><b>Online: </b><span id="tel0">Updating..</span></p>
                    <p><b>Parked: </b><span id="tel3">Updating..</span></p>
                    <p><b>Speed: </b><span id="tel1">Updating..</span></p>
                    <p><b>Direction: </b><span id="tel2">Updating..</span></p>
                    <p><b>Mileage: </b><span id="tel4">Updating..</span></p>
                </div>

            </div>
        </div>
    </div>

<!--    --><?php //foreach ($chanelData as $chanel): ?>
<!--        <iframe src="--><?php //= $chanel ?><!--"  scrolling="no" class='iframe-main'></iframe>-->
<!--    --><?php //endforeach; ?>

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
            console.log(data);
            //Insert telemetry

            document.getElementById('tel0').innerHTML = data.online === 1 ? 'Online' : 'Offline';
            document.getElementById('tel1').innerHTML = data.speed + ' km/h';
            document.getElementById('tel2').innerHTML = data.direction + '° deg';
            document.getElementById('tel3').innerHTML = data.park/60 + ' min';
            document.getElementById('tel4').innerHTML = data.mileage + ' miles';


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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
