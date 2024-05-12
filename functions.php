<?php

//Functions
function isResultReceived($api, $errorMessage)
{
    $content = file_get_contents($api);
    if ($content === false) {
        echo 'Error: Unable to fetch data from API';
        die;
    }
    $result = json_decode($content, true);
    if ($result['result'] == 1 || (!empty($result['result']))) {
        echo "<p style='color:red; text-align: center'>$errorMessage</p>";
        die();
    }
    return true;
}

function getFieldFromResult($api, $variable, $variable1 = null, $variable2 = null){
    $content = file_get_contents($api);
    if ($variable2 != null) {
        return json_decode($content, true)[$variable][0][$variable1][0][$variable2];
    }
    if ($variable1 != null) {
        return json_decode($content, true)[$variable][0][$variable1];
    }
    return json_decode($content, true)[$variable];
}

function getFullResult($api)
{
    $content = file_get_contents($api);
    return json_decode($content, true);
}