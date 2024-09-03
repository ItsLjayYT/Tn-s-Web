<?php
define("IntigrityKey", "K8fX5Z2tP7rL3mQ1vW9y"); // mod menu intigrity
header('Content-Type: application/json');

function parseSpecialChars($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function decodeKeyFromUrl($key) {
    return urldecode($key);
}

$key = isset($_GET['key']) ? decodeKeyFromUrl(parseSpecialChars($_GET['key'])) : '';
$integrityKey = isset($_GET['integrityKey']) ? parseSpecialChars($_GET['integrityKey']) : '';
$response = array("Status" => "Failed", "MessageString" => "404", "Username" => "");


// key verification
// database request only open if your intigrity key is valid
if ($integrityKey == IntigrityKey) { 
    include "cred/_dbconnect.php";
    $sql = "SELECT * FROM `userkeys` WHERE `CreatedKeys` = '$key'";
    $result = $dbconnect->query($sql);
    
    if ($result->num_rows > 0) {
        $exprow = $result->fetch_assoc();
        $expdate = $exprow['EndDate'];

        if(strtotime(date("Y-m-d")) <= strtotime($expdate)) {
            $response["Status"] = "Success_TN_Login";
            $response["MessageString"] = "Log in Success Your key is valid till ".$expdate;
            $response["Username"] = $key; 
        } else {
            $response["Status"] = "Failed";
            $response["MessageString"] = "Logged in failed Your key was expired on ".$expdate;
            $response["Username"] = ""; 
        }

    } else {
        $response["Status"] = "Failed";
        $response["MessageString"] = "Invalid Key";
        $response["Username"] = ""; 
    }

} else {
    $response["Status"] = "Failed";
    $response["MessageString"] = "Can't Verify Your Mod Menu ";
    $response["Username"] = $key; 
}

echo json_encode($response);
?>