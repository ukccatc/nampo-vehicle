<?php
header('Content-Type: application/json');
include 'functions.php';
$login = 'farmspace01';
$pass = 'farmspace01';
$sessionId = '';
$vehicleId = '';
$data = [];

$loginApi = "https://fleetcamonline.com/StandardApiAction_login.action?account=$login&password=$pass";

if (isResultReceived($loginApi, 'Wrong credentials')) {
    $sessionId = getFieldFromResult("https://fleetcamonline.com/StandardApiAction_login.action?account=$login&password=$pass", 'jsession');
    $vehicleApi = "https://fleetcamonline.com/StandardApiAction_queryUserVehicle.action?jsession=$sessionId&language=en";
    if (isResultReceived($vehicleApi, 'No sessionId received')) {
        $vehicleId = getFieldFromResult($vehicleApi, 'vehicles', 'nm');
        $deviceId = getFieldFromResult($vehicleApi, 'vehicles', 'dl', 'id');
        $deviceGPS = "https://fleetcamonline.com/StandardApiAction_getDeviceStatus.action?jsession=$sessionId&devIdno=$deviceId&toMap=1&language=en";
        if (isResultReceived($deviceGPS, 'No GPS data received')) {
            $data['lat'] = getFieldFromResult($deviceGPS, 'status', 'mlat');
            $data['lng'] = getFieldFromResult($deviceGPS, 'status', 'mlng');
            $data['speed'] = getFieldFromResult($deviceGPS, 'status', 'sp');
            $data['direction'] = getFieldFromResult($deviceGPS, 'status', 'hx');
            $data['park'] = getFieldFromResult($deviceGPS, 'status', 'pk');
            $data['mileage'] = getFieldFromResult($deviceGPS, 'status', 'lc');
            $data['online'] = getFieldFromResult($deviceGPS, 'status', 'ol');
        }
    }
}

echo json_encode($data);
