<?php
require_once "db_connect.php";

//REGISTER 
// Initialize curl session
$curl = curl_init();

if (isset($_POST["register"])) {
    $playername = strtoupper($_POST["name"]);
    // Data to be sent via POST for registration
    $postData = array(
        "symbol" => $playername,
        "faction" => "COSMIC",
    );
}

// Set curl options for a POST request
curl_setopt($curl, CURLOPT_URL, "https://api.spacetraders.io/v2/register");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

//execute the curl session
$response = curl_exec($curl);

//convert response to PHP array
$responseData = json_decode($response, true); 

$token = "";
// Check if the token is received
if (isset($responseData['token'])) {
    // Save this token for future requests
    $token = $responseData['token'];
} else {
    // Handle error; token not received
    echo "Error: Token not received from API.";
}

//SET THE TOKEN
// Set the token in the Authorization header
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
];

// Initialize curl session
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.spacetraders.io/v2/my/agent');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

// Execute curl session
$response = curl_exec($curl);

// Close curl session
curl_close($curl);

//ERITE IT INTO DATABASE
$sql = "INSERT INTO `players`(`player_name`, `player_token`) VALUES ('$playername','$token')";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) !== 0) {
    $row = mysqli_fetch_assoc($result);
    if($row["user_status"] === "user") {
        // here you set var for the session so you can reach the datas in the session
        $_SESSION["user"] = $row["user_id"]; 
        $_SESSION["img"] = $row["user_img"];
        header("Location: /BE20_CR5_SahraStursa/index.php");
    } else if($row["user_status"] === "adm") {
        // here you set var for the session so you can reach the datas in the session
        $_SESSION["adm"] = $row["user_id"]; 
        $_SESSION["img"] = $row["user_img"];
        header("Location: /BE20_CR5_SahraStursa/index.php");
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 
</head>
<body>

<h1>Sign Up</h1>
    <div class="container">
        <div class="form">
            <form method="POST" enctype="multipart/form-data">
                <label>
                    <h3>Username</h3>
                    <input type="name" name="name" class="form-control">
                </label>
                <div class="buttonForm">
                    <input type="submit" value="Register" name="register" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</body>
</html>


