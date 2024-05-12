<?php
include 'functions.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login = 'farmspace01';
$pass = 'farmspace01';
$sessionId = '';
$vehicleId = '';
$showVideos = false;
$channels = [0, 1, 2, 3, 4];
$channelData = [];


$loginApi = "https://fleetcamonline.com/StandardApiAction_login.action?account=$login&password=$pass";

if (isResultReceived($loginApi, 'Wrong credentials')) {
    $sessionId = getFieldFromResult("https://fleetcamonline.com/StandardApiAction_login.action?account=$login&password=$pass", 'jsession');
    $vehicleApi = "https://fleetcamonline.com/StandardApiAction_queryUserVehicle.action?jsession=$sessionId&language=en";
    if (isResultReceived($vehicleApi, 'No sessionId received')) {
        $vehicleId = getFieldFromResult($vehicleApi, 'vehicles', 'nm');

        foreach ($channels as $chanel) {
//            isResultReceived("https://fleetcamonline.com/808gps/open/player/video.html?lang=en&
//jsession=$sessionId&vehiIdno=$vehicleId&channel=1&chns=$chanel", 'Channel ' . $chanel . ' is empty');

            $chanelData[] = "https://fleetcamonline.com/808gps/open/player/video.html?lang=en&
jsession=$sessionId&vehiIdno=$vehicleId&channel=1&chns=$chanel";
        }
        $showVideos = true;
    }
}
